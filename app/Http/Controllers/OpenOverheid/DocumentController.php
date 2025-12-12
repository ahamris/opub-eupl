<?php

namespace App\Http\Controllers\OpenOverheid;

use App\Http\Controllers\Controller;
use App\Models\OpenOverheidDocument;
use App\Services\OpenOverheid\OpenOverheidSearchService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct(
        private readonly OpenOverheidSearchService $service
    ) {}

    /**
     * Show a single Open Overheid document by its ID (e.g. "oep-...").
     */
    public function show(string $id, Request $request)
    {
        // Try local database first
        $document = OpenOverheidDocument::where('external_id', $id)->first();

        if ($document) {
            $jsonData = $document->toArray();
        } else {
            // Fallback to API
            try {
                $jsonData = $this->service->getDocument($id);
            } catch (\Exception $e) {
                abort(404, 'Document not found: '.$e->getMessage());
            }
        }

        // If format=json is requested, return JSON response
        if ($request->get('format') === 'json') {
            return response()->json($jsonData, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        // If format=xml is requested, return XML response
        if ($request->get('format') === 'xml') {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
            $xml .= '<document>'."\n";
            $xml .= $this->arrayToXml($jsonData);
            $xml .= '</document>';

            return response($xml, 200)
                ->header('Content-Type', 'application/xml; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="'.($jsonData['external_id'] ?? 'document').'.xml"');
        }

        // Get dossier members if document exists in database
        $dossierMembers = collect();
        if ($document) {
            $dossierMembers = $document->getDossierMembers();
        }

        return view('detail', [
            'document' => $document,
            'jsonData' => $jsonData,
            'dossierMembers' => $dossierMembers,
        ]);
    }

    /**
     * Convert array to XML (recursive helper, without XML declaration)
     */
    private function arrayToXml(array $data, string $indent = '  '): string
    {
        $xml = '';

        foreach ($data as $key => $value) {
            // Handle numeric keys (arrays)
            if (is_numeric($key)) {
                $key = 'item';
            }

            // Sanitize XML tag name
            $key = preg_replace('/[^a-z0-9_-]/i', '_', $key);
            if (empty($key) || is_numeric($key[0])) {
                $key = 'item_'.$key;
            }

            if (is_array($value)) {
                if ($this->isAssociativeArray($value)) {
                    $xml .= "{$indent}<{$key}>\n";
                    $xml .= $this->arrayToXml($value, $indent.'  ');
                    $xml .= "{$indent}</{$key}>\n";
                } else {
                    // Sequential array
                    foreach ($value as $item) {
                        if (is_array($item)) {
                            $xml .= "{$indent}<{$key}>\n";
                            $xml .= $this->arrayToXml($item, $indent.'  ');
                            $xml .= "{$indent}</{$key}>\n";
                        } else {
                            $xml .= "{$indent}<{$key}>".htmlspecialchars($item ?? '', ENT_XML1, 'UTF-8')."</{$key}>\n";
                        }
                    }
                }
            } else {
                $xml .= "{$indent}<{$key}>".htmlspecialchars($value ?? '', ENT_XML1, 'UTF-8')."</{$key}>\n";
            }
        }

        return $xml;
    }

    /**
     * Check if array is associative
     */
    private function isAssociativeArray(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
