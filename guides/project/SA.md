# Solution Architecture Document

**Open Overheid Document Search Application**

Version: 1.0  
Date: 2025-12-20  
Framework: Laravel 12 (PHP 8.2+)

---

## Table of Contents

1. [Overview](#1-overview)
2. [System Architecture](#2-system-architecture)
3. [Technology Stack](#3-technology-stack)
4. [Application Layers](#4-application-layers)
5. [Data Flow](#5-data-flow)
6. [Database Schema](#6-database-schema)
7. [API Integration](#7-api-integration)
8. [Search Architecture](#8-search-architecture)
9. [Synchronization Strategy](#9-synchronization-strategy)
10. [Frontend Architecture](#10-frontend-architecture)
11. [Security & Performance](#11-security--performance)
12. [Deployment & Infrastructure](#12-deployment--infrastructure)

---

## 1. Overview

### 1.1 Purpose

This application provides a search interface for Dutch government documents (Open Overheid / Wet Open Overheid - Woo). It integrates with the Open Overheid API to discover, index, and search through publicly available government documents.

### 1.2 Key Features

- **Document Search**: Full-text search with filters (document type, category, theme, organisation, date range)
- **Document Detail View**: Comprehensive metadata display with JSON/XML export
- **Local Database Sync**: PostgreSQL storage for offline capabilities
- **Typesense Integration**: Advanced search engine for fast, semantic search
- **API Proxy**: RESTful API endpoints for programmatic access
- **Scheduled Synchronization**: Automated daily sync of recent documents

### 1.3 Goals

- Enable fast, local search of government documents
- Reduce dependency on external API for search operations
- Provide rich metadata display and export capabilities
- Support both web interface and API access

---

## 2. System Architecture

### 2.1 High-Level Architecture

```
┌─────────────────┐
│   Web Browser   │
│   (Frontend)    │
└────────┬────────┘
         │
         ▼
┌─────────────────────────────────────┐
│      Laravel Application            │
│  ┌───────────────────────────────┐  │
│  │   Controllers (MVC Layer)     │  │
│  └───────────┬───────────────────┘  │
│              │                       │
│  ┌───────────▼───────────────────┐  │
│  │   Services Layer              │  │
│  │  - OpenOverheidSearchService  │  │
│  │  - OpenOverheidSyncService    │  │
│  │  - OpenOverheidLocalSearch    │  │
│  │  - TypesenseSyncService       │  │
│  └───────────┬───────────────────┘  │
└──────────────┼──────────────────────┘
               │
    ┌──────────┴──────────┐
    │                     │
    ▼                     ▼
┌──────────┐      ┌──────────────┐
│PostgreSQL│      │  Typesense   │
│ Database │      │ Search Engine│
└────┬─────┘      └──────────────┘
     │
     │ Sync
     ▼
┌─────────────────┐
│ Open Overheid   │
│      API        │
└─────────────────┘
```

### 2.2 Component Diagram

```
┌─────────────────────────────────────────────────────────┐
│                    Presentation Layer                    │
├─────────────────────────────────────────────────────────┤
│ • Blade Templates (zoek.blade.php, zoekresultaten.blade │
│   .php, detail.blade.php)                               │
│ • CSS/JS Assets (openoverheid.css, app.js)              │
└─────────────────────────────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────┐
│                    Controller Layer                      │
├─────────────────────────────────────────────────────────┤
│ • SearchController (searchPage, searchResults, index)   │
│ • DocumentController (show with JSON/XML export)        │
└─────────────────────────────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────┐
│                    Service Layer                         │
├─────────────────────────────────────────────────────────┤
│ • OpenOverheidSearchService (Remote API calls)          │
│ • OpenOverheidSyncService (Data synchronization)        │
│ • OpenOverheidLocalSearchService (PostgreSQL search)    │
│ • TypesenseSyncService (Search engine sync)             │
│ • TypesenseSearchService (Search engine queries)        │
└─────────────────────────────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────┐
│                    Data Layer                            │
├─────────────────────────────────────────────────────────┤
│ • Models (OpenOverheidDocument)                         │
│ • DTOs (OpenOverheidSearchQuery, OpenOverheidSearchResult)│
│ • Database (PostgreSQL)                                 │
│ • Search Engine (Typesense)                             │
└─────────────────────────────────────────────────────────┘
```

---

## 3. Technology Stack

### 3.1 Backend

- **Framework**: Laravel 12.x
- **PHP Version**: 8.2+
- **Database**: PostgreSQL (primary), SQLite (development)
- **Search Engine**: Typesense
- **HTTP Client**: Laravel HTTP Facade (Guzzle)
- **Queue System**: Laravel Queue (for async sync jobs)
- **Task Scheduler**: Laravel Scheduler (Cron)

### 3.2 Frontend

- **Template Engine**: Blade
- **CSS**: Custom CSS (openoverheid.css)
- **JavaScript**: Vanilla JS
- **Build Tool**: Vite
- **Styling**: Custom implementation following Overheid.nl design guidelines

### 3.3 Dependencies

- `typesense/typesense-php`: Typesense PHP client
- `laravel/framework`: Laravel core framework
- `laravel/tinker`: REPL for development

---

## 4. Application Layers

### 4.1 Presentation Layer

**Location**: `resources/views/`

**Components**:
- `zoek.blade.php`: Main search page
- `zoekresultaten.blade.php`: Search results page with filters
- `detail.blade.php`: Document detail page with metadata and JSON/XML export

**Responsibilities**:
- Render user interface
- Handle user interactions
- Display search results and document details
- Provide export functionality (JSON/XML)

### 4.2 Controller Layer

**Location**: `app/Http/Controllers/OpenOverheid/`

**Components**:

#### SearchController
- `searchPage()`: Display search form
- `searchResults()`: Handle search form submission and display results
- `index()`: API endpoint for programmatic search

#### DocumentController
- `show($id)`: Display document detail page
- Supports `?format=json` and `?format=xml` query parameters for export

**Responsibilities**:
- Request validation
- Route requests to appropriate services
- Format responses (view or JSON)
- Error handling

### 4.3 Service Layer

**Location**: `app/Services/`

#### OpenOverheidSearchService
**File**: `app/Services/OpenOverheid/OpenOverheidSearchService.php`

**Methods**:
- `search(OpenOverheidSearchQuery $query): array`: Query remote API
- `getDocument(string $id): array`: Fetch single document from API

**Responsibilities**:
- Build API request URLs
- Handle HTTP requests to Open Overheid API
- Error handling and logging
- Response parsing

#### OpenOverheidSyncService
**File**: `app/Services/OpenOverheid/OpenOverheidSyncService.php`

**Methods**:
- `syncRecent()`: Sync documents from last 24 hours
- `syncByDateRange(string $from, string $to)`: Sync documents in date range
- `syncAll()`: Full sync (all available documents)
- `syncDocument(string $externalId)`: Sync single document

**Responsibilities**:
- Fetch documents from API
- Transform API responses to database format
- Upsert operations (insert/update)
- Track sync timestamps

#### OpenOverheidLocalSearchService
**File**: `app/Services/OpenOverheid/OpenOverheidLocalSearchService.php`

**Methods**:
- `search(OpenOverheidSearchQuery $query): array`: Search local PostgreSQL database

**Responsibilities**:
- PostgreSQL full-text search queries
- Filter application (document type, category, theme, etc.)
- Pagination
- Result formatting

#### TypesenseSyncService
**File**: `app/Services/Typesense/TypesenseSyncService.php`

**Methods**:
- `syncToTypesense()`: Sync pending documents to Typesense
- `createOrUpdateCollection()`: Manage Typesense collection schema
- `indexDocument(OpenOverheidDocument $document)`: Index single document

**Responsibilities**:
- Maintain Typesense collection schema
- Sync documents from PostgreSQL to Typesense
- Track sync status (typesense_synced_at)
- Handle sync errors

#### TypesenseSearchService
**File**: `app/Services/Typesense/TypesenseSearchService.php`

**Methods**:
- `search(OpenOverheidSearchQuery $query): array`: Query Typesense

**Responsibilities**:
- Build Typesense search queries
- Handle faceted search
- Result formatting

### 4.4 Data Layer

#### Models

**OpenOverheidDocument**
**File**: `app/Models/OpenOverheidDocument.php`

**Attributes**:
- `id`: Primary key
- `external_id`: Unique identifier from API (e.g., "oep-...")
- `title`: Document title
- `description`: Document description
- `content`: Full document content (when available)
- `publication_date`: Publication date
- `document_type`: Document type (documentsoort)
- `category`: Information category (informatiecategorie)
- `theme`: Theme (thema)
- `organisation`: Publishing organisation
- `metadata`: Full API response (JSONB)
- `synced_at`: Last sync timestamp
- `typesense_synced_at`: Last Typesense sync timestamp
- `created_at`, `updated_at`: Timestamps

**Scopes**:
- `whereFullText()`: PostgreSQL full-text search
- `dateRange()`: Filter by publication date range
- `byDocumentType()`: Filter by document type
- `byCategory()`: Filter by category
- `byTheme()`: Filter by theme
- `byOrganisation()`: Filter by organisation

#### Data Transfer Objects (DTOs)

**OpenOverheidSearchQuery**
**File**: `app/DataTransferObjects/OpenOverheid/OpenOverheidSearchQuery.php`

**Properties**:
- `zoektekst`: Search text
- `page`: Page number
- `perPage`: Results per page (10, 20, or 50)
- `publicatiedatumVan`: Start date (DD-MM-YYYY)
- `publicatiedatumTot`: End date (DD-MM-YYYY)
- `documentsoort`: Document type filter
- `informatiecategorie`: Information category filter
- `thema`: Theme filter
- `organisatie`: Organisation filter
- `sort`: Sort order (relevance, publication_date, modified_date)

**OpenOverheidSearchResult**
**File**: `app/DataTransferObjects/OpenOverheid/OpenOverheidSearchResult.php`

**Properties**:
- `items`: Array of documents
- `total`: Total number of results
- `page`: Current page
- `perPage`: Results per page
- `hasNextPage`: Boolean
- `hasPreviousPage`: Boolean

---

## 5. Data Flow

### 5.1 Search Flow

```
User Input
    │
    ▼
SearchController::searchResults()
    │
    ▼
Validate Request
    │
    ▼
Create OpenOverheidSearchQuery DTO
    │
    ├─────────────────────────────────┐
    │                                 │
    ▼                                 ▼
Local Search (Default)          Remote API Search (Fallback)
OpenOverheidLocalSearchService  OpenOverheidSearchService
    │                                 │
    ▼                                 ▼
PostgreSQL Query                HTTP Request to API
    │                                 │
    ▼                                 ▼
Format Results                  Parse JSON Response
    │                                 │
    └─────────────────────────────────┘
                    │
                    ▼
        Render zoekresultaten.blade.php
```

### 5.2 Sync Flow

```
Scheduled Job (Daily at 2:00 AM)
    │
    ▼
SyncOpenOverheidDocumentsJob
    │
    ▼
OpenOverheidSyncService::syncRecent()
    │
    ▼
Fetch from API (Date Range: Last 24 hours)
    │
    ▼
For each document:
    │
    ├─► Fetch detail via getDocument()
    │
    ├─► Transform to database format
    │
    ├─► Upsert to PostgreSQL (updateOrCreate)
    │
    └─► Update synced_at timestamp
```

### 5.3 Typesense Sync Flow

```
After PostgreSQL Sync
    │
    ▼
TypesenseSyncService::syncToTypesense()
    │
    ▼
Query documents where:
  - typesense_synced_at IS NULL, OR
  - typesense_synced_at < updated_at
    │
    ▼
For each document:
    │
    ├─► Transform to Typesense format
    │
    ├─► Index in Typesense
    │
    └─► Update typesense_synced_at
```

### 5.4 Document Detail Flow

```
User clicks document link
    │
    ▼
DocumentController::show($id)
    │
    ├─► Try local database first
    │   OpenOverheidDocument::where('external_id', $id)
    │
    ├─► If not found, fetch from API
    │   OpenOverheidSearchService::getDocument($id)
    │
    └─► Render detail.blade.php
        (with JSON/XML export options)
```

---

## 6. Database Schema

### 6.1 open_overheid_documents Table

```sql
CREATE TABLE open_overheid_documents (
    id BIGSERIAL PRIMARY KEY,
    external_id VARCHAR(255) UNIQUE NOT NULL,
    title VARCHAR(255) NULLABLE,
    description TEXT NULLABLE,
    content TEXT NULLABLE,
    publication_date DATE NULLABLE,
    document_type VARCHAR(255) NULLABLE,
    category VARCHAR(255) NULLABLE,
    theme VARCHAR(255) NULLABLE,
    organisation VARCHAR(255) NULLABLE,
    metadata JSONB NULLABLE,
    synced_at TIMESTAMP NULLABLE,
    typesense_synced_at TIMESTAMP NULLABLE,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);
```

### 6.2 Indexes

**Standard Indexes**:
- `external_id` (UNIQUE)
- `publication_date`
- `document_type`
- `category`
- `theme`
- `organisation`
- `synced_at`
- `typesense_synced_at`

**Full-Text Search Index (PostgreSQL)**:
- `search_vector` (GIN index on tsvector)
  - Generated column combining title (weight A), description (weight B), content (weight C)
  - Uses Dutch language configuration

### 6.3 Data Relationships

- **None**: Currently a flat document structure
- **Future considerations**: Could add relationships for organisations, themes, categories if normalized

---

## 7. API Integration

### 7.1 Open Overheid API

**Base URL**: `https://open.overheid.nl/overheid/openbaarmakingen/api/v0`

**Endpoints Used**:

#### Search Endpoint
```
GET /zoek
```

**Parameters**:
- `zoektekst` (string): Search query
- `start` (integer): Pagination offset
- `aantalResultaten` (integer): Page size (10, 20, or 50)
- `publicatiedatumVan` (string): Start date (DD-MM-YYYY)
- `publicatiedatumTot` (string): End date (DD-MM-YYYY)
- `documentsoort` (string): Document type filter
- `informatiecategorie` (string): Information category filter
- `thema` (string): Theme filter
- `organisatie` (string): Organisation filter

#### Detail Endpoint
```
GET /zoek/{id}
```

**Parameters**:
- `{id}`: Document external ID (e.g., "oep-...")

### 7.2 Configuration

**File**: `config/open_overheid.php`

**Settings**:
- `base_url`: API base URL
- `timeout`: HTTP timeout (seconds)
- `sync.enabled`: Enable/disable sync
- `sync.batch_size`: Batch size for sync operations
- `sync.days_back`: Days to sync back (default: 1)
- `use_local_search`: Prefer local PostgreSQL over API
- `typesense.enabled`: Enable/disable Typesense sync
- `typesense.api_key`: Typesense API key
- `typesense.host`: Typesense host
- `typesense.port`: Typesense port
- `typesense.protocol`: HTTP protocol

### 7.3 Error Handling

- HTTP errors are logged via Laravel Log facade
- Fallback mechanisms:
  - Local search → Remote API (if local fails)
  - Local database → Remote API (for document details)
- User-friendly error messages in views

---

## 8. Search Architecture

### 8.1 Multi-Tier Search Strategy

**Tier 1: Typesense (Future Implementation)**
- Fast, semantic search
- Typo tolerance
- Faceted search capabilities
- Currently synced but not actively used in search flow

**Tier 2: PostgreSQL Full-Text Search (Primary)**
- Uses `search_vector` tsvector column
- Dutch language support
- Weighted search (title > description > content)
- Filter support (document_type, category, theme, organisation)

**Tier 3: Remote API (Fallback)**
- Direct API calls when local search unavailable
- Used when `use_local_search` is false or local search fails

### 8.2 Search Implementation

**Local Search** (`OpenOverheidLocalSearchService`):
- Uses `whereFullText()` scope for text search
- Applies filters via model scopes
- Pagination via Laravel's built-in pagination
- Results formatted to match API response structure

**Remote Search** (`OpenOverheidSearchService`):
- Direct API calls
- Response passed through with minimal transformation

---

## 9. Synchronization Strategy

### 9.1 Sync Methods

#### Recent Sync (Default)
- Syncs documents from last 24 hours (configurable via `sync.days_back`)
- Runs daily at 2:00 AM via Laravel Scheduler
- Job: `SyncOpenOverheidDocumentsJob`

#### Date Range Sync
- Allows syncing specific date ranges
- Used for initial full sync or historical data retrieval

#### Single Document Sync
- Sync individual documents by external_id
- Useful for manual updates or fixing sync issues

### 9.2 Sync Process

1. **Search Phase**: Query API search endpoint with date filters
2. **Detail Phase**: For each search result, fetch full document details
3. **Transform Phase**: Extract relevant fields from API response
4. **Upsert Phase**: Insert or update document in PostgreSQL
5. **Tracking Phase**: Update `synced_at` timestamp

### 9.3 Sync Commands

**Artisan Commands**:
- `SyncOpenOverheidDocuments`: Manual sync trigger
  - Options: `--id` (sync specific document), `--all` (full sync)
- `SyncTypesense`: Sync pending documents to Typesense

---

## 10. Frontend Architecture

### 10.1 Page Structure

#### Search Page (`/zoek`)
- Simple search form
- Document count display
- Links to information pages

#### Search Results Page (`/zoeken`)
- Left sidebar: Filters
  - Search keywords
  - Date available filter
  - Document type filter
  - Theme filter
  - Organisation filter
- Main content: Results list
  - Pagination
  - Sort options
  - Results per page selector
  - Individual result cards

#### Document Detail Page (`/open-overheid/documents/{id}`)
- Document title and metadata summary
- Toggle between Metadata and JSON views
- Metadata view: Structured characteristics display
  - "Toon alle kenmerken" / "Toon minder kenmerken" toggle
- JSON view: Formatted JSON with syntax highlighting
- Export options: Download JSON, Download XML, Copy JSON

### 10.2 Styling

**CSS Framework**: Custom CSS following Overheid.nl design guidelines
**File**: `public/css/openoverheid.css`

**Design Elements**:
- Blue color scheme (#01689b primary color)
- Clean, minimalist layout
- Responsive design
- Accessibility considerations

### 10.3 JavaScript Functionality

- Form submissions and filtering
- JSON syntax highlighting
- Copy to clipboard functionality
- Toggle show/hide for extended characteristics
- View switching (Metadata ↔ JSON)

---

## 11. Security & Performance

### 11.1 Security Measures

- **Input Validation**: All user inputs validated via Laravel validation rules
- **SQL Injection**: Protected via Eloquent ORM
- **XSS Protection**: Blade templates escape output by default
- **CSRF Protection**: Laravel CSRF tokens on forms
- **Rate Limiting**: Consider implementing for API endpoints

### 11.2 Performance Optimizations

- **Database Indexes**: Comprehensive indexing strategy
- **Full-Text Search**: PostgreSQL tsvector for fast text search
- **Caching**: Potential for query result caching (not yet implemented)
- **Lazy Loading**: Relationships not currently used, but would use eager loading if added
- **Pagination**: All search results paginated

### 11.3 Scalability Considerations

- **Queue Jobs**: Sync operations run asynchronously
- **Batch Processing**: Sync uses configurable batch sizes
- **Search Engine**: Typesense ready for high-traffic scenarios
- **Database**: PostgreSQL handles large document collections efficiently

---

## 12. Deployment & Infrastructure

### 12.1 Environment Requirements

**PHP**: 8.2 or higher
**Database**: PostgreSQL 12+ (production), SQLite (development)
**Web Server**: Nginx or Apache
**Queue Worker**: Required for scheduled sync jobs
**Cron**: Required for Laravel Scheduler

### 12.2 Environment Variables

```env
# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=open_overheid
DB_USERNAME=username
DB_PASSWORD=password

# Open Overheid API
OPEN_OVERHEID_BASE_URL=https://open.overheid.nl/overheid/openbaarmakingen/api/v0
OPEN_OVERHEID_TIMEOUT=10
OPEN_OVERHEID_SYNC_ENABLED=true
OPEN_OVERHEID_SYNC_BATCH_SIZE=50
OPEN_OVERHEID_SYNC_DAYS_BACK=1
OPEN_OVERHEID_USE_LOCAL_SEARCH=true

# Typesense
TYPESENSE_SYNC_ENABLED=true
TYPESENSE_API_KEY=your_api_key
TYPESENSE_HOST=localhost
TYPESENSE_PORT=8108
TYPESENSE_PROTOCOL=http

# Queue
QUEUE_CONNECTION=database
```

### 12.3 Deployment Steps

1. Clone repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env` and configure
4. Generate application key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Build assets: `npm install && npm run build`
7. Set up queue worker: `php artisan queue:work`
8. Set up cron for scheduler
9. (Optional) Run initial sync: `php artisan open-overheid:sync --all`

### 12.4 Scheduled Tasks

**Cron Entry** (required):
```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

**Scheduled Jobs**:
- Daily at 2:00 AM: `SyncOpenOverheidDocumentsJob` (syncs recent documents)

---

## 13. Future Enhancements

### 13.1 Planned Features

- [ ] Admin dashboard for sync monitoring
- [ ] Advanced analytics and statistics
- [ ] User favorites/bookmarks
- [ ] Email alerts for new documents
- [ ] Advanced Typesense search implementation
- [ ] API authentication/rate limiting
- [ ] Caching layer (Redis)
- [ ] Export functionality enhancements (CSV, Excel)

### 13.2 Technical Improvements

- [ ] Unit and integration tests
- [ ] API documentation (OpenAPI/Swagger)
- [ ] Search result caching
- [ ] Image/document preview
- [ ] Multi-language support
- [ ] Accessibility audit and improvements
- [ ] Performance monitoring

---

## 14. Glossary

- **Open Overheid**: Dutch open government initiative
- **Woo**: Wet Open Overheid (Open Government Act)
- **External ID**: Unique identifier from Open Overheid API (format: "oep-...")
- **Documentsoort**: Document type classification
- **Informatiecategorie**: Information category classification
- **Thema**: Theme classification
- **tsvector**: PostgreSQL full-text search data type
- **GIN Index**: Generalized Inverted Index (PostgreSQL index type)
- **Upsert**: Insert or update operation

---

## 15. References

- [Laravel Documentation](https://laravel.com/docs)
- [Open Overheid API Documentation](https://open.overheid.nl/)
- [Typesense Documentation](https://typesense.org/docs/)
- [PostgreSQL Full-Text Search](https://www.postgresql.org/docs/current/textsearch.html)
- [Overheid.nl Design Guidelines](https://www.rijksoverheid.nl/)

---

**Document Status**: Active  
**Last Updated**: 2025-12-20  
**Maintained By**: Development Team


