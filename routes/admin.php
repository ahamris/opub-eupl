<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContactSubmissionController;
use App\Http\Controllers\Admin\SearchSubscriptionController;
use App\Http\Controllers\Admin\ApiClientController;
use App\Http\Controllers\Admin\Content\BlogController;
use App\Http\Controllers\Admin\Content\BlogCategoryController;
use App\Http\Controllers\Admin\Content\StaticPageController;
use App\Http\Controllers\Admin\Content\CookieSettingController;
use App\Http\Controllers\Admin\Content\GeneralSettingController;
use App\Http\Controllers\Admin\DataManagementController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Auth\AdminPasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\AdminNewPasswordController;
use App\Livewire\Admin\MenuManager;
use App\Livewire\Admin\HeaderMenuManager;
use App\Livewire\Admin\FooterMenuManager;
use App\Livewire\Admin\ThemeManager;

// Admin Auth Routes (Guest only)
Route::middleware('guest')->prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminLoginController::class, 'create'])->name('login');
    Route::post('login', [AdminLoginController::class, 'store'])->name('login.store');
    
    Route::get('forgot-password', [AdminPasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [AdminPasswordResetLinkController::class, 'store'])->name('password.email');
    
    Route::get('reset-password/{token}', [AdminNewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [AdminNewPasswordController::class, 'store'])->name('password.store');
});

// Logout Required Route (for non-admin users trying to access admin)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('logout-required', [AdminLoginController::class, 'logoutRequired'])->name('logout-required');
    Route::post('logout', [AdminLoginController::class, 'destroy'])->name('logout');
});

// Admin Protected Routes
Route::middleware(['auth', \App\Http\Middleware\CheckIfAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    
    Route::controller(AdminController::class)->group(function () {
        Route::get('/', 'home')->name('home');
        Route::get('/analytics', 'analytics')->name('analytics');
        Route::post('/clear-cache', 'clearCache')->name('clear-cache');
    });

    // Users Resource Routes
    Route::resource('users', UserController::class);

    // Public API Clients (API keys + allowed domains)
    Route::resource('api-clients', ApiClientController::class)->parameters([
        'api-clients' => 'apiClient',
    ]);
    Route::post('api-clients/{apiClient}/regenerate', [ApiClientController::class, 'regenerate'])
        ->name('api-clients.regenerate');

    // Contact Submissions Routes
    Route::resource('contact-submissions', ContactSubmissionController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::post('contact-submissions/{contactSubmission}/toggle-read', [ContactSubmissionController::class, 'toggleRead'])->name('contact-submissions.toggle-read');
    Route::post('contact-submissions/{contactSubmission}/toggle-archive', [ContactSubmissionController::class, 'toggleArchive'])->name('contact-submissions.toggle-archive');

    // Search Subscriptions Routes
    Route::resource('search-subscriptions', SearchSubscriptionController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::post('search-subscriptions/{searchSubscription}/toggle-active', [SearchSubscriptionController::class, 'toggleActive'])->name('search-subscriptions.toggle-active');

    // Content Routes
    Route::prefix('content')->name('content.')->group(function () {
        // Blog Category Routes
        Route::resource('blog-category', BlogCategoryController::class)->except(['show', 'create', 'edit'])->parameters([
            'blog-category' => 'blogCategory',
        ]);

        // Blog Routes
        Route::resource('blog', BlogController::class);
        Route::post('blog/{blog}/toggle-active', [BlogController::class, 'toggleActive'])->name('blog.toggle-active');
        Route::post('blog/{blog}/toggle-featured', [BlogController::class, 'toggleFeatured'])->name('blog.toggle-featured');

        // Static Page Routes
        Route::resource('static-page', StaticPageController::class)->parameters([
            'static-page' => 'staticPage',
        ]);
        Route::post('static-page/{staticPage}/toggle-active', [StaticPageController::class, 'toggleActive'])->name('static-page.toggle-active');

        // Reference Routes
        Route::resource('reference', \App\Http\Controllers\Admin\Content\ReferenceController::class)->except(['show', 'create', 'edit']);
        Route::post('reference/{reference}/toggle-active', [\App\Http\Controllers\Admin\Content\ReferenceController::class, 'toggleActive'])->name('reference.toggle-active');

        // About Page Settings (singleton)
        Route::get('about', [\App\Http\Controllers\Admin\Content\AboutSettingController::class, 'edit'])->name('about.edit');
        Route::put('about', [\App\Http\Controllers\Admin\Content\AboutSettingController::class, 'update'])->name('about.update');

        // Homepage Management Routes
        Route::prefix('homepage')->name('homepage.')->group(function () {
            // Homepage Settings (singleton)
            Route::get('settings', [\App\Http\Controllers\Admin\Content\HomepageSettingController::class, 'edit'])->name('settings.edit');
            Route::put('settings', [\App\Http\Controllers\Admin\Content\HomepageSettingController::class, 'update'])->name('settings.update');
            
            // CTA Panels
            Route::resource('cta-panel', \App\Http\Controllers\Admin\Content\CtaPanelController::class)->parameters([
                'cta-panel' => 'ctaPanel',
            ]);
            Route::post('cta-panel/{ctaPanel}/toggle-active', [\App\Http\Controllers\Admin\Content\CtaPanelController::class, 'toggleActive'])->name('cta-panel.toggle-active');
            
            // Bento Items
            Route::resource('bento-item', \App\Http\Controllers\Admin\Content\BentoItemController::class)->parameters([
                'bento-item' => 'bentoItem',
            ]);
            Route::post('bento-item/{bentoItem}/toggle-active', [\App\Http\Controllers\Admin\Content\BentoItemController::class, 'toggleActive'])->name('bento-item.toggle-active');
            
            // Testimonials
            Route::resource('testimonial', \App\Http\Controllers\Admin\Content\TestimonialController::class);
            Route::post('testimonial/{testimonial}/toggle-active', [\App\Http\Controllers\Admin\Content\TestimonialController::class, 'toggleActive'])->name('testimonial.toggle-active');
        });

        // Settings Routes
        Route::resource('setting', SettingController::class);

        // Cookie Settings (singleton)
        Route::get('cookie-settings', [\App\Http\Controllers\Admin\Content\CookieSettingController::class, 'index'])->name('cookie-settings.index');
        Route::put('cookie-settings', [\App\Http\Controllers\Admin\Content\CookieSettingController::class, 'update'])->name('cookie-settings.update');

        // General Settings (singleton)
        Route::get('general-settings', [\App\Http\Controllers\Admin\Content\GeneralSettingController::class, 'index'])->name('general-settings.index');
        Route::put('general-settings', [\App\Http\Controllers\Admin\Content\GeneralSettingController::class, 'update'])->name('general-settings.update');

        // Data Management Routes
        Route::resource('data-management', DataManagementController::class);
    });

    // Settings Routes
    Route::get('/settings/menu', MenuManager::class)->name('settings.menu');
    Route::get('/settings/header-menu', HeaderMenuManager::class)->name('settings.header-menu');
    Route::get('/settings/footer-menu', FooterMenuManager::class)->name('settings.footer-menu');
    Route::get('/settings/theme', ThemeManager::class)->name('settings.theme');
});