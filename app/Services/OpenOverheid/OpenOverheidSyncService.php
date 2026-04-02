<?php

namespace App\Services\OpenOverheid;

use App\DataTransferObjects\OpenOverheid\OpenOverheidSearchQuery;
use App\Models\OpenOverheidDocument;
use Illuminate\Support\Facades\Log;

readonly class OpenOverheidSyncService
{
    public function __construct(
        private OpenOverheidSearchService $searchService
    ) {}

    /**
     * Sync recent documents (last N days)
     */
    public function syncRecent(int $daysBack = 7, $command = null): array
    {
        $from = now()->subDays($daysBack)->format('d-m-Y');
        $to = now()->format('d-m-Y');

        return $this->syncByDateRange($from, $to, $command);
    }

    /**
     * Perform a full sync of all documents.
     */
    public function syncAll($command = null): array
    {
        return $this->syncByDateRange(null, null, $command);
    }

    /**
     * Sync a single document by its external ID.
     */
    public function syncDocument(string $externalId): void
    {
        try {
            $documentData = $this->searchService->getDocument($externalId);
            $this->upsertDocument($externalId, $documentData);
        } catch (\Exception $e) {
            Log::channel('sync_errors')->error('Open Overheid sync error for single document', [
                'external_id' => $externalId,
                'exception' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Core sync method — handles both date-range and full syncs.
     *
     * Optimizations over original:
     * 1. Smart skip: compares mutatiedatumtijd from search results against synced_at
     *    to skip the expensive detail API call for unchanged documents.
     * 2. Pre-loads existing document index for O(1) lookups instead of per-doc DB queries.
     * 3. DRY: syncAll() delegates here instead of duplicating 200 lines.
     */
    public function syncByDateRange(?string $from = null, ?string $to = null, $command = null): array
    {
        if (! config('open_overheid.sync.enabled', true)) {
            Log::channel('sync_errors')->info('Open Overheid sync is disabled');
            $command?->info('Open Overheid sync is disabled.');
            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        $dateRange = $from && $to ? "{$from} to {$to}" : ($from ? "from {$from}" : ($to ? "until {$to}" : 'all'));
        Log::channel('sync_errors')->info("Starting Open Overheid sync for: {$dateRange}");

        // Get total count
        $firstQuery = new OpenOverheidSearchQuery(
            zoektekst: '',
            page: 1,
            perPage: 50,
            publicatiedatumVan: $from,
            publicatiedatumTot: $to,
        );

        $firstResponse = $this->searchService->search($firstQuery);
        $totalResults = $firstResponse['totaal'] ?? $firstResponse['total'] ?? 0;

        if ($command) {
            $command->info("Found {$totalResults} documents to sync.");
            $command->newLine();
        }

        // Pre-load existing documents for fast change detection.
        // Maps external_id => synced_at timestamp so we can skip unchanged docs
        // without making the expensive detail API call.
        $existingDocs = $this->loadExistingDocIndex($from, $to);

        if ($command && ! empty($existingDocs)) {
            $command->line('   Loaded ' . count($existingDocs) . ' existing docs for smart-skip comparison');
            $command->newLine();
        }

        $page = 1;
        $perPage = 50;
        $stats = ['synced' => 0, 'created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0, 'skipped_early' => 0];
        $hasMorePages = true;
        $processed = 0;
        $failedDocuments = [];
        $delayMs = config('open_overheid.sync.delay_between_requests', 50);

        if ($command && $totalResults > 0) {
            $bar = $command->getOutput()->createProgressBar($totalResults);
            $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s% %memory:6s%');
            $bar->start();
        }

        while ($hasMorePages) {
            try {
                $query = new OpenOverheidSearchQuery(
                    zoektekst: '',
                    page: $page,
                    perPage: $perPage,
                    publicatiedatumVan: $from,
                    publicatiedatumTot: $to,
                );

                $response = $this->searchService->search($query);
                $items = $response['resultaten'] ?? $response['items'] ?? $response['data'] ?? [];

                if (empty($items)) {
                    $hasMorePages = false;
                    break;
                }

                foreach ($items as $item) {
                    try {
                        $document = $item['document'] ?? $item;
                        $externalId = $this->extractExternalId($document);

                        if (! $externalId) {
                            Log::channel('sync_errors')->warning('Open Overheid item missing ID', ['item' => $item]);
                            $stats['errors']++;
                            $processed++;
                            if ($command) { $bar->advance(); }
                            continue;
                        }

                        // OPTIMIZATION: Smart skip — check mutatiedatumtijd from search result
                        // against our synced_at. If the document hasn't been modified since
                        // our last sync, skip the expensive detail API call entirely.
                        $mutatieDatum = $document['mutatiedatumtijd'] ?? null;
                        if ($mutatieDatum && isset($existingDocs[$externalId])) {
                            $lastSynced = $existingDocs[$externalId];
                            $mutatie = strtotime($mutatieDatum);
                            if ($mutatie && $lastSynced && $mutatie <= $lastSynced) {
                                $stats['skipped']++;
                                $stats['skipped_early']++;
                                $processed++;
                                if ($command) {
                                    $this->updateProgressBar($bar, $processed, $totalResults, $stats, $command);
                                    $bar->advance();
                                }
                                continue;
                            }
                        }

                        // Document is new or modified — fetch detail
                        $documentData = $this->searchService->getDocument($externalId);

                        if ($delayMs > 0 && $totalResults > 1) {
                            usleep($delayMs * 1000);
                        }

                        $result = $this->upsertDocument($externalId, $documentData);

                        if ($result === 'created') {
                            $stats['created']++;
                            $stats['synced']++;
                        } elseif ($result === 'updated') {
                            $stats['updated']++;
                            $stats['synced']++;
                        } elseif ($result === 'skipped') {
                            $stats['skipped']++;
                        }

                        $processed++;

                        if ($command) {
                            $this->updateProgressBar($bar, $processed, $totalResults, $stats, $command);
                            $bar->advance();
                        }
                    } catch (\Exception $e) {
                        $exId = $this->extractExternalId($document ?? $item);

                        Log::channel('sync_errors')->error('Open Overheid sync error', [
                            'external_id' => $exId,
                            'error' => $e->getMessage(),
                        ]);

                        if ($exId) {
                            $failedDocuments[] = ['external_id' => $exId, 'error' => $e->getMessage()];
                        }

                        $stats['errors']++;
                        $processed++;

                        if ($command) {
                            $this->updateProgressBar($bar, $processed, $totalResults, $stats, $command);
                            $bar->advance();
                        }
                    }
                }

                $hasMorePages = count($items) === $perPage && ($page * $perPage) < $totalResults;
                $page++;
            } catch (\Exception $e) {
                Log::channel('sync_errors')->error('Open Overheid sync error on page', [
                    'page' => $page,
                    'exception' => $e->getMessage(),
                ]);
                $stats['errors']++;
                $hasMorePages = false;
                break;
            }
        }

        if ($command && $totalResults > 0) {
            $bar->finish();
            $command->newLine();
            $command->getOutput()->write("\r\033[K");
            $command->newLine();
        }

        // Retry failed documents
        $retriedCount = 0;
        if (! empty($failedDocuments) && $command && ! $command->option('no-retry')) {
            $command->info('Retrying ' . count($failedDocuments) . ' failed documents...');
            $command->newLine();

            $retryBar = $command->getOutput()->createProgressBar(count($failedDocuments));
            $retryBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%');
            $retryBar->start();

            foreach ($failedDocuments as $failed) {
                try {
                    $documentData = $this->searchService->getDocument($failed['external_id']);
                    $result = $this->upsertDocument($failed['external_id'], $documentData);
                    if ($result !== 'skipped') {
                        $retriedCount++;
                        $stats['synced']++;
                        $stats[$result === 'created' ? 'created' : 'updated']++;
                    }
                    $stats['errors']--;
                } catch (\Exception $e) {
                    Log::channel('sync_errors')->error('Retry failed', [
                        'external_id' => $failed['external_id'],
                        'error' => $e->getMessage(),
                    ]);
                }
                $retryBar->advance();
            }

            $retryBar->finish();
            $command->newLine(2);

            if ($retriedCount > 0) {
                $command->info("Retried and synced {$retriedCount} documents.");
            }
        }

        Log::channel('sync_errors')->info('Open Overheid sync completed', [
            'date_range' => $dateRange,
            'synced' => $stats['synced'],
            'created' => $stats['created'],
            'updated' => $stats['updated'],
            'skipped' => $stats['skipped'],
            'skipped_early' => $stats['skipped_early'],
            'errors' => $stats['errors'],
            'retried' => $retriedCount,
        ]);

        if ($command) {
            $command->newLine();
            if ($stats['skipped_early'] > 0) {
                $command->line("   Smart skip: {$stats['skipped_early']} docs skipped without API detail call");
            }
        }

        return [
            'total' => $totalResults,
            'synced' => $stats['synced'],
            'created' => $stats['created'],
            'updated' => $stats['updated'],
            'skipped' => $stats['skipped'],
            'errors' => $stats['errors'],
            'retried' => $retriedCount,
        ];
    }

    /**
     * Pre-load a map of external_id => synced_at timestamp for fast lookups.
     * This avoids the need for a detail API call + DB query per document
     * when the document hasn't changed.
     */
    protected function loadExistingDocIndex(?string $from, ?string $to): array
    {
        $query = OpenOverheidDocument::select('external_id', 'synced_at');

        if ($from) {
            $fromDate = \Carbon\Carbon::createFromFormat('d-m-Y', $from)?->format('Y-m-d');
            if ($fromDate) {
                $query->where('publication_date', '>=', $fromDate);
            }
        }
        if ($to) {
            $toDate = \Carbon\Carbon::createFromFormat('d-m-Y', $to)?->format('Y-m-d');
            if ($toDate) {
                $query->where('publication_date', '<=', $toDate);
            }
        }

        $map = [];
        $query->chunk(5000, function ($docs) use (&$map) {
            foreach ($docs as $doc) {
                $map[$doc->external_id] = $doc->synced_at ? $doc->synced_at->timestamp : null;
            }
        });

        return $map;
    }

    /**
     * Upsert document data to the database.
     *
     * @return string 'created'|'updated'|'skipped'
     */
    protected function upsertDocument(string $externalId, array $documentData): string
    {
        $document = $documentData['document'] ?? [];
        $versies = $documentData['versies'] ?? [];
        $classificatie = $document['classificatiecollectie'] ?? [];

        $title = $document['titelcollectie']['officieleTitel'] ?? null;
        if ($title && mb_strlen($title) > 1000) {
            $originalLength = mb_strlen($title);
            $title = mb_substr($title, 0, 1000);
            Log::channel('sync_errors')->warning('Open Overheid title truncated', [
                'external_id' => $externalId,
                'original_length' => $originalLength,
            ]);
        }

        $publicationDate = null;
        if (! empty($versies) && isset($versies[0]['openbaarmakingsdatum'])) {
            $publicationDate = $this->parseDate($versies[0]['openbaarmakingsdatum']);
        }

        $documentType = $classificatie['documentsoorten'][0]['label'] ?? null;
        $theme = $classificatie['themas'][0]['label'] ?? null;
        $category = $classificatie['informatiecategorieen'][0]['label'] ?? null;
        $organisation = $document['verantwoordelijke']['label'] ?? $document['publisher']['label'] ?? null;

        $description = null;
        if (! empty($document['omschrijvingen'][0])) {
            $description = is_string($document['omschrijvingen'][0])
                ? $document['omschrijvingen'][0]
                : ($document['omschrijvingen'][0]['tekst'] ?? null);
        }

        $normalizedMetadata = $this->normalizeMetadataForDatabase($documentData);

        // Build source URL to original document on open.overheid.nl
        $sourceUrl = null;
        $pid = $document['pid'] ?? null;
        if ($pid) {
            $sourceUrl = $pid;
        } elseif ($externalId) {
            $sourceUrl = 'https://open.overheid.nl/documenten/' . $externalId;
        }

        // Extract subjects/themes as array
        $subjects = [];
        $themas = $classificatie['themas'] ?? [];
        foreach ($themas as $thema) {
            if (isset($thema['label'])) {
                $subjects[] = $thema['label'];
            }
        }

        $data = [
            'external_id' => $externalId,
            'title' => $title,
            'description' => $description,
            'content' => null,
            'publication_date' => $publicationDate,
            'document_type' => $documentType,
            'category' => $category,
            'theme' => $theme,
            'subjects' => ! empty($subjects) ? $subjects : null,
            'organisation' => $organisation,
            'metadata' => $normalizedMetadata,
            'source_url' => $sourceUrl,
            'source_type' => 'open_overheid',
            'synced_at' => now(),
        ];

        $existing = OpenOverheidDocument::where('external_id', $externalId)->first();

        if ($existing) {
            $hasChanged = $existing->title !== $title
                || $existing->description !== $description
                || $existing->publication_date?->format('Y-m-d') !== $publicationDate
                || $existing->document_type !== $documentType
                || $existing->category !== $category
                || $existing->theme !== $theme
                || $existing->organisation !== $organisation;

            if (! $hasChanged) {
                // Update synced_at so future smart-skip works correctly
                $existing->update(['synced_at' => now()]);
                return 'skipped';
            }

            $existing->update($data);

            if ($this->isDossierDocument($existing)) {
                \App\Jobs\PrecomputeDossierMetadataJob::dispatch($externalId);
            }

            return 'updated';
        }

        $newDocument = OpenOverheidDocument::create($data);

        if ($this->isDossierDocument($newDocument)) {
            \App\Jobs\PrecomputeDossierMetadataJob::dispatch($externalId);
        }

        return 'created';
    }

    /**
     * Extract external ID from document data.
     */
    protected function extractExternalId(array $document): ?string
    {
        $id = $document['id'] ?? null;

        if ($id) {
            return preg_replace('/_\d+$/', '', $id);
        }

        $pid = $document['pid'] ?? '';
        if ($pid && preg_match('/\/([^\/]+)$/', $pid, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Parse date string to Y-m-d format.
     */
    protected function parseDate(?string $date): ?string
    {
        if (! $date) {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            Log::channel('sync_errors')->warning('Open Overheid date parse error', [
                'date' => $date,
                'exception' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Check if a document is part of a dossier.
     */
    protected function isDossierDocument(OpenOverheidDocument $document): bool
    {
        $documentrelaties = $document->metadata['documentrelaties'] ?? [];

        if (empty($documentrelaties) || ! is_array($documentrelaties)) {
            return false;
        }

        foreach ($documentrelaties as $relation) {
            if (str_contains($relation['role'] ?? '', 'identiteitsgroep')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalize metadata to ensure Unicode characters are properly encoded.
     */
    protected function normalizeMetadataForDatabase(array $metadata): array
    {
        $json = json_encode($metadata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return $metadata;
        }

        return json_decode($json, true) ?? $metadata;
    }

    /**
     * Update progress bar with stats and ETA.
     */
    protected function updateProgressBar($bar, int $processed, int $total, array $stats, $command): void
    {
        if ($processed === 0 || ! $command) {
            return;
        }

        $elapsed = time() - $bar->getStartTime();
        $eta = $elapsed > 0 && $processed > 0 && $processed < $total
            ? (int) (($elapsed / $processed) * ($total - $processed))
            : 0;

        $etaFormatted = $eta > 0 ? gmdate('H:i:s', $eta) : '--:--:--';

        $statusLine = "\n\033[K";

        if ($stats['skipped_early'] > 0) {
            $statusLine .= "\033[32mSmart skip: {$stats['skipped_early']}\033[0m  ";
        }
        if ($stats['skipped'] - $stats['skipped_early'] > 0) {
            $regular = $stats['skipped'] - $stats['skipped_early'];
            $statusLine .= "\033[36mSkipped: {$regular}\033[0m  ";
        }
        if ($stats['errors'] > 0) {
            $statusLine .= "\033[31mErrors: {$stats['errors']}\033[0m  ";
        }
        if ($stats['synced'] > 0) {
            $statusLine .= "\033[33mSynced: {$stats['synced']}\033[0m  ";
        }

        $statusLine .= "\033[38;5;208mETA: {$etaFormatted}\033[0m";
        $statusLine .= "\033[1A";

        $command->getOutput()->write($statusLine);
    }
}
