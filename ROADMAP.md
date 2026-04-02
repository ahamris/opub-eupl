# oPub Roadmap — Ontwikkelplan

## Huidige staat (v1.0)
| Component | Status |
|---|---|
| React SPA (UntitledUI) | ✅ Live |
| Command palette + instant search | ✅ Live |
| AI-chat met interactief dossier | ✅ Live |
| API v2 + Swagger | ✅ Live |
| Ingest API | ✅ Live |
| Document enrichment (AI) | 🔄 1.014/645.483 (draait 24/7) |
| Sync open.overheid.nl | ✅ Dagelijks 02:00 |
| GPU acceleration (Tesla T4) | ✅ 41 t/s |
| PostgreSQL geoptimaliseerd | ✅ |
| Typesense zoekindex | ✅ 642.783 docs |

---

## Prioriteit 1 — Quick wins (1-2 dagen)

### 1.1 Streaming AI responses
- **Impact**: Hoog — gebruiker ziet direct tekst verschijnen
- **Effort**: Laag
- Ollama ondersteunt `stream: true`
- Frontend: `fetch` met `ReadableStream`, token-voor-token renderen
- Geen wachttijd van 5-10 sec meer

### 1.2 Zoekpagina upgraden
- **Impact**: Hoog — meest bezochte pagina na home
- **Effort**: Laag-medium
- Faceted sidebar: organisatie, thema, categorie, documenttype (met counts)
- Gekleurde filter badges
- Datum range picker
- Paginering
- "Stel deze vraag aan de AI" knop per zoekresultaat
- Instant search (geen page reload)

### 1.3 Document detailpagina
- **Impact**: Medium
- **Effort**: Laag
- AI-samenvatting prominent tonen (als verrijkt)
- Klikbare trefwoorden → zoekfilter
- Gerelateerde documenten sidebar
- "Vraag de AI over dit document" knop
- Metadata kaart (organisatie, datum, type, thema)
- Link naar origineel op open.overheid.nl

### 1.4 Dark mode
- **Impact**: Medium — professionele uitstraling
- **Effort**: Laag
- UntitledUI heeft dark tokens
- Toggle in header
- `prefers-color-scheme` detectie

---

## Prioriteit 2 — Core features (1-2 weken)

### 2.1 Attendering / notificaties
- **Impact**: Hoog — unieke waarde, democratische functie
- **Effort**: Medium
- Gebruiker slaat zoekopdracht op met e-mailadres
- Dagelijkse cron checkt nieuwe documenten tegen opgeslagen queries
- E-mail notificatie bij match
- Beheer via gebruikersportaal
- Database: `search_subscriptions` tabel (bestaat al)

### 2.2 Organisatiekaart
- **Impact**: Medium-hoog
- **Effort**: Medium
- Overzichtspagina van alle aangesloten bestuursorganen
- Per organisatie: naam, aantal documenten, laatste publicatie, categorieën
- Zoek/filter op organisatienaam
- Klikbaar → gefilterde zoekresultaten
- Bereikbaarheidsgegevens (adres, website, contactpersoon)

### 2.3 Dashboard verrijken
- **Impact**: Medium
- **Effort**: Medium
- Publicatievolumes per maand (grafiek via Recharts)
- Top 20 organisaties met trend
- Categorieverdeling (pie/donut chart)
- Woo-compliance indicatoren
- Export als CSV/PDF

### 2.4 Semantic search (vector)
- **Impact**: Hoog — veel betere zoekresultaten
- **Effort**: Medium
- nomic-embed-text draait al op GPU
- Embeddings genereren voor alle documenten (batch job)
- Typesense ondersteunt vector search
- "Vergelijkbare documenten" feature
- Hybrid search: keyword + semantic

---

## Prioriteit 3 — Portalen (2-4 weken)

### 3.1 Gebruikersportaal
- **Impact**: Medium
- **Effort**: Medium
- Registratie / login (bestaat al via Laravel Breeze)
- Opgeslagen zoekopdrachten
- Attenderingen beheren
- Chat geschiedenis
- Favoriete documenten
- Berichtenbox (notificaties)

