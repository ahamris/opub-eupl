<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\HomepageSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomepageSettingController extends AdminBaseController
{
    /**
     * Show the form for editing homepage settings.
     */
    public function edit(): View
    {
        $settings = HomepageSetting::getInstance();

        return view('admin.content.homepage.settings.edit', compact('settings'));
    }

    /**
     * Update homepage settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // Hero Section
            'hero_badge' => 'nullable|string|max:255',
            'hero_badge_text' => 'nullable|string|max:255',
            'hero_badge_url' => 'nullable|string|max:500',
            'hero_title' => 'required|string|max:255',
            'hero_description' => 'nullable|string|max:1000',
            'hero_is_active' => 'boolean',
            // Newsletter Section
            'newsletter_eyebrow' => 'nullable|string|max:255',
            'newsletter_title' => 'nullable|string|max:255',
            'newsletter_description' => 'nullable|string|max:1000',
            'newsletter_button_text' => 'nullable|string|max:100',
            'newsletter_feature_1_title' => 'nullable|string|max:255',
            'newsletter_feature_1_description' => 'nullable|string|max:500',
            'newsletter_feature_2_title' => 'nullable|string|max:255',
            'newsletter_feature_2_description' => 'nullable|string|max:500',
            'newsletter_is_active' => 'boolean',
            // Bento Section
            'bento_eyebrow' => 'nullable|string|max:255',
            'bento_title' => 'nullable|string|max:255',
            'bento_description' => 'nullable|string|max:500',
            'bento_is_active' => 'boolean',
            // Kennisbank Section
            'kennisbank_eyebrow' => 'nullable|string|max:255',
            'kennisbank_title' => 'nullable|string|max:255',
            'kennisbank_description' => 'nullable|string|max:500',
            'kennisbank_is_active' => 'boolean',
            // Testimonials Section
            'testimonials_eyebrow' => 'nullable|string|max:255',
            'testimonials_title' => 'nullable|string|max:255',
            'testimonials_description' => 'nullable|string|max:500',
            'testimonials_is_active' => 'boolean',
        ]);

        // Handle checkboxes
        $validated['hero_is_active'] = $request->boolean('hero_is_active');
        $validated['newsletter_is_active'] = $request->boolean('newsletter_is_active');
        $validated['bento_is_active'] = $request->boolean('bento_is_active');
        $validated['kennisbank_is_active'] = $request->boolean('kennisbank_is_active');
        $validated['testimonials_is_active'] = $request->boolean('testimonials_is_active');

        $settings = HomepageSetting::first() ?? new HomepageSetting();
        $settings->fill($validated);
        $settings->save();

        return redirect()
            ->route('admin.content.homepage.settings.edit')
            ->with('success', 'Homepage settings updated successfully.');
    }
}
