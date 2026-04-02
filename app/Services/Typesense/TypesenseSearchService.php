<?php

namespace App\Services\Typesense;

use App\Services\AI\EmbeddingService;
use Illuminate\Support\Facades\Log;
use Typesense\Client;

class TypesenseSearchService
{
    protected Client $client;

    protected string $collection = 'open_overheid_documents';
    
    // Singleton client instance for connection reuse
    protected static ?Client $sharedClient = null;

    public function __construct()
    {
        // Reuse existing client connection for speed
        if (self::$sharedClient === null) {
            $config = config('open_overheid.typesense', []);

            self::$sharedClient = new Client([
                'api_key' => $config['api_key'] ?? env('TYPESENSE_API_KEY'),
                'nodes' => [[
                    'host' => $config['host'] ?? env('TYPESENSE_HOST', 'localhost'),
                    'port' => (int) ($config['port'] ?? env('TYPESENSE_PORT', 8108)),
                    'protocol' => $config['protocol'] ?? env('TYPESENSE_PROTOCOL', 'http'),
                ]],
                'connection_timeout_seconds' => 5, // Reduced timeout for fast failure
            ]);
        }
        
        $this->client = self::$sharedClient;
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

            // SPEED OPTIMIZATIONS
            // Set search cutoff for fast responses
            $searchParams['search_cutoff_ms'] = $options['search_cutoff_ms'] ?? 100;
            
            // Limit highlight for speed
            $searchParams['highlight_full_fields'] = 'title';
            $searchParams['snippet_threshold'] = 30;
            
            // Typo tolerance
            $searchParams['typo_tolerance'] = $options['typo_tolerance'] ?? 'auto';

            // Prefix matching
            $searchParams['prefix'] = $options['prefix'] ?? 'true';

            // Infix matching
            $searchParams['infix'] = $options['infix'] ?? 'off';

            // Synonyms
            if (isset($options['enable_synonyms'])) {
                $searchParams['enable_synonyms'] = $options['enable_synonyms'];
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

            // Add facets for filter counts - include all filterable fields
            if (isset($options['facet_by'])) {
                $searchParams['facet_by'] = $options['facet_by'];
            } else {
                // Include all filterable fields for facet counts
                $searchParams['facet_by'] = 'document_type,theme,organisation,category';
            }
            // Limit facet values for speed (100 is enough for most UI dropdowns)
            $searchParams['max_facet_values'] = $options['max_facet_values'] ?? 100;

            // Enable hybrid search (keyword + semantic) if embeddings are available
            // Typesense supports automatic semantic search when query vectors are provided
            // For now, we use keyword search with typo tolerance and prefix matching
            // Future: Add embedding generation for semantic search

            // Enable natural language search improvements
            $searchParams['prioritize_exact_match'] = true;
            $searchParams['prioritize_token_position'] = true;

            // Grouping
            if (isset($options['group_by'])) {
                $searchParams['group_by'] = $options['group_by'];
                $searchParams['group_limit'] = $options['group_limit'] ?? 3;
            }

            // Natural language search disabled - Typesense is pure search only
            // AI search is premium-only feature via chat interface
            // if (! empty($options['natural_language_search'])) {
            //     $nlConfig = config('open_overheid.typesense.natural_language_search', []);
            //     if (! empty($nlConfig['enabled']) && ! empty($nlConfig['model'])) {
            //         $searchParams['nlq_model'] = $nlConfig['model'];
            //     }
            // }

            $results = $this->client->collections[$this->collection]
                ->documents
                ->search($searchParams);

            return [
                'hits' => $results['hits'] ?? [],
                'found' => $results['found'] ?? 0,
                'page' => $results['page'] ?? 1,
                'search_time_ms' => $results['search_time_ms'] ?? 0,
                'facet_counts' => $results['facet_counts'] ?? [],
                'grouped_hits' => $results['grouped_hits'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::channel('typesense_errors')->error('Typesense search error', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Natural language search using Typesense NLSearch
     *
     * @param  string  $query  Natural language query
     * @param  array  $options  Search options
     */
    public function naturalLanguageSearch(string $query, array $options = []): array
    {
        try {
            // Check if natural language search model exists
            $nlConfig = config('open_overheid.typesense.natural_language_search', []);

            if (empty($nlConfig['enabled']) || empty($nlConfig['model'])) {
                // Fallback to regular search if NL search not configured
                return $this->search($query, $options);
            }

            $searchParams = [
                'q' => $query,
                'query_by' => 'title,description,content',
                'per_page' => $options['per_page'] ?? 20,
                'page' => $options['page'] ?? 1,
                'nlq_model' => $nlConfig['model'],
            ];

            // Add filters if provided
            if (isset($options['filter_by'])) {
                $searchParams['filter_by'] = $options['filter_by'];
            }

            // Add sorting
            if (isset($options['sort_by'])) {
                $searchParams['sort_by'] = $options['sort_by'];
            } else {
                $searchParams['sort_by'] = 'publication_date:desc';
            }

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
            Log::channel('typesense_errors')->error('Typesense natural language search error', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);

            // Fallback to regular search
            return $this->search($query, $options);
        }
    }

    /**
     * Perform multiple searches in a single request
     *
     * @param  array  $searches  Array of search parameters
     * @param  array  $commonParams  Common parameters for all searches
     */
    public function multiSearch(array $searches, array $commonParams = []): array
    {
        try {
            $searchRequests = [
                'searches' => $searches,
            ];

            return $this->client->multiSearch->perform($searchRequests, $commonParams);
        } catch (\Exception $e) {
            Log::channel('typesense_errors')->error('Typesense multi-search error', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Semantic/hybrid search: keyword + vector combined.
     * Uses multi_search to handle large vector_query payloads.
     */
    public function semanticSearch(string $query, array $options = []): array
    {
        try {
            $embedding = app(EmbeddingService::class)->embed($query);

            if (!$embedding) {
                return $this->search($query, $options);
            }

            $searchParams = [
                'collection' => $this->collection,
                'q' => $query,
                'query_by' => 'title,description,content',
                'per_page' => $options['per_page'] ?? 20,
                'page' => $options['page'] ?? 1,
                'vector_query' => 'embedding:(' . json_encode($embedding) . ', k:' . (($options['per_page'] ?? 20) * 2) . ')',
                'search_cutoff_ms' => $options['search_cutoff_ms'] ?? 200,
                'highlight_full_fields' => 'title',
                'snippet_threshold' => 30,
                'typo_tolerance' => 'auto',
                'prefix' => 'true',
                'prioritize_exact_match' => true,
                'prioritize_token_position' => true,
                'exclude_fields' => 'embedding',
                'facet_by' => $options['facet_by'] ?? 'document_type,theme,organisation,category',
                'max_facet_values' => $options['max_facet_values'] ?? 100,
            ];

            if (isset($options['filter_by'])) {
                $searchParams['filter_by'] = $options['filter_by'];
            }
            if (isset($options['sort_by'])) {
                $searchParams['sort_by'] = $options['sort_by'];
            }

            $response = $this->client->multiSearch->perform(
                ['searches' => [$searchParams]],
                []
            );

            $results = $response['results'][0] ?? [];

            return [
                'hits' => $results['hits'] ?? [],
                'found' => $results['found'] ?? 0,
                'page' => $results['page'] ?? 1,
                'search_time_ms' => $results['search_time_ms'] ?? 0,
                'facet_counts' => $results['facet_counts'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::channel('typesense_errors')->error('Typesense semantic search error', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);

            return $this->search($query, $options);
        }
    }

    /**
     * Find similar documents by embedding vector of a given document.
     */
    public function findSimilar(string $documentId, int $limit = 5): array
    {
        try {
            // Try to get embedding from Typesense
            $doc = $this->getDocument($documentId);

            $embedding = $doc['embedding'] ?? null;

            // If no stored embedding, generate one on the fly
            if (empty($embedding) && $doc) {
                $embService = app(EmbeddingService::class);
                $text = $embService->buildDocumentText(
                    $doc['title'] ?? '',
                    $doc['description'] ?? null
                );
                $embedding = $embService->embed($text);
            }

            if (empty($embedding)) {
                return ['hits' => [], 'found' => 0];
            }

            $k = min($limit + 1, 100);

            $response = $this->client->multiSearch->perform(
                ['searches' => [[
                    'collection' => $this->collection,
                    'q' => '*',
                    'vector_query' => 'embedding:(' . json_encode($embedding) . ', k:' . $k . ')',
                    'exclude_fields' => 'embedding,content',
                    'per_page' => $k,
                ]]],
                []
            );

            $results = $response['results'][0] ?? [];

            // Filter out the source document
            $hits = array_filter($results['hits'] ?? [], fn($h) => ($h['document']['id'] ?? '') !== $documentId);
            $hits = array_values(array_slice($hits, 0, $limit));

            return [
                'hits' => $hits,
                'found' => count($hits),
                'search_time_ms' => $results['search_time_ms'] ?? 0,
            ];
        } catch (\Exception $e) {
            Log::channel('typesense_errors')->error('Typesense find similar error', [
                'document_id' => $documentId,
                'error' => $e->getMessage(),
            ]);

            return ['hits' => [], 'found' => 0];
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
            Log::channel('typesense_errors')->warning('Typesense document not found', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
