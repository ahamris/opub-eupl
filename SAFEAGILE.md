# oPub SAFe Agile Plan — Q2/Q3 2026

> Laatst bijgewerkt: 2 april 2026
> Sprint cadans: 2 weken | Team velocity: ~40 SP/sprint | PI duur: 5 sprints (10 weken)
>
> **Gerelateerde documenten:**
> - [METADATA.md](METADATA.md) — Data-architectuur, snelle dataset, ETL pipeline, veld mapping
> - [ROADMAP.md](ROADMAP.md) — Feature roadmap en technische status

---

## Legenda

| Status | Betekenis |
|--------|-----------|
| ✅ | Afgerond en live |
| 🔄 | In ontwikkeling (uncommitted/WIP) |
| 📋 | Gepland |
| ❌ | Niet gestart |

---

## HUIDIGE STATUS — Wat is er al gedaan?

### Platform Kern (✅ Live)

| Component | Status | Details |
|-----------|--------|---------|
| React SPA (UntitledUI) | ✅ | Volledige SPA met routing, layout, responsive |
| Zoekpagina met faceted filters | ✅ | Sidebar, organisatie/thema/categorie/type filters, counts, URL state sync |
| Zoekresultaten | ✅ | Paginering, sortering (relevantie/datum), per-page selector, filter badges |
| Autocomplete | ✅ | Document suggesties + filter suggesties bij typen |
| Semantic search toggle | ✅ | Vector similarity zoeken als optie |
| Document detailpagina | ✅ | AI-samenvatting, klikbare keywords, gerelateerde docs, metadata kaart |
| AI Chat met streaming | ✅ | SSE streaming, stop-knop, thinking steps, bronnen, follow-up suggesties |
| Chat export | ✅ | PDF, JSON, XML, Markdown, email dossier |
| Chat geschiedenis | ✅ | Sidebar met eerdere gesprekken, verwijderen, laden |
| Command palette | ✅ | Cmd+K globale zoekbalk |
| Homepage | ✅ | Hero, stats, features grid, doelgroepkaarten, kennisbank links |
| Contact formulier | ✅ | Naam, email, organisatie, onderwerp, bericht — POST naar backend |
| Dashboard | ✅ | Stats cards, area chart maandelijks, top organisaties, thema's, categorieën |
| Collecties/Dossiers | ✅ | Lijst met paginering, doorklik naar detail |
| Kennisbank | ✅ | Artikelen index, ontwikkelaars docs, evenementen (statisch) |
| Organisatiepagina | ✅ | Stats, charts, recente docs, bestuursorgaan info |
| API v2 + Swagger | ✅ | Search, documents, dossiers, stats, settings, chat, ingest |
| Ingest API | ✅ | Single + batch upload met API keys |
| Open.overheid.nl sync | ✅ | Dagelijks 02:00, 642K+ docs |
| Typesense zoekindex | ✅ | Real-time sync elke 5 min |
| GPU acceleration | ✅ | Tesla T4 @ 41 t/s |
| PostgreSQL geoptimaliseerd | ✅ | FTS, GIN indexes, dual database |

### In Ontwikkeling (🔄 Uncommitted/WIP)

| Component | Status | Details |
|-----------|--------|---------|
| Authenticatie systeem | 🔄 | Login, register, account pagina, auth provider context |
| Attenderingen / Notificaties | 🔄 | Subscribe modal, email verificatie, alert command, unsubscribe |
| Embeddings pipeline | 🔄 | GenerateEmbeddings command, EmbeddingService, DB migration |
| Bestuursorganen import | 🔄 | Model + import command |
| AI Document Enrichment | 🔄 | ~5.000/645.483 docs verwerkt (draait 24/7) |

---

## WAT MOET ER NOG GEBEUREN — Epic Overzicht

### Prioriteit Matrix (WSJF: Weighted Shortest Job First)

```
                    IMPACT OP GEBRUIKERSERVARING
          Hoog ┌─────────────────────────────────────┐
               │ ★ Feedback Module    ★ Blog Pages   │
               │ ★ Dark Mode          ★ Doc→OO link  │
               │ ★ Testimonials                      │
               │                                     │
        Medium │ ○ Gebruikersportaal  ○ Kennisbank   │
               │   uitbreiden          dynamisch     │
               │ ○ Collectie filters  ○ WCAG audit   │
               │                                     │
          Laag │ △ Multi-model AI     △ OPPR inst.   │
               │ △ Mobile app         △ Federatie    │
               │ △ Webhooks                          │
               └─────────────────────────────────────┘
                Laag          EFFORT           Hoog
```

| # | Epic | WSJF | Status | PI |
|---|------|------|--------|-----|
| E0 | Data-inrichting & Woo-classificatie | 36 | 📋 | PI-1 S1 |
| E1 | Feedback & Review Module | 34 | 📋 | PI-1 S1 |
| E2 | Blog Pagina's (SPA) | 30 | 📋 | PI-1 S1 |
| E3 | Dark Mode | 28 | 📋 | PI-1 S2 |
| E4 | Document & Zoek Polish + Detailpagina's | 26 | 📋 | PI-1 S1-S2 |
| E5 | Testimonials & Social Proof | 24 | 📋 | PI-1 S2 |
| E6 | Gebruikersportaal Uitbreiden | 20 | 📋 | PI-1 S3 |
| E7 | Kennisbank Dynamisch | 18 | 📋 | PI-1 S3 |
| E8 | Multi-source Ingest & Adapters | 17 | 📋 | PI-2 |
| E9 | Bestuursorgaanportaal | 16 | 📋 | PI-2 |
| E10 | WCAG 2.1 AA Compliance | 14 | 📋 | PI-2 |
| E11 | Geavanceerd (Multi-model, Federatie) | 10 | ❌ | PI-3+ |

---

## EPIC 0: Data-inrichting & Woo-classificatie ★ NIEUW
**Business Value**: Fundament voor ALLES. Zonder correcte data-inrichting is elke feature gebouwd op drijfzand. Woo/niet-Woo onderscheid is essentieel voor vertrouwen en juridische correctheid.
**Referentie**: Zie [METADATA.md](METADATA.md) voor volledig schema en veld mapping.

### Feature 0.1: Database Uitbreiding (Bronbeheer + Woo)
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 0.1.1 | Als systeem wil ik per document de databron opslaan | Migration: `source` (VARCHAR 100), `source_id` (VARCHAR 255), `source_url` (VARCHAR 2000) kolommen toevoegen | 3 | S1 | 📋 |
| 0.1.2 | Als systeem wil ik per document de Woo-status bijhouden | Migration: `woo_status` (VARCHAR 20, default 'unknown'), `ingested_by` (VARCHAR 255) kolommen toevoegen | 2 | S1 | 📋 |
| 0.1.3 | Als systeem wil ik het mediatype per document opslaan | Migration: `media_type` (VARCHAR 100) kolom toevoegen | 1 | S1 | 📋 |
| 0.1.4 | Als systeem wil ik alle bestaande open.overheid documenten backfillen | Command: set `source='open_overheid'`, `woo_status='woo'`, `source_url` uit metadata, `media_type` detectie | 5 | S1 | 📋 |
| 0.1.5 | Als systeem wil ik nieuwe velden in Eloquent model registreren | `$fillable`, `$casts` bijwerken, scopes voor `woo_status`, `source`, `media_type` | 2 | S1 | 📋 |

**Subtotaal**: 13 SP

### Feature 0.2: Typesense Schema Uitbreiding
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 0.2.1 | Als systeem wil ik `media_type`, `woo_status`, `source` als facets in Typesense | Schema update: 3 nieuwe facet velden toevoegen aan collectie | 3 | S1 | 📋 |
| 0.2.2 | Als systeem wil ik `source_url` opslaan in Typesense | Schema update: `source_url` als string veld | 1 | S1 | 📋 |
| 0.2.3 | Als systeem wil ik alle bestaande documenten re-syncen naar Typesense | Re-sync command met nieuwe velden, batch verwerking | 3 | S1 | 📋 |

**Subtotaal**: 7 SP

### Feature 0.3: API Response Uitbreiding
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 0.3.1 | Als API consumer wil ik `woo_status`, `media_type`, `source`, `source_url` in zoekresultaten | SearchHit uitbreiden met nieuwe velden | 2 | S1 | 📋 |
| 0.3.2 | Als API consumer wil ik facets voor `woo_status`, `media_type`, `source` | Facets toevoegen aan search response | 2 | S1 | 📋 |
| 0.3.3 | Als API consumer wil ik `woo_status`, `source`, `source_url`, `ingested_by` in document detail | DocumentResponse uitbreiden | 1 | S1 | 📋 |

**Subtotaal**: 5 SP

### Feature 0.4: Frontend TypeScript & Zoek UI
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 0.4.1 | Als ontwikkelaar wil ik TypeScript interfaces bijwerken | `SearchHit`, `DocumentResponse` uitbreiden in api.ts | 1 | S1 | 📋 |
| 0.4.2 | Als burger wil ik filteren op Woo-status in de zoeksidebar | Woo filter: "Woo-documenten", "Woo-gerelateerd", "Niet-Woo" met counts | 3 | S1 | 📋 |
| 0.4.3 | Als burger wil ik filteren op mediatype in de zoeksidebar | Mediatype filter met iconen: PDF, Word, Video, etc. met counts | 3 | S1 | 📋 |
| 0.4.4 | Als burger wil ik filteren op databron | Bron filter: "Open Overheid", "Ingest API", etc. met counts | 2 | S2 | 📋 |

**Subtotaal**: 9 SP

### Feature 0.5: Ingest API Uitbreiding
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 0.5.1 | Als bestuursorgaan wil ik bij ingest de woo_status meegeven | Ingest API: `woo_status` veld accepteren (optioneel, default 'woo') | 1 | S1 | 📋 |
| 0.5.2 | Als bestuursorgaan wil ik het mediatype meegeven of laten detecteren | Ingest API: `media_type` veld (optioneel), auto-detectie uit URL/content-type als niet meegegeven | 3 | S1 | 📋 |
| 0.5.3 | Als systeem wil ik de `source` en `ingested_by` automatisch zetten bij ingest | `source='ingest_api'`, `ingested_by` uit API client naam | 1 | S1 | 📋 |
| 0.5.4 | Als systeem wil ik de `source_url` opslaan bij ingest | Ingest API: `source_url` veld accepteren (optioneel) | 1 | S1 | 📋 |

**Subtotaal**: 6 SP

### Feature 0.6: ETL Sync Aanpassen
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 0.6.1 | Als systeem wil ik bij open.overheid sync automatisch source-velden zetten | `source='open_overheid'`, `source_id` uit API doc ID, `woo_status='woo'` | 2 | S1 | 📋 |
| 0.6.2 | Als systeem wil ik het mediatype detecteren bij sync | MIME-type uit bron URL / content-type header / extensie | 3 | S2 | 📋 |
| 0.6.3 | Als systeem wil ik `source_url` extraheren uit metadata bij sync | `metadata.document.weblocatie` of `metadata.document.pid` → `source_url` | 1 | S1 | 📋 |

**Subtotaal**: 6 SP

**Epic 0 Totaal**: 46 SP (Sprint 1-2)

---

