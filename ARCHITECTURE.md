# oPub Reference Architecture

> Laatst bijgewerkt: 2 april 2026

---

## Overzicht

```
                         ┌─────────────────────────────┐
                         │        GEBRUIKER             │
                         │  Browser / Mobile / API      │
                         └──────────────┬──────────────┘
                                        │
                         ┌──────────────▼──────────────┐
                         │         NGINX               │
                         │   Reverse proxy + SSL       │
                         │   Ploi managed              │
                         └──────────────┬──────────────┘
                                        │
                    ┌───────────────────┬┴──────────────────┐
                    │                   │                    │
         ┌──────────▼──────┐  ┌────────▼────────┐  ┌──────▼───────┐
         │  REACT SPA      │  │  LARAVEL API    │  │  ADMIN PANEL │
         │  React 19       │  │  Laravel 12     │  │  Blade +     │
         │  TypeScript 6   │  │  PHP 8.4        │  │  Livewire 3  │
         │  Tailwind v4    │  │  REST API v2    │  │              │
         │  Vite 7         │  │                 │  │  → MIGRATIE  │
         │  React Aria     │  │                 │  │    NAAR REACT│
         └────────┬────────┘  └───────┬─────────┘  └──────────────┘
                  │                   │
                  │    ┌──────────────┼──────────────┐
                  │    │              │              │
           ┌──────▼────▼──┐  ┌───────▼──────┐  ┌───▼────────────┐
           │  TYPESENSE   │  │  POSTGRESQL  │  │  REDIS         │
           │  Zoekindex   │  │  Primair DB  │  │  Cache         │
           │  Facets      │  │  + FTS       │  │  Session       │
           │  Vectors     │  │  + JSONB     │  │  Queue         │
           └──────────────┘  └──────────────┘  └────────────────┘
                                    │
                  ┌─────────────────┼─────────────────┐
                  │                 │                  │
           ┌──────▼──────┐  ┌──────▼───────┐  ┌──────▼──────────┐
           │  OLLAMA      │  │  OPEN        │  │  TOEKOMSTIGE    │
           │  Geitje-7b   │  │  OVERHEID.NL │  │  BRONNEN        │
           │  nomic-embed │  │  API v0      │  │  (adapters)     │
           │  Tesla T4    │  │  Dagelijks   │  │                 │
           └─────────────┘  └──────────────┘  └─────────────────┘
```

---

## Huidige Stack — Versies

### Frontend (React SPA)

| Component | Versie | Status | Notities |
|-----------|--------|--------|----------|
| **React** | 19.2.4 | ✅ Latest | Concurrent features, Server Components ready |
| **TypeScript** | 6.0.2 | ✅ Latest | Strict mode |
| **Tailwind CSS** | 4.1.18 | ✅ Latest | v4 met CSS-first config |
| **Vite** | 7.2.7 | ✅ Latest | Build + HMR |
| **React Router** | 7.13.2 | ✅ Latest | Client-side routing |
| **React Aria Components** | 1.16.0 | ✅ Latest | Accessibility foundation |
| **ApexCharts** | 5.3.6 | ✅ | Dashboard charts |
| **Motion (Framer)** | 12.38.0 | ✅ | Animaties |
| **Tiptap** | 3.15.0 | ✅ | Rich text editor |
| **UntitledUI Icons** | 0.0.22 | ✅ | 1.100+ icons |
| **Axios** | 1.13.2 | ✅ | HTTP client |

### Backend (Laravel)

| Component | Versie | Status | Actie |
|-----------|--------|--------|-------|
| **PHP** | 8.4.18 | ✅ Latest | |
| **Laravel** | 12.49.0 | ✅ Latest | |
| **Livewire** | 3.7.8 | ⚠️ | Alleen admin panel — migreren naar React |
| **Spatie Permission** | 6.24.0 | ✅ | RBAC |
| **Typesense PHP** | 5.2.0 | ✅ | Zoekindex client |
| **Laravel AI** | 0.4.3 | ✅ | AI SDK (Ollama provider) |
| **Eloquent Sluggable** | 12.0.0 | ✅ | URL slugs |
| **Laravel Breeze** | 2.3 | ✅ | Auth scaffolding |

