# oPub Metadata Plan — Data-architectuur

> Laatst bijgewerkt: 2 april 2026
> Dit document is leidend voor alle data-gerelateerde ontwikkeling.

---

## Kernprincipe: Snelle Dataset + Volledige Metadata

Elk document in oPub heeft twee datalagen:

```
┌─────────────────────────────────────────────────────┐
│  SNELLE DATASET (geïndexeerd, doorzoekbaar, snel)   │
│                                                     │
│  titel · korte_omschrijving · lange_omschrijving    │
│  categorie · onderwerp · organisatie                │
│  sleutelwoorden (AI) · bron_verwijzing              │
├─────────────────────────────────────────────────────┤
│  VOLLEDIGE METADATA (JSONB, ongestructureerd)       │
│                                                     │
│  { alles van de bron-API, ongewijzigd opgeslagen }  │
└─────────────────────────────────────────────────────┘
```

**Waarom deze scheiding?**
- De snelle dataset wordt geïndexeerd in Typesense → milliseconden zoektijd
- De snelle dataset wordt verrijkt door AI → begrijpelijke titels, samenvattingen
- De volledige metadata blijft altijd beschikbaar voor detail-weergave en toekomstige features
- ETL haalt ALTIJD de volledige bron op en slaat deze ongewijzigd op in `metadata`

---

## Snelle Dataset — Veldendefinitie

### Kern Velden

| Veld | DB Kolom | Type | Max | Zoekbaar | Facet | Bron |
|------|----------|------|-----|----------|-------|------|
| **Titel** | `title` | VARCHAR(1000) | 1000 tekens | Ja (weight A) | Nee | API: `document.titelcollectie.officieleTitel` |
| **Korte omschrijving** | `description` | TEXT | - | Ja (weight B) | Nee | API: `document.omschrijvingen[0]` |
| **Lange omschrijving** | `content` | TEXT | - | Ja (weight C) | Nee | API: volledige documenttekst (indien beschikbaar) |
| **Categorie** | `category` | VARCHAR(255) | 255 tekens | Nee | Ja | API: `document.classificatiecollectie.informatiecategorieen[0].label` |
| **Onderwerp / Thema** | `theme` | VARCHAR(255) | 255 tekens | Nee | Ja | API: `document.classificatiecollectie.themas[0].label` |
| **Organisatie** | `organisation` | VARCHAR(255) | 255 tekens | Nee | Ja | API: `document.verantwoordelijke.label` of `document.publisher.label` |
| **Sleutelwoorden (AI)** | `ai_keywords` | JSON (array) | 10 items | Nee | Nee | AI: Ollama Geitje-7b verrijking |
| **Bron verwijzing** | `external_id` | VARCHAR(255) | 255 tekens | Nee | Nee | API: `document.id` (uniek, traceerbaar naar bron) |
| **Type (formaat)** | `media_type` | VARCHAR(100) | 100 tekens | Nee | Ja | API: `document.format` / MIME-type / bestandsextensie |

### Type (Formaat/Drager) — Archiefwet Classificatie

> **Let op het verschil:**
> - `document_type` = **classificatie** (Besluit, Woo-verzoek, Convenant, Jaarverslag)
> - `media_type` = **formaat/drager** (PDF, video, audio, spreadsheet — conform Archiefwet)

Het `media_type` veld beschrijft het fysieke formaat van het informatieobject, conform de Archiefwet die stelt dat overheidsinformatie in elke vorm kan voorkomen.

| Media Type | Label (NL) | MIME Types | Extensies | Icoon |
|------------|-----------|------------|-----------|-------|
| `pdf` | PDF Document | `application/pdf` | .pdf | FileTypePdf |
| `word` | Word Document | `application/msword`, `application/vnd.openxmlformats-officedocument.wordprocessingml.document` | .doc, .docx | FileTypeDoc |
| `excel` | Spreadsheet | `application/vnd.ms-excel`, `application/vnd.openxmlformats-officedocument.spreadsheetml.sheet` | .xls, .xlsx | FileTypeXls |
| `presentation` | Presentatie | `application/vnd.ms-powerpoint`, `application/vnd.openxmlformats-officedocument.presentationml.presentation` | .ppt, .pptx | FileTypePpt |
| `image` | Afbeelding | `image/jpeg`, `image/png`, `image/tiff`, `image/svg+xml` | .jpg, .png, .tiff, .svg | FileTypeJpg |
| `video` | Video | `video/mp4`, `video/webm`, `video/mpeg` | .mp4, .webm, .mpeg | FileTypeVideo |
| `audio` | Audio | `audio/mpeg`, `audio/wav`, `audio/ogg` | .mp3, .wav, .ogg | FileTypeAudio |
| `html` | Webpagina | `text/html` | .html, .htm | FileTypeHtml |
| `xml` | XML/Data | `application/xml`, `text/xml` | .xml | FileTypeXml |
| `csv` | CSV Data | `text/csv` | .csv | FileTypeCsv |
| `json` | JSON Data | `application/json` | .json | FileTypeJson |
| `text` | Platte tekst | `text/plain` | .txt | FileTypeText |
| `email` | E-mail | `message/rfc822` | .eml, .msg | FileTypeMail |
| `archive` | Archief/ZIP | `application/zip`, `application/x-rar` | .zip, .rar, .7z | FileTypeZip |
| `map` | Kaart/GIS | `application/geo+json`, `application/gml+xml` | .geojson, .gml | FileTypeMap |
| `dataset` | Dataset | `application/x-dataset` | divers | FileTypeDataset |
| `overview` | Overzicht/Lijst | - | - | FileTypeList |
| `other` | Overig | alle overige types | - | FileTypeGeneric |
| `unknown` | Onbekend | niet bepaald | - | FileTypeUnknown |

#### Detectie Volgorde (ETL)

```
1. MIME-type uit bron-API (metadata.document.format)
2. Content-Type header van bron URL
3. Bestandsextensie van bron URL / weblocatie
4. AI classificatie (bij twijfel)
5. Fallback: "unknown"
```

### Aanvullende Snelle Velden

| Veld | DB Kolom | Type | Zoekbaar | Facet | Sorteerbaar | Bron |
|------|----------|------|----------|-------|-------------|------|
| Documenttype (classificatie) | `document_type` | VARCHAR(255) | Nee | Ja | Nee | API: `document.classificatiecollectie.documentsoorten[0].label` |
| Type (formaat) | `media_type` | VARCHAR(100) | Nee | Ja | Nee | API: MIME-type / extensie / AI detectie |
| Publicatiedatum | `publication_date` | DATE | Nee | Nee | Ja | API: `versies[0].openbaarmakingsdatum` |
| Bron URL | via `metadata` | - | Nee | Nee | Nee | API: `document.weblocatie` of `document.pid` |
| Publicatie bestemming | via sync | VARCHAR(255) | Nee | Ja | Nee | Afgeleid: domein van bron URL |

### AI Verrijkte Velden

| Veld | DB Kolom | Type | Doel | Model |
|------|----------|------|------|-------|
| **Vereenvoudigde titel** | `ai_enhanced_title` | TEXT | B1-niveau Nederlands, max 100 tekens | Geitje-7b-ultra |
| **Samenvatting** | `ai_summary` | TEXT | 2-3 zinnen, max 200 woorden, begrijpelijk | Geitje-7b-ultra |
| **Sleutelwoorden** | `ai_keywords` | JSON array | Max 10 relevante trefwoorden | Geitje-7b-ultra |
| **Vector embedding** | Typesense `embedding` | float[768] | Semantisch zoeken, vergelijkbare docs | nomic-embed-text |

---

## Volledige Metadata — JSONB Opslag

### Wat zit erin?

De `metadata` kolom bevat de **ongewijzigde API response** van open.overheid.nl:

```json
{
  "document": {
    "id": "oep-abc123",
    "titelcollectie": {
      "officieleTitel": "Besluit omgevingsvergunning...",
      "verkorteTitel": null,
      "alternatieveTitels": []
    },
    "omschrijvingen": [
      "Omschrijving van het besluit..."
    ],
    "classificatiecollectie": {
      "documentsoorten": [{ "id": "...", "label": "Besluit" }],
      "informatiecategorieen": [{ "id": "...", "label": "2j. Onderzoeksrapporten" }],
      "themas": [{ "id": "...", "label": "Ruimte en infrastructuur" }]
    },
    "verantwoordelijke": {
      "id": "...",
      "label": "Gemeente Amsterdam"
    },
    "publisher": {
      "id": "...",
      "label": "Gemeente Amsterdam"
    },
    "weblocatie": "https://example.com/document.pdf",
    "pid": "https://identifier.overheid.nl/tooi/id/...",
    "documentrelaties": [
      {
        "role": "identiteitsgroep",
        "relation": "https://open.overheid.nl/documenten/oep-..."
      }
    ],
    "mutatiedatumtijd": "2025-04-01T12:34:56Z"
  },
  "versies": [
    {
      "openbaarmakingsdatum": "2025-03-15",
      "mutatiedatumtijd": "2025-03-15T08:00:00Z"
    }
  ]
}
```

### Waarom alles opslaan?

| Reden | Voorbeeld |
|-------|-----------|
| **Toekomstbestendig** | Nieuwe velden uit de bron-API zijn direct beschikbaar |
| **Dossierrelaties** | `documentrelaties` gebruikt voor dossier-weergave |
| **Bron-URL** | `weblocatie` en `pid` voor link naar open.overheid.nl |
| **Versiegeschiedenis** | `versies[]` voor publicatie-timeline |
| **Audit trail** | Originele data altijd verifieerbaar |
| **Classificatie-ID's** | `id`-velden in classificatie voor exacte matching |

---

## ETL Pipeline — Dataflow

```
┌──────────────────────┐
│  1. EXTRACT          │
│  open.overheid.nl    │
│  API v0/zoek         │
│  ┌────────────────┐  │
│  │ Volledige JSON  │  │
│  │ response per    │  │
│  │ document        │  │
│  └────────────────┘  │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│  2. TRANSFORM        │
│  SyncOpenOverheid    │
│  Documents.php       │
│                      │
│  Extract:            │
│  • titel             │
│  • korte_omschrijving│
│  • categorie         │
│  • onderwerp/thema   │
│  • organisatie       │
│  • documenttype      │
│  • type (formaat)    │
│  • publicatiedatum   │
│  • external_id       │
│                      │
│  Bewaar:             │
│  • metadata = {json} │
│    (volledige bron)  │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│  3. LOAD             │
│  PostgreSQL          │
│  open_overheid_      │
│  documents           │
│                      │
│  Upsert op           │
│  external_id         │
│  (uniek)             │
└──────────┬───────────┘
           │
     ┌─────┴─────┐
     ▼           ▼
┌──────────┐ ┌──────────────┐
│ 4a. INDEX│ │ 4b. ENRICH   │
│ Typesense│ │ Ollama AI    │
│          │ │              │
│ Snelle   │ │ → titel (B1) │
│ dataset  │ │ → samenvatting│
│ + facets │ │ → keywords   │
│ + vector │ │              │
│          │ │ 4c. EMBED    │
│          │ │ nomic-embed  │
│          │ │ → 768-dim    │
└──────────┘ └──────────────┘
```

