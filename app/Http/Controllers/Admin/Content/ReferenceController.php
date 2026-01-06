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
        try {
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Reference creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create reference. Please try again.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reference $reference)
    {
        try {
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Reference update failed', [
                'reference_id' => $reference->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update reference. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reference $reference)
    {
        try {
            $reference->delete();

            return redirect()
                ->route('admin.content.reference.index')
                ->with('success', 'Reference deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Reference deletion failed', [
                'reference_id' => $reference->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to delete reference. Please try again.');
        }
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