### Infrastructuur

| Component | Versie/Config | Status | Locatie |
|-----------|--------------|--------|---------|
| **PostgreSQL** | 16+ | ✅ | Primair: localhost:5432 |
| **PostgreSQL 2** | 16+ | ✅ | Secundair: 45.140.140.11:5432 (Overheid data) |
| **Redis** | 7+ | ✅ | localhost:6379 (cache, session, queue) |
| **Typesense** | 0.25+ | ✅ | 45.140.140.13:8108 |
| **Ollama** | latest | ✅ | 45.140.140.31:11434 |
| **GPU** | Tesla T4 x2 | ⚠️ | GPU 0: actief, GPU 1: RmInitAdapter failed |
| **Nginx** | latest | ✅ | Reverse proxy, Ploi managed |
| **Deployment** | Ploi | ✅ | Automated deploys |

---

## Architectuur Beslissingen

### 1. Frontend: React SPA (unified)

```
HUIDIGE SITUATIE                    DOEL
─────────────────                   ────────────────
React SPA (publiek)     ───→        React SPA (alles)
  ├── Zoeken                          ├── Publiek (zoeken, docs, chat)
  ├── Documenten                      ├── Gebruikersportaal
  ├── Chat                            ├── Bestuursorgaanportaal
  ├── Dashboard                       └── Admin panel
  └── Account
                                    
Blade + Livewire (admin) ───→       UITFASEREN
  ├── Blog CRUD                     Livewire componenten worden
  ├── Gebruikersbeheer              React componenten met dezelfde
  ├── Content management            API endpoints
  └── Settings
```

**Waarom migreren?**
- Eén codebase, één design systeem (UntitledUI)
- Eén build pipeline (Vite + React)
- Betere DX: TypeScript end-to-end
- React Aria voor consistente accessibility
- Livewire is niet slecht maar voegt complexiteit toe met twee UI stacks

**Migratie strategie:**
1. Admin API endpoints bestaan al (Laravel controllers)
2. Nieuwe admin pagina's direct in React bouwen
3. Bestaande Livewire pagina's stuk voor stuk omzetten
4. Blade views verwijderen wanneer React equivalent live is
5. Livewire dependency verwijderen als alles is gemigreerd

### 2. Backend: Laravel als API

```
Laravel 12 (PHP 8.4)
├── REST API v2 (publiek + auth)
├── Ingest API (API key)
├── Admin API (session auth)
├── ETL Pipeline (Artisan commands)
│   ├── Source Adapters (per bron)
│   ├── Typesense Sync
│   ├── AI Enrichment
│   └── Embedding Generation
├── Queue Workers (Redis)
│   ├── Document verwerking
│   ├── Email notificaties
│   └── Sync taken
└── Scheduled Tasks (Cron)
    ├── open.overheid.nl sync (02:00)
    ├── Typesense sync (elke 5 min)
    ├── AI enrichment (continu)
    └── Subscription alerts (dagelijks)
```

Laravel blijft de backend. Geen reden om te migreren — PHP 8.4 + Laravel 12 is modern, snel, en heeft een uitstekend ecosystem.

### 3. Database: PostgreSQL + Redis

```
PostgreSQL (primair)
├── open_overheid_documents (645K+ rijen)
│   ├── Snelle dataset (geïndexeerde kolommen)
│   ├── metadata JSONB (volledige bron-response)
│   └── search_vector TSVECTOR (FTS fallback)
├── users, roles, permissions
├── agent_conversations + messages
├── search_subscriptions
├── feedback (nieuw)
├── blog, static_pages, settings
└── api_clients

PostgreSQL 2 (secundair, read-only)
├── overheid_documents (Ooori snapshot)
├── overheid_categories
├── overheid_themes
└── overheid_organisations

Redis
├── Cache (DB 1) — API responses, Ollama cache (30 dagen)
├── Session (DB 0) — User sessions (120 min)
└── Queue (DB 0) — Background jobs
```

