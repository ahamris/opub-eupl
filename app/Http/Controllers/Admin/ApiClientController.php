<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = ApiClient::query()
            ->orderByDesc('created_at')
            ->get();

        return view('admin.api-clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.api-clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'allowed_domains_raw' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $plainKey = $this->generateApiKey();
        $hash = hash('sha256', $plainKey);

        $domains = $this->normalizeDomains((string) ($validated['allowed_domains_raw'] ?? ''));

        $client = ApiClient::create([
            'name' => $validated['name'],
            'key_prefix' => substr($plainKey, 0, 12),
            'api_key_hash' => $hash,
            'allowed_domains' => $domains,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        return redirect()
            ->route('admin.api-clients.show', $client)
            ->with('success', 'API client created. Copy the key now — it will not be shown again.')
            ->with('plain_api_key', $plainKey);
    }

    /**
     * Display the specified resource.
     */
    public function show(ApiClient $apiClient)
    {
        return view('admin.api-clients.show', [
            'client' => $apiClient,
            'plainApiKey' => session('plain_api_key'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApiClient $apiClient)
    {
        $allowedDomainsRaw = '';
        if ($apiClient->allowed_domains) {
            $allowedDomainsRaw = implode("\n", $apiClient->allowed_domains->toArray());
        }

        return view('admin.api-clients.edit', [
            'client' => $apiClient,
            'allowedDomainsRaw' => $allowedDomainsRaw,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApiClient $apiClient)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'allowed_domains_raw' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $domains = $this->normalizeDomains((string) ($validated['allowed_domains_raw'] ?? ''));

        $apiClient->update([
            'name' => $validated['name'],
            'allowed_domains' => $domains,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()
            ->route('admin.api-clients.show', $apiClient)
            ->with('success', 'API client updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApiClient $apiClient)
    {
        $apiClient->delete();

        return redirect()
            ->route('admin.api-clients.index')
            ->with('success', 'API client deleted.');
    }

    /**
     * Regenerate an API key (the old key stops working immediately).
     */
    public function regenerate(ApiClient $apiClient)
    {
        $plainKey = $this->generateApiKey();

        $apiClient->update([
            'key_prefix' => substr($plainKey, 0, 12),
            'api_key_hash' => hash('sha256', $plainKey),
        ]);

        return redirect()
            ->route('admin.api-clients.show', $apiClient)
            ->with('success', 'API key regenerated. Copy the new key now — it will not be shown again.')
            ->with('plain_api_key', $plainKey);
    }

    private function generateApiKey(): string
    {
        // 48 chars base62-ish; long enough to be unguessable
        return Str::random(48);
    }

    /**
     * Accepts newline/comma separated domains and normalizes them.
     *
     * Allowed formats:
     * - example.com
     * - *.example.com
     * - https://example.com (scheme will be stripped)
     * - * (allow any browser origin)
     */
    private function normalizeDomains(string $raw): array
    {
        $raw = trim($raw);
        if ($raw === '') {
            return [];
        }

        $tokens = preg_split('/[\r\n,]+/', $raw) ?: [];
        $domains = [];

        foreach ($tokens as $token) {
            $token = trim((string) $token);
            if ($token === '') {
                continue;
            }

            if ($token === '*') {
                return ['*'];
            }

            // If someone pasted a full URL, keep only host.
            if (str_contains($token, '://')) {
                $p = parse_url($token);
                $token = (string) ($p['host'] ?? $token);
            }

            $token = strtolower($token);
            $token = preg_replace('/:\d+$/', '', $token) ?? $token;
            $token = trim($token, " \t\n\r\0\x0B.");

            if ($token === '') {
                continue;
            }

            // Very lightweight validation: allow wildcard prefix, then a host-like string
            if (! preg_match('/^(\*\.)?[a-z0-9][a-z0-9\-\.]*[a-z0-9]$/', $token)) {
                continue;
            }

            $domains[] = $token;
        }

        $domains = array_values(array_unique($domains));
        sort($domains);

        return $domains;
    }
}
