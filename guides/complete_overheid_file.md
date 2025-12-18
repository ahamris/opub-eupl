# Open Overheid Complete Integration Guide

**Complete guide for Open Overheid API integration with PostgreSQL and Typesense search**

---

## Table of Contents

1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Quick Start](#quick-start)
4. [Installation & Setup](#installation--setup)
5. [Database Schema](#database-schema)
6. [Sync Command](#sync-command)
7. [Automatic Sync System](#automatic-sync-system)
8. [Services & Components](#services--components)
9. [API Endpoints](#api-endpoints)
10. [Frontend Search UI](#frontend-search-ui)
11. [Monitoring & Troubleshooting](#monitoring--troubleshooting)
12. [Best Practices](#best-practices)
13. [Production Deployment](#production-deployment)

---

## Overview

### What This System Does

This integration provides a complete solution for syncing and searching Open Overheid documents (18M+ documents) with:

- **PostgreSQL** as the primary data store (source of truth)
- **Typesense** for fast, semantic, and full-text search
- **AI-powered search** using Google Gemini
- **Automatic synchronization** every minute
- **One command** to sync everything

### Key Features

✅ **Single Command Sync** - `php artisan open-overheid:sync`  
✅ **Automatic Typesense Sync** - Runs every minute via scheduler  
✅ **Efficient Processing** - Only syncs changed/new documents  
✅ **Queue-Based** - Non-blocking background processing  
✅ **AI Search** - Natural language queries with Gemini  
✅ **Faceted Search** - Filter by theme, organisation, type, category  
✅ **Autocomplete** - Instant search suggestions  
✅ **Trackable** - Full sync status tracking  

---

## Architecture

### Data Flow

```
┌─────────────────────┐
│ Open Overheid API   │
│ (18M+ documents)    │
└──────────┬──────────┘
           │
           │ (Manual sync via command)
           ▼
┌─────────────────────┐
│ PostgreSQL Database │
│ (Source of Truth)   │
│ - Full document data│
│ - Metadata (JSONB)  │
│ - Sync timestamps   │
└──────────┬──────────┘
           │
           │ (Automatic sync every 1 minute)
           ▼
┌─────────────────────┐
│ Typesense Search    │
│ - Fast search       │
│ - Semantic search   │
│ - Faceted filters   │
└──────────┬──────────┘
           │
           │ (Search queries)
           ▼
┌─────────────────────┐
│ Frontend Search UI  │
│ - Standard search   │
│ - AI Q&A            │
│ - Autocomplete      │
└─────────────────────┘
```

### Components

1. **OpenOverheidApiService** - Fetches data from Open Overheid API
2. **OpenOverheidSyncService** - Syncs API → PostgreSQL
3. **PostgreSQLToTypesenseService** - Syncs PostgreSQL → Typesense
4. **OpenOverheidAISearchService** - AI-powered search with Gemini
5. **SyncDocumentToTypesense** - Queue job for background sync
6. **SyncOpenOverheid** - Unified sync command

---

## Quick Start

### 1. Run Migration

```bash
php artisan migrate
```

Creates the `open_overheid_documents` table with all required fields.

### 2. Run Sync Command

```bash
# Sync last 7 days from API → PostgreSQL → Typesense
php artisan open-overheid:sync --recent --days=7
```

**What happens:**
- ✅ Fetches documents from Open Overheid API (last 7 days)
- ✅ Stores them in PostgreSQL
- ✅ Dispatches Typesense sync job immediately
- ✅ Scheduler will continue syncing every minute

### 3. Start Scheduler (Development)

```bash
php artisan schedule:work
```

This runs the scheduler in the foreground. Typesense sync will run every minute automatically.

### 4. Access Search UI

Open in browser: **http://localhost/open-overheid**

---

## Installation & Setup

### Prerequisites

- Laravel 12.x
- PostgreSQL database
- Typesense running (Docker)
- PHP 8.2+

### Step 1: Install Dependencies

```bash
# Typesense PHP client (if not already installed)
composer require typesense/typesense-php
```

### Step 2: Configure Environment

Add to `.env`:

```env
# Open Overheid API
OPEN_OVERHEID_BASE_URL=https://open.overheid.nl/overheid/openbaarmakingen/api/v0
OPEN_OVERHEID_TYPESENSE_COLLECTION=oo

# Typesense
TYPESENSE_HOST=localhost
TYPESENSE_PORT=8108
TYPESENSE_API_KEY=xyz
TYPESENSE_PROTOCOL=http

# Gemini AI
GEMINI_API_KEY=AIzaSyAM8nIe9YghjEIYDNlOuKM5IBPIdmrkYuE
```

### Step 3: Run Migration

```bash
php artisan migrate
```

### Step 4: Start Typesense (Docker)

```bash
docker-compose up -d
```

### Step 5: Verify Setup

```bash
# Test API connection
php artisan open-overheid:test-api

# Check Typesense health
curl http://localhost:8108/health
```

---

## Database Schema

### Table: `open_overheid_documents`

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
    publisher VARCHAR(255),
    url VARCHAR(255),
    weblocatie VARCHAR(255),
    file_size VARCHAR(255),
    page_count INTEGER,
    file_type VARCHAR(255),
    metadata JSONB,
    synced_at TIMESTAMP,              -- When synced from API
    typesense_synced_at TIMESTAMP,    -- When synced to Typesense
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Indexes
CREATE INDEX idx_external_id ON open_overheid_documents(external_id);
CREATE INDEX idx_publication_date ON open_overheid_documents(publication_date);
CREATE INDEX idx_synced_at ON open_overheid_documents(synced_at);
CREATE INDEX idx_typesense_synced_at ON open_overheid_documents(typesense_synced_at);
CREATE INDEX idx_document_type ON open_overheid_documents(document_type);
CREATE INDEX idx_category ON open_overheid_documents(category);
CREATE INDEX idx_theme ON open_overheid_documents(theme);
CREATE INDEX idx_organisation ON open_overheid_documents(organisation);

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

### Key Fields

- **`external_id`** - Unique identifier from API (e.g., `oep-abc123`)
- **`metadata`** - Full JSONB response from API detail endpoint
- **`synced_at`** - Timestamp when synced from API
- **`typesense_synced_at`** - Timestamp when synced to Typesense (NULL = needs sync)

---

## Sync Command

### Command: `php artisan open-overheid:sync`

**Unified command that syncs API → PostgreSQL → Typesense**

### Options

| Option | Description | Default | Example |
|--------|-------------|---------|---------|
| `--recent` | Sync recent documents | - | `--recent --days=7` |
| `--days=N` | Number of days back | 7 | `--days=30` |
| `--from=DD-MM-YYYY` | Start date | - | `--from=01-12-2025` |
| `--to=DD-MM-YYYY` | End date | Today | `--to=10-12-2025` |
| `--skip-typesense` | Skip immediate Typesense sync | false | `--skip-typesense` |

### Examples

#### Sync Last 7 Days (Default)

```bash
php artisan open-overheid:sync --recent --days=7
```

#### Sync Last 30 Days

```bash
php artisan open-overheid:sync --recent --days=30
```

#### Sync Specific Date Range

```bash
php artisan open-overheid:sync --from=01-12-2025 --to=10-12-2025
```

#### Skip Immediate Typesense Sync

```bash
php artisan open-overheid:sync --recent --skip-typesense
```

Useful when you want to let the scheduler handle all Typesense syncing.

### Command Output

```
🚀 Starting Open Overheid sync...

📥 Step 1: Syncing from API to PostgreSQL...
   Fetching last 7 days...
   ✓ Synced 150 documents to PostgreSQL

📤 Step 2: Syncing PostgreSQL → Typesense...
   ✓ Typesense sync job dispatched
   ℹ️  Scheduled sync runs every minute automatically

✅ Sync completed successfully!

💡 Tip: Typesense sync runs automatically every minute via scheduler
   Run: php artisan schedule:work (in development)
   Or set up cron: * * * * * cd /path && php artisan schedule:run
```

---

## Automatic Sync System

### How It Works

1. **Manual Sync** - Run `php artisan open-overheid:sync` to sync API → PostgreSQL
2. **Automatic Sync** - Scheduler runs every minute to sync PostgreSQL → Typesense
3. **Efficient** - Only syncs documents where `typesense_synced_at IS NULL` OR `typesense_synced_at < updated_at`

### Scheduler Configuration

Located in `routes/console.php`:

```php
// Schedule Typesense sync every minute
Schedule::job(SyncDocumentToTypesense::class)
    ->everyMinute()
    ->withoutOverlapping();
```

### Starting the Scheduler

#### Development

```bash
php artisan schedule:work
```

Runs the scheduler in the foreground. Keep this running.

#### Production

Add to crontab:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### Queue Job: `SyncDocumentToTypesense`

- **Runs**: Every minute via scheduler
- **Processes**: Up to 100 documents per run (configurable)
- **Retries**: 3 times on failure
- **Backoff**: 60 seconds between retries
- **Status**: Updates `typesense_synced_at` timestamp

### Sync Logic

```php
// Documents that need Typesense sync
OpenOverheidDocument::needsTypesenseSync()
    ->whereNull('typesense_synced_at')
    ->orWhereColumn('typesense_synced_at', '<', 'updated_at')
    ->limit(100)
    ->get();
```

---

## Services & Components

### 1. OpenOverheidApiService

**Location**: `app/Services/OpenOverheid/OpenOverheidApiService.php`

**Purpose**: Fetches data from Open Overheid API

**Key Methods**:
- `search(array $params)` - Search for documents
- `getDocument(string $id)` - Get detailed document
- `searchPaginated(array $params)` - Paginated search (generator)
- `extractExternalId(array $document)` - Extract external ID

### 2. OpenOverheidSyncService

**Location**: `app/Services/OpenOverheid/OpenOverheidSyncService.php`

**Purpose**: Syncs API → PostgreSQL

**Key Methods**:
- `syncRecent(int $daysBack)` - Sync recent documents
- `syncByDateRange(string $from, string $to)` - Sync by date range
- `storeDocument(array $apiData)` - Store document in PostgreSQL

**Process**:
1. Fetches documents from API
2. Transforms API response to database format
3. Uses `updateOrCreate` with `external_id` as unique key
4. Updates `synced_at` timestamp

### 3. PostgreSQLToTypesenseService

**Location**: `app/Services/OpenOverheid/PostgreSQLToTypesenseService.php`

**Purpose**: Syncs PostgreSQL → Typesense

**Key Methods**:
- `syncPending(int $limit)` - Sync pending documents
- `ensureCollection()` - Ensure Typesense collection exists
- `createCollection(string $name)` - Create Typesense collection
- `indexDocument(OpenOverheidDocument $document)` - Index single document

**Process**:
1. Queries documents needing sync
2. Transforms PostgreSQL data to Typesense format
3. Upserts to Typesense collection `oo`
4. Updates `typesense_synced_at` timestamp

### 4. OpenOverheidAISearchService

**Location**: `app/Services/OpenOverheid/OpenOverheidAISearchService.php`

**Purpose**: AI-powered search with Gemini integration

**Key Methods**:
- `askAI(string $question)` - Natural language Q&A
- `searchWithParams(string $query, array $filters)` - Standard search
- `getAutocompleteSuggestions(string $query, int $limit)` - Autocomplete
- `getFacets()` - Get available facets

### 5. GeminiService

**Location**: `app/Services/AI/GeminiService.php`

**Purpose**: Google Gemini AI integration

**Key Methods**:
- `generateText(string $prompt)` - Generate text
- `analyzeQuery(string $query)` - Analyze search query
- `generateAnswer(string $question, array $context)` - Generate answer

### 6. TypesenseService

**Location**: `app/Services/TypesenseService.php`

**Purpose**: Typesense operations wrapper

**Key Methods**:
- `client()` - Get Typesense client
- `search(string $collection, string $query, array $params)` - Search
- `createCollection(string $name, array $schema)` - Create collection
- `indexDocument(string $collection, array $document)` - Index document

### 7. OpenOverheidDocument Model

**Location**: `app/Models/OpenOverheidDocument.php`

**Purpose**: Eloquent model for documents

**Scopes**:
- `needsTypesenseSync()` - Documents needing Typesense sync
- `recent(int $days)` - Recent documents
- `byDocumentType(string $type)` - Filter by document type
- `byTheme(string $theme)` - Filter by theme
- `byOrganisation(string $organisation)` - Filter by organisation
- `byCategory(string $category)` - Filter by category

**Methods**:
- `needsTypesenseSync()` - Check if needs sync
- `markTypesenseSynced()` - Mark as synced

---

## API Endpoints

### Base URL

```
/api/open-overheid
```

### Endpoints

#### 1. AI Q&A Search

```http
POST /api/open-overheid/ask
Content-Type: application/json

{
  "question": "Hoeveel zaken zijn er van VVD betreffende stikstof?",
  "per_page": 10
}
```

**Response**:
```json
{
  "question": "Hoeveel zaken zijn er van VVD betreffende stikstof?",
  "answer": "Op basis van de beschikbare documenten zijn er X zaken...",
  "sources": [
    {
      "title": "Document Title",
      "url": "https://open.overheid.nl/documenten/...",
      "organisation": "VVD",
      "theme": "stikstof",
      "publication_date": 1733788800,
      "relevance_score": 95
    }
  ],
  "result_count": 10,
  "search_query": "stikstof VVD",
  "search_filters": {
    "organisation": "VVD",
    "theme": "stikstof"
  }
}
```

#### 2. Standard Search

```http
GET /api/open-overheid/search?q=klimaat&theme=stikstof&organisation=VVD&per_page=20
```

**Query Parameters**:
- `q` - Search query
- `theme` - Filter by theme
- `organisation` - Filter by organisation
- `document_type` - Filter by document type
- `category` - Filter by category
- `per_page` - Results per page (default: 20)
- `page` - Page number (default: 1)

**Response**:
```json
{
  "hits": [
    {
      "document": {
        "id": "oep-abc123",
        "title": "Document Title",
        "description": "Description...",
        "organisation": "VVD",
        "theme": "stikstof"
      },
      "highlights": []
    }
  ],
  "found": 150,
  "page": 1,
  "facet_counts": {
    "theme": [
      {"value": "stikstof", "count": 50}
    ],
    "organisation": [
      {"value": "VVD", "count": 30}
    ]
  }
}
```

#### 3. Autocomplete

```http
GET /api/open-overheid/autocomplete?q=stikstof&limit=5
```

**Response**:
```json
{
  "suggestions": [
    {
      "text": "Stikstofbeleid en maatregelen",
      "theme": "stikstof",
      "organisation": "Ministerie van Landbouw"
    }
  ]
}
```

#### 4. Get Facets

```http
GET /api/open-overheid/facets
```

**Response**:
```json
{
  "theme": {
    "counts": [
      {"value": "stikstof", "count": 500},
      {"value": "klimaat", "count": 300}
    ]
  },
  "organisation": {
    "counts": [
      {"value": "VVD", "count": 200},
      {"value": "PvdA", "count": 150}
    ]
  }
}
```

#### 5. Count Documents

```http
GET /api/open-overheid/count?organisation=VVD&theme=stikstof
```

**Response**:
```json
{
  "count": 247,
  "filters": {
    "organisation": "VVD",
    "theme": "stikstof"
  }
}
```

---

## Frontend Search UI

### Route

```
/open-overheid
```

### Features

- **Search Mode** - Standard search with filters
- **Ask AI Mode** - Natural language questions
- **Autocomplete** - Instant suggestions as you type
- **Faceted Filters** - Theme, organisation, document type, category
- **Results Display** - With metadata and badges
- **AI Answer Display** - With sources and citations

### Component

**Location**: `resources/views/pages/open-overheid-search.blade.php`

**Type**: Livewire Volt component

**Flux UI Components Used**:
- `flux:input` - Search input
- `flux:textarea` - AI question input
- `flux:button` - Action buttons
- `flux:badge` - Document tags
- `flux:checkbox` - Filter checkboxes
- `flux:heading` - Headings
- `flux:text` - Text content

### Usage Examples

#### Standard Search

1. Enter search term: `klimaat`
2. Use filters: Select theme `stikstof`, organisation `VVD`
3. Click "Zoeken"
4. View results with metadata

#### AI Q&A

1. Click "Vraag AI" tab
2. Enter question: `Hoeveel zaken zijn er van VVD betreffende stikstof?`
3. Click "Vraag stellen"
4. View AI answer with sources

#### Autocomplete

1. Start typing in search box
2. See suggestions appear automatically
3. Click suggestion or use arrow keys to navigate

---

## Monitoring & Troubleshooting

### Check Sync Status

#### Via Tinker

```php
php artisan tinker

// Documents needing Typesense sync
>>> App\Models\OpenOverheidDocument::needsTypesenseSync()->count()

// Total documents
>>> App\Models\OpenOverheidDocument::count()

// Recently synced
>>> App\Models\OpenOverheidDocument::where('synced_at', '>', now()->subHour())->count()

// Documents by theme
>>> App\Models\OpenOverheidDocument::byTheme('stikstof')->count()
```

#### Via Logs

```bash
# View sync logs
tail -f storage/logs/laravel.log | grep "Open Overheid\|Typesense"

# View scheduler logs
tail -f storage/logs/laravel.log | grep "schedule"

# View queue logs
tail -f storage/logs/laravel.log | grep "queue"
```

### Check Queue Status

```bash
# View queue jobs
php artisan queue:work

# View failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Clear failed jobs
php artisan queue:flush
```

### Check Scheduler Status

```bash
# List scheduled tasks
php artisan schedule:list

# Test scheduler (runs once)
php artisan schedule:run

# Start scheduler (development)
php artisan schedule:work
```

### Check Typesense Collection

```bash
# Count documents in Typesense
curl -H "X-TYPESENSE-API-KEY: xyz" \
  "http://localhost:8108/collections/oo/documents/search?q=*&per_page=1"

# Get collection info
curl -H "X-TYPESENSE-API-KEY: xyz" \
  "http://localhost:8108/collections/oo"

# Health check
curl http://localhost:8108/health
```

### Common Issues

#### Scheduler Not Running

**Problem**: Typesense sync not happening automatically

**Solution**:
```bash
# Check if scheduler is running
php artisan schedule:list

# Start scheduler (development)
php artisan schedule:work

# Check cron (production)
crontab -l
```

#### Queue Jobs Not Processing

**Problem**: Jobs stuck in queue

**Solution**:
```bash
# Start queue worker
php artisan queue:work

# Clear failed jobs
php artisan queue:flush

# Retry failed jobs
php artisan queue:retry all
```

#### Sync Too Slow

**Problem**: Sync taking too long

**Solutions**:
- Increase batch size in `syncPending()` (default: 100)
- Run multiple queue workers
- Check database indexes are present
- Monitor Typesense performance

#### Duplicate Documents

**Problem**: Same document synced multiple times

**Solution**: 
- `external_id` is unique in database
- `updateOrCreate` prevents duplicates
- Check for API returning duplicate IDs

#### API Connection Errors

**Problem**: Cannot connect to Open Overheid API

**Solution**:
```bash
# Test API connection
php artisan open-overheid:test-api

# Check network connectivity
curl https://open.overheid.nl/overheid/openbaarmakingen/api/v0/zoek?start=0&aantalResultaten=10
```

---

## Best Practices

### 1. Sync Frequency

- **API → PostgreSQL**: Run manually when needed (daily/weekly)
- **PostgreSQL → Typesense**: Automatic every minute (via scheduler)

### 2. Batch Processing

- Process documents in batches (50-100 per run)
- Use queue jobs for background processing
- Monitor queue size and processing time

### 3. Error Handling

- Always wrap sync operations in try-catch
- Log errors with context
- Retry failed operations automatically
- Alert on repeated failures

### 4. Performance

- Use database indexes
- Cache frequent queries
- Optimize Typesense collection schema
- Monitor query performance

### 5. Data Consistency

- Use transactions for critical operations
- Validate data before syncing
- Handle API rate limits
- Track sync status accurately

### 6. Monitoring

- Log all sync operations
- Track sync metrics (count, duration, errors)
- Monitor queue size
- Alert on failures

---

## Production Deployment

### 1. Environment Configuration

```env
# Production settings
APP_ENV=production
APP_DEBUG=false

# Database
DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password

# Typesense
TYPESENSE_HOST=your-typesense-host
TYPESENSE_PORT=8108
TYPESENSE_API_KEY=your-secure-api-key

# Queue
QUEUE_CONNECTION=database  # or redis
```

### 2. Set Up Cron

Add to crontab:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Set Up Queue Worker

Use supervisor or systemd to run queue worker:

**Supervisor config** (`/etc/supervisor/conf.d/laravel-worker.conf`):

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path-to-project/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path-to-project/storage/logs/worker.log
stopwaitsecs=3600
```

### 4. Set Up Logging

Configure logging in `config/logging.php`:

```php
'channels' => [
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'info'),
        'days' => 14,
    ],
],
```

### 5. Monitor Performance

- Set up application monitoring (e.g., Laravel Telescope)
- Monitor queue size and processing time
- Track API rate limits
- Monitor database performance

### 6. Backup Strategy

- Regular PostgreSQL backups
- Typesense data persistence (Docker volumes)
- Backup sync logs and configuration

---

## File Structure

```
app/
├── Console/
│   └── Commands/
│       ├── SyncOpenOverheid.php          # Unified sync command
│       └── SyncOpenOverheidToTypesense.php  # Direct sync (legacy)
├── Http/
│   └── Controllers/
│       └── Api/
│           └── OpenOverheidSearchController.php
├── Jobs/
│   └── SyncDocumentToTypesense.php      # Queue job for Typesense sync
├── Models/
│   └── OpenOverheidDocument.php         # Eloquent model
└── Services/
    ├── AI/
    │   └── GeminiService.php            # Gemini AI integration
    ├── OpenOverheid/
    │   ├── OpenOverheidApiService.php   # API client
    │   ├── OpenOverheidSyncService.php  # API → PostgreSQL
    │   ├── PostgreSQLToTypesenseService.php  # PostgreSQL → Typesense
    │   └── OpenOverheidAISearchService.php  # AI search
    └── TypesenseService.php             # Typesense wrapper

database/
└── migrations/
    └── 2025_12_18_004354_create_open_overheid_documents_table.php

resources/
├── views/
│   └── pages/
│       └── open-overheid-search.blade.php  # Search UI (Volt)
└── js/
    └── open-overheid-search.js            # JavaScript enhancements

routes/
├── api.php                                # API routes
├── web.php                                # Web routes
└── console.php                            # Scheduler configuration

config/
├── open_overheid.php                      # Open Overheid config
├── typesense.php                          # Typesense config
└── gemini.php                             # Gemini config
```

---

## Summary

### What You Get

✅ **One Command** - `php artisan open-overheid:sync`  
✅ **Automatic Sync** - PostgreSQL → Typesense every minute  
✅ **Efficient** - Only syncs changed/new documents  
✅ **Queue-Based** - Non-blocking background processing  
✅ **AI Search** - Natural language queries  
✅ **Complete UI** - Search, filters, autocomplete, AI Q&A  
✅ **Trackable** - Full sync status tracking  
✅ **Production Ready** - Queue workers, scheduler, monitoring  

### Quick Commands

```bash
# Sync everything
php artisan open-overheid:sync --recent --days=7

# Start scheduler (dev)
php artisan schedule:work

# Check status
php artisan tinker
>>> App\Models\OpenOverheidDocument::needsTypesenseSync()->count()

# View logs
tail -f storage/logs/laravel.log | grep "Open Overheid\|Typesense"
```

---

**Everything is automated! Run the sync command once, and Typesense will stay in sync automatically every minute!** 🚀

