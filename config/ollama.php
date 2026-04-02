<?php

return [
    "base_url" => env("OLLAMA_BASE_URL", "http://45.140.140.31:11434"),
    "model" => env("OLLAMA_MODEL", "gemma2:9b"),
    "cache_ttl" => (int) env("OLLAMA_CACHE_TTL", 2592000),
];