### 4. Zoeken: Typesense + PostgreSQL FTS

```
Zoekverzoek van gebruiker
         │
         ▼
    Typesense (primair)
    ├── Full-text search (title, description, content)
    ├── Faceted filters (7 facets met counts)
    ├── Vector search (768-dim, nomic-embed-text)
    ├── Typo tolerance + prefix matching
    ├── 100ms search cutoff
    └── Sorting (relevantie / datum)
         │
         │  fallback bij Typesense uitval
         ▼
    PostgreSQL FTS (secundair)
    ├── search_vector TSVECTOR
    ├── Dutch language stemming
    └── Gewogen zoeken (A: title, B: description, C: content)
```

### 5. AI: Sovereign & Lokaal

```
┌───────────────────────────────────────────────────────┐
│  AI STACK (volledig lokaal, geen cloud afhankelijkheid)│
│                                                       │
│  ┌─────────────────────────────────────────┐          │
│  │  Ollama Server (45.140.140.31:11434)    │          │
│  │                                         │          │
│  │  Modellen:                              │          │
│  │  ├── Geitje-7b-ultra:Q4_K_M (chat)     │          │
│  │  ├── Gemma2:9b (fallback)              │          │
│  │  └── nomic-embed-text (embeddings)     │          │
│  │                                         │          │
│  │  Hardware:                              │          │
│  │  ├── GPU 0: Tesla T4 ✅ (41 t/s)       │          │
│  │  └── GPU 1: Tesla T4 ❌ (te repareren)  │          │
│  └─────────────────────────────────────────┘          │
│                                                       │
│  Fallback (optioneel, cloud):                         │
│  ├── Google Gemini (gemini-2.5-flash-lite)            │
│  ├── OpenAI (audio transcriptie)                      │
│  └── Cohere (reranking)                               │
└───────────────────────────────────────────────────────┘
```

**Sovereign AI principes:**
- Primaire AI draait lokaal op eigen hardware
- Geen data naar externe cloud providers voor kernfuncties
- Cloud AI alleen als optionele fallback of voor specifieke taken
- Nederlands taalmodel (Geitje) voor optimale Nederlandse output

---

## ETL & Data Pipeline

```
┌────────────────────────────────────────────────────────────────────┐
│  SOURCE ADAPTERS                                                   │
│                                                                    │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐            │
│  │ OpenOverheid  │  │ Ingest API   │  │ Gemeente     │  ...       │
│  │ Adapter       │  │ Adapter      │  │ Adapter      │            │
│  │ (Pull, daily) │  │ (Push, RT)   │  │ (Pull, daily)│            │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘            │
│         │                  │                  │                    │
│         └──────────────────┼──────────────────┘                   │
│                            │                                       │
│                            ▼                                       │
│              ┌─────────────────────────┐                           │
│              │  GENORMALISEERD         │                           │
│              │  DOCUMENT               │                           │
│              │                         │                           │
│              │  Snelle dataset:        │                           │
│              │  title, description,    │                           │
│              │  category, theme,       │                           │
│              │  organisation,          │                           │
│              │  document_type,         │                           │
│              │  media_type,            │                           │
│              │  woo_status,            │                           │
│              │  source, source_url     │                           │
│              │                         │                           │
│              │  + metadata (JSONB)     │                           │
│              └───────────┬─────────────┘                           │
│                          │                                         │
│              ┌───────────▼─────────────┐                           │
│              │  PostgreSQL             │                           │
│              │  (upsert on external_id)│                           │
│              └───────────┬─────────────┘                           │
│                          │                                         │
│              ┌───────────┼───────────────┐                         │
│              │           │               │                         │
│              ▼           ▼               ▼                         │
│        ┌──────────┐ ┌────────┐  ┌──────────────┐                  │
│        │Typesense │ │  AI    │  │  Embeddings  │                  │
│        │Index     │ │Enrich  │  │  nomic-embed │                  │
│        │(5 min)   │ │(24/7)  │  │  (na enrich) │                  │
│        └──────────┘ └────────┘  └──────────────┘                  │
└────────────────────────────────────────────────────────────────────┘
```