## EPIC 1: Feedback & Review Module ★ NIEUW
**Business Value**: Directe feedback loop van burgers, journalisten en bestuursorganen. Essentieel voor platformverbetering en democratische verantwoording.
**Referentie design**: UntitledUI Testimonial Social Cards 03 — masonry grid met sterren, tekst, auteur

### Feature 1.1: Feedback Widget (Inline)
> Eenvoudige 1-5 sterren rating + opmerking, overal bereikbaar

| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 1.1.1 | Als burger wil ik een feedback-knop zien op elke pagina zodat ik mijn ervaring kan delen | Floating feedback button rechtsonder, opent modal | 3 | S1 | 📋 |
| 1.1.2 | Als burger wil ik 1-5 sterren geven zodat ik snel mijn tevredenheid kan aangeven | 5-sterren rating component (React Aria), verplicht veld | 3 | S1 | 📋 |
| 1.1.3 | Als burger wil ik een opmerking toevoegen zodat ik kan uitleggen wat beter kan | Textarea (optioneel), max 500 tekens, placeholder "Wat kunnen we verbeteren?" | 2 | S1 | 📋 |
| 1.1.4 | Als burger wil ik kiezen welk type feedback ik geef zodat mijn input juist wordt gerouteerd | Dropdown: Gebruikerservaring / Feature request / Bug / Compliment / Anders | 2 | S1 | 📋 |
| 1.1.5 | Als burger wil ik optioneel mijn email achterlaten zodat ik een reactie kan krijgen | Email veld (optioneel), validatie | 1 | S1 | 📋 |
| 1.1.6 | Als burger wil ik feedback geven zonder account zodat de drempel laag is | Geen authenticatie vereist, honeypot spam-bescherming | 2 | S1 | 📋 |
| 1.1.7 | Als bestuursorgaan wil ik feedback geven over de aanleverervaring | Extra categorie "Aanleverproces" in dropdown, organisatienaam veld | 1 | S1 | 📋 |

**Subtotaal**: 14 SP

### Feature 1.2: Feedback Backend & Opslag
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 1.2.1 | Als systeem wil ik feedback opslaan in de database | Migration: `feedback` tabel (rating, comment, type, email, page_url, user_agent, ip_hash, created_at) | 3 | S1 | 📋 |
| 1.2.2 | Als systeem wil ik spam voorkomen | Honeypot veld, rate limiting (max 5/uur per IP), geen captcha | 2 | S1 | 📋 |
| 1.2.3 | Als admin wil ik alle feedback inzien | Admin pagina met lijst, filters op type/rating/datum, export CSV | 5 | S2 | 📋 |
| 1.2.4 | Als admin wil ik feedback markeren als "behandeld" of "feature request" | Status veld + labels, notities per feedback item | 3 | S2 | 📋 |
| 1.2.5 | Als admin wil ik gemiddelde scores zien per pagina/periode | Dashboard widget: avg rating, trend, NPS berekening | 3 | S2 | 📋 |

**Subtotaal**: 16 SP

### Feature 1.3: Publieke Reviews & Testimonials Display
> UntitledUI Testimonial Social Cards 03 — masonry grid layout

| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 1.3.1 | Als burger wil ik reviews van andere gebruikers zien zodat ik vertrouwen krijg in het platform | Testimonial sectie op homepage, masonry grid, 6-9 kaarten | 5 | S2 | 📋 |
| 1.3.2 | Als admin wil ik feedback promoveren naar publieke testimonial | "Publiceer als testimonial" knop in admin, met naam/rol bewerking | 3 | S2 | 📋 |
| 1.3.3 | Per testimonial kaart: sterren, quote tekst, naam, rol/organisatie, avatar/initialen | Kaart component conform UntitledUI design, responsive masonry | 5 | S2 | 📋 |
| 1.3.4 | Als burger wil ik zien hoeveel mensen het platform waarderen | Aggregate stats boven testimonials: "4.7/5 gemiddeld, 234 reviews" | 2 | S2 | 📋 |

**Subtotaal**: 15 SP

**Epic 1 Totaal**: 45 SP (Sprint 1-2)

---

## EPIC 2: Blog Pagina's (SPA) ★ NIEUW
**Business Value**: Content marketing, SEO, kennisdeling. Burgers en journalisten vinden het platform via blog content.
**Backend**: Blog model + controller bestaan al in Laravel. Frontend SPA pagina's ontbreken.

### Feature 2.1: Blog Overzichtspagina
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 2.1.1 | Als burger wil ik alle blogartikelen zien op /blog | Grid van artikel kaarten (afbeelding, titel, excerpt, datum, categorie, auteur) | 5 | S1 | 📋 |
| 2.1.2 | Als burger wil ik filteren op categorie | Categorie tabs/pills bovenaan, actieve state | 3 | S1 | 📋 |
| 2.1.3 | Als burger wil ik paginering zodat ik door alle artikelen kan bladeren | Paginering component, 12 artikelen per pagina | 2 | S1 | 📋 |
| 2.1.4 | Als burger wil ik een uitgelicht artikel bovenaan zien | Hero-stijl featured article kaart, grotere afbeelding | 3 | S1 | 📋 |

**Subtotaal**: 13 SP

### Feature 2.2: Blog Artikel Detailpagina
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 2.2.1 | Als burger wil ik een artikel lezen op /blog/:slug | Volledige artikelpagina met titel, afbeelding, auteur, datum, rich text content | 5 | S1 | 📋 |
| 2.2.2 | Als burger wil ik gerelateerde artikelen zien onderaan | "Lees ook" sectie met 3 gerelateerde artikelen (zelfde categorie) | 3 | S2 | 📋 |
| 2.2.3 | Als burger wil ik het artikel delen via social media | Deel-knoppen: kopieer link, Twitter/X, LinkedIn, email | 2 | S2 | 📋 |
| 2.2.4 | Als burger wil ik de leestijd zien | Berekende leestijd op basis van woordaantal | 1 | S1 | 📋 |

