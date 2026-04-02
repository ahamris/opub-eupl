<?php

use App\Http\Controllers\Api\TypesenseDocumentController;
use App\Http\Controllers\Api\TypesenseSearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V2\ApiV2Controller;
use App\Http\Controllers\Api\V2\IngestController;

Route::middleware('opub-api-key')->group(function () {
    Route::get('/typesense/search', [TypesenseSearchController::class, 'search'])->name('api.typesense.search');
    Route::get('/typesense/documents/{id}', [TypesenseDocumentController::class, 'show'])->name('api.typesense.documents.show');
});

// API v2 routes
Route::prefix('v2')->group(function () {
    // Public read endpoints
    Route::get('search', [ApiV2Controller::class, 'search']);
    Route::get('documents/{id}', [ApiV2Controller::class, 'document']);
    Route::get('dossiers', [ApiV2Controller::class, 'dossiers']);
    Route::get('dossiers/{id}', [ApiV2Controller::class, 'dossier']);
    Route::get('stats', [ApiV2Controller::class, 'stats']);
    Route::get('settings', [ApiV2Controller::class, 'settings']);
    Route::get('organisations/{name}', [ApiV2Controller::class, 'organisation']);
    Route::get('documents/{id}/similar', [ApiV2Controller::class, 'similar']);
    Route::post('woo-verzoek', [ApiV2Controller::class, 'wooVerzoek']);

    // Chat (session-based)
    Route::middleware('web')->group(function () {
        Route::post('chat/send', [ApiV2Controller::class, 'chatSend']);
        Route::get('chat/conversations', [ApiV2Controller::class, 'chatConversations']);
        Route::get('chat/conversations/{id}/messages', [ApiV2Controller::class, 'chatMessages']);
        Route::delete('chat/conversations/{id}', [ApiV2Controller::class, 'chatDelete']);
    });

    // Ingest endpoints (API key required)
    Route::middleware('opub-api-key')->group(function () {
        Route::post('ingest', [IngestController::class, 'store']);
        Route::post('ingest/batch', [IngestController::class, 'batch']);
        Route::delete('ingest/{external_id}', [IngestController::class, 'destroy']);
    });
});
