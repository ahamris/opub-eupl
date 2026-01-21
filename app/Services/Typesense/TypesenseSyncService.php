<?php

namespace App\Services\Typesense;

use App\Models\OpenOverheidDocument;
use Illuminate\Support\Facades\Log;
use Typesense\Client;

class TypesenseSyncService
{
    protected Client $client;

    protected string $collection = 'open_overheid_documents';

    public function __construct()
    {
        $config = config('open_overheid.typesense', []);

        $this->client = new Client([
            'api_key' => $config['api_key'] ?? env('TYPESENSE_API_KEY'),
            'nodes' => [[
                'host' => $config['host'] ?? env('TYPESENSE_HOST', 'localhost'),
                'port' => (int) ($config['port'] ?? env('TYPESENSE_PORT', 8108)),
                'protocol' => $config['protocol'] ?? env('TYPESENSE_PROTOCOL', 'http'),
            ]],
            'connection_timeout_seconds' => 2,
        ]);
    }

    /**
     * Sync pending documents to Typesense (for queue job - processes limited batch)
     *
     * @param  int  $limit  Maximum number of documents to process per run
     * @return array{total: int, synced: int, errors: int}
     */
    public function syncPending(int $limit = 100): array
    {
        if (! config('open_overheid.typesense.enabled', true)) {
            Log::channel('typesense_errors')->info('Typesense sync is disabled');

            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        // Mark sync as running
        \Illuminate\Support\Facades\Cache::put('typesense_sync_running', true, 300);
        \Illuminate\Support\Facades\Cache::put('typesense_sync_started_at', now(), 300);

        try {
            // Query documents that need Typesense sync
            $documents = OpenOverheidDocument::needsTypesenseSync()
                ->limit($limit)
                ->get();

            if ($documents->isEmpty()) {
                Log::channel('typesense_errors')->info('No documents to sync to Typesense');
                
                // Mark sync as complete
                \Illuminate\Support\Facades\Cache::forget('typesense_sync_running');

                return ['total' => 0, 'synced' => 0, 'errors' => 0];
            }

            $totalPending = $documents->count();
            Log::channel('typesense_errors')->info("Syncing {$totalPending} documents to Typesense (batch limit: {$limit})");

            $synced = 0;
            $errors = 0;

            foreach ($documents as $document) {
                try {
                    $this->indexDocument($document);
                    $document->update(['typesense_synced_at' => now()]);
                    $synced++;
                } catch (\Exception $e) {
                    Log::channel('typesense_errors')->error('Typesense index error', [
                        'external_id' => $document->external_id,
                        'error' => $e->getMessage(),
                    ]);
                    $errors++;
                }
            }

            Log::channel('typesense_errors')->info('Typesense sync batch completed', [
                'total' => $totalPending,
                'synced' => $synced,
                'errors' => $errors,
            ]);

            return [
                'total' => $totalPending,
                'synced' => $synced,
                'errors' => $errors,
            ];
        } finally {
            // Mark sync as complete after a short delay (in case another batch is queued)
            \Illuminate\Support\Facades\Cache::put('typesense_sync_running', false, 60);
        }
    }

    /**
     * Sync all pending documents to Typesense
     *
     * @param  \Illuminate\Console\Command|null  $command
     * @return array{total: int, synced: int, errors: int}
     */
    public function syncToTypesense($command = null): array
    {
        if (! config('open_overheid.typesense.enabled', true)) {
            Log::channel('typesense_errors')->info('Typesense sync is disabled');

            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        // Mark sync as running
        \Illuminate\Support\Facades\Cache::put('typesense_sync_running', true, 600);
        \Illuminate\Support\Facades\Cache::put('typesense_sync_started_at', now(), 600);

        try {
            $totalPending = OpenOverheidDocument::needsTypesenseSync()->count();

            if ($totalPending === 0) {
                Log::channel('typesense_errors')->info('No documents to sync to Typesense');
                if ($command) {
                    $command->info('All documents are already synced.');
                }
                
                \Illuminate\Support\Facades\Cache::forget('typesense_sync_running');

                return ['total' => 0, 'synced' => 0, 'errors' => 0];
            }

            if ($command) {
                $command->info("Found {$totalPending} documents to sync.");
                $command->newLine();
            }

            Log::channel('typesense_errors')->info("Syncing {$totalPending} documents to Typesense");

            $synced = 0;
            $errors = 0;
            $batchSize = 100;
            $processed = 0;

            $documents = OpenOverheidDocument::needsTypesenseSync()
                ->lazyById($batchSize);

            if ($command) {
                $bar = $command->getOutput()->createProgressBar($totalPending);
                $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s% %memory:6s%');
                $bar->start();
            }

            foreach ($documents as $document) {
                try {
                    $this->indexDocument($document);
                    $document->update(['typesense_synced_at' => now()]);
                    $synced++;
                    $processed++;

                    if ($command) {
                        $this->updateProgressBar($bar, $processed, $totalPending, $errors, $command);
                        $bar->advance();
                    }
                } catch (\Exception $e) {
                    Log::channel('typesense_errors')->error('Typesense index error', [
                        'external_id' => $document->external_id,
                        'error' => $e->getMessage(),
                    ]);
                    $errors++;
                    $processed++;

                    if ($command) {
                        $this->updateProgressBar($bar, $processed, $totalPending, $errors, $command);
                        $bar->advance();
                    }
                }
            }

            if ($command) {
                $bar->finish();
                $command->newLine();
                // Clear the custom message line
                $command->getOutput()->write("\r\033[K");
                $command->newLine();
            }

            Log::channel('typesense_errors')->info('Typesense sync completed', [
                'total' => $totalPending,
                'synced' => $synced,
                'errors' => $errors,
            ]);

            return [
                'total' => $totalPending,
                'synced' => $synced,
                'errors' => $errors,
            ];
        } finally {
            // Mark sync as complete
            \Illuminate\Support\Facades\Cache::put('typesense_sync_running', false, 60);
        }
    }

    /**
     * Index a single document
     */
    protected function indexDocument(OpenOverheidDocument $document): void
    {
        // Ensure collection exists
        $this->ensureCollectionExists();

        $url = $this->extractUrl($document);
        $publicationDestination = $this->extractPublicationDestination($url);

        $data = [
            'id' => (string) $document->id,
            'external_id' => $document->external_id,
            'title' => $document->title ?? '',
            'description' => $document->description ?? '',
            'content' => $document->content ?? '',
            'publication_date' => $document->publication_date
                ? strtotime($document->publication_date->format('Y-m-d'))
                : 0,
            'document_type' => $document->document_type ?? '',
            'category' => $document->category ?? '',
            'theme' => $document->theme ?? '',
            'organisation' => $document->organisation ?? '',
            'url' => $url,
            'publication_destination' => $publicationDestination,
            'synced_at' => $document->synced_at
                ? $document->synced_at->timestamp
                : 0,
        ];

        try {
            $this->client->collections[$this->collection]->documents->upsert($data);
        } catch (\Exception $e) {
                Log::channel('typesense_errors')->error('Typesense upsert failed', [
                    'external_id' => $document->external_id,
                    'error' => $e->getMessage(),
                ]);
            throw $e;
        }
    }

    /**
     * Extract URL from document metadata
     */
    protected function extractUrl(OpenOverheidDocument $document): string
    {
        $metadata = $document->metadata ?? [];
        $weblocatie = $metadata['document']['weblocatie'] ?? null;

        if ($weblocatie) {
            return $weblocatie;
        }

        $pid = $metadata['document']['pid'] ?? null;
        if ($pid) {
            return $pid;
        }

        return '';
    }

    /**
     * Extract publication destination (domain) from URL
     */
    protected function extractPublicationDestination(string $url): string
    {
        if (empty($url)) {
            return '';
        }

        $parsed = parse_url($url);
        if (isset($parsed['host'])) {
            return $parsed['host'];
        }

        // If URL doesn't have a scheme, try to extract domain from the string
        if (preg_match('/^(?:https?:\/\/)?([^\/]+)/', $url, $matches)) {
            return $matches[1];
        }

        return '';
    }

    /**
     * Ensure Typesense collection exists
     */
    protected function ensureCollectionExists(): void
    {
        try {
            $this->client->collections[$this->collection]->retrieve();
        } catch (\Exception $e) {
            // Collection doesn't exist, create it
            if (str_contains($e->getMessage(), 'Not Found')) {
                $this->createCollection();
            } else {
                throw $e;
            }
        }
    }

    /**
     * Create Typesense collection schema
     */
    protected function createCollection(): void
    {
        $schema = [
            'name' => $this->collection,
            'fields' => [
                ['name' => 'id', 'type' => 'string'],
                ['name' => 'external_id', 'type' => 'string'],
                ['name' => 'title', 'type' => 'string', 'index' => true],
                ['name' => 'description', 'type' => 'string', 'index' => true],
                ['name' => 'content', 'type' => 'string', 'index' => true],
                ['name' => 'publication_date', 'type' => 'int64', 'sort' => true],
                ['name' => 'document_type', 'type' => 'string', 'facet' => true],
                ['name' => 'category', 'type' => 'string', 'facet' => true],
                ['name' => 'theme', 'type' => 'string', 'facet' => true],
                ['name' => 'organisation', 'type' => 'string', 'facet' => true],
                ['name' => 'url', 'type' => 'string'],
                ['name' => 'publication_destination', 'type' => 'string', 'facet' => true],
                ['name' => 'synced_at', 'type' => 'int64', 'sort' => true],
            ],
            'default_sorting_field' => 'publication_date',
        ];

        // Natural language search disabled - Typesense is pure search only
        // AI search is premium-only feature via chat interface
        // $nlConfig = config('open_overheid.typesense.natural_language_search', []);
        // if (! empty($nlConfig['enabled']) && ! empty($nlConfig['api_key']) && ! empty($nlConfig['model'])) {
        //     $schema['natural_language_search'] = [
        //         'enabled' => true,
        //         'model' => $nlConfig['model'],
        //     ];
        // }

        try {
            $this->client->collections->create($schema);
            Log::channel('typesense_errors')->info("Created Typesense collection: {$this->collection}");
        } catch (\Exception $e) {
            Log::channel('typesense_errors')->error('Failed to create Typesense collection', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Update progress bar with error count and ETA in colors
     */
    protected function updateProgressBar($bar, int $processed, int $total, int $errors, $command): void
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
}
