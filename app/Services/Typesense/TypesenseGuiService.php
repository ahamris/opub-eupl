<?php

namespace App\Services\Typesense;

use Illuminate\Support\Facades\Log;
use Typesense\Client;

class TypesenseGuiService
{
    protected Client $client;

    public function __construct()
    {
        $config = config('open_overheid.typesense', []);

        $this->client = new Client([
            'api_key' => $config['api_key'] ?? '',
            'nodes' => [[
                'host' => $config['host'] ?? 'localhost',
                'port' => (int) ($config['port'] ?? 8108),
                'protocol' => $config['protocol'] ?? 'http',
            ]],
            'connection_timeout_seconds' => 2,
        ]);
    }

    /**
     * List all collections with document counts
     *
     * @return array<int, array{name: string, num_documents: int, created_at: int}>
     */
    public function listCollections(): array
    {
        try {
            $response = $this->client->collections->retrieve();

            // Typesense API returns collections directly as array, not nested
            if (isset($response['collections'])) {
                return $response['collections'];
            }

            // If response is already an array of collections
            if (is_array($response) && ! empty($response) && isset($response[0]['name'])) {
                return $response;
            }

            // Empty or unexpected structure
            return [];
        } catch (\Exception $e) {
            Log::error('Typesense list collections error', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get collection details and schema
     *
     * @return array<string, mixed>
     */
    public function getCollection(string $name): array
    {
        try {
            return $this->client->collections[$name]->retrieve();
        } catch (\Exception $e) {
            Log::error('Typesense get collection error', [
                'collection' => $name,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get collection statistics including document count
     *
     * @return array<string, mixed>
     */
    public function getCollectionStats(string $name): array
    {
        try {
            $collection = $this->getCollection($name);

            return [
                'name' => $collection['name'] ?? $name,
                'num_documents' => $collection['num_documents'] ?? 0,
                'created_at' => $collection['created_at'] ?? 0,
                'fields' => $collection['fields'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('Typesense get collection stats error', [
                'collection' => $name,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search documents in a collection
     *
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    public function searchCollection(string $collection, array $params = []): array
    {
        try {
            $searchParams = [
                'q' => $params['q'] ?? '*',
                'query_by' => $params['query_by'] ?? '',
                'per_page' => $params['per_page'] ?? 20,
                'page' => $params['page'] ?? 1,
            ];

            if (isset($params['filter_by'])) {
                $searchParams['filter_by'] = $params['filter_by'];
            }

            if (isset($params['sort_by'])) {
                $searchParams['sort_by'] = $params['sort_by'];
            }

            if (isset($params['facet_by'])) {
                $searchParams['facet_by'] = $params['facet_by'];
            }

            $results = $this->client->collections[$collection]
                ->documents
                ->search($searchParams);

            return [
                'hits' => $results['hits'] ?? [],
                'found' => $results['found'] ?? 0,
                'page' => $results['page'] ?? 1,
                'search_time_ms' => $results['search_time_ms'] ?? 0,
                'facet_counts' => $results['facet_counts'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('Typesense search collection error', [
                'collection' => $collection,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get a single document by ID
     *
     * @return array<string, mixed>|null
     */
    public function getDocument(string $collection, string $id): ?array
    {
        try {
            return $this->client->collections[$collection]
                ->documents[$id]
                ->retrieve();
        } catch (\Exception $e) {
            Log::warning('Typesense document not found', [
                'collection' => $collection,
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Add or update a document
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function addDocument(string $collection, array $data): array
    {
        try {
            return $this->client->collections[$collection]
                ->documents
                ->upsert($data);
        } catch (\Exception $e) {
            Log::error('Typesense add document error', [
                'collection' => $collection,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete a document by ID
     */
    public function deleteDocument(string $collection, string $id): array
    {
        try {
            return $this->client->collections[$collection]
                ->documents[$id]
                ->delete();
        } catch (\Exception $e) {
            Log::error('Typesense delete document error', [
                'collection' => $collection,
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete a collection
     */
    public function deleteCollection(string $name): array
    {
        try {
            return $this->client->collections[$name]->delete();
        } catch (\Exception $e) {
            Log::error('Typesense delete collection error', [
                'collection' => $name,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create a new collection
     *
     * @param  array<string, mixed>  $schema
     * @return array<string, mixed>
     */
    public function createCollection(array $schema): array
    {
        try {
            return $this->client->collections->create($schema);
        } catch (\Exception $e) {
            Log::error('Typesense create collection error', [
                'schema' => $schema,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