**Subtotaal**: 11 SP

### Feature 2.3: Blog API & Routing
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 2.3.1 | Als systeem wil ik blog data ophalen via API | GET /api/v2/blog (lijst + paginering), GET /api/v2/blog/:slug (detail) | 3 | S1 | 📋 |
| 2.3.2 | Als systeem wil ik blog categorieën ophalen | GET /api/v2/blog/categories | 1 | S1 | 📋 |
| 2.3.3 | Als router wil ik /blog en /blog/:slug routes | Toevoegen aan router.tsx, lazy loading | 1 | S1 | 📋 |

**Subtotaal**: 5 SP

**Epic 2 Totaal**: 29 SP (Sprint 1-2)

---

## EPIC 3: Dark Mode
**Business Value**: Professionele uitstraling, toegankelijkheid, verwachting van moderne webapps.

### Feature 3.1: Dark Mode Implementatie
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 3.1.1 | Als burger wil ik een dark mode toggle in de header | Zon/maan icoon toggle, slaat voorkeur op in localStorage | 3 | S2 | 📋 |
| 3.1.2 | Als burger wil ik dat dark mode mijn systeemvoorkeur volgt | `prefers-color-scheme` detectie, handmatige override mogelijk | 2 | S2 | 📋 |
| 3.1.3 | Als burger wil ik dat alle pagina's correct renderen in dark mode | UntitledUI dark tokens activeren, alle pages testen | 8 | S2-S3 | 📋 |
| 3.1.4 | Als burger wil ik dat charts in dark mode leesbaar zijn | ApexCharts dark theme, aangepaste kleuren | 3 | S3 | 📋 |

**Subtotaal**: 16 SP

**Epic 3 Totaal**: 16 SP (Sprint 2-3)

---

## EPIC 4: Document & Zoek Polish + Detailpagina's
**Business Value**: De document detailpagina is waar de gebruiker uiteindelijk belandt. Dit moet de meest informatieve en bruikbare pagina van het platform zijn.
**Referentie**: Zie [METADATA.md](METADATA.md) sectie "Pagina-inrichting" voor wireframes.

### Feature 4.1: Document Detailpagina Herinrichting
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 4.1.1 | Als burger wil ik direct zien of het een Woo-document is | Woo-status badge bovenaan: groen "Woo-document", geel "Woo-gerelateerd", grijs "Niet-Woo" | 2 | S1 | 📋 |
| 4.1.2 | Als burger wil ik het mediatype zien met icoon | Media type badge met UntitledUI File Icon (PDF, Word, Video, etc.) | 2 | S1 | 📋 |
| 4.1.3 | Als burger wil ik een link naar het originele document | `source_url` als "Bekijk origineel" button, opent nieuw tabblad, toont domeinnaam | 2 | S1 | 📋 |
| 4.1.4 | Als burger wil ik een complete metadata kaart | Kaart met: organisatie (klikbaar), datum, documenttype, formaat, categorie, thema, woo-status, bron, sync datum | 5 | S1 | 📋 |
| 4.1.5 | Als burger wil ik acties uitvoeren op het document | Actie-balk: "Vraag de AI", "Bekijk origineel", "Kopieer link", "Deel via email", "Bewaar als favoriet" | 5 | S2 | 📋 |
| 4.1.6 | Als burger wil ik de ruwe metadata kunnen inzien | Inklapbare JSON viewer onderaan de pagina (voor ontwikkelaars/journalisten) | 3 | S2 | 📋 |

**Subtotaal**: 19 SP

### Feature 4.2: Dossier Detailpagina (Eigen Weergave)
> Dossiers krijgen een EIGEN pagina (niet hergebruik van DocumentPage)

| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 4.2.1 | Als burger wil ik een dossier als tijdlijn zien | Chronologische tijdlijn van alle documenten in het dossier, met datum, titel, mediatype icoon, Woo badge | 8 | S2 | 📋 |
| 4.2.2 | Als burger wil ik een dossier-samenvatting zien | AI-gegenereerde samenvatting van het gehele dossier (als verrijkt) | 3 | S3 | 📋 |
| 4.2.3 | Als burger wil ik dossier-metadata zien | Organisatie, aantal documenten, datumbereik, thema/categorie | 2 | S2 | 📋 |
| 4.2.4 | Als burger wil ik "Vraag de AI over dit dossier" | Knop → opent chat met dossier context | 3 | S3 | 📋 |
| 4.2.5 | Als burger wil ik een dossier exporteren | Export als PDF rapport / JSON / CSV | 5 | S3 | 📋 |
| 4.2.6 | Als router wil ik een aparte dossier route | `/collecties/:id` → DossierPage (niet meer DocumentPage) | 2 | S2 | 📋 |

**Subtotaal**: 23 SP

### Feature 4.3: Zoekresultaat Kaarten Verrijken
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 4.3.1 | Als burger wil ik het mediatype icoon zien per zoekresultaat | File-type icoon (PDF/Word/Video/etc.) links van de titel | 2 | S1 | 📋 |
| 4.3.2 | Als burger wil ik de Woo-status badge zien per zoekresultaat | Groen/geel/grijs Woo badge naast categorie en thema | 2 | S1 | 📋 |
| 4.3.3 | Als burger wil ik highlighted zoektermen in resultaten | Bold/highlight van matches in titel en beschrijving | 3 | S1 | 📋 |
| 4.3.4 | Als burger wil ik "geen resultaten" suggesties zien | Bij 0 resultaten: spelling suggesties, bredere zoekterm, AI suggestie | 3 | S2 | 📋 |

