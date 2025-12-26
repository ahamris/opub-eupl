<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Setting;
use App\Models\StaticPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CookieSettingController extends AdminBaseController
{
    /**
     * Show the form for editing cookie settings.
     */
    public function index(): View
    {
        $staticPages = StaticPage::active()->ordered()->get();
        
        return view('admin.content.cookie-settings.index', compact('staticPages'));
    }

    /**
     * Update cookie settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Banner settings
            'cookie_banner_enabled' => 'boolean',
            
            // Text settings
            'cookie_intro_title' => 'nullable|string|max:255',
            'cookie_intro_summary' => 'nullable|string|max:1000',
            'cookie_preferences_title' => 'nullable|string|max:255',
            'cookie_preferences_summary' => 'nullable|string|max:1000',
            'cookie_settings_label' => 'nullable|string|max:255',
            
            // URL settings
            'cookie_settings_page_type' => 'nullable|string|in:custom,static',
            'cookie_settings_page_id' => 'nullable|integer|exists:static_pages,id',
            'cookie_settings_url' => 'nullable|string|max:500',
            
            // Category labels and descriptions
            'cookie_category_functional_label' => 'nullable|string|max:255',
            'cookie_category_functional_description' => 'nullable|string|max:500',
            'cookie_category_analytics_label' => 'nullable|string|max:255',
            'cookie_category_analytics_description' => 'nullable|string|max:500',
            'cookie_category_marketing_label' => 'nullable|string|max:255',
            'cookie_category_marketing_description' => 'nullable|string|max:500',
        ]);

        // Handle checkbox
        $validated['cookie_banner_enabled'] = $request->boolean('cookie_banner_enabled') ? '1' : '0';

        // Save all settings
        foreach ($validated as $key => $value) {
            Setting::forceSet($key, $value ?? '', 'cookie');
        }

        // Clear settings cache
        Setting::clearCache();

        return redirect()
            ->route('admin.content.cookie-settings.index')
            ->with('success', 'Cookie settings updated successfully.');
    }
}
