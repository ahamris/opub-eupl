<?php

namespace App\Services\OpenOverheid;

use App\Models\OpenOverheidDocument;
use Illuminate\Support\Facades\Cache;

class QueryParsingService
{
    /**
     * Detect if a query string matches a filter value (organisation, theme, category, document type).
     */
    public function isFilterValue(string $query): bool
    {
        $lowerQuery = strtolower(trim($query));

        if (empty($lowerQuery) || strlen($lowerQuery) < 2) {
            return false;
        }

        // Check if query matches any filter value
        return $this->matchesOrganisation($lowerQuery)
            || $this->matchesTheme($lowerQuery)
            || $this->matchesCategory($lowerQuery)
            || $this->matchesDocumentType($lowerQuery);
    }

    /**
     * Parse query to determine if it's a search term or filter value.
     * Returns array with 'type' (search|filter) and 'filter_type' if applicable.
     */
    public function parseQuery(string $query): array
    {
        $trimmedQuery = trim($query);

        if (empty($trimmedQuery)) {
            return [
                'type' => 'search',
                'query' => '',
            ];
        }

        if ($this->isFilterValue($trimmedQuery)) {
            $filterType = $this->detectFilterType($trimmedQuery);

            return [
                'type' => 'filter',
                'query' => $trimmedQuery,
                'filter_type' => $filterType,
                'filter_value' => $trimmedQuery,
            ];
        }

        return [
            'type' => 'search',
            'query' => $trimmedQuery,
        ];
    }

    /**
     * Detect which filter type the query matches.
     */
    public function detectFilterType(string $query): ?string
    {
        $lowerQuery = strtolower(trim($query));

        if ($this->matchesOrganisation($lowerQuery)) {
            return 'organisatie';
        }

        if ($this->matchesTheme($lowerQuery)) {
            return 'thema';
        }

        if ($this->matchesCategory($lowerQuery)) {
            return 'informatiecategorie';
        }

        if ($this->matchesDocumentType($lowerQuery)) {
            return 'documentsoort';
        }

        return null;
    }

    /**
     * Check if query matches an organisation.
     */
    private function matchesOrganisation(string $lowerQuery): bool
    {
        return Cache::remember(
            "query_parsing:organisation:{$lowerQuery}",
            3600,
            function () use ($lowerQuery) {
                return OpenOverheidDocument::whereNotNull('organisation')
                    ->whereRaw('LOWER(organisation) LIKE ?', ["%{$lowerQuery}%"])
                    ->exists();
            }
        );
    }

    /**
     * Check if query matches a theme.
     */
    private function matchesTheme(string $lowerQuery): bool
    {
        return Cache::remember(
            "query_parsing:theme:{$lowerQuery}",
            3600,
            function () use ($lowerQuery) {
                return OpenOverheidDocument::whereNotNull('theme')
                    ->whereRaw('LOWER(theme) LIKE ?', ["%{$lowerQuery}%"])
                    ->exists();
            }
        );
    }

    /**
     * Check if query matches a category.
     * Also checks for plural/singular variations (e.g., "advies" matches "adviezen").
     */
    private function matchesCategory(string $lowerQuery): bool
    {
        return Cache::remember(
            "query_parsing:category:{$lowerQuery}",
            3600,
            function () use ($lowerQuery) {
                // Direct match
                $directMatch = OpenOverheidDocument::whereNotNull('category')
                    ->whereRaw('LOWER(category) LIKE ?', ["%{$lowerQuery}%"])
                    ->exists();

                if ($directMatch) {
                    return true;
                }

                // Try plural/singular variations
                // Common Dutch plural patterns
                $variations = [];

                // If query ends with common singular endings, try plural
                if (str_ends_with($lowerQuery, 's')) {
                    // Already plural-like, try singular
                    $variations[] = rtrim($lowerQuery, 's');
                } else {
                    // Try plural forms
                    $variations[] = $lowerQuery.'en'; // advies -> adviezen
                    $variations[] = $lowerQuery.'s';  // advies -> adviezen (alternative)
                }

                foreach ($variations as $variation) {
                    if (OpenOverheidDocument::whereNotNull('category')
                        ->whereRaw('LOWER(category) LIKE ?', ["%{$variation}%"])
                        ->exists()) {
                        return true;
                    }
                }

                return false;
            }
        );
    }

