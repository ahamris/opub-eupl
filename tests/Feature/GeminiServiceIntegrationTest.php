<?php

use App\Services\AI\GeminiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

uses(RefreshDatabase::class);

beforeEach(function () {
    Config::set('open_overheid.gemini.api_key', env('GEMINI_API_KEY', 'test-key'));
    Config::set('open_overheid.gemini.model', 'gemini-2.0-flash-exp');
    Config::set('open_overheid.gemini.tts_model', 'gemini-2.5-flash-preview-tts');
    Config::set('open_overheid.gemini.cache_ttl', 2592000);
    Cache::flush();
});

it('returns null when summarizing empty dossier', function () {
    $service = app(GeminiService::class);
    $result = $service->summarizeDossier([]);

    expect($result)->toBeNull();
});

it('returns null when enhancing empty title', function () {
    $service = app(GeminiService::class);
    $result = $service->enhanceTitle('', null);

    expect($result)->toBeNull();
});

it('returns empty array when extracting keywords from empty text', function () {
    $service = app(GeminiService::class);
    $result = $service->extractKeywords('', 10);

    expect($result)->toBeArray()
        ->toBeEmpty();
});

it('caches responses correctly', function () {
    $service = app(GeminiService::class);
    $documents = [
        (object) ['title' => 'Test', 'description' => 'Test', 'content' => 'Test'],
    ];

    // Build the same content hash that the service uses
    $content = 'Titel: Test'."\n".'Omschrijving: Test'."\n".'Inhoud: Test';
    $cacheKey = 'gemini:dossier_summary:'.md5($content);

    // Pre-populate cache with the exact key format the service uses
    Cache::put($cacheKey, 'Cached summary', 2592000);

    // Should return cached value without calling API
    $result = $service->summarizeDossier($documents);

    expect($result)->toBe('Cached summary');
});
