<?php

namespace App\Http\Controllers\OpenOverheid;

use App\Http\Controllers\Controller;
use App\Models\OpenOverheidDocument;
use App\Services\AI\DossierEnhancementService;
use Illuminate\Http\Request;

class DossierController extends Controller
{
    /**
     * Display a listing of all dossiers.
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
            'informatiecategorie' => ['nullable'],
            'informatiecategorie.*' => ['string'],
            'thema' => ['nullable'],
            'thema.*' => ['string'],
            'organisatie' => ['nullable'],
            'organisatie.*' => ['string'],
            'status' => ['nullable', 'string', 'in:actief,gesloten'],
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
        $organisatie = $validated['organisatie'] ?? null;

        try {
            // Use same search query structure as SearchController
            $searchQuery = new \App\DataTransferObjects\OpenOverheid\OpenOverheidSearchQuery(
                zoektekst: $validated['zoeken'] ?? '',
                page: (int) ($validated['pagina'] ?? 1),
                perPage: (int) ($validated['per_page'] ?? 20),
                publicatiedatumVan: $publicatiedatumVan,
                publicatiedatumTot: $publicatiedatumTot,
                documentsoort: null, // Dossiers don't use document type filter
                informatiecategorie: is_array($validated['informatiecategorie'] ?? null)
                    ? ($validated['informatiecategorie'][0] ?? null)
                    : ($validated['informatiecategorie'] ?? null),
                thema: is_array($validated['thema'] ?? null) ? $validated['thema'] : ($validated['thema'] ?? null),
                organisatie: $organisatie,
                sort: $validated['sort'] ?? 'relevance',
                titlesOnly: ! empty($validated['titles_only']),
            );

            // Get total count from pre-computed metadata table (FAST!)
            // Only count dossiers that are fully processed (metadata + AI-content)
            $totalDossiers = \Illuminate\Support\Facades\Cache::remember(
                'dossier_count_precomputed_processed',
                300, // Cache for 5 minutes
                function () {
                    return \Illuminate\Support\Facades\DB::table('dossier_metadata')
                        ->join('dossier_ai_content', 'dossier_metadata.dossier_external_id', '=', 'dossier_ai_content.dossier_external_id')
                        ->whereNotNull('dossier_metadata.computed_at')
                        ->whereNotNull('dossier_ai_content.generated_at')
                        ->count();
                }
            );

            // Use local search service but filter to dossiers only
            // The GIN index on metadata->'documentrelaties' speeds this up significantly
            // IMPORTANT: Only show dossiers that have been fully processed (metadata + AI-content)
            $baseQuery = OpenOverheidDocument::inDossier()
                ->select('open_overheid_documents.*')
                ->join('dossier_metadata', 'open_overheid_documents.external_id', '=', 'dossier_metadata.dossier_external_id')
                ->join('dossier_ai_content', 'open_overheid_documents.external_id', '=', 'dossier_ai_content.dossier_external_id')
                ->whereNotNull('dossier_metadata.computed_at')
                ->whereNotNull('dossier_ai_content.generated_at');

            // Apply search query filters
            if (! empty($validated['zoeken'])) {
                if (! empty($validated['titles_only'])) {
                    $likeOp = config('database.default') === 'pgsql' ? 'ilike' : 'like';
                    $baseQuery->where('title', $likeOp, '%'.$validated['zoeken'].'%');
                } else {
                    $baseQuery->whereFullText(['title', 'description', 'content'], $validated['zoeken']);
                }
            }

            $baseQuery->dateRange($publicatiedatumVan, $publicatiedatumTot);

            // Filter by status using metadata (already joined)
            if (! empty($validated['status'])) {
                $baseQuery->where('dossier_metadata.status', $validated['status']);
            }

            // Filter by category using metadata
            if (! empty($validated['informatiecategorie'])) {
                $baseQuery->where('dossier_metadata.category', $validated['informatiecategorie']);
            }

            // Filter by organisation using metadata
            if (! empty($organisatie)) {
                $orgArray = is_array($organisatie) ? $organisatie : [$organisatie];
                $baseQuery->whereIn('dossier_metadata.organisation', $orgArray);
            }

            // Filter by theme using metadata
            if (! empty($validated['thema'])) {
                $thema = is_array($validated['thema']) ? $validated['thema'] : [$validated['thema']];
                $baseQuery->whereIn('dossier_metadata.theme', $thema);
            }

            // Apply sorting - use pre-computed latest_publication_date
            switch ($validated['sort'] ?? 'relevance') {
                case 'publication_date':
                    $baseQuery->orderBy('dossier_metadata.latest_publication_date', 'desc');
                    break;
                case 'modified_date':
                    $baseQuery->orderBy('open_overheid_documents.updated_at', 'desc');
                    break;
                case 'relevance':
                default:
                    $baseQuery->orderBy('dossier_metadata.latest_publication_date', 'desc');
                    break;
            }

            $results = $baseQuery->paginate((int) ($validated['per_page'] ?? 20), ['*'], 'page', (int) ($validated['pagina'] ?? 1));

            // Get pre-computed metadata for all dossiers in this page (FAST - single query)
            $externalIds = $results->getCollection()->pluck('external_id')->toArray();
            $metadataMap = collect();
            if (! empty($externalIds)) {
                $metadataMap = \Illuminate\Support\Facades\DB::table('dossier_metadata')
                    ->whereIn('dossier_external_id', $externalIds)
                    ->get()
                    ->keyBy('dossier_external_id');
            }

            // Get AI content for all dossiers (also cached/pre-computed)
            $enhancementService = app(DossierEnhancementService::class);
            $aiContentMap = [];
            foreach ($externalIds as $externalId) {
                $aiContent = $enhancementService->getDossierAiContent($externalId);
                if ($aiContent) {
                    $aiContentMap[$externalId] = $aiContent;
                }
            }

            // Enhance results with pre-computed metadata and AI-content (NO N+1 queries!)
            $results->getCollection()->transform(function ($document) use ($metadataMap, $aiContentMap) {
                $metadata = $metadataMap[$document->external_id] ?? null;
                $aiContent = $aiContentMap[$document->external_id] ?? null;

                // Use pre-computed metadata (should always exist since we filter on it)
                if ($metadata) {
                    $document->dossier_status = $metadata->status;
                    $document->dossier_member_count = $metadata->member_count;
                } else {
                    // Fallback (shouldn't happen, but just in case)
                    $twoYearsAgo = now()->subYears(2);
                    $latestDate = $document->publication_date;
                    $document->dossier_status = ($latestDate && $latestDate->gte($twoYearsAgo)) ? 'actief' : 'gesloten';
                    $document->dossier_member_count = 1;
                }

                // Add AI content to document (should always exist since we filter on it)
                $document->ai_enhanced_title = $aiContent->enhanced_title ?? null;
                $document->ai_summary = $aiContent->summary ?? null;
                $document->has_audio = ! empty($aiContent->audio_url);

                // Get list of documents in this dossier for display
                try {
                    $dossierDocument = \App\Models\OpenOverheidDocument::where('external_id', $document->external_id)->first();
                    if ($dossierDocument) {
                        $members = $dossierDocument->getDossierMembers();
                        $allMembers = $members->push($dossierDocument)->unique('id');
                        $document->dossier_documents = $allMembers->map(function ($doc) {
                            return [
                                'id' => $doc->id,
                                'external_id' => $doc->external_id,
                                'title' => $doc->title,
                                'publication_date' => $doc->publication_date?->format('d-m-Y'),
                            ];
                        })->toArray();
                    }
                } catch (\Exception $e) {
                    $document->dossier_documents = [];
                }

                return $document;
            });

            // Format results to match zoekresultaten view structure
            $formattedResults = [
                'items' => $results->items(),
                'total' => $results->total(),
                'page' => $results->currentPage(),
                'perPage' => $results->perPage(),
                'hasNextPage' => $results->hasMorePages(),
                'hasPreviousPage' => $results->currentPage() > 1,
            ];

            // Calculate filter counts based on current results (only for dossiers domain)
            $filterCounts = $this->calculateFilterCounts($baseQuery, $validated);

            // Get all available filter options for "Toon meer"
            $allFilterOptions = $this->getAllFilterOptions($baseQuery);

            // Use same view as /zoeken but pass 'isDossier' flag
            return view('zoekresultaten', [
                'results' => $formattedResults,
                'query' => $searchQuery,
                'documentCount' => $totalDossiers,
                'filters' => $validated,
                'filterCounts' => $filterCounts,
                'allFilterOptions' => $allFilterOptions,
                'isDossier' => true, // Flag to indicate we're showing dossiers
            ]);
        } catch (\Exception $e) {
            \Log::error('Dossier search error', ['error' => $e->getMessage()]);

            return view('zoekresultaten', [
                'results' => ['items' => [], 'total' => 0],
                'query' => $searchQuery ?? (object) $validated,
                'documentCount' => \Illuminate\Support\Facades\Cache::remember('dossier_count_precomputed_processed', 300, fn () => \Illuminate\Support\Facades\DB::table('dossier_metadata')->join('dossier_ai_content', 'dossier_metadata.dossier_external_id', '=', 'dossier_ai_content.dossier_external_id')->whereNotNull('dossier_metadata.computed_at')->whereNotNull('dossier_ai_content.generated_at')->count()),
                'filters' => $validated,
                'filterCounts' => [],
                'allFilterOptions' => [],
                'error' => $e->getMessage(),
                'isDossier' => true,
            ]);
        }
    }

    /**
     * Calculate filter counts based on current search results (dossiers domain only).
     */
    private function calculateFilterCounts($baseQuery, array $validated): array
    {
        $counts = [
            'week' => 0,
            'maand' => 0,
            'jaar' => 0,
            'status' => [
                'actief' => 0,
                'gesloten' => 0,
            ],
            'organisatie' => [],
            'informatiecategorie' => [],
            'thema' => [],
        ];

        try {
            // Use pre-computed metadata table for FAST filter counts
            // Only count dossiers that are fully processed (metadata + AI-content)
            $metadataQuery = \Illuminate\Support\Facades\DB::table('dossier_metadata')
                ->join('dossier_ai_content', 'dossier_metadata.dossier_external_id', '=', 'dossier_ai_content.dossier_external_id')
                ->whereNotNull('dossier_metadata.computed_at')
                ->whereNotNull('dossier_ai_content.generated_at');

            // Apply same filters to metadata table
            if (! empty($validated['zoeken'])) {
                // Join with documents for text search
                $metadataQuery->join('open_overheid_documents', 'dossier_metadata.dossier_external_id', '=', 'open_overheid_documents.external_id');
                $likeOp = config('database.default') === 'pgsql' ? 'ilike' : 'like';
                if (! empty($validated['titles_only'])) {
                    $metadataQuery->where('open_overheid_documents.title', $likeOp, '%'.$validated['zoeken'].'%');
                } else {
                    if (config('database.default') === 'pgsql') {
                        $metadataQuery->whereRaw(
                            "open_overheid_documents.search_vector @@ plainto_tsquery('dutch', ?)",
                            [$validated['zoeken']]
                        );
                    } else {
                        // MariaDB/MySQL fallback: use LIKE search
                        $searchTerm = '%'.$validated['zoeken'].'%';
                        $metadataQuery->where(function ($q) use ($searchTerm) {
                            $q->where('open_overheid_documents.title', 'like', $searchTerm)
                              ->orWhere('open_overheid_documents.description', 'like', $searchTerm)
                              ->orWhere('open_overheid_documents.content', 'like', $searchTerm);
                        });
                    }
                }
            }

            // Date filter counts from pre-computed metadata
            $now = now();
            $counts['week'] = (clone $metadataQuery)->where('latest_publication_date', '>=', $now->copy()->subWeek())->count();
            $counts['maand'] = (clone $metadataQuery)->where('latest_publication_date', '>=', $now->copy()->subMonth())->count();
            $counts['jaar'] = (clone $metadataQuery)->where('latest_publication_date', '>=', $now->copy()->subYear())->count();

            // Status counts from pre-computed metadata (FAST!)
            $counts['status']['actief'] = (clone $metadataQuery)->where('status', 'actief')->count();
            $counts['status']['gesloten'] = (clone $metadataQuery)->where('status', 'gesloten')->count();

            // Get filter counts from pre-computed metadata (FAST!)
            // Only count dossiers that are fully processed (metadata + AI-content)
            $metadataCountQuery = \Illuminate\Support\Facades\DB::table('dossier_metadata')
                ->join('dossier_ai_content', 'dossier_metadata.dossier_external_id', '=', 'dossier_ai_content.dossier_external_id')
                ->whereNotNull('dossier_metadata.computed_at')
                ->whereNotNull('dossier_ai_content.generated_at');

            // Apply search filter if present
            if (! empty($validated['zoeken'])) {
                $metadataCountQuery->join('open_overheid_documents', 'dossier_metadata.dossier_external_id', '=', 'open_overheid_documents.external_id');
                $likeOp = config('database.default') === 'pgsql' ? 'ilike' : 'like';
                if (! empty($validated['titles_only'])) {
                    $metadataCountQuery->where('open_overheid_documents.title', $likeOp, '%'.$validated['zoeken'].'%');
                } else {
                    if (config('database.default') === 'pgsql') {
                        $metadataCountQuery->whereRaw(
                            "open_overheid_documents.search_vector @@ plainto_tsquery('dutch', ?)",
                            [$validated['zoeken']]
                        );
                    } else {
                        // MariaDB/MySQL fallback: use LIKE search
                        $searchTerm = '%'.$validated['zoeken'].'%';
                        $metadataCountQuery->where(function ($q) use ($searchTerm) {
                            $q->where('open_overheid_documents.title', 'like', $searchTerm)
                              ->orWhere('open_overheid_documents.description', 'like', $searchTerm)
                              ->orWhere('open_overheid_documents.content', 'like', $searchTerm);
                        });
                    }
                }
            }

            // Get unique organisations from metadata
            $organisations = (clone $metadataCountQuery)
                ->whereNotNull('organisation')
                ->distinct()
                ->pluck('organisation')
                ->filter()
                ->unique()
                ->toArray();

            foreach ($organisations as $org) {
                $counts['organisatie'][$org] = (clone $metadataCountQuery)
                    ->where('organisation', $org)
                    ->count();
            }

            // Get unique categories from metadata
            $categories = (clone $metadataCountQuery)
                ->whereNotNull('category')
                ->distinct()
                ->pluck('category')
                ->filter()
                ->unique()
                ->toArray();

            foreach ($categories as $category) {
                $counts['informatiecategorie'][$category] = (clone $metadataCountQuery)
                    ->where('category', $category)
                    ->count();
            }

            // Get unique themes from metadata
            $themes = (clone $metadataCountQuery)
                ->whereNotNull('theme')
                ->where('theme', '!=', 'Onbekend')
                ->distinct()
                ->pluck('theme')
                ->filter()
                ->unique()
                ->toArray();

            foreach ($themes as $theme) {
                $counts['thema'][$theme] = (clone $metadataCountQuery)
                    ->where('theme', $theme)
                    ->count();
            }
        } catch (\Exception $e) {
            // Return empty counts on error
        }

        return $counts;
    }

