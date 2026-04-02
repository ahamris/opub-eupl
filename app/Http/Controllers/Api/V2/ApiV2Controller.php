<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Bestuursorgaan;
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
            'per_page' => ['integer', 'min:1', 'max:100'],
            'organisation' => ['nullable', 'string'],
            'theme' => ['nullable', 'string'],
            'category' => ['nullable', 'string'],
            'document_type' => ['nullable', 'string'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'sort' => ['nullable', 'string', 'in:relevance,date_desc,date_asc,modified_desc,modified_asc'],
            'semantic' => ['nullable', 'boolean'],
            'group_by' => ['nullable', 'string', 'in:organisation,theme,category,document_type'],
            'enriched' => ['nullable', 'boolean'],
            'exact' => ['nullable', 'boolean'],
        ]);

        $searchService = app(TypesenseSearchService::class);

        $options = [
            'per_page' => $validated['per_page'] ?? 20,
            'page' => $validated['page'] ?? 1,
        ];

        // Build filters (supports pipe-separated multi-values: "val1|val2")
        $filters = [];
        foreach (['organisation', 'theme', 'category', 'document_type'] as $field) {
            if (! empty($validated[$field])) {
                $values = array_map('trim', explode('|', $validated[$field]));
                if (count($values) === 1) {
                    $filters[] = "{$field}:={$values[0]}";
                } else {
                    $escaped = array_map(fn ($v) => '`' . str_replace('`', '', $v) . '`', $values);
                    $filters[] = "{$field}:=[" . implode(',', $escaped) . ']';
                }
            }
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
        match ($sort) {
            'date_desc' => $options['sort_by'] = 'publication_date:desc',
            'date_asc' => $options['sort_by'] = 'publication_date:asc',
            'modified_desc' => $options['sort_by'] = 'synced_at:desc',
            'modified_asc' => $options['sort_by'] = 'synced_at:asc',
            default => null,
        };

        // Grouping
        $groupBy = $validated['group_by'] ?? null;
        if ($groupBy) {
            $options['group_by'] = $groupBy;
            $options['group_limit'] = 3;
        }

        // Exact match mode — disable typo tolerance and synonyms
        if (filter_var($validated['exact'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            $options['typo_tolerance'] = 'false';
            $options['prefix'] = 'false';
            $options['enable_synonyms'] = false;
        }

        // Use semantic (hybrid) or keyword search
        $useSemantic = filter_var($validated['semantic'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $results = $useSemantic
            ? $searchService->semanticSearch($validated['q'], $options)
            : $searchService->search($validated['q'], $options);

        $formatHit = function ($hit) {
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
                'vector_distance' => $hit['vector_distance'] ?? null,
            ];
        };

        $perPage = $validated['per_page'] ?? 20;
        $found = $results['found'] ?? 0;

        // Grouped response
        if ($groupBy && !empty($results['grouped_hits'])) {
            $groups = array_map(function ($group) use ($formatHit, $groupBy) {
                return [
                    'group_key' => $group['group_key'][0] ?? '',
                    'found' => $group['found'] ?? 0,
                    'hits' => array_map($formatHit, $group['hits'] ?? []),
                ];
            }, $results['grouped_hits']);

            return response()->json([
                'groups' => $groups,
                'found' => $found,
                'page' => $validated['page'] ?? 1,
                'per_page' => $perPage,
                'total_pages' => $found > 0 ? (int) ceil($found / $perPage) : 0,
                'search_time_ms' => $results['search_time_ms'] ?? 0,
                'facets' => $results['facet_counts'] ?? [],
                'semantic' => $useSemantic,
                'group_by' => $groupBy,
            ]);
        }

        $hits = array_map($formatHit, $results['hits'] ?? []);

        return response()->json([
            'hits' => $hits,
            'found' => $found,
            'page' => $validated['page'] ?? 1,
            'per_page' => $perPage,
            'total_pages' => $found > 0 ? (int) ceil($found / $perPage) : 0,
            'search_time_ms' => $results['search_time_ms'] ?? 0,
            'facets' => $results['facet_counts'] ?? [],
            'semantic' => $useSemantic,
        ]);
    }

    /**
     * Find similar documents by vector similarity.
     */
    public function similar(string $id): JsonResponse
    {
        $doc = OpenOverheidDocument::where('external_id', $id)->first();

        if (!$doc) {
            return response()->json(['error' => 'Document niet gevonden'], 404);
        }

        $searchService = app(TypesenseSearchService::class);
        $results = $searchService->findSimilar((string) $doc->id, 6);

        $similar = array_map(function ($hit) {
            $d = $hit['document'] ?? $hit;
            return [
                'external_id' => $d['external_id'] ?? '',
                'title' => $d['title'] ?? '',
                'description' => $d['description'] ?? '',
                'organisation' => $d['organisation'] ?? '',
                'publication_date' => isset($d['publication_date']) && $d['publication_date'] > 0
                    ? date('Y-m-d', $d['publication_date']) : null,
                'theme' => $d['theme'] ?? '',
                'vector_distance' => $hit['vector_distance'] ?? null,
            ];
        }, $results['hits'] ?? []);

        return response()->json([
            'document' => $id,
            'similar' => $similar,
            'search_time_ms' => $results['search_time_ms'] ?? 0,
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
                'monthly_publications' => DB::table('open_overheid_documents')
                    ->select(DB::raw("to_char(publication_date, 'YYYY-MM') as month"), DB::raw('count(*) as count'))
                    ->whereNotNull('publication_date')
                    ->where('publication_date', '>=', now()->subMonths(12))
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get(),
                'top_categories' => DB::table('open_overheid_documents')
                    ->select('category', DB::raw('count(*) as count'))
                    ->whereNotNull('category')
                    ->where('category', '!=', '')
                    ->groupBy('category')
                    ->orderByDesc('count')
                    ->limit(10)
                    ->get(),
            ];
        }));
    }

    /**
     * Organisation detail with stats and recent documents.
     */
    public function organisation(Request $request, string $name): JsonResponse
    {
        $orgName = urldecode($name);

        $cacheKey = 'org_detail_' . md5($orgName);

        return response()->json(Cache::remember($cacheKey, 300, function () use ($orgName) {
            $totalDocs = DB::table('open_overheid_documents')
                ->where('organisation', $orgName)
                ->count();

            if ($totalDocs === 0) {
                return response()->json(['error' => 'Organisatie niet gevonden'], 404);
            }

            $themes = DB::table('open_overheid_documents')
                ->select('theme', DB::raw('count(*) as count'))
                ->where('organisation', $orgName)
                ->whereNotNull('theme')
                ->groupBy('theme')
                ->orderByDesc('count')
                ->limit(10)
                ->get();

            $categories = DB::table('open_overheid_documents')
                ->select('category', DB::raw('count(*) as count'))
                ->where('organisation', $orgName)
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->groupBy('category')
                ->orderByDesc('count')
                ->limit(10)
                ->get();

            $monthly = DB::table('open_overheid_documents')
                ->select(DB::raw("to_char(publication_date, 'YYYY-MM') as month"), DB::raw('count(*) as count'))
                ->where('organisation', $orgName)
                ->whereNotNull('publication_date')
                ->where('publication_date', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $recentDocs = OpenOverheidDocument::where('organisation', $orgName)
                ->orderByDesc('publication_date')
                ->limit(10)
                ->get(['external_id', 'title', 'ai_enhanced_title', 'description', 'ai_summary', 'publication_date', 'category', 'theme', 'document_type']);

            $enrichedCount = DB::table('open_overheid_documents')
                ->where('organisation', $orgName)
                ->whereNotNull('ai_enhanced_at')
                ->count();

            // Find matching bestuursorgaan (case-insensitive, with fuzzy fallback)
            $orgInfo = Bestuursorgaan::whereRaw('LOWER(document_match_name) = ?', [mb_strtolower($orgName)])
                ->orWhereRaw('LOWER(naam) = ?', [mb_strtolower($orgName)])
                ->first();

            if (!$orgInfo) {
                // Try partial match: "ministerie van X" -> search for "X" with type Ministerie
                $orgInfo = Bestuursorgaan::whereRaw('LOWER(naam) LIKE ?', ['%' . mb_strtolower($orgName) . '%'])
                    ->first();
            }

            if (!$orgInfo && preg_match('/^ministerie van (.+)$/i', $orgName, $m)) {
                $orgInfo = Bestuursorgaan::whereRaw('LOWER(naam) = ?', [mb_strtolower(trim($m[1]))])
                    ->where('type', 'Ministerie')
                    ->first();
            }

            $bestuursorgaan = null;
            if ($orgInfo) {
                $bestuursorgaan = [
                    'id' => $orgInfo->id,
                    'slug' => $orgInfo->slug,
                    'naam' => $orgInfo->naam,
                    'afkorting' => $orgInfo->afkorting,
                    'type' => $orgInfo->type,
                    'logo_url' => $orgInfo->logo_url,
                    'beschrijving' => $orgInfo->custom_beschrijving ?: $orgInfo->beschrijving,
                    'bezoekadres' => $orgInfo->bezoekadres,
                    'postadres' => $orgInfo->postadres,
                    'woo_adres' => $orgInfo->woo_adres,
                    'woo_email' => $orgInfo->woo_email,
                    'telefoon' => $orgInfo->telefoon,
                    'email' => $orgInfo->email,
                    'website' => $orgInfo->website,
                    'contactformulier_url' => $orgInfo->contactformulier_url,
                    'is_woo_plichtig' => $orgInfo->is_woo_plichtig,
                    'woo_url' => $orgInfo->woo_url,
                    'relatie_ministerie' => $orgInfo->relatie_ministerie,
                    'is_claimed' => $orgInfo->isClaimed(),
                ];
            }

            return [
                'name' => $orgName,
                'total_documents' => $totalDocs,
                'total_enriched' => $enrichedCount,
                'themes' => $themes,
                'categories' => $categories,
                'monthly_publications' => $monthly,
                'bestuursorgaan' => $bestuursorgaan,
                'recent_documents' => $recentDocs->map(fn($d) => [
                    'external_id' => $d->external_id,
                    'title' => $d->ai_enhanced_title ?? $d->title,
                    'description' => $d->ai_summary ?? $d->description,
                    'publication_date' => $d->publication_date?->format('Y-m-d'),
                    'category' => $d->category,
                    'theme' => $d->theme,
                    'document_type' => $d->document_type,
                ]),
            ];
        }));
    }

    /**
     * Submit a WOO request for an organisation.
     */
    public function wooVerzoek(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'organisation' => ['required', 'string'],
            'naam' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'onderwerp' => ['required', 'string', 'max:500'],
            'omschrijving' => ['required', 'string', 'max:5000'],
        ]);

        // Find the bestuursorgaan (case-insensitive)
        $orgName = $validated['organisation'];
        $orgInfo = Bestuursorgaan::whereRaw('LOWER(document_match_name) = ?', [mb_strtolower($orgName)])
            ->orWhereRaw('LOWER(naam) = ?', [mb_strtolower($orgName)])
            ->orWhereRaw('LOWER(naam) LIKE ?', ['%' . mb_strtolower($orgName) . '%'])
            ->first();

        $wooEmail = $orgInfo?->woo_email ?: $orgInfo?->email;

        // Store the request
        $contact = \App\Models\Contact::create([
            'email' => $validated['email'],
            'full_name' => $validated['naam'],
            'organisation' => $validated['organisation'],
            'notes' => "WOO-verzoek: {$validated['onderwerp']}\n\n{$validated['omschrijving']}",
            'status' => 'new',
            'priority' => 'normal',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Uw Woo-verzoek is ontvangen.',
            'woo_email' => $wooEmail,
            'contact_id' => $contact->id,
        ]);
    }
}
