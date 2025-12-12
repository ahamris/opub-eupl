<?php

namespace App\Http\Controllers\OpenOverheid;

use App\DataTransferObjects\OpenOverheid\OpenOverheidSearchQuery;
use App\Http\Controllers\Controller;
use App\Models\OpenOverheidDocument;
use App\Services\OpenOverheid\OpenOverheidLocalSearchService;
use Illuminate\Http\Request;

class ThemaController extends Controller
{
    public function __construct(
        private readonly OpenOverheidLocalSearchService $localService
    ) {}

    /**
     * Display a listing of all themes.
     */
    public function index(Request $request)
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
            // Get base query for themes only
            $baseQuery = OpenOverheidDocument::whereNotNull('theme')
                ->where('theme', '!=', 'Onbekend');

            // Apply search query filters
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

            // Apply sorting
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

            // Transform to match API response format
            $formattedResults = [
                'items' => $results->items(),
                'total' => $results->total(),
                'page' => $results->currentPage(),
                'perPage' => $results->perPage(),
                'hasNextPage' => $results->hasMorePages(),
                'hasPreviousPage' => $results->currentPage() > 1,
            ];

            $documentCount = OpenOverheidDocument::whereNotNull('theme')
                ->where('theme', '!=', 'Onbekend')
                ->count();

            // Calculate filter counts based on current results (only for themes domain)
            $filterCounts = $this->calculateFilterCounts($baseQuery, $validated);

            // Get all available filter options for "Toon meer"
            $allFilterOptions = $this->getAllFilterOptions($baseQuery);

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

            return view('themas.index', [
                'results' => ['items' => [], 'total' => 0],
                'query' => $query,
                'documentCount' => OpenOverheidDocument::whereNotNull('theme')->where('theme', '!=', 'Onbekend')->count(),
                'filters' => $validated,
                'filterCounts' => [],
                'allFilterOptions' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Calculate filter counts based on current search results (themes domain only).
     */
    private function calculateFilterCounts($baseQuery, array $validated): array
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
            // Calculate date filter counts
            $now = now();
            $counts['week'] = (clone $baseQuery)->where('publication_date', '>=', $now->copy()->subWeek())->count();
            $counts['maand'] = (clone $baseQuery)->where('publication_date', '>=', $now->copy()->subMonth())->count();
            $counts['jaar'] = (clone $baseQuery)->where('publication_date', '>=', $now->copy()->subYear())->count();

            // Get unique document types
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

            // Get unique themes (only from themes domain)
            $themes = (clone $baseQuery)
                ->whereNotNull('theme')
                ->where('theme', '!=', 'Onbekend')
                ->distinct()
                ->pluck('theme')
                ->filter()
                ->toArray();

            foreach ($themes as $theme) {
                $counts['thema'][$theme] = (clone $baseQuery)
                    ->where('theme', $theme)
                    ->count();
            }

            // Get unique organisations
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

            // Get unique categories
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
     * Get all available filter options for "Toon meer" functionality (themes domain only).
     */
    private function getAllFilterOptions($baseQuery): array
    {
        // Get all unique document types
        $allDocumentTypes = (clone $baseQuery)
            ->whereNotNull('document_type')
            ->distinct()
            ->orderBy('document_type')
            ->pluck('document_type')
            ->filter()
            ->values()
            ->toArray();

        // Get all unique themes (only from themes domain)
        $allThemes = (clone $baseQuery)
            ->whereNotNull('theme')
            ->where('theme', '!=', 'Onbekend')
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
        $wooCategoryService = app(\App\Services\OpenOverheid\WooCategoryService::class);
        $allCategories = (clone $baseQuery)
            ->whereNotNull('category')
            ->where('category', '!=', 'Onbekend')
            ->distinct()
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

        return [
            'documentsoort' => $allDocumentTypes,
            'thema' => $allThemes,
            'organisatie' => $allOrganisations,
            'informatiecategorie' => $allCategories,
        ];
    }
}
