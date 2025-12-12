# Open Overheid API Integration Guide

**Complete instructor guide for Open Overheid API integration with PostgreSQL sync and Typesense search**

---

## Table of Contents

1. [Overview](#1-overview)
2. [API Endpoints & Parameters](#2-api-endpoints--parameters)
3. [API Response Structure](#3-api-response-structure)
4. [PostgreSQL Sync Strategy](#4-postgresql-sync-strategy)
5. [Typesense Integration](#5-typesense-integration)
6. [Implementation Guide](#6-implementation-guide)
7. [AI Tool Guidelines](#7-ai-tool-guidelines)

---

## 1. Overview

### 1.1 Purpose

This guide documents the complete integration of the **Open Overheid openbaarmakingen API** into a Laravel application with:

- **PostgreSQL** as the primary data store
- **Typesense** for fast, semantic, and full-text search
- **24-hour incremental sync** for recent documents
- **Automatic Typesense indexing** from PostgreSQL

### 1.2 Architecture Flow

```
Open Overheid API
    ↓ (24hr sync)
PostgreSQL Database
    ↓ (auto-sync)
Typesense Search Engine
    ↓
Frontend Search
```

### 1.3 Goals

- Fetch **most recent documents** every 24 hours to PostgreSQL
- Store **detailed document pages** (not just search results)
- Automatically feed Typesense from PostgreSQL
- Enable fast, semantic search on our website
- Reduce dependency on external API for search operations

---

## 2. API Endpoints & Parameters

### 2.1 Base URL

```
https://open.overheid.nl/overheid/openbaarmakingen/api/v0
```

### 2.2 Search Endpoint

**URL:** `GET /zoek`

**Purpose:** Search for documents (fast, lightweight results)

**Query Parameters:**

| Parameter | Type | Required | Format | Description |
|-----------|------|----------|--------|-------------|
| `zoektekst` | string | No | - | Free text search query (may be empty) |
| `start` | integer | Yes | - | Zero-based offset for pagination |
| `aantalResultaten` | integer | Yes | `10`, `20`, or `50` | Page size (MUST be one of these values) |
| `publicatiedatumVan` | string | No | `DD-MM-YYYY` | Start date filter |
| `publicatiedatumTot` | string | No | `DD-MM-YYYY` | End date filter |
| `documentsoort` | string | No | - | Document type (e.g. "beslisnota") |
| `informatiecategorie` | string | No | - | Information category (e.g. "adviezen") |
| `thema` | string | No | - | Theme/topic (e.g. "afval") |
| `organisatie` | string | No | - | Organisation name |

**Example:**
```
GET /zoek?zoektekst=klimaat&start=0&aantalResultaten=10&publicatiedatumVan=01-12-2025
```

**Important:**
- `aantalResultaten` MUST be exactly `10`, `20`, or `50` (no other values)
- Use `start` for pagination, NOT a `page` parameter
- Date format is `DD-MM-YYYY` (Dutch format)

### 2.3 Detail Endpoint

**URL:** `GET /zoek/{id}`

**Purpose:** Get full detailed information about a single document

**Path Parameter:**
- `{id}` - Document identifier (e.g. `oep-0b03c64e8b44a7b0466a809054860a2c77fb628b`)

**ID Handling:**
- Search results return: `document.id = "oep-..._1"` (with version suffix)
- Detail endpoint uses: base ID without `_1` suffix
- Extract from `pid` URL: `https://open.overheid.nl/documenten/oep-...` → use `oep-...`

**Example:**
```
GET /zoek/oep-0b03c64e8b44a7b0466a809054860a2c77fb628b
```

---

## 3. API Response Structure

### 3.1 Search Response

**Structure:**
```json
{
  "totaal": 109062,
  "resultaten": [
    {
      "document": {
        "id": "oep-0b03c64e8b44a7b0466a809054860a2c77fb628b_1",
        "pid": "https://open.overheid.nl/documenten/oep-0b03c64e8b44a7b0466a809054860a2c77fb628b",
        "titel": "Document title",
        "omschrijving": "Optional description",
        "openbaarmakingsdatum": "2025-12-10",
        "weblocatie": "https://zoek.officielebekendmakingen.nl/...",
        "publisher": "Organisation name",
        "aanbieder": "officielebekendmakingen.nl",
        "mutatiedatumtijd": "2025-12-10T16:04:16.436Z"
      },
      "bestandsgrootte": "0.58 MB",
      "aantalPaginas": 2,
      "bestandsType": "application/pdf",
      "highlightedText": "Optional highlighted search text"
    }
  ]
}
```

**Fields:**
- `totaal`: Total number of results
- `resultaten`: Array of search hit objects
- Each hit contains:
  - `document`: Basic metadata
  - `bestandsgrootte`: File size (human-readable)
  - `aantalPaginas`: Page count
  - `bestandsType`: MIME type
  - `highlightedText`: Optional search highlight

### 3.2 Detail Response

**Structure:**
```json
{
  "document": {
    "pid": "https://open.overheid.nl/documenten/oep-...",
    "weblocatie": "https://zoek.officielebekendmakingen.nl/...",
    "creatiedatum": "2025-12-10",
    "titelcollectie": {
      "officieleTitel": "Full document title",
      "verkorteTitels": [],
      "alternatieveTitels": []
    },
    "classificatiecollectie": {
      "documentsoorten": [{"label": "beslisnota"}],
      "themas": [{"label": "afval"}],
      "informatiecategorieen": [{"label": "adviezen"}],
      "trefwoorden": []
    },
    "verantwoordelijke": {
      "label": "ministerie van Justitie en Veiligheid"
    },
    "omschrijvingen": ["Description text"],
    // ... many more fields
  },
  "plooiIntern": {
    "dcnId": "oep-...",
    "aanbieder": "Aanleverloket"
  },
  "versies": [
    {
      "nummer": 1,
      "openbaarmakingsdatum": "2025-12-10",
      "bestanden": [
        {
          "id": "https://open.overheid.nl/documenten/.../file",
          "url": "https://repository.overheid.nl/.../file.pdf",
          "mime-type": "application/pdf",
          "bestandsnaam": "document.pdf",
          "grootte": 123456,
          "paginas": 10
        }
      ]
    }
  ],
  "documentrelaties": []
}
```

**Key Fields:**
- `document`: Full metadata
- `versies`: Array of document versions with file attachments
- `plooiIntern`: Internal technical metadata
- `documentrelaties`: Related documents

---

## 4. PostgreSQL Sync Strategy

### 4.1 Sync Approach: Incremental (24-hour window)

**Strategy:** Fetch only **recent documents** published in the last 24 hours

**Why:**
- Faster sync times
- Lower API load
- Focus on fresh content
- Full historical sync can be done separately if needed

### 4.2 Sync Process

1. **Calculate date range:**
   - `publicatiedatumVan`: Yesterday (24 hours ago)
   - `publicatiedatumTot`: Today

2. **Search for recent documents:**
   - Use search endpoint with date filters
   - Paginate through all results (50 per page)

3. **Fetch detailed data:**
   - For each search result, call detail endpoint
   - Store complete detail response in PostgreSQL

4. **Upsert to database:**
   - Use `external_id` as unique key
   - Update if exists, insert if new
   - Track `synced_at` timestamp

### 4.3 Database Schema

**Table:** `open_overheid_documents`

```sql
CREATE TABLE open_overheid_documents (
    id BIGSERIAL PRIMARY KEY,
    external_id VARCHAR(255) UNIQUE NOT NULL,
    title TEXT,
    description TEXT,
    content TEXT,
    publication_date DATE,
    document_type VARCHAR(255),
    category VARCHAR(255),
    theme VARCHAR(255),
    organisation VARCHAR(255),
    metadata JSONB,
    synced_at TIMESTAMP,
    typesense_synced_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Indexes
CREATE INDEX idx_external_id ON open_overheid_documents(external_id);
CREATE INDEX idx_publication_date ON open_overheid_documents(publication_date);
CREATE INDEX idx_synced_at ON open_overheid_documents(synced_at);
CREATE INDEX idx_typesense_synced_at ON open_overheid_documents(typesense_synced_at);

-- Full-text search index (PostgreSQL)
ALTER TABLE open_overheid_documents
ADD COLUMN search_vector tsvector
GENERATED ALWAYS AS (
    setweight(to_tsvector('dutch', COALESCE(title, '')), 'A') ||
    setweight(to_tsvector('dutch', COALESCE(description, '')), 'B') ||
    setweight(to_tsvector('dutch', COALESCE(content, '')), 'C')
) STORED;

CREATE INDEX idx_search_vector ON open_overheid_documents USING GIN(search_vector);
```

**Fields:**
- `external_id`: Unique identifier from API (e.g. `oep-...`)
- `metadata`: Full JSONB response from detail endpoint
- `typesense_synced_at`: Tracks when document was last synced to Typesense

### 4.4 Sync Service Implementation

**Service:** `App\Services\OpenOverheid\OpenOverheidSyncService`

**Key Methods:**

```php
// Sync recent documents (last 24 hours)
public function syncRecent(): void

// Sync by date range
public function syncByDateRange(string $from, string $to): void

// Sync single document
public function syncDocument(string $externalId): void
```

**Sync Recent Logic:**
```php
$yesterday = now()->subDay()->format('d-m-Y');
$today = now()->format('d-m-Y');
$this->syncByDateRange($yesterday, $today);
```

### 4.5 Scheduled Sync

**Laravel Scheduler:** Run daily at 2:00 AM

```php
// app/Console/Kernel.php or routes/console.php
$schedule->call(function () {
    app(OpenOverheidSyncService::class)->syncRecent();
})->dailyAt('02:00');
```

**Or use Job:**
```php
$schedule->job(SyncOpenOverheidDocumentsJob::class)->dailyAt('02:00');
```

---

## 5. Typesense Integration

### 5.1 Why Typesense?

- **Fast search**: Sub-millisecond response times
- **Semantic search**: AI-powered relevance
- **Typo tolerance**: Handles spelling mistakes
- **Faceted search**: Filter by document_type, theme, etc.
- **Multi-language**: Supports Dutch language

### 5.2 Typesense Collection Schema

**Collection Name:** `open_overheid_documents`

**Schema:**
```json
{
  "name": "open_overheid_documents",
  "fields": [
    {
      "name": "id",
      "type": "string"
    },
    {
      "name": "external_id",
      "type": "string"
    },
    {
      "name": "title",
      "type": "string",
      "facet": false,
      "index": true
    },
    {
      "name": "description",
      "type": "string",
      "facet": false,
      "index": true
    },
    {
      "name": "content",
      "type": "string",
      "facet": false,
      "index": true
    },
    {
      "name": "publication_date",
      "type": "int64",
      "facet": false,
      "sort": true
    },
    {
      "name": "document_type",
      "type": "string",
      "facet": true
    },
    {
      "name": "category",
      "type": "string",
      "facet": true
    },
    {
      "name": "theme",
      "type": "string",
      "facet": true
    },
    {
      "name": "organisation",
      "type": "string",
      "facet": true
    },
    {
      "name": "url",
      "type": "string",
      "facet": false
    },
    {
      "name": "synced_at",
      "type": "int64",
      "facet": false,
      "sort": true
    }
  ],
  "default_sorting_field": "publication_date"
}
```

### 5.3 Auto-Sync to Typesense

**Service:** `App\Services\Typesense\TypesenseSyncService`

**Strategy:** Sync documents that:
- Are new (not yet in Typesense)
- Have been updated since last Typesense sync
- Have `typesense_synced_at` = NULL or older than document `updated_at`

**Implementation:**
```php
public function syncToTypesense(): void
{
    $documents = OpenOverheidDocument::query()
        ->where(function ($query) {
            $query->whereNull('typesense_synced_at')
                ->orWhereColumn('typesense_synced_at', '<', 'updated_at');
        })
        ->get();

    foreach ($documents as $document) {
        $this->indexDocument($document);
        $document->update(['typesense_synced_at' => now()]);
    }
}
```

### 5.4 Typesense Sync Schedule

**Run after PostgreSQL sync:** Every 24 hours at 2:30 AM

```php
$schedule->call(function () {
    app(TypesenseSyncService::class)->syncToTypesense();
})->dailyAt('02:30');
```

**Or trigger after sync:**
```php
// In OpenOverheidSyncService after sync completes
app(TypesenseSyncService::class)->syncToTypesense();
```

### 5.5 Typesense Search Service

**Service:** `App\Services\Typesense\TypesenseSearchService`

**Usage:**
```php
$results = $typesenseService->search('klimaat', [
    'filter_by' => 'document_type:beslisnota',
    'sort_by' => 'publication_date:desc',
    'per_page' => 20
]);
```

---

## 6. Implementation Guide

### 6.1 Configuration

**File:** `config/open_overheid.php`

```php
<?php

return [
    'base_url' => env('OPEN_OVERHEID_BASE_URL', 'https://open.overheid.nl/overheid/openbaarmakingen/api/v0'),
    'timeout' => env('OPEN_OVERHEID_TIMEOUT', 30),
    
    'sync' => [
        'enabled' => env('OPEN_OVERHEID_SYNC_ENABLED', true),
        'days_back' => env('OPEN_OVERHEID_SYNC_DAYS_BACK', 1), // Sync last N days
    ],
    
    'typesense' => [
        'enabled' => env('TYPESENSE_SYNC_ENABLED', true),
        'api_key' => env('TYPESENSE_API_KEY'),
        'host' => env('TYPESENSE_HOST', 'localhost'),
        'port' => env('TYPESENSE_PORT', 8108),
        'protocol' => env('TYPESENSE_PROTOCOL', 'http'),
    ],
];
```

### 6.2 Sync Service (24-hour incremental)

**File:** `app/Services/OpenOverheid/OpenOverheidSyncService.php`

```php
<?php

namespace App\Services\OpenOverheid;

use App\DataTransferObjects\OpenOverheid\OpenOverheidSearchQuery;
use App\Models\OpenOverheidDocument;
use Illuminate\Support\Facades\Log;

class OpenOverheidSyncService
{
    public function __construct(
        private readonly OpenOverheidSearchService $searchService
    ) {}

    /**
     * Sync recent documents (last 24 hours or configured days)
     */
    public function syncRecent(): void
    {
        $daysBack = config('open_overheid.sync.days_back', 1);
        $from = now()->subDays($daysBack)->format('d-m-Y');
        $to = now()->format('d-m-Y');
        
        $this->syncByDateRange($from, $to);
    }

    /**
     * Sync documents by date range
     */
    public function syncByDateRange(string $from, string $to): void
    {
        Log::info("Starting Open Overheid sync: {$from} to {$to}");

        $page = 1;
        $perPage = 50;
        $totalSynced = 0;
        $totalErrors = 0;
        $hasMorePages = true;

        while ($hasMorePages) {
            try {
                $query = new OpenOverheidSearchQuery(
                    zoektekst: '',
                    page: $page,
                    perPage: $perPage,
                    publicatiedatumVan: $from,
                    publicatiedatumTot: $to,
                );

                $response = $this->searchService->search($query);
                $items = $response['resultaten'] ?? [];

                if (empty($items)) {
                    $hasMorePages = false;
                    break;
                }

                foreach ($items as $item) {
                    try {
                        $document = $item['document'] ?? $item;
                        $externalId = $this->extractExternalId($document);
                        
                        if (!$externalId) {
                            Log::warning('Missing external_id', ['item' => $item]);
                            $totalErrors++;
                            continue;
                        }

                        // Fetch detailed document
                        $detailData = $this->searchService->getDocument($externalId);
                        
                        // Upsert to PostgreSQL
                        $this->upsertDocument($externalId, $detailData);
                        $totalSynced++;

                        if ($totalSynced % 10 === 0) {
                            Log::info("Sync progress: {$totalSynced} documents");
                        }
                    } catch (\Exception $e) {
                        Log::error('Document sync error', [
                            'external_id' => $externalId ?? 'unknown',
                            'error' => $e->getMessage()
                        ]);
                        $totalErrors++;
                    }
                }

                // Check for more pages
                $totalResults = $response['totaal'] ?? 0;
                $hasMorePages = count($items) === $perPage && 
                               (($page * $perPage) < $totalResults);
                $page++;
            } catch (\Exception $e) {
                Log::error('Page sync error', ['page' => $page, 'error' => $e->getMessage()]);
                $hasMorePages = false;
                break;
            }
        }

        Log::info('Sync completed', [
            'total_synced' => $totalSynced,
            'total_errors' => $totalErrors
        ]);
    }

    /**
     * Extract external ID from document data
     */
    protected function extractExternalId(array $document): ?string
    {
        // Try various possible locations
        $id = $document['id'] ?? null;
        
        if ($id) {
            // Remove version suffix (_1, _2, etc.)
            return preg_replace('/_\d+$/', '', $id);
        }
        
        // Try extracting from PID
        $pid = $document['pid'] ?? '';
        if ($pid && preg_match('/\/([^\/]+)$/', $pid, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    /**
     * Upsert document to database
     */
    protected function upsertDocument(string $externalId, array $detailData): void
    {
        $document = $detailData['document'] ?? [];
        $versies = $detailData['versies'] ?? [];
        $classificatie = $document['classificatiecollectie'] ?? [];

        $data = [
            'external_id' => $externalId,
            'title' => $document['titelcollectie']['officieleTitel'] ?? null,
            'description' => $document['omschrijvingen'][0] ?? null,
            'publication_date' => $this->parseDate($versies[0]['openbaarmakingsdatum'] ?? null),
            'document_type' => $classificatie['documentsoorten'][0]['label'] ?? null,
            'category' => $classificatie['informatiecategorieen'][0]['label'] ?? null,
            'theme' => $classificatie['themas'][0]['label'] ?? null,
            'organisation' => $document['verantwoordelijke']['label'] ?? 
                            $document['publisher']['label'] ?? null,
            'metadata' => $detailData,
            'synced_at' => now(),
        ];

        OpenOverheidDocument::updateOrCreate(
            ['external_id' => $externalId],
            $data
        );
    }

    protected function parseDate(?string $date): ?string
    {
        if (!$date) return null;
        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
```

### 6.3 Typesense Sync Service

**File:** `app/Services/Typesense/TypesenseSyncService.php`

```php
<?php

namespace App\Services\Typesense;

use App\Models\OpenOverheidDocument;
use Illuminate\Support\Facades\Log;
use Typesense\Client;

class TypesenseSyncService
{
    protected Client $client;
    protected string $collection = 'open_overheid_documents';

    public function __construct()
    {
        $this->client = new Client([
            'api_key' => config('open_overheid.typesense.api_key'),
            'nodes' => [[
                'host' => config('open_overheid.typesense.host'),
                'port' => config('open_overheid.typesense.port'),
                'protocol' => config('open_overheid.typesense.protocol'),
            ]],
            'connection_timeout_seconds' => 2,
        ]);
    }

    /**
     * Sync all pending documents to Typesense
     */
    public function syncToTypesense(): void
    {
        if (!config('open_overheid.typesense.enabled')) {
            return;
        }

        $documents = OpenOverheidDocument::query()
            ->where(function ($query) {
                $query->whereNull('typesense_synced_at')
                    ->orWhereColumn('typesense_synced_at', '<', 'updated_at');
            })
            ->get();

        Log::info("Syncing {$documents->count()} documents to Typesense");

        foreach ($documents as $document) {
            try {
                $this->indexDocument($document);
                $document->update(['typesense_synced_at' => now()]);
            } catch (\Exception $e) {
                Log::error('Typesense index error', [
                    'external_id' => $document->external_id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info('Typesense sync completed');
    }

    /**
     * Index a single document
     */
    protected function indexDocument(OpenOverheidDocument $document): void
    {
        $data = [
            'id' => (string) $document->id,
            'external_id' => $document->external_id,
            'title' => $document->title ?? '',
            'description' => $document->description ?? '',
            'content' => $document->content ?? '',
            'publication_date' => $document->publication_date 
                ? strtotime($document->publication_date) 
                : 0,
            'document_type' => $document->document_type ?? '',
            'category' => $document->category ?? '',
            'theme' => $document->theme ?? '',
            'organisation' => $document->organisation ?? '',
            'url' => $document->metadata['document']['weblocatie'] ?? '',
            'synced_at' => $document->synced_at 
                ? $document->synced_at->timestamp 
                : 0,
        ];

        try {
            $this->client->collections[$this->collection]->documents->upsert($data);
        } catch (\Exception $e) {
            // Document might not exist, create it
            if (str_contains($e->getMessage(), 'not found')) {
                $this->createCollection();
                $this->client->collections[$this->collection]->documents->upsert($data);
            } else {
                throw $e;
            }
        }
    }

    /**
     * Create Typesense collection if it doesn't exist
     */
    protected function createCollection(): void
    {
        $schema = [
            'name' => $this->collection,
            'fields' => [
                ['name' => 'id', 'type' => 'string'],
                ['name' => 'external_id', 'type' => 'string'],
                ['name' => 'title', 'type' => 'string', 'index' => true],
                ['name' => 'description', 'type' => 'string', 'index' => true],
                ['name' => 'content', 'type' => 'string', 'index' => true],
                ['name' => 'publication_date', 'type' => 'int64', 'sort' => true],
                ['name' => 'document_type', 'type' => 'string', 'facet' => true],
                ['name' => 'category', 'type' => 'string', 'facet' => true],
                ['name' => 'theme', 'type' => 'string', 'facet' => true],
                ['name' => 'organisation', 'type' => 'string', 'facet' => true],
                ['name' => 'url', 'type' => 'string'],
                ['name' => 'synced_at', 'type' => 'int64', 'sort' => true],
            ],
            'default_sorting_field' => 'publication_date',
        ];

        $this->client->collections->create($schema);
    }
}
```

### 6.4 Scheduled Jobs

**File:** `routes/console.php` or `app/Console/Kernel.php`

```php
use App\Services\OpenOverheid\OpenOverheidSyncService;
use App\Services\Typesense\TypesenseSyncService;

// Sync Open Overheid documents daily at 2:00 AM
$schedule->call(function () {
    app(OpenOverheidSyncService::class)->syncRecent();
})->dailyAt('02:00')->name('sync-open-overheid');

// Sync to Typesense daily at 2:30 AM (after PostgreSQL sync)
$schedule->call(function () {
    app(TypesenseSyncService::class)->syncToTypesense();
})->dailyAt('02:30')->name('sync-typesense');
```

### 6.5 Artisan Commands

**File:** `app/Console/Commands/SyncOpenOverheidDocuments.php`

```php
<?php

namespace App\Console\Commands;

use App\Services\OpenOverheid\OpenOverheidSyncService;
use Illuminate\Console\Command;

class SyncOpenOverheidDocuments extends Command
{
    protected $signature = 'open-overheid:sync 
                            {--recent : Sync recent documents (last 24 hours)}
                            {--from= : Start date (DD-MM-YYYY)}
                            {--to= : End date (DD-MM-YYYY)}
                            {--id= : Sync single document by external ID}';

    protected $description = 'Synchronize Open Overheid documents to PostgreSQL';

    public function handle(OpenOverheidSyncService $service): int
    {
        if ($id = $this->option('id')) {
            $this->info("Syncing document: {$id}");
            $service->syncDocument($id);
            $this->info('Done!');
            return self::SUCCESS;
        }

        if ($this->option('recent')) {
            $this->info('Syncing recent documents...');
            $service->syncRecent();
        } elseif ($from = $this->option('from')) {
            $to = $this->option('to') ?? now()->format('d-m-Y');
            $this->info("Syncing documents from {$from} to {$to}...");
            $service->syncByDateRange($from, $to);
        } else {
            $this->info('Syncing recent documents (default)...');
            $service->syncRecent();
        }

        $this->info('Sync completed!');
        return self::SUCCESS;
    }
}
```

**File:** `app/Console/Commands/SyncTypesense.php`

```php
<?php

namespace App\Console\Commands;

use App\Services\Typesense\TypesenseSyncService;
use Illuminate\Console\Command;

class SyncTypesense extends Command
{
    protected $signature = 'typesense:sync';
    protected $description = 'Sync PostgreSQL documents to Typesense';

    public function handle(TypesenseSyncService $service): int
    {
        $this->info('Syncing documents to Typesense...');
        $service->syncToTypesense();
        $this->info('Done!');
        return self::SUCCESS;
    }
}
```

---

## 7. AI Tool Guidelines

### 7.1 For Cursor / AI Assistants

When working with this integration:

1. **Use documented endpoints only:**
   - Search: `GET {base_url}/zoek`
   - Detail: `GET {base_url}/zoek/{id}`

2. **Respect parameter constraints:**
   - `aantalResultaten` MUST be `10`, `20`, or `50`
   - Date format: `DD-MM-YYYY`
   - Use `start` for pagination, not `page`

3. **Follow naming conventions:**
   - Services: `App\Services\OpenOverheid\*`
   - DTOs: `App\DataTransferObjects\OpenOverheid\*`
   - Models: `App\Models\OpenOverheidDocument`

4. **Sync strategy:**
   - Always use `syncRecent()` for daily sync
   - Fetch detailed documents, not just search results
   - Update `typesense_synced_at` after Typesense sync

5. **Error handling:**
   - Log errors but continue processing
   - Don't fail entire sync for single document errors
   - Track sync statistics

6. **Typesense integration:**
   - Always check if collection exists before indexing
   - Use upsert operations (update if exists)
   - Track sync status in database

### 7.2 Common Patterns

**Extract External ID:**
```php
// From search result
$externalId = preg_replace('/_\d+$/', '', $document['id']);

// From PID URL
preg_match('/\/([^\/]+)$/', $pid, $matches);
$externalId = $matches[1];
```

**Parse Dates:**
```php
// API format: YYYY-MM-DD or DD-MM-YYYY
$date = \Carbon\Carbon::parse($dateString)->format('Y-m-d');
```

**Upsert Document:**
```php
OpenOverheidDocument::updateOrCreate(
    ['external_id' => $externalId],
    $data
);
```

---

## 8. Quick Reference

### 8.1 Environment Variables

```env
OPEN_OVERHEID_BASE_URL=https://open.overheid.nl/overheid/openbaarmakingen/api/v0
OPEN_OVERHEID_TIMEOUT=30
OPEN_OVERHEID_SYNC_ENABLED=true
OPEN_OVERHEID_SYNC_DAYS_BACK=1

TYPESENSE_SYNC_ENABLED=true
TYPESENSE_API_KEY=your-api-key
TYPESENSE_HOST=localhost
TYPESENSE_PORT=8108
TYPESENSE_PROTOCOL=http
```

### 8.2 Commands

```bash
# Sync recent documents
php artisan open-overheid:sync --recent

# Sync date range
php artisan open-overheid:sync --from=01-12-2025 --to=10-12-2025

# Sync single document
php artisan open-overheid:sync --id=oep-abc123

# Sync to Typesense
php artisan typesense:sync
```

### 8.3 Response Structure Summary

**Search Response:**
- `totaal`: integer
- `resultaten`: array of hits
- Each hit: `document` object + file metadata

**Detail Response:**
- `document`: full metadata
- `versies`: array with file attachments
- `plooiIntern`: technical metadata

---

**End of Guide**


