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
| E1 | Feedback & Review Module | 34 | 📋 | PI-1 S1 |
| E2 | Blog Pagina's (SPA) | 30 | 📋 | PI-1 S1 |
| E3 | Dark Mode | 28 | 📋 | PI-1 S2 |
| E4 | Document & Zoek Polish | 26 | 📋 | PI-1 S1-S2 |
| E5 | Testimonials & Social Proof | 24 | 📋 | PI-1 S2 |
| E6 | Gebruikersportaal Uitbreiden | 20 | 📋 | PI-1 S3 |
| E7 | Kennisbank Dynamisch | 18 | 📋 | PI-1 S3 |
| E8 | Bestuursorgaanportaal | 16 | 📋 | PI-2 |
| E9 | WCAG 2.1 AA Compliance | 14 | 📋 | PI-2 |
| E10 | Geavanceerd (Multi-model, Federatie) | 10 | ❌ | PI-3+ |

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

## EPIC 4: Document & Zoek Polish
**Business Value**: Kleine verbeteringen die de dagelijkse ervaring significant verbeteren.

### Feature 4.1: Ontbrekende Details
| # | User Story | Acceptatiecriteria | SP | Sprint | Status |
|---|------------|-------------------|-----|--------|--------|
| 4.1.1 | Als burger wil ik een link naar het origineel op open.overheid.nl | Externe link button op document pagina, opent in nieuw tabblad | 2 | S1 | 📋 |
| 4.1.2 | Als burger wil ik highlighted zoektermen in resultaten | Bold/highlight van matches in titel en beschrijving (Typesense highlight) | 3 | S1 | 📋 |
| 4.1.3 | Als burger wil ik "Vraag de AI over dit document" op de document pagina | Knop → opent /chat met document-ID + titel als context query | 3 | S1 | 📋 |
| 4.1.4 | Als burger wil ik collecties doorzoeken en filteren | Zoekbalk + organisatie filter op collectiepagina | 5 | S2 | 📋 |
| 4.1.5 | Als burger wil ik "geen resultaten" suggesties zien | Bij 0 resultaten: spelling suggesties, bredere zoekterm, AI suggestie | 3 | S2 | 📋 |

**Subtotaal**: 16 SP

**Epic 4 Totaal**: 16 SP (Sprint 1-2)

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

## EPIC 8: Bestuursorgaanportaal
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

### Sprint 1 (Week 1-2) — "Feedback & Blog Foundation" | ~40 SP

| Item | Epic | SP | MoSCoW |
|------|------|----|--------|
| Feedback widget (floating button + modal) | E1 | 3 | Must |
| Sterren rating component (1-5) | E1 | 3 | Must |
| Feedback opmerking textarea | E1 | 2 | Must |
| Feedback type dropdown | E1 | 2 | Must |
| Email veld (optioneel) | E1 | 1 | Must |
| Geen-auth feedback + honeypot | E1 | 2 | Must |
| Bestuursorgaan feedback categorie | E1 | 1 | Should |
| Feedback database migration + model | E1 | 3 | Must |
| Spam preventie (rate limiting) | E1 | 2 | Must |
| Blog overzichtspagina (/blog) | E2 | 5 | Must |
| Blog categorie filter | E2 | 3 | Must |
| Blog paginering | E2 | 2 | Must |
| Blog artikel detailpagina (/blog/:slug) | E2 | 5 | Must |
| Blog API endpoints + routing | E2 | 4 | Must |
| Leestijd berekening | E2 | 1 | Should |
| Link naar open.overheid.nl | E4 | 2 | Must |
| **Totaal** | | **41** | |

### Sprint 2 (Week 3-4) — "Social Proof & Dark Mode" | ~40 SP

