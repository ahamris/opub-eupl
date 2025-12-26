<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperBentoItem
 */
class BentoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'url',
        'image',
        'col_span',
        'is_coming_soon',
        'coming_soon_text',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_coming_soon' => 'boolean',
        'col_span' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Scope to get only active items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the image URL.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return Storage::url($this->image);
    }

    /**
     * Get grid class based on col_span.
     */
    public function getGridClassAttribute(): string
    {
        return $this->col_span === 4 ? 'lg:col-span-4' : 'lg:col-span-2';
    }

    /**
     * Get all active bento items with caching.
     */
    public static function getAllActive()
    {
        return Cache::remember('bento_items_active', 3600, function () {
            return self::active()->ordered()->get();
        });
    }

    /**
     * Clear cache when saved.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('bento_items_active');
        });

        static::deleted(function () {
            Cache::forget('bento_items_active');
        });
    }
}
