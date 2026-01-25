<?php

namespace App\Http\Controllers\OpenOverheid;

use App\DataTransferObjects\OpenOverheid\OpenOverheidSearchQuery;
use App\Http\Controllers\Controller;
use App\Models\OpenOverheidDocument;
use App\Services\OpenOverheid\OpenOverheidLocalSearchService;
use App\Services\OpenOverheid\FilterCountService;
use Illuminate\Http\Request;

class ThemaController extends Controller
{
    public function __construct(
        private readonly OpenOverheidLocalSearchService $localService,
        private readonly FilterCountService $filterCountService
    ) {}

    /**
     * Display a listing of all themes.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'zoeken' => ['nullable', 'string'],
            'pagina' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'in:10,20,50,100'],
            'beschikbaarSinds' => ['nullable', 'string', 'in:week,maand,jaar,zelf'],
            'publicatiedatum_van' => ['nullable', 'date_format:d-m-Y'],
            'publicatiedatum_tot' => ['nullable', 'date_format:d-m-Y'],
            'documentsoort' => ['nullable'],
            'documentsoort.*' => ['string'],
            'informatiecategorie' => ['nullable'],
            'informatiecategorie.*' => ['string'],
            'organisatie' => ['nullable'],
            'organisatie.*' => ['string'],
            'thema' => ['nullable'],
            'thema.*' => ['string'],
            'sort' => ['nullable', 'string', 'in:relevance,publication_date,modified_date'],
            'titles_only' => ['nullable', 'boolean'],
        ]);

        // Handle beschikbaarSinds date filter
        $publicatiedatumVan = $validated['publicatiedatum_van'] ?? null;
        $publicatiedatumTot = $validated['publicatiedatum_tot'] ?? null;

        if (! empty($validated['beschikbaarSinds'])) {
            $now = now();
            switch ($validated['beschikbaarSinds']) {
                case 'week':
                    $publicatiedatumVan = $now->copy()->subWeek()->format('d-m-Y');
                    break;
                case 'maand':
                    $publicatiedatumVan = $now->copy()->subMonth()->format('d-m-Y');
                    break;
                case 'jaar':
                    $publicatiedatumVan = $now->copy()->subYear()->format('d-m-Y');
                    break;
            }
        }

        // Handle array filters
        $documentsoort = $validated['documentsoort'] ?? null;
        $thema = $validated['thema'] ?? null;
        $organisatie = $validated['organisatie'] ?? null;

        // Build query - all documents must have a theme
        $query = new OpenOverheidSearchQuery(
            zoektekst: $validated['zoeken'] ?? '',
            page: (int) ($validated['pagina'] ?? 1),
            perPage: (int) ($validated['per_page'] ?? 20),
            publicatiedatumVan: $publicatiedatumVan,
            publicatiedatumTot: $publicatiedatumTot,
            documentsoort: $documentsoort,
            informatiecategorie: is_array($validated['informatiecategorie'] ?? null)
                ? ($validated['informatiecategorie'][0] ?? null)
                : ($validated['informatiecategorie'] ?? null),
            thema: $thema,
            organisatie: $organisatie,
            sort: $validated['sort'] ?? 'relevance',
            titlesOnly: ! empty($validated['titles_only']),
        );

        try {
            // Use Typesense as primary search engine (much faster with 600k+ records)
            $useTypesense = config('open_overheid.typesense.enabled', true);
            $filterCounts = [];
            $allFilterOptions = [];

            if ($useTypesense) {
                try {
                    // Use Typesense search with theme filter
                    $results = $this->searchWithTypesense($query, true); // true = themes only
                    
                    // Extract facet counts from Typesense results
                    if (isset($results['facet_counts']) && ! empty($results['facet_counts'])) {
                        $filterCounts = $this->convertTypesenseFacetsToFilterCounts($results['facet_counts'], $query);
                        
                        // Calculate date filter counts using Typesense (not database!)
                        try {
                            $filterCounts['week'] = $this->getDateFilterCountFromTypesense($query, 'week');
                            $filterCounts['maand'] = $this->getDateFilterCountFromTypesense($query, 'maand');
                            $filterCounts['jaar'] = $this->getDateFilterCountFromTypesense($query, 'jaar');
                        } catch (\Exception $dateException) {
                            \Log::warning('Date filter counts failed in ThemaController', ['error' => $dateException->getMessage()]);
                        }
                    } else {
                        // No facets from Typesense - this should not happen, but if it does, use empty arrays
                        // DO NOT fall back to database queries with 600k+ records - it will cause memory exhaustion
                        \Log::warning('Typesense returned no facets in ThemaController - using empty filter counts');
                        $filterCounts = [];
                    }
                    
                    // Get filter options from Typesense facets (NOT from database)
                    // For 600k+ records, we MUST use Typesense, never query the database
                    try {
                        if (isset($results['facet_counts']) && !empty($results['facet_counts'])) {
                            $allFilterOptions = $this->extractFilterOptionsFromFacets($results['facet_counts']);
                        } else {
                            // If no facets, return empty - DO NOT query database
                            $allFilterOptions = [];
                        }
                    } catch (\Exception $e) {
                        \Log::warning('Failed to extract filter options from Typesense facets', ['error' => $e->getMessage()]);
                        $allFilterOptions = [];
                    }
                    
                    $formattedResults = $results;
                } catch (\Exception $typesenseException) {
                    // Fallback to PostgreSQL if Typesense fails
                    // BUT: For 600k+ records, avoid expensive GROUP BY queries
                    \Log::warning('Typesense search failed in ThemaController, falling back to PostgreSQL', [
                        'error' => $typesenseException->getMessage(),
                    ]);
                    $formattedResults = $this->searchWithPostgreSQL($query, $validated);
                    
                    // Use cached/lightweight filter counts - avoid expensive GROUP BY queries
                    // Only calculate if absolutely necessary and with strict limits
                    try {
                        $baseQuery = OpenOverheidDocument::whereNotNull('theme')->where('theme', '!=', 'Onbekend');
                        $filterCounts = $this->calculateFilterCounts($baseQuery, $validated);
                    } catch (\Exception $e) {
                        \Log::error('FilterCountService failed in fallback - using empty counts', ['error' => $e->getMessage()]);
                        $filterCounts = [];
                    }
                    
                    // DO NOT call getAllFilterOptions on 600k+ records - it will cause memory exhaustion
                    // Return empty filter options instead
                    \Log::warning('Skipping getAllFilterOptions in fallback to prevent memory exhaustion with 600k+ records');
                    $allFilterOptions = [];
                }
            } else {
                // Typesense disabled, use PostgreSQL
                // WARNING: With 600k+ records, this will be slow and may cause memory issues
                \Log::warning('Typesense is disabled - using PostgreSQL fallback (may be slow with 600k+ records)');
                $formattedResults = $this->searchWithPostgreSQL($query, $validated);
                $baseQuery = OpenOverheidDocument::whereNotNull('theme')->where('theme', '!=', 'Onbekend');
                
                try {
                    $filterCounts = $this->calculateFilterCounts($baseQuery, $validated);
                } catch (\Exception $e) {
                    \Log::error('FilterCountService failed - using empty counts', ['error' => $e->getMessage()]);
                    $filterCounts = [];
                }
                
                // DO NOT call getAllFilterOptions on 600k+ records when Typesense is disabled
                // It will cause memory exhaustion - return empty instead
                \Log::warning('Skipping getAllFilterOptions - Typesense disabled and table has 600k+ records');
                $allFilterOptions = [];
            }

            // Cache document count to avoid repeated queries
            $documentCount = \Illuminate\Support\Facades\Cache::remember('themas_document_count', 3600, function () {
                return OpenOverheidDocument::whereNotNull('theme')
                    ->where('theme', '!=', 'Onbekend')
                    ->count();
            });

            return view('themas.index', [
                'results' => $formattedResults,
                'query' => $query,
                'documentCount' => $documentCount,
                'filters' => $validated,
                'filterCounts' => $filterCounts,
                'allFilterOptions' => $allFilterOptions,
            ]);
        } catch (\Exception $e) {
            \Log::error('Thema search error', ['error' => $e->getMessage()]);

            // Use cached count in error case too
            $documentCount = \Illuminate\Support\Facades\Cache::remember('themas_document_count', 3600, function () {
                return OpenOverheidDocument::whereNotNull('theme')
                    ->where('theme', '!=', 'Onbekend')
                    ->count();
            });
            
            return view('themas.index', [
                'results' => ['items' => [], 'total' => 0],
                'query' => $query,
                'documentCount' => $documentCount,
                'filters' => $validated,
                'filterCounts' => [],
                'allFilterOptions' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Search using Typesense (optimized for themes)
     */
    private function searchWithTypesense(OpenOverheidSearchQuery $query, bool $themesOnly = false): array
    {
        $searchService = app(\App\Services\Typesense\TypesenseSearchService::class);

        // Build filter_by string - always filter for themes only
        $filters = ['theme:!=Onbekend'];
        if ($themesOnly) {
            $filters[] = 'theme:!=null';
        }

        if ($query->documentsoort) {
            $types = is_array($query->documentsoort) ? $query->documentsoort : [$query->documentsoort];
            $filters[] = 'document_type:['.implode(',', array_map(fn ($t) => '='.$t, $types)).']';
        }
        if ($query->informatiecategorie) {
            $filters[] = 'category:='.$query->informatiecategorie;
        }
        if ($query->thema) {
            $themes = is_array($query->thema) ? $query->thema : [$query->thema];
            $filters[] = 'theme:['.implode(',', array_map(fn ($t) => '='.$t, $themes)).']';
        }
        if ($query->organisatie) {
            $orgs = is_array($query->organisatie) ? $query->organisatie : [$query->organisatie];
            $filters[] = 'organisation:['.implode(',', array_map(fn ($o) => '='.$o, $orgs)).']';
        }
        if ($query->publicatiedatumVan || $query->publicatiedatumTot) {
            $dateFilters = [];
            if ($query->publicatiedatumVan) {
                $fromDate = \DateTime::createFromFormat('d-m-Y', $query->publicatiedatumVan);
                if ($fromDate) {
                    $dateFilters[] = '>='.$fromDate->getTimestamp();
                }
            }
            if ($query->publicatiedatumTot) {
                $toDate = \DateTime::createFromFormat('d-m-Y', $query->publicatiedatumTot);
                if ($toDate) {
                    $dateFilters[] = '<='.$toDate->getTimestamp();
                }
            }
            if (! empty($dateFilters)) {
                $filters[] = 'publication_date:['.implode(',', $dateFilters).']';
            }
        }

        // Build sort_by
        $sortBy = 'publication_date:desc';
        switch ($query->sort) {
            case 'publication_date':
                $sortBy = 'publication_date:desc';
                break;
            case 'modified_date':
                $sortBy = 'synced_at:desc';
                break;
            case 'relevance':
            default:
                $sortBy = 'publication_date:desc';
                break;
        }

        $options = [
            'per_page' => $query->perPage,
            'page' => $query->page,
            'sort_by' => $sortBy,
            'facet_by' => 'document_type,theme,organisation,category',
            'max_facet_values' => 500,
        ];

        if (! empty($filters)) {
            $options['filter_by'] = implode(' && ', $filters);
        }

        $typesenseResults = $searchService->search($query->zoektekst ?? '', $options);

        // Transform Typesense results
        // Optimize: Load all models in one query instead of N+1 queries
        $externalIds = collect($typesenseResults['hits'] ?? [])
            ->map(fn ($hit) => $hit['document']['external_id'] ?? $hit['external_id'] ?? null)
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // Load all models in one query
        $models = !empty($externalIds)
            ? \App\Models\OpenOverheidDocument::whereIn('external_id', $externalIds)
                ->get()
                ->keyBy('external_id')
            : collect();

        $items = collect($typesenseResults['hits'] ?? [])->map(function ($hit) use ($models) {
            $doc = $hit['document'] ?? $hit;
            $externalId = $doc['external_id'] ?? null;
            
            // Use pre-loaded model if available
            $model = $externalId ? ($models[$externalId] ?? null) : null;

            if ($model) {
                return $model;
            }

            // Fallback to object if model not found
            return (object) [
                'id' => $doc['id'] ?? null,
                'external_id' => $externalId,
                'title' => $doc['title'] ?? 'Geen titel',
                'description' => $doc['description'] ?? '',
                'content' => $doc['content'] ?? '',
                'publication_date' => isset($doc['publication_date']) && $doc['publication_date'] > 0
                    ? \Carbon\Carbon::createFromTimestamp($doc['publication_date'])
                    : null,
                'document_type' => $doc['document_type'] ?? null,
                'category' => $doc['category'] ?? null,
                'theme' => $doc['theme'] ?? null,
                'organisation' => $doc['organisation'] ?? null,
                'updated_at' => isset($doc['synced_at']) && $doc['synced_at'] > 0
                    ? \Carbon\Carbon::createFromTimestamp($doc['synced_at'])
                    : now(),
            ];
        });

        return [
            'items' => $items,
            'total' => $typesenseResults['found'] ?? 0,
            'page' => $typesenseResults['page'] ?? $query->page,
            'perPage' => $query->perPage,
            'hasNextPage' => ($typesenseResults['page'] ?? $query->page) * $query->perPage < ($typesenseResults['found'] ?? 0),
            'hasPreviousPage' => ($typesenseResults['page'] ?? $query->page) > 1,
            'facet_counts' => $typesenseResults['facet_counts'] ?? [],
        ];
    }

