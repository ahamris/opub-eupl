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
        return view('admin.content.blog-category.index');
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
