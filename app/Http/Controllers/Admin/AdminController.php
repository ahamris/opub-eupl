<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class AdminController extends AdminBaseController
{
    public function home()
    {
        // User statistics
        $totalUsers = \App\Models\User::count();
        $activeUsers = \App\Models\User::where('is_active', true)->count();
        $usersThisMonth = \App\Models\User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Blog statistics
        $totalBlogs = \App\Models\Blog::count();
        $activeBlogs = \App\Models\Blog::where('is_active', true)->count();
        $featuredBlogs = \App\Models\Blog::where('is_featured', true)->count();

        // Contact submission statistics
        $totalMessages = \App\Models\ContactSubmission::count();
        $unreadMessages = \App\Models\ContactSubmission::where('is_read', false)->count();
        $activeMessages = \App\Models\ContactSubmission::where('is_archived', false)->count();

        // Search subscription statistics
        $totalSubscriptions = \App\Models\SearchSubscription::count();
        $activeSubscriptions = \App\Models\SearchSubscription::where('is_active', true)->count();
        $verifiedSubscriptions = \App\Models\SearchSubscription::whereNotNull('verified_at')->count();

        // Document statistics
        $totalDocuments = \App\Models\OpenOverheidDocument::count();

        // Recent unread contact submissions
        $recentUnreadMessages = \App\Models\ContactSubmission::where('is_read', false)
            ->where('is_archived', false)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.home.index', compact(
            'totalUsers',
            'activeUsers',
            'usersThisMonth',
            'totalBlogs',
            'activeBlogs',
            'featuredBlogs',
            'totalMessages',
            'unreadMessages',
            'activeMessages',
            'totalSubscriptions',
            'activeSubscriptions',
            'verifiedSubscriptions',
            'totalDocuments',
            'recentUnreadMessages'
        ));
    }

    /**
     * Clear all application cache.
     */
    public function clearCache(): JsonResponse
    {
        try {
            // Clear application cache (safer than Cache::flush())
            Artisan::call('cache:clear');

            // Clear config cache
            Artisan::call('config:clear');

            // Clear route cache
            Artisan::call('route:clear');

            // Clear view cache
            Artisan::call('view:clear');

            // Clear event cache
            Artisan::call('event:clear');

            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage(),
            ], 500);
        }
    }
}
