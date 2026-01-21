<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperOpenOverheidDocument
 */
class OpenOverheidDocumentOld extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'title',
        'description',
        'content',
        'publication_date',
        'document_type',
        'category',
        'theme',
        'organisation',
        'metadata',
        'synced_at',
        'typesense_synced_at',
        'ai_enhanced_title',
        'ai_enhanced_description',
        'ai_summary',
        'ai_keywords',
        'ai_enhanced_at',
    ];

    protected $casts = [
        'metadata' => \App\Casts\UnicodeJson::class,
        'publication_date' => 'date',
        'synced_at' => 'datetime',
        'typesense_synced_at' => 'datetime',
        'ai_keywords' => 'array',
        'ai_enhanced_at' => 'datetime',
    ];

    /**
     * Scope for full-text search on title, description, and content.
     * Uses PostgreSQL's full-text search capabilities.
     */
    public function scopeWhereFullText(Builder $query, array $columns, string $search): Builder
    {
        if (config('database.default') !== 'pgsql') {
            // Fallback for non-PostgreSQL databases
            return $query->where(function ($q) use ($columns, $search) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        // PostgreSQL full-text search using the generated search_vector column
        return $query->whereRaw(
            "search_vector @@ plainto_tsquery('dutch', ?)",
            [$search]
        );
    }

    /**
     * Scope to filter by publication date range.
     * Accepts dates in d-m-Y format and converts to Y-m-d for database.
     */
    public function scopeDateRange(Builder $query, ?string $from, ?string $to): Builder
    {
        if ($from) {
            // Convert d-m-Y to Y-m-d if needed
            $fromDate = self::convertDateFormat($from);
            $query->where('publication_date', '>=', $fromDate);
        }
        if ($to) {
            // Convert d-m-Y to Y-m-d if needed
            $toDate = self::convertDateFormat($to);
            $query->where('publication_date', '<=', $toDate);
        }

        return $query;
    }

    /**
     * Convert date format from d-m-Y to Y-m-d if needed.
     */
    private static function convertDateFormat(string $date): string
    {
        // Try to parse d-m-Y format
        $parsed = \DateTime::createFromFormat('d-m-Y', $date);
        if ($parsed !== false) {
            return $parsed->format('Y-m-d');
        }

        // If already in Y-m-d format, return as is
        return $date;
    }

    /**
     * Scope to filter by document type.
     * Supports both single string and array of types.
     */
    public function scopeByDocumentType(Builder $query, string|array|null $type): Builder
    {
        if (empty($type)) {
            return $query;
        }

        if (is_array($type)) {
            $query->whereIn('document_type', $type);
        } else {
            $query->where('document_type', $type);
        }

        return $query;
    }

    /**
     * Scope to filter by category.
     * Normalizes the category before searching to handle variations and case differences.
     */
    public function scopeByCategory(Builder $query, string|array|null $category): Builder
    {
        if (empty($category)) {
            return $query;
        }

        $wooCategoryService = app(\App\Services\OpenOverheid\WooCategoryService::class);

        // Determine the correct case-insensitive comparison operator
        $isPostgres = config('database.default') === 'pgsql';
        $likeOperator = $isPostgres ? 'ilike' : 'like';

        if (is_array($category)) {
            // Normalize each category in the array and collect all possible matches
            $searchTerms = [];
            foreach ($category as $cat) {
                $normalized = $wooCategoryService->normalizeCategory($cat) ?? $cat;
                $searchTerms[] = $normalized;
                // Also include original in case normalization doesn't match
                if ($normalized !== $cat) {
                    $searchTerms[] = $cat;
                }
            }

            // Use case-insensitive search
            $query->where(function ($q) use ($searchTerms, $likeOperator) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('category', $likeOperator, $term);
                }
            });
        } else {
            // Normalize the category
            $normalizedCategory = $wooCategoryService->normalizeCategory($category) ?? $category;

            // Use case-insensitive search
            // Also check original category in case it's already in the correct format
            $query->where(function ($q) use ($normalizedCategory, $category, $likeOperator) {
                $q->where('category', $likeOperator, $normalizedCategory);
                if ($normalizedCategory !== $category) {
                    $q->orWhere('category', $likeOperator, $category);
                }
            });
        }

        return $query;
    }

    /**
     * Get the formatted category for display (e.g., "2j. Onderzoeksrapporten").
     */
    public function getFormattedCategoryAttribute(): ?string
    {
        $service = app(\App\Services\OpenOverheid\WooCategoryService::class);

        return $service->formatCategoryForDisplay($this->category);
    }

    /**
     * Get the normalized category name.
     */
    public function getNormalizedCategoryAttribute(): ?string
    {
        $service = app(\App\Services\OpenOverheid\WooCategoryService::class);

        return $service->normalizeCategory($this->category);
    }

    /**
     * Scope to filter by theme.
     * Supports both single string and array of themes.
     */
    public function scopeByTheme(Builder $query, string|array|null $theme): Builder
    {
        if (empty($theme)) {
            return $query;
        }

        if (is_array($theme)) {
            $query->whereIn('theme', $theme);
        } else {
            $query->where('theme', $theme);
        }

        return $query;
    }

    /**
     * Scope to filter by organisation.
     * Supports both single string and array of organisations.
     */
    public function scopeByOrganisation(Builder $query, string|array|null $organisation): Builder
    {
        if (empty($organisation)) {
            return $query;
        }

        if (is_array($organisation)) {
            $query->whereIn('organisation', $organisation);
        } else {
            $query->where('organisation', $organisation);
        }

        return $query;
    }

    /**
     * Scope to filter documents that need Typesense sync.
     * Documents need sync if typesense_synced_at is NULL or older than updated_at.
     */
    public function scopeNeedsTypesenseSync(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('typesense_synced_at')
              ->orWhereColumn('typesense_synced_at', '<', 'updated_at');
        });
    }

    /**
     * Scope to filter documents that are part of a dossier (have identiteitsgroep relations).
     */
    public function scopeInDossier(Builder $query): Builder
    {
        if (config('database.default') === 'pgsql') {
            return $query->whereRaw(
                "EXISTS (
                    SELECT 1 
                    FROM jsonb_array_elements(metadata::jsonb->'documentrelaties') AS rel
                    WHERE rel->>'role' LIKE ?
                )",
                ['%identiteitsgroep%']
            );
        }

        // MariaDB/MySQL: Use JSON_SEARCH to find identiteitsgroep in metadata
        return $query->whereRaw(
            "JSON_SEARCH(metadata, 'one', '%identiteitsgroep%', NULL, '$.documentrelaties[*].role') IS NOT NULL"
        );
    }

    /**
     * Count documents that are part of dossiers.
     * Cached for performance.
     */
    public static function countDossiers(): int
    {
        return \Illuminate\Support\Facades\Cache::remember('dossier_count_query', 300, function () {
            return self::inDossier()->count();
        });
    }

    /**
     * Extract external ID from a relation URL.
     */
    protected function extractExternalIdFromRelation(string $relationUrl): ?string
    {
        // Extract ID from URLs like: https://open.overheid.nl/documenten/oep-ob-...
        if (preg_match('/\/documenten\/([^\/]+)$/', $relationUrl, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Get all documents in the same dossier (identiteitsgroep).
     * Returns documents that share the same dossier relation.
     */
    public function getDossierMembers(): \Illuminate\Database\Eloquent\Collection
    {
        $metadata = $this->metadata ?? [];
        $documentrelaties = $metadata['documentrelaties'] ?? [];

        if (empty($documentrelaties) || ! is_array($documentrelaties)) {
            return self::newCollection([]);
        }

        // Extract all dossier relation IDs (identiteitsgroep)
        $dossierRelationIds = [];
        foreach ($documentrelaties as $relation) {
            $role = $relation['role'] ?? '';
            $relationUrl = $relation['relation'] ?? '';

            // Check if this is an "identiteitsgroep" relation (dossier grouping)
            if (str_contains($role, 'identiteitsgroep') && ! empty($relationUrl)) {
                $relatedId = $this->extractExternalIdFromRelation($relationUrl);
                if ($relatedId) {
                    $dossierRelationIds[] = $relatedId;
                }
            }
        }

        if (empty($dossierRelationIds)) {
            return self::newCollection([]);
        }

        // Find all documents that have any of these dossier IDs in their relations
        // We'll use a PostgreSQL JSONB query to search for documents
        $allDossierIds = array_merge($dossierRelationIds, [$this->external_id]);

        // Build query to find documents that reference any of the dossier IDs
        // Exclude the current document
        $isPostgres = config('database.default') === 'pgsql';

        return self::where('external_id', '!=', $this->external_id)
            ->where(function ($query) use ($allDossierIds, $dossierRelationIds, $isPostgres) {
                // Find documents that have any of the dossier IDs in their relations
                foreach ($allDossierIds as $dossierId) {
                    if ($isPostgres) {
                        // PostgreSQL: Use jsonb_array_elements (cast to jsonb to ensure correct type)
                        $query->orWhereRaw(
                            "EXISTS (
                                SELECT 1 
                                FROM jsonb_array_elements(metadata::jsonb->'documentrelaties') AS rel
                                WHERE rel->>'relation' LIKE ?
                            )",
                            ['%'.$dossierId.'%']
                        );
                    } else {
                        // MariaDB/MySQL: Use JSON_SEARCH
                        $query->orWhereRaw(
                            "JSON_SEARCH(metadata, 'one', ?, NULL, '$.documentrelaties[*].relation') IS NOT NULL",
                            ['%'.$dossierId.'%']
                        );
                    }
                }
                // Also find documents by their external_id if they match dossier IDs
                if (! empty($dossierRelationIds)) {
                    $query->orWhereIn('external_id', $dossierRelationIds);
                }
            })
            ->orderBy('publication_date', 'desc')
            ->get();
    }
}