**Subtotaal**: 10 SP

### Feature 4.4: Organisatie Pagina Verrijken
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 4.4.1 | Als burger wil ik de document formaat-verdeling zien per organisatie | Staafdiagram: PDF x%, Word x%, Video x%, etc. | 3 | S3 | 📋 |
| 4.4.2 | Als burger wil ik de Woo-status verdeling zien per organisatie | Donut chart: Woo x%, Woo-gerelateerd x%, Niet-Woo x% | 3 | S3 | 📋 |
| 4.4.3 | Als burger wil ik collecties doorzoeken en filteren | Zoekbalk + organisatie filter op collectiepagina | 5 | S2 | 📋 |

**Subtotaal**: 11 SP

**Epic 4 Totaal**: 63 SP (Sprint 1-3)

---

## EPIC 5: Testimonials & Social Proof
**Business Value**: Vertrouwen opbouwen, conversie verhogen voor bestuursorganen.

### Feature 5.1: Homepage Testimonials Sectie
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 5.1.1 | Als burger wil ik ervaringen van andere gebruikers zien op de homepage | Testimonial Social Cards sectie (masonry), tussen features en kennisbank | 5 | S2 | 📋 |
| 5.1.2 | Per kaart: 1-5 sterren, quote, naam, rol, organisatie (optioneel), avatar/initialen | UntitledUI card component, responsive 1/2/3 kolommen | 5 | S2 | 📋 |
| 5.1.3 | Als admin wil ik testimonials beheren in het admin panel | CRUD voor testimonials: tekst, rating, naam, rol, organisatie, volgorde, actief/inactief | 5 | S3 | 📋 |
| 5.1.4 | Testimonials API endpoint | GET /api/v2/testimonials (actieve, gesorteerd op volgorde) | 2 | S2 | 📋 |

**Subtotaal**: 17 SP

**Epic 5 Totaal**: 17 SP (Sprint 2-3)

---

## EPIC 6: Gebruikersportaal Uitbreiden
**Business Value**: Persoonlijke ervaring verhoogt retentie.

### Feature 6.1: Account Verrijken
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 6.1.1 | Als gebruiker wil ik documenten als favoriet markeren | Ster-icoon op document + zoekresultaten, favorietenlijst in account | 5 | S3 | 📋 |
| 6.1.2 | Als gebruiker wil ik mijn zoekopdrachten opslaan | "Bewaar zoekopdracht" knop, lijst in account met directe herhaalklik | 5 | S3 | 📋 |
| 6.1.3 | Als gebruiker wil ik een overzicht van mijn activiteit | Recente zoekopdrachten, bekeken documenten, chat gesprekken timeline | 5 | S4 | 📋 |
| 6.1.4 | Als gebruiker wil ik notificatie-voorkeuren instellen | Email frequentie, digest vs individueel, pauzeer alle | 3 | S4 | 📋 |

**Subtotaal**: 18 SP

**Epic 6 Totaal**: 18 SP (Sprint 3-4)

---

## EPIC 7: Kennisbank Dynamisch
**Business Value**: Actuele content, SEO, kennisdeling.

### Feature 7.1: Dynamische Content
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 7.1.1 | Als burger wil ik actuele artikelen zien in de kennisbank | Dynamisch laden van blog artikelen (categorie "Kennisbank") ipv hardcoded | 3 | S3 | 📋 |
| 7.1.2 | Als burger wil ik API documentatie inline kunnen lezen | Swagger/OpenAPI viewer embedded in kennisbank pagina | 5 | S4 | 📋 |
| 7.1.3 | Als burger wil ik evenementen met datum en locatie zien | Evenementen uit database, toekomstige bovenaan, archief | 3 | S4 | 📋 |
| 7.1.4 | Als ontwikkelaar wil ik code voorbeelden zien per API endpoint | Syntax highlighted code blokken (curl, Python, JS) | 5 | S4 | 📋 |

**Subtotaal**: 16 SP

**Epic 7 Totaal**: 16 SP (Sprint 3-4)

---

## EPIC 8: Multi-source Ingest & Adapters ★ NIEUW
**Business Value**: Hoe meer bronnen, hoe completer het platform. Elke bron volgt hetzelfde adapter-patroon → genormaliseerd document.
**Referentie**: Zie [METADATA.md](METADATA.md) sectie "ETL per Databron — Universeel Patroon"

### Feature 8.1: Source Adapter Framework
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 8.1.1 | Als systeem wil ik een abstract SourceAdapter interface | PHP interface: `extract()`, `transform()`, `getSource()`, `getDefaultWooStatus()` | 5 | S5 | 📋 |
| 8.1.2 | Als systeem wil ik de bestaande OpenOverheid sync refactoren naar adapter | `OpenOverheidAdapter implements SourceAdapter`, bestaande logica behouden | 5 | S5 | 📋 |
| 8.1.3 | Als systeem wil ik een generiek sync command per adapter | `php artisan source:sync {adapter} --recent --from --to` | 3 | S5 | 📋 |
| 8.1.4 | Als admin wil ik zien welke bronnen actief zijn en hun sync status | Admin dashboard: per bron: naam, laatste sync, docs count, status | 5 | S6 | 📋 |

**Subtotaal**: 18 SP

