<?php

namespace App\Http\Controllers\OpenOverheid;

use App\Models\OpenOverheidDocument;
use App\Services\Typesense\TypesenseSearchService;
use Illuminate\Http\Request;

class ReportController
{
    /**
     * Display the reports dashboard (Woo-style statistics)
     * Uses Typesense for fast faceted search results
     */
    /**
     * Display the reports dashboard (Woo-style statistics)
     * Uses Typesense for fast faceted search results
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $searchService = app(TypesenseSearchService::class);
        $wooCategoryService = app(\App\Services\OpenOverheid\WooCategoryService::class);

        // Get available years from database (years that have documents)
        $query = OpenOverheidDocument::whereNotNull('publication_date');
        
        if (config('database.default') === 'sqlite') {
            $availableYears = $query->selectRaw('strftime(\'%Y\', publication_date) as year');
        } elseif (config('database.default') === 'pgsql') {
            $availableYears = $query->selectRaw('EXTRACT(YEAR FROM publication_date) as year');
        } else {
            // MySQL
            $availableYears = $query->selectRaw('EXTRACT(YEAR FROM publication_date) as year');
        }

        $availableYears = $availableYears->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->map(fn($y) => (int) $y)
            ->toArray();

        // Default to the most recent year with data, or current year if no data
        $defaultYear = !empty($availableYears) ? max($availableYears) : now()->year;

        $selectedOrganisation = $request->get('organisatie');
        $selectedCategory = $request->get('informatiecategorie');
        $selectedTheme = $request->get('thema');

        // Date Range Handling
        $startDateInput = $request->get('start_date');
        $endDateInput = $request->get('end_date');

        if ($startDateInput && $endDateInput) {
            try {
                $startDate = \Carbon\Carbon::parse($startDateInput)->startOfDay();
                $endDate = \Carbon\Carbon::parse($endDateInput)->endOfDay();
            } catch (\Exception $e) {
                // Fallback if parsing fails
                $startDate = \Carbon\Carbon::createFromDate($defaultYear, 1, 1)->startOfDay();
                $endDate = \Carbon\Carbon::createFromDate($defaultYear, 12, 31)->endOfDay();
            }
        } else {
            $startDate = \Carbon\Carbon::createFromDate($defaultYear, 1, 1)->startOfDay();
            $endDate = \Carbon\Carbon::createFromDate($defaultYear, 12, 31)->endOfDay();
        }

        // Ensure endDate doesn't exceed today for accurate reporting
        if ($endDate->isFuture()) {
            $endDate = now()->endOfDay();
        }

        // Determine year for chart context (based on start date)
        $year = $startDate->year;
        $quarter = null;

        // Build Typesense filter
        $filters = [];
        $filters[] = 'publication_date:>=' . $startDate->timestamp;
        $filters[] = 'publication_date:<=' . $endDate->timestamp;
        
        if ($selectedOrganisation) {
            $filters[] = 'organisation:=' . $selectedOrganisation;
        }
        if ($selectedCategory) {
            $filters[] = 'category:=' . $selectedCategory;
        }
        if ($selectedTheme) {
            $filters[] = 'theme:=' . $selectedTheme;
        }

        $filterBy = implode(' && ', $filters);

        // Search with facets using Typesense
        try {
            $results = $searchService->search('*', [
                'filter_by' => $filterBy,
                'facet_by' => 'organisation,category,theme',
                'max_facet_values' => 50,
                'per_page' => 0, // We only need facet counts
            ]);

            $totalDocuments = $results['found'] ?? 0;
            $facetCounts = collect($results['facet_counts'] ?? []);

            // Extract organization facets
            $orgFacet = $facetCounts->firstWhere('field_name', 'organisation');
            $documentsPerOrganisation = collect($orgFacet['counts'] ?? [])
                ->take(20)
                ->map(fn($item) => [
                    'organisation' => $item['value'],
                    'count' => (int) $item['count'],
                ])
                ->toArray();
            
            $activeOrganisationsCount = count($orgFacet['counts'] ?? []);

            // Extract category facets
            $catFacet = $facetCounts->firstWhere('field_name', 'category');
            $documentsPerCategory = collect($catFacet['counts'] ?? [])
                ->map(fn($item) => [
                    'category' => $item['value'],
                    'label' => $wooCategoryService->formatCategoryForDisplay($item['value']) ?? $item['value'],
                    'count' => (int) $item['count'],
                ])
                ->toArray();

            // Extract theme facets
            $themeFacet = $facetCounts->firstWhere('field_name', 'theme');
            $documentsPerTheme = collect($themeFacet['counts'] ?? [])
                ->take(15)
                ->map(fn($item) => [
                    'theme' => $item['value'],
                    'count' => (int) $item['count'],
                ])
                ->toArray();
            
            $totalThemesCount = count($themeFacet['counts'] ?? []);

        } catch (\Exception $e) {
            // Fallback to database if Typesense fails
            \Log::warning('Typesense failed for reports, using database fallback', ['error' => $e->getMessage()]);
            
            $baseQuery = OpenOverheidDocument::whereNotNull('publication_date')
                ->whereBetween('publication_date', [$startDate, $endDate]);
            
            if ($selectedOrganisation) {
                $baseQuery->where('organisation', $selectedOrganisation);
            }
            if ($selectedCategory) {
                $baseQuery->where('category', $selectedCategory);
            }
            if ($selectedTheme) {
                $baseQuery->where('theme', $selectedTheme);
            }
            
            $totalDocuments = $baseQuery->count();
            
            $documentsPerOrganisation = (clone $baseQuery)
                ->whereNotNull('organisation')
                ->selectRaw('organisation, COUNT(*) as count')
                ->groupBy('organisation')
                ->orderBy('count', 'desc')
                ->limit(20)
                ->get()
                ->map(function ($item) {
                    return [
                        'organisation' => $item->organisation,
                        'count' => (int) $item->count,
                    ];
                })
                ->toArray();
            
            $activeOrganisationsCount = (clone $baseQuery)->distinct('organisation')->count('organisation');

            $documentsPerCategory = (clone $baseQuery)
                ->whereNotNull('category')
                ->selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get()
                ->map(function ($item) use ($wooCategoryService) {
                    return [
                        'category' => $item->category,
                        'label' => $wooCategoryService->formatCategoryForDisplay($item->category) ?? $item->category,
                        'count' => (int) $item->count,
                    ];
                })
                ->toArray();

            $documentsPerTheme = (clone $baseQuery)
                ->whereNotNull('theme')
                ->selectRaw('theme, COUNT(*) as count')
                ->groupBy('theme')
                ->orderBy('count', 'desc')
                ->limit(15)
                ->get()
                ->map(function ($item) {
                    return [
                        'theme' => $item->theme,
                        'count' => (int) $item->count,
                    ];
                })
                ->toArray();
            
            $totalThemesCount = (clone $baseQuery)->distinct('theme')->count('theme');
        }

        $documentsWithDecision = $totalDocuments;

        // Get quarterly data for top organizations for the chart
        $quarterlyOrgData = $this->getQuarterlyOrganisationData($searchService, $year, array_slice($documentsPerOrganisation, 0, 5), $selectedCategory, $selectedTheme);

        // Monthly trend using Typesense Multi-Search
        $monthlyTrend = [];
        $searches = [];
        
        // Generate months for the selected period
        $periodStart = $startDate->copy()->startOfMonth();
        $periodEnd = $endDate->copy()->endOfMonth();
        
        $current = $periodStart->copy();
        while ($current->lte($periodEnd)) {
            $monthStart = $current->copy()->startOfMonth();
            $monthEnd = $current->copy()->endOfMonth();
            
            $monthFilters = [
                'publication_date:>=' . $monthStart->timestamp,
                'publication_date:<=' . $monthEnd->timestamp,
            ];
            
            if ($selectedOrganisation) {
                $monthFilters[] = 'organisation:=' . $selectedOrganisation;
            }
            if ($selectedCategory) {
                $monthFilters[] = 'category:=' . $selectedCategory;
            }
            if ($selectedTheme) {
                $monthFilters[] = 'theme:=' . $selectedTheme;
            }

            $searches[] = [
                'collection' => 'open_overheid_documents',
                'q' => '*',
                'filter_by' => implode(' && ', $monthFilters),
                'per_page' => 0, // We only need the count
            ];
            
            // Initialize with 0 count
            $monthlyTrend[] = [
                $current->timestamp * 1000, // Timestamp in milliseconds
                0
            ];
            
            $current->addMonth();
        }

        try {
            if (!empty($searches)) {
                $multiSearchResults = $searchService->multiSearch($searches);
                
                if (isset($multiSearchResults['results'])) {
                    foreach ($multiSearchResults['results'] as $index => $result) {
                        if (isset($monthlyTrend[$index])) {
                            $monthlyTrend[$index][1] = (int) ($result['found'] ?? 0);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Typesense multi-search failed for trend', ['error' => $e->getMessage()]);
            // Keep the initialized 0 values so chart still renders
        }

        // Get all unique organizations and themes for dropdowns (from Typesense unfiltered)
        try {
            $unfilteredFilters = [
                'publication_date:>=' . $startDate->timestamp,
                'publication_date:<=' . $endDate->timestamp,
            ];
            $unfilteredResults = $searchService->search('*', [
                'filter_by' => implode(' && ', $unfilteredFilters),
                'facet_by' => 'organisation,category,theme',
                'max_facet_values' => 200,
                'per_page' => 0,
            ]);
            
            $orgFacetUnfiltered = collect($unfilteredResults['facet_counts'] ?? [])->firstWhere('field_name', 'organisation');
            $allOrganisations = collect($orgFacetUnfiltered['counts'] ?? [])
                ->pluck('value')
                ->sort()
                ->values()
                ->toArray();
            
            $catFacetUnfiltered = collect($unfilteredResults['facet_counts'] ?? [])->firstWhere('field_name', 'category');
            $allCategories = collect($catFacetUnfiltered['counts'] ?? [])
                ->filter(fn($item) => !in_array(strtolower($item['value']), ['onbekend']))
                ->map(fn($item) => [
                    'value' => $item['value'],
                    'label' => $wooCategoryService->formatCategoryForDisplay($item['value']) ?? $item['value'],
                ])
                ->sortBy('label')
                ->values()
                ->toArray();

            $themeFacetUnfiltered = collect($unfilteredResults['facet_counts'] ?? [])->firstWhere('field_name', 'theme');
            $allThemes = collect($themeFacetUnfiltered['counts'] ?? [])
                ->pluck('value')
                ->sort()
                ->values()
                ->toArray();

        } catch (\Exception $e) {
            \Log::warning('Typesense failed for filters, using database fallback', ['error' => $e->getMessage()]);

            // Database fallback for filters
            $allOrganisations = OpenOverheidDocument::whereNotNull('publication_date')
                ->whereBetween('publication_date', [$startDate, $endDate])
                ->whereNotNull('organisation')
                ->distinct()
                ->orderBy('organisation')
                ->pluck('organisation')
                ->filter()
                ->values()
                ->toArray();

            $allCategories = OpenOverheidDocument::whereNotNull('publication_date')
                ->whereBetween('publication_date', [$startDate, $endDate])
                ->whereNotNull('category')
                ->whereNotIn('category', ['Onbekend', 'onbekend'])
                ->distinct()
                ->pluck('category')
                ->filter()
                ->map(function ($category) use ($wooCategoryService) {
                    return [
                        'value' => $category,
                        'label' => $wooCategoryService->formatCategoryForDisplay($category) ?? $category,
                    ];
                })
                ->sortBy('label')
                ->values()
                ->toArray();

            $allThemes = OpenOverheidDocument::whereNotNull('publication_date')
                ->whereBetween('publication_date', [$startDate, $endDate])
                ->whereNotNull('theme')
                ->distinct()
                ->orderBy('theme')
                ->pluck('theme')
                ->filter()
                ->values()
                ->toArray();
        }

        return view('reports.index', [
            'year' => $year,
            'quarter' => $quarter,
            'selectedOrganisation' => $selectedOrganisation,
            'selectedCategory' => $selectedCategory,
            'selectedTheme' => $selectedTheme,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalDocuments' => $totalDocuments,
            'activeOrganisationsCount' => $activeOrganisationsCount,
            'totalThemesCount' => $totalThemesCount,
            'documentsWithDecision' => $documentsWithDecision,
            'documentsPerOrganisation' => $documentsPerOrganisation,
            'documentsPerCategory' => $documentsPerCategory,
            'documentsPerTheme' => $documentsPerTheme,
            'monthlyTrend' => $monthlyTrend,
            'availableYears' => $availableYears,
            'allOrganisations' => $allOrganisations,
            'allCategories' => $allCategories,
            'allThemes' => $allThemes,
            'quarterlyOrgData' => $quarterlyOrgData,
        ]);
    }

    /**
     * Display the organization dashboard
     */
    /**
     * Display the organization dashboard
     */
    public function show(string $organisation): \Illuminate\View\View
    {
        $organisation = urldecode($organisation);
        $wooCategoryService = app(\App\Services\OpenOverheid\WooCategoryService::class);
        $searchService = app(TypesenseSearchService::class);

        // 1. Basic Stats & Metadata (Single Multi-Search or parallel requests? Let's do sequential for simplicity first, or optimize with multi-search if needed. 
        // Actually, we can get total docs, last 12 months, themes, and categories in one go or a few efficient queries.)

        // Query 1: Total Documents + Facets for Themes & Categories + Last Publication (via Sort)
        // We can't get "Last 12 months" count easily in the same query as "Total" without a separate filter.
        // So let's do a few targeted queries.

        try {
            // A. Total Documents, Themes, Categories, Last Publication
            $mainStats = $searchService->search('*', [
                'filter_by' => 'organisation:=' . $organisation,
                'facet_by' => 'theme,category',
                'max_facet_values' => 100,
                'sort_by' => 'publication_date:desc',
                'per_page' => 1, // We need the first one for "Last Publication"
            ]);

            $totalDocuments = $mainStats['found'] ?? 0;
            
            // Last Publication
            $lastPublication = null;
            if (!empty($mainStats['hits'][0]['document']['publication_date'])) {
                $lastPublication = date('Y-m-d H:i:s', $mainStats['hits'][0]['document']['publication_date']);
            }

            // Total Themes (Unique count from facet)
            $themeFacet = collect($mainStats['facet_counts'] ?? [])->firstWhere('field_name', 'theme');
            $totalThemes = count($themeFacet['counts'] ?? []);

            // Categories Distribution
            $catFacet = collect($mainStats['facet_counts'] ?? [])->firstWhere('field_name', 'category');
            $categories = collect($catFacet['counts'] ?? [])
                ->map(fn($item) => [
                    'category' => $wooCategoryService->formatCategoryForDisplay($item['value']) ?? $item['value'],
                    'count' => (int) $item['count'],
                ])
                ->sortByDesc('count')
                ->take(10)
                ->values();

            // B. Last 12 Months Count
            $last12MonthsDate = now()->subMonths(12)->timestamp;
            $last12MonthsStats = $searchService->search('*', [
                'filter_by' => 'organisation:=' . $organisation . ' && publication_date:>=' . $last12MonthsDate,
                'per_page' => 0,
            ]);
            $last12Months = $last12MonthsStats['found'] ?? 0;

            // C. Recent Documents
            $recentDocsResult = $searchService->search('*', [
                'filter_by' => 'organisation:=' . $organisation,
                'sort_by' => 'publication_date:desc',
                'per_page' => 10,
            ]);
            
            // Map Typesense hits to object-like structure for Blade
            $recentDocuments = collect($recentDocsResult['hits'] ?? [])->map(function ($hit) {
                $doc = $hit['document'];
                return (object) [
                    'id' => $doc['id'] ?? null,
                    'title' => $doc['title'] ?? 'Naamloos document',
                    'category' => $doc['category'] ?? 'Onbekend',
                    'publication_date' => isset($doc['publication_date']) ? date('Y-m-d H:i:s', $doc['publication_date']) : null,
                ];
            });

            // D. History (Last 24 months) - MultiSearch
            $history = [];
            $searches = [];
            $months = [];
            
            $startHistory = now()->subYears(2)->startOfMonth();
            $endHistory = now()->endOfMonth();
            
            $current = $startHistory->copy();
            while ($current->lte($endHistory)) {
                $monthStart = $current->copy()->startOfMonth();
                $monthEnd = $current->copy()->endOfMonth();
                
                $searches[] = [
                    'collection' => 'open_overheid_documents',
                    'q' => '*',
                    'filter_by' => 'organisation:=' . $organisation . ' && publication_date:>=' . $monthStart->timestamp . ' && publication_date:<=' . $monthEnd->timestamp,
                    'per_page' => 0,
                ];
                
                $months[] = $current->format('Y-m');
                $current->addMonth();
            }

            if (!empty($searches)) {
                $multiSearchResults = $searchService->multiSearch($searches);
                foreach ($multiSearchResults['results'] as $index => $result) {
                    $history[] = [
                        'month' => $months[$index],
                        'count' => (int) ($result['found'] ?? 0),
                    ];
                }
            }
            $history = collect($history);

        } catch (\Exception $e) {
            \Log::error('Typesense failed for organization dashboard', ['error' => $e->getMessage()]);
            // Fallback or empty state could be implemented here, but for now we return empty/zeros to avoid crash
            $totalDocuments = 0;
            $last12Months = 0;
            $totalThemes = 0;
            $lastPublication = null;
            $categories = collect([]);
            $history = collect([]);
            $recentDocuments = collect([]);
        }

        return view('reports.show', [
            'organisation' => $organisation,
            'totalDocuments' => $totalDocuments,
            'last12Months' => $last12Months,
            'totalThemes' => $totalThemes,
            'lastPublication' => $lastPublication,
            'categories' => $categories,
            'history' => $history,
            'recentDocuments' => $recentDocuments,
        ]);
    }

