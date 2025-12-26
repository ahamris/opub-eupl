<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class CookieSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Banner settings
        Setting::forceSet('cookie_banner_enabled', '1', 'cookie');
        
        // Text settings
        Setting::forceSet('cookie_intro_title', 'We use cookies', 'cookie');
        Setting::forceSet('cookie_intro_summary', 'In addition to functional cookies we also place analytics and marketing cookies to understand usage, show relevant content and offer support. Only essential cookies are enabled by default.', 'cookie');
        Setting::forceSet('cookie_preferences_title', 'Manage cookie preferences', 'cookie');
        Setting::forceSet('cookie_preferences_summary', 'Configure your cookie preferences below. Need more information? Read our policy.', 'cookie');
        Setting::forceSet('cookie_settings_label', 'Cookie policy', 'cookie');
        
        // URL settings
        Setting::forceSet('cookie_settings_page_type', 'custom', 'cookie');
        Setting::forceSet('cookie_settings_url', 'javascript:void(0)', 'cookie');
        
        // Category labels and descriptions
        Setting::forceSet('cookie_category_functional_label', 'Functional cookies', 'cookie');
        Setting::forceSet('cookie_category_functional_description', 'Required for core functionality of the website.', 'cookie');
        
        Setting::forceSet('cookie_category_analytics_label', 'Analytics cookies', 'cookie');
        Setting::forceSet('cookie_category_analytics_description', 'Help us measure usage and improve the experience.', 'cookie');
        
        Setting::forceSet('cookie_category_marketing_label', 'Marketing cookies', 'cookie');
        Setting::forceSet('cookie_category_marketing_description', 'Enable personalised content and external integrations.', 'cookie');
        
        // Clear cache
        Setting::clearCache();
    }
}