### Feature 8.2: Geplande Adapters
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 8.2.1 | Als systeem wil ik gemeente Open Data API's aansluiten | `GemeenteAdapter`: mapping van gemeente API formaat → genormaliseerd document | 8 | S6 | 📋 |
| 8.2.2 | Als systeem wil ik Officiële Bekendmakingen aansluiten | `OfficielebekendmakingenAdapter`: mapping + woo_status='woo_related' | 8 | S7 | 📋 |
| 8.2.3 | Als systeem wil ik CSV/JSON bulk import ondersteunen | `BulkImportAdapter`: CLI command, kolom mapping, woo_status='unknown' default | 5 | S6 | 📋 |
| 8.2.4 | Als systeem wil ik media_type auto-detectie per adapter | MIME-type uit header, extensie, of AI classificatie — gedeelde utility | 3 | S5 | 📋 |

**Subtotaal**: 24 SP

**Epic 8 Totaal**: 42 SP (Sprint 5-7, PI-2)

---

## EPIC 9: Bestuursorgaanportaal
**Business Value**: Kernproduct voor overheden — directe klantwaarde.

### Feature 8.1: Organisatie Dashboard
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 8.1.1 | Als bestuursorgaan wil ik mijn publicaties zien in een eigen dashboard | Organisatie-specifiek dashboard na claim, eigen docs + stats | 8 | S5 | 📋 |
| 8.1.2 | Als bestuursorgaan wil ik documenten uploaden via een formulier | Upload form: titel, PDF, metadata (type, thema, categorie) | 8 | S5 | 📋 |
| 8.1.3 | Als bestuursorgaan wil ik API keys aanmaken en beheren | Key generatie, test/productie, rate limits, gebruiksstatistieken | 5 | S5 | 📋 |
| 8.1.4 | Als bestuursorgaan wil ik Woo-compliance rapportage zien | Status tov Woo-verplichting, aanbevelingen, vergelijking peers | 8 | S6 | 📋 |
| 8.1.5 | Als bestuursorgaan wil ik meerdere medewerkers uitnodigen | Gebruikersbeheer per organisatie, rollen (admin/editor/viewer) | 5 | S6 | 📋 |

**Subtotaal**: 34 SP

**Epic 8 Totaal**: 34 SP (Sprint 5-6, PI-2)

---

## EPIC 9: WCAG 2.1 AA Compliance
**Business Value**: Wettelijke verplichting voor overheidswebsites, inclusiviteit.

### Feature 9.1: Toegankelijkheid
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 9.1.1 | Volledige keyboard navigatie op alle pagina's | Tab-volgorde logisch, focus indicators zichtbaar, geen keyboard traps | 5 | S5 | 📋 |
| 9.1.2 | Screenreader compatibiliteit | ARIA labels, landmarks, live regions voor chat streaming | 5 | S5 | 📋 |
| 9.1.3 | Kleurcontrast check (4.5:1 minimum) | Alle tekst/achtergrond combinaties getest, dark mode inclusief | 3 | S5 | 📋 |
| 9.1.4 | Skip-to-content link en heading hiërarchie | Skip link op elke pagina, h1→h2→h3 correct genest | 2 | S5 | 📋 |

**Subtotaal**: 15 SP

**Epic 9 Totaal**: 15 SP (Sprint 5, PI-2)

---

## SPRINT PLANNING — PI-1

### Sprint 1 (Week 1-2) — "Data Foundation & Detailpagina's" | ~42 SP

> Data-inrichting EERST. Zonder correcte velden is elke UI gebouwd op drijfzand.

| Item | Epic | SP | MoSCoW |
|------|------|----|--------|
| **DATA-INRICHTING** | | | |
| DB migration: source, source_id, source_url, woo_status, ingested_by, media_type | E0 | 6 | Must |
| Backfill bestaande docs: source='open_overheid', woo_status='woo', source_url, media_type | E0 | 5 | Must |
| Eloquent model: $fillable, $casts, scopes | E0 | 2 | Must |
| Typesense schema: media_type, woo_status, source facets + re-sync | E0 | 7 | Must |
| API response: nieuwe velden in search + document + facets | E0 | 5 | Must |
| TypeScript interfaces bijwerken (api.ts) | E0 | 1 | Must |
| Ingest API: woo_status, media_type, source_url accepteren | E0 | 3 | Must |
| ETL sync: source-velden automatisch zetten + source_url extractie | E0 | 3 | Must |
| **DETAILPAGINA'S** | | | |
| Woo-status badge op document pagina | E4 | 2 | Must |
| Media type icoon op document pagina | E4 | 2 | Must |
| "Bekijk origineel" link (source_url) | E4 | 2 | Must |
| Woo + media_type icoon op zoekresultaat kaarten | E4 | 4 | Must |
| **Totaal** | | **42** | |

### Sprint 2 (Week 3-4) — "Feedback, Blog & Filters" | ~43 SP

| Item | Epic | SP | MoSCoW |
|------|------|----|--------|
| **FEEDBACK MODULE** | | | |
| Feedback widget (floating button + modal + sterren + opmerking) | E1 | 8 | Must |
| Feedback type dropdown + email (optioneel) + honeypot | E1 | 4 | Must |
| Feedback DB migration + model + spam preventie | E1 | 5 | Must |
| **BLOG** | | | |
| Blog overzichtspagina + categorie filter + paginering | E2 | 8 | Must |
| Blog artikel detailpagina + leestijd | E2 | 6 | Must |
| Blog API endpoints + routing | E2 | 4 | Must |
| **ZOEK FILTERS** | | | |
| Woo-status filter in zoeksidebar | E0 | 3 | Must |
| Media type filter in zoeksidebar (met iconen) | E0 | 3 | Must |
| Highlighted zoektermen in resultaten | E4 | 3 | Should |
| **Totaal** | | **44** | |

### Sprint 3 (Week 5-6) — "Social Proof, Dark Mode & Document Detail" | ~42 SP

