<?php

namespace App\Services\OpenOverheid;

use App\DataTransferObjects\OpenOverheid\OpenOverheidSearchQuery;
use App\Models\OpenOverheidDocument;
use Illuminate\Support\Facades\Cache;

class FilterCountService
{
    /**
     * Calculate filter counts based on current search query.
     * Returns counts for all filter options that are relevant to the current search.
     */
    public function calculateFilterCounts(OpenOverheidSearchQuery $query): array
    {
        $cacheKey = $this->getCacheKey($query);

        // Increase cache time to 1 hour (3600 seconds) for better performance
        // Filter counts don't change frequently, so longer cache is acceptable
        return Cache::remember($cacheKey, 3600, function () use ($query) {
            return $this->computeFilterCounts($query);
        });
    }

    /**
     * Compute filter counts without caching.
     */
    private function computeFilterCounts(OpenOverheidSearchQuery $query): array
    {
        $baseQuery = OpenOverheidDocument::query();

        // Apply search text filter if present
        if (! empty($query->zoektekst)) {
            $baseQuery->whereFullText(['title', 'description', 'content'], $query->zoektekst);
        }

        // Apply existing filters (except the one we're counting for)
        $baseQuery = $this->applyExistingFilters($baseQuery, $query);

        $counts = [
            'week' => 0,
            'maand' => 0,
            'jaar' => 0,
            'documentsoort' => [],
            'thema' => [],
            'organisatie' => [],
            'informatiecategorie' => [],
            'bestandstype' => [],
        ];

        // Calculate date filter counts (optimized - single queries)
        $now = now();
        $counts['week'] = (clone $baseQuery)->where('publication_date', '>=', $now->copy()->subWeek())->count();
        $counts['maand'] = (clone $baseQuery)->where('publication_date', '>=', $now->copy()->subMonth())->count();
        $counts['jaar'] = (clone $baseQuery)->where('publication_date', '>=', $now->copy()->subYear())->count();

        // OPTIMIZED: Use GROUP BY queries instead of individual COUNT queries
        // This reduces from potentially 2000+ queries to just 4 queries!
        // Limit to top 500 most common values to prevent memory issues
        $maxResults = 500;

        // Get document type counts in a single GROUP BY query
        $documentTypeCounts = (clone $baseQuery)
            ->whereNotNull('document_type')
            ->selectRaw('document_type, COUNT(*) as count')
            ->groupBy('document_type')
            ->orderByDesc('count')
            ->limit($maxResults)
            ->pluck('count', 'document_type')
            ->toArray();
        $counts['documentsoort'] = $documentTypeCounts;

        // Get theme counts in a single GROUP BY query
        $themeCounts = (clone $baseQuery)
            ->whereNotNull('theme')
            ->selectRaw('theme, COUNT(*) as count')
            ->groupBy('theme')
            ->orderByDesc('count')
            ->limit($maxResults)
            ->pluck('count', 'theme')
            ->toArray();
        $counts['thema'] = $themeCounts;

        // Get organisation counts in a single GROUP BY query
        $organisationCounts = (clone $baseQuery)
            ->whereNotNull('organisation')
            ->selectRaw('organisation, COUNT(*) as count')
            ->groupBy('organisation')
            ->orderByDesc('count')
            ->limit($maxResults)
            ->pluck('count', 'organisation')
            ->toArray();
        $counts['organisatie'] = $organisationCounts;

        // Get category counts in a single GROUP BY query
        $categoryCounts = (clone $baseQuery)
            ->whereNotNull('category')
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('count')
            ->limit($maxResults)
            ->pluck('count', 'category')
            ->toArray();
        $counts['informatiecategorie'] = $categoryCounts;

        // Calculate file type counts from metadata
        $counts['bestandstype'] = $this->calculateFileTypeCounts($baseQuery);

        return $counts;
    }

    /**
     * Apply existing filters to base query (excluding the filter we're counting for).
     */
    private function applyExistingFilters($baseQuery, OpenOverheidSearchQuery $query)
    {
        // Apply date filters
        if ($query->publicatiedatumVan || $query->publicatiedatumTot) {
            $baseQuery->dateRange($query->publicatiedatumVan, $query->publicatiedatumTot);
        }

        // Note: We don't apply other filters here because we want counts for all options
        // regardless of current filter selection. This allows users to see what's available.

        return $baseQuery;
    }

