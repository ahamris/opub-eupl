<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenOverheidDocument extends Model
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
    ];

    protected $casts = [
        'metadata' => 'array',
        'publication_date' => 'date',
        'synced_at' => 'datetime',
        'typesense_synced_at' => 'datetime',
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
     */
    public function scopeByCategory(Builder $query, string|array|null $category): Builder
    {
        if (empty($category)) {
            return $query;
        }

        if (is_array($category)) {
            $query->whereIn('category', $category);
        } else {
            $query->where('category', $category);
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
}
