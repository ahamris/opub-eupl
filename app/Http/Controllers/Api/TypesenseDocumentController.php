<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Typesense\TypesenseSearchService;
use Illuminate\Http\Request;

class TypesenseDocumentController extends Controller
{
    public function show(string $id, Request $request, TypesenseSearchService $typesense)
    {
        $includeContent = filter_var($request->query('include_content', false), FILTER_VALIDATE_BOOLEAN);

        // First try retrieving by Typesense doc id (which maps to local DB id in this project)
        $doc = $typesense->getDocument($id);

        // If not found, fall back to lookup by external_id (used as "id" in search results)
        if ($doc === null) {
            $safe = str_replace(['"', "'"], '', trim($id));

            $results = $typesense->search('*', [
                'per_page' => 1,
                'page' => 1,
                'filter_by' => 'external_id:='.$safe,
                'facet_by' => 'document_type,theme,organisation,category,publication_destination',
                'max_facet_values' => 0,
            ]);

            $firstHit = $results['hits'][0] ?? null;
            if (is_array($firstHit)) {
                $doc = $firstHit['document'] ?? $firstHit;
            }
        }

        if ($doc === null) {
            return response()->json([
                'message' => 'Not found.',
            ], 404);
        }

        if (! $includeContent) {
            unset($doc['content']);
        }

        // Normalize publication_date to ISO if present
        if (isset($doc['publication_date']) && (int) $doc['publication_date'] > 0) {
            $doc['publication_date_iso'] = date('Y-m-d', (int) $doc['publication_date']);
        }

        return response()->json([
            'document' => $doc,
        ]);
    }
}

