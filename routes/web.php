<?php

use App\Http\Controllers\OpenOverheid\DocumentController;
use App\Http\Controllers\OpenOverheid\SearchController;
use Illuminate\Support\Facades\Route;

// Open Overheid routes
Route::get('/', [SearchController::class, 'searchPage'])->name('home');
Route::get('/zoek', [SearchController::class, 'searchPage'])->name('zoek');
Route::get('/zoeken', [SearchController::class, 'searchResults'])->name('zoeken');
Route::get('/open-overheid/search', [SearchController::class, 'index']);
Route::get('/open-overheid/documents/{id}', [DocumentController::class, 'show']);
Route::get('/v2026', [SearchController::class, 'v2026LandingPage'])->name('v2026');
Route::get('/new', [SearchController::class, 'newLandingPage'])->name('new');
Route::get('/over', [SearchController::class, 'aboutPage'])->name('over');
Route::get('/api/live-search', [SearchController::class, 'liveSearch'])->name('api.live-search');
Route::get('/api/autocomplete', [SearchController::class, 'autocomplete'])->name('api.autocomplete');
Route::get('/verwijzingen', [SearchController::class, 'referencesPage'])->name('verwijzingen');