    /**
     * Calculate file type counts from metadata.
     * Maps mime types to display labels (PDF, Word-document, etc.)
     * Uses PostgreSQL JSON queries for efficiency.
     */
    private function calculateFileTypeCounts($baseQuery): array
    {
        // File type mapping: display label => mime type patterns
        $fileTypeMapping = [
            'PDF' => ['application/pdf'],
            'Word-document' => [
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ],
            'E-mailbericht' => ['message/rfc822', 'text/plain'],
            'Presentatie' => [
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            ],
            'Spreadsheet' => [
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ],
            'Afbeelding' => ['image/'],
        ];

        $fileTypeCounts = [];

        // Use PostgreSQL JSON queries for efficiency
        if (config('database.default') === 'pgsql') {
            // OPTIMIZED: Use GROUP BY instead of loading all mime types into memory
            $mimeTypeCounts = (clone $baseQuery)
                ->whereNotNull('metadata')
                ->selectRaw("metadata->'versies'->0->'bestanden'->0->>'mime-type' as mime_type, COUNT(*) as count")
                ->whereRaw("metadata->'versies'->0->'bestanden'->0->>'mime-type' IS NOT NULL")
                ->groupBy('mime_type')
                ->pluck('count', 'mime_type')
                ->toArray();

            // Map mime types to display labels
            foreach ($mimeTypeCounts as $mimeType => $count) {
                $matchedLabel = $this->mapMimeTypeToLabel($mimeType, $fileTypeMapping);
                if ($matchedLabel) {
                    if (! isset($fileTypeCounts[$matchedLabel])) {
                        $fileTypeCounts[$matchedLabel] = 0;
                    }
                    $fileTypeCounts[$matchedLabel] += $count;
                }
            }
        } else {
            // Fallback for non-PostgreSQL: process in PHP using chunking to avoid memory issues
            $fileTypeCounts = [];
            
            (clone $baseQuery)
                ->whereNotNull('metadata')
                ->select('metadata')
                ->chunk(1000, function ($documents) use (&$fileTypeCounts, $fileTypeMapping) {
                    foreach ($documents as $document) {
                        $metadata = $document->metadata;
                        if (! is_array($metadata)) {
                            continue;
                        }

                        // Get first version's first bestand
                        $firstVersion = $metadata['versies'][0] ?? null;
                        if (! $firstVersion || ! isset($firstVersion['bestanden']) || empty($firstVersion['bestanden'])) {
                            continue;
                        }

                        $firstBestand = $firstVersion['bestanden'][0] ?? null;
                        if (! $firstBestand) {
                            continue;
                        }

                        $mimeType = $firstBestand['mime-type'] ?? null;
                        if (! $mimeType) {
                            continue;
                        }

                        $matchedLabel = $this->mapMimeTypeToLabel($mimeType, $fileTypeMapping);
                        if ($matchedLabel) {
                            if (! isset($fileTypeCounts[$matchedLabel])) {
                                $fileTypeCounts[$matchedLabel] = 0;
                            }
                            $fileTypeCounts[$matchedLabel]++;
                        }
                    }
                });
        }

        return $fileTypeCounts;
    }

    /**
     * Map mime type to display label.
     */
    private function mapMimeTypeToLabel(string $mimeType, array $fileTypeMapping): ?string
    {
        foreach ($fileTypeMapping as $label => $patterns) {
            foreach ($patterns as $pattern) {
                // Handle prefix patterns (like 'image/')
                if (str_ends_with($pattern, '/')) {
                    if (str_starts_with($mimeType, $pattern)) {
                        return $label;
                    }
                } else {
                    // Exact match
                    if ($mimeType === $pattern) {
                        return $label;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Get cache key for filter counts based on query.
     */
    private function getCacheKey(OpenOverheidSearchQuery $query): string
    {
        $parts = [
            'filter_counts',
            md5($query->zoektekst ?? ''),
            md5(json_encode($query->publicatiedatumVan ?? '')),
            md5(json_encode($query->publicatiedatumTot ?? '')),
        ];

        return implode(':', $parts);
    }

    /**
     * Clear cache for filter counts.
     */
    public function clearCache(): void
    {
        // Note: In a production environment, you might want to use cache tags
        // For now, we rely on TTL expiration
    }

    /**
     * Get all available filter options (not just counts).
     * Useful for "Toon meer" functionality.
     */
    public function getAllFilterOptions(OpenOverheidSearchQuery $query): array
    {
        $cacheKey = 'filter_options_'.md5($query->zoektekst ?? '');
        
        // Cache filter options for 1 hour since they don't change frequently
        return Cache::remember($cacheKey, 3600, function () use ($query) {
            $baseQuery = OpenOverheidDocument::query();

            // Apply same search text filter if present
            if (! empty($query->zoektekst)) {
                $baseQuery->whereFullText(['title', 'description', 'content'], $query->zoektekst);
            }

            // Limit to reasonable number of unique values to prevent memory exhaustion
            $limit = 1000; // Maximum unique values per filter type

            // Get all unique document types (optimized with select)
            $allDocumentTypes = (clone $baseQuery)
                ->whereNotNull('document_type')
                ->select('document_type')
                ->distinct()
                ->orderBy('document_type')
                ->limit($limit)
                ->pluck('document_type')
                ->filter()
                ->values()
                ->toArray();

            // Get all unique themes (optimized with select)
            $allThemes = (clone $baseQuery)
                ->whereNotNull('theme')
                ->select('theme')
                ->distinct()
                ->orderBy('theme')
                ->limit($limit)
                ->pluck('theme')
                ->filter()
                ->values()
                ->toArray();

            // Get all unique organisations (optimized with select)
            $allOrganisations = (clone $baseQuery)
                ->whereNotNull('organisation')
                ->select('organisation')
                ->distinct()
                ->orderBy('organisation')
                ->limit($limit)
                ->pluck('organisation')
                ->filter()
                ->values()
                ->toArray();

            // Get all unique information categories (optimized with select)
            $allCategories = (clone $baseQuery)
                ->whereNotNull('category')
                ->select('category')
                ->distinct()
                ->orderBy('category')
                ->limit($limit)
                ->pluck('category')
                ->filter()
                ->values()
                ->toArray();

            return [
                'documentsoort' => $allDocumentTypes,
                'thema' => $allThemes,
                'organisatie' => $allOrganisations,
                'informatiecategorie' => $allCategories,
            ];
        });
    }
}
