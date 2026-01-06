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
        try {
            $validated = $request->validated();

            $blogCategory = BlogCategory::create($validated);

            return redirect()->route('admin.content.blog-category.index')
                ->with('success', 'Blog category created successfully!');
        } catch (\Exception $e) {
            \Log::error('Blog category creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create blog category. Please try again.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogCategoryRequest $request, BlogCategory $blogCategory)
    {
        try {
            $validated = $request->validated();

            $blogCategory->update($validated);

            return redirect()->route('admin.content.blog-category.index')
                ->with('success', 'Blog category updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Blog category update failed', [
                'blog_category_id' => $blogCategory->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update blog category. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogCategory $blogCategory)
    {
        try {
            $blogCategory->delete();

            return redirect()->route('admin.content.blog-category.index')
                ->with('success', 'Blog category deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Blog category deletion failed', [
                'blog_category_id' => $blogCategory->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to delete blog category. Please try again.');
        }
    }
}
