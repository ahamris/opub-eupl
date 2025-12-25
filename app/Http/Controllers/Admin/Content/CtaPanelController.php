<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\CtaPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CtaPanelController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.content.homepage.cta-panel.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.content.homepage.cta-panel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'nullable|string|max:255|unique:cta_panels,slug',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'primary_button_text' => 'nullable|string|max:100',
            'primary_button_url' => 'nullable|string|max:500',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_url' => 'nullable|string|max:500',
            'screenshot' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'screenshot_alt' => 'nullable|string|max:255',
            'variant' => 'required|in:purple,primary',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle checkbox
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Handle image upload
        if ($request->hasFile('screenshot')) {
            $validated['screenshot'] = $request->file('screenshot')->store('cta-panels', 'public');
        }

        CtaPanel::create($validated);

        return redirect()
            ->route('admin.content.homepage.cta-panel.index')
            ->with('success', 'CTA Panel created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CtaPanel $ctaPanel): View
    {
        return view('admin.content.homepage.cta-panel.edit', compact('ctaPanel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CtaPanel $ctaPanel)
    {
        $validated = $request->validate([
            'slug' => 'nullable|string|max:255|unique:cta_panels,slug,' . $ctaPanel->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'primary_button_text' => 'nullable|string|max:100',
            'primary_button_url' => 'nullable|string|max:500',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_url' => 'nullable|string|max:500',
            'screenshot' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'screenshot_alt' => 'nullable|string|max:255',
            'variant' => 'required|in:purple,primary',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle checkbox
        $validated['is_active'] = $request->boolean('is_active');

        // Handle image upload
        if ($request->hasFile('screenshot')) {
            // Delete old image
            if ($ctaPanel->screenshot) {
                Storage::disk('public')->delete($ctaPanel->screenshot);
            }
            $validated['screenshot'] = $request->file('screenshot')->store('cta-panels', 'public');
        }

        // Handle image removal
        if ($request->boolean('remove_screenshot') && $ctaPanel->screenshot) {
            Storage::disk('public')->delete($ctaPanel->screenshot);
            $validated['screenshot'] = null;
        }

        $ctaPanel->update($validated);

        return redirect()
            ->route('admin.content.homepage.cta-panel.index')
            ->with('success', 'CTA Panel updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CtaPanel $ctaPanel)
    {
        // Delete image
        if ($ctaPanel->screenshot) {
            Storage::disk('public')->delete($ctaPanel->screenshot);
        }

        $ctaPanel->delete();

        return redirect()
            ->route('admin.content.homepage.cta-panel.index')
            ->with('success', 'CTA Panel deleted successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(CtaPanel $ctaPanel)
    {
        $ctaPanel->update(['is_active' => !$ctaPanel->is_active]);

        return redirect()
            ->back()
            ->with('success', 'CTA Panel status updated.');
    }
}
