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
     */
    public function syncToTypesense(): void
    {
        if (! config('open_overheid.typesense.enabled', true)) {
            Log::info('Typesense sync is disabled');

            return;
        }

        $documents = OpenOverheidDocument::query()
            ->where(function ($query) {
                $query->whereNull('typesense_synced_at')
                    ->orWhereColumn('typesense_synced_at', '<', 'updated_at');
            })
            ->get();

        if ($documents->isEmpty()) {
            Log::info('No documents to sync to Typesense');

            return;
        }

        Log::info("Syncing {$documents->count()} documents to Typesense");

        $synced = 0;
        $errors = 0;

        foreach ($documents as $document) {
            try {
                $this->indexDocument($document);
                $document->update(['typesense_synced_at' => now()]);
                $synced++;

                if ($synced % 10 === 0) {
                    Log::info("Typesense sync progress: {$synced} documents");
                }
            } catch (\Exception $e) {
                Log::error('Typesense index error', [
                    'external_id' => $document->external_id,
                    'error' => $e->getMessage(),
                ]);
                $errors++;
            }
        }

        Log::info('Typesense sync completed', [
            'synced' => $synced,
            'errors' => $errors,
        ]);
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
}
