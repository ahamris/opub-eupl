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
     *
     * @param  int  $daysBack  Number of days back to sync (default: 7)
     * @param  \Illuminate\Console\Command|null  $command
     * @return array{total: int, synced: int, errors: int}
     */
    public function syncRecent(int $daysBack = 7, $command = null): array
    {
        $from = now()->subDays($daysBack)->format('d-m-Y');
        $to = now()->format('d-m-Y');

        return $this->syncByDateRange($from, $to, $command);
    }

    /**
     * @param string|null $from
     * @param string|null $to
     * @param $command
     * @return array|int[]
     * @throws \Exception
     */
    public function syncByDateRange(?string $from = null, ?string $to = null, $command = null): array
    {
        if (! config('open_overheid.sync.enabled', true)) {
            Log::channel('sync_errors')->info('Open Overheid sync is disabled');
            $command?->info('Open Overheid sync is disabled.');

            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        $dateRange = $from && $to ? "{$from} to {$to}" : ($from ? "from {$from}" : ($to ? "until {$to}" : 'all'));
        Log::channel('sync_errors')->info("Starting Open Overheid sync for date range: {$dateRange}");

        // First, get total count for progress bar
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

        $page = 1;
        $perPage = 50; // Use maximum page size for efficiency
        $totalSynced = 0;
        $totalCreated = 0;
        $totalUpdated = 0;
        $totalSkipped = 0;
        $totalErrors = 0;
        $hasMorePages = true;
        $processed = 0;
        $failedDocuments = []; // Track failed documents for retry

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
                            Log::channel('sync_errors')->warning('Open Overheid item missing ID', ['item' => $item]);
                            $totalErrors++;
                            $processed++;

                            if ($command) {
                                $this->updateProgressBar($bar, $processed, $totalResults, $totalErrors, $command, $totalSkipped);
                                $bar->advance();
                            }

                            continue;
                        }

                        // Fetch detailed document
                        $documentData = $this->searchService->getDocument($externalId);

                        // Add small delay between requests to avoid overwhelming the API
                        // Only delay if processing multiple documents (not for single document sync)
                        if ($command && $totalResults > 1) {
                            $delayMs = config('open_overheid.sync.delay_between_requests', 200);
                            usleep($delayMs * 1000); // Convert milliseconds to microseconds
                        }

                        // Sync the document
                        $result = $this->upsertDocument($externalId, $documentData);

                        if ($result === 'created') {
                            $totalCreated++;
                            $totalSynced++;
                        } elseif ($result === 'updated') {
                            $totalUpdated++;
                            $totalSynced++;
                        } elseif ($result === 'skipped') {
                            $totalSkipped++;
                        }

                        $processed++;

                        // Update progress bar
                        if ($command) {
                            $this->updateProgressBar($bar, $processed, $totalResults, $totalErrors, $command, $totalSkipped);
                            $bar->advance();
                        }
                    } catch (\Exception $e) {
                        $externalId = $this->extractExternalId($document ?? $item);

                        // Enhanced error logging to dedicated sync errors log
                        Log::channel('sync_errors')->error('Open Overheid sync error for document', [
                            'external_id' => $externalId,
                            'error' => $e->getMessage(),
                            'error_class' => get_class($e),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                            'trace' => $e->getTraceAsString(),
                        ]);

                        // Store failed document for retry
                        if ($externalId) {
                            $failedDocuments[] = [
                                'external_id' => $externalId,
                                'error' => $e->getMessage(),
                                'error_class' => get_class($e),
                            ];
                        }

                        $totalErrors++;
                        $processed++;

                        if ($command) {
                            $this->updateProgressBar($bar, $processed, $totalResults, $totalErrors, $command, $totalSkipped);
                            $bar->advance();
                        }
                        // Continue with next document
                    }
                }

                // Check if there are more pages
                $hasMorePages = count($items) === $perPage && ($page * $perPage) < $totalResults;

                $page++;
            } catch (\Exception $e) {
                Log::channel('sync_errors')->error('Open Overheid sync error on page', [
                    'page' => $page,
                    'exception' => $e->getMessage(),
                    'error_class' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
                $totalErrors++;
                $hasMorePages = false; // Stop on page-level errors
                break;
            }
        }

        if ($command && $totalResults > 0) {
            $bar->finish();
            $command->newLine();
            // Clear the custom message line
            $command->getOutput()->write("\r\033[K");
            $command->newLine();
        }

        // Retry failed documents if any
        $retriedCount = 0;
        if (! empty($failedDocuments) && $command && ! $command->option('no-retry')) {
            $command->info('Retrying '.count($failedDocuments).' failed documents...');
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
                        $totalSynced++;
                        if ($result === 'created') {
                            $totalCreated++;
                        } elseif ($result === 'updated') {
                            $totalUpdated++;
                        }
                    } else {
                        $totalSkipped++;
                    }
                    $totalErrors--;
                } catch (\Exception $e) {
                    Log::channel('sync_errors')->error('Open Overheid retry failed for document', [
                        'external_id' => $failed['external_id'],
                        'original_error' => $failed['error'],
                        'retry_error' => $e->getMessage(),
                        'error_class' => get_class($e),
                    ]);
                }
                $retryBar->advance();
            }

            $retryBar->finish();
            $command->newLine(2);

            if ($retriedCount > 0) {
                $command->info("✅ Retried and synced {$retriedCount} documents successfully.");
            }
        }

        Log::channel('sync_errors')->info('Open Overheid sync completed', [
            'date_range' => $dateRange,
            'total_synced' => $totalSynced,
            'total_created' => $totalCreated,
            'total_updated' => $totalUpdated,
            'total_skipped' => $totalSkipped,
            'total_errors' => $totalErrors,
            'retried_count' => $retriedCount,
        ]);

        return [
            'total' => $totalResults,
            'synced' => $totalSynced,
            'created' => $totalCreated,
            'updated' => $totalUpdated,
            'skipped' => $totalSkipped,
            'errors' => $totalErrors,
            'retried' => $retriedCount,
        ];
    }

    /**
     * Perform a full sync of all documents.
     * Fetches all documents from the search endpoint and syncs each one.
     *
     * @param  \Illuminate\Console\Command|null  $command
     * @return array{total: int, synced: int, errors: int}
     */
    public function syncAll($command = null): array
    {
        if (! config('open_overheid.sync.enabled', true)) {
            Log::channel('sync_errors')->info('Open Overheid sync is disabled');
            if ($command) {
                $command->info('Open Overheid sync is disabled.');
            }

            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        Log::channel('sync_errors')->info('Starting Open Overheid full sync');

        // First, get total count for progress bar
        $firstQuery = new OpenOverheidSearchQuery(
            zoektekst: '',
            page: 1,
            perPage: 50,
        );

        $firstResponse = $this->searchService->search($firstQuery);
        $totalResults = $firstResponse['totaal'] ?? $firstResponse['total'] ?? 0;

        if ($command) {
            $command->info("Found {$totalResults} documents to sync.");
            $command->newLine();
        }

        $page = 1;
        $perPage = 50; // Use maximum page size for efficiency
        $totalSynced = 0;
        $totalCreated = 0;
        $totalUpdated = 0;
        $totalSkipped = 0;
        $totalErrors = 0;
        $hasMorePages = true;
        $processed = 0;
        $failedDocuments = []; // Track failed documents for retry

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
                            Log::channel('sync_errors')->warning('Open Overheid item missing ID', ['item' => $item]);
                            $totalErrors++;
                            $processed++;

                            if ($command) {
                                $this->updateProgressBar($bar, $processed, $totalResults, $totalErrors, $command, $totalSkipped);
                                $bar->advance();
                            }

                            continue;
                        }

                        // Fetch detailed document
                        $documentData = $this->searchService->getDocument($externalId);

                        // Add small delay between requests to avoid overwhelming the API
                        // Only delay if processing multiple documents (not for single document sync)
                        if ($command && $totalResults > 1) {
                            $delayMs = config('open_overheid.sync.delay_between_requests', 200);
                            usleep($delayMs * 1000); // Convert milliseconds to microseconds
                        }

                        // Sync the document
                        $result = $this->upsertDocument($externalId, $documentData);

                        if ($result === 'created') {
                            $totalCreated++;
                            $totalSynced++;
                        } elseif ($result === 'updated') {
                            $totalUpdated++;
                            $totalSynced++;
                        } elseif ($result === 'skipped') {
                            $totalSkipped++;
                        }

                        $processed++;

                        // Update progress bar
                        if ($command) {
                            $this->updateProgressBar($bar, $processed, $totalResults, $totalErrors, $command, $totalSkipped);
                            $bar->advance();
                        }
                    } catch (\Exception $e) {
                        $externalId = $this->extractExternalId($document ?? $item);

                        // Enhanced error logging to dedicated sync errors log
                        Log::channel('sync_errors')->error('Open Overheid sync error for document', [
                            'external_id' => $externalId,
                            'error' => $e->getMessage(),
                            'error_class' => get_class($e),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                            'trace' => $e->getTraceAsString(),
                        ]);

                        // Store failed document for retry
                        if ($externalId) {
                            $failedDocuments[] = [
                                'external_id' => $externalId,
                                'error' => $e->getMessage(),
                                'error_class' => get_class($e),
                            ];
                        }

                        $totalErrors++;
                        $processed++;

                        if ($command) {
                            $this->updateProgressBar($bar, $processed, $totalResults, $totalErrors, $command, $totalSkipped);
                            $bar->advance();
                        }
                        // Continue with next document
                    }
                }

                // Check if there are more pages
                $hasMorePages = count($items) === $perPage && ($page * $perPage) < $totalResults;

                $page++;
            } catch (\Exception $e) {
                Log::channel('sync_errors')->error('Open Overheid sync error on page', [
                    'page' => $page,
                    'exception' => $e->getMessage(),
                    'error_class' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
                $totalErrors++;
                $hasMorePages = false; // Stop on page-level errors
                break;
            }
        }

        if ($command && $totalResults > 0) {
            $bar->finish();
            $command->newLine();
            // Clear the custom message line
            $command->getOutput()->write("\r\033[K");
            $command->newLine();
        }

        // Retry failed documents if any
        $retriedCount = 0;
        if (! empty($failedDocuments) && $command && ! $command->option('no-retry')) {
            $command->info('Retrying '.count($failedDocuments).' failed documents...');
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
                        $totalSynced++;
                        if ($result === 'created') {
                            $totalCreated++;
                        } elseif ($result === 'updated') {
                            $totalUpdated++;
                        }
                    } else {
                        $totalSkipped++;
                    }
                    $totalErrors--;
                } catch (\Exception $e) {
                    Log::channel('sync_errors')->error('Open Overheid retry failed for document', [
                        'external_id' => $failed['external_id'],
                        'original_error' => $failed['error'],
                        'retry_error' => $e->getMessage(),
                        'error_class' => get_class($e),
                    ]);
                }
                $retryBar->advance();
            }

            $retryBar->finish();
            $command->newLine(2);

            if ($retriedCount > 0) {
                $command->info("✅ Retried and synced {$retriedCount} documents successfully.");
            }
        }

        Log::channel('sync_errors')->info('Open Overheid sync completed', [
            'total_synced' => $totalSynced,
            'total_created' => $totalCreated,
            'total_updated' => $totalUpdated,
            'total_skipped' => $totalSkipped,
            'total_errors' => $totalErrors,
            'retried_count' => $retriedCount,
        ]);

        return [
            'total' => $totalResults,
            'synced' => $totalSynced,
            'created' => $totalCreated,
            'updated' => $totalUpdated,
            'skipped' => $totalSkipped,
            'errors' => $totalErrors,
            'retried' => $retriedCount,
        ];
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
     * Upsert document data to the database.
     * Maps the detail endpoint response structure to our database fields.
     *
     * @return string 'created'|'updated'|'skipped'
     */
    protected function upsertDocument(string $externalId, array $documentData): string
    {
        $document = $documentData['document'] ?? [];
        $plooiIntern = $documentData['plooiIntern'] ?? [];
        $versies = $documentData['versies'] ?? [];
        $classificatie = $document['classificatiecollectie'] ?? [];

        // Extract title from titelcollectie.officieleTitel
        // Truncate to 1000 characters to prevent database errors (title column is varchar(1000))
        $title = $document['titelcollectie']['officieleTitel'] ?? null;
        if ($title && mb_strlen($title) > 1000) {
            $originalLength = mb_strlen($title);
            $title = mb_substr($title, 0, 1000);
            Log::channel('sync_errors')->warning('Open Overheid title truncated', [
                'external_id' => $externalId,
                'original_length' => $originalLength,
                'truncated_length' => 1000,
            ]);
        }

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

        // Normalize metadata to ensure Unicode characters are properly encoded
        // This prevents PostgreSQL SQL_ASCII encoding issues with Unicode escape sequences
        $normalizedMetadata = $this->normalizeMetadataForDatabase($documentData);

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
            'metadata' => $normalizedMetadata, // Store normalized response for reference
            'synced_at' => now(),
        ];

        // Check if document already exists
        $existing = OpenOverheidDocument::where('external_id', $externalId)->first();

        if ($existing) {
            // Check if data has changed (compare key fields)
            $hasChanged = $existing->title !== $title
                || $existing->description !== $description
                || $existing->publication_date?->format('Y-m-d') !== $publicationDate
                || $existing->document_type !== $documentType
                || $existing->category !== $category
                || $existing->theme !== $theme
                || $existing->organisation !== $organisation;

            if (! $hasChanged) {
                // Document exists and hasn't changed - skip
                return 'skipped';
            }

            // Document exists but has changed - update
            $existing->update($data);

            // Dispatch job to pre-compute dossier metadata if this is a dossier document
            if ($this->isDossierDocument($existing)) {
                \App\Jobs\PrecomputeDossierMetadataJob::dispatch($externalId);
            }

            return 'updated';
        }

        // New document - create
        $newDocument = OpenOverheidDocument::create($data);

        // Dispatch job to pre-compute dossier metadata if this is a dossier document
        if ($this->isDossierDocument($newDocument)) {
            \App\Jobs\PrecomputeDossierMetadataJob::dispatch($externalId);
        }

        return 'created';
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
            Log::channel('sync_errors')->warning('Open Overheid date parse error', [
                'date' => $date,
                'exception' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Update progress bar with error count, skipped count, and ETA in colors
     */
    protected function updateProgressBar($bar, int $processed, int $total, int $errors, $command, int $skipped = 0): void
    {
        if ($processed === 0 || ! $command) {
            return;
        }

        // Calculate ETA based on average time per document
        $elapsed = time() - $bar->getStartTime();
        $eta = $elapsed > 0 && $processed > 0 && $processed < $total
            ? (int) (($elapsed / $processed) * ($total - $processed))
            : 0;

        $etaFormatted = $eta > 0 ? gmdate('H:i:s', $eta) : '--:--:--';

        // Build status line with colors (on a new line below progress bar)
        $statusLine = "\n"; // Move to new line
        $statusLine .= "\033[K"; // Clear line

        // Skipped in blue/cyan
        if ($skipped > 0) {
            $statusLine .= "\033[36mSkipped: {$skipped}\033[0m  ";
        }

        // Errors in red
        if ($errors > 0) {
            $statusLine .= "\033[31mErrors: {$errors}\033[0m  ";
        }

        // ETA in orange/yellow (38;5;208 = orange)
        $statusLine .= "\033[38;5;208mETA: {$etaFormatted}\033[0m";

        // Move cursor back up one line
        $statusLine .= "\033[1A";

        $command->getOutput()->write($statusLine);
    }

    /**
     * Check if a document is part of a dossier (has identiteitsgroep relations).
     */
    protected function isDossierDocument(OpenOverheidDocument $document): bool
    {
        $metadata = $document->metadata ?? [];
        $documentrelaties = $metadata['documentrelaties'] ?? [];

        if (empty($documentrelaties) || ! is_array($documentrelaties)) {
            return false;
        }

        foreach ($documentrelaties as $relation) {
            $role = $relation['role'] ?? '';
            if (str_contains($role, 'identiteitsgroep')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalize metadata array to ensure Unicode characters are properly encoded.
     * This converts Unicode escape sequences (like \u2018, \u00eb) to actual UTF-8 characters
     * to prevent PostgreSQL SQL_ASCII encoding issues.
     *
     * Note: The custom UnicodeJson cast will also ensure proper encoding when storing,
     * but this normalization provides an extra layer of safety.
     *
     * @param  array  $metadata
     * @return array
     */
    protected function normalizeMetadataForDatabase(array $metadata): array
    {
        // Encode to JSON with UNESCAPED_UNICODE flag to convert escape sequences to actual UTF-8
        // Then decode back to get the normalized array with proper UTF-8 characters
        $json = json_encode($metadata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($json === false) {
            // If encoding fails, log and return original
            Log::channel('sync_errors')->warning('Failed to normalize metadata for database', [
                'json_error' => json_last_error_msg(),
            ]);
            return $metadata;
        }

        $normalized = json_decode($json, true);

        if ($normalized === null && json_last_error() !== JSON_ERROR_NONE) {
            // If decoding fails, log and return original
            Log::channel('sync_errors')->warning('Failed to decode normalized metadata', [
                'json_error' => json_last_error_msg(),
            ]);
            return $metadata;
        }

        return $normalized ?? $metadata;
    }
}
