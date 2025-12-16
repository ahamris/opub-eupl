<?php

namespace App\Http\Controllers\OpenOverheid;

use App\DataTransferObjects\OpenOverheid\OpenOverheidSearchQuery;
use App\Http\Controllers\Controller;
use App\Models\OpenOverheidDocument;
use App\Services\OpenOverheid\FilterCountService;
use App\Services\OpenOverheid\OpenOverheidLocalSearchService;
use App\Services\OpenOverheid\OpenOverheidSearchService;
use App\Services\OpenOverheid\QueryParsingService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        private readonly OpenOverheidLocalSearchService $localService,
        private readonly OpenOverheidSearchService $remoteService,
        private readonly QueryParsingService $queryParsingService,
        private readonly FilterCountService $filterCountService
    ) {}

    public function searchPage(Request $request)
    {
        // Minimal queries for homepage - only get document count
        $documentCount = \Illuminate\Support\Facades\Cache::remember('open_overheid:document_count', 300, function () {
            return OpenOverheidDocument::count();
        });

        // Only load statistics if not on homepage (for search results page)
        if (! request()->routeIs('home')) {
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

                $dossierCount = OpenOverheidDocument::countDossiers();

                // Get top categories by count - normalize categories first and filter out "Onbekend"
                $wooCategoryService = app(\App\Services\OpenOverheid\WooCategoryService::class);
                $allCategories = OpenOverheidDocument::whereNotNull('category')
                    ->where('category', '!=', 'Onbekend')
                    ->where('category', '!=', 'onbekend')
                    ->pluck('category')
                    ->map(function ($category) use ($wooCategoryService) {
                        $normalized = $wooCategoryService->normalizeCategory($category);
                        // Filter out "Onbekend" after normalization too
                        if (empty($normalized) || strtolower(trim($normalized)) === 'onbekend') {
                            return null;
                        }

                        return $normalized;
                    })
                    ->filter()
                    ->countBy()
                    ->sortDesc()
                    ->take(10)
                    ->toArray();

                $topCategories = $allCategories;

                // Get top themes by count - filter out "Onbekend"
                $topThemes = OpenOverheidDocument::whereNotNull('theme')
                    ->where('theme', '!=', 'Onbekend')
                    ->where('theme', '!=', 'onbekend')
                    ->selectRaw('theme, COUNT(*) as count')
                    ->groupBy('theme')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->pluck('count', 'theme')
                    ->toArray();

                // Leaderboard: Top organisaties all time
                $leaderboardAllTime = OpenOverheidDocument::whereNotNull('organisation')
                    ->selectRaw('organisation, COUNT(*) as count')
                    ->groupBy('organisation')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'organisation' => $item->organisation,
                            'count' => $item->count,
                        ];
                    })
                    ->toArray();

                // Leaderboard: Top organisaties deze maand
                $leaderboardThisMonth = OpenOverheidDocument::whereNotNull('organisation')
                    ->whereNotNull('publication_date')
                    ->where('publication_date', '>=', now()->startOfMonth())
                    ->selectRaw('organisation, COUNT(*) as count')
                    ->groupBy('organisation')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'organisation' => $item->organisation,
                            'count' => $item->count,
                        ];
                    })
                    ->toArray();

                // Leaderboard: Top organisaties dit jaar
                $leaderboardThisYear = OpenOverheidDocument::whereNotNull('organisation')
                    ->whereNotNull('publication_date')
                    ->where('publication_date', '>=', now()->startOfYear())
                    ->selectRaw('organisation, COUNT(*) as count')
                    ->groupBy('organisation')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'organisation' => $item->organisation,
                            'count' => $item->count,
                        ];
                    })
                    ->toArray();

                return [
                    'categoryCount' => $categoryCount,
                    'themeCount' => $themeCount,
                    'organisationCount' => $organisationCount,
                    'dossierCount' => $dossierCount,
                    'topCategories' => $topCategories,
                    'topThemes' => $topThemes,
                    'leaderboardAllTime' => $leaderboardAllTime,
                    'leaderboardThisMonth' => $leaderboardThisMonth,
                    'leaderboardThisYear' => $leaderboardThisYear,
                ];
            });

            $categoryCount = $statistics['categoryCount'];
            $themeCount = $statistics['themeCount'];
            $organisationCount = $statistics['organisationCount'];
            $topCategories = $statistics['topCategories'];
            $topThemes = $statistics['topThemes'];

            return view('zoek', [
                'documentCount' => $documentCount,
                'statistics' => [
                    'totalDocuments' => $documentCount,
                    'categoryCount' => $categoryCount,
                    'themeCount' => $themeCount,
                    'organisationCount' => $organisationCount,
                    'dossierCount' => $statistics['dossierCount'],
                    'topCategories' => $topCategories,
                    'topThemes' => $topThemes,
                    'leaderboardAllTime' => $statistics['leaderboardAllTime'] ?? [],
                    'leaderboardThisMonth' => $statistics['leaderboardThisMonth'] ?? [],
                    'leaderboardThisYear' => $statistics['leaderboardThisYear'] ?? [],
                ],
            ]);
        }

        // Homepage - minimal data only
        return view('zoek', [
            'documentCount' => $documentCount,
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
     * Chat interface page with natural language search
     */
    public function chatPage(Request $request)
    {
        $documentCount = \Illuminate\Support\Facades\Cache::remember('open_overheid:document_count', 300, function () {
            return OpenOverheidDocument::count();
        });

        return view('chat', [
            'documentCount' => $documentCount,
        ]);
    }

    /**
     * Natural language search endpoint for chat interface
     */
    public function naturalLanguageSearch(Request $request)
    {
        $validated = $request->validate([
            'query' => ['required', 'string', 'max:500'],
        ]);

        $query = $validated['query'];
        $limit = min((int) $request->get('limit', 10), 20);

        try {
            $searchService = app(\App\Services\Typesense\TypesenseSearchService::class);

            // Use natural language search if available, otherwise fallback to regular search
            $results = $searchService->naturalLanguageSearch($query, [
                'per_page' => $limit,
                'page' => 1,
            ]);

            // Extract original documents for AI context (with full content)
            // If content is missing from Typesense, fetch from database
            $documentsForAI = array_map(function ($hit) {
                $document = $hit['document'] ?? $hit;

                // If content is missing or empty, try to fetch from database
                if (empty($document['content'] ?? null) && ! empty($document['external_id'] ?? null)) {
                    $dbDocument = OpenOverheidDocument::where('external_id', $document['external_id'])->first();
                    if ($dbDocument && ! empty($dbDocument->content)) {
                        $document['content'] = $dbDocument->content;
                    }
                }

                return $document;
            }, $results['hits'] ?? []);

            // Format results for frontend (without content to keep response size manageable)
            $formattedHits = array_map(function ($hit) {
                $document = $hit['document'] ?? $hit;

                // Convert Unix timestamp to ISO date string for frontend
                $publicationDate = null;
                if (isset($document['publication_date']) && $document['publication_date'] > 0) {
                    $publicationDate = date('Y-m-d', $document['publication_date']);
                }

                return [
                    'id' => $document['external_id'] ?? $document['id'] ?? null,
                    'title' => $document['title'] ?? 'Geen titel',
                    'description' => \Illuminate\Support\Str::limit($document['description'] ?? '', 200),
                    'publication_date' => $publicationDate,
                    'document_type' => $document['document_type'] ?? null,
                    'category' => $document['category'] ?? null,
                    'formatted_category' => $this->formatCategory($document['category'] ?? null),
                    'organisation' => $document['organisation'] ?? null,
                    'theme' => $document['theme'] ?? null,
                ];
            }, $results['hits'] ?? []);

            // Generate AI answer based on found documents (use original documents with content)
            $aiAnswer = null;
            $sources = [];
            if (! empty($documentsForAI)) {
                try {
                    $geminiService = app(\App\Services\AI\GeminiService::class);
                    $aiResponse = $geminiService->answerQuestion($query, $documentsForAI);
                    $aiAnswer = $aiResponse['answer'] ?? null;
                    $sources = $aiResponse['sources'] ?? [];
                } catch (\Exception $aiException) {
                    \Log::warning('AI answer generation failed', [
                        'query' => $query,
                        'error' => $aiException->getMessage(),
                        'trace' => $aiException->getTraceAsString(),
                    ]);
                    // Continue without AI answer if generation fails
                }
            }

            return response()->json([
                'hits' => $formattedHits,
                'found' => $results['found'] ?? 0,
                'search_time_ms' => $results['search_time_ms'] ?? 0,
                'query' => $query,
                'answer' => $aiAnswer,
                'sources' => $sources,
            ]);
        } catch (\Exception $e) {
            \Log::error('Natural language search error', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);

            // Fallback to regular search if natural language search fails
            try {
                $results = $searchService->search($query, [
                    'per_page' => $limit,
                    'page' => 1,
                ]);

                // Extract original documents for AI context (with full content)
                // If content is missing from Typesense, fetch from database
                $documentsForAI = array_map(function ($hit) {
                    $document = $hit['document'] ?? $hit;

                    // If content is missing or empty, try to fetch from database
                    if (empty($document['content'] ?? null) && ! empty($document['external_id'] ?? null)) {
                        $dbDocument = OpenOverheidDocument::where('external_id', $document['external_id'])->first();
                        if ($dbDocument && ! empty($dbDocument->content)) {
                            $document['content'] = $dbDocument->content;
                        }
                    }

                    return $document;
                }, $results['hits'] ?? []);

                $formattedHits = array_map(function ($hit) {
                    $document = $hit['document'] ?? $hit;
                    $publicationDate = null;
                    if (isset($document['publication_date']) && $document['publication_date'] > 0) {
                        $publicationDate = date('Y-m-d', $document['publication_date']);
                    }

                    return [
                        'id' => $document['external_id'] ?? $document['id'] ?? null,
                        'title' => $document['title'] ?? 'Geen titel',
                        'description' => \Illuminate\Support\Str::limit($document['description'] ?? '', 200),
                        'publication_date' => $publicationDate,
                        'document_type' => $document['document_type'] ?? null,
                        'category' => $document['category'] ?? null,
                        'formatted_category' => $this->formatCategory($document['category'] ?? null),
                        'organisation' => $document['organisation'] ?? null,
                        'theme' => $document['theme'] ?? null,
                    ];
                }, $results['hits'] ?? []);

                // Generate AI answer based on found documents (use original documents with content)
                $aiAnswer = null;
                $sources = [];
                if (! empty($documentsForAI)) {
                    try {
                        $geminiService = app(\App\Services\AI\GeminiService::class);
                        $aiResponse = $geminiService->answerQuestion($query, $documentsForAI);
                        $aiAnswer = $aiResponse['answer'] ?? null;
                        $sources = $aiResponse['sources'] ?? [];
                    } catch (\Exception $aiException) {
                        \Log::warning('AI answer generation failed (fallback)', [
                            'query' => $query,
                            'error' => $aiException->getMessage(),
                            'trace' => $aiException->getTraceAsString(),
                        ]);
                    }
                }

                return response()->json([
                    'hits' => $formattedHits,
                    'found' => $results['found'] ?? 0,
                    'search_time_ms' => $results['search_time_ms'] ?? 0,
                    'query' => $query,
                    'answer' => $aiAnswer,
                    'sources' => $sources,
                    'fallback' => true,
                ]);
            } catch (\Exception $fallbackException) {
                return response()->json([
                    'hits' => [],
                    'found' => 0,
                    'error' => 'Search temporarily unavailable',
                ], 500);
            }
        }
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

            // Always add a "Search for..." option as first suggestion
            $searchSuggestion = [
                'type' => 'search_action',
                'action' => 'search',
                'query' => $query,
                'label' => "Zoeken naar \"{$query}\"",
                'description' => 'Zoek in alle documenten',
            ];

            // Add filter suggestions using QueryParsingService
            $filterSuggestions = $this->queryParsingService->getFilterSuggestions($query, min($limit, 5));

            // Separate documents and filters for better frontend handling
            $documentSuggestions = array_filter($suggestions, fn ($s) => $s['type'] === 'document' || $s['type'] === 'suggestion');
            $filterSuggestionsList = $filterSuggestions;

            // Parse query to detect if it's a filter value
            $parsedQuery = $this->queryParsingService->parseQuery($query);

            // If query matches a filter, add explicit filter option
            $explicitFilterSuggestion = null;
            if ($parsedQuery['type'] === 'filter' && isset($parsedQuery['filter_type'])) {
                $filterTypeLabels = [
                    'organisatie' => 'Organisatie',
                    'thema' => 'Thema',
                    'documentsoort' => 'Documentsoort',
                    'informatiecategorie' => 'Categorie',
                ];
                $filterTypeLabel = $filterTypeLabels[$parsedQuery['filter_type']] ?? $parsedQuery['filter_type'];
                $explicitFilterSuggestion = [
                    'type' => 'filter_action',
                    'action' => 'filter',
                    'filter_type' => $parsedQuery['filter_type'],
                    'filter_value' => $query,
                    'label' => "Filter op {$filterTypeLabel}: \"{$query}\"",
                    'description' => "Toon alleen documenten met deze {$filterTypeLabel}",
                ];
            }

            // Combine: search action first, then explicit filter (if detected), then documents, then other filters
            $allSuggestions = [$searchSuggestion];
            if ($explicitFilterSuggestion) {
                $allSuggestions[] = $explicitFilterSuggestion;
            }
            $allSuggestions = array_merge($allSuggestions, array_values($documentSuggestions), $filterSuggestionsList);

            return response()->json([
                'suggestions' => array_slice($allSuggestions, 0, $limit + 2), // +2 for search and filter actions
                'query_type' => $parsedQuery['type'], // 'search' or 'filter'
                'is_filter_value' => $parsedQuery['type'] === 'filter',
                'filter_type' => $parsedQuery['filter_type'] ?? null,
                'query' => $query, // Include original query for frontend
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

        // If empty query, return total document count for live ticker
        if (empty($query) || strlen($query) < 2) {
            $totalCount = OpenOverheidDocument::count();

            return response()->json([
                'hits' => [],
                'found' => 0,
                'total_found' => $totalCount,
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

                // Get total document count for live ticker
                $totalCount = OpenOverheidDocument::count();

                return response()->json([
                    'hits' => $formattedHits,
                    'found' => $results['found'] ?? 0,
                    'total_found' => $totalCount,
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

            // Ensure $items is a collection
            if (! $items instanceof \Illuminate\Support\Collection) {
                $items = collect($items);
            }

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
            }, $items->toArray());

            $searchTime = (int) ((microtime(true) - $startTime) * 1000);

            // Get total document count for live ticker
            $totalCount = OpenOverheidDocument::count();

            return response()->json([
                'hits' => $formattedHits,
                'found' => $results['total'] ?? count($formattedHits),
                'total_found' => $totalCount,
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
            // Neuro search is now premium-only, handled separately via chat interface
            // 'neuro_search' => ['nullable', 'boolean'],
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

        // Always try Typesense first (better performance, typo tolerance, faceting)
        // Fallback to PostgreSQL if Typesense is unavailable
        // Neuro search is now premium-only feature, removed from regular search
        // $useNeuroSearch = ! empty($validated['neuro_search']);
        $useTypesense = config('open_overheid.typesense.enabled', true);

        try {
            // Use Typesense as PRIMARY search engine (faster, better search features, includes facets)
            // No natural language search - Typesense is pure search only
            $filterCounts = [];
            $facetCounts = null;

            if ($useTypesense) {
                try {
                    $results = $this->searchWithTypesense($query, false);
                    
                    // Extract facet counts from Typesense results
                    if (isset($results['facet_counts']) && ! empty($results['facet_counts'])) {
                        $facetCounts = $results['facet_counts'];
                        \Log::debug('Typesense facets received', [
                            'facet_count' => count($facetCounts),
                            'facets' => array_keys(array_column($facetCounts, 'field_name')),
                        ]);
                        // Convert Typesense facets to filter counts format
                        $filterCounts = $this->convertTypesenseFacetsToFilterCounts($facetCounts, $query);
                        
                        // Calculate date filter counts using Typesense (not database!)
                        // Make lightweight Typesense queries with same filters + date range
                        $filterCounts['week'] = $this->getDateFilterCountFromTypesense($query, 'week');
                        $filterCounts['maand'] = $this->getDateFilterCountFromTypesense($query, 'maand');
                        $filterCounts['jaar'] = $this->getDateFilterCountFromTypesense($query, 'jaar');
                    } else {
                        // Typesense search succeeded but no facets returned - fallback to FilterCountService
                        \Log::warning('Typesense search succeeded but no facet_counts returned, using FilterCountService fallback');
                        try {
                            $filterCounts = $this->filterCountService->calculateFilterCounts($query);
                        } catch (\Exception $e) {
                            \Log::warning('FilterCountService failed', ['error' => $e->getMessage()]);
                            $filterCounts = [];
                        }
                    }
                } catch (\Exception $typesenseException) {
                    // If Typesense fails, fallback to PostgreSQL
                    \Log::warning('Typesense search failed, falling back to PostgreSQL', [
                        'error' => $typesenseException->getMessage(),
                    ]);
                    $results = $this->localService->search($query);
                    
                    // Use FilterCountService for PostgreSQL fallback
                    try {
                        $filterCounts = $this->filterCountService->calculateFilterCounts($query);
                    } catch (\Exception $e) {
                        \Log::warning('FilterCountService failed', ['error' => $e->getMessage()]);
                        $filterCounts = [];
                    }
                }
            } else {
                // Typesense disabled, use PostgreSQL directly
                $results = $this->localService->search($query);
                
                // Use FilterCountService for PostgreSQL
                try {
                    $filterCounts = $this->filterCountService->calculateFilterCounts($query);
                } catch (\Exception $e) {
                    \Log::warning('FilterCountService failed', ['error' => $e->getMessage()]);
                    $filterCounts = [];
                }
            }

            // Get total document count from Typesense (not database!)
            $documentCount = 0;
            if ($useTypesense) {
                try {
                    // Make lightweight Typesense query to get total count
                    $countResults = $this->searchWithTypesense(
                        new OpenOverheidSearchQuery(zoektekst: '', page: 1, perPage: 0),
                        false
                    );
                    $documentCount = $countResults['total'] ?? 0;
                } catch (\Exception $e) {
                    \Log::warning('Typesense total count failed, using database fallback', ['error' => $e->getMessage()]);
                    $documentCount = OpenOverheidDocument::count();
                }
            } else {
                $documentCount = OpenOverheidDocument::count();
            }

            // Get all available filter options for "Toon meer"
            // Note: This still uses database for now, but could be optimized to use Typesense facets
            // For 600k+ records, this is acceptable as it's cached and only used for "Show more" dropdown
            try {
                $allFilterOptions = $this->filterCountService->getAllFilterOptions($query);
            } catch (\Exception $e) {
                \Log::warning('getAllFilterOptions failed', ['error' => $e->getMessage()]);
                $allFilterOptions = [];
            }

            return view('zoekresultaten', [
                'results' => $results,
                'query' => $query,
                'documentCount' => $documentCount,
                'filters' => $validated,
                'filterCounts' => $filterCounts,
                'allFilterOptions' => $allFilterOptions,
            ]);
        } catch (\Exception $e) {
            \Log::error('Search failed (both Typesense and PostgreSQL)', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // If both Typesense and local search failed, try remote API as last resort
            try {
                $results = $this->remoteService->search($query);
                $documentCount = OpenOverheidDocument::count();

                // Safely get filter counts and options
                try {
                    $filterCounts = $this->filterCountService->calculateFilterCounts($query);
                } catch (\Exception $filterException) {
                    \Log::warning('FilterCountService failed in fallback', ['error' => $filterException->getMessage()]);
                    $filterCounts = [];
                }

                try {
                    $allFilterOptions = $this->filterCountService->getAllFilterOptions($query);
                } catch (\Exception $filterException) {
                    \Log::warning('getAllFilterOptions failed in fallback', ['error' => $filterException->getMessage()]);
                    $allFilterOptions = [];
                }

                return view('zoekresultaten', [
                    'results' => $results,
                    'query' => $query,
                    'documentCount' => $documentCount,
                    'filters' => $validated,
                    'filterCounts' => $filterCounts,
                    'allFilterOptions' => $allFilterOptions,
                ]);
            } catch (\Exception $fallbackException) {
                \Log::error('All search methods failed', [
                    'primary_error' => $e->getMessage(),
                    'fallback_error' => $fallbackException->getMessage(),
                ]);

                // Safely get filter options even if everything else fails
                try {
                    $allFilterOptions = $this->filterCountService->getAllFilterOptions($query);
                } catch (\Exception $filterException) {
                    $allFilterOptions = [];
                }

                return view('zoekresultaten', [
                    'results' => ['items' => collect([]), 'total' => 0],
                    'query' => $query,
                    'documentCount' => OpenOverheidDocument::count(),
                    'filters' => $validated,
                    'filterCounts' => [],
                    'allFilterOptions' => $allFilterOptions,
                    'error' => 'Er is een fout opgetreden bij het zoeken. Probeer het later opnieuw.',
                ]);
            }

            $allFilterOptions = $this->filterCountService->getAllFilterOptions($query);

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

    // Note: calculateFilterCounts and getAllFilterOptions methods moved to FilterCountService

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

    // Note: getFilterSuggestions method moved to QueryParsingService

    /**
     * Search using Typesense with natural language support
     */
    private function searchWithTypesense(OpenOverheidSearchQuery $query, bool $useNaturalLanguage = false): array
    {
        $searchService = app(\App\Services\Typesense\TypesenseSearchService::class);

        // Build filter_by string
        // IMPORTANT: Escape special characters and filter out empty values to prevent Typesense errors
        $filters = [];
        if ($query->documentsoort) {
            $types = is_array($query->documentsoort) ? $query->documentsoort : [$query->documentsoort];
            $types = array_filter(array_map('trim', $types)); // Remove empty values
            if (! empty($types)) {
                // Escape special characters in Typesense filter syntax
                $escapedTypes = array_map(function ($t) {
                    // Escape quotes and special characters
                    $t = str_replace(['"', "'"], '', $t); // Remove quotes
                    return '='.$t;
                }, $types);
                $filters[] = 'document_type:['.implode(',', $escapedTypes).']';
            }
        }
        if ($query->informatiecategorie) {
            $category = trim($query->informatiecategorie);
            if (! empty($category)) {
                $category = str_replace(['"', "'"], '', $category); // Remove quotes
                $filters[] = 'category:='.$category;
            }
        }
        if ($query->thema) {
            $themes = is_array($query->thema) ? $query->thema : [$query->thema];
            $themes = array_filter(array_map('trim', $themes)); // Remove empty values
            if (! empty($themes)) {
                $escapedThemes = array_map(function ($t) {
                    $t = str_replace(['"', "'"], '', $t); // Remove quotes
                    return '='.$t;
                }, $themes);
                $filters[] = 'theme:['.implode(',', $escapedThemes).']';
            }
        }
        if ($query->organisatie) {
            $orgs = is_array($query->organisatie) ? $query->organisatie : [$query->organisatie];
            $orgs = array_filter(array_map('trim', $orgs)); // Remove empty values
            if (! empty($orgs)) {
                $escapedOrgs = array_map(function ($o) {
                    $o = str_replace(['"', "'"], '', $o); // Remove quotes
                    return '='.$o;
                }, $orgs);
                $filters[] = 'organisation:['.implode(',', $escapedOrgs).']';
            }
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

        // Build search options
        $options = [
            'per_page' => $query->perPage,
            'page' => $query->page,
            'sort_by' => $sortBy,
            // Request facets for all filterable fields to get counts
            'facet_by' => 'document_type,theme,organisation,category',
            'max_facet_values' => 500, // Get up to 500 facet values for accurate counts
        ];

        if (! empty($filters)) {
            $filterString = implode(' && ', $filters);
            $options['filter_by'] = $filterString;
            
            // Log filter string for debugging (only in debug mode)
            if (config('app.debug')) {
                \Log::debug('Typesense filter string', ['filter_by' => $filterString, 'filter_count' => count($filters)]);
            }
        }

        // Natural language search is disabled - Typesense is pure search only
        // AI search is premium-only feature via chat interface
        // if ($useNaturalLanguage) {
        //     $options['natural_language_search'] = true;
        // }

        // Search in Typesense with timeout handling
        try {
            $typesenseResults = $searchService->search($query->zoektekst ?? '', $options);
        } catch (\Typesense\Exceptions\TypesenseClientError $e) {
            // Log Typesense-specific errors
            \Log::error('Typesense client error', [
                'error' => $e->getMessage(),
                'filters' => $filters ?? [],
                'query' => $query->zoektekst ?? '',
            ]);
            throw $e;
        } catch (\Exception $e) {
            // Log other errors
            \Log::error('Typesense search error', [
                'error' => $e->getMessage(),
                'filters' => $filters ?? [],
                'query' => $query->zoektekst ?? '',
            ]);
            throw $e;
        }

        // Transform Typesense results to match expected format
        $items = collect($typesenseResults['hits'] ?? [])->map(function ($hit) {
            $doc = $hit['document'] ?? $hit;
            $model = \App\Models\OpenOverheidDocument::where('external_id', $doc['external_id'] ?? null)->first();

            // If model exists, return it; otherwise create a fake model-like object
            if ($model) {
                return $model;
            }

            // Create a simple object with necessary properties
            return (object) [
                'id' => $doc['id'] ?? null,
                'external_id' => $doc['external_id'] ?? null,
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
            'facet_counts' => $typesenseResults['facet_counts'] ?? [], // Include facet counts from Typesense
        ];
    }

    /**
     * Convert Typesense facet_counts to the format expected by FilterCountService
     * Typesense returns facets as an array of objects with field_name and counts
     */
    private function convertTypesenseFacetsToFilterCounts(array $facetCounts, OpenOverheidSearchQuery $query): array
    {
        $counts = [
            'week' => 0, // Will be calculated separately
            'maand' => 0, // Will be calculated separately
            'jaar' => 0, // Will be calculated separately
            'documentsoort' => [],
            'thema' => [],
            'organisatie' => [],
            'informatiecategorie' => [],
            'bestandstype' => [], // Not available in Typesense, will be empty
        ];

        // Typesense returns facets as an array of objects: [{field_name: "document_type", counts: [{value: "...", count: 123}]}]
        if (is_array($facetCounts) && ! empty($facetCounts)) {
            foreach ($facetCounts as $facetGroup) {
                // Handle both array format and object format
                if (! is_array($facetGroup)) {
                    continue;
                }

                $fieldName = $facetGroup['field_name'] ?? $facetGroup['fieldName'] ?? null;
                $countsArray = $facetGroup['counts'] ?? [];

                if (! $fieldName || empty($countsArray)) {
                    continue;
                }

                // Map Typesense field names to our filter count keys
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
        } else {
            \Log::warning('Typesense facet_counts is empty or invalid', [
                'facet_counts' => $facetCounts,
                'type' => gettype($facetCounts),
            ]);
        }

        return $counts;
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
            
            // Build same filters as main search
            $filters = [];
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