    /**
     * Search using PostgreSQL (fallback)
     */
    private function searchWithPostgreSQL(OpenOverheidSearchQuery $query, array $validated): array
    {
        $baseQuery = OpenOverheidDocument::whereNotNull('theme')
            ->where('theme', '!=', 'Onbekend');

        if (! empty($query->zoektekst)) {
            if (! empty($query->titlesOnly)) {
                $likeOp = config('database.default') === 'pgsql' ? 'ilike' : 'like';
                $baseQuery->where('title', $likeOp, '%'.$query->zoektekst.'%');
            } else {
                $baseQuery->whereFullText(['title', 'description', 'content'], $query->zoektekst);
            }
        }

        $baseQuery->dateRange($query->publicatiedatumVan, $query->publicatiedatumTot);
        $baseQuery->byDocumentType($query->documentsoort);
        $baseQuery->byCategory($query->informatiecategorie);
        $baseQuery->byTheme($query->thema);
        $baseQuery->byOrganisation($query->organisatie);

        switch ($query->sort ?? 'relevance') {
            case 'publication_date':
                $baseQuery->orderBy('publication_date', 'desc');
                break;
            case 'modified_date':
                $baseQuery->orderBy('updated_at', 'desc');
                break;
            case 'relevance':
            default:
                $baseQuery->orderBy('publication_date', 'desc');
                break;
        }

        $results = $baseQuery->paginate($query->perPage, ['*'], 'page', (int) ($validated['pagina'] ?? 1));

        return [
            'items' => $results->items(),
            'total' => $results->total(),
            'page' => $results->currentPage(),
            'perPage' => $results->perPage(),
            'hasNextPage' => $results->hasMorePages(),
            'hasPreviousPage' => $results->currentPage() > 1,
        ];
    }