Zie [METADATA.md](METADATA.md) voor het volledige schema en veld mapping.

---

## Netwerk Topologie

```
┌─────────────────────────────────────────────────────────────┐
│  PRODUCTIE OMGEVING                                         │
│                                                             │
│  ┌────────────────────────────────────┐                     │
│  │  Web Server (Ploi managed)         │                     │
│  │  ├── Nginx (reverse proxy + SSL)   │                     │
│  │  ├── PHP-FPM 8.4                   │                     │
│  │  ├── Laravel 12 (API + SPA serve)  │                     │
│  │  ├── Redis 7 (local)               │                     │
│  │  ├── PostgreSQL 16 (local)         │                     │
│  │  └── Queue Workers (supervisor)    │                     │
│  └────────────────────────────────────┘                     │
│           │              │                                   │
│           │              │                                   │
│  ┌────────▼──────┐  ┌───▼──────────────────┐               │
│  │  Typesense    │  │  Ollama + GPU Server │               │
│  │  Server       │  │                      │               │
│  │  :8108        │  │  Tesla T4 x2         │               │
│  │               │  │  :11434              │               │
│  │  45.140.140.13│  │  45.140.140.31       │               │
│  └───────────────┘  └──────────────────────┘               │
│           │                                                  │
│  ┌────────▼──────────────────┐                              │
│  │  PostgreSQL 2 (read-only) │                              │
│  │  Overheid data snapshot   │                              │
│  │  45.140.140.11:5432       │                              │
│  └───────────────────────────┘                              │
│                                                             │
│  ┌───────────────────────────┐                              │
│  │  Externe API's            │                              │
│  │  ├── open.overheid.nl     │                              │
│  │  ├── Google Gemini        │                              │
│  │  └── (toekomstige bronnen)│                              │
│  └───────────────────────────┘                              │
└─────────────────────────────────────────────────────────────┘
```

---

## Migratie Plan: Admin Blade/Livewire → React

### Huidige Livewire Componenten (te migreren)

| Livewire Component | Functie | Prioriteit | Complexiteit |
|--------------------|---------|-----------|-------------|
| MenuManager | Hoofdmenu CRUD + reordering | Laag | Medium |
| HeaderMenuManager | Header menu beheer | Laag | Laag |
| FooterMenuManager | Footer menu beheer | Laag | Laag |
| ThemeManager | Thema instellingen | Laag | Laag |
| Search | Admin zoekfunctie | Laag | Laag |
| Sidebar | Navigatie sidebar | Medium | Laag |
| ContactSubmissionsTable | Contact formulier inzendingen | Medium | Medium |
| TypesenseSyncStatus | Typesense sync status | Medium | Laag |
| Table | Generieke tabel component | Hoog | Medium |

### Huidige Blade Views (te migreren)

| View Groep | Aantal Views | Prioriteit | Aanpak |
|------------|-------------|-----------|--------|
| Blog CRUD | 4 views | Hoog | React admin pagina |
| Users CRUD | 4 views | Hoog | React admin pagina |
| API Clients | 4 views | Medium | React admin pagina |
| Contact/Submissions | 4 views | Medium | React admin pagina |
| Settings | 3 views | Laag | React admin pagina |
| Homepage content | 6 views | Laag | React admin pagina |
| Static pages | 4 views | Laag | React admin pagina |
| Auth (admin) | 4 views | Medium | React login flow |
| Documentation | 1 view | Laag | Verwijderen (niet nodig) |

### Migratie Volgorde