### Pipeline Timing

| Stap | Frequentie | Trigger | Huidige Status |
|------|-----------|---------|----------------|
| Extract + Transform + Load | Dagelijks 02:00 | Cron: `sync:open-overheid` | ✅ 642.783 docs |
| Typesense Index | Elke 5 minuten | Cron: `sync:typesense` | ✅ Continu |
| AI Verrijking | Continu (24/7) | Cron: `documents:enrich` | 🔄 ~5.000/645.483 |
| Embedding Generatie | Na verrijking | Cron: `embeddings:generate` | 🔄 Gestart |

---

## Database Schema — Compleet

```sql
CREATE TABLE open_overheid_documents (
    -- Primaire sleutel
    id                      BIGSERIAL PRIMARY KEY,

    -- === SNELLE DATASET ===

    -- Kern identificatie
    external_id             VARCHAR(255) NOT NULL UNIQUE,

    -- Doorzoekbare tekstvelden
    title                   VARCHAR(1000),          -- Titel
    description             TEXT,                    -- Korte omschrijving
    content                 TEXT,                    -- Lange omschrijving

    -- Facet/filter velden
    category                VARCHAR(255),            -- Categorie (Woo)
    theme                   VARCHAR(255),            -- Onderwerp / Thema
    organisation            VARCHAR(255),            -- Organisatie
    document_type           VARCHAR(255),            -- Documenttype (classificatie)
    media_type              VARCHAR(100),            -- Type/formaat (pdf, video, audio, etc.)

    -- Sortering
    publication_date        DATE,                    -- Publicatiedatum

    -- === BRONBEHEER & WOO-CLASSIFICATIE ===
    source                  VARCHAR(100),            -- Databron (open_overheid, ingest_api, etc.)
    source_id               VARCHAR(255),            -- Uniek ID binnen de bron
    source_url              VARCHAR(2000),           -- Directe link naar origineel
    woo_status              VARCHAR(20) DEFAULT 'unknown', -- woo / woo_related / non_woo / unknown
    ingested_by             VARCHAR(255),            -- Organisatie/API client die aanleverde

    -- AI verrijkte velden (Geitje-7b)
    ai_enhanced_title       TEXT,                    -- Vereenvoudigde titel (B1)
    ai_summary              TEXT,                    -- Samenvatting (2-3 zinnen)
    ai_keywords             JSONB,                   -- Sleutelwoorden (max 10)
    ai_enhanced_description TEXT,                    -- Deprecated (gebruik ai_summary)
    ai_enhanced_at          TIMESTAMP,               -- Wanneer verrijkt

    -- Embedding tracking
    embedding_generated_at  TIMESTAMP,               -- Wanneer vector gegenereerd

    -- === VOLLEDIGE METADATA ===
    metadata                JSONB,                   -- Ongewijzigde bron-response

    -- === SYSTEEM ===
    synced_at               TIMESTAMP,               -- Laatste sync van bron
    typesense_synced_at     TIMESTAMP,               -- Laatste Typesense sync
    created_at              TIMESTAMP NOT NULL,
    updated_at              TIMESTAMP NOT NULL,

    -- === BEREKEND (PostgreSQL) ===
    search_vector           TSVECTOR GENERATED ALWAYS AS (
        setweight(to_tsvector('dutch', coalesce(title, '')), 'A') ||
        setweight(to_tsvector('dutch', coalesce(description, '')), 'B') ||
        setweight(to_tsvector('dutch', coalesce(content, '')), 'C')
    ) STORED
);

-- Indexes
CREATE UNIQUE INDEX idx_external_id ON open_overheid_documents(external_id);
CREATE INDEX idx_publication_date ON open_overheid_documents(publication_date);
CREATE INDEX idx_document_type ON open_overheid_documents(document_type);
CREATE INDEX idx_category ON open_overheid_documents(category);
CREATE INDEX idx_theme ON open_overheid_documents(theme);
CREATE INDEX idx_organisation ON open_overheid_documents(organisation);
CREATE INDEX idx_media_type ON open_overheid_documents(media_type);
CREATE INDEX idx_source ON open_overheid_documents(source);
CREATE INDEX idx_woo_status ON open_overheid_documents(woo_status);
CREATE INDEX idx_synced_at ON open_overheid_documents(synced_at);
CREATE INDEX idx_typesense_synced_at ON open_overheid_documents(typesense_synced_at);
CREATE INDEX idx_ai_enhanced_at ON open_overheid_documents(ai_enhanced_at);
CREATE INDEX idx_embedding_generated_at ON open_overheid_documents(embedding_generated_at);
CREATE INDEX idx_search_vector ON open_overheid_documents USING GIN(search_vector);
```

---

## Typesense Collection Schema

```json
{
  "name": "open_overheid_documents",
  "fields": [
    {"name": "id",                      "type": "string"},
    {"name": "external_id",             "type": "string"},

    {"name": "title",                   "type": "string",   "index": true},
    {"name": "description",             "type": "string",   "index": true},
    {"name": "content",                 "type": "string",   "index": true},

    {"name": "document_type",           "type": "string",   "facet": true},
    {"name": "media_type",              "type": "string",   "facet": true},
    {"name": "category",                "type": "string",   "facet": true},
    {"name": "theme",                   "type": "string",   "facet": true},
    {"name": "organisation",            "type": "string",   "facet": true},
    {"name": "woo_status",              "type": "string",   "facet": true},
    {"name": "source",                  "type": "string",   "facet": true},
    {"name": "publication_destination", "type": "string",   "facet": true},

    {"name": "url",                     "type": "string"},
    {"name": "source_url",             "type": "string",   "optional": true},
    {"name": "publication_date",        "type": "int64",    "sort": true},
    {"name": "synced_at",              "type": "int64",    "sort": true},

    {"name": "embedding",              "type": "float[]",  "num_dim": 768, "optional": true}
  ],
  "default_sorting_field": "publication_date"
}
```

### Zoekgewichten (Search Weights)

| Veld | Typesense Weight | PostgreSQL Weight | Doel |
|------|-----------------|-------------------|------|
| `title` | Standaard (hoog) | A (hoogste) | Exacte titel-match prioriteit |
| `description` | Standaard | B | Korte omschrijving match |
| `content` | Standaard (laag) | C (laagste) | Brede tekst-match |

---

## API Response Formaat

### Zoekresultaat (per hit)

```typescript
interface SearchHit {
  id: string;
  external_id: string;        // Bron verwijzing
  title: string;               // Titel
  description: string;         // Korte omschrijving
  organisation: string;        // Organisatie
  publication_date: string;    // YYYY-MM-DD
  document_type: string;       // Documenttype (classificatie)
  media_type: string;          // Type/formaat (pdf, video, audio, etc.)
  category: string;            // Categorie
  theme: string;               // Onderwerp
  woo_status: string;          // woo / woo_related / non_woo / unknown
  source: string;              // Databron (open_overheid, ingest_api, etc.)
  vector_distance?: number;    // Afstand bij semantic search
}
```

### Document Detail

```typescript
interface DocumentResponse {
  id: number;
  external_id: string;              // Bron verwijzing
  title: string;                     // Titel (origineel)
  ai_enhanced_title: string | null;  // Vereenvoudigde titel (AI)
  description: string;               // Korte omschrijving
  ai_summary: string | null;         // Samenvatting (AI)
  ai_keywords: string[] | null;      // Sleutelwoorden (AI)
  organisation: string;              // Organisatie
  publication_date: string;          // Publicatiedatum
  document_type: string;             // Documenttype (classificatie)
  media_type: string | null;         // Type/formaat (pdf, video, audio, etc.)
  category: string;                  // Categorie
  theme: string;                     // Onderwerp
  woo_status: string;                // woo / woo_related / non_woo / unknown
  source: string;                    // Databron
  source_url: string | null;         // Directe link naar origineel
  ingested_by: string | null;        // Wie leverde het aan
  metadata: Record<string, any>;     // Volledige metadata (JSONB)
  synced_at: string;                 // Laatste sync
  ai_enhanced_at: string | null;     // Wanneer verrijkt
}
```

### Facets (zoekfilters)

```typescript
interface SearchResponse {
  hits: SearchHit[];
  found: number;
  page: number;
  per_page: number;
  total_pages: number;
  search_time_ms: number;
  facets: {
    document_type: FacetCount[];   // Documenttype verdeling (classificatie)
    media_type: FacetCount[];       // Type/formaat verdeling (pdf, video, etc.)
    woo_status: FacetCount[];       // Woo-status verdeling
    source: FacetCount[];           // Databron verdeling
    organisation: FacetCount[];     // Organisatie verdeling
    theme: FacetCount[];            // Onderwerp verdeling
    category: FacetCount[];         // Categorie verdeling
  };
  semantic: boolean;
}

interface FacetCount {
  value: string;
  count: number;
}
```

---

## Veld Mapping — Van Bron tot Frontend

```
open.overheid.nl API              PostgreSQL                Typesense              Frontend
─────────────────────────────────────────────────────────────────────────────────────────────
document.id                    →  external_id             →  external_id          →  external_id
document.titelcollectie           
  .officieleTitel              →  title                   →  title                →  title
document.omschrijvingen[0]     →  description             →  description          →  description
(volledige tekst)              →  content                 →  content              →  (niet in response)
document.classificatiecollectie
  .informatiecategorieen[0]    →  category                →  category (facet)     →  category
  .themas[0]                   →  theme                   →  theme (facet)        →  theme
  .documentsoorten[0]          →  document_type           →  document_type (facet)→  document_type
document.format / MIME /
  bestandsextensie             →  media_type              →  media_type (facet)   →  media_type
(bron identifier)              →  source                  →  source (facet)       →  source
(bron document ID)             →  source_id               →  (niet in Typesense)  →  (niet in response)
document.weblocatie/pid        →  source_url              →  source_url           →  source_url
(o.b.v. bron + categorie)     →  woo_status              →  woo_status (facet)   →  woo_status
(API client / systeem)         →  ingested_by             →  (niet in Typesense)  →  ingested_by
document.verantwoordelijke
  .label                       →  organisation            →  organisation (facet) →  organisation
versies[0]
  .openbaarmakingsdatum        →  publication_date        →  publication_date     →  publication_date
document.weblocatie/pid        →  metadata->weblocatie    →  url                  →  metadata.document.weblocatie
(hele response)                →  metadata (JSONB)        →  (niet geïndexeerd)   →  metadata

--- AI VERRIJKING (Geitje-7b) ---
                                  ai_enhanced_title       →  (niet in Typesense)  →  ai_enhanced_title
                                  ai_summary              →  (niet in Typesense)  →  ai_summary
                                  ai_keywords             →  (niet in Typesense)  →  ai_keywords

--- EMBEDDING (nomic-embed-text) ---
                                  embedding_generated_at  →  embedding (float[768])→ (niet in response)
```

