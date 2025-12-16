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
        // Get period filters (default to current year)
        $year = (int) $request->get('jaar', now()->year);
        $quarter = $request->get('kwartaal') ? (int) $request->get('kwartaal') : null;

        // Calculate date range based on filters
        $startDate = $quarter
            ? now()->setYear($year)->startOfYear()->addMonths(($quarter - 1) * 3)
            : now()->setYear($year)->startOfYear();

        $endDate = $quarter
            ? $startDate->copy()->endOfQuarter()
            : now()->setYear($year)->endOfYear();

        // Total documents in period
        $totalDocuments = OpenOverheidDocument::whereBetween('publication_date', [$startDate, $endDate])
            ->count();

        // Documents with decision (completed)
        $documentsWithDecision = OpenOverheidDocument::whereBetween('publication_date', [$startDate, $endDate])
            ->whereNotNull('publication_date')
            ->count();

        // Documents in progress (published in period but might be ongoing)
        $documentsInProgress = OpenOverheidDocument::where('publication_date', '>=', $startDate)
            ->where('publication_date', '<=', now())
            ->count();

        // Average processing time (simulated - would need actual processing dates)
        $avgProcessingDays = 45; // Placeholder

        // Documents per organisation
        $documentsPerOrganisation = OpenOverheidDocument::whereBetween('publication_date', [$startDate, $endDate])
            ->whereNotNull('organisation')
            ->selectRaw('organisation, COUNT(*) as count')
            ->groupBy('organisation')
            ->orderBy('count', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($item) {
                return [
                    'organisation' => $item->organisation,
                    'count' => $item->count,
                ];
            })
            ->toArray();

        // Documents per category
        $documentsPerCategory = OpenOverheidDocument::whereBetween('publication_date', [$startDate, $endDate])
            ->whereNotNull('category')
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category,
                    'count' => $item->count,
                ];
            })
            ->toArray();

        // Documents per theme
        $documentsPerTheme = OpenOverheidDocument::whereBetween('publication_date', [$startDate, $endDate])
            ->whereNotNull('theme')
            ->selectRaw('theme, COUNT(*) as count')
            ->groupBy('theme')
            ->orderBy('count', 'desc')
            ->limit(15)
            ->get()
            ->map(function ($item) {
                return [
                    'theme' => $item->theme,
                    'count' => $item->count,
                ];
            })
            ->toArray();

        // Monthly trend
        if (config('database.default') === 'pgsql') {
            $monthlyTrend = OpenOverheidDocument::whereBetween('publication_date', [$startDate, $endDate])
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
                        'count' => $item->count,
                    ];
                })
                ->toArray();
        } else {
            // Fallback for non-PostgreSQL databases
            $monthlyTrend = OpenOverheidDocument::whereBetween('publication_date', [$startDate, $endDate])
                ->selectRaw('DATE_FORMAT(publication_date, \'%Y-%m\') as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get()
                ->map(function ($item) {
                    $date = \Carbon\Carbon::createFromFormat('Y-m', $item->month);

                    return [
                        'month' => $item->month,
                        'monthName' => $date->format('M Y'),
                        'count' => $item->count,
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
        ]);
    }
}
