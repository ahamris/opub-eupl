<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Livewire\Admin\MenuManager;
use App\Livewire\Admin\ThemeManager;

Route::prefix('admin')->name('admin.')->group(function () {
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