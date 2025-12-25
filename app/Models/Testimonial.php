<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote',
        'author',
        'role',
        'organization',
        'rating',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'rating' => 'integer',
    ];

    /**
     * Scope to get only active testimonials.
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
     * Get display text for role and organization.
     */
    public function getRoleDisplayAttribute(): string
    {
        if ($this->role && $this->organization) {
            return "{$this->role} · {$this->organization}";
        }

        return $this->role ?? $this->organization ?? '';
    }

    /**
     * Get all active testimonials with caching.
     */
    public static function getAllActive()
    {
        return Cache::remember('testimonials_active', 3600, function () {
            return self::active()->ordered()->get();
        });
    }

    /**
     * Clear cache when saved.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('testimonials_active');
        });

        static::deleted(function () {
            Cache::forget('testimonials_active');
        });
    }
}
