<?php

namespace App\Models;

use App\Helpers\Variable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Static Page Model
 */
class StaticPage extends Model
{
    use Sluggable;

    const string CACHE_KEY = 'static_pages';

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'short_description',
        'content',
        // SEO Fields
        'meta_description',
        'meta_keywords',
        'meta_robots',
        'og_title',
        'og_description',
        'og_image',
        'canonical_url',
        // Button 1
        'button_1_text',
        'button_1_url',
        'button_1_style',
        'button_1_icon',
        'button_1_new_tab',
        // Button 2
        'button_2_text',
        'button_2_url',
        'button_2_style',
        'button_2_icon',
        'button_2_new_tab',
        // Settings
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'button_1_new_tab' => 'boolean',
        'button_2_new_tab' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model and set up event listeners
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

    /**
     * Sluggable configuration
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'maxLength' => 255,
                'separator' => '-',
                'includeTrashed' => true,
            ],
        ];
    }

    /**
     * Set the slug attribute with proper cleaning
     */
    public function setSlugAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['slug'] = \Illuminate\Support\Str::slug($value, '-');
        } else {
            $this->attributes['slug'] = null;
        }
    }

    /**
     * Get the URL for this page
     */
    public function getLinkUrlAttribute(): string
    {
        if (empty($this->slug)) {
            return '#';
        }
        return route('page.show', ['slug' => $this->slug]);
    }

    /**
     * Scope for active pages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * Get cached active pages
     */
    public static function getCachedActivePages()
    {
        return Cache::remember(
            self::CACHE_KEY . '_active',
            Variable::CACHE_TTL,
            fn() => self::active()->ordered()->get()
        );
    }

    /**
     * Get a cached page by slug
     */
    public static function getCachedBySlug(string $slug)
    {
        return Cache::remember(
            self::CACHE_KEY . '_slug_' . $slug,
            Variable::CACHE_TTL,
            fn() => self::where('slug', $slug)->active()->first()
        );
    }

    /**
     * Clear page cache
     */
    public static function clearCache(): void
    {
        try {
            Cache::forget(self::CACHE_KEY . '_active');
            
            // Clear individual page caches
            $slugs = self::pluck('slug')->toArray();
            foreach ($slugs as $slug) {
                Cache::forget(self::CACHE_KEY . '_slug_' . $slug);
            }
        } catch (\Exception $e) {
            // Log error but don't break the process
        }
    }
}
