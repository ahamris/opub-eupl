<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CtaPanel extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'primary_button_text',
        'primary_button_url',
        'secondary_button_text',
        'secondary_button_url',
        'screenshot',
        'screenshot_alt',
        'variant',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => false,
            ],
        ];
    }

    /**
     * Scope to get only active panels.
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
     * Get a panel by slug with caching.
     */
    public static function getBySlug(string $slug): ?self
    {
        return Cache::remember("cta_panel_{$slug}", 3600, function () use ($slug) {
            return self::where('slug', $slug)->active()->first();
        });
    }

    /**
     * Clear cache when saved.
     */
    protected static function booted(): void
    {
        static::saved(function ($panel) {
            Cache::forget("cta_panel_{$panel->slug}");
            Cache::forget('cta_panels_all');
        });

        static::deleted(function ($panel) {
            Cache::forget("cta_panel_{$panel->slug}");
            Cache::forget('cta_panels_all');
        });
    }
}
