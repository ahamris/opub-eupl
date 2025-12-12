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

        return Cache::remember($cacheKey, 300, function () use ($query) {
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

        // Calculate date filter counts
        $now = now();
        $counts['week'] = (clone $baseQuery)->where('publication_date', '>=', $now->copy()->subWeek())->count();
        $counts['maand'] = (clone $baseQuery)->where('publication_date', '>=', $now->copy()->subMonth())->count();
        $counts['jaar'] = (clone $baseQuery)->where('publication_date', '>=', $now->copy()->subYear())->count();

        // Get unique document types from current results
        $documentTypes = (clone $baseQuery)
            ->whereNotNull('document_type')
            ->distinct()
            ->pluck('document_type')
            ->filter()
            ->toArray();

        foreach ($documentTypes as $type) {
            $counts['documentsoort'][$type] = (clone $baseQuery)
                ->where('document_type', $type)
                ->count();
        }

        // Get unique themes from current results
        $themes = (clone $baseQuery)
            ->whereNotNull('theme')
            ->distinct()
            ->pluck('theme')
            ->filter()
            ->toArray();

        foreach ($themes as $theme) {
            $counts['thema'][$theme] = (clone $baseQuery)
                ->where('theme', $theme)
                ->count();
        }

        // Get unique organisations from current results
        $organisations = (clone $baseQuery)
            ->whereNotNull('organisation')
            ->distinct()
            ->pluck('organisation')
            ->filter()
            ->toArray();

        foreach ($organisations as $org) {
            $counts['organisatie'][$org] = (clone $baseQuery)
                ->where('organisation', $org)
                ->count();
        }

        // Get unique categories from current results
        $categories = (clone $baseQuery)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->toArray();

        foreach ($categories as $category) {
            $counts['informatiecategorie'][$category] = (clone $baseQuery)
                ->where('category', $category)
                ->count();
        }

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
            // Get mime types from metadata using JSON queries
            $mimeTypes = (clone $baseQuery)
                ->whereNotNull('metadata')
                ->selectRaw("metadata->'versies'->0->'bestanden'->0->>'mime-type' as mime_type")
                ->whereRaw("metadata->'versies'->0->'bestanden'->0->>'mime-type' IS NOT NULL")
                ->pluck('mime_type')
                ->filter();

            // Count by mime type
            $mimeTypeCounts = $mimeTypes->countBy()->toArray();

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
            // Fallback for non-PostgreSQL: process in PHP
            $documents = (clone $baseQuery)
                ->whereNotNull('metadata')
                ->get();

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
        $baseQuery = OpenOverheidDocument::query();

        // Apply same search text filter if present
        if (! empty($query->zoektekst)) {
            $baseQuery->whereFullText(['title', 'description', 'content'], $query->zoektekst);
        }

        // Get all unique document types
        $allDocumentTypes = (clone $baseQuery)
            ->whereNotNull('document_type')
            ->distinct()
            ->orderBy('document_type')
            ->pluck('document_type')
            ->filter()
            ->values()
            ->toArray();

        // Get all unique themes
        $allThemes = (clone $baseQuery)
            ->whereNotNull('theme')
            ->distinct()
            ->orderBy('theme')
            ->pluck('theme')
            ->filter()
            ->values()
            ->toArray();

        // Get all unique organisations
        $allOrganisations = (clone $baseQuery)
            ->whereNotNull('organisation')
            ->distinct()
            ->orderBy('organisation')
            ->pluck('organisation')
            ->filter()
            ->values()
            ->toArray();

        // Get all unique information categories
        $allCategories = (clone $baseQuery)
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
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
    }
}
