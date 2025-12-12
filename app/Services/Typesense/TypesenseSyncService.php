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
     * Sync all pending documents to Typesense
     *
     * @param  \Illuminate\Console\Command|null  $command
     * @return array{total: int, synced: int, errors: int}
     */
    public function syncToTypesense($command = null): array
    {
        if (! config('open_overheid.typesense.enabled', true)) {
            Log::info('Typesense sync is disabled');

            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        $totalPending = OpenOverheidDocument::query()
            ->where(function ($query) {
                $query->whereNull('typesense_synced_at')
                    ->orWhereColumn('typesense_synced_at', '<', 'updated_at');
            })
            ->count();

        if ($totalPending === 0) {
            Log::info('No documents to sync to Typesense');
            if ($command) {
                $command->info('All documents are already synced.');
            }

            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        if ($command) {
            $command->info("Found {$totalPending} documents to sync.");
            $command->newLine();
        }

        Log::info("Syncing {$totalPending} documents to Typesense");

        $synced = 0;
        $errors = 0;
        $batchSize = 100;
        $processed = 0;

        $documents = OpenOverheidDocument::query()
            ->where(function ($query) {
                $query->whereNull('typesense_synced_at')
                    ->orWhereColumn('typesense_synced_at', '<', 'updated_at');
            })
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
                Log::error('Typesense index error', [
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

        Log::info('Typesense sync completed', [
            'total' => $totalPending,
            'synced' => $synced,
            'errors' => $errors,
        ]);

        return [
            'total' => $totalPending,
            'synced' => $synced,
            'errors' => $errors,
        ];
    }

    /**
     * Index a single document
     */
    protected function indexDocument(OpenOverheidDocument $document): void
    {
        // Ensure collection exists
        $this->ensureCollectionExists();

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
            'url' => $this->extractUrl($document),
            'synced_at' => $document->synced_at
                ? $document->synced_at->timestamp
                : 0,
        ];

        try {
            $this->client->collections[$this->collection]->documents->upsert($data);
        } catch (\Exception $e) {
            Log::error('Typesense upsert failed', [
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
            Log::info("Created Typesense collection: {$this->collection}");
        } catch (\Exception $e) {
            Log::error('Failed to create Typesense collection', [
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
