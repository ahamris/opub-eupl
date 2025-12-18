<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Requests\BlogCategoryRequest;
use App\Models\BlogCategory;
use Illuminate\View\View;
use App\Http\Controllers\Admin\AdminBaseController;

class BlogCategoryController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $blogCategories = BlogCategory::query()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.content.blog-category.index', compact('blogCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.content.blog-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCategoryRequest $request)
    {
        $validated = $request->validated();

        $blogCategory = BlogCategory::create($validated);

        return redirect()->route('admin.content.blog-category.index')
            ->with('success', 'Blog category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogCategory $blogCategory): View
    {
        return view('admin.content.blog-category.show', compact('blogCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogCategory $blogCategory): View
    {
        return view('admin.content.blog-category.edit', compact('blogCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogCategoryRequest $request, BlogCategory $blogCategory)
    {
        $validated = $request->validated();

        $blogCategory->update($validated);

        return redirect()->route('admin.content.blog-category.index')
            ->with('success', 'Blog category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        return redirect()->route('admin.content.blog-category.index')
            ->with('success', 'Blog category deleted successfully!');
    }
}
