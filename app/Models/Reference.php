<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperReference
 */
class Reference extends Model
{
    protected $fillable = [
        'icon',
        'title',
        'description',
        'link_url',
        'link_text',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope for active references
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered references
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Get cached active references
     */
    public static function getCachedReferences()
    {
        return Cache::remember('references', 3600, function () {
            return self::active()->ordered()->get();
        });
    }

    /**
     * Clear the cache
     */
    public static function clearCache(): void
    {
        Cache::forget('references');
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }
}