| Item | Epic | SP | MoSCoW |
|------|------|----|--------|
| **TESTIMONIALS** | | | |
| Homepage testimonial sectie (masonry grid, UntitledUI) | E5 | 5 | Must |
| Testimonial kaart component (sterren, quote, auteur) | E5 | 5 | Must |
| Testimonials API endpoint | E5 | 2 | Must |
| Admin feedback overzicht + labels + promotie naar testimonial | E1 | 8 | Should |
| **DARK MODE** | | | |
| Dark mode toggle + systeemdetectie | E3 | 5 | Must |
| Dark mode alle pagina's testen + fixen | E3 | 8 | Must |
| **DOCUMENT DETAIL** | | | |
| Complete metadata kaart (alle velden incl. woo, media_type) | E4 | 5 | Must |
| Actie-balk (Vraag AI, Bekijk origineel, Kopieer, Deel) | E4 | 5 | Should |
| **Totaal** | | **43** | |

### Sprint 4 (Week 7-8) — "Dossiers, Portaal & Content" | ~40 SP

| Item | Epic | SP | MoSCoW |
|------|------|----|--------|
| **DOSSIER PAGINA** | | | |
| Dossier tijdlijn weergave (chronologisch, met iconen + badges) | E4 | 8 | Must |
| Dossier metadata + aparte route (/collecties/:id → DossierPage) | E4 | 4 | Must |
| Dossier AI-samenvatting | E4 | 3 | Should |
| "Vraag AI over dit dossier" knop | E4 | 3 | Should |
| **GEBRUIKERSPORTAAL** | | | |
| Favorieten (document markeren + lijst) | E6 | 5 | Should |
| Opgeslagen zoekopdrachten | E6 | 5 | Should |
| **ORGANISATIE PAGINA** | | | |
| Formaat-verdeling chart (PDF/Word/Video etc.) | E4 | 3 | Should |
| Woo-status verdeling chart | E4 | 3 | Should |
| **OVERIG** | | | |
| Dark mode charts (ApexCharts theme) | E3 | 3 | Should |
| Blog gerelateerde artikelen + social share | E2 | 5 | Could |
| **Totaal** | | **42** | |

### Sprint 5 (Week 9-10) — "WCAG, Kennisbank & Inklapbare Metadata" | ~40 SP

| Item | Epic | SP | MoSCoW |
|------|------|----|--------|
| **WCAG** | | | |
| Keyboard navigatie audit + fixes | E10 | 5 | Must |
| Screenreader compatibiliteit | E10 | 5 | Must |
| Kleurcontrast check | E10 | 3 | Must |
| Skip-to-content + heading hiërarchie | E10 | 2 | Must |
| **DOCUMENT DETAIL** | | | |
| Inklapbare JSON metadata viewer | E4 | 3 | Should |
| Dossier export (PDF/JSON/CSV) | E4 | 5 | Should |
| "Geen resultaten" suggesties | E4 | 3 | Should |
| **KENNISBANK** | | | |
| Kennisbank dynamische artikelen | E7 | 3 | Could |
| Swagger viewer in kennisbank | E7 | 5 | Could |
| **OVERIG** | | | |
| Databron filter in zoeksidebar | E0 | 2 | Should |
| Feedback dashboard (scores, trends, NPS) | E1 | 3 | Should |
| **Buffer** | | 1 | |
| **Totaal** | | **40** | |

---

## PI-2 PREVIEW (Sprint 6-10)

| Sprint | Focus | Key Items |
|--------|-------|-----------|
| S6 | Source Adapter Framework | Abstract interface, OpenOverheid refactor, generiek sync command |
| S7 | Gemeente + Bulk Import Adapters | GemeenteAdapter, BulkImportAdapter, media_type auto-detectie |
| S8 | Bestuursorgaanportaal | Organisatie dashboard, document upload, API key management |
| S9 | Woo-compliance & Analytics | Compliance rapportage, zoekanalytics, API usage monitoring |
| S10 | Multi-model AI & OPPR | Taaldetectie, model routing, OPPR instantie setup |

---

## VELOCITY & BURNDOWN PI-1

```
Sprint    Focus                              Planned SP    Cumulative    Done
S1        Data Foundation & Detailpagina's   42            42            📋
S2        Feedback, Blog & Filters           44            86            📋
S3        Social Proof, Dark Mode & Detail   43            129           📋
S4        Dossiers, Portaal & Content        42            171           📋
S5        WCAG, Kennisbank & Polish          40            211           📋
──────────────────────────────────────────────────────────────────
PI-1 Total: ~211 SP in 10 weken
```

---

## WAT KUNNEN WE BETER?

### 1. Gebruikerservaring (UX)
| Probleem | Verbetering | Impact |
|----------|------------|--------|
| Geen directe feedback mogelijkheid | Feedback module met 1-5 sterren + opmerking | Hoog |
| Geen social proof / testimonials | Masonry grid testimonials op homepage | Hoog |
| Geen blog content in SPA | Blog pagina's met artikelen, categorieën | Hoog |
| Geen dark mode | Toggle + systeem detectie | Medium |
| Kennisbank is statisch/hardcoded | Dynamische content uit database | Medium |
| Chat feedback (thumbs) gaat nergens heen | Feedback opslaan in database, analyseren | Hoog |
| Geen link naar origineel document | open.overheid.nl link op document pagina | Laag |

### 2. Engagement & Retentie
| Probleem | Verbetering | Impact |
|----------|------------|--------|
| Geen favorieten systeem | Ster-icoon op documenten, favorietenlijst | Medium |
| Geen opgeslagen zoekopdrachten | "Bewaar" knop, herhaal-klik in account | Medium |
| Geen activiteiten overzicht | Timeline van zoeken, bekijken, chatten | Medium |
| Blog mist in navigatie | Blog link in hoofdnavigatie + footer | Hoog |

