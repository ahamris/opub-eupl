<?php

namespace App\Services\Raadsinformatie;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class RaadsinformatieSearchService
{
    protected function baseUrl(): string
    {
        return rtrim(config('open_overheid.raadsinformatie.base_url'), '/');
    }

    /**
     * Search documents across all ORI indices using Elasticsearch query DSL.
     */
    public function search(array $query, int $from = 0, int $size = 100, ?string $indices = null): array
    {
        $indices = $indices ?? config('open_overheid.raadsinformatie.indices', 'ori_*');
        $url = $this->baseUrl() . '/' . $indices . '/_search';

        $body = array_merge($query, [
            'from' => $from,
            'size' => $size,
        ]);

        return $this->request('POST', $url, $body);
    }

    /**
     * Search documents by text query with optional filters.
     */
    public function searchDocuments(
        string $text = '',
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?array $classifications = null,
        int $from = 0,
        int $size = 100,
        ?string $indices = null,
    ): array {
        $must = [];
        $filter = [];

        if ($text !== '') {
            $must[] = [
                'simple_query_string' => [
                    'query' => $text,
                    'fields' => ['text', 'title', 'description', 'name'],
                ],
            ];
        } else {
            $must[] = ['match_all' => new \stdClass];
        }

        if ($dateFrom || $dateTo) {
            $range = [];
            if ($dateFrom) {
                $range['gte'] = $dateFrom;
            }
            if ($dateTo) {
                $range['lte'] = $dateTo;
            }
            $filter[] = ['range' => ['last_discussed_at' => $range]];
        }

        if ($classifications) {
            $filter[] = ['terms' => ['classification' => $classifications]];
        }

        $query = [
            'query' => [
                'bool' => [
                    'must' => $must,
                    'filter' => $filter,
                ],
            ],
            '_source' => [
                'title', 'name', 'description', 'text',
                'last_discussed_at', 'start_date', 'classification',
                'organization', 'organization_id',
                '@type', 'sources',
            ],
            'sort' => [
                ['last_discussed_at' => ['order' => 'desc', 'unmapped_type' => 'date']],
            ],
        ];

        return $this->search($query, $from, $size, $indices);
    }

    /**
     * Get a single document by its Elasticsearch _id.
     */
    public function getDocument(string $id, ?string $indices = null): ?array
    {
        $indices = $indices ?? config('open_overheid.raadsinformatie.indices', 'ori_*');
        $url = $this->baseUrl() . '/' . $indices . '/_search';

        $body = [
            'query' => [
                'term' => ['_id' => $id],
            ],
            'size' => 1,
        ];

        $response = $this->request('POST', $url, $body);
        $hits = $response['hits']['hits'] ?? [];

        return ! empty($hits) ? $hits[0] : null;
    }

    /**
     * List available indices.
     */
    public function listIndices(): array
    {
        $url = $this->baseUrl() . '/_cat/indices?v&format=json';

        return $this->request('GET', $url);
    }

    /**
     * Get the total document count across indices.
     */
    public function getTotalCount(?string $indices = null): int
    {
        $indices = $indices ?? config('open_overheid.raadsinformatie.indices', 'ori_*');
        $url = $this->baseUrl() . '/' . $indices . '/_count';

        $response = $this->request('POST', $url, [
            'query' => ['match_all' => new \stdClass],
        ]);

        return $response['count'] ?? 0;
    }

    /**
     * Perform an HTTP request to the ORI Elasticsearch API.
     */
    protected function request(string $method, string $url, ?array $body = null): array
    {
        $timeout = config('open_overheid.raadsinformatie.timeout', 30);

        try {
            $request = Http::timeout($timeout)
                ->retry(2, 500)
                ->withHeaders(['Content-Type' => 'application/json']);

            $response = match ($method) {
                'GET' => $request->get($url),
                'POST' => $request->post($url, $body ?? []),
                default => throw new RuntimeException("Unsupported HTTP method: {$method}"),
            };

            if (! $response->successful()) {
                Log::channel('sync_errors')->error('ORI Elasticsearch API error', [
                    'url' => $url,
                    'method' => $method,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new RuntimeException(
                    'ORI Elasticsearch API error: ' . $response->status()
                );
            }

            return $response->json();
        } catch (\Exception $e) {
            if ($e instanceof RuntimeException) {
                throw $e;
            }

            Log::channel('sync_errors')->error('ORI Elasticsearch API exception', [
                'url' => $url,
                'method' => $method,
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
