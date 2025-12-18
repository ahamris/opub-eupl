<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Open Overheid API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the Open Overheid openbaarmakingen zoek API integration.
    | This API provides access to publicly available government documents.
    |
    */

    'base_url' => env('OPEN_OVERHEID_BASE_URL', 'https://open.overheid.nl/overheid/openbaarmakingen/api/v0'),

    'timeout' => env('OPEN_OVERHEID_TIMEOUT', 30), // Increased from 10 to 30 seconds for slow API responses

    /*
    |--------------------------------------------------------------------------
    | Synchronization Settings
    |--------------------------------------------------------------------------
    |
    | Configure how documents are synchronized to the local PostgreSQL database.
    |
    */

    'sync' => [
        'enabled' => env('OPEN_OVERHEID_SYNC_ENABLED', true),
        'batch_size' => env('OPEN_OVERHEID_SYNC_BATCH_SIZE', 50),
        'days_back' => env('OPEN_OVERHEID_SYNC_DAYS_BACK', 1), // Sync last N days (default: 1 = 24 hours)
        'delay_between_requests' => env('OPEN_OVERHEID_SYNC_DELAY', 200), // Delay in milliseconds between document fetches (default: 200ms)
    ],

    /*
    |--------------------------------------------------------------------------
    | Search Strategy
    |--------------------------------------------------------------------------
    |
    | When true, search operations will query the local PostgreSQL database
    | instead of the remote API. Falls back to remote API if local search
    | is disabled or fails.
    |
    */

    'use_local_search' => env('OPEN_OVERHEID_USE_LOCAL_SEARCH', true),

    /*
    |--------------------------------------------------------------------------
    | Typesense Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Typesense search engine integration.
    | Documents are automatically synced from PostgreSQL to Typesense.
    |
    */

    'typesense' => [
        'enabled' => env('TYPESENSE_SYNC_ENABLED', true),
        'api_key' => env('TYPESENSE_API_KEY'),
        'host' => env('TYPESENSE_HOST', 'localhost'),
        'port' => (int) env('TYPESENSE_PORT', 8108),
        'protocol' => env('TYPESENSE_PROTOCOL', 'http'),
        'natural_language_search' => [
            'enabled' => env('TYPESENSE_NL_SEARCH_ENABLED', false),
            'model' => env('TYPESENSE_NL_SEARCH_MODEL', ''),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Gemini AI Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Gemini API integration.
    | Used for dossier enhancement, summaries, and audio generation.
    |
    */

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-2.0-flash-exp'),
        'tts_model' => env('GEMINI_TTS_MODEL', 'gemini-2.5-flash-tts'),
        'cache_ttl' => (int) env('GEMINI_CACHE_TTL', 2592000), // 30 days
        'max_retries' => (int) env('GEMINI_MAX_RETRIES', 3),
        'timeout' => (int) env('GEMINI_TIMEOUT', 30),
    ],

];
