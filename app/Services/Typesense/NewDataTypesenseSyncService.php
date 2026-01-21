<?php

namespace App\Services\Typesense;

use App\Models\Ooori\OverheidDocument;
use App\Models\Ooori\OriDocument;
use App\Models\Ooori\OverheidTheme;
use App\Models\Ooori\OverheidCategory;
use App\Models\Ooori\OverheidOrganisation;
use Illuminate\Support\Facades\Log;
use Typesense\Client;

class NewDataTypesenseSyncService
{
    protected Client $client;

    // Collection names
    protected string $documentsCollection = 'oo_documents';
    protected string $oriDocumentsCollection = 'oo_ori_documents';
    protected string $themesCollection = 'oo_themes';
    protected string $categoriesCollection = 'oo_categories';
    protected string $organisationsCollection = 'oo_organisations';

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
     * Sync all Ooori models to Typesense (or specific models if provided)
     *
     * @param  array|null  $models  Array of model names to sync (null = all)
     * @param  \Illuminate\Console\Command|null  $command
     * @return array{total: int, synced: int, errors: int}
     */
    public function syncAll(?array $models = null, $command = null): array
    {
        if (! config('open_overheid.typesense.enabled', true)) {
            Log::channel('typesense_errors')->info('Typesense sync is disabled');
            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        $availableModels = [
            'documents' => 'syncDocuments',
            'ori_documents' => 'syncOriDocuments',
            'themes' => 'syncThemes',
            'categories' => 'syncCategories',
            'organisations' => 'syncOrganisations',
        ];

        $modelsToSync = $models ?? array_keys($availableModels);

        $totalSynced = 0;
        $totalErrors = 0;
        $totalProcessed = 0;

        foreach ($modelsToSync as $model) {
            if (! isset($availableModels[$model])) {
                Log::channel('typesense_errors')->warning("Unknown model type: {$model}");
                continue;
            }

            $method = $availableModels[$model];
            $result = $this->$method($command);

            $totalSynced += $result['synced'];
            $totalErrors += $result['errors'];
            $totalProcessed += $result['total'];
        }

        return [
            'total' => $totalProcessed,
            'synced' => $totalSynced,
            'errors' => $totalErrors,
        ];
    }

    /**
     * Sync OverheidDocument models to Typesense
     */
    public function syncDocuments($command = null, int $limit = null): array
    {
        $this->ensureCollectionExists($this->documentsCollection, $this->getDocumentsSchema());

        $query = OverheidDocument::needsTypesenseSync();
        if ($limit) {
            $query->limit($limit);
        }

        $documents = $query->with(['category', 'theme', 'organisation'])->get();

        if ($documents->isEmpty()) {
            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        $synced = 0;
        $errors = 0;

        foreach ($documents as $document) {
            try {
                $this->indexDocument($document);
                $document->update(['typesense_synced_at' => now()]);
                $synced++;
            } catch (\Exception $e) {
                Log::channel('typesense_errors')->error('Typesense index error (OverheidDocument)', [
                    'external_id' => $document->external_id,
                    'error' => $e->getMessage(),
                ]);
                $errors++;
            }
        }

        return [
            'total' => $documents->count(),
            'synced' => $synced,
            'errors' => $errors,
        ];
    }

    /**
     * Sync OriDocument models to Typesense
     */
    public function syncOriDocuments($command = null, int $limit = null): array
    {
        $this->ensureCollectionExists($this->oriDocumentsCollection, $this->getOriDocumentsSchema());

        $query = OriDocument::needsTypesenseSync();
        if ($limit) {
            $query->limit($limit);
        }

        $documents = $query->get();

        if ($documents->isEmpty()) {
            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        $synced = 0;
        $errors = 0;

        foreach ($documents as $document) {
            try {
                $this->indexOriDocument($document);
                $document->update(['typesense_synced_at' => now()]);
                $synced++;
            } catch (\Exception $e) {
                Log::channel('typesense_errors')->error('Typesense index error (OriDocument)', [
                    'external_id' => $document->external_id,
                    'error' => $e->getMessage(),
                ]);
                $errors++;
            }
        }

        return [
            'total' => $documents->count(),
            'synced' => $synced,
            'errors' => $errors,
        ];
    }

    /**
     * Sync OverheidTheme models to Typesense
     */
    public function syncThemes($command = null): array
    {
        $this->ensureCollectionExists($this->themesCollection, $this->getThemesSchema());

        $themes = OverheidTheme::all();

        if ($themes->isEmpty()) {
            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        $synced = 0;
        $errors = 0;

        foreach ($themes as $theme) {
            try {
                $this->indexTheme($theme);
                $synced++;
            } catch (\Exception $e) {
                Log::channel('typesense_errors')->error('Typesense index error (OverheidTheme)', [
                    'id' => $theme->id,
                    'error' => $e->getMessage(),
                ]);
                $errors++;
            }
        }

        return [
            'total' => $themes->count(),
            'synced' => $synced,
            'errors' => $errors,
        ];
    }

    /**
     * Sync OverheidCategory models to Typesense
     */
    public function syncCategories($command = null): array
    {
        $this->ensureCollectionExists($this->categoriesCollection, $this->getCategoriesSchema());

        $categories = OverheidCategory::all();

        if ($categories->isEmpty()) {
            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        $synced = 0;
        $errors = 0;

        foreach ($categories as $category) {
            try {
                $this->indexCategory($category);
                $synced++;
            } catch (\Exception $e) {
                Log::channel('typesense_errors')->error('Typesense index error (OverheidCategory)', [
                    'id' => $category->id,
                    'error' => $e->getMessage(),
                ]);
                $errors++;
            }
        }

        return [
            'total' => $categories->count(),
            'synced' => $synced,
            'errors' => $errors,
        ];
    }

    /**
     * Sync OverheidOrganisation models to Typesense
     */
    public function syncOrganisations($command = null): array
    {
        $this->ensureCollectionExists($this->organisationsCollection, $this->getOrganisationsSchema());

        $organisations = OverheidOrganisation::all();

        if ($organisations->isEmpty()) {
            return ['total' => 0, 'synced' => 0, 'errors' => 0];
        }

        $synced = 0;
        $errors = 0;

        foreach ($organisations as $organisation) {
            try {
                $this->indexOrganisation($organisation);
                $synced++;
            } catch (\Exception $e) {
                Log::channel('typesense_errors')->error('Typesense index error (OverheidOrganisation)', [
                    'id' => $organisation->id,
                    'error' => $e->getMessage(),
                ]);
                $errors++;
            }
        }

        return [
            'total' => $organisations->count(),
            'synced' => $synced,
            'errors' => $errors,
        ];
    }

    /**
     * Index a single OverheidDocument
     */
    protected function indexDocument(OverheidDocument $document): void
    {
        $url = $this->extractUrlFromMetadata($document->metadata ?? []);
        $publicationDestination = $this->extractPublicationDestination($url);

        $data = [
            'id' => (string) $document->id,
            'external_id' => $document->external_id ?? '',
            'title' => $document->title ?? '',
            'description' => $document->description ?? '',
            'content' => $document->content ?? '',
            'publication_date' => $document->publication_date
                ? strtotime($document->publication_date->format('Y-m-d'))
                : 0,
            'document_type' => $document->document_type ?? '',
            'category' => $document->category?->visible_name ?? $document->category?->name ?? '',
            'category_id' => (string) ($document->overheid_category_id ?? ''),
            'theme' => $document->theme?->visible_name ?? $document->theme?->name ?? '',
            'theme_id' => (string) ($document->overheid_theme_id ?? ''),
            'organisation' => $document->organisation?->visible_name ?? $document->organisation?->name ?? '',
            'organisation_id' => (string) ($document->overheid_organisation_id ?? ''),
            'url' => $url,
            'publication_destination' => $publicationDestination,
            'synced_at' => $document->synced_at
                ? $document->synced_at->timestamp
                : 0,
        ];

        $this->client->collections[$this->documentsCollection]->documents->upsert($data);
    }

    /**
     * Index a single OriDocument
     */
    protected function indexOriDocument(OriDocument $document): void
    {
        $rawData = $document->raw_data ?? [];
        $metadata = $document->metadata ?? [];

        // Extract searchable fields from raw_data or metadata
        $title = $rawData['title'] ?? $metadata['title'] ?? '';
        $description = $rawData['description'] ?? $metadata['description'] ?? '';
        $content = $rawData['content'] ?? $metadata['content'] ?? '';

        $data = [
            'id' => (string) $document->id,
            'external_id' => $document->external_id ?? '',
            'title' => $title,
            'description' => $description,
            'content' => $content,
            'last_discussed_at' => $document->last_discussed_at
                ? $document->last_discussed_at->timestamp
                : 0,
            'synced_at' => $document->synced_at
                ? $document->synced_at->timestamp
                : 0,
        ];

        $this->client->collections[$this->oriDocumentsCollection]->documents->upsert($data);
    }

    /**
     * Index a single OverheidTheme
     */
    protected function indexTheme(OverheidTheme $theme): void
    {
        $data = [
            'id' => (string) $theme->id,
            'name' => $theme->name ?? '',
            'visible_name' => $theme->visible_name ?? $theme->name ?? '',
            'parent_id' => $theme->parent_id ? (string) $theme->parent_id : '',
            'depth' => $theme->dpth ?? 0,
        ];

        $this->client->collections[$this->themesCollection]->documents->upsert($data);
    }

    /**
     * Index a single OverheidCategory
     */
    protected function indexCategory(OverheidCategory $category): void
    {
        $data = [
            'id' => (string) $category->id,
            'name' => $category->name ?? '',
            'visible_name' => $category->visible_name ?? $category->name ?? '',
        ];

        $this->client->collections[$this->categoriesCollection]->documents->upsert($data);
    }

    /**
     * Index a single OverheidOrganisation
     */
    protected function indexOrganisation(OverheidOrganisation $organisation): void
    {
        $data = [
            'id' => (string) $organisation->id,
            'name' => $organisation->name ?? '',
            'visible_name' => $organisation->visible_name ?? $organisation->name ?? '',
        ];

        $this->client->collections[$this->organisationsCollection]->documents->upsert($data);
    }

    /**
     * Get Typesense schema for OverheidDocument collection
     */
    protected function getDocumentsSchema(): array
    {
        return [
            'name' => $this->documentsCollection,
            'fields' => [
                ['name' => 'id', 'type' => 'string'],
                ['name' => 'external_id', 'type' => 'string'],
                ['name' => 'title', 'type' => 'string', 'index' => true],
                ['name' => 'description', 'type' => 'string', 'index' => true],
                ['name' => 'content', 'type' => 'string', 'index' => true],
                ['name' => 'publication_date', 'type' => 'int64', 'sort' => true],
                ['name' => 'document_type', 'type' => 'string', 'facet' => true],
                ['name' => 'category', 'type' => 'string', 'facet' => true],
                ['name' => 'category_id', 'type' => 'string'],
                ['name' => 'theme', 'type' => 'string', 'facet' => true],
                ['name' => 'theme_id', 'type' => 'string'],
                ['name' => 'organisation', 'type' => 'string', 'facet' => true],
                ['name' => 'organisation_id', 'type' => 'string'],
                ['name' => 'url', 'type' => 'string'],
                ['name' => 'publication_destination', 'type' => 'string'],
                ['name' => 'synced_at', 'type' => 'int64', 'sort' => true],
            ],
            'default_sorting_field' => 'publication_date',
        ];
    }

    /**
     * Get Typesense schema for OriDocument collection
     */
    protected function getOriDocumentsSchema(): array
    {
        return [
            'name' => $this->oriDocumentsCollection,
            'fields' => [
                ['name' => 'id', 'type' => 'string'],
                ['name' => 'external_id', 'type' => 'string'],
                ['name' => 'title', 'type' => 'string', 'index' => true],
                ['name' => 'description', 'type' => 'string', 'index' => true],
                ['name' => 'content', 'type' => 'string', 'index' => true],
                ['name' => 'last_discussed_at', 'type' => 'int64', 'sort' => true],
                ['name' => 'synced_at', 'type' => 'int64', 'sort' => true],
            ],
            'default_sorting_field' => 'last_discussed_at',
        ];
    }

    /**
     * Get Typesense schema for OverheidTheme collection
     */
    protected function getThemesSchema(): array
    {
        return [
            'name' => $this->themesCollection,
            'fields' => [
                ['name' => 'id', 'type' => 'string'],
                ['name' => 'name', 'type' => 'string', 'index' => true],
                ['name' => 'visible_name', 'type' => 'string', 'index' => true],
                ['name' => 'parent_id', 'type' => 'string'],
                ['name' => 'depth', 'type' => 'int32'],
            ],
            'default_sorting_field' => 'name',
        ];
    }

    /**
     * Get Typesense schema for OverheidCategory collection
     */
    protected function getCategoriesSchema(): array
    {
        return [
            'name' => $this->categoriesCollection,
            'fields' => [
                ['name' => 'id', 'type' => 'string'],
                ['name' => 'name', 'type' => 'string', 'index' => true],
                ['name' => 'visible_name', 'type' => 'string', 'index' => true],
            ],
            'default_sorting_field' => 'name',
        ];
    }

    /**
     * Get Typesense schema for OverheidOrganisation collection
     */
    protected function getOrganisationsSchema(): array
    {
        return [
            'name' => $this->organisationsCollection,
            'fields' => [
                ['name' => 'id', 'type' => 'string'],
                ['name' => 'name', 'type' => 'string', 'index' => true],
                ['name' => 'visible_name', 'type' => 'string', 'index' => true],
            ],
            'default_sorting_field' => 'name',
        ];
    }

    /**
     * Ensure Typesense collection exists
     */
    protected function ensureCollectionExists(string $collectionName, array $schema): void
    {
        try {
            $this->client->collections[$collectionName]->retrieve();
        } catch (\Exception $e) {
            // Collection doesn't exist, create it
            if (str_contains($e->getMessage(), 'Not Found')) {
                try {
                    $this->client->collections->create($schema);
                    Log::channel('typesense_errors')->info("Created Typesense collection: {$collectionName}");
                } catch (\Exception $createException) {
                    Log::channel('typesense_errors')->error('Failed to create Typesense collection', [
                        'collection' => $collectionName,
                        'error' => $createException->getMessage(),
                    ]);
                    throw $createException;
                }
            } else {
                throw $e;
            }
        }
    }

    /**
     * Extract URL from document metadata
     */
    protected function extractUrlFromMetadata(array $metadata): string
    {
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
}
