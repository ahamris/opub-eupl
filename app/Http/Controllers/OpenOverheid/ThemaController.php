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
                        // Fallback to FilterCountService if no facets
                        try {
                            $baseQuery = OpenOverheidDocument::whereNotNull('theme')->where('theme', '!=', 'Onbekend');
                            $filterCounts = $this->calculateFilterCounts($baseQuery, $validated);
                        } catch (\Exception $e) {
                            \Log::warning('FilterCountService failed in ThemaController', ['error' => $e->getMessage()]);
                            $filterCounts = [];
                        }
                    }
                    
                    // Get filter options using FilterCountService
                    try {
                        $allFilterOptions = $this->filterCountService->getAllFilterOptions($query);
                    } catch (\Exception $e) {
                        \Log::warning('getAllFilterOptions failed in ThemaController', ['error' => $e->getMessage()]);
                        $allFilterOptions = [];
                    }
                    
                    $formattedResults = $results;
                } catch (\Exception $typesenseException) {
                    // Fallback to PostgreSQL if Typesense fails
                    \Log::warning('Typesense search failed in ThemaController, falling back to PostgreSQL', [
                        'error' => $typesenseException->getMessage(),
                    ]);
                    $formattedResults = $this->searchWithPostgreSQL($query, $validated);
                    $filterCounts = $this->calculateFilterCounts(
                        OpenOverheidDocument::whereNotNull('theme')->where('theme', '!=', 'Onbekend'),
                        $validated
                    );
                    $allFilterOptions = $this->getAllFilterOptions(
                        OpenOverheidDocument::whereNotNull('theme')->where('theme', '!=', 'Onbekend')
                    );
                }
            } else {
                // Typesense disabled, use PostgreSQL
                $formattedResults = $this->searchWithPostgreSQL($query, $validated);
                $baseQuery = OpenOverheidDocument::whereNotNull('theme')->where('theme', '!=', 'Onbekend');
                $filterCounts = $this->calculateFilterCounts($baseQuery, $validated);
                $allFilterOptions = $this->getAllFilterOptions($baseQuery);
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
     * Calculate filter counts using optimized GROUP BY queries (fallback)
     * Uses caching to prevent repeated expensive queries
     */
    private function calculateFilterCounts($baseQuery, array $validated): array
    {
        // Cache filter counts to avoid repeated expensive queries
        $cacheKey = 'themas_filter_counts_'.md5(serialize($baseQuery->toSql()).serialize($baseQuery->getBindings()).serialize($validated));
        
        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use ($baseQuery, $validated) {
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
                // Calculate date filter counts (cached separately to avoid memory issues)
                $now = now();
                $counts['week'] = \Illuminate\Support\Facades\Cache::remember('themas_count_week', 3600, function () use ($baseQuery, $now) {
                    return (clone $baseQuery)->where('publication_date', '>=', $now->copy()->subWeek())->count();
                });
                $counts['maand'] = \Illuminate\Support\Facades\Cache::remember('themas_count_maand', 3600, function () use ($baseQuery, $now) {
                    return (clone $baseQuery)->where('publication_date', '>=', $now->copy()->subMonth())->count();
                });
                $counts['jaar'] = \Illuminate\Support\Facades\Cache::remember('themas_count_jaar', 3600, function () use ($baseQuery, $now) {
                    return (clone $baseQuery)->where('publication_date', '>=', $now->copy()->subYear())->count();
                });

                // OPTIMIZED: Use GROUP BY queries instead of N+1 queries
                // Reduced limit to 300 to prevent memory exhaustion with very large datasets
                $maxResults = 300;

                // Get document type counts in a single GROUP BY query
                try {
                    $counts['documentsoort'] = (clone $baseQuery)
                        ->whereNotNull('document_type')
                        ->selectRaw('document_type, COUNT(*) as count')
                        ->groupBy('document_type')
                        ->orderByDesc('count')
                        ->limit($maxResults)
                        ->pluck('count', 'document_type')
                        ->toArray();
                } catch (\Exception $e) {
                    \Log::warning('Failed to get document type counts', ['error' => $e->getMessage()]);
                    $counts['documentsoort'] = [];
                }

                // Get theme counts in a single GROUP BY query
                try {
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
                    \Log::warning('Failed to get theme counts', ['error' => $e->getMessage()]);
                    $counts['thema'] = [];
                }

                // Get organisation counts in a single GROUP BY query
                try {
                    $counts['organisatie'] = (clone $baseQuery)
                        ->whereNotNull('organisation')
                        ->selectRaw('organisation, COUNT(*) as count')
                        ->groupBy('organisation')
                        ->orderByDesc('count')
                        ->limit($maxResults)
                        ->pluck('count', 'organisation')
                        ->toArray();
                } catch (\Exception $e) {
                    \Log::warning('Failed to get organisation counts', ['error' => $e->getMessage()]);
                    $counts['organisatie'] = [];
                }

                // Get category counts in a single GROUP BY query
                try {
                    $counts['informatiecategorie'] = (clone $baseQuery)
                        ->whereNotNull('category')
                        ->selectRaw('category, COUNT(*) as count')
                        ->groupBy('category')
                        ->orderByDesc('count')
                        ->limit($maxResults)
                        ->pluck('count', 'category')
                        ->toArray();
                } catch (\Exception $e) {
                    \Log::warning('Failed to get category counts', ['error' => $e->getMessage()]);
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
     * Uses optimized queries with limits and caching to prevent memory exhaustion.
     */
    private function getAllFilterOptions($baseQuery): array
    {
        // Cache the results to avoid repeated expensive queries
        $cacheKey = 'themas_filter_options_'.md5(serialize($baseQuery->toSql()).serialize($baseQuery->getBindings()));
        
        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use ($baseQuery) {
            try {
                // Reduce limit to 500 to prevent memory exhaustion with large datasets
                $maxResults = 500;

                // Get unique document types (limited and ordered by frequency)
                try {
                    $allDocumentTypes = (clone $baseQuery)
                        ->whereNotNull('document_type')
                        ->selectRaw('document_type, COUNT(*) as count')
                        ->groupBy('document_type')
                        ->orderByDesc('count')
                        ->limit($maxResults)
                        ->pluck('document_type')
                        ->filter()
                        ->sort()
                        ->values()
                        ->toArray();
                } catch (\Exception $e) {
                    \Log::warning('Failed to get document types in getAllFilterOptions', ['error' => $e->getMessage()]);
                    $allDocumentTypes = [];
                }

                // Get unique themes (limited and ordered by frequency)
                try {
                    $allThemes = (clone $baseQuery)
                        ->whereNotNull('theme')
                        ->where('theme', '!=', 'Onbekend')
                        ->selectRaw('theme, COUNT(*) as count')
                        ->groupBy('theme')
                        ->orderByDesc('count')
                        ->limit($maxResults)
                        ->pluck('theme')
                        ->filter()
                        ->sort()
                        ->values()
                        ->toArray();
                } catch (\Exception $e) {
                    \Log::warning('Failed to get themes in getAllFilterOptions', ['error' => $e->getMessage()]);
                    $allThemes = [];
                }

                // Get unique organisations (limited and ordered by frequency)
                try {
                    $allOrganisations = (clone $baseQuery)
                        ->whereNotNull('organisation')
                        ->selectRaw('organisation, COUNT(*) as count')
                        ->groupBy('organisation')
                        ->orderByDesc('count')
                        ->limit($maxResults)
                        ->pluck('organisation')
                        ->filter()
                        ->sort()
                        ->values()
                        ->toArray();
                } catch (\Exception $e) {
                    \Log::warning('Failed to get organisations in getAllFilterOptions', ['error' => $e->getMessage()]);
                    $allOrganisations = [];
                }

                // Get unique information categories (limited and ordered by frequency)
                try {
                    $wooCategoryService = app(\App\Services\OpenOverheid\WooCategoryService::class);
                    $allCategories = (clone $baseQuery)
                        ->whereNotNull('category')
                        ->where('category', '!=', 'Onbekend')
                        ->selectRaw('category, COUNT(*) as count')
                        ->groupBy('category')
                        ->orderByDesc('count')
                        ->limit($maxResults)
                        ->pluck('category')
                        ->filter()
                        ->map(function ($category) use ($wooCategoryService) {
                            return $wooCategoryService->formatCategoryForDisplay($category) ?? $category;
                        })
                        ->filter()
                        ->unique()
                        ->sort()
                        ->values()
                        ->toArray();
                } catch (\Exception $e) {
                    \Log::warning('Failed to get categories in getAllFilterOptions', ['error' => $e->getMessage()]);
                    $allCategories = [];
                }

                return [
                    'documentsoort' => $allDocumentTypes,
                    'thema' => $allThemes,
                    'organisatie' => $allOrganisations,
                    'informatiecategorie' => $allCategories,
                ];
            } catch (\Exception $e) {
                \Log::error('getAllFilterOptions failed in ThemaController', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                
                // Return empty arrays on error to prevent crash
                return [
                    'documentsoort' => [],
                    'thema' => [],
                    'organisatie' => [],
                    'informatiecategorie' => [],
                ];
            }
        });
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
