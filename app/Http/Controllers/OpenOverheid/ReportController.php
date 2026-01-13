<?php

namespace App\Http\Controllers\OpenOverheid;

use App\Models\OpenOverheidDocument;
use Illuminate\Http\Request;

class ReportController
{
    /**
     * Display the reports dashboard (Woo-style statistics)
     */
    public function index(Request $request): \Illuminate\View\View
    {
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

        // Build base query for documents with publication dates in the selected period
        // Use Carbon instances directly - Laravel will handle date casting automatically
        $baseQuery = OpenOverheidDocument::whereNotNull('publication_date')
            ->whereBetween('publication_date', [$startDate, $endDate]);

        // Build unfiltered query for breakdowns (so users can see all options and click to filter)
        $unfilteredQuery = OpenOverheidDocument::whereNotNull('publication_date')
            ->whereBetween('publication_date', [$startDate, $endDate]);

        // Apply organisation filter if provided (only to filtered query for totals)
        if ($selectedOrganisation) {
            $baseQuery->where('organisation', $selectedOrganisation);
        }

        // Apply category filter if provided (only to filtered query for totals)
        if ($selectedCategory) {
            $baseQuery->where('category', $selectedCategory);
        }

        // Total documents in period (with filters applied)
        $totalDocuments = $baseQuery->count();

        // Documents with decision (completed) - same as total since we're filtering by publication_date
        $documentsWithDecision = $totalDocuments;

        // Documents in progress (published in period but might be ongoing)
        // For now, this is the same as total documents in the period
        $documentsInProgress = $totalDocuments;

        // Average processing time (simulated - would need actual processing dates)
        $avgProcessingDays = 45; // Placeholder

        // Documents per organisation (use unfiltered query so breakdown shows all organizations)
        // This allows users to see all organizations and click to filter further
        $documentsPerOrganisation = (clone $unfilteredQuery)
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

        // Documents per category (use unfiltered query so breakdown shows all categories)
        // This allows users to see all categories and click to filter further
        $documentsPerCategory = (clone $unfilteredQuery)
            ->whereNotNull('category')
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category,
                    'count' => (int) $item->count,
                ];
            })
            ->toArray();

        // Documents per theme (use unfiltered query for consistency)
        $documentsPerTheme = (clone $unfilteredQuery)
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

        // Monthly trend
        if (config('database.default') === 'pgsql') {
            $monthlyTrend = (clone $baseQuery)
                ->selectRaw('DATE_TRUNC(\'month\', publication_date) as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get()
                ->map(function ($item) {
                    // DATE_TRUNC returns a string, convert to Carbon
                    $date = \Carbon\Carbon::parse($item->month);

                    return [
                        'month' => $date->format('Y-m'),
                        'monthName' => $date->format('M Y'),
                        'count' => (int) $item->count,
                    ];
                })
                ->toArray();
        } else {
            // Fallback for non-PostgreSQL databases
            $monthlyTrend = (clone $baseQuery)
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

        // Get all unique organizations from documents in the selected period for dropdown
        $allOrganisations = OpenOverheidDocument::whereNotNull('publication_date')
            ->whereBetween('publication_date', [$startDate, $endDate])
            ->whereNotNull('organisation')
            ->distinct()
            ->orderBy('organisation')
            ->pluck('organisation')
            ->filter()
            ->values()
            ->toArray();

        // Get all unique categories from documents in the selected period for dropdown
        $wooCategoryService = app(\App\Services\OpenOverheid\WooCategoryService::class);
        $allCategories = OpenOverheidDocument::whereNotNull('publication_date')
            ->whereBetween('publication_date', [$startDate, $endDate])
            ->whereNotNull('category')
            ->where('category', '!=', 'Onbekend')
            ->where('category', '!=', 'onbekend')
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

        return view('reports.index', [
            'year' => $year,
            'quarter' => $quarter,
            'selectedOrganisation' => $selectedOrganisation,
            'selectedCategory' => $selectedCategory,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalDocuments' => $totalDocuments,
            'documentsWithDecision' => $documentsWithDecision,
            'documentsInProgress' => $documentsInProgress,
            'avgProcessingDays' => $avgProcessingDays,
            'documentsPerOrganisation' => $documentsPerOrganisation,
            'documentsPerCategory' => $documentsPerCategory,
            'documentsPerTheme' => $documentsPerTheme,
            'monthlyTrend' => $monthlyTrend,
            'availableYears' => $availableYears,
            'allOrganisations' => $allOrganisations,
            'allCategories' => $allCategories,
        ]);
    }
}
