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
     */
    public function syncPending(int $limit = 100): array
    {
        if (! config('open_overheid.typesense.enabled', true)) {
            Log::channel('typesense_errors')->info('Typesense sync is disabled');
            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        \Illuminate\Support\Facades\Cache::put('typesense_sync_running', true, 300);
        \Illuminate\Support\Facades\Cache::put('typesense_sync_started_at', now(), 300);

        try {
            $documents = OpenOverheidDocument::needsTypesenseSync()
                ->limit($limit)
                ->get();

            if ($documents->isEmpty()) {
                \Illuminate\Support\Facades\Cache::forget('typesense_sync_running');
                return ['total' => 0, 'synced' => 0, 'errors' => 0];
            }

            $result = $this->batchIndex($documents);

            return $result;
        } finally {
            \Illuminate\Support\Facades\Cache::put('typesense_sync_running', false, 60);
        }
    }

    /**
     * Sync all pending documents to Typesense
     */
    public function syncToTypesense($command = null): array
    {
        if (! config('open_overheid.typesense.enabled', true)) {
            Log::channel('typesense_errors')->info('Typesense sync is disabled');
            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        \Illuminate\Support\Facades\Cache::put('typesense_sync_running', true, 600);
        \Illuminate\Support\Facades\Cache::put('typesense_sync_started_at', now(), 600);

        try {
            $totalPending = OpenOverheidDocument::needsTypesenseSync()->count();

            if ($totalPending === 0) {
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

            if ($command) {
                $bar = $command->getOutput()->createProgressBar($totalPending);
                $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s% %memory:6s%');
                $bar->start();
            }

            // Process in batches for bulk import
            OpenOverheidDocument::needsTypesenseSync()
                ->chunk($batchSize, function ($documents) use (&$synced, &$errors, &$processed, $totalPending, $command, &$bar) {
                    $result = $this->batchIndex($documents);
                    $synced += $result['synced'];
                    $errors += $result['errors'];
                    $processed += $documents->count();

                    if ($command) {
                        $bar->advance($documents->count());
                    }
                });

            if ($command) {
                $bar->finish();
                $command->newLine(2);
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
            \Illuminate\Support\Facades\Cache::put('typesense_sync_running', false, 60);
        }
    }

    /**
     * Batch index documents using Typesense's import API.
     * Much faster than individual upserts — sends all docs in one HTTP call.
     */
    protected function batchIndex($documents): array
    {
        $this->ensureCollectionExists();

        $jsonLines = [];
        $docMap = []; // Track which documents are in this batch

        foreach ($documents as $document) {
            $data = $this->buildDocumentData($document);
            $jsonLines[] = json_encode($data);
            $docMap[] = $document;
        }

        if (empty($jsonLines)) {
            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        $synced = 0;
        $errors = 0;

        try {
            // Use Typesense bulk import (JSONL format) with upsert action
            $importData = implode("\n", $jsonLines);
            $results = $this->client->collections[$this->collection]->documents->import($importData, ['action' => 'upsert']);

            // Parse results — each line is a JSON response for the corresponding document
            $resultLines = is_string($results) ? explode("\n", trim($results)) : $results;

            foreach ($resultLines as $i => $resultLine) {
                $result = is_string($resultLine) ? json_decode($resultLine, true) : $resultLine;

                if (isset($result['success']) && $result['success']) {
                    $synced++;
                    if (isset($docMap[$i])) {
                        $docMap[$i]->update(['typesense_synced_at' => now()]);
                    }
                } else {
                    $errors++;
                    $errorMsg = $result['error'] ?? 'Unknown error';
                    Log::channel('typesense_errors')->error('Typesense batch import error', [
                        'external_id' => $docMap[$i]->external_id ?? 'unknown',
                        'error' => $errorMsg,
                    ]);
                    // Still mark as synced to prevent infinite retry loops for permanent errors
                    if (isset($docMap[$i]) && str_contains($errorMsg, 'not found in the schema')) {
                        $docMap[$i]->update(['typesense_synced_at' => now()]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::channel('typesense_errors')->error('Typesense batch import failed, falling back to individual upserts', [
                'error' => $e->getMessage(),
                'batch_size' => count($jsonLines),
            ]);

            // Fallback: try individual upserts
            foreach ($docMap as $document) {
                try {
                    $data = $this->buildDocumentData($document);
                    $this->client->collections[$this->collection]->documents->upsert($data);
                    $document->update(['typesense_synced_at' => now()]);
                    $synced++;
                } catch (\Exception $e2) {
                    Log::channel('typesense_errors')->error('Typesense individual upsert also failed', [
                        'external_id' => $document->external_id,
                        'error' => $e2->getMessage(),
                    ]);
                    $errors++;
                }
            }
        }

        return [
            'total' => count($docMap),
            'synced' => $synced,
            'errors' => $errors,
        ];
    }

    /**
     * Build the Typesense document data array from an Eloquent model.
     */
    protected function buildDocumentData(OpenOverheidDocument $document): array
    {
        $url = $this->extractUrl($document);
        $publicationDestination = $this->extractPublicationDestination($url);

        return [
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
                ['name' => 'embedding', 'type' => 'float[]', 'num_dim' => 768, 'optional' => true],
            ],
            'default_sorting_field' => 'publication_date',
        ];

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
}