### 3. Vertrouwen & Transparantie
| Probleem | Verbetering | Impact |
|----------|------------|--------|
| Geen gebruikersreviews zichtbaar | Publieke testimonials met sterren op homepage | Hoog |
| Geen NPS/satisfaction tracking | Aggregate scores tonen, intern monitoren | Medium |
| Geen WCAG compliance | Toegankelijkheidsaudit + fixes | Hoog (wettelijk) |

### 4. Technisch
| Probleem | Verbetering | Impact |
|----------|------------|--------|
| GPU 1 offline (RmInitAdapter failed) | Diagnose + repair → verdubbeling enrichment snelheid | Hoog |
| Enrichment 0.16% compleet | GPU 1 fixen + batch optimalisatie | Hoog |
| Chat feedback niet persistent | Opslaan in feedback tabel, analyse dashboard | Medium |
| Geen error tracking (Sentry etc.) | Frontend error monitoring toevoegen | Medium |

---

## RISICO'S & MITIGATIES

| Risico | Impact | Kans | Mitigatie |
|--------|--------|------|----------|
| GPU 1 blijft offline → enrichment vertraagd | Medium | Hoog | Niet blokkerend voor UI; prioriteit geven aan diagnose |
| Feedback spam zonder captcha | Medium | Medium | Honeypot + rate limiting + IP hash; captcha als fallback |
| Dark mode breekt bestaande componenten | Laag | Hoog | Componentgewijs testen, S3 als buffer |
| Blog content niet aanwezig (geen artikelen geschreven) | Medium | Hoog | Placeholder artikelen aanmaken, content plan opstellen |
| WCAG audit onthult grote problemen | Medium | Medium | React Aria als basis biedt goede a11y; focus op custom componenten |

---

## DEFINITION OF DONE

- [ ] Feature werkt in Chrome, Firefox, Safari, Edge (desktop + mobiel)
- [ ] Responsive op mobile viewports (320px+)
- [ ] Keyboard navigeerbaar (React Aria basis)
- [ ] Geen console errors of warnings
- [ ] API errors worden graceful afgehandeld met gebruikersvriendelijke melding
- [ ] Dark mode compatible (vanaf S2)
- [ ] Code volgt project conventies (kebab-case, Aria* prefix, sortCx)
- [ ] Feedback module: rating + opmerking opgeslagen in database

---

## FEEDBACK MODULE — Technisch Ontwerp

### Database Schema
```sql
CREATE TABLE feedback (
    id BIGSERIAL PRIMARY KEY,
    rating SMALLINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    type VARCHAR(50) NOT NULL DEFAULT 'general',
    -- Types: experience, feature_request, bug, compliment, other, onboarding
    email VARCHAR(255),
    organisation VARCHAR(255),
    page_url VARCHAR(500),
    user_agent VARCHAR(500),
    ip_hash VARCHAR(64), -- privacy: alleen hash, geen raw IP
    user_id BIGINT REFERENCES users(id) ON DELETE SET NULL,
    is_published BOOLEAN DEFAULT FALSE, -- admin kan promoveren naar testimonial
    published_name VARCHAR(255),
    published_role VARCHAR(255),
    admin_status VARCHAR(20) DEFAULT 'new',
    -- Status: new, reviewed, planned, resolved, published
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_feedback_rating ON feedback(rating);
CREATE INDEX idx_feedback_type ON feedback(type);
CREATE INDEX idx_feedback_status ON feedback(admin_status);
CREATE INDEX idx_feedback_published ON feedback(is_published) WHERE is_published = TRUE;
```

### API Endpoints
```
POST   /api/v2/feedback              — Nieuwe feedback indienen
GET    /api/v2/testimonials           — Publieke testimonials (is_published=true)
GET    /api/v2/testimonials/stats     — Aggregate: avg rating, count

# Admin (authenticated)
GET    /admin/api/feedback            — Alle feedback (filtered, paginated)
PATCH  /admin/api/feedback/:id        — Status/notes updaten
POST   /admin/api/feedback/:id/publish — Promoveer naar testimonial
DELETE /admin/api/feedback/:id        — Verwijder feedback
```

### Frontend Component Structuur
```
resources/js/spa/
├── components/features/
│   ├── feedback-widget.tsx        — Floating button + modal
│   ├── star-rating.tsx            — 1-5 sterren (React Aria)
│   └── testimonial-card.tsx       — Enkele review kaart
├── pages/
│   ├── blog.tsx                   — Blog overzicht
│   └── blog-article.tsx           — Blog artikel detail
```

### Testimonial Card Design (UntitledUI Social Cards 03 stijl)
```
┌──────────────────────────┐
│  ★★★★★  5/5             │
│                          │
│  "Eindelijk een platform │
│   waar ik snel Woo-      │
│   documenten kan vinden  │
│   zonder eindeloos       │
│   zoeken."               │
│                          │
│  ┌──┐  Jan de Vries      │
│  │AV│  Journalist        │
│  └──┘  NRC Handelsblad   │
└──────────────────────────┘
```

---

## OVERZICHT TOTALE SCOPE

| Categorie | Story Points | % van PI-1 |
|-----------|-------------|-------------|
| Feedback & Reviews (E1) | 45 | 23% |
| Blog (E2) | 29 | 15% |
| Dark Mode (E3) | 16 | 8% |
| Zoek & Document Polish (E4) | 16 | 8% |
| Testimonials (E5) | 17 | 9% |
| Gebruikersportaal (E6) | 18 | 9% |
| Kennisbank (E7) | 16 | 8% |
| Bestuursorgaanportaal start (E8) | 21 | 11% |
| WCAG Compliance (E9) | 15 | 8% |
| **Totaal PI-1** | **196** | **100%** |