    /**
     * Search for organizations with rank
     */
    public function searchOrganisation(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $request->get('query');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        // Cache the ranked list of organizations for 1 hour to avoid heavy queries
        $rankedOrganisations = \Cache::remember('organisations_ranked_by_count', 3600, function () {
            return OpenOverheidDocument::whereNotNull('organisation')
                ->selectRaw('organisation, COUNT(*) as count')
                ->groupBy('organisation')
                ->orderBy('count', 'desc')
                ->get()
                ->values() // Ensure array is indexed 0, 1, 2...
                ->map(function ($item, $index) {
                    return [
                        'organisation' => $item->organisation,
                        'count' => (int) $item->count,
                        'rank' => $index + 1,
                    ];
                });
        });

        // Filter the cached list
        $results = $rankedOrganisations->filter(function ($item) use ($query) {
            return stripos($item['organisation'], $query) !== false;
        })->take(10)->values();

        return response()->json($results);
    }

    /**
     * Get quarterly document counts for top organizations
     */
    private function getQuarterlyOrganisationData(TypesenseSearchService $searchService, int $year, array $topOrgs, ?string $category = null, ?string $theme = null): array
    {
        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        $series = [];
        
        foreach ($topOrgs as $org) {
            $orgName = $org['organisation'];
            $data = [];
            
            foreach ([1, 2, 3, 4] as $q) {
                $startDate = now()->setYear($year)->startOfYear()->addMonths(($q - 1) * 3);
                $endDate = $startDate->copy()->endOfQuarter();
                
                // Don't query future quarters
                if ($startDate->isFuture()) {
                    $data[] = 0;
                    continue;
                }
                
                try {
                    $filters = [
                        'publication_date:>=' . $startDate->timestamp,
                        'publication_date:<=' . $endDate->timestamp,
                        'organisation:=' . $orgName,
                    ];

                    if ($category) {
                        $filters[] = 'category:=' . $category;
                    }
                    if ($theme) {
                        $filters[] = 'theme:=' . $theme;
                    }
                    
                    $results = $searchService->search('*', [
                        'filter_by' => implode(' && ', $filters),
                        'per_page' => 0,
                    ]);
                    
                    $data[] = $results['found'] ?? 0;
                } catch (\Exception $e) {
                    $data[] = 0;
                }
            }
            
            $series[] = [
                'name' => $orgName,
                'data' => $data,
            ];
        }

        return [
            'categories' => $quarters,
            'series' => $series,
        ];
    }
}