### 3.2 Bestuursorgaanportaal
- **Impact**: Hoog — kernproduct voor overheden
- **Effort**: Hoog
- **Handmatig aanleveren**: Upload formulier voor documenten (titel, PDF, metadata)
- **API tokens**: Aanmaken van test/productie API keys met rate limits
- **Webhook instellingen**: URL configureren voor notificaties bij verwerking
- **Publicatieoverzicht**: Dashboard van eigen aangeleverde documenten
- **Compliance rapportage**: Status t.o.v. Woo-verplichting
- **Gebruikersbeheer**: Meerdere medewerkers per organisatie
- **Buffer-API status**: Inzicht in doorlevering naar GWV/open.overheid.nl

### 3.3 Admin portaal upgraden
- **Impact**: Medium
- **Effort**: Medium
- Huidige Blade admin → React herwerken (of behouden)
- Bulk operaties op documenten
- AI enrichment monitoring
- System health dashboard (GPU, queue, sync status)
- Gebruikersbeheer

---

## Prioriteit 4 — Geavanceerd (1-2 maanden)

### 4.1 OPPR-instantie (oppr.opub.nl)
- **Impact**: Strategisch
- **Effort**: Medium
- Aparte oPub instantie voor Rijksoverheid
- Zelfde codebase, eigen database/Typesense collectie
- Eigen branding (Rijkshuisstijl)
- Multi-tenant architectuur of aparte deployment

### 4.2 React Native mobiele app
- **Impact**: Medium
- **Effort**: Hoog
- iOS + Android via React Native
- Zoeken, AI-chat, attenderingen (push notifications)
- Offline favorieten
- Delen van documenten
- Biometrische login

### 4.3 Ingest webhooks + buffer-API
- **Impact**: Hoog voor bestuursorganen
- **Effort**: Medium
- Automatische doorlevering naar GWV bij ingest
- Webhook notificaties naar bestuursorgaan bij verwerking
- Retry mechanisme bij falen
- Status tracking per document

### 4.4 Multi-model AI
- **Impact**: Medium
- **Effort**: Laag-medium
- Geitje voor Nederlands
- Gemma2/Llama voor technisch Engels
- Automatische taaldetectie
- Model routing op basis van query

### 4.5 Tijdslijn & dossierweergave
- **Impact**: Medium
- **Effort**: Medium
- Visuele tijdslijn van documenten in een dossier
- Chronologisch geordend
- Relaties tussen documenten zichtbaar
- AI-gegenereerde dossier-samenvatting
- Export als rapport

---

## Prioriteit 5 — Toekomst

### 5.1 Federatie met andere oPub-instanties
- Meerdere oPub installaties die elkaars index doorzoeken
- Gedistribueerde zoekfunctionaliteit

### 5.2 WCAG 2.1 AA compliance
- Volledige toegankelijkheidsaudit
- Screenreader optimalisatie
- Keyboard navigatie
- Hoog contrast modus

### 5.3 Analytics & rapportage
- Zoekopdrachten analytics (populaire termen, geen-resultaat queries)
- Gebruikersgedrag (anoniem)
- API usage monitoring
- SLA rapportage voor bestuursorganen

### 5.4 Europese uitbreiding
- Multi-taal interface (NL, EN, DE, FR)
- EUPL-compliante voor EU-brede inzet
- Koppeling met EU Open Data portaal

---

## Nu actief

| Taak | Status | ETA |
|---|---|---|
| AI enrichment (645K docs) | 🔄 5000/batch, elke 2 uur | ~20 dagen |
| Open.overheid.nl sync | ✅ Dagelijks 02:00 | Continu |
| Typesense sync | ✅ Elke 5 min | Continu |
| GPU 0 (Tesla T4) | ✅ 41 t/s | Actief |
| GPU 1 (Tesla T4) | ❌ RmInitAdapter failed | Te onderzoeken |
