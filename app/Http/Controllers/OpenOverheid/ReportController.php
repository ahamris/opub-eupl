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
    public function index(Request $request): \Illuminate\View\View
    {
        $searchService = app(TypesenseSearchService::class);

        // Get available years from database (years that have documents)
        $availableYears = OpenOverheidDocument::whereNotNull('publication_date')
            ->selectRaw('EXTRACT(YEAR FROM publication_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->map(fn($y) => (int) $y)
            ->toArray();

        // Default to the most recent year with data, or current year if no data
        $defaultYear = !empty($availableYears) ? max($availableYears) : now()->year;

        // Get period filters (default to year with most data)
        $year = (int) $request->get('jaar', $defaultYear);
        $quarter = $request->get('kwartaal') ? (int) $request->get('kwartaal') : null;
        $selectedOrganisation = $request->get('organisatie');
        $selectedCategory = $request->get('informatiecategorie');

        // Calculate date range based on filters
        $startDate = $quarter
            ? now()->setYear($year)->startOfYear()->addMonths(($quarter - 1) * 3)
            : now()->setYear($year)->startOfYear();

        $endDate = $quarter
            ? $startDate->copy()->endOfQuarter()
            : now()->setYear($year)->endOfYear();

        // Ensure endDate doesn't exceed today for accurate reporting
        if ($endDate->isFuture()) {
            $endDate = now();
        }

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

            // Extract category facets
            $catFacet = $facetCounts->firstWhere('field_name', 'category');
            $wooCategoryService = app(\App\Services\OpenOverheid\WooCategoryService::class);
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
            
            $totalDocuments = $baseQuery->count();
            $documentsPerOrganisation = [];
            $documentsPerCategory = [];
            $documentsPerTheme = [];
        }

        $documentsWithDecision = $totalDocuments;

        // Get quarterly data for top organizations for the chart
        $quarterlyOrgData = $this->getQuarterlyOrganisationData($searchService, $year, array_slice($documentsPerOrganisation, 0, 5));

        // Monthly trend using database (simpler for date grouping)
        $baseQueryForTrend = OpenOverheidDocument::whereNotNull('publication_date')
            ->whereBetween('publication_date', [$startDate, $endDate]);
        
        if ($selectedOrganisation) {
            $baseQueryForTrend->where('organisation', $selectedOrganisation);
        }
        if ($selectedCategory) {
            $baseQueryForTrend->where('category', $selectedCategory);
        }

        if (config('database.default') === 'pgsql') {
            $monthlyTrend = (clone $baseQueryForTrend)
                ->selectRaw('DATE_TRUNC(\'month\', publication_date) as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get()
                ->map(function ($item) {
                    $date = \Carbon\Carbon::parse($item->month);
                    return [
                        'month' => $date->format('Y-m'),
                        'monthName' => $date->format('M Y'),
                        'count' => (int) $item->count,
                    ];
                })
                ->toArray();
        } else {
            $monthlyTrend = (clone $baseQueryForTrend)
                ->selectRaw('DATE_FORMAT(publication_date, \'%Y-%m\') as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get()
                ->map(function ($item) {
                    $date = \Carbon\Carbon::createFromFormat('Y-m', $item->month);
                    return [
                        'month' => $item->month,
                        'monthName' => $date->format('M Y'),
                        'count' => (int) $item->count,
                    ];
                })
                ->toArray();
        }

        // Get all unique organizations for dropdown (from Typesense unfiltered)
        try {
            $unfilteredFilters = [
                'publication_date:>=' . $startDate->timestamp,
                'publication_date:<=' . $endDate->timestamp,
            ];
            $unfilteredResults = $searchService->search('*', [
                'filter_by' => implode(' && ', $unfilteredFilters),
                'facet_by' => 'organisation,category',
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
        } catch (\Exception $e) {
            $allOrganisations = [];
            $allCategories = [];
        }

        return view('reports.index', [
            'year' => $year,
            'quarter' => $quarter,
            'selectedOrganisation' => $selectedOrganisation,
            'selectedCategory' => $selectedCategory,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalDocuments' => $totalDocuments,
            'documentsWithDecision' => $documentsWithDecision,
            'documentsPerOrganisation' => $documentsPerOrganisation,
            'documentsPerCategory' => $documentsPerCategory,
            'documentsPerTheme' => $documentsPerTheme,
            'monthlyTrend' => $monthlyTrend,
            'availableYears' => $availableYears,
            'allOrganisations' => $allOrganisations,
            'allCategories' => $allCategories,
            'quarterlyOrgData' => $quarterlyOrgData,
        ]);
    }

    /**
     * Get quarterly document counts for top organizations
     */
    private function getQuarterlyOrganisationData(TypesenseSearchService $searchService, int $year, array $topOrgs): array
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