    /**
     * Extract filter options from Typesense facets (for "Toon meer" functionality)
     * This avoids querying the database with 600k+ records
     */
    private function extractFilterOptionsFromFacets(array $facetCounts): array
    {
        $options = [
            'documentsoort' => [],
            'thema' => [],
            'organisatie' => [],
            'informatiecategorie' => [],
        ];

        if (empty($facetCounts) || !is_array($facetCounts)) {
            return $options;
        }

        foreach ($facetCounts as $facetGroup) {
            if (!is_array($facetGroup)) {
                continue;
            }

            $fieldName = $facetGroup['field_name'] ?? $facetGroup['fieldName'] ?? null;
            $countsArray = $facetGroup['counts'] ?? [];

            if (!$fieldName || empty($countsArray)) {
                continue;
            }

            $optionKey = match ($fieldName) {
                'document_type' => 'documentsoort',
                'theme' => 'thema',
                'organisation' => 'organisatie',
                'category' => 'informatiecategorie',
                default => null,
            };

            if ($optionKey) {
                foreach ($countsArray as $facet) {
                    if (!is_array($facet)) {
                        continue;
                    }
                    $value = $facet['value'] ?? null;
                    if ($value) {
                        $options[$optionKey][] = $value;
                    }
                }
                
                // Sort and limit to top 500 most common
                $options[$optionKey] = array_slice(array_unique($options[$optionKey]), 0, 500);
                sort($options[$optionKey]);
            }
        }

        return $options;
    }