    /**
     * Get all available filter options for "Toon meer" functionality (dossiers domain only).
     * Uses pre-computed metadata table for FAST queries.
     * Only includes dossiers that are fully processed (metadata + AI-content).
     */
    private function getAllFilterOptions($baseQuery): array
    {
        // Use pre-computed metadata table (FAST!)
        // Only include dossiers that are fully processed
        $metadataQuery = \Illuminate\Support\Facades\DB::table('dossier_metadata')
            ->join('dossier_ai_content', 'dossier_metadata.dossier_external_id', '=', 'dossier_ai_content.dossier_external_id')
            ->whereNotNull('dossier_metadata.computed_at')
            ->whereNotNull('dossier_ai_content.generated_at');

        // Get all unique organisations from metadata
        $allOrganisations = (clone $metadataQuery)
            ->whereNotNull('organisation')
            ->distinct()
            ->pluck('organisation')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // Get all unique information categories from metadata
        $wooCategoryService = app(\App\Services\OpenOverheid\WooCategoryService::class);
        $allCategories = (clone $metadataQuery)
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

        // Get all unique themes from metadata
        $allThemes = (clone $metadataQuery)
            ->whereNotNull('theme')
            ->where('theme', '!=', 'Onbekend')
            ->distinct()
            ->pluck('theme')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        return [
            'organisatie' => $allOrganisations,
            'informatiecategorie' => $allCategories,
            'thema' => $allThemes,
        ];
    }

    /**
     * Display a specific dossier (all documents in a dossier group).
     */
    public function show(string $id, Request $request)
    {
        $document = OpenOverheidDocument::where('external_id', $id)->firstOrFail();

        // Get all documents in this dossier
        $dossierMembers = $document->getDossierMembers();

        // Include the current document in the list
        $allDossierDocuments = $dossierMembers->push($document)
            ->sortByDesc('publication_date')
            ->values();

        // Get AI-enhanced content for this dossier
        $enhancementService = app(DossierEnhancementService::class);
        $aiContent = $enhancementService->getDossierAiContent($id);

        return view('dossiers.show', [
            'document' => $document,
            'dossierMembers' => $allDossierDocuments,
            'dossierCount' => $allDossierDocuments->count(),
            'aiContent' => $aiContent,
        ]);
    }

    /**
     * Trigger AI enhancement for a dossier (async via queue)
     */
    public function enhance(string $id)
    {
        $document = OpenOverheidDocument::where('external_id', $id)->firstOrFail();

        // Dispatch job to enhance dossier in background
        \App\Jobs\EnhanceDossierJob::dispatch($id);

        return response()->json([
            'message' => 'AI-verrijking is gestart. Dit kan even duren.',
            'status' => 'processing',
        ]);
    }

    /**
     * Get AI summary for a dossier
     */
    public function getSummary(string $id)
    {
        $enhancementService = app(DossierEnhancementService::class);
        $summary = $enhancementService->getOrGenerateSummary($id);

        if (empty($summary)) {
            // If no summary exists, trigger generation
            \App\Jobs\EnhanceDossierJob::dispatch($id);

            return response()->json([
                'message' => 'Samenvatting wordt gegenereerd. Dit kan even duren.',
                'status' => 'processing',
            ], 202);
        }

        return response()->json([
            'summary' => $summary,
            'status' => 'ready',
        ]);
    }

    /**
     * Get audio URL for a dossier
     */
    public function getAudio(string $id)
    {
        $enhancementService = app(DossierEnhancementService::class);
        $audioUrl = $enhancementService->getOrGenerateAudio($id);

        if (empty($audioUrl)) {
            // If no audio exists, trigger generation
            \App\Jobs\GenerateDossierAudioJob::dispatch($id);

            return response()->json([
                'message' => 'Audio wordt gegenereerd. Dit kan even duren.',
                'status' => 'processing',
            ], 202);
        }

        $aiContent = $enhancementService->getDossierAiContent($id);

        return response()->json([
            'audio_url' => $audioUrl,
            'duration_seconds' => $aiContent->audio_duration_seconds ?? null,
            'status' => 'ready',
        ]);
    }
}
