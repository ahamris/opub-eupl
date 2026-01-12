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

        // Total documents in period
        $totalDocuments = $baseQuery->count();

        // Documents with decision (completed) - same as total since we're filtering by publication_date
        $documentsWithDecision = $totalDocuments;

        // Documents in progress (published in period but might be ongoing)
        // For now, this is the same as total documents in the period
        $documentsInProgress = $totalDocuments;

        // Average processing time (simulated - would need actual processing dates)
        $avgProcessingDays = 45; // Placeholder

        // Documents per organisation
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

        // Documents per category
        $documentsPerCategory = (clone $baseQuery)
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

        // Documents per theme
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

        return view('reports.index', [
            'year' => $year,
            'quarter' => $quarter,
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
        ]);
    }
}
