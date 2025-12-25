<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\BentoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BentoItemController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.content.homepage.bento-item.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.content.homepage.bento-item.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'url' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'col_span' => 'required|integer|in:2,4',
            'is_coming_soon' => 'boolean',
            'coming_soon_text' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle checkboxes
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_coming_soon'] = $request->boolean('is_coming_soon');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('bento-items', 'public');
        }

        BentoItem::create($validated);

        return redirect()
            ->route('admin.content.homepage.bento-item.index')
            ->with('success', 'Bento Item created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BentoItem $bentoItem): View
    {
        return view('admin.content.homepage.bento-item.edit', compact('bentoItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BentoItem $bentoItem)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'url' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'col_span' => 'required|integer|in:2,4',
            'is_coming_soon' => 'boolean',
            'coming_soon_text' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle checkboxes
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_coming_soon'] = $request->boolean('is_coming_soon');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it's a stored file
            if ($bentoItem->image && !str_starts_with($bentoItem->image, 'http')) {
                Storage::disk('public')->delete($bentoItem->image);
            }
            $validated['image'] = $request->file('image')->store('bento-items', 'public');
        }

        // Handle image removal
        if ($request->boolean('remove_image') && $bentoItem->image) {
            if (!str_starts_with($bentoItem->image, 'http')) {
                Storage::disk('public')->delete($bentoItem->image);
            }
            $validated['image'] = null;
        }

        $bentoItem->update($validated);

        return redirect()
            ->route('admin.content.homepage.bento-item.index')
            ->with('success', 'Bento Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BentoItem $bentoItem)
    {
        // Delete image
        if ($bentoItem->image && !str_starts_with($bentoItem->image, 'http')) {
            Storage::disk('public')->delete($bentoItem->image);
        }

        $bentoItem->delete();

        return redirect()
            ->route('admin.content.homepage.bento-item.index')
            ->with('success', 'Bento Item deleted successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(BentoItem $bentoItem)
    {
        $bentoItem->update(['is_active' => !$bentoItem->is_active]);

        return redirect()
            ->back()
            ->with('success', 'Bento Item status updated.');
    }
}
