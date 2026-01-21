<?php

namespace App\Models\Ooori;

use App\Casts\UnicodeJson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OriDocument extends Model
{
    protected $table = 'ori_documents';

    protected $fillable = [
        'external_id',
        'last_discussed_at',
        'raw_data',
        'metadata',
        'extra_fields',
        'synced_at',
        'typesense_synced_at',
    ];

    protected $casts = [
        'raw_data' => UnicodeJson::class,
        'metadata' => UnicodeJson::class,
        'extra_fields' => UnicodeJson::class,
        'last_discussed_at' => 'datetime',
        'synced_at' => 'datetime',
        'typesense_synced_at' => 'datetime',
    ];

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
    public function scopeWhereFullText(Builder $query, string $search): Builder
    {
        return $query->whereRaw(
            "search_vector @@ plainto_tsquery('dutch', ?)",
            [$search]
        );
    }

    /**
     * Scope a query to filter by last_discussed_at for incremental sync.
     */
    public function scopeAfterLastDiscussed(Builder $query, $date): Builder
    {
        return $query->where('last_discussed_at', '>', $date);
    }
}