---

## Databronnen & Woo-classificatie

### Woo vs. Niet-Woo

> **Kritiek onderscheid**: Niet alle documenten in oPub zijn Woo-documenten. Het platform haalt data uit meerdere bronnen. Het moet voor de gebruiker altijd duidelijk zijn of een document onder de Wet open overheid (Woo) valt of niet.

| Classificatie | Betekenis | Bron | Voorbeeld |
|---------------|-----------|------|-----------|
| `woo` | Verplichte Woo-publicatie door bestuursorgaan | open.overheid.nl, Ingest API (bestuursorgaan) | Woo-besluit, Woo-verzoek, Convenant |
| `woo_related` | Gerelateerd aan Woo maar niet zelf een Woo-document | Ingest API, handmatige upload | Jaarverslag, beleidsnotitie, onderzoeksrapport |
| `non_woo` | Geen Woo-document | Externe bronnen, Ingest API | Nieuwsbericht, persverklaring, dataset |
| `unknown` | Nog niet geclassificeerd | Bulk import, legacy data | Documenten zonder categorie |

### Nieuwe Velden voor Bronbeheer

| Veld | DB Kolom | Type | Doel |
|------|----------|------|------|
| **Databron** | `source` | VARCHAR(100) | Waar het document vandaan komt |
| **Bron ID** | `source_id` | VARCHAR(255) | Uniek ID binnen de bron (naast `external_id`) |
| **Woo-classificatie** | `woo_status` | VARCHAR(20) | `woo` / `woo_related` / `non_woo` / `unknown` |
| **Bron URL** | `source_url` | VARCHAR(2000) | Directe link naar origineel document |
| **Aangeleverd door** | `ingested_by` | VARCHAR(255) | Organisatie/API client die het aanleverde |

### Geregistreerde Databronnen

| Source ID | Naam | Type | Woo Default | Frequentie | Status |
|-----------|------|------|-------------|-----------|--------|
| `open_overheid` | Open Overheid (Rijksoverheid) | Pull (API) | `woo` | Dagelijks 02:00 | ✅ Live |
| `ingest_api` | Ingest API (bestuursorganen) | Push (REST) | `woo` | Realtime | ✅ Live |
| `manual_upload` | Handmatige upload (portaal) | Push (form) | `woo_related` | Ad-hoc | 📋 Gepland |
| `gemeente_api` | Gemeente Open Data API's | Pull (API) | `woo` | Dagelijks | 📋 Gepland |
| `provincies` | Provinciale Open Data | Pull (API) | `woo` | Dagelijks | 📋 Gepland |
| `waterschappen` | Waterschappen publicaties | Pull (API) | `woo` | Wekelijks | 📋 Gepland |
| `rijksoverheid_nl` | Rijksoverheid.nl (nieuwsberichten) | Pull (scrape) | `non_woo` | Dagelijks | 📋 Gepland |
| `officielebekendmakingen` | Officiële Bekendmakingen | Pull (API) | `woo_related` | Dagelijks | 📋 Gepland |
| `wetten_overheid` | Wetten.overheid.nl | Pull (API) | `non_woo` | Wekelijks | 📋 Gepland |
| `cvdr` | Centrale Voorziening Decentrale Regelgeving | Pull (API) | `non_woo` | Wekelijks | 📋 Gepland |
| `external_import` | Bulk CSV/JSON import | Push (CLI) | `unknown` | Ad-hoc | 📋 Gepland |

### ETL per Databron — Universeel Patroon

Elke databron volgt hetzelfde patroon, ongeacht formaat:

```
┌─────────────────────────┐
│  BRON (eigen formaat)   │
│                         │
│  Open Overheid → JSON   │
│  Gemeente → XML/JSON    │
│  CSV import → CSV       │
│  Handmatig → Formulier  │
│  Scrape → HTML          │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────┐
│  SOURCE ADAPTER (per bron)                          │
│                                                     │
│  Verantwoordelijkheid:                              │
│  1. Ophalen van data in bronformaat                 │
│  2. Opslaan volledige response → metadata (JSONB)   │
│  3. Mapping naar snelle dataset velden              │
│  4. Bepalen woo_status                              │
│  5. Detecteren media_type                           │
│  6. Zetten van source + source_id                   │
└────────────┬────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────┐
│  GENORMALISEERD DOCUMENT                            │
│  (altijd dezelfde structuur, ongeacht bron)         │
│                                                     │
│  external_id    ← source:source_id (uniek)          │
│  title          ← gemapped vanuit bron              │
│  description    ← gemapped vanuit bron              │
│  content        ← gemapped vanuit bron              │
│  category       ← gemapped + genormaliseerd         │
│  theme          ← gemapped + genormaliseerd         │
│  organisation   ← gemapped + genormaliseerd         │
│  document_type  ← gemapped + genormaliseerd         │
│  media_type     ← gedetecteerd (MIME/ext/AI)        │
│  publication_date ← gemapped vanuit bron            │
│  source         ← bron identifier                   │
│  source_id      ← origineel ID in bron              │
│  source_url     ← link naar origineel               │
│  woo_status     ← bepaald door adapter              │
│  ingested_by    ← API client / systeem              │
│  metadata       ← volledige bron-response           │
└────────────┬────────────────────────────────────────┘
             │
             ▼
        PostgreSQL → Typesense → AI Verrijking → Embeddings
```