| Item | Epic | SP | MoSCoW |
|------|------|----|--------|
| Admin feedback overzicht | E1 | 5 | Must |
| Feedback markeren + labels | E1 | 3 | Should |
| Feedback dashboard (scores, trends) | E1 | 3 | Should |
| Homepage testimonial sectie (masonry grid) | E1/E5 | 5 | Must |
| Testimonial kaart component (sterren, quote, auteur) | E5 | 5 | Must |
| Feedback → testimonial promotie (admin) | E1 | 3 | Should |
| Testimonials API endpoint | E5 | 2 | Must |
| Aggregate review stats op homepage | E1 | 2 | Should |
| Dark mode toggle + systeemdetectie | E3 | 5 | Must |
| Highlighted zoektermen in resultaten | E4 | 3 | Should |
| "Vraag de AI over dit document" knop | E4 | 3 | Should |
| Blog gerelateerde artikelen | E2 | 3 | Should |
| Blog social share knoppen | E2 | 2 | Could |
| **Totaal** | | **44** | |

### Sprint 3 (Week 5-6) — "Dark Mode Complete & Gebruikersportaal" | ~38 SP

| Item | Epic | SP | MoSCoW |
|------|------|----|--------|
| Dark mode alle pagina's testen + fixen | E3 | 8 | Must |
| Dark mode charts (ApexCharts theme) | E3 | 3 | Must |
| Admin testimonials CRUD | E5 | 5 | Should |
| Favorieten (document markeren + lijst) | E6 | 5 | Should |
| Opgeslagen zoekopdrachten | E6 | 5 | Should |
| Collectie zoeken + filteren | E4 | 5 | Should |
| "Geen resultaten" suggesties | E4 | 3 | Should |
| Kennisbank dynamische artikelen | E7 | 3 | Could |
| **Totaal** | | **37** | |

### Sprint 4 (Week 7-8) — "Engagement & Content" | ~36 SP

| Item | Epic | SP | MoSCoW |
|------|------|----|--------|
| Activiteiten overzicht (account) | E6 | 5 | Should |
| Notificatie-voorkeuren | E6 | 3 | Should |
| Swagger viewer in kennisbank | E7 | 5 | Could |
| Dynamische evenementen | E7 | 3 | Could |
| API code voorbeelden | E7 | 5 | Could |
| Uitgelicht blog artikel (hero) | E2 | 3 | Should |
| Chat gesprek zoeken in sidebar | — | 5 | Could |
| Performance audit + optimalisatie | — | 5 | Should |
| **Buffer/bugfix** | | 2 | |
| **Totaal** | | **36** | |

### Sprint 5 (Week 9-10) — "Compliance & Portaal Start" | ~38 SP

| Item | Epic | SP | MoSCoW |
|------|------|----|--------|
| Keyboard navigatie audit + fixes | E9 | 5 | Must |
| Screenreader compatibiliteit | E9 | 5 | Must |
| Kleurcontrast check | E9 | 3 | Must |
| Skip-to-content + heading hiërarchie | E9 | 2 | Must |
| Bestuursorgaan dashboard (basis) | E8 | 8 | Should |
| Document upload formulier | E8 | 8 | Should |
| API key management UI | E8 | 5 | Should |
| **Buffer** | | 2 | |
| **Totaal** | | **38** | |

---

## PI-2 PREVIEW (Sprint 6-10)

| Sprint | Focus | Key Items |
|--------|-------|-----------|
| S6 | Bestuursorgaanportaal | Woo-compliance rapportage, medewerker uitnodigen, webhook settings |
| S7 | Analytics & Rapportage | Zoekanalytics, gebruikersgedrag (anoniem), API usage monitoring |
| S8 | Multi-model AI | Taaldetectie, model routing, Gemma2/Llama voor Engels |
| S9 | Tijdslijn & Dossier | Visuele document tijdslijn, AI dossier-samenvatting |
| S10 | OPPR Instantie | Multi-tenant setup, Rijkshuisstijl, eigen Typesense collectie |

---

## VELOCITY & BURNDOWN PI-1

```
Sprint    Focus                      Planned SP    Cumulative    Done
S1        Feedback & Blog            41            41            📋
S2        Social Proof & Dark Mode   44            85            📋
S3        Dark Mode & Portaal        37            122           📋
S4        Engagement & Content       36            158           📋
S5        Compliance & Portaal       38            196           📋
──────────────────────────────────────────────────────────
PI-1 Total: ~196 SP in 10 weken
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
