<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperSetting
 */
class Setting extends Model
{

    protected $fillable = [
        '_key',
        '_value',
        'group',
    ];


    public static function getValue($key, $default = null)
    {
        return Cache::remember("settings.{$key}", 60 * 60, function () use ($key, $default) {
            $setting = self::where('_key', $key)->first();
            return $setting?->_value ?? $default;
        });
    }

    public static function setValue($key, $value, string $group = 'general')
    {
        $existing = self::where('_key', $key)->first();

        if ($existing) {
            // Update existing setting
            $existing->update(['_value' => $value]);
            $setting = $existing;
        } else {
            // Create new setting with required fields
            $setting = self::create([
                '_key' => $key,
                '_value' => $value,
                'group' => $group,
            ]);
        }

        Cache::forget("settings.{$key}");

        return $setting;
    }

    /**
     * Force set a setting value (alias for setValue)
     * This method always updates or creates the setting regardless of existing value
     */
    public static function forceSet($key, $value, string $group = 'general')
    {
        return self::setValue($key, $value, $group);
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        // Clear individual setting caches
        $settings = self::query()->get();
        foreach ($settings as $setting) {
            Cache::forget("settings.{$setting->_key}");
        }
        
        // Clear the general settings cache
        Cache::forget('settings');
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($setting) {
            Cache::forget("settings.{$setting->_key}");
        });

        static::deleted(function ($setting) {
            Cache::forget("settings.{$setting->_key}");
        });
    }

    public static function getCached()
    {

        if (! Cache::has('settings')) {
            return Cache::remember('settings', 60 * 60, function () {
                return self::query()->get()->keyBy('_key');
            });
        }

        return Cache::get('settings');
    }

    /**
     * Get the full URL for a file setting (logo, favicon, etc.)
     */
    public static function getFileUrl($key, $default = null)
    {
        $value = self::getValue($key);

        if (! $value) {
            // If no setting value, return default or formatted app name
            $appName = config('app.name', 'Open Publicaties');
            $appUrl = config('app.url');
            $domain = preg_replace('/^https?:\/\//', '', $appUrl);
            $nameParts = explode('.', $domain);
            $mainName = $nameParts[0] ?? $appName;

            return '<span class="font-bold uppercase tracking-wider">'.ucfirst($mainName).'</span>';
        }

        // If it's already a full URL, return as is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Check if file exists in storage
        if (! Storage::disk('public')->exists($value)) {
            // If file does not exist, return formatted app name
            $appName = config('app.name', 'Opub.nl');
            $appUrl = config('app.url');
            $domain = preg_replace('/^https?:\/\//', '', $appUrl);
            $nameParts = explode('.', $domain);
            $mainName = $nameParts[0] ?? $appName;

            return '<span class="font-bold uppercase tracking-wider">'.ucfirst($mainName).'</span>';
        }

        // Return the storage URL
        return Storage::disk('public')->url($value);
    }

    /**
     * Get logo URL
     */
    public static function getLogoUrl($default = null)
    {
        return self::getFileUrl('site_logo', $default);
    }

    /**
     * Get favicon URL
     */
    public static function getFaviconUrl($default = null)
    {
        return self::getFileUrl('site_favicon', $default);
    }

    /**
     * Check if a file setting exists and the file is accessible
     */
    public static function hasFile($key)
    {
        $value = self::getValue($key);

        if (! $value) {
            return false;
        }

        return Storage::disk('public')->exists($value);
    }
}
