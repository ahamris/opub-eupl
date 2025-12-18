<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request): View
    {
        $query = Blog::with(['blog_category', 'author'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc');

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $category = BlogCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('blog_category_id', $category->id);
            }
        }

        // Filter by featured if provided
        if ($request->has('featured') && $request->featured === '1') {
            $query->where('is_featured', true);
        }

        $blogs = $query->paginate(12);
        $categories = BlogCategory::withCount(['blogs' => function ($query) {
            $query->where('is_active', true);
        }])->orderBy('name')->get();

        $featuredBlogs = Blog::with(['blog_category', 'author'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(3)
            ->get();

        $breadcrumbs = [
            ['label' => 'Home', 'href' => route('home')],
            ['label' => 'Blog', 'href' => null, 'current' => true],
        ];

        return view('blog.index', compact(
            'blogs',
            'categories',
            'featuredBlogs',
            'breadcrumbs'
        ));
    }

    /**
     * Display the specified blog post.
     */
    public function show(string $slug): View
    {
        $blog = Blog::with(['blog_category', 'author'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get related blogs from same category
        $relatedBlogs = Blog::with(['blog_category', 'author'])
            ->where('is_active', true)
            ->where('id', '!=', $blog->id)
            ->when($blog->blog_category_id, function ($query) use ($blog) {
                $query->where('blog_category_id', $blog->blog_category_id);
            })
            ->latest()
            ->take(3)
            ->get();

        // If not enough related blogs from category, fill with recent ones
        if ($relatedBlogs->count() < 3) {
            $existingIds = $relatedBlogs->pluck('id')->push($blog->id);
            $additionalBlogs = Blog::with(['blog_category', 'author'])
                ->where('is_active', true)
                ->whereNotIn('id', $existingIds)
                ->latest()
                ->take(3 - $relatedBlogs->count())
                ->get();
            $relatedBlogs = $relatedBlogs->merge($additionalBlogs);
        }

        $breadcrumbs = [
            ['label' => 'Home', 'href' => route('home')],
            ['label' => 'Blog', 'href' => route('blog.index')],
            ['label' => $blog->title, 'href' => null, 'current' => true],
        ];

        return view('blog.show', compact(
            'blog',
            'relatedBlogs',
            'breadcrumbs'
        ));
    }
}
