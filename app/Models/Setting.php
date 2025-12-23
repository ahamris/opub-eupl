<?php

namespace App\Models;

use App\Helpers\Variable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    /**
     * Cache key prefix for settings.
     */
    protected const CACHE_PREFIX = 'setting_';

    /**
     * Cache key for all settings.
     */
    protected const CACHE_KEY_ALL = 'settings_all';

    /**
     * Get cache TTL in seconds.
     */
    protected static function getCacheTtl(): int
    {
        return Variable::CACHE_TTL;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        '_key',
        '_value',
        'group',
    ];

    /**
     * Get a setting value by key (cached).
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = static::CACHE_PREFIX . $key;
        
        return Cache::remember($cacheKey, static::getCacheTtl(), function () use ($key, $default) {
            $setting = static::where('_key', $key)->first();
            return $setting ? $setting->_value : $default;
        });
    }

    /**
     * Set a setting value by key.
     * Only creates if the key doesn't exist, does not update existing keys.
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @return Setting|null
     */
    public static function set(string $key, $value, string $group = 'general'): ?Setting
    {
        // Check if key already exists
        $existing = static::where('_key', $key)->first();
        
        if ($existing) {
            // Key exists, return existing setting without updating
            return $existing;
        }
        
        // Key doesn't exist, create new setting
        // Cache will be cleared automatically via model events
        return static::create([
            '_key' => $key,
            '_value' => $value,
            'group' => $group,
        ]);
    }

    /**
     * Force set a setting value by key (updates if exists, creates if not).
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @return Setting
     */
    public static function forceSet(string $key, $value, string $group = 'general'): Setting
    {
        return static::updateOrCreate(
            ['_key' => $key],
            [
                '_value' => $value,
                'group' => $group,
            ]
        );
    }

    /**
     * Get all settings by group (cached).
     *
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByGroup(string $group)
    {
        $cacheKey = static::CACHE_PREFIX . 'group_' . $group;
        
        return Cache::remember($cacheKey, static::getCacheTtl(), function () use ($group) {
            return static::where('group', $group)->get();
        });
    }

    /**
     * Get all settings as key-value array (cached).
     *
     * @param string|null $group
     * @return array
     */
    public static function getAll(?string $group = null): array
    {
        if ($group) {
            $cacheKey = static::CACHE_PREFIX . 'all_group_' . $group;
            
            return Cache::remember($cacheKey, static::getCacheTtl(), function () use ($group) {
                return static::where('group', $group)->pluck('_value', '_key')->toArray();
            });
        }
        
        return Cache::remember(static::CACHE_KEY_ALL, static::getCacheTtl(), function () {
            return static::pluck('_value', '_key')->toArray();
        });
    }

    /**
     * Clear all settings cache.
     */
    public static function clearCache(): void
    {
        // Clear individual setting caches
        static::all()->each(function ($setting) {
            Cache::forget(static::CACHE_PREFIX . $setting->_key);
        });
        
        // Clear group caches
        $groups = static::select('group')->distinct()->pluck('group');
        $groups->each(function ($group) {
            Cache::forget(static::CACHE_PREFIX . 'group_' . $group);
            Cache::forget(static::CACHE_PREFIX . 'all_group_' . $group);
        });
        
        // Clear all settings cache
        Cache::forget(static::CACHE_KEY_ALL);
    }

    /**
     * Clear cache for a specific setting.
     */
    protected function clearSettingCache(): void
    {
        // Clear individual setting cache
        Cache::forget(static::CACHE_PREFIX . $this->_key);
        
        // Clear group caches
        Cache::forget(static::CACHE_PREFIX . 'group_' . $this->group);
        Cache::forget(static::CACHE_PREFIX . 'all_group_' . $this->group);
        
        // Clear all settings cache
        Cache::forget(static::CACHE_KEY_ALL);
    }

    /**
     * Boot the model and register event listeners.
     */
    protected static function booted(): void
    {
        // Clear cache when a setting is created
        static::created(function ($setting) {
            $setting->clearSettingCache();
        });

        // Clear cache when a setting is updated
        static::updated(function ($setting) {
            $setting->clearSettingCache();
        });

        // Clear cache when a setting is deleted
        static::deleted(function ($setting) {
            $setting->clearSettingCache();
        });
    }
}
