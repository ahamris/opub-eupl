<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\OpenOverheidDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IngestController extends Controller
{
    /**
     * Ingest a single document.
     *
     * POST /api/v2/ingest
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'external_id' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'publication_date' => ['nullable', 'date'],
            'document_type' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'theme' => ['nullable', 'string', 'max:255'],
            'organisation' => ['nullable', 'string', 'max:255'],
            'metadata' => ['nullable', 'array'],
            'source' => ['nullable', 'string', 'max:255'],
        ]);

        $doc = OpenOverheidDocument::updateOrCreate(
            ['external_id' => $validated['external_id']],
            [
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'content' => $validated['content'] ?? null,
                'publication_date' => $validated['publication_date'] ?? null,
                'document_type' => $validated['document_type'] ?? null,
                'category' => $validated['category'] ?? null,
                'theme' => $validated['theme'] ?? null,
                'organisation' => $validated['organisation'] ?? null,
                'metadata' => $validated['metadata'] ?? null,
                'synced_at' => now(),
            ]
        );

        Log::info('Document ingested', [
            'external_id' => $doc->external_id,
            'source' => $validated['source'] ?? 'api',
            'action' => $doc->wasRecentlyCreated ? 'created' : 'updated',
        ]);

        return response()->json([
            'status' => 'ok',
            'action' => $doc->wasRecentlyCreated ? 'created' : 'updated',
            'id' => $doc->id,
            'external_id' => $doc->external_id,
        ], $doc->wasRecentlyCreated ? 201 : 200);
    }

    /**
     * Ingest multiple documents in batch.
     *
     * POST /api/v2/ingest/batch
     */
    public function batch(Request $request): JsonResponse
    {
        $request->validate([
            'documents' => ['required', 'array', 'min:1', 'max:100'],
            'documents.*.external_id' => ['required', 'string', 'max:255'],
            'documents.*.title' => ['required', 'string', 'max:1000'],
            'documents.*.description' => ['nullable', 'string'],
            'documents.*.content' => ['nullable', 'string'],
            'documents.*.publication_date' => ['nullable', 'date'],
            'documents.*.document_type' => ['nullable', 'string', 'max:255'],
            'documents.*.category' => ['nullable', 'string', 'max:255'],
            'documents.*.theme' => ['nullable', 'string', 'max:255'],
            'documents.*.organisation' => ['nullable', 'string', 'max:255'],
            'documents.*.metadata' => ['nullable', 'array'],
        ]);

        $source = $request->input('source', 'api');
        $results = ['created' => 0, 'updated' => 0, 'errors' => 0, 'details' => []];

        foreach ($request->input('documents') as $item) {
            try {
                $doc = OpenOverheidDocument::updateOrCreate(
                    ['external_id' => $item['external_id']],
                    [
                        'title' => $item['title'],
                        'description' => $item['description'] ?? null,
                        'content' => $item['content'] ?? null,
                        'publication_date' => $item['publication_date'] ?? null,
                        'document_type' => $item['document_type'] ?? null,
                        'category' => $item['category'] ?? null,
                        'theme' => $item['theme'] ?? null,
                        'organisation' => $item['organisation'] ?? null,
                        'metadata' => $item['metadata'] ?? null,
                        'synced_at' => now(),
                    ]
                );

                $action = $doc->wasRecentlyCreated ? 'created' : 'updated';
                $results[$action]++;
                $results['details'][] = [
                    'external_id' => $item['external_id'],
                    'action' => $action,
                ];
            } catch (\Exception $e) {
                $results['errors']++;
                $results['details'][] = [
                    'external_id' => $item['external_id'] ?? 'unknown',
                    'action' => 'error',
                    'error' => $e->getMessage(),
                ];
            }
        }

        Log::info('Batch ingest completed', [
            'source' => $source,
            'created' => $results['created'],
            'updated' => $results['updated'],
            'errors' => $results['errors'],
        ]);

        return response()->json([
            'status' => 'ok',
            'created' => $results['created'],
            'updated' => $results['updated'],
            'errors' => $results['errors'],
            'total' => count($request->input('documents')),
            'details' => $results['details'],
        ]);
    }

    /**
     * Delete a document.
     *
     * DELETE /api/v2/ingest/{external_id}
     */
    public function destroy(string $externalId): JsonResponse
    {
        $doc = OpenOverheidDocument::where('external_id', $externalId)->first();

        if (! $doc) {
            return response()->json(['error' => 'Document niet gevonden'], 404);
        }

        $doc->delete();

        Log::info('Document deleted via API', ['external_id' => $externalId]);

        return response()->json(['status' => 'ok', 'deleted' => $externalId]);
    }
}
