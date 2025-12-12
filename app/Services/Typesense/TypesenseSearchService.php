<?php

namespace App\Services\Typesense;

use Illuminate\Support\Facades\Log;
use Typesense\Client;

class TypesenseSearchService
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
     * Search documents in Typesense
     *
     * @param  string  $query  Search query
     * @param  array  $options  Search options (filter_by, sort_by, per_page, etc.)
     */
    public function search(string $query, array $options = []): array
    {
        try {
            $searchParams = [
                'q' => $query,
                'query_by' => 'title,description,content',
                'per_page' => $options['per_page'] ?? 20,
                'page' => $options['page'] ?? 1,
            ];

            // Enable typo tolerance for better search experience
            if (! isset($options['typo_tolerance'])) {
                $searchParams['typo_tolerance'] = 'auto';
            }

            // Enable prefix matching for instant search
            if (! isset($options['prefix'])) {
                $searchParams['prefix'] = 'true';
            }

            // Enable infix matching for better results
            if (! isset($options['infix'])) {
                $searchParams['infix'] = 'off';
            }

            // Add filters
            if (isset($options['filter_by'])) {
                $searchParams['filter_by'] = $options['filter_by'];
            }

            // Add sorting
            if (isset($options['sort_by'])) {
                $searchParams['sort_by'] = $options['sort_by'];
            } else {
                $searchParams['sort_by'] = 'publication_date:desc';
            }

            // Add facets
            if (isset($options['facet_by'])) {
                $searchParams['facet_by'] = $options['facet_by'];
            } else {
                $searchParams['facet_by'] = 'document_type,theme,organisation';
            }

            // Enable hybrid search (keyword + semantic) if embeddings are available
            // Typesense supports automatic semantic search when query vectors are provided
            // For now, we use keyword search with typo tolerance and prefix matching
            // Future: Add embedding generation for semantic search

            // Enable natural language search improvements
            $searchParams['prioritize_exact_match'] = true;
            $searchParams['prioritize_token_position'] = true;

            $results = $this->client->collections[$this->collection]
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
            Log::error('Typesense search error', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get document by ID
     */
    public function getDocument(string $id): ?array
    {
        try {
            return $this->client->collections[$this->collection]
                ->documents[$id]
                ->retrieve();
        } catch (\Exception $e) {
            Log::warning('Typesense document not found', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
