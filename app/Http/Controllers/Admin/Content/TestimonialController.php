<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TestimonialController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.content.homepage.testimonial.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.content.homepage.testimonial.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'quote' => 'required|string|max:1000',
            'author' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle checkbox
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Handle image upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        Testimonial::create($validated);

        return redirect()
            ->route('admin.content.homepage.testimonial.index')
            ->with('success', 'Testimonial created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial): View
    {
        return view('admin.content.homepage.testimonial.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'quote' => 'required|string|max:1000',
            'author' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle checkbox
        $validated['is_active'] = $request->boolean('is_active');

        // Handle image upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it's a stored file
            if ($testimonial->avatar && !str_starts_with($testimonial->avatar, 'http')) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        // Handle avatar removal
        if ($request->boolean('remove_avatar') && $testimonial->avatar) {
            if (!str_starts_with($testimonial->avatar, 'http')) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $validated['avatar'] = null;
        }

        $testimonial->update($validated);

        return redirect()
            ->route('admin.content.homepage.testimonial.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        // Delete avatar
        if ($testimonial->avatar && !str_starts_with($testimonial->avatar, 'http')) {
            Storage::disk('public')->delete($testimonial->avatar);
        }

        $testimonial->delete();

        return redirect()
            ->route('admin.content.homepage.testimonial.index')
            ->with('success', 'Testimonial deleted successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(Testimonial $testimonial)
    {
        $testimonial->update(['is_active' => !$testimonial->is_active]);

        return redirect()
            ->back()
            ->with('success', 'Testimonial status updated.');
    }
}