### Source Adapter — Mapping per Bron

#### Open Overheid (huidige bron)
```
API veld                              →  Snelle dataset veld
──────────────────────────────────────────────────────────
document.id                           →  source_id
"open_overheid"                       →  source
"open_overheid:{document.id}"         →  external_id
document.titelcollectie.officieleTitel→  title
document.omschrijvingen[0]            →  description
(niet beschikbaar)                    →  content
classificatiecollectie
  .informatiecategorieen[0].label     →  category
  .themas[0].label                    →  theme
  .documentsoorten[0].label           →  document_type
document.verantwoordelijke.label      →  organisation
versies[0].openbaarmakingsdatum       →  publication_date
document.weblocatie                   →  source_url
"woo"                                 →  woo_status
MIME van weblocatie                    →  media_type
(hele response)                       →  metadata
```

#### Gemeente Open Data API (voorbeeld toekomstige bron)
```
API veld                              →  Snelle dataset veld
──────────────────────────────────────────────────────────
record.id                             →  source_id
"gemeente_{gemeente_naam}"            →  source
"gemeente_{naam}:{record.id}"         →  external_id
record.titel                          →  title
record.samenvatting                   →  description
record.inhoud                         →  content
record.categorie                      →  category
record.thema                          →  theme
record.documentsoort                  →  document_type
gemeente_naam                         →  organisation
record.datum                          →  publication_date
record.url                            →  source_url
bepaald o.b.v. categorie              →  woo_status
record.mime_type / extensie           →  media_type
(hele response)                       →  metadata
```

#### CSV/Bulk Import (voorbeeld)
```
CSV kolom                             →  Snelle dataset veld
──────────────────────────────────────────────────────────
id (of rijnummer)                     →  source_id
"csv_import_{bestandsnaam}"           →  source
"csv_{bestand}:{id}"                  →  external_id
titel                                 →  title
omschrijving                          →  description
inhoud                                →  content
categorie                             →  category
thema                                 →  theme
documenttype                          →  document_type
organisatie                           →  organisation
datum                                 →  publication_date
url                                   →  source_url
"unknown" (handmatig verrijken)       →  woo_status
(afgeleid van url/bestand)            →  media_type
{hele CSV rij als JSON}               →  metadata
```

---

## Pagina-inrichting — Detail & Document Pagina's

### Zoekresultaat Kaart (ResultCard)

