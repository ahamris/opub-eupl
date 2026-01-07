<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GeneralSettingController extends AdminBaseController
{
    /**
     * Show the form for editing general settings.
     */
    public function index(): View
    {
        return view('admin.content.general-settings.index');
    }

    /**
     * Update general settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Site Information
            'site_title' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_url' => 'nullable|url|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:500',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg,gif,svg|max:1024',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            
            // SMTP Settings
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|string|in:tls,ssl',
            'smtp_from_address' => 'nullable|email|max:255',
            'smtp_from_name' => 'nullable|string|max:255',
            
            // Social Media
            'facebook_url' => 'nullable|url|max:500',
            'twitter_url' => 'nullable|url|max:500',
            'linkedin_url' => 'nullable|url|max:500',
            'instagram_url' => 'nullable|url|max:500',
            'youtube_url' => 'nullable|url|max:500',
            'github_url' => 'nullable|url|max:500',
            
            // Other Settings
            'maintenance_mode' => 'boolean',
            'maintenance_message' => 'nullable|string|max:1000',
            'timezone' => 'nullable|string|max:100',
            'locale' => 'nullable|string|max:10',
        ]);

        // Handle file uploads
        if ($request->hasFile('site_logo')) {
            $logoPath = $this->handleFileUpload($request->file('site_logo'), 'images');
            if ($logoPath) {
                // Delete old logo if exists
                $oldLogo = Setting::getValue('site_logo');
                if ($oldLogo && !filter_var($oldLogo, FILTER_VALIDATE_URL)) {
                    $this->deleteImage($oldLogo);
                }
                Setting::forceSet('site_logo', $logoPath, 'general');
            }
        }

        if ($request->hasFile('site_favicon')) {
            $faviconPath = $this->handleFileUpload($request->file('site_favicon'), 'images');
            if ($faviconPath) {
                // Delete old favicon if exists
                $oldFavicon = Setting::getValue('site_favicon');
                if ($oldFavicon && !filter_var($oldFavicon, FILTER_VALIDATE_URL)) {
                    $this->deleteImage($oldFavicon);
                }
                Setting::forceSet('site_favicon', $faviconPath, 'general');
            }
        }

        if ($request->hasFile('og_image')) {
            $ogImagePath = $this->handleFileUpload($request->file('og_image'), 'images');
            if ($ogImagePath) {
                // Delete old OG image if exists
                $oldOgImage = Setting::getValue('og_image');
                if ($oldOgImage && !filter_var($oldOgImage, FILTER_VALIDATE_URL)) {
                    $this->deleteImage($oldOgImage);
                }
                Setting::forceSet('og_image', $ogImagePath, 'general');
            }
        }

        // Handle checkbox
        $validated['maintenance_mode'] = $request->boolean('maintenance_mode') ? '1' : '0';

        // Save all settings (except files which are handled above)
        $settingsToSave = [
            // Site Information
            'site_title', 'site_description', 'site_url', 'meta_keywords', 'meta_description',
            // SMTP
            'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption', 
            'smtp_from_address', 'smtp_from_name',
            // Social Media
            'facebook_url', 'twitter_url', 'linkedin_url', 'instagram_url', 'youtube_url', 'github_url',
            // Open Graph
            'og_title', 'og_description',
            // Other
            'maintenance_mode', 'maintenance_message', 'timezone', 'locale',
        ];

        foreach ($settingsToSave as $key) {
            if (array_key_exists($key, $validated)) {
                $value = $validated[$key];
                // For SMTP password, only update if provided (don't overwrite with empty)
                if ($key === 'smtp_password' && empty($value)) {
                    continue;
                }
                Setting::forceSet($key, $value ?? '', 'general');
            }
        }

        // Clear settings cache
        Setting::clearCache();

        return redirect()
            ->route('admin.content.general-settings.index')
            ->with('success', 'General settings updated successfully.');
    }
}
