<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\AboutSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutSettingController extends AdminBaseController
{
    /**
     * Show the form for editing about settings.
     */
    public function edit(): View
    {
        $settings = AboutSetting::getInstance();

        return view('admin.content.about.edit', compact('settings'));
    }

    /**
     * Update about settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // Page Header
            'page_eyebrow' => 'nullable|string|max:255',
            'page_title' => 'required|string|max:255',
            'page_description' => 'nullable|string|max:1000',
            // Introduction
            'intro_content' => 'nullable|string',
            // Section 1
            'section1_title' => 'nullable|string|max:255',
            'section1_content' => 'nullable|string',
            'section1_is_active' => 'boolean',
            // Section 2
            'section2_title' => 'nullable|string|max:255',
            'section2_intro' => 'nullable|string',
            'section2_features' => 'nullable|array',
            'section2_features.*.title' => 'nullable|string|max:255',
            'section2_features.*.description' => 'nullable|string|max:500',
            'section2_outro' => 'nullable|string',
            'section2_is_active' => 'boolean',
            // Section 3
            'section3_title' => 'nullable|string|max:255',
            'section3_values' => 'nullable|array',
            'section3_values.*.icon' => 'nullable|string|max:100',
            'section3_values.*.title' => 'nullable|string|max:255',
            'section3_values.*.description' => 'nullable|string',
            'section3_is_active' => 'boolean',
            // Section 4
            'section4_title' => 'nullable|string|max:255',
            'section4_content' => 'nullable|string',
            'section4_is_active' => 'boolean',
            // Section 5
            'section5_title' => 'nullable|string|max:255',
            'section5_content' => 'nullable|string',
            'section5_is_active' => 'boolean',
            // Contact
            'contact_title' => 'nullable|string|max:255',
            'contact_content' => 'nullable|string',
            'contact_link_text' => 'nullable|string|max:255',
            'contact_link_url' => 'nullable|string|max:500',
            'contact_is_active' => 'boolean',
        ]);

        // Handle checkboxes
        $validated['section1_is_active'] = $request->boolean('section1_is_active');
        $validated['section2_is_active'] = $request->boolean('section2_is_active');
        $validated['section3_is_active'] = $request->boolean('section3_is_active');
        $validated['section4_is_active'] = $request->boolean('section4_is_active');
        $validated['section5_is_active'] = $request->boolean('section5_is_active');
        $validated['contact_is_active'] = $request->boolean('contact_is_active');

        // Filter empty array items
        if (isset($validated['section2_features'])) {
            $validated['section2_features'] = array_values(array_filter($validated['section2_features'], fn($f) => !empty($f['title'])));
        }
        if (isset($validated['section3_values'])) {
            $validated['section3_values'] = array_values(array_filter($validated['section3_values'], fn($v) => !empty($v['title'])));
        }

        $settings = AboutSetting::first() ?? new AboutSetting();
        $settings->fill($validated);
        $settings->save();

        return redirect()
            ->route('admin.content.about.edit')
            ->with('success', 'About page settings updated successfully.');
    }
}
