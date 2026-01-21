<?php

namespace App\Models\Ooori;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class OverheidDocument extends Model
{
    protected $connection = 'pgsql2';
    protected $table = 'overheid_documents';

    protected $fillable = [
        'external_id',
        'title',
        'description',
        'content',
        'publication_date',
        'document_type',
        'overheid_category_id',
        'overheid_theme_id',
        'overheid_organisation_id',
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
     * Get the category that owns the document.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(OverheidCategory::class, 'overheid_category_id');
    }

    /**
     * Get the theme that owns the document.
     */
    public function theme(): BelongsTo
    {
        return $this->belongsTo(OverheidTheme::class, 'overheid_theme_id');
    }

    /**
     * Get the organisation that owns the document.
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(OverheidOrganisation::class, 'overheid_organisation_id');
    }

    /**
     * Scope a query to only include documents that need Typesense sync.
     */
    public function scopeNeedsTypesenseSync(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('typesense_synced_at')
                ->orWhereColumn('typesense_synced_at', '<', 'updated_at');
        });
    }

    /**
     * Scope a query to filter by full-text search.
     */
    public function scopeWhereFullText(Builder $query, array $columns, string $search): Builder
    {
        return $query->whereRaw(
            "search_vector @@ plainto_tsquery('dutch', ?)",
            [$search]
        );
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange(Builder $query, ?string $from = null, ?string $to = null): Builder
    {
        if ($from) {
            $query->where('publication_date', '>=', $from);
        }

        if ($to) {
            $query->where('publication_date', '<=', $to);
        }

        return $query;
    }

    /**
     * Scope a query to filter by document type.
     */
    public function scopeByDocumentType(Builder $query, string|array|null $type): Builder
    {
        if (empty($type)) {
            return $query;
        }

        if (is_array($type)) {
            return $query->whereIn('document_type', $type);
        }

        return $query->where('document_type', $type);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory(Builder $query, ?string $category): Builder
    {
        if (empty($category)) {
            return $query;
        }

        return $query->whereHas('category', function ($q) use ($category) {
            $q->where('name', $category)
                ->orWhere('visible_name', $category);
        });
    }

    /**
     * Scope a query to filter by theme.
     */
    public function scopeByTheme(Builder $query, string|array|null $theme): Builder
    {
        if (empty($theme)) {
            return $query;
        }

        return $query->whereHas('theme', function ($q) use ($theme) {
            if (is_array($theme)) {
                $q->whereIn('name', $theme);
            } else {
                $q->where('name', $theme);
            }
        });
    }

    /**
     * Scope a query to filter by organisation.
     */
    public function scopeByOrganisation(Builder $query, string|array|null $organisation): Builder
    {
        if (empty($organisation)) {
            return $query;
        }

        return $query->whereHas('organisation', function ($q) use ($organisation) {
            if (is_array($organisation)) {
                $q->whereIn('name', $organisation);
            } else {
                $q->where('name', $organisation);
            }
        });
    }

    /**
     * Get all documents that are members of this dossier (via identiteitsgroep relations).
     * 
     * @param bool $usePrecomputed Use pre-computed dossier_members table if available (default: true)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDossierMembers(bool $usePrecomputed = true)
    {
        // Try to use pre-computed table first for better performance
        if ($usePrecomputed && $this->hasPrecomputedMembers()) {
            return $this->getDossierMembersFromTable();
        }

        // Fallback to original method (parse JSON metadata)
        return $this->getDossierMembersFromMetadata();
    }

    /**
     * Check if pre-computed members exist for this dossier.
     */
    public function hasPrecomputedMembers(): bool
    {
        return DB::connection('pgsql2')->table('dossier_members')
            ->where('dossier_external_id', $this->external_id)
            ->exists();
    }

    /**
     * Get dossier members from pre-computed table (optimized).
     */
    protected function getDossierMembersFromTable()
    {
        $memberExternalIds = DB::connection('pgsql2')->table('dossier_members')
            ->where('dossier_external_id', $this->external_id)
            ->pluck('member_external_id')
            ->toArray();

        if (empty($memberExternalIds)) {
            return collect([]);
        }

        return self::whereIn('external_id', $memberExternalIds)->get();
    }

    /**
     * Get dossier members from metadata (original method, fallback).
     */
    protected function getDossierMembersFromMetadata()
    {
        $metadata = $this->metadata ?? [];
        $documentrelaties = $metadata['documentrelaties'] ?? [];

        if (empty($documentrelaties) || ! is_array($documentrelaties)) {
            return collect([]);
        }

        $relatedExternalIds = [];

        foreach ($documentrelaties as $relation) {
            $role = $relation['role'] ?? '';
            if (str_contains($role, 'identiteitsgroep')) {
                $relatedId = $relation['gerelateerd_document']['id'] ?? $relation['gerelateerd_document']['external_id'] ?? null;
                if ($relatedId) {
                    // Remove version suffix if present
                    $relatedId = preg_replace('/_\d+$/', '', $relatedId);
                    $relatedExternalIds[] = $relatedId;
                }
            }
        }

        if (empty($relatedExternalIds)) {
            return collect([]);
        }

        return self::whereIn('external_id', $relatedExternalIds)->get();
    }
}
