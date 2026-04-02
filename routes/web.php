<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TypesenseGuiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPA Frontend (React + UntitledUI)
|--------------------------------------------------------------------------
|
| De React SPA handelt alle publieke pagina's af.
| Client-side routing via React Router.
|
*/

// Contact form POST (consumed by SPA)
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/subscriptions', [ContactController::class, 'storeSubscription'])->name('subscriptions.store');

// SPA Auth API (session-based JSON endpoints)
Route::post('/auth/login', [\App\Http\Controllers\Api\AuthApiController::class, 'login'])->name('auth.api.login');
Route::post('/auth/register', [\App\Http\Controllers\Api\AuthApiController::class, 'register'])->name('auth.api.register');
Route::get('/auth/user', [\App\Http\Controllers\Api\AuthApiController::class, 'user'])->name('auth.api.user');
Route::post('/auth/logout', [\App\Http\Controllers\Api\AuthApiController::class, 'logout'])->name('auth.api.logout');
Route::middleware('auth')->group(function () {
    Route::put('/auth/profile', [\App\Http\Controllers\Api\AuthApiController::class, 'updateProfile'])->name('auth.api.profile');
    Route::put('/auth/password', [\App\Http\Controllers\Api\AuthApiController::class, 'changePassword'])->name('auth.api.password');
    Route::get('/auth/subscriptions', [\App\Http\Controllers\Api\AuthApiController::class, 'subscriptions'])->name('auth.api.subscriptions');
    Route::patch('/auth/subscriptions/{id}/toggle', [\App\Http\Controllers\Api\AuthApiController::class, 'toggleSubscription'])->name('auth.api.subscription.toggle');
    Route::delete('/auth/subscriptions/{id}', [\App\Http\Controllers\Api\AuthApiController::class, 'deleteSubscription'])->name('auth.api.subscription.delete');

    // Organisation claim
    Route::post('/auth/claim-organisation', [\App\Http\Controllers\Api\AuthApiController::class, 'claimOrganisation'])->name('auth.api.claim');
    Route::put('/auth/organisation/{id}', [\App\Http\Controllers\Api\AuthApiController::class, 'updateOrganisation'])->name('auth.api.organisation.update');
});

// Subscription / Attendering (public endpoints)
Route::post('/api/subscriptions', [\App\Http\Controllers\SubscriptionController::class, 'store'])->name('subscription.store.api');
Route::post('/api/subscriptions/resend', [\App\Http\Controllers\SubscriptionController::class, 'resendVerification'])->name('subscription.resend');
Route::get('/attendering/verify/{token}', [\App\Http\Controllers\SubscriptionController::class, 'verify'])->name('subscription.verify');
Route::get('/attendering/unsubscribe/{id}/{hash}', [\App\Http\Controllers\SubscriptionController::class, 'unsubscribe'])->name('subscription.unsubscribe');

// Chat API (session-based, used by SPA)
Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'send'])->name('chat.send');
Route::post('/chat/stream', [\App\Http\Controllers\ChatController::class, 'stream'])->name('chat.stream');
Route::get('/chat/conversations', [\App\Http\Controllers\ChatController::class, 'conversations'])->name('chat.conversations');
Route::get('/chat/conversations/{id}/messages', [\App\Http\Controllers\ChatController::class, 'messages'])->name('chat.messages');
Route::delete('/chat/conversations/{id}', [\App\Http\Controllers\ChatController::class, 'deleteConversation'])->name('chat.delete');

// Legacy API endpoints (still used by some components)
Route::get('/api/live-search', [\App\Http\Controllers\OpenOverheid\SearchController::class, 'liveSearch'])->name('api.live-search');
Route::get('/api/fast-search', [\App\Http\Controllers\OpenOverheid\SearchController::class, 'fastSearch'])->name('api.fast-search');
Route::get('/api/autocomplete', [\App\Http\Controllers\OpenOverheid\SearchController::class, 'autocomplete'])->name('api.autocomplete');
Route::post('/api/natural-language-search', [\App\Http\Controllers\OpenOverheid\SearchController::class, 'naturalLanguageSearch'])->name('api.natural-language-search');

/*
|--------------------------------------------------------------------------
| User Authentication & Dashboard
|--------------------------------------------------------------------------
*/

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
        Route::delete('subscriptions/{subscription}', [\App\Http\Controllers\User\DashboardController::class, 'destroySubscription'])->name('subscription.destroy');
        Route::get('berichtenbox', [\App\Http\Controllers\User\DashboardController::class, 'berichtenbox'])->name('berichtenbox');
        Route::get('berichtenbox/archief', [\App\Http\Controllers\User\DashboardController::class, 'berichtenboxArchief'])->name('berichtenbox.archief');
        Route::get('berichtenbox/prullenbak', [\App\Http\Controllers\User\DashboardController::class, 'berichtenboxPrullenbak'])->name('berichtenbox.prullenbak');
        Route::get('berichtenbox/{id}', [\App\Http\Controllers\User\DashboardController::class, 'berichtShow'])->name('bericht.show');
    });
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Profile, Typesense GUI)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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

/*
|--------------------------------------------------------------------------
| API Documentation
|--------------------------------------------------------------------------
*/


// Named route aliases for Blade view compatibility
Route::get("/chat-page", function () { return view("spa"); })->name("chat");
Route::get("/zoeken-page", function () { return view("spa"); })->name("zoeken");
Route::get("/zoek-page", function () { return view("spa"); })->name("zoek");

Route::get('/api/docs', function () { return view('swagger'); })->name('api.docs');
Route::get('/api/v2/openapi.json', function () { return response()->file(public_path('api-docs.json')); });

/*
|--------------------------------------------------------------------------
| SPA Catch-All (MUST be last)
|--------------------------------------------------------------------------
|
| Alle niet-gematchte routes worden door de React SPA afgehandeld.
| React Router doet de client-side routing.
|
*/

Route::get('/{any?}', function () {
    return view('spa');
})->where('any', '^(?!api|admin|livewire|storage|tsgui|account).*$')->name('home');
