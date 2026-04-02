# OpenPublicaties (oPub)

> Het open platform voor alle overheidspublicaties in Nederland.

**[opub.nl](https://opub.nl)** · **[API Documentatie](https://opub.nl/api/docs)** · **[Licentie: EUPL-1.2](LICENSE)**

---

## Projectomschrijving

OpenPublicaties is een open source platform gericht op het ontsluiten, beheren en publiceren van **alle overheidspublicaties** in Nederland. Niet alleen open data of Woo-verzoeken, maar het volledige publicatielandschap van de overheid: wet- en regelgeving, beleidsdocumenten, rapporten, bekendmakingen, onderzoeken, niet-toepassingen en meer.

Het project bevindt zich in een **vroege fase**. De basis staat, maar er is nog veel ruimte voor doorontwikkeling, verbreding en verdieping — samen met de community. We nodigen overheidsinstanties, onderzoekers, ontwikkelaars en beleidsmakers uit om mee te denken, mee te bouwen en mee te groeien.

## Visie & doelstelling

De Nederlandse overheid produceert dagelijks duizenden publicaties, verspreid over honderden portalen, systemen en formaten. OpenPublicaties wil die fragmentatie doorbreken door **één centraal, doorzoekbaar en open platform** te bieden waarop al deze publicaties samenkomen.

Onze doelstellingen:

- **Toegankelijkheid** — Iedere burger, onderzoeker of journalist kan overheidspublicaties eenvoudig vinden en raadplegen.
- **Transparantie** — Volledig open source, volledig inzichtelijk. Geen black boxes, geen vendor lock-in.
- **Samenwerking** — Doorontwikkeling gebeurt samen met de gemeenschap: overheidsinstanties, kennisinstellingen en individuele bijdragers.
- **Compleetheid** — Niet alleen Woo-documenten, maar het gehele publicatiespectrum van de overheid.

## Functionaliteiten

### Huidige functionaliteiten

- Doorzoeken van 645.000+ actief openbaar gemaakte overheidsdocumenten
- Full-text zoeken met faceted filters (organisatie, thema, categorie, documenttype) en URL state sync
- Semantic search via vector embeddings (nomic-embed-text, 768-dim)
- REST API v2 voor zoeken, ophalen en aanleveren van documenten (Swagger)
- AI-verrijking: vereenvoudigde titels, samenvattingen en keywords (sovereign, lokaal via Ollama + Geitje-7b)
- AI-chatfunctie met streaming (SSE), bronverwijzingen, follow-up suggesties en export (PDF/JSON/XML/MD)
- Dashboarding met publicatievolumes, top organisaties, thema's en categorieën (ApexCharts)
- Organisatiepagina's met statistieken, charts en bestuursorgaan-informatie
- Automatische synchronisatie met open.overheid.nl (dagelijks)
- Attenderingsfunctie: zoekabonnementen met e-mailverificatie en frequentiekeuze
- Authenticatie: registratie, login, account beheer, abonnementen overzicht
- Dossiers/collecties: overzicht en doorklik naar gerelateerde documenten

### Data-architectuur

Elk document heeft een **snelle dataset** (geïndexeerd, doorzoekbaar) en **volledige metadata** (JSONB, ongewijzigd uit de bron):

| Snelle dataset | Doel |
|----------------|------|
| Titel, korte/lange omschrijving | Doorzoekbaar (FTS + Typesense) |
| Categorie, thema, organisatie, documenttype | Facet filters |
| Media type (PDF, video, audio, etc.) | Formaat/drager classificatie |
| Woo-status (woo / woo_related / non_woo) | Juridische classificatie |
| Sleutelwoorden (AI), samenvatting (AI) | AI-verrijkte metadata |
| Bron verwijzing, bron URL | Traceerbaarheid naar origineel |

Zie [METADATA.md](METADATA.md) voor het volledige schema, ETL pipeline en veld mapping.

### Roadmap

Zie [SAFEAGILE.md](SAFEAGILE.md) voor het volledige SAFe Agile plan met epics, user stories en sprint planning.

**PI-1 (Q2 2026):**
- Data-inrichting: Woo-classificatie, media types, multi-source bronbeheer
- Feedback & review module (1-5 sterren, testimonials)
- Blog pagina's (SPA)
- Document detailpagina herinrichting (Woo badges, media iconen, metadata kaart, acties)
- Dossier eigen pagina met tijdlijn
- Dark mode
- WCAG 2.1 AA compliance

**PI-2 (Q3 2026):**
- Multi-source adapter framework (gemeenten, Officiële Bekendmakingen, bulk import)
- Bestuursorgaanportaal (document upload, API keys, compliance rapportage)
- Analytics & rapportage

## Technische stack

| Component | Technologie |
|---|---|
| **Backend** | Laravel 12 (PHP 8.4) |
| **Frontend** | Livewire, React 19, TypeScript, Tailwind CSS v4 |
| **Zoekmachine** | Typesense |
| **AI** | Ollama + Geitje-7b (lokaal, sovereign) |
| **Database** | PostgreSQL |
| **Licentie** | EUPL-1.2 |

## Installatie

### Vereisten

- PHP 8.4+
- Composer
- Node.js 22+
- PostgreSQL 16+
- Typesense 0.25+
- Ollama (optioneel, voor AI-functionaliteiten)

### Stappen

```bash
git clone git@github.com:ahamris/opub-eupl.git
cd opub-eupl

composer install
npm install

cp .env.example .env
php artisan key:generate
php artisan migrate

npm run build
```

### Configuratie

Pas het `.env`-bestand aan met uw lokale instellingen:

```env
# Database
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_DATABASE=opub

# Typesense
TYPESENSE_HOST=localhost
TYPESENSE_PORT=8108
TYPESENSE_API_KEY=your-key

# Ollama (optioneel)
OLLAMA_BASE_URL=http://localhost:11434
OLLAMA_MODEL=bramvanroy/geitje-7b-ultra:Q4_K_M

# Open Overheid synchronisatie
OPEN_OVERHEID_SYNC_ENABLED=true
```

## Gebruik

### Documenten synchroniseren

```bash
# Sync documenten van open.overheid.nl
php artisan open-overheid:sync --recent --days=7

# Sync naar Typesense zoekindex
php artisan typesense:sync

# AI-verrijking van documenten
php artisan documents:enrich --limit=100 --concurrency=4
```

### API

Volledige API-documentatie is beschikbaar via Swagger UI op [opub.nl/api/docs](https://opub.nl/api/docs).

| Methode | Endpoint | Beschrijving |
|---|---|---|
| `GET` | `/api/v2/search?q=...` | Zoeken met filters en facetten |
| `GET` | `/api/v2/documents/{id}` | Document ophalen met metadata |
| `GET` | `/api/v2/stats` | Platformstatistieken |
| `GET` | `/api/v2/documents/{id}/similar` | Vergelijkbare documenten (vector) |
| `GET` | `/api/v2/dossiers` | Dossiers/collecties |
| `GET` | `/api/v2/organisations/{name}` | Organisatie detail + stats |
| `POST` | `/api/v2/ingest` | Document aanleveren |
| `POST` | `/api/v2/ingest/batch` | Batch aanlevering (max 100) |
| `POST` | `/api/v2/chat/send` | AI-chat bericht (streaming) |

### Aansluiten als bestuursorgaan

Elk bestuursorgaan kan kosteloos aansluiten op OpenPublicaties. Geen licentiekosten, geen contracten.

1. Neem contact op via [opub.nl/contact](https://opub.nl/contact)
2. Ontvang uw API-sleutel
3. Lever documenten aan via de API of het uploadportaal

## Bijdragen

Bijdragen zijn van harte welkom! Of het nu gaat om code, documentatie, onderzoek of ideeën — elke bijdrage telt. Zie [CONTRIBUTING.md](CONTRIBUTING.md) voor richtlijnen en het bijdrageproces.

Dit is een vroeg stadium project: er is ruimte voor fundamentele bijdragen en nieuwe richtingen.

## Licentie

Dit project is gelicentieerd onder de **European Union Public Licence v. 1.2 (EUPL-1.2)**. Zie [LICENSE](LICENSE) voor de volledige licentietekst.

### Belangrijk

- **Commercieel gebruik** van dit platform of afgeleiden daarvan wordt ontmoedigd zonder voorafgaand overleg met CodeLabs B.V.
- **Ongereviewde forks** worden ontmoedigd. Wilt u het project forken of een afgeleide maken? Open eerst een [GitHub Issue](https://github.com/ahamris/opub-eupl/issues) zodat we kunnen afstemmen en samenwerken in plaats van fragmenteren.

## Contact

**CodeLabs B.V.** — Ontwikkelaar en beheerder van OpenPublicaties

- Website: [code-labs.nl](https://code-labs.nl)
- E-mail: info@codelabs.nl
- Telefoon: +31 (0)85 212 9557
- Adres: Kamperingweg 45C, 2803 PE Gouda

---

*OpenPublicaties is een initiatief van CodeLabs B.V. en groeit met bijdragen uit de community. Samen werken we aan een transparantere overheid.*
