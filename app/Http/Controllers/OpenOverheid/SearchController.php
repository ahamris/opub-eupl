<?php

namespace App\Http\Controllers\OpenOverheid;

use App\DataTransferObjects\OpenOverheid\OpenOverheidSearchQuery;
use App\Http\Controllers\Controller;
use App\Models\OpenOverheidDocument;
use App\Services\OpenOverheid\OpenOverheidLocalSearchService;
use App\Services\OpenOverheid\OpenOverheidSearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        private readonly OpenOverheidLocalSearchService $localService,
        private readonly OpenOverheidSearchService $remoteService
    ) {}

    public function searchPage(Request $request)
    {
        $documentCount = OpenOverheidDocument::count();

        // Get recent documents (last 6 published documents)
        $recentDocuments = OpenOverheidDocument::whereNotNull('publication_date')
            ->orderBy('publication_date', 'desc')
            ->limit(6)
            ->get();

        // Get statistics (cached for 5 minutes)
        $statistics = \Illuminate\Support\Facades\Cache::remember('open_overheid:statistics', 300, function () {
            $categoryCount = OpenOverheidDocument::whereNotNull('category')
                ->distinct('category')
                ->count('category');

            $themeCount = OpenOverheidDocument::whereNotNull('theme')
                ->distinct('theme')
                ->count('theme');

            $organisationCount = OpenOverheidDocument::whereNotNull('organisation')
                ->distinct('organisation')
                ->count('organisation');

            // Get top categories by count
            $topCategories = OpenOverheidDocument::whereNotNull('category')
                ->selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('count', 'category')
                ->toArray();

            // Get top themes by count
            $topThemes = OpenOverheidDocument::whereNotNull('theme')
                ->selectRaw('theme, COUNT(*) as count')
                ->groupBy('theme')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('count', 'theme')
                ->toArray();

            return [
                'categoryCount' => $categoryCount,
                'themeCount' => $themeCount,
                'organisationCount' => $organisationCount,
                'topCategories' => $topCategories,
                'topThemes' => $topThemes,
            ];
        });

        $categoryCount = $statistics['categoryCount'];
        $themeCount = $statistics['themeCount'];
        $organisationCount = $statistics['organisationCount'];
        $topCategories = $statistics['topCategories'];
        $topThemes = $statistics['topThemes'];

        return view('zoek', [
            'documentCount' => $documentCount,
            'recentDocuments' => $recentDocuments,
            'statistics' => [
                'totalDocuments' => $documentCount,
                'categoryCount' => $categoryCount,
                'themeCount' => $themeCount,
                'organisationCount' => $organisationCount,
                'topCategories' => $topCategories,
                'topThemes' => $topThemes,
            ],
        ]);
    }

    public function v2026LandingPage(Request $request)
    {
        $documentCount = OpenOverheidDocument::count();

        return view('v2026', [
            'documentCount' => $documentCount,
        ]);
    }

    public function newLandingPage(Request $request)
    {
        $documentCount = OpenOverheidDocument::count();

        // Get unique theme count
        $themeCount = OpenOverheidDocument::whereNotNull('theme')
            ->distinct('theme')
            ->count('theme');

        // Get unique document type count for "Dossiers"
        $dossierCount = OpenOverheidDocument::whereNotNull('document_type')
            ->distinct('document_type')
            ->count('document_type');

        return view('new', [
            'documentCount' => $documentCount,
            'themeCount' => $themeCount ?: 0,
            'dossierCount' => $dossierCount ?: 0,
        ]);
    }

    public function aboutPage(Request $request)
    {
        return view('over');
    }

    /**
     * Autocomplete endpoint for query suggestions
     */
    public function autocomplete(Request $request)
    {
        $query = $request->get('q', '');
        $limit = min((int) $request->get('limit', 8), 10);

        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'suggestions' => [],
            ]);
        }

        try {
            $searchService = app(\App\Services\Typesense\TypesenseSearchService::class);

            // Get search results for autocomplete with advanced search features
            $results = $searchService->search($query, [
                'per_page' => $limit,
                'page' => 1,
                'typo_tolerance' => 'auto', // Enable typo tolerance
                'prefix' => 'true', // Enable prefix matching for instant search
            ]);

            // Extract unique suggestions from titles and descriptions
            $suggestions = [];
            $seen = [];

            foreach ($results['hits'] ?? [] as $hit) {
                $document = $hit['document'] ?? $hit;
                $title = $document['title'] ?? '';

                // Extract meaningful phrases from title - prioritize exact matches
                if (! empty($title) && ! isset($seen[$title])) {
                    $suggestions[] = [
                        'query' => $title,
                        'type' => 'document',
                        'highlight' => $this->highlightQuery($title, $query),
                        'id' => $document['external_id'] ?? $document['id'] ?? null,
                        'category' => $this->formatCategory($document['category'] ?? null),
                    ];
                    $seen[$title] = true;
                }
            }

            // Also suggest common query patterns
            if (count($suggestions) < $limit) {
                $commonQueries = $this->getCommonQueries($query);
                foreach ($commonQueries as $commonQuery) {
                    if (! isset($seen[$commonQuery]) && count($suggestions) < $limit) {
                        $suggestions[] = [
                            'query' => $commonQuery,
                            'type' => 'suggestion',
                            'highlight' => $this->highlightQuery($commonQuery, $query),
                        ];
                        $seen[$commonQuery] = true;
                    }
                }
            }

            return response()->json([
                'suggestions' => array_slice($suggestions, 0, $limit),
            ]);
        } catch (\Exception $e) {
            \Log::error('Autocomplete error', ['error' => $e->getMessage()]);

            return response()->json(['suggestions' => []], 500);
        }
    }

    /**
     * Highlight query terms in text
     */
    private function highlightQuery(string $text, string $query): string
    {
        $terms = explode(' ', trim($query));
        foreach ($terms as $term) {
            if (strlen($term) > 2) {
                $text = preg_replace(
                    '/('.preg_quote($term, '/').')/i',
                    '<mark>$1</mark>',
                    $text
                );
            }
        }

        return $text;
    }

    /**
     * Get common query suggestions based on current query
     */
    private function getCommonQueries(string $query): array
    {
        $suggestions = [];
        $lowerQuery = strtolower($query);

        // Common government document search patterns
        $patterns = [
            'wet' => ['wet', 'wetsvoorstel', 'wetgeving'],
            'beleid' => ['beleid', 'beleidsnota', 'beleidsdocument'],
            'rapport' => ['rapport', 'onderzoek', 'evaluatie'],
            'brief' => ['brief', 'kamerbrief', 'kamerstuk'],
            'besluit' => ['besluit', 'regeling', 'verordening'],
        ];

        foreach ($patterns as $key => $terms) {
            if (str_contains($lowerQuery, $key)) {
                $suggestions = array_merge($suggestions, $terms);
            }
        }

        return array_unique($suggestions);
    }

    /**
     * Live search endpoint with fallback to PostgreSQL if Typesense unavailable
     */
    public function liveSearch(Request $request)
    {
        $query = $request->get('q', '');
        $limit = min((int) $request->get('limit', 5), 10); // Max 10 results for live search

        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'hits' => [],
                'found' => 0,
                'search_time_ms' => 0,
            ]);
        }

        $startTime = microtime(true);

        // Try Typesense first if enabled
        $useTypesense = config('open_overheid.typesense.enabled', true);

        if ($useTypesense) {
            try {
                $searchService = app(\App\Services\Typesense\TypesenseSearchService::class);
                $results = $searchService->search($query, [
                    'per_page' => $limit,
                    'page' => 1,
                ]);

                // Format results for frontend
                $formattedHits = array_map(function ($hit) {
                    $document = $hit['document'] ?? $hit;

                    // Convert Unix timestamp to ISO date string for frontend
                    $publicationDate = null;
                    if (isset($document['publication_date']) && $document['publication_date'] > 0) {
                        // Typesense stores publication_date as Unix timestamp (seconds)
                        // Convert to ISO 8601 string for JavaScript Date parsing
                        $publicationDate = date('Y-m-d', $document['publication_date']);
                    }

                    return [
                        'id' => $document['external_id'] ?? $document['id'] ?? null,
                        'title' => $document['title'] ?? 'Geen titel',
                        'description' => \Illuminate\Support\Str::limit($document['description'] ?? '', 120),
                        'publication_date' => $publicationDate,
                        'document_type' => $document['document_type'] ?? null,
                        'category' => $document['category'] ?? null,
                        'formatted_category' => $this->formatCategory($document['category'] ?? null),
                        'organisation' => $document['organisation'] ?? null,
                    ];
                }, $results['hits'] ?? []);

                $searchTime = (int) (($results['search_time_ms'] ?? 0) + ((microtime(true) - $startTime) * 1000));

                return response()->json([
                    'hits' => $formattedHits,
                    'found' => $results['found'] ?? 0,
                    'search_time_ms' => $searchTime,
                ]);
            } catch (\Exception $e) {
                \Log::warning('Typesense live search failed, falling back to PostgreSQL', ['error' => $e->getMessage()]);
                // Fall through to PostgreSQL fallback
            }
        }

        // Fallback to PostgreSQL search
        try {
            $searchQuery = new OpenOverheidSearchQuery(
                zoektekst: $query,
                page: 1,
                perPage: $limit,
            );

            $results = $this->localService->search($searchQuery);
            $items = $results['items'] ?? collect();

            $formattedHits = array_map(function ($item) {
                return [
                    'id' => $item->external_id ?? $item->id ?? null,
                    'title' => $item->title ?? 'Geen titel',
                    'description' => \Illuminate\Support\Str::limit($item->description ?? '', 120),
                    'publication_date' => $item->publication_date?->format('Y-m-d'),
                    'document_type' => $item->document_type ?? null,
                    'category' => $item->category ?? null,
                    'formatted_category' => $this->formatCategory($item->category ?? null),
                    'organisation' => $item->organisation ?? null,
                ];
            }, $items->items->toArray() ?? []);

            $searchTime = (int) ((microtime(true) - $startTime) * 1000);

            return response()->json([
                'hits' => $formattedHits,
                'found' => $results['total'] ?? count($formattedHits),
                'search_time_ms' => $searchTime,
            ]);
        } catch (\Exception $e) {
            \Log::error('Live search error (both Typesense and PostgreSQL failed)', ['error' => $e->getMessage()]);

            return response()->json([
                'hits' => [],
                'found' => 0,
                'search_time_ms' => 0,
                'error' => 'Search temporarily unavailable',
            ], 500);
        }
    }

    public function referencesPage(Request $request)
    {
        return view('verwijzingen');
    }

    public function searchResults(Request $request)
    {
        $validated = $request->validate([
            'zoeken' => ['nullable', 'string'],
            'pagina' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'in:10,20,50'],
            'beschikbaarSinds' => ['nullable', 'string', 'in:week,maand,jaar,zelf'],
            'publicatiedatum_van' => ['nullable', 'date_format:d-m-Y'],
            'publicatiedatum_tot' => ['nullable', 'date_format:d-m-Y'],
            'documentsoort' => ['nullable'],
            'documentsoort.*' => ['string'],
            'informatiecategorie' => ['nullable'],
            'informatiecategorie.*' => ['string'],
            'thema' => ['nullable'],
            'thema.*' => ['string'],
            'organisatie' => ['nullable'],
            'organisatie.*' => ['string'],
            'bestandstype' => ['nullable'],
            'bestandstype.*' => ['string'],
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

        // Handle array filters - support multiple values
        $documentsoort = $validated['documentsoort'] ?? null;
        $thema = $validated['thema'] ?? null;
        $organisatie = $validated['organisatie'] ?? null;

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

        // Use local search if enabled, fallback to remote
        $useLocal = config('open_overheid.use_local_search', true);

        try {
            $results = $useLocal
                ? $this->localService->search($query)
                : $this->remoteService->search($query);

            $documentCount = OpenOverheidDocument::count();

            // Calculate dynamic filter counts based on current results
            $filterCounts = $this->calculateFilterCounts($query, $validated);

            // Get all available filter options for "Toon meer"
            $allFilterOptions = $this->getAllFilterOptions($query);

            return view('zoekresultaten', [
                'results' => $results,
                'query' => $query,
                'documentCount' => $documentCount,
                'filters' => $validated,
                'filterCounts' => $filterCounts,
                'allFilterOptions' => $allFilterOptions,
            ]);
        } catch (\Exception $e) {
            // If local search fails and it was enabled, try remote as fallback
            if ($useLocal) {
                try {
                    $results = $this->remoteService->search($query);
                    $documentCount = OpenOverheidDocument::count();

                    $filterCounts = $this->calculateFilterCounts($query, $validated);
                    $allFilterOptions = $this->getAllFilterOptions($query);

                    return view('zoekresultaten', [
                        'results' => $results,
                        'query' => $query,
                        'documentCount' => $documentCount,
                        'filters' => $validated,
                        'filterCounts' => $filterCounts,
                        'allFilterOptions' => $allFilterOptions,
                    ]);
                } catch (\Exception $fallbackException) {
                    $allFilterOptions = $this->getAllFilterOptions($query);

                    return view('zoekresultaten', [
                        'results' => ['items' => [], 'total' => 0],
                        'query' => $query,
                        'documentCount' => OpenOverheidDocument::count(),
                        'filters' => $validated,
                        'filterCounts' => [],
                        'allFilterOptions' => $allFilterOptions,
                        'error' => $fallbackException->getMessage(),
                    ]);
                }
            }

            $allFilterOptions = $this->getAllFilterOptions($query);

            return view('zoekresultaten', [
                'results' => ['items' => [], 'total' => 0],
                'query' => $query,
                'documentCount' => OpenOverheidDocument::count(),
                'filters' => $validated,
                'filterCounts' => [],
                'allFilterOptions' => $allFilterOptions,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'in:10,20,50'],
            'publicatiedatum_van' => ['nullable', 'date_format:d-m-Y'],
            'publicatiedatum_tot' => ['nullable', 'date_format:d-m-Y'],
            'documentsoort' => ['nullable'],
            'documentsoort.*' => ['string'],
            'informatiecategorie' => ['nullable', 'string'],
            'thema' => ['nullable'],
            'thema.*' => ['string'],
            'organisatie' => ['nullable'],
            'organisatie.*' => ['string'],
            'sort' => ['nullable', 'string', 'in:relevance,publication_date,modified_date'],
        ]);

        $query = new OpenOverheidSearchQuery(
            zoektekst: $validated['q'] ?? '',
            page: (int) ($validated['page'] ?? 1),
            perPage: (int) ($validated['per_page'] ?? 20),
            publicatiedatumVan: $validated['publicatiedatum_van'] ?? null,
            publicatiedatumTot: $validated['publicatiedatum_tot'] ?? null,
            documentsoort: $validated['documentsoort'] ?? null,
            informatiecategorie: $validated['informatiecategorie'] ?? null,
            thema: $validated['thema'] ?? null,
            organisatie: $validated['organisatie'] ?? null,
            sort: $validated['sort'] ?? 'relevance',
        );

        // Use local search if enabled, fallback to remote
        $useLocal = config('open_overheid.use_local_search', true);

        try {
            $results = $useLocal
                ? $this->localService->search($query)
                : $this->remoteService->search($query);

            return response()->json($results);
        } catch (\Exception $e) {
            // If local search fails and it was enabled, try remote as fallback
            if ($useLocal) {
                try {
                    $results = $this->remoteService->search($query);

                    return response()->json($results);
                } catch (\Exception $fallbackException) {
                    return response()->json([
                        'error' => 'Search failed',
                        'message' => $fallbackException->getMessage(),
                    ], 500);
                }
            }

            return response()->json([
                'error' => 'Search failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Calculate filter counts based on current search results
     */
    private function calculateFilterCounts(OpenOverheidSearchQuery $query, array $validated): array
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

        try {
            $baseQuery = OpenOverheidDocument::query();

            // Apply same search text filter if present
            if (! empty($query->zoektekst)) {
                $baseQuery->whereFullText(['title', 'description', 'content'], $query->zoektekst);
            }

            // Calculate date filter counts
            $now = now();
            $counts['week'] = (clone $baseQuery)->where('publication_date', '>=', $now->copy()->subWeek())->count();
            $counts['maand'] = (clone $baseQuery)->where('publication_date', '>=', $now->copy()->subMonth())->count();
            $counts['jaar'] = (clone $baseQuery)->where('publication_date', '>=', $now->copy()->subYear())->count();

            // Get unique document types from current results
            $documentTypes = (clone $baseQuery)
                ->whereNotNull('document_type')
                ->distinct()
                ->pluck('document_type')
                ->filter()
                ->toArray();

            foreach ($documentTypes as $type) {
                $counts['documentsoort'][$type] = (clone $baseQuery)
                    ->where('document_type', $type)
                    ->count();
            }

            // Get unique themes from current results
            $themes = (clone $baseQuery)
                ->whereNotNull('theme')
                ->distinct()
                ->pluck('theme')
                ->filter()
                ->toArray();

            foreach ($themes as $theme) {
                $counts['thema'][$theme] = (clone $baseQuery)
                    ->where('theme', $theme)
                    ->count();
            }

            // Get unique organisations from current results
            $organisations = (clone $baseQuery)
                ->whereNotNull('organisation')
                ->distinct()
                ->pluck('organisation')
                ->filter()
                ->toArray();

            foreach ($organisations as $org) {
                $counts['organisatie'][$org] = (clone $baseQuery)
                    ->where('organisation', $org)
                    ->count();
            }

            // Get unique categories from current results
            $categories = (clone $baseQuery)
                ->whereNotNull('category')
                ->distinct()
                ->pluck('category')
                ->filter()
                ->toArray();

            foreach ($categories as $category) {
                $counts['informatiecategorie'][$category] = (clone $baseQuery)
                    ->where('category', $category)
                    ->count();
            }
        } catch (\Exception $e) {
            // Return empty counts on error
        }

        return $counts;
    }

    /**
     * Get all available filter options for "Toon meer" functionality
     */
    private function getAllFilterOptions(OpenOverheidSearchQuery $query): array
    {
        $baseQuery = OpenOverheidDocument::query();

        // Apply same search text filter if present
        if (! empty($query->zoektekst)) {
            $baseQuery->whereFullText(['title', 'description', 'content'], $query->zoektekst);
        }

        // Get all unique document types
        $allDocumentTypes = (clone $baseQuery)
            ->whereNotNull('document_type')
            ->distinct()
            ->orderBy('document_type')
            ->pluck('document_type')
            ->filter()
            ->values()
            ->toArray();

        // Get all unique themes
        $allThemes = (clone $baseQuery)
            ->whereNotNull('theme')
            ->distinct()
            ->orderBy('theme')
            ->pluck('theme')
            ->filter()
            ->values()
            ->toArray();

        // Get all unique organisations
        $allOrganisations = (clone $baseQuery)
            ->whereNotNull('organisation')
            ->distinct()
            ->orderBy('organisation')
            ->pluck('organisation')
            ->filter()
            ->values()
            ->toArray();

        // Get all unique information categories
        $allCategories = (clone $baseQuery)
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter()
            ->values()
            ->toArray();

        return [
            'documentsoort' => $allDocumentTypes,
            'thema' => $allThemes,
            'organisatie' => $allOrganisations,
            'informatiecategorie' => $allCategories,
        ];
    }

    /**
     * Format a category name with its article number for display.
     *
     * @param  string|null  $category  The category name from database
     * @return string|null The formatted category like "2j. Onderzoeksrapporten", or null
     */
    private function formatCategory(?string $category): ?string
    {
        if (empty($category)) {
            return null;
        }

        $service = app(\App\Services\OpenOverheid\WooCategoryService::class);

        return $service->formatCategoryForDisplay($category);
    }
}
