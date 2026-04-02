# Changelog

Alle noemenswaardige wijzigingen aan dit project worden gedocumenteerd in dit bestand.

Het formaat is gebaseerd op [Keep a Changelog](https://keepachangelog.com/nl/1.1.0/) en dit project volgt [Semantic Versioning](https://semver.org/lang/nl/).

---

## [Unreleased]

### Toegevoegd
- **SAFEAGILE.md** — Volledig SAFe Agile plan met 11 epics, user stories, sprint planning (5 sprints, PI-1)
- **METADATA.md** — Data-architectuur plan met snelle dataset, ETL pipeline, veld mapping bron→frontend
- **Woo-classificatie** — `woo_status` veld (woo / woo_related / non_woo / unknown) voor juridisch onderscheid
- **Media type** — `media_type` veld voor formaat/drager classificatie (PDF, video, audio, etc. conform Archiefwet)
- **Bronbeheer** — `source`, `source_id`, `source_url`, `ingested_by` velden voor multi-source traceerbaarheid
- **Source Adapter patroon** — Universeel ETL patroon voor het aansluiten van nieuwe databronnen
- **Pagina-inrichting wireframes** — Document detail, dossier tijdlijn, zoekresultaat kaarten, organisatie pagina
- **Feedback module plan** — 1-5 sterren rating, opmerkingen, testimonials (UntitledUI Social Cards 03)
- **Blog pagina's plan** — SPA blog overzicht en artikel detail (backend bestaat al)
- **CHANGELOG.md** — Dit bestand

### Gewijzigd
- **README.md** — Bijgewerkt met actuele functionaliteiten, data-architectuur sectie, roadmap verwijzingen
- **SAFEAGILE.md** — Herprioriteerd: data-inrichting als Sprint 1, detailpagina's uitgebreid, multi-source epic toegevoegd

---

## [1.1.0] - 2026-04-02

### Toegevoegd
- Authenticatie systeem: login, registratie, account pagina, auth provider context
- Attenderingen: subscribe modal, email verificatie, alert command, unsubscribe flow
- Embeddings pipeline: GenerateEmbeddings command, EmbeddingService
- Bestuursorganen model en import command
- Chat streaming via SSE met thinking steps, follow-up suggesties, export opties
- Organisatie pagina met stats, charts, bestuursorgaan info
- Semantic search toggle in zoekpagina
- Autocomplete met document en filter suggesties

## [1.0.0] - 2026-04-02

### Toegevoegd
- Open source release onder EUPL-1.2
- README.md, CONTRIBUTING.md, LICENSE

## [0.9.0] - 2026-03-02

### Toegevoegd
- React 19 SPA met UntitledUI design systeem
- AI-chat met Ollama + Geitje-7b (sovereign, lokaal)
- API v2 met Swagger documentatie
- Document enrichment pipeline (AI samenvattingen, keywords)
- Zoekpagina met faceted filters, paginering, sortering
- Document detailpagina met AI samenvatting en gerelateerde docs
- Dashboard met ApexCharts (publicatievolumes, organisaties, thema's)
- Homepage met hero, features grid, doelgroepkaarten
- Contact formulier
- Kennisbank (statisch)
- Collecties/dossiers overzicht
- Command palette (Cmd+K)
- Ingest API (single + batch)
- Open.overheid.nl synchronisatie (dagelijks)
- Typesense zoekindex (real-time sync)
- GPU acceleration (Tesla T4 @ 41 t/s)