    /**
     * Convert Typesense facet_counts to filter counts format
     */
    private function convertTypesenseFacetsToFilterCounts(array $facetCounts, OpenOverheidSearchQuery $query): array
    {
        $counts = [
            'week' => 0,
            'maand' => 0,
            'jaar' => 0,
            'documentsoort' => [],
            'thema' => [],
            'organisatie' => [],
            'informatiecategorie' => [],
        ];

        if (is_array($facetCounts) && ! empty($facetCounts)) {
            foreach ($facetCounts as $facetGroup) {
                if (! is_array($facetGroup)) {
                    continue;
                }

                $fieldName = $facetGroup['field_name'] ?? $facetGroup['fieldName'] ?? null;
                $countsArray = $facetGroup['counts'] ?? [];

                if (! $fieldName || empty($countsArray)) {
                    continue;
                }

                $countKey = match ($fieldName) {
                    'document_type' => 'documentsoort',
                    'theme' => 'thema',
                    'organisation' => 'organisatie',
                    'category' => 'informatiecategorie',
                    default => null,
                };

                if ($countKey) {
                    foreach ($countsArray as $facet) {
                        if (! is_array($facet)) {
                            continue;
                        }
                        $value = $facet['value'] ?? null;
                        $count = $facet['count'] ?? 0;
                        if ($value && $count > 0) {
                            $counts[$countKey][$value] = $count;
                        }
                    }
                }
            }
        }

        return $counts;
    }