```
┌──────────────────────────────────────────────────────────────┐
│  ┌────┐                                                      │
│  │ PDF│  Titel van het document (max 2 regels)               │
│  └────┘  Korte omschrijving van het document, afgekapt       │
│          op maximaal 2 regels tekst...                       │
│                                                              │
│  🏛 Gemeente Amsterdam  ·  📅 15 maart 2025  ·  📄 Besluit  │
│                                                              │
│  [Woo]  [Ruimte en infrastructuur]  [2j. Onderzoeksrapporten]│
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

| Element | Veld | Nieuw | Toelichting |
|---------|------|-------|-------------|
| Type icoon | `media_type` | ★ | PDF/Video/Audio/Excel icoon links van titel |
| Titel | `title` | | Vetgedrukt, max 2 regels |
| Omschrijving | `description` | | Grijs, max 2 regels |
| Organisatie | `organisation` | | Met Building icoon, klikbaar naar org pagina |
| Datum | `publication_date` | | Geformatteerd (dd mmmm yyyy) |
| Documenttype | `document_type` | | Met File icoon |
| Woo badge | `woo_status` | ★ | Groen "Woo" badge als `woo`, grijs "Niet-Woo" als `non_woo` |
| Thema badge | `theme` | | Blauw badge |
| Categorie badge | `category` | | Grijs badge |

### Document Detailpagina — Volledige Indeling

```
┌──────────────────────────────────────────────────────────────────────┐
│  ← Terug naar zoekresultaten                                        │
│                                                                      │
│  [Woo-document]  [PDF]  [Bron: open.overheid.nl ↗]                  │
│                                                                      │
│  ═══════════════════════════════════════════════════════════════════  │
│  Titel van het Document                                              │
│  (ai_enhanced_title als beschikbaar, anders title)                   │
│  ═══════════════════════════════════════════════════════════════════  │
│                                                                      │
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │  METADATA KAART                                                 │ │
│  │                                                                 │ │
│  │  🏛 Organisatie    Gemeente Amsterdam  (klikbaar)               │ │
│  │  📅 Publicatie     15 maart 2025                                │ │
│  │  📄 Documenttype   Woo-besluit                                  │ │
│  │  🎞 Formaat        PDF Document                                 │ │
│  │  📂 Categorie      2j. Onderzoeksrapporten                     │ │
│  │  🏷 Thema          Ruimte en infrastructuur                     │ │
│  │  📊 Woo-status     ✅ Woo-document (verplichte publicatie)      │ │
│  │  🔗 Bron           open.overheid.nl/documenten/oep-...  ↗      │ │
│  │  🔄 Gesynchroniseerd  2 april 2026 14:30                       │ │
│  └─────────────────────────────────────────────────────────────────┘ │
│                                                                      │
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │  AI SAMENVATTING                                        🤖     │ │
│  │                                                                 │ │
│  │  Dit document betreft een besluit van de gemeente Amsterdam     │ │
│  │  over een omgevingsvergunning voor de bouw van een woning-      │ │
│  │  complex aan de Herengracht. Het besluit is genomen op basis    │ │
│  │  van de Omgevingswet.                                          │ │
│  │                                                                 │ │
│  │  Sleutelwoorden:                                               │ │
│  │  [omgevingsvergunning] [woningbouw] [herengracht] [amsterdam]  │ │
│  │  [omgevingswet]                                                │ │
│  └─────────────────────────────────────────────────────────────────┘ │
│                                                                      │
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │  OMSCHRIJVING                                                   │ │
│  │                                                                 │ │
│  │  Volledige beschrijving van het document zoals aangeleverd      │ │
│  │  door de bron...                                                │ │
│  └─────────────────────────────────────────────────────────────────┘ │
│                                                                      │
│  ┌─────────────────────── ACTIES ──────────────────────────────────┐ │
│  │                                                                 │ │
│  │  [🤖 Vraag de AI]  [📥 Bekijk origineel ↗]  [📋 Kopieer link] │ │
│  │  [📧 Deel via email]  [⭐ Bewaar als favoriet]                  │ │
│  └─────────────────────────────────────────────────────────────────┘ │
│                                                                      │
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │  GERELATEERDE DOCUMENTEN                              🤖 AI   │ │
│  │                                                                 │ │
│  │  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐           │ │
│  │  │ [PDF] Titel  │ │ [DOC] Titel  │ │ [PDF] Titel  │           │ │
│  │  │ Omschrijving │ │ Omschrijving │ │ Omschrijving │           │ │
│  │  │ Org · Datum  │ │ Org · Datum  │ │ Org · Datum  │           │ │
│  │  └──────────────┘ └──────────────┘ └──────────────┘           │ │
│  └─────────────────────────────────────────────────────────────────┘ │
│                                                                      │
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │  DOSSIER (als onderdeel van een dossier)                       │ │
│  │                                                                 │ │
│  │  Dit document is onderdeel van dossier "Omgevingsvergunning    │ │
│  │  Herengracht 123" met 5 gerelateerde documenten:               │ │
│  │                                                                 │ │
│  │  📄 15-01-2025  Aanvraag omgevingsvergunning                   │ │
│  │  📄 20-02-2025  Zienswijze bewonerscommissie                   │ │
│  │  📄 01-03-2025  Reactie op zienswijze                → dit doc │ │
│  │  📄 15-03-2025  Besluit omgevingsvergunning                    │ │
│  │  📄 01-04-2025  Publicatie in Staatscourant                    │ │
│  └─────────────────────────────────────────────────────────────────┘ │
│                                                                      │
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │  RUWE METADATA (inklapbaar)                                    │ │
│  │                                                                 │ │
│  │  ▶ Bekijk volledige metadata (JSON)                            │ │
│  └─────────────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────────────┘
```

### Pagina Secties — Gedetailleerd

| # | Sectie | Velden | Nieuw | Prioriteit |
|---|--------|--------|-------|-----------|
| 1 | **Terugknop** | Navigatie naar vorige pagina | | Must |
| 2 | **Status badges** | `woo_status`, `media_type`, bron link | ★ | Must |
| 3 | **Titel** | `ai_enhanced_title` → fallback `title` | | Bestaat |
| 4 | **Metadata kaart** | organisatie, datum, type, formaat, categorie, thema, woo-status, bron, sync datum | ★ media_type, woo_status, source_url | Must |
| 5 | **AI Samenvatting** | `ai_summary`, `ai_keywords` (klikbaar) | | Bestaat |
| 6 | **Omschrijving** | `description` / `content` | | Bestaat |
| 7 | **Acties** | Vraag AI, Bekijk origineel, Kopieer, Deel, Favoriet | ★ Bekijk origineel, Favoriet | Should |
| 8 | **Gerelateerde documenten** | API similar() met media_type icoon | ★ icoon | Bestaat |
| 9 | **Dossier tijdlijn** | `metadata.documentrelaties` chronologisch | ★ Nieuw | Should |
| 10 | **Ruwe metadata** | `metadata` als inklapbare JSON viewer | ★ Nieuw | Could |

### Organisatie Detailpagina — Aanvullingen

```
┌──────────────────────────────────────────────────────────────────────┐
│  Gemeente Amsterdam                                                  │
│  [Woo-plichtig ✅]  [1.234 documenten]  [Bron: open.overheid.nl]    │
│                                                                      │
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │  DOCUMENT VERDELING NAAR TYPE (formaat)                 Nieuw  │ │
│  │                                                                 │ │
│  │  📄 PDF          ████████████████████████  892  (72%)          │ │
│  │  📝 Word         ████████                  187  (15%)          │ │
│  │  📊 Excel        ████                       98   (8%)          │ │
│  │  🌐 HTML         ██                         42   (3%)          │ │
│  │  🎥 Video        █                          15   (1%)          │ │
│  └─────────────────────────────────────────────────────────────────┘ │
│                                                                      │
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │  WOO-STATUS VERDELING                                   Nieuw  │ │
│  │                                                                 │ │
│  │  ✅ Woo-documenten     1.100  (89%)                            │ │
│  │  🔗 Woo-gerelateerd      102   (8%)                            │ │
│  │  ⚪ Niet-Woo              32   (3%)                            │ │
│  └─────────────────────────────────────────────────────────────────┘ │
│                                                                      │
│  (bestaande secties: stats, charts, recente documenten)              │
└──────────────────────────────────────────────────────────────────────┘
```

### Dossier Detailpagina — Eigen Weergave

> Dossiers krijgen een eigen pagina-indeling (niet hergebruik van DocumentPage)

```
┌──────────────────────────────────────────────────────────────────────┐
│  ← Terug naar collecties                                             │
│                                                                      │
│  DOSSIER                                                             │
│  Omgevingsvergunning Herengracht 123                                 │
│  Gemeente Amsterdam  ·  5 documenten  ·  jan 2025 — apr 2025        │
│                                                                      │
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │  AI DOSSIER-SAMENVATTING                                🤖    │ │
│  │                                                                 │ │
│  │  Dit dossier bevat alle documenten rond de aanvraag en          │ │
│  │  verlening van een omgevingsvergunning voor woningbouw...       │ │
│  └─────────────────────────────────────────────────────────────────┘ │
│                                                                      │
│  ┌─────────────────── TIJDLIJN ───────────────────────────────────┐ │
│  │                                                                 │ │
│  │  ● 15-01-2025  📄 Aanvraag omgevingsvergunning          [Woo] │ │
│  │  │                                                              │ │
│  │  ● 20-02-2025  📄 Zienswijze bewonerscommissie          [Woo] │ │
│  │  │                                                              │ │
│  │  ● 01-03-2025  📄 Reactie op zienswijze                 [Woo] │ │
│  │  │                                                              │ │
│  │  ● 15-03-2025  📄 Besluit omgevingsvergunning           [Woo] │ │
│  │  │                                                              │ │
│  │  ● 01-04-2025  🌐 Publicatie in Staatscourant           [Woo] │ │
│  │                                                                 │ │
│  └─────────────────────────────────────────────────────────────────┘ │
│                                                                      │
│  [🤖 Vraag de AI over dit dossier]  [📥 Exporteer dossier]         │
└──────────────────────────────────────────────────────────────────────┘
```

---

## Regels voor Ontwikkeling

### 1. ETL Altijd Eerst
> Elke nieuwe databron volgt hetzelfde patroon: haal ALLES op, sla de volledige response op in `metadata`, extraheer de snelle dataset naar kolommen.

### 2. Snelle Dataset = Doorzoekbaar
> Alleen velden in de snelle dataset worden geïndexeerd in Typesense en PostgreSQL FTS. Metadata wordt NIET geïndexeerd.

### 3. AI Verrijking = Optioneel
> De snelle dataset moet functioneren ZONDER AI verrijking. AI velden (`ai_enhanced_title`, `ai_summary`, `ai_keywords`) zijn altijd nullable. Frontend toont fallback naar originele velden.

### 4. Metadata = Ongewijzigd
> De `metadata` JSONB kolom bevat altijd de ongewijzigde bron-response. Nooit transformeren of selectief opslaan — altijd het volledige antwoord.

### 5. Bron Verwijzing = Traceerbaar
> Elk document moet traceerbaar zijn naar de originele bron via `external_id`. Dit is de unieke sleutel voor upserts en deduplicatie.

### 6. Facets = Genormaliseerd
> Facet-velden (`category`, `theme`, `organisation`, `document_type`) worden genormaliseerd bij opslag voor consistente filtering.

### 7. Nieuwe Velden
> Bij het toevoegen van nieuwe velden aan de snelle dataset:
> 1. Voeg DB kolom toe (migration)
> 2. Voeg toe aan model `$fillable` en eventueel `$casts`
> 3. Voeg toe aan Typesense schema (indien doorzoekbaar/facet)
> 4. Voeg toe aan API response (controller)
> 5. Voeg toe aan TypeScript interface (api.ts)
> 6. Update ETL extraction logica (sync command)

---

## Toekomstige Uitbreidingen

### Gepland
| Veld | Type | Doel | Bron |
|------|------|------|------|
| `ai_sentiment` | VARCHAR(20) | Sentiment analyse (positief/neutraal/negatief) | AI |
| `ai_readability_score` | SMALLINT | Leesbaarheidscore (1-100) | AI |
| `language` | VARCHAR(10) | Taaldetectie (nl/en/de/fr) | AI |
| `word_count` | INTEGER | Woordaantal voor leestijd berekening | ETL |
| `has_attachments` | BOOLEAN | Of document bijlagen heeft | API metadata |

### Overwogen
| Veld | Type | Doel | Complexiteit |
|------|------|------|-------------|
| `geo_location` | POINT | Geografische locatie van organisatie | Hoog |
| `woo_compliance_score` | DECIMAL | Compliance score per organisatie | Hoog |
| `related_legislation` | JSON array | Gerelateerde wetsartikelen | Medium |
| `accessibility_score` | SMALLINT | Toegankelijkheid van brondocument | Medium |
