<?php

namespace App\Services\OpenOverheid;

use App\DataTransferObjects\OpenOverheid\OpenOverheidSearchQuery;
use App\Models\OpenOverheidDocument;
use Illuminate\Support\Facades\Log;

class OpenOverheidSyncService
{
    public function __construct(
        private readonly OpenOverheidSearchService $searchService
    ) {}

    /**
     * Sync recent documents (last 24 hours or configured days)
     */
    public function syncRecent(): void
    {
        $daysBack = config('open_overheid.sync.days_back', 1);
        $from = now()->subDays($daysBack)->format('d-m-Y');
        $to = now()->format('d-m-Y');

        $this->syncByDateRange($from, $to);
    }

    /**
     * Perform a sync of documents within a date range.
     *
     * @param  string|null  $from  Start date (DD-MM-YYYY format)
     * @param  string|null  $to  End date (DD-MM-YYYY format)
     */
    public function syncByDateRange(?string $from = null, ?string $to = null): void
    {
        if (! config('open_overheid.sync.enabled', true)) {
            Log::info('Open Overheid sync is disabled');

            return;
        }

        $dateRange = $from && $to ? "{$from} to {$to}" : ($from ? "from {$from}" : ($to ? "until {$to}" : 'all'));
        Log::info("Starting Open Overheid sync for date range: {$dateRange}");

        $page = 1;
        $perPage = 50; // Use maximum page size for efficiency
        $totalSynced = 0;
        $totalErrors = 0;
        $hasMorePages = true;

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

                // Extract items from response
                // Note: Actual structure may vary - adjust based on real API response
                $items = $response['resultaten'] ?? $response['items'] ?? $response['data'] ?? [];

                if (empty($items)) {
                    $hasMorePages = false;
                    break;
                }

                foreach ($items as $item) {
                    try {
                        // Extract external_id from the item
                        $document = $item['document'] ?? $item;
                        $externalId = $this->extractExternalId($document);

                        if (! $externalId) {
                            Log::warning('Open Overheid item missing ID', ['item' => $item]);
                            $totalErrors++;

                            continue;
                        }

                        // Fetch detailed document
                        $documentData = $this->searchService->getDocument($externalId);

                        // Sync the document
                        $this->upsertDocument($externalId, $documentData);
                        $totalSynced++;

                        // Log progress every 10 documents
                        if ($totalSynced % 10 === 0) {
                            Log::info("Open Overheid sync progress: {$totalSynced} documents synced");
                        }
                    } catch (\Exception $e) {
                        Log::error('Open Overheid sync error for document', [
                            'item' => $item,
                            'exception' => $e->getMessage(),
                        ]);
                        $totalErrors++;
                        // Continue with next document
                    }
                }

                // Check if there are more pages
                $totalResults = $response['totaal'] ?? $response['total'] ?? 0;
                $hasMorePages = count($items) === $perPage && ($page * $perPage) < $totalResults;

                $page++;
            } catch (\Exception $e) {
                Log::error('Open Overheid sync error on page', [
                    'page' => $page,
                    'exception' => $e->getMessage(),
                ]);
                $totalErrors++;
                $hasMorePages = false; // Stop on page-level errors
                break;
            }
        }

        Log::info('Open Overheid sync completed', [
            'date_range' => $dateRange,
            'total_synced' => $totalSynced,
            'total_errors' => $totalErrors,
        ]);
    }

    /**
     * Perform a full sync of all documents.
     * Fetches all documents from the search endpoint and syncs each one.
     */
    public function syncAll(): void
    {
        if (! config('open_overheid.sync.enabled', true)) {
            Log::info('Open Overheid sync is disabled');

            return;
        }

        Log::info('Starting Open Overheid full sync');

        $page = 1;
        $perPage = 50; // Use maximum page size for efficiency
        $totalSynced = 0;
        $totalErrors = 0;
        $hasMorePages = true;

        while ($hasMorePages) {
            try {
                $query = new OpenOverheidSearchQuery(
                    zoektekst: '',
                    page: $page,
                    perPage: $perPage,
                );

                $response = $this->searchService->search($query);

                // Extract items from response
                // Note: Actual structure may vary - adjust based on real API response
                $items = $response['resultaten'] ?? $response['items'] ?? $response['data'] ?? [];

                if (empty($items)) {
                    $hasMorePages = false;
                    break;
                }

                foreach ($items as $item) {
                    try {
                        // Extract external_id from the item
                        $document = $item['document'] ?? $item;
                        $externalId = $this->extractExternalId($document);

                        if (! $externalId) {
                            Log::warning('Open Overheid item missing ID', ['item' => $item]);
                            $totalErrors++;

                            continue;
                        }

                        // Fetch detailed document
                        $documentData = $this->searchService->getDocument($externalId);

                        // Sync the document
                        $this->upsertDocument($externalId, $documentData);
                        $totalSynced++;

                        // Log progress every 10 documents
                        if ($totalSynced % 10 === 0) {
                            Log::info("Open Overheid sync progress: {$totalSynced} documents synced");
                        }
                    } catch (\Exception $e) {
                        Log::error('Open Overheid sync error for document', [
                            'item' => $item,
                            'exception' => $e->getMessage(),
                        ]);
                        $totalErrors++;
                        // Continue with next document
                    }
                }

                // Check if there are more pages
                $totalResults = $response['totaal'] ?? $response['total'] ?? 0;
                $hasMorePages = count($items) === $perPage && ($page * $perPage) < $totalResults;

                $page++;
            } catch (\Exception $e) {
                Log::error('Open Overheid sync error on page', [
                    'page' => $page,
                    'exception' => $e->getMessage(),
                ]);
                $totalErrors++;
                $hasMorePages = false; // Stop on page-level errors
                break;
            }
        }

        Log::info('Open Overheid sync completed', [
            'total_synced' => $totalSynced,
            'total_errors' => $totalErrors,
        ]);
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
            Log::error('Open Overheid sync error for single document', [
                'external_id' => $externalId,
                'exception' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Upsert document data to the database.
     * Maps the detail endpoint response structure to our database fields.
     */
    protected function upsertDocument(string $externalId, array $documentData): void
    {
        $document = $documentData['document'] ?? [];
        $plooiIntern = $documentData['plooiIntern'] ?? [];
        $versies = $documentData['versies'] ?? [];
        $classificatie = $document['classificatiecollectie'] ?? [];

        // Extract title from titelcollectie.officieleTitel
        $title = $document['titelcollectie']['officieleTitel'] ?? null;

        // Extract publication date from first version's openbaarmakingsdatum
        $publicationDate = null;
        if (! empty($versies) && isset($versies[0]['openbaarmakingsdatum'])) {
            $publicationDate = $this->parseDate($versies[0]['openbaarmakingsdatum']);
        }

        // Extract document type from classificatiecollectie.documentsoorten
        $documentType = null;
        if (! empty($classificatie['documentsoorten']) && isset($classificatie['documentsoorten'][0]['label'])) {
            $documentType = $classificatie['documentsoorten'][0]['label'];
        }

        // Extract theme from classificatiecollectie.themas
        $theme = null;
        if (! empty($classificatie['themas']) && isset($classificatie['themas'][0]['label'])) {
            $theme = $classificatie['themas'][0]['label'];
        }

        // Extract information category from classificatiecollectie.informatiecategorieen
        $category = null;
        if (! empty($classificatie['informatiecategorieen']) && isset($classificatie['informatiecategorieen'][0]['label'])) {
            $category = $classificatie['informatiecategorieen'][0]['label'];
        }

        // Extract organisation from verantwoordelijke or publisher
        $organisation = $document['verantwoordelijke']['label']
            ?? $document['publisher']['label']
            ?? null;

        // Extract description from omschrijvingen (if available)
        $description = null;
        if (! empty($document['omschrijvingen']) && isset($document['omschrijvingen'][0])) {
            $description = is_string($document['omschrijvingen'][0])
                ? $document['omschrijvingen'][0]
                : ($document['omschrijvingen'][0]['tekst'] ?? null);
        }

        $data = [
            'external_id' => $externalId,
            'title' => $title,
            'description' => $description,
            'content' => null, // Content is typically in PDF files, not in API response
            'publication_date' => $publicationDate,
            'document_type' => $documentType,
            'category' => $category,
            'theme' => $theme,
            'organisation' => $organisation,
            'metadata' => $documentData, // Store full response for reference
            'synced_at' => now(),
        ];

        // Upsert the document
        OpenOverheidDocument::updateOrCreate(
            ['external_id' => $externalId],
            $data
        );
    }

    /**
     * Extract external ID from document data
     */
    protected function extractExternalId(array $document): ?string
    {
        // Try various possible locations
        $id = $document['id'] ?? null;

        if ($id) {
            // Remove version suffix (_1, _2, etc.)
            return preg_replace('/_\d+$/', '', $id);
        }

        // Try extracting from PID
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

        // Try to parse various date formats
        try {
            $parsed = \Carbon\Carbon::parse($date);

            return $parsed->format('Y-m-d');
        } catch (\Exception $e) {
            Log::warning('Open Overheid date parse error', [
                'date' => $date,
                'exception' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
