<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

if (!function_exists('get_setting')) {

    /**
     * Get a setting value by key from the cached Setting model
     *
     * @param string $key The setting key (property name)
     * @param mixed|null $default Default value if setting doesn't exist
     * @return mixed The setting value or default
     */
    function get_setting(string $key, mixed $default = null): mixed
    {
        // Access property directly (e.g., $setting->name, $setting->email)
        return Setting::getValue($key) ?? $default;
    }
}


if (!function_exists('get_image')) {
    function get_image(?string $url = null, ?string $default = null): string
    {
        if (empty($url)) {
            return $default ?? asset('assets/images/blog.png');
        }
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        $cleanPath = ltrim($url, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }
        if (Storage::disk('public')->exists($cleanPath)) {
            return Storage::disk('public')->url($cleanPath);
        }

        $publicPath = public_path($url);
        if (file_exists($publicPath)) {
            return asset($url);
        }

        return $default ?? asset('assets/images/blog.png');
    }
}

if (!function_exists('localized_route')) {
    function localized_route(string $name, array $parameters = [], ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale() ?: 'tr';
        $parameters['locale'] = $locale;
        return route($name, $parameters);
    }
}

if (!function_exists('route_frontend')) {
    /**
     * Generate a frontend route with automatic locale injection
     */
    function route_frontend(string $name, array $parameters = [], ?string $locale = null): string
    {
        $locale = $locale ?? request()->route('locale') ?? app()->getLocale() ?: 'tr';
        $parameters['locale'] = $locale;
        return route($name, $parameters);
    }
}

if (!function_exists('current_locale')) {
    function current_locale(): string
    {
        return app()->getLocale() ?: 'tr';
    }
}

if (!function_exists('available_locales')) {
    function available_locales(): array
    {
        return [
            'nl' => __('Dutch'),
            'en' => __('English'),
        ];
    }
}

if (!function_exists('is_active_route')) {

    function is_active_route(string $routeName): bool
    {
        $currentRoute = request()->route();
        if (!$currentRoute) {
            return false;
        }

        $currentRouteName = $currentRoute->getName();
        if (!$currentRouteName) {
            return false;
        }

        // Use Laravel's built-in route matching with wildcard
        return request()->routeIs($routeName . '*');
    }
}

if (!function_exists('route_index')) {

    function route_index(string $routeName, array $parameters = []): string
    {
        return route($routeName . '.index', $parameters);
    }
}

if (!function_exists('format_date_for_display')) {
    /**
     * Format a date for display in dd/mm/yyyy format
     */
    function format_date_for_display($date): string
    {
        if (!$date) {
            return '';
        }

        try {
            if ($date instanceof Carbon) {
                return $date->format('d/m/Y');
            }

            return Carbon::parse($date)->format('d/m/Y');
        } catch (Exception $e) {
            return '';
        }
    }
}

if (!function_exists('format_date_for_backend')) {
    /**
     * Format a date from dd/mm/yyyy to Y-m-d for backend storage
     */
    function format_date_for_backend($date): ?string
    {
        if (!$date) {
            return null;
        }

        try {
            // If it's already in Y-m-d format
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                return $date;
            }

            // If it's in dd/mm/yyyy format
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
                return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
            }

            // Try to parse other formats
            return Carbon::parse($date)->format('Y-m-d');
        } catch (Exception $e) {
            return null;
        }
    }
}

if (!function_exists('format_localized_date')) {
    /**
     * Format a date in the current application locale (defaults to Turkish)
     *
     * @param mixed $date Carbon instance, date string, or null
     * @param string $format Format string (default: 'd M Y')
     * @param string|null $locale Override locale (default: Turkish 'tr')
     * @return string Formatted date in the specified locale
     */
    function format_localized_date($date, string $format = 'd M Y', ?string $locale = null): string
    {
        if (!$date) {
            return '';
        }

        try {
            $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);

            // Use provided locale or fall back to Turkish as default
            $locale = $locale ?? app()->getLocale() ?: 'tr';
            $carbon->locale($locale);

            // Use translatedFormat for localized month/day names
            return $carbon->translatedFormat($format);
        } catch (Exception $e) {
            return '';
        }
    }
}

if (!function_exists('format_localized_datetime')) {
    /**
     * Format a datetime in the current application locale (defaults to Turkish)
     *
     * @param mixed $date Carbon instance, date string, or null
     * @param string|null $locale Override locale (default: Turkish 'tr')
     * @return string Formatted datetime in the specified locale
     */
    function format_localized_datetime($date, ?string $locale = null): string
    {
        return format_localized_date($date, 'd F Y H:i', $locale);
    }
}

if (!function_exists('format_localized_date_long')) {
    /**
     * Format a date with full month name in the current application locale (defaults to Turkish)
     *
     * @param mixed $date Carbon instance, date string, or null
     * @param string|null $locale Override locale (default: Turkish 'tr')
     * @return string Formatted date with full month name in the specified locale
     */
    function format_localized_date_long($date, ?string $locale = null): string
    {
        return format_localized_date($date, 'd F Y', $locale);
    }
}


if (!function_exists('format_turkish_datetime')) {
    /**
     * Format a datetime in Turkish locale
     *
     * @param mixed $date Carbon instance, date string, or null
     * @return string Formatted datetime in Turkish
     */
    function format_turkish_datetime($date): string
    {
        return format_localized_datetime($date, 'tr');
    }
}

if (!function_exists('format_turkish_date_long')) {
    /**
     * Format a date with full month name in Turkish locale
     *
     * @param mixed $date Carbon instance, date string, or null
     * @return string Formatted date with full month name in Turkish
     */
    function format_turkish_date_long($date): string
    {
        return format_localized_date_long($date, 'tr');
    }
}
