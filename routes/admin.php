<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Auth\AdminPasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\AdminNewPasswordController;
use App\Livewire\Admin\MenuManager;
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
    });

    // Users Resource Routes
    Route::resource('users', UserController::class);

    // Settings Routes
    Route::get('/settings/menu', MenuManager::class)->name('settings.menu');
    Route::get('/settings/theme', ThemeManager::class)->name('settings.theme');
});