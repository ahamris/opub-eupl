<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\OpenOverheidDocument;
use App\Services\Typesense\TypesenseSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ApiV2Controller extends Controller
{
    /**
     * Search documents via Typesense.
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'max:500'],
            'page' => ['integer', 'min:1', 'max:1000'],
            'per_page' => ['integer', 'min:1', 'max:50'],
            'organisation' => ['nullable', 'string'],
            'theme' => ['nullable', 'string'],
            'category' => ['nullable', 'string'],
            'document_type' => ['nullable', 'string'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'sort' => ['nullable', 'string', 'in:relevance,date_desc,date_asc'],
        ]);

        $searchService = app(TypesenseSearchService::class);

        $options = [
            'per_page' => $validated['per_page'] ?? 20,
            'page' => $validated['page'] ?? 1,
        ];

        // Build filters
        $filters = [];
        if (! empty($validated['organisation'])) {
            $filters[] = "organisation:={$validated['organisation']}";
        }
        if (! empty($validated['theme'])) {
            $filters[] = "theme:={$validated['theme']}";
        }
        if (! empty($validated['category'])) {
            $filters[] = "category:={$validated['category']}";
        }
        if (! empty($validated['document_type'])) {
            $filters[] = "document_type:={$validated['document_type']}";
        }
        if (! empty($validated['date_from'])) {
            $filters[] = 'publication_date:>=' . strtotime($validated['date_from']);
        }
        if (! empty($validated['date_to'])) {
            $filters[] = 'publication_date:<=' . strtotime($validated['date_to']);
        }
        if (! empty($filters)) {
            $options['filter_by'] = implode(' && ', $filters);
        }

        // Sorting
        $sort = $validated['sort'] ?? 'relevance';
        if ($sort === 'date_desc') {
            $options['sort_by'] = 'publication_date:desc';
        } elseif ($sort === 'date_asc') {
            $options['sort_by'] = 'publication_date:asc';
        }

        $results = $searchService->search($validated['q'], $options);

        $hits = array_map(function ($hit) {
            $doc = $hit['document'] ?? $hit;

            return [
                'id' => $doc['id'] ?? null,
                'external_id' => $doc['external_id'] ?? '',
                'title' => $doc['title'] ?? '',
                'description' => $doc['description'] ?? '',
                'organisation' => $doc['organisation'] ?? '',
                'publication_date' => isset($doc['publication_date']) && $doc['publication_date'] > 0
                    ? date('Y-m-d', $doc['publication_date']) : null,
                'document_type' => $doc['document_type'] ?? '',
                'category' => $doc['category'] ?? '',
                'theme' => $doc['theme'] ?? '',
            ];
        }, $results['hits'] ?? []);

        $perPage = $validated['per_page'] ?? 20;
        $found = $results['found'] ?? 0;

        return response()->json([
            'hits' => $hits,
            'found' => $found,
            'page' => $validated['page'] ?? 1,
            'per_page' => $perPage,
            'total_pages' => $found > 0 ? (int) ceil($found / $perPage) : 0,
            'search_time_ms' => $results['search_time_ms'] ?? 0,
            'facets' => $results['facet_counts'] ?? [],
        ]);
    }

    /**
     * Get a single document by external_id.
     */
    public function document(string $id): JsonResponse
    {
        $doc = OpenOverheidDocument::where('external_id', $id)->first();

        if (! $doc) {
            return response()->json(['error' => 'Document niet gevonden'], 404);
        }

        return response()->json([
            'id' => $doc->id,
            'external_id' => $doc->external_id,
            'title' => $doc->title,
            'ai_enhanced_title' => $doc->ai_enhanced_title,
            'description' => $doc->description,
            'ai_summary' => $doc->ai_summary,
            'ai_keywords' => $doc->ai_keywords,
            'organisation' => $doc->organisation,
            'publication_date' => $doc->publication_date?->format('Y-m-d'),
            'document_type' => $doc->document_type,
            'category' => $doc->category,
            'theme' => $doc->theme,
            'metadata' => $doc->metadata,
            'synced_at' => $doc->synced_at?->toIso8601String(),
            'ai_enhanced_at' => $doc->ai_enhanced_at?->toIso8601String(),
        ]);
    }

    /**
     * List dossiers (documents with identiteitsgroep relations).
     */
    public function dossiers(Request $request): JsonResponse
    {
        $page = max(1, (int) $request->get('page', 1));
        $perPage = min(50, max(1, (int) $request->get('per_page', 20)));

        $query = OpenOverheidDocument::inDossier()
            ->orderByDesc('publication_date');

        $total = Cache::remember('dossiers_count', 300, fn() => (clone $query)->count());

        $docs = $query->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get(['id', 'external_id', 'title', 'ai_enhanced_title', 'description', 'ai_summary', 'organisation', 'publication_date', 'document_type', 'category', 'theme']);

        return response()->json([
            'data' => $docs->map(fn($d) => [
                'external_id' => $d->external_id,
                'title' => $d->ai_enhanced_title ?? $d->title,
                'description' => $d->ai_summary ?? $d->description,
                'organisation' => $d->organisation,
                'publication_date' => $d->publication_date?->format('Y-m-d'),
                'category' => $d->category,
                'theme' => $d->theme,
            ]),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int) ceil($total / $perPage),
        ]);
    }

    /**
     * Get a dossier with its members.
     */
    public function dossier(string $id): JsonResponse
    {
        $doc = OpenOverheidDocument::where('external_id', $id)->first();

        if (! $doc) {
            return response()->json(['error' => 'Dossier niet gevonden'], 404);
        }

        $members = $doc->getDossierMembers();

        return response()->json([
            'id' => $doc->id,
            'external_id' => $doc->external_id,
            'title' => $doc->ai_enhanced_title ?? $doc->title,
            'description' => $doc->ai_summary ?? $doc->description,
            'organisation' => $doc->organisation,
            'publication_date' => $doc->publication_date?->format('Y-m-d'),
            'category' => $doc->category,
            'theme' => $doc->theme,
            'metadata' => $doc->metadata,
            'members' => $members->map(fn($m) => [
                'external_id' => $m->external_id,
                'title' => $m->ai_enhanced_title ?? $m->title,
                'organisation' => $m->organisation,
                'publication_date' => $m->publication_date?->format('Y-m-d'),
            ]),
        ]);
    }

    /**
     * Chat send.
     */
    public function chatSend(Request $request): JsonResponse
    {
        return app(\App\Http\Controllers\ChatController::class)->send($request);
    }

    /**
     * Chat conversations.
     */
    public function chatConversations(Request $request): JsonResponse
    {
        return app(\App\Http\Controllers\ChatController::class)->conversations($request);
    }

    /**
     * Chat messages.
     */
    public function chatMessages(Request $request, string $id): JsonResponse
    {
        return app(\App\Http\Controllers\ChatController::class)->messages($request, $id);
    }

    /**
     * Chat delete.
     */
    public function chatDelete(Request $request, string $id): JsonResponse
    {
        return app(\App\Http\Controllers\ChatController::class)->deleteConversation($request, $id);
    }

    /**
     * Site settings for SPA.
     */
    public function settings(): JsonResponse
    {
        $totalDocs = Cache::remember('total_documents', 3600, fn() => OpenOverheidDocument::count());

        return response()->json([
            'name' => config('app.name', 'OPub'),
            'total_documents' => $totalDocs,
        ]);
    }

    /**
     * Statistics / counts.
     */
    public function stats(): JsonResponse
    {
        return response()->json(Cache::remember('api_stats', 300, function () {
            return [
                'total_documents' => OpenOverheidDocument::count(),
                'total_enriched' => OpenOverheidDocument::whereNotNull('ai_enhanced_at')->count(),
                'latest_sync' => OpenOverheidDocument::max('synced_at'),
                'organisations' => DB::table('open_overheid_documents')
                    ->select('organisation', DB::raw('count(*) as count'))
                    ->whereNotNull('organisation')
                    ->groupBy('organisation')
                    ->orderByDesc('count')
                    ->limit(20)
                    ->get(),
                'themes' => DB::table('open_overheid_documents')
                    ->select('theme', DB::raw('count(*) as count'))
                    ->whereNotNull('theme')
                    ->groupBy('theme')
                    ->orderByDesc('count')
                    ->limit(20)
                    ->get(),
            ];
        }));
    }
}