    /**
     * Calculate filter counts using optimized GROUP BY queries (fallback ONLY)
     * WARNING: With 600k+ records, this should be avoided - use Typesense facets instead
     * Uses aggressive caching and limits to prevent memory exhaustion
     */
    private function calculateFilterCounts($baseQuery, array $validated): array
    {
        // Cache filter counts to avoid repeated expensive queries
        $cacheKey = 'themas_filter_counts_'.md5(serialize($baseQuery->toSql()).serialize($baseQuery->getBindings()).serialize($validated));
        
        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 7200, function () use ($baseQuery, $validated) {
            $counts = [
                'week' => 0,
                'maand' => 0,
                'jaar' => 0,
                'documentsoort' => [],
                'thema' => [],
                'organisatie' => [],
                'informatiecategorie' => [],
            ];

            try {
                // Calculate date filter counts (cached separately with longer TTL)
                // Use simple COUNT queries with indexes - these are relatively safe
                $now = now();
                $counts['week'] = \Illuminate\Support\Facades\Cache::remember('themas_count_week', 7200, function () use ($baseQuery, $now) {
                    try {
                        return (clone $baseQuery)
                            ->where('publication_date', '>=', $now->copy()->subWeek())
                            ->limit(1000000) // Safety limit - should never hit this
                            ->count();
                    } catch (\Exception $e) {
                        \Log::error('Date count query failed', ['error' => $e->getMessage()]);
                        return 0;
                    }
                });
                $counts['maand'] = \Illuminate\Support\Facades\Cache::remember('themas_count_maand', 7200, function () use ($baseQuery, $now) {
                    try {
                        return (clone $baseQuery)
                            ->where('publication_date', '>=', $now->copy()->subMonth())
                            ->limit(1000000)
                            ->count();
                    } catch (\Exception $e) {
                        \Log::error('Date count query failed', ['error' => $e->getMessage()]);
                        return 0;
                    }
                });
                $counts['jaar'] = \Illuminate\Support\Facades\Cache::remember('themas_count_jaar', 7200, function () use ($baseQuery, $now) {
                    try {
                        return (clone $baseQuery)
                            ->where('publication_date', '>=', $now->copy()->subYear())
                            ->limit(1000000)
                            ->count();
                    } catch (\Exception $e) {
                        \Log::error('Date count query failed', ['error' => $e->getMessage()]);
                        return 0;
                    }
                });

                // OPTIMIZED: Use GROUP BY queries with VERY aggressive limits for 600k+ records
                // WARNING: Even with limits, GROUP BY on 600k+ records can be slow
                // Consider using Typesense facets instead whenever possible
                $maxResults = 200; // Further reduced for 600k+ records

                // Get document type counts - use timeout and memory limit protection
                try {
                    set_time_limit(30); // 30 second timeout per query
                    $counts['documentsoort'] = (clone $baseQuery)
                        ->whereNotNull('document_type')
                        ->selectRaw('document_type, COUNT(*) as count')
                        ->groupBy('document_type')
                        ->orderByDesc('count')
                        ->limit($maxResults)
                        ->pluck('count', 'document_type')
                        ->toArray();
                } catch (\Exception $e) {
                    \Log::error('Failed to get document type counts (600k+ records)', ['error' => $e->getMessage()]);
                    $counts['documentsoort'] = [];
                }

                // Get theme counts
                try {
                    set_time_limit(30);
                    $counts['thema'] = (clone $baseQuery)
                        ->whereNotNull('theme')
                        ->where('theme', '!=', 'Onbekend')
                        ->selectRaw('theme, COUNT(*) as count')
                        ->groupBy('theme')
                        ->orderByDesc('count')
                        ->limit($maxResults)
                        ->pluck('count', 'theme')
                        ->toArray();
                } catch (\Exception $e) {
                    \Log::error('Failed to get theme counts (600k+ records)', ['error' => $e->getMessage()]);
                    $counts['thema'] = [];
                }

                // Get organisation counts
                try {
                    set_time_limit(30);
                    $counts['organisatie'] = (clone $baseQuery)
                        ->whereNotNull('organisation')
                        ->selectRaw('organisation, COUNT(*) as count')
                        ->groupBy('organisation')
                        ->orderByDesc('count')
                        ->limit($maxResults)
                        ->pluck('count', 'organisation')
                        ->toArray();
                } catch (\Exception $e) {
                    \Log::error('Failed to get organisation counts (600k+ records)', ['error' => $e->getMessage()]);
                    $counts['organisatie'] = [];
                }

                // Get category counts
                try {
                    set_time_limit(30);
                    $counts['informatiecategorie'] = (clone $baseQuery)
                        ->whereNotNull('category')
                        ->selectRaw('category, COUNT(*) as count')
                        ->groupBy('category')
                        ->orderByDesc('count')
                        ->limit($maxResults)
                        ->pluck('count', 'category')
                        ->toArray();
                } catch (\Exception $e) {
                    \Log::error('Failed to get category counts (600k+ records)', ['error' => $e->getMessage()]);
                    $counts['informatiecategorie'] = [];
                }
            } catch (\Exception $e) {
                \Log::warning('calculateFilterCounts failed in ThemaController', ['error' => $e->getMessage()]);
            }

            return $counts;
        });
    }

    /**
     * Get all available filter options for "Toon meer" functionality (themes domain only).
     * WARNING: With 600k+ records, this method should be AVOIDED - use Typesense facets instead
     * This method is kept only as a last resort fallback
     */
    private function getAllFilterOptions($baseQuery): array
    {
        // DO NOT use this method with 600k+ records - it will cause memory exhaustion
        // This should only be called when Typesense is completely unavailable
        \Log::warning('getAllFilterOptions called - this is dangerous with 600k+ records. Use Typesense facets instead.');
        
        // Return empty arrays to prevent memory exhaustion
        // If filter options are needed, they should come from Typesense facets
        return [
            'documentsoort' => [],
            'thema' => [],
            'organisatie' => [],
            'informatiecategorie' => [],
        ];
    }

    /**
     * Get date filter count from Typesense (not database!)
     * Makes a lightweight Typesense query with same filters + date range
     */
    private function getDateFilterCountFromTypesense(OpenOverheidSearchQuery $query, string $period): int
    {
        try {
            $searchService = app(\App\Services\Typesense\TypesenseSearchService::class);
            $now = now();
            
            // Calculate date threshold based on period
            $dateThreshold = match ($period) {
                'week' => $now->copy()->subWeek()->timestamp,
                'maand' => $now->copy()->subMonth()->timestamp,
                'jaar' => $now->copy()->subYear()->timestamp,
                default => 0,
            };
            
            if ($dateThreshold <= 0) {
                return 0;
            }
            
            // Build same filters as main search (including theme filter for themes page)
            $filters = ['theme:!=Onbekend']; // Themes page always filters out "Onbekend"
            
            if ($query->documentsoort) {
                $types = is_array($query->documentsoort) ? $query->documentsoort : [$query->documentsoort];
                $types = array_filter(array_map('trim', $types));
                if (! empty($types)) {
                    $escapedTypes = array_map(function ($t) {
                        $t = str_replace(['"', "'"], '', $t);
                        return '='.$t;
                    }, $types);
                    $filters[] = 'document_type:['.implode(',', $escapedTypes).']';
                }
            }
            if ($query->informatiecategorie) {
                $category = trim($query->informatiecategorie);
                if (! empty($category)) {
                    $category = str_replace(['"', "'"], '', $category);
                    $filters[] = 'category:='.$category;
                }
            }
            if ($query->thema) {
                $themes = is_array($query->thema) ? $query->thema : [$query->thema];
                $themes = array_filter(array_map('trim', $themes));
                if (! empty($themes)) {
                    $escapedThemes = array_map(function ($t) {
                        $t = str_replace(['"', "'"], '', $t);
                        return '='.$t;
                    }, $themes);
                    $filters[] = 'theme:['.implode(',', $escapedThemes).']';
                }
            }
            if ($query->organisatie) {
                $orgs = is_array($query->organisatie) ? $query->organisatie : [$query->organisatie];
                $orgs = array_filter(array_map('trim', $orgs));
                if (! empty($orgs)) {
                    $escapedOrgs = array_map(function ($o) {
                        $o = str_replace(['"', "'"], '', $o);
                        return '='.$o;
                    }, $orgs);
                    $filters[] = 'organisation:['.implode(',', $escapedOrgs).']';
                }
            }
            
            // Add date range filters (existing + period threshold)
            $dateFilters = [];
            if ($query->publicatiedatumVan) {
                $fromDate = \DateTime::createFromFormat('d-m-Y', $query->publicatiedatumVan);
                if ($fromDate) {
                    $dateFilters[] = '>='.$fromDate->getTimestamp();
                }
            }
            // Use the period threshold as minimum date (week/month/year ago)
            $dateFilters[] = '>='.$dateThreshold;
            
            if ($query->publicatiedatumTot) {
                $toDate = \DateTime::createFromFormat('d-m-Y', $query->publicatiedatumTot);
                if ($toDate) {
                    $dateFilters[] = '<='.$toDate->getTimestamp();
                }
            }
            if (! empty($dateFilters)) {
                $filters[] = 'publication_date:['.implode(',', $dateFilters).']';
            }
            
            // Make lightweight Typesense query (per_page=0 to get only count)
            $options = [
                'per_page' => 0, // Don't fetch documents, just get count
                'page' => 1,
                'filter_by' => implode(' && ', $filters),
            ];
            
            $results = $searchService->search($query->zoektekst ?? '', $options);
            
            return $results['found'] ?? 0;
        } catch (\Exception $e) {
            \Log::warning('Typesense date filter count failed', [
                'period' => $period,
                'error' => $e->getMessage(),
            ]);
            return 0;
        }
    }
}
