<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class RequireOpubApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $originHeader = (string) $request->headers->get('Origin', '');
        $originHostFromOrigin = null;
        if ($originHeader !== '') {
            $p = parse_url($originHeader);
            $originHostFromOrigin = isset($p['host']) && is_string($p['host']) && $p['host'] !== ''
                ? strtolower($p['host'])
                : null;
        }

        // Handle CORS preflight (OPTIONS) based on allowed domains (no API key required).
        if ($request->isMethod('OPTIONS') && $originHostFromOrigin !== null) {
            try {
                if (Schema::hasTable('api_clients') && $this->isOriginAllowedForAnyActiveClient($originHostFromOrigin)) {
                    $resp = response('', 204);
                    return $this->applyCorsHeaders($resp, $originHeader);
                }
            } catch (\Throwable $e) {
                // If database is unavailable or query fails, deny preflight (secure default)
                \Log::warning('CORS preflight check failed', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'message' => 'Origin not allowed.',
            ], 403);
        }

        $headerName = (string) config('opub_api.header', 'X-OPUB-API-KEY');
        $apiKey = (string) $request->header($headerName, '');

        // Also support Authorization: Bearer <key>
        if ($apiKey === '') {
            $auth = (string) $request->header('Authorization', '');
            if (preg_match('/^\s*Bearer\s+(.+)\s*$/i', $auth, $m)) {
                $apiKey = trim($m[1]);
            }
        }

        if ($apiKey === '') {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 401);
        }

        // Prefer DB-managed API clients when table exists.
        try {
            if (Schema::hasTable('api_clients')) {
                // If there are no DB clients yet, fall back to legacy keys (if any),
                // otherwise treat the public API as not configured.
                $hasDbClients = Cache::remember('opub_api:has_db_clients', 60, function () {
                    try {
                        return ApiClient::query()->where('is_active', true)->exists();
                    } catch (\Throwable $e) {
                        \Log::warning('Failed to check for DB API clients', ['error' => $e->getMessage()]);
                        return false;
                    }
                });

            if (! $hasDbClients) {
                // Fallback: env/config list (legacy)
                $allowedKeys = config('opub_api.keys', []);

                if (empty($allowedKeys)) {
                    return response()->json([
                        'message' => 'Public API is not configured.',
                    ], 403);
                }

                foreach ($allowedKeys as $allowed) {
                    if (is_string($allowed) && $allowed !== '' && hash_equals($allowed, $apiKey)) {
                        return $next($request);
                    }
                }

                return response()->json([
                    'message' => 'Unauthorized.',
                ], 401);
            }

                $hash = hash('sha256', $apiKey);

                /** @var ApiClient|null $client */
                try {
                    $client = ApiClient::query()
                        ->where('api_key_hash', $hash)
                        ->where('is_active', true)
                        ->first();
                } catch (\Throwable $e) {
                    \Log::error('Failed to query API client', ['error' => $e->getMessage()]);
                    return response()->json([
                        'message' => 'Service temporarily unavailable.',
                    ], 503);
                }

                if (! $client) {
                    return response()->json([
                        'message' => 'Unauthorized.',
                    ], 401);
                }

                // Enforce allowed domains for browser-style requests when Origin/Referer is present.
                $originHost = $this->extractOriginHost($request);
                if ($originHost !== null && ! $this->isOriginAllowed($originHost, $client->allowed_domains ? $client->allowed_domains->toArray() : [])) {
                    return response()->json([
                        'message' => 'Origin not allowed.',
                    ], 403);
                }

                // Update last used info at most once per minute per client (avoid hot updates).
                $cacheKey = 'api_client:last_used:'.$client->id;
                if (Cache::add($cacheKey, true, 60)) {
                    try {
                        $client->forceFill([
                            'last_used_at' => now(),
                            'last_used_ip' => get_client_ip($request),
                            'last_used_user_agent' => substr((string) $request->userAgent(), 0, 2000),
                        ])->save();
                    } catch (\Throwable $e) {
                        // Don't fail the request if last_used update fails
                        \Log::warning('Failed to update API client last_used', ['error' => $e->getMessage()]);
                    }
                }

            // Optionally expose the client on request for logging/auditing downstream.
            $request->attributes->set('opub_api_client', $client);

            $response = $next($request);

            // If the browser sent an Origin header and it’s allowed, attach CORS headers.
            if ($originHeader !== '' && $originHostFromOrigin !== null) {
                $allowedDomainsForClient = $client->allowed_domains ? $client->allowed_domains->toArray() : [];
                if ($this->isOriginAllowed($originHostFromOrigin, $allowedDomainsForClient)) {
                    return $this->applyCorsHeaders($response, $originHeader);
                }
            }

            return $response;
        }

        // Fallback: env/config list (legacy)
        $allowedKeys = config('opub_api.keys', []);

        // If no keys configured, block by default (secure-by-default).
        if (empty($allowedKeys)) {
            return response()->json([
                'message' => 'Public API is not configured.',
            ], 403);
        }

        foreach ($allowedKeys as $allowed) {
            if (is_string($allowed) && $allowed !== '' && hash_equals($allowed, $apiKey)) {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'Unauthorized.',
        ], 401);
    }

    private function extractOriginHost(Request $request): ?string
    {
        $origin = (string) $request->headers->get('Origin', '');
        $referer = (string) $request->headers->get('Referer', '');

        $candidate = $origin !== '' ? $origin : $referer;
        if ($candidate === '') {
            // No browser origin context; assume server-to-server call.
            return null;
        }

        $parts = parse_url($candidate);
        $host = $parts['host'] ?? null;

        if (! is_string($host) || $host === '') {
            return null;
        }

        return strtolower($host);
    }

    /**
     * Domain rules:
     * - Empty allowed domains: deny browser-origin calls; allow server-to-server (handled above).
     * - "*" allows any origin.
     * - "example.com" matches exact host.
     * - "*.example.com" matches any subdomain of example.com (not the apex).
     */
    private function isOriginAllowed(string $originHost, array $allowedDomains): bool
    {
        $originHost = strtolower($originHost);

        if (empty($allowedDomains)) {
            return false;
        }

        foreach ($allowedDomains as $raw) {
            if (! is_string($raw)) {
                continue;
            }
            $rule = strtolower(trim($raw));
            if ($rule === '') {
                continue;
            }

            if ($rule === '*') {
                return true;
            }

            // Normalize: strip scheme if someone stored it
            if (str_contains($rule, '://')) {
                $p = parse_url($rule);
                if (! empty($p['host'])) {
                    $rule = strtolower((string) $p['host']);
                }
            }

            // Strip port if present
            $rule = preg_replace('/:\d+$/', '', $rule) ?? $rule;

            if ($originHost === $rule) {
                return true;
            }

            if (str_starts_with($rule, '*.')) {
                $base = substr($rule, 2);
                if ($base !== '' && str_ends_with($originHost, '.'.$base)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if origin is allowed for any active API client (streaming).
     * This avoids loading all clients/domains into memory.
     * Uses caching to prevent repeated database queries.
     */
    private function isOriginAllowedForAnyActiveClient(string $originHost): bool
    {
        $originHost = strtolower($originHost);
        $cacheKey = 'opub_api:origin_allowed:'.md5($originHost);

        // Cache result for 5 minutes to reduce database load
        return Cache::remember($cacheKey, 300, function () use ($originHost) {
            try {
                // Limit to first 100 active clients to prevent infinite loops
                $count = 0;
                foreach (
                    ApiClient::query()
                        ->where('is_active', true)
                        ->whereNotNull('allowed_domains')
                        ->select(['allowed_domains'])
                        ->cursor() as $client
                ) {
                    $count++;
                    if ($count > 100) {
                        // Safety limit to prevent memory issues
                        break;
                    }

                    $domains = $client->allowed_domains ? $client->allowed_domains->toArray() : [];
                    if ($this->isOriginAllowed($originHost, $domains)) {
                        return true;
                    }
                }
            } catch (\Throwable $e) {
                \Log::warning('Failed to check origin for API clients', ['error' => $e->getMessage()]);
            }

            return false;
        });
    }

    private function applyCorsHeaders(Response $response, string $originHeader): Response
    {
        // Echo back the requesting origin (recommended when you have a whitelist)
        $response->headers->set('Access-Control-Allow-Origin', $originHeader);
        $response->headers->set('Vary', 'Origin');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'X-OPUB-API-KEY, Authorization, Content-Type, Accept');
        $response->headers->set('Access-Control-Max-Age', '86400');

        return $response;
    }
}

