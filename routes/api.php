<?php

use App\Http\Controllers\Api\TypesenseDocumentController;
use App\Http\Controllers\Api\TypesenseSearchController;
use Illuminate\Support\Facades\Route;

Route::middleware('opub-api-key')->group(function () {
    Route::get('/typesense/search', [TypesenseSearchController::class, 'search'])->name('api.typesense.search');
    Route::get('/typesense/documents/{id}', [TypesenseDocumentController::class, 'show'])->name('api.typesense.documents.show');
});

