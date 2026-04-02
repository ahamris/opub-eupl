<?php

namespace App\Services\Raadsinformatie;

use App\Models\OpenOverheidDocument;
use Illuminate\Support\Facades\Log;

readonly class RaadsinformatieSyncService
{
    public function __construct(
        private RaadsinformatieSearchService $searchService
    ) {}

    /**
     * Sync recent documents (last N days).
     */
    public function syncRecent(int $daysBack = 7, $command = null): array
    {
        $from = now()->subDays($daysBack)->toISOString();
        $to = now()->toISOString();

        return $this->syncByDateRange($from, $to, $command);
    }

    /**
     * Sync all documents from ORI.
     */
    public function syncAll($command = null): array
    {
        return $this->syncByDateRange(null, null, $command);
    }

    /**
     * Sync documents filtered by a specific municipality/index pattern.
     */
    public function syncByIndex(string $indices, $command = null): array
    {
        return $this->syncByDateRange(null, null, $command, $indices);
    }

    /**
     * Core sync method — scrolls through ORI Elasticsearch results and upserts
     * documents into the OpenOverheidDocument table.
     */
    public function syncByDateRange(
        ?string $from = null,
        ?string $to = null,
        $command = null,
        ?string $indices = null,
    ): array {
        if (! config('open_overheid.raadsinformatie.enabled', false)) {
            Log::channel('sync_errors')->info('ORI sync is disabled');
            $command?->info('ORI sync is disabled. Set ORI_SYNC_ENABLED=true in .env');

            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        $dateRange = $this->formatDateRangeLabel($from, $to);
        Log::channel('sync_errors')->info("Starting ORI sync for: {$dateRange}");

        // Get total count first
        $totalCount = $this->searchService->getTotalCount($indices);
        $command?->info("Found approximately {$totalCount} documents in ORI.");

        // Pre-load existing ORI documents for fast skip comparison
        $existingDocs = $this->loadExistingOriDocs();
        if ($command && ! empty($existingDocs)) {
            $command->line('   Loaded ' . count($existingDocs) . ' existing ORI docs for smart-skip');
        }

        $batchSize = config('open_overheid.raadsinformatie.batch_size', 100);
        $delayMs = config('open_overheid.raadsinformatie.delay_between_requests', 200);
        $offset = 0;
        $stats = ['synced' => 0, 'created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0];
        $processed = 0;
        $hasMore = true;

        // Elasticsearch has a 10,000 result window limit by default
        $maxResults = min($totalCount, 10000);

        if ($command && $maxResults > 0) {
            $bar = $command->getOutput()->createProgressBar($maxResults);
            $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s% %memory:6s%');
            $bar->start();
        }

        while ($hasMore && $offset < $maxResults) {
            try {
                $response = $this->searchService->searchDocuments(
                    text: '',
                    dateFrom: $from,
                    dateTo: $to,
                    from: $offset,
                    size: $batchSize,
                    indices: $indices,
                );

                $hits = $response['hits']['hits'] ?? [];

                if (empty($hits)) {
                    $hasMore = false;
                    break;
                }

                foreach ($hits as $hit) {
                    try {
                        $result = $this->upsertFromHit($hit, $existingDocs);

                        match ($result) {
                            'created' => $stats['created']++ && $stats['synced']++,
                            'updated' => $stats['updated']++ && $stats['synced']++,
                            'skipped' => $stats['skipped']++,
                            default => null,
                        };

                        // Increment synced for created/updated (the && trick above may not work)
                        if ($result === 'created' || $result === 'updated') {
                            $stats['synced'] = $stats['created'] + $stats['updated'];
                        }
                    } catch (\Exception $e) {
                        Log::channel('sync_errors')->error('ORI sync error for document', [
                            'hit_id' => $hit['_id'] ?? 'unknown',
                            'index' => $hit['_index'] ?? 'unknown',
                            'error' => $e->getMessage(),
                        ]);
                        $stats['errors']++;
                    }

                    $processed++;
                    if (isset($bar)) {
                        $bar->advance();
                    }
                }

                $offset += count($hits);
                $hasMore = count($hits) === $batchSize;

                if ($delayMs > 0) {
                    usleep($delayMs * 1000);
                }
            } catch (\Exception $e) {
                Log::channel('sync_errors')->error('ORI sync error on batch', [
                    'offset' => $offset,
                    'exception' => $e->getMessage(),
                ]);
                $stats['errors']++;
                $hasMore = false;
            }
        }

        if (isset($bar)) {
            $bar->finish();
            $command?->newLine(2);
        }

        Log::channel('sync_errors')->info('ORI sync completed', [
            'date_range' => $dateRange,
            ...$stats,
        ]);

        return [
            'total' => $maxResults,
            ...$stats,
        ];
    }

    /**
     * Map an Elasticsearch hit to an OpenOverheidDocument and upsert it.
     *
     * @return string 'created'|'updated'|'skipped'
     */
    protected function upsertFromHit(array $hit, array $existingDocs): string
    {
        $source = $hit['_source'] ?? [];
        $esId = $hit['_id'] ?? null;
        $index = $hit['_index'] ?? null;

        if (! $esId) {
            return 'skipped';
        }

        // Prefix with ori- to avoid collisions with open.overheid.nl documents
        $externalId = 'ori-' . $esId;

        // Extract title — try multiple fields
        $title = $source['title'] ?? $source['name'] ?? null;
        if (is_array($title)) {
            $title = implode(' ', array_filter($title));
        }
        if ($title && mb_strlen($title) > 1000) {
            $title = mb_substr($title, 0, 1000);
        }

        // Extract text content
        $content = $source['text'] ?? null;
        if (is_array($content)) {
            // ORI v1 stores text as paginated arrays
            $content = implode("\n\n", array_filter($content));
        }

        // Extract description
        $description = $source['description'] ?? null;
        if (is_array($description)) {
            $description = implode(' ', array_filter($description));
        }

        // Extract date
        $publicationDate = $this->parseDate(
            $source['last_discussed_at'] ?? $source['start_date'] ?? null
        );

        // Extract organisation from the index name (e.g. ori_amsterdam_* → Amsterdam)
        $organisation = $this->extractOrganisation($source, $index);

        // Map @type to document_type
        $type = $source['@type'] ?? null;
        $documentType = $this->mapDocumentType($type);

        // Classification as category
        $classification = $source['classification'] ?? null;
        if (is_array($classification)) {
            $classification = $classification[0] ?? null;
        }

        // Store ORI-specific metadata
        $metadata = [
            'ori_source' => 'raadsinformatie',
            'ori_id' => $esId,
            'ori_index' => $index,
            'ori_type' => $type,
            'ori_classification' => $source['classification'] ?? null,
            'ori_organization_id' => $source['organization_id'] ?? null,
            'ori_sources' => $source['sources'] ?? null,
        ];

        // Check if we already have this document
        if (isset($existingDocs[$externalId])) {
            $existing = OpenOverheidDocument::where('external_id', $externalId)->first();

            if ($existing) {
                $hasChanged = $existing->title !== $title
                    || $existing->description !== $description
                    || $existing->publication_date?->format('Y-m-d') !== $publicationDate;

                if (! $hasChanged) {
                    $existing->update(['synced_at' => now()]);

                    return 'skipped';
                }

                $existing->update([
                    'title' => $title,
                    'description' => $description,
                    'content' => $content,
                    'publication_date' => $publicationDate,
                    'document_type' => $documentType,
                    'category' => $classification,
                    'organisation' => $organisation,
                    'metadata' => $metadata,
                    'synced_at' => now(),
                ]);

                return 'updated';
            }
        }

        OpenOverheidDocument::create([
            'external_id' => $externalId,
            'title' => $title,
            'description' => $description,
            'content' => $content,
            'publication_date' => $publicationDate,
            'document_type' => $documentType,
            'category' => $classification,
            'organisation' => $organisation,
            'metadata' => $metadata,
            'synced_at' => now(),
        ]);

        return 'created';
    }

    /**
     * Extract the organisation name from the source data or index name.
     */
    protected function extractOrganisation(array $source, ?string $index): ?string
    {
        // Prefer explicit organization field
        if (! empty($source['organization'])) {
            $org = $source['organization'];

            return is_array($org) ? ($org['name'] ?? json_encode($org)) : $org;
        }

        // Fall back to extracting from index name: ori_amsterdam_20190809 → Amsterdam
        if ($index && preg_match('/^ori_([^_]+)/', $index, $matches)) {
            return ucfirst(str_replace('_', ' ', $matches[1]));
        }

        return null;
    }

    /**
     * Map ORI @type values to human-readable document types.
     */
    protected function mapDocumentType(?string $type): ?string
    {
        if (! $type) {
            return 'Raadsinformatie';
        }

        return match (true) {
            str_contains($type, 'AgendaItem') => 'Agendapunt',
            str_contains($type, 'Meeting') => 'Vergadering',
            str_contains($type, 'Document'), str_contains($type, 'MediaObject') => 'Raadsdocument',
            str_contains($type, 'Person') => 'Persoon',
            str_contains($type, 'Organization') => 'Organisatie',
            str_contains($type, 'Motion') => 'Motie',
            str_contains($type, 'VoteEvent') => 'Stemming',
            default => 'Raadsinformatie',
        };
    }

    /**
     * Parse date string to Y-m-d format.
     */
    protected function parseDate(mixed $date): ?string
    {
        if (! $date) {
            return null;
        }

        if (is_array($date)) {
            $date = $date[0] ?? null;
        }

        if (! is_string($date)) {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Pre-load existing ORI documents for fast lookups.
     */
    protected function loadExistingOriDocs(): array
    {
        $map = [];

        OpenOverheidDocument::select('external_id', 'synced_at')
            ->where('external_id', 'like', 'ori-%')
            ->chunk(5000, function ($docs) use (&$map) {
                foreach ($docs as $doc) {
                    $map[$doc->external_id] = $doc->synced_at?->timestamp;
                }
            });

        return $map;
    }

    /**
     * Format a human-readable date range label for logging.
     */
    protected function formatDateRangeLabel(?string $from, ?string $to): string
    {
        if ($from && $to) {
            return "{$from} to {$to}";
        }
        if ($from) {
            return "from {$from}";
        }
        if ($to) {
            return "until {$to}";
        }

        return 'all';
    }
}
