<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Admin\AdminBaseController;

class BlogController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $blogs = Blog::with(['blog_category', 'author'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.content.blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $blogCategories = BlogCategory::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $authors = User::query()->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return view('admin.content.blog.create', compact(
            'blogCategories', 
            'authors', 
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        // Sluggable package will handle slug generation/sanitization via setSlugAttribute mutator
        $blog = Blog::create($validated);

        return redirect()->route('admin.content.blog.index')
            ->with('success', 'Blog created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog): View
    {
        $blog->load(['blog_category', 'author']);

        return view('admin.content.blog.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog): View
    {
        $blog->load(['blog_category', 'author']);

        $blogCategories = BlogCategory::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $authors = User::select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return view('admin.content.blog.edit', compact(
            'blog', 
            'blogCategories', 
            'authors', 
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, Blog $blog)
    {
        $validated = $request->validated();

        // Handle image deletion
        if ($request->has('remove_image') && $request->input('remove_image') == '1') {
            // Delete old image from storage if exists
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            // Set image to null in database
            $validated['image'] = null;
        }
        // Handle image upload
        elseif ($request->hasFile('image')) {
            // Delete old image if exists
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        $blog->update($validated);

        return redirect()->route('admin.content.blog.index')
            ->with('success', 'Blog updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        // Delete image if exists
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return redirect()->route('admin.content.blog.index')
            ->with('success', 'Blog deleted successfully!');
    }

    /**
     * Toggle blog active status
     */
    public function toggleActive(Blog $blog)
    {
        $oldStatus = $blog->is_active ? 'active' : 'inactive';
        $blog->update(['is_active' => ! $blog->is_active]);
        $newStatus = $blog->is_active ? 'active' : 'inactive';

        return response()->json([
            'success' => true,
            'is_active' => $blog->is_active,
            'message' => $blog->is_active ? 'Blog activated successfully!' : 'Blog deactivated successfully!',
        ]);
    }

    /**
     * Toggle blog featured status
     */
    public function toggleFeatured(Blog $blog)
    {
        $oldStatus = $blog->is_featured ? 'featured' : 'not featured';
        $blog->update(['is_featured' => ! $blog->is_featured]);
        $newStatus = $blog->is_featured ? 'featured' : 'not featured';

        return response()->json([
            'success' => true,
            'is_featured' => $blog->is_featured,
            'message' => $blog->is_featured ? 'Blog featured successfully!' : 'Blog unfeatured successfully!',
        ]);
    }

}
