<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OPub Public API Keys
    |--------------------------------------------------------------------------
    |
    | Comma-separated list of API keys allowed to access the public API endpoints.
    | Example:
    |   OPUB_API_KEYS="key-for-app-a,key-for-app-b"
    |
    */
    'keys' => array_values(array_filter(array_map('trim', explode(',', (string) env('OPUB_API_KEYS', ''))))),

    /*
    |--------------------------------------------------------------------------
    | API Key Header Name
    |--------------------------------------------------------------------------
    |
    | Header to read the API key from. Authorization: Bearer <key> is also
    | supported by the middleware for convenience.
    |
    */
    'header' => env('OPUB_API_KEY_HEADER', 'X-OPUB-API-KEY'),
];

