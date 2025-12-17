<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AdminThemeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'base_color',
        'accent_color',
    ];

    /**
     * In-memory cache for the current request to prevent duplicate cache queries.
     */
    protected static ?self $cachedInstance = null;

    public static function getSettings(): self
    {
        // Return cached instance if already loaded in this request
        if (static::$cachedInstance !== null) {
            return static::$cachedInstance;
        }

        // Load from cache (or database if not cached)
        static::$cachedInstance = Cache::remember('admin_theme_settings', 3600, function () {
            return static::firstOrCreate(
                ['id' => 1],
                [
                    'base_color' => 'zinc',
                    'accent_color' => 'indigo',
                ]
            );
        });

        return static::$cachedInstance;
    }

    /**
     * Clear cache when settings are updated.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('admin_theme_settings');
            // Clear in-memory cache
            static::$cachedInstance = null;
        });

        static::deleted(function () {
            Cache::forget('admin_theme_settings');
            // Clear in-memory cache
            static::$cachedInstance = null;
        });
    }

    public function getBaseColorAttribute(string $value): string
    {
        return $value ?: 'zinc';
    }

    public function getAccentColorAttribute(string $value): string
    {
        return $value ?: 'indigo';
    }
}
