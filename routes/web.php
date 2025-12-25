<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OpenOverheid\DocumentController;
use App\Http\Controllers\OpenOverheid\DossierController;
use App\Http\Controllers\OpenOverheid\ReportController;
use App\Http\Controllers\OpenOverheid\SearchController;
use App\Http\Controllers\OpenOverheid\ThemaController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TypesenseGuiController;
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
Route::get('/contact', [SearchController::class, 'contactPage'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/chat', [SearchController::class, 'chatPage'])->name('chat');
Route::get('/api/live-search', [SearchController::class, 'liveSearch'])->name('api.live-search');
Route::get('/api/fast-search', [SearchController::class, 'fastSearch'])->name('api.fast-search');
Route::get('/api/autocomplete', [SearchController::class, 'autocomplete'])->name('api.autocomplete');
Route::post('/api/natural-language-search', [SearchController::class, 'naturalLanguageSearch'])->name('api.natural-language-search');
Route::get('/verwijzingen', [SearchController::class, 'referencesPage'])->name('verwijzingen');

// Report routes
Route::get('/rapporten', [ReportController::class, 'index'])->name('reports.index');
Route::get('/in-cijfers', [ReportController::class, 'index'])->name('reports.index'); // Alias

// Thema routes
Route::get('/themas', [ThemaController::class, 'index'])->name('themas.index');

// Dossier routes
Route::get('/dossiers', [DossierController::class, 'index'])->name('dossiers.index');
Route::get('/dossiers/{id}', [DossierController::class, 'show'])->name('dossiers.show');
Route::post('/dossiers/{id}/enhance', [DossierController::class, 'enhance'])->name('dossiers.enhance');
Route::get('/dossiers/{id}/summary', [DossierController::class, 'getSummary'])->name('dossiers.summary');
Route::get('/dossiers/{id}/audio', [DossierController::class, 'getAudio'])->name('dossiers.audio');

// Blog routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');


// User Authentication & Dashboard Routes
Route::prefix('account')->name('user.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [\App\Http\Controllers\User\AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [\App\Http\Controllers\User\AuthController::class, 'login']);
        Route::get('register', [\App\Http\Controllers\User\AuthController::class, 'showRegister'])->name('register');
        Route::post('register', [\App\Http\Controllers\User\AuthController::class, 'register']);
    });
    
    Route::middleware('auth')->group(function () {
        Route::post('logout', [\App\Http\Controllers\User\AuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
        Route::get('subscriptions', [\App\Http\Controllers\User\DashboardController::class, 'subscriptions'])->name('subscriptions');
    });
});

// Static Page routes
Route::get('/pagina/{slug}', [PageController::class, 'show'])->name('page.show');

// Dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Typesense GUI routes (protected)
    Route::prefix('tsgui')->group(function () {
        Route::get('/', [TypesenseGuiController::class, 'index'])->name('tsgui.index');
        Route::post('/collections', [TypesenseGuiController::class, 'createCollection'])->name('tsgui.collection.create');
        Route::get('/collections/{collection}', [TypesenseGuiController::class, 'show'])->name('tsgui.collection');
        Route::get('/collections/{collection}/search', [TypesenseGuiController::class, 'search'])->name('tsgui.search');
        Route::get('/collections/{collection}/documents/{id}', [TypesenseGuiController::class, 'document'])->name('tsgui.document');
        Route::post('/collections/{collection}/documents', [TypesenseGuiController::class, 'storeDocument'])->name('tsgui.document.store');
        Route::delete('/collections/{collection}/documents/{id}', [TypesenseGuiController::class, 'destroyDocument'])->name('tsgui.document.destroy');
        Route::delete('/collections/{collection}', [TypesenseGuiController::class, 'destroyCollection'])->name('tsgui.collection.destroy');
    });
});

require __DIR__.'/auth.php';
