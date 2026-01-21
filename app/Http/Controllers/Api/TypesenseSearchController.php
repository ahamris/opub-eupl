<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Typesense\TypesenseSearchService;
use Illuminate\Http\Request;

class TypesenseSearchController extends Controller
{
    public function search(Request $request, TypesenseSearchService $typesense)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'in:10,20,50,100'],
            'publicatiedatum_van' => ['nullable', 'date_format:d-m-Y'],
            'publicatiedatum_tot' => ['nullable', 'date_format:d-m-Y'],
            'documentsoort' => ['nullable'],
            'documentsoort.*' => ['string'],
            'informatiecategorie' => ['nullable', 'string'],
            'thema' => ['nullable'],
            'thema.*' => ['string'],
            'organisatie' => ['nullable'],
            'organisatie.*' => ['string'],
            'publicatiebestemming' => ['nullable'],
            'publicatiebestemming.*' => ['string'],
            'sort' => ['nullable', 'string', 'in:relevance,publication_date,modified_date'],
            'include_raw' => ['nullable', 'boolean'],
            'include_content' => ['nullable', 'boolean'],
        ]);

        $q = (string) ($validated['q'] ?? '');
        $page = (int) ($validated['page'] ?? 1);
        $perPage = (int) ($validated['per_page'] ?? 20);

        $filters = [];

        $documentsoort = $validated['documentsoort'] ?? [];
        if (! is_array($documentsoort)) {
            $documentsoort = $documentsoort ? [$documentsoort] : [];
        }
        if (! empty($documentsoort)) {
            $types = array_filter(array_map('trim', $documentsoort));
            if (! empty($types)) {
                $escaped = array_map(fn ($t) => '='.str_replace(['"', "'"], '', $t), $types);
                $filters[] = 'document_type:['.implode(',', $escaped).']';
            }
        }

        $informatiecategorie = $validated['informatiecategorie'] ?? null;
        if (! empty($informatiecategorie)) {
            $category = str_replace(['"', "'"], '', trim((string) $informatiecategorie));
            if ($category !== '') {
                $filters[] = 'category:='.$category;
            }
        }

        $thema = $validated['thema'] ?? [];
        if (! is_array($thema)) {
            $thema = $thema ? [$thema] : [];
        }
        if (! empty($thema)) {
            $themes = array_filter(array_map('trim', $thema));
            if (! empty($themes)) {
                $escaped = array_map(fn ($t) => '='.str_replace(['"', "'"], '', $t), $themes);
                $filters[] = 'theme:['.implode(',', $escaped).']';
            }
        }

        $organisatie = $validated['organisatie'] ?? [];
        if (! is_array($organisatie)) {
            $organisatie = $organisatie ? [$organisatie] : [];
        }
        if (! empty($organisatie)) {
            $orgs = array_filter(array_map('trim', $organisatie));
            if (! empty($orgs)) {
                $escaped = array_map(fn ($o) => '='.str_replace(['"', "'"], '', $o), $orgs);
                $filters[] = 'organisation:['.implode(',', $escaped).']';
            }
        }

        $publicatiebestemming = $validated['publicatiebestemming'] ?? [];
        if (! is_array($publicatiebestemming)) {
            $publicatiebestemming = $publicatiebestemming ? [$publicatiebestemming] : [];
        }
        if (! empty($publicatiebestemming)) {
            $destinations = array_filter(array_map('trim', $publicatiebestemming));
            if (! empty($destinations)) {
                $escaped = array_map(fn ($d) => '='.str_replace(['"', "'"], '', $d), $destinations);
                $filters[] = 'publication_destination:['.implode(',', $escaped).']';
            }
        }

        // Date filters (publication_date is stored as unix timestamp)
        $publicatiedatumVan = $validated['publicatiedatum_van'] ?? null;
        $publicatiedatumTot = $validated['publicatiedatum_tot'] ?? null;
        if ($publicatiedatumVan || $publicatiedatumTot) {
            $dateFilters = [];
            if ($publicatiedatumVan) {
                $fromDate = \DateTime::createFromFormat('d-m-Y', (string) $publicatiedatumVan);
                if ($fromDate) {
                    $dateFilters[] = '>='.$fromDate->getTimestamp();
                }
            }
            if ($publicatiedatumTot) {
                $toDate = \DateTime::createFromFormat('d-m-Y', (string) $publicatiedatumTot);
                if ($toDate) {
                    $dateFilters[] = '<='.$toDate->getTimestamp();
                }
            }
            if (! empty($dateFilters)) {
                $filters[] = 'publication_date:['.implode(',', $dateFilters).']';
            }
        }

        $sort = (string) ($validated['sort'] ?? 'relevance');
        $sortBy = 'publication_date:desc';
        if ($sort === 'modified_date') {
            $sortBy = 'synced_at:desc';
        }

        $options = [
            'per_page' => $perPage,
            'page' => $page,
            'sort_by' => $sortBy,
            'facet_by' => 'document_type,theme,organisation,category,publication_destination',
            'max_facet_values' => 100,
        ];

        if (! empty($filters)) {
            $options['filter_by'] = implode(' && ', $filters);
        }

        $results = $typesense->search($q, $options);

        $includeRaw = (bool) ($validated['include_raw'] ?? false);
        $includeContent = (bool) ($validated['include_content'] ?? false);

        $items = array_map(function (array $hit) use ($includeContent) {
            $doc = $hit['document'] ?? $hit;

            $publicationDate = null;
            if (isset($doc['publication_date']) && (int) $doc['publication_date'] > 0) {
                $publicationDate = date('Y-m-d', (int) $doc['publication_date']);
            }

            $item = [
                'id' => $doc['external_id'] ?? $doc['id'] ?? null,
                'title' => $doc['title'] ?? 'Geen titel',
                'description' => $doc['description'] ?? '',
                'publication_date' => $publicationDate,
                'document_type' => $doc['document_type'] ?? null,
                'category' => $doc['category'] ?? null,
                'theme' => $doc['theme'] ?? null,
                'organisation' => $doc['organisation'] ?? null,
                'publication_destination' => $doc['publication_destination'] ?? null,
                'url' => $doc['url'] ?? null,
            ];

            if ($includeContent) {
                $item['content'] = $doc['content'] ?? null;
            }

            return $item;
        }, $results['hits'] ?? []);

        $payload = [
            'items' => $items,
            'total' => $results['found'] ?? 0,
            'page' => $page,
            'perPage' => $perPage,
            'search_time_ms' => $results['search_time_ms'] ?? 0,
            'facet_counts' => $results['facet_counts'] ?? [],
            'query' => $q,
        ];

        if ($includeRaw) {
            $payload['raw'] = $results;
        }

        return response()->json($payload);
    }
}

