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

    'timeout' => env('OPEN_OVERHEID_TIMEOUT', 10),

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
        'port' => env('TYPESENSE_PORT', 8108),
        'protocol' => env('TYPESENSE_PROTOCOL', 'http'),
    ],

];
