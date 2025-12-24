<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StaticPageController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.content.static-page.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.content.static-page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:static_pages,slug',
            'subtitle' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            // SEO Fields
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'meta_robots' => 'nullable|string|max:100',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'canonical_url' => 'nullable|url|max:500',
            // Button 1
            'button_1_text' => 'nullable|string|max:100',
            'button_1_url' => 'nullable|string|max:500',
            'button_1_style' => 'nullable|string|in:primary,secondary,outline',
            'button_1_icon' => 'nullable|string|max:50',
            'button_1_new_tab' => 'boolean',
            // Button 2
            'button_2_text' => 'nullable|string|max:100',
            'button_2_url' => 'nullable|string|max:500',
            'button_2_style' => 'nullable|string|in:primary,secondary,outline',
            'button_2_icon' => 'nullable|string|max:50',
            'button_2_new_tab' => 'boolean',
            // Settings
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Handle checkboxes when unchecked
        $validated['is_active'] = $request->has('is_active');
        $validated['button_1_new_tab'] = $request->has('button_1_new_tab');
        $validated['button_2_new_tab'] = $request->has('button_2_new_tab');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['meta_robots'] = $validated['meta_robots'] ?? 'index, follow';
        $validated['button_1_style'] = $validated['button_1_style'] ?? 'primary';
        $validated['button_2_style'] = $validated['button_2_style'] ?? 'secondary';

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')->store('static-pages/og', 'public');
        }

        StaticPage::create($validated);

        return redirect()->route('admin.content.static-page.index')
            ->with('success', 'Static page created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(StaticPage $staticPage): View
    {
        return view('admin.content.static-page.show', compact('staticPage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StaticPage $staticPage): View
    {
        return view('admin.content.static-page.edit', compact('staticPage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StaticPage $staticPage)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:static_pages,slug,' . $staticPage->id,
            'subtitle' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            // SEO Fields
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'meta_robots' => 'nullable|string|max:100',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'canonical_url' => 'nullable|url|max:500',
            // Button 1
            'button_1_text' => 'nullable|string|max:100',
            'button_1_url' => 'nullable|string|max:500',
            'button_1_style' => 'nullable|string|in:primary,secondary,outline',
            'button_1_icon' => 'nullable|string|max:50',
            'button_1_new_tab' => 'boolean',
            // Button 2
            'button_2_text' => 'nullable|string|max:100',
            'button_2_url' => 'nullable|string|max:500',
            'button_2_style' => 'nullable|string|in:primary,secondary,outline',
            'button_2_icon' => 'nullable|string|max:50',
            'button_2_new_tab' => 'boolean',
            // Settings
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Handle checkboxes when unchecked
        $validated['is_active'] = $request->has('is_active');
        $validated['button_1_new_tab'] = $request->has('button_1_new_tab');
        $validated['button_2_new_tab'] = $request->has('button_2_new_tab');

        // Handle OG image deletion
        if ($request->has('remove_og_image') && $request->input('remove_og_image') == '1') {
            if ($staticPage->og_image) {
                Storage::disk('public')->delete($staticPage->og_image);
            }
            $validated['og_image'] = null;
        }
        // Handle OG image upload
        elseif ($request->hasFile('og_image')) {
            if ($staticPage->og_image) {
                Storage::disk('public')->delete($staticPage->og_image);
            }
            $validated['og_image'] = $request->file('og_image')->store('static-pages/og', 'public');
        }

        $staticPage->update($validated);

        return redirect()->route('admin.content.static-page.index')
            ->with('success', 'Static page updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StaticPage $staticPage)
    {
        // Delete OG image if exists
        if ($staticPage->og_image) {
            Storage::disk('public')->delete($staticPage->og_image);
        }

        $staticPage->delete();

        return redirect()->route('admin.content.static-page.index')
            ->with('success', 'Static page deleted successfully!');
    }

    /**
     * Toggle page active status
     */
    public function toggleActive(StaticPage $staticPage)
    {
        $staticPage->update(['is_active' => !$staticPage->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $staticPage->is_active,
            'message' => $staticPage->is_active ? 'Page activated successfully!' : 'Page deactivated successfully!',
        ]);
    }
}
