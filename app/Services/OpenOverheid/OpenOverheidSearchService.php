<?php

namespace App\Services\OpenOverheid;

use App\DataTransferObjects\OpenOverheid\OpenOverheidSearchQuery;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class OpenOverheidSearchService
{
    protected function baseUrl(): string
    {
        return rtrim(config('open_overheid.base_url'), '/');
    }

    /**
     * Perform a search request.
     */
    public function search(OpenOverheidSearchQuery $query): array
    {
        $url = $this->baseUrl().'/zoek';

        $params = [
            'zoektekst' => $query->zoektekst ?? '',
            'start' => $query->offset(),
            'aantalResultaten' => $query->perPage,
        ];

        if ($query->publicatiedatumVan) {
            $params['publicatiedatumVan'] = $query->publicatiedatumVan;
        }
        if ($query->publicatiedatumTot) {
            $params['publicatiedatumTot'] = $query->publicatiedatumTot;
        }
        if ($query->documentsoort) {
            $params['documentsoort'] = $query->documentsoort;
        }
        if ($query->informatiecategorie) {
            $params['informatiecategorie'] = $query->informatiecategorie;
        }
        if ($query->thema) {
            $params['thema'] = $query->thema;
        }
        if ($query->organisatie) {
            $params['organisatie'] = $query->organisatie;
        }

        try {
            $response = Http::timeout(config('open_overheid.timeout'))
                ->get($url, $params);

            if (! $response->successful()) {
                Log::error('Open Overheid API error (search)', [
                    'url' => $url,
                    'params' => $params,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new RuntimeException(
                    'Open Overheid API error (search): '.$response->status()
                );
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Open Overheid API exception (search)', [
                'url' => $url,
                'params' => $params,
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Fetch a single document by its Open Overheid ID (e.g. "oep-...").
     */
    public function getDocument(string $id): array
    {
        $url = $this->baseUrl().'/zoek/'.urlencode($id);

        try {
            $response = Http::timeout(config('open_overheid.timeout'))
                ->get($url);

            if (! $response->successful()) {
                Log::error('Open Overheid API error (detail)', [
                    'url' => $url,
                    'id' => $id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new RuntimeException(
                    'Open Overheid API error (detail): '.$response->status()
                );
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Open Overheid API exception (detail)', [
                'url' => $url,
                'id' => $id,
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
