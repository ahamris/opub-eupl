# OPub Public API (Typesense Search) — Integration Guide

This guide explains how to integrate a **separate application** with the OPub dataset by calling OPub’s **token-protected Typesense search API**.

## What you get

- **Single source of truth**: your other apps reuse the same indexed dataset.
- **Stable JSON** search responses (pagination + facet counts).
- **Access control** via API keys (“apps I allow”).

## Server-side setup (in OPub)

### 1) Create an API client (recommended)

In OPub admin:

- Go to `Admin → API Clients` (URL: `/admin/api-clients`)
- Create a client, copy the generated key once
- Optionally set **Allowed Domains** (to restrict browser origins via `Origin` / `Referer`)

### 2) Configure allowed API keys (legacy)

Set one or more keys on the OPub server:

```env
# Comma-separated keys
OPUB_API_KEYS="key-for-app-a,key-for-app-b"

# Optional header name (default: X-OPUB-API-KEY)
OPUB_API_KEY_HEADER="X-OPUB-API-KEY"
```

### 2) Deploy

- Ensure OPub is deployed with the new routes enabled (`routes/api.php` is loaded from `bootstrap/app.php`).
- The API is **secure-by-default**:
  - If no DB API clients exist and `OPUB_API_KEYS` is empty, requests return **403**
  - If a request includes `Origin`/`Referer` and the API client has allowed domains, the origin must match

## Authentication (from your new app)

Send the API key in either form:

- Header: `X-OPUB-API-KEY: <key>`
- Or: `Authorization: Bearer <key>`

If the key is missing/invalid you’ll get **401**.

## Base URL

All endpoints are under:

- `https://<opub-host>/api/...`

## Endpoints

### 1) Search

`GET /api/typesense/search`

#### Query parameters

- **q**: string (search query; can be empty)
- **page**: integer (default 1)
- **per_page**: 10|20|50|100 (default 20)
- **sort**: `relevance|publication_date|modified_date` (default `relevance`)
- **publicatiedatum_van**: `d-m-Y` (optional)
- **publicatiedatum_tot**: `d-m-Y` (optional)
- **documentsoort[]**: string[] (maps to Typesense field `document_type`)
- **thema[]**: string[] (maps to Typesense field `theme`)
- **organisatie[]**: string[] (maps to Typesense field `organisation`)
- **publicatiebestemming[]**: string[] (maps to Typesense field `publication_destination`)
- **informatiecategorie**: string (maps to Typesense field `category`)
- **include_content**: boolean (default false) — include full `content` in each item
- **include_raw**: boolean (default false) — include raw Typesense response payload under `raw`

#### Response (shape)

```json
{
  "items": [
    {
      "id": "oep-ob-...",
      "title": "…",
      "description": "…",
      "publication_date": "2026-01-21",
      "document_type": "…",
      "category": "…",
      "theme": "…",
      "organisation": "…",
      "publication_destination": "…",
      "url": "…",
      "content": "…" // only when include_content=1
    }
  ],
  "total": 12345,
  "page": 1,
  "perPage": 20,
  "search_time_ms": 7,
  "facet_counts": [],
  "query": "water",
  "raw": {} // only when include_raw=1
}
```

#### Example request

```bash
curl -H "X-OPUB-API-KEY: key-for-app-a" ^
  "https://<opub-host>/api/typesense/search?q=water&per_page=20&page=1&sort=publication_date&organisatie[]=gemeente%20Utrecht"
```

### 2) Get a document

`GET /api/typesense/documents/{id}`

Notes:
- `{id}` can be either the Typesense document id (internal) **or** an `external_id` (the id you receive in `items[].id` from search).
- Optional: `include_content=1`

```bash
curl -H "Authorization: Bearer key-for-app-a" ^
  "https://<opub-host>/api/typesense/documents/oep-ob-123?include_content=1"
```

## Client examples (new app)

### JavaScript / TypeScript (fetch)

```ts
export async function opubSearch(baseUrl: string, apiKey: string, params: Record<string, string>) {
  const url = new URL("/api/typesense/search", baseUrl);
  for (const [k, v] of Object.entries(params)) url.searchParams.set(k, v);

  const res = await fetch(url.toString(), {
    headers: { "X-OPUB-API-KEY": apiKey },
  });

  if (!res.ok) throw new Error(`OPub API error: ${res.status} ${await res.text()}`);
  return res.json();
}
```

### PHP (Laravel/any) using HTTP client

```php
$response = Http::withHeaders([
    'X-OPUB-API-KEY' => env('OPUB_API_KEY'),
])->get(rtrim(env('OPUB_BASE_URL'), '/').'/api/typesense/search', [
    'q' => 'water',
    'page' => 1,
    'per_page' => 20,
]);

$data = $response->throw()->json();
```

### Python (requests)

```python
import requests

res = requests.get(
    "https://<opub-host>/api/typesense/search",
    headers={"X-OPUB-API-KEY": "key-for-app-a"},
    params={"q": "water", "page": 1, "per_page": 20},
    timeout=15,
)
res.raise_for_status()
data = res.json()
```

## Operational notes

- **Browser apps (CORS)**: If your new app runs in a browser and calls OPub directly, you may need to configure CORS on OPub to allow your origin. Otherwise, call OPub from your backend (recommended).
- **Errors**:
  - `401` Unauthorized (missing/invalid key)
  - `403` Public API not configured (`OPUB_API_KEYS` empty)
  - `422` Validation error (bad query params)
  - `500` Typesense or server error

## Where this is implemented (in OPub)

- Routes: `routes/api.php`
- Auth middleware: `app/Http/Middleware/RequireOpubApiKey.php`
- Search controller: `app/Http/Controllers/Api/TypesenseSearchController.php`
- Document controller: `app/Http/Controllers/Api/TypesenseDocumentController.php`
- Config: `config/opub_api.php`

