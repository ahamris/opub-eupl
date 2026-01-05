<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Reference;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReferenceController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.content.references.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'icon' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'link_url' => 'nullable|string|max:500',
            'link_text' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Reference::create($validated);

        return redirect()
            ->route('admin.content.reference.index')
            ->with('success', 'Reference created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reference $reference)
    {
        $validated = $request->validate([
            'icon' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'link_url' => 'nullable|string|max:500',
            'link_text' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $reference->update($validated);

        return redirect()
            ->route('admin.content.reference.index')
            ->with('success', 'Reference updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reference $reference)
    {
        $reference->delete();

        return redirect()
            ->route('admin.content.reference.index')
            ->with('success', 'Reference deleted successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(Reference $reference)
    {
        $reference->update(['is_active' => !$reference->is_active]);

        return redirect()
            ->back()
            ->with('success', 'Reference status updated.');
    }
}