```
Fase 1 (PI-2): Admin API endpoints formaliseren
  └── Alle bestaande controllers retourneren al JSON
  └── Standaardiseer response format
  └── Voeg pagination/filtering toe waar nodig

Fase 2 (PI-2): React admin shell
  └── Admin layout component (sidebar, header)
  └── Route guard (admin role check)
  └── Generieke CRUD componenten (Table, Form, Modal)

Fase 3 (PI-2/3): Pagina voor pagina migreren
  └── Blog beheer (hoogste prioriteit — wordt meest gebruikt)
  └── Gebruikersbeheer
  └── API client beheer
  └── Contact/submissions
  └── Overige content pagina's

Fase 4 (PI-3): Opruimen
  └── Livewire dependency verwijderen uit composer.json
  └── Blade admin views verwijderen
  └── Alpine.js verwijderen (alleen gebruikt in admin)
  └── Admin CSS entry point verwijderen uit Vite config
```

---

## Dependency Strategie

### Geen Upgrade Nodig (al latest)

| Package | Versie | Reden |
|---------|--------|-------|
| React | 19.2.4 | Latest stable |
| TypeScript | 6.0.2 | Latest stable |
| Tailwind CSS | 4.1.18 | Latest v4 |
| Vite | 7.2.7 | Latest |
| PHP | 8.4.18 | Latest stable |
| Laravel | 12.49.0 | Latest stable |

### Te Verwijderen bij Admin Migratie

| Package | Versie | Reden |
|---------|--------|-------|
| livewire/livewire | 3.7.8 | Vervangen door React |
| alpinejs | 3.15.3 | Alleen gebruikt in Livewire admin |
| @alpinejs/focus | 3.15.3 | Alleen gebruikt in Livewire admin |
| quill | 2.0.3 | Vervangen door Tiptap (al in gebruik) |
| flatpickr | 4.6.13 | Vervangen door React date picker |

### Te Evalueren

| Package | Versie | Vraag |
|---------|--------|-------|
| hosseinhezami/laravel-gemini | 1.0.4 | Nodig naast laravel/ai? |
| sebastian/version | 6.0.0 | Wordt dit nog gebruikt? |
| @algolia/autocomplete-* | 1.19.4 | Custom autocomplete in React vervangt dit? |

---

## Security Model

```
┌─────────────────────────────────────────────────────────┐
│  PUBLIEK (geen auth)                                    │
│  ├── GET /api/v2/search                                 │
│  ├── GET /api/v2/documents/{id}                         │
│  ├── GET /api/v2/dossiers                               │
│  ├── GET /api/v2/stats                                  │
│  ├── GET /api/v2/organisations/{name}                   │
│  ├── GET /api/v2/testimonials                           │
│  └── POST /api/v2/feedback (rate limited)               │
├─────────────────────────────────────────────────────────┤
│  SESSION AUTH (cookie-based)                            │
│  ├── POST /api/v2/chat/send (streaming)                 │
│  ├── GET  /api/v2/chat/conversations                    │
│  ├── Auth endpoints (/auth/login, /register, etc.)      │
│  └── Subscription endpoints                             │
├─────────────────────────────────────────────────────────┤
│  API KEY AUTH (X-OPUB-API-KEY header)                   │
│  ├── POST /api/v2/ingest                                │
│  ├── POST /api/v2/ingest/batch                          │
│  └── DELETE /api/v2/ingest/{external_id}                │
├─────────────────────────────────────────────────────────┤
│  ADMIN (session + CheckIfAdmin middleware)               │
│  ├── Alle /admin/* routes                               │
│  └── Spatie permission checks                           │
└─────────────────────────────────────────────────────────┘
```

---

## Gerelateerde Documenten

- [METADATA.md](METADATA.md) — Data-architectuur, snelle dataset, ETL pipeline, veld mapping
- [SAFEAGILE.md](SAFEAGILE.md) — SAFe Agile plan, epics, user stories, sprint planning
- [ROADMAP.md](ROADMAP.md) — Feature roadmap en technische status
- [CONTRIBUTING.md](CONTRIBUTING.md) — Bijdrage richtlijnen