    /**
     * Check if query matches a document type.
     */
    private function matchesDocumentType(string $lowerQuery): bool
    {
        return Cache::remember(
            "query_parsing:document_type:{$lowerQuery}",
            3600,
            function () use ($lowerQuery) {
                return OpenOverheidDocument::whereNotNull('document_type')
                    ->whereRaw('LOWER(document_type) LIKE ?', ["%{$lowerQuery}%"])
                    ->exists();
            }
        );
    }

    /**
     * Get filter suggestions for a query.
     * Returns array of filter suggestions with type and value.
     */
    public function getFilterSuggestions(string $query, int $limit = 5): array
    {
        $lowerQuery = strtolower(trim($query));

        if (empty($lowerQuery) || strlen($lowerQuery) < 2) {
            return [];
        }

        $suggestions = [];

        // Get organisation suggestions (max 2)
        $organisations = OpenOverheidDocument::whereNotNull('organisation')
            ->whereRaw('LOWER(organisation) LIKE ?', ["%{$lowerQuery}%"])
            ->distinct()
            ->orderBy('organisation')
            ->limit(2)
            ->pluck('organisation');

        foreach ($organisations as $org) {
            $suggestions[] = [
                'type' => 'filter_organisatie',
                'filter_type' => 'organisatie',
                'label' => $org,
                'value' => $org,
            ];
        }

        // Get theme suggestions (max 2)
        $themes = OpenOverheidDocument::whereNotNull('theme')
            ->whereRaw('LOWER(theme) LIKE ?', ["%{$lowerQuery}%"])
            ->distinct()
            ->orderBy('theme')
            ->limit(2)
            ->pluck('theme');

        foreach ($themes as $theme) {
            $suggestions[] = [
                'type' => 'filter_thema',
                'filter_type' => 'thema',
                'label' => $theme,
                'value' => $theme,
            ];
        }

        // Get category suggestions (max 3) - improved matching for plural/singular
        $categoryQuery = OpenOverheidDocument::whereNotNull('category');

        // Try direct match first
        $categories = (clone $categoryQuery)
            ->whereRaw('LOWER(category) LIKE ?', ["%{$lowerQuery}%"])
            ->distinct()
            ->orderBy('category')
            ->limit(3)
            ->pluck('category');

        // If not enough results, try variations
        if ($categories->count() < 3) {
            $variations = [];
            if (str_ends_with($lowerQuery, 's')) {
                $variations[] = rtrim($lowerQuery, 's');
            } else {
                $variations[] = $lowerQuery.'en';
                $variations[] = $lowerQuery.'s';
            }

            foreach ($variations as $variation) {
                $additional = (clone $categoryQuery)
                    ->whereRaw('LOWER(category) LIKE ?', ["%{$variation}%"])
                    ->whereNotIn('category', $categories)
                    ->distinct()
                    ->orderBy('category')
                    ->limit(3 - $categories->count())
                    ->pluck('category');

                $categories = $categories->merge($additional);
                if ($categories->count() >= 3) {
                    break;
                }
            }
        }

        foreach ($categories as $category) {
            $suggestions[] = [
                'type' => 'filter_informatiecategorie',
                'filter_type' => 'informatiecategorie',
                'label' => $category,
                'value' => $category,
            ];
        }

        // Get document type suggestions (max 2)
        $documentTypes = OpenOverheidDocument::whereNotNull('document_type')
            ->whereRaw('LOWER(document_type) LIKE ?', ["%{$lowerQuery}%"])
            ->distinct()
            ->orderBy('document_type')
            ->limit(2)
            ->pluck('document_type');

        foreach ($documentTypes as $type) {
            $suggestions[] = [
                'type' => 'filter_documentsoort',
                'filter_type' => 'documentsoort',
                'label' => $type,
                'value' => $type,
            ];
        }

        return array_slice($suggestions, 0, $limit);
    }
}
