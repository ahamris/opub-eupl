# oPub — OpenPublicaties

> Open source Woo-voorziening voor de hele overheid — kosteloos.

oPub is een volledig open source Woo-zoekplatform waarop alle bestuursorganen kosteloos kunnen aansluiten. Doorzoek 641.000+ actief openbaar gemaakte overheidsdocumenten. Direct. Gratis. Open.

**🌐 [opub.nl](https://opub.nl)** · **📖 [API Docs](https://opub.nl/api/docs)** · **🏛️ [developer.overheid.nl](https://developer.overheid.nl)**

---

## Kenmerken

| Feature | Beschrijving |
|---|---|
| **Federatief zoekportaal** | Doorzoek alle Woo-publicaties via één zoekinterface met filters en facets |
| **Sovereign AI** | AI-chat via Ollama + Geitje (Nederlands LLM). Lokale verwerking, geen externe cloud |
| **Open API** | Gedocumenteerde REST API voor zoeken, aanleveren en integreren |
| **Attendering** | Automatische meldingen bij nieuwe publicaties die matchen met uw zoekopdracht |
| **Buffer-API** | Automatische doorlevering naar de Generieke Woo-voorziening en open.overheid.nl |
| **Dashboarding** | Publicatievolumes, compliance-indicatoren en trends per organisatie |
| **Gratis** | Geen licentiekosten, geen abonnementen, geen vendor lock-in |

## Technologie

- **Backend**: Laravel 12 (PHP 8.4)
- **Frontend**: React 19 + TypeScript + Tailwind CSS v4 + UntitledUI
- **Zoeken**: Typesense (full-text + facets)
- **AI**: Ollama + Geitje-7b (sovereign, lokaal op GPU)
- **Database**: PostgreSQL 18
- **Licentie**: EUPL 1.2

## Snel starten

### Vereisten

- PHP 8.4+
- Node.js 22+
- PostgreSQL 16+
- Typesense 0.25+
- Ollama (optioneel, voor AI features)

### Installatie

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

# Open Overheid sync
OPEN_OVERHEID_SYNC_ENABLED=true
```

### Commando's

```bash
# Sync documenten van open.overheid.nl
php artisan open-overheid:sync --recent --days=7

# Sync naar Typesense zoekindex
php artisan typesense:sync

# AI-verrijking van documenten
php artisan documents:enrich --limit=100 --concurrency=4

# Alle schedules draaien
php artisan schedule:work
```

## API

Volledige API documentatie beschikbaar via Swagger UI: [opub.nl/api/docs](https://opub.nl/api/docs)

### Endpoints

| Methode | Endpoint | Beschrijving |
|---|---|---|
| `GET` | `/api/v2/search?q=...` | Zoeken met filters en facets |
| `GET` | `/api/v2/documents/{id}` | Document ophalen met AI-metadata |
| `GET` | `/api/v2/stats` | Platform statistieken |
| `POST` | `/api/v2/ingest` | Document aanleveren |
| `POST` | `/api/v2/ingest/batch` | Batch aanlevering (max 100) |
| `POST` | `/api/v2/chat/send` | AI-chat bericht |

### Aanleveren via API

```bash
curl -X POST https://opub.nl/api/v2/ingest \
  -H "X-OPUB-API-KEY: uw-api-key" \
  -H "Content-Type: application/json" \
  -d '{
    "external_id": "doc-001",
    "title": "Besluit gemeenteraad",
    "organisation": "Gemeente Amsterdam",
    "publication_date": "2026-03-15",
    "category": "Besluiten"
  }'
```

## Aansluiten als bestuursorgaan

Elk bestuursorgaan kan kosteloos aansluiten op oPub. Geen licentiekosten, geen contracten.

1. Neem contact op via [opub.nl/contact](https://opub.nl/contact)
2. Ontvang uw API-sleutel
3. Lever documenten aan via de API of het uploadportaal
4. oPub verzendt automatisch door naar de Generieke Woo-voorziening

## Sovereign AI

oPub gebruikt **Ollama** als lokale AI-inferentielaag met het **Geitje**-taalmodel — een Nederlands open source LLM. Alle AI-verwerking vindt lokaal plaats: geen externe cloudproviders, geen data die de eigen infrastructuur verlaat.

## Gerelateerde projecten

| Project | Beschrijving |
|---|---|
| [OPMS](https://github.com/code-labs-nl) | Open Publicatie Management Systeem — backoffice voor Woo-verzoekbeheer |
| [OpenCivics](https://github.com/code-labs-nl) | Open source data-aanlevertool voor gemeenten |
| [ZEDB](https://github.com/code-labs-nl) | Open Zaak- en Dossierbeheersysteem |

## Bijdragen

Bijdragen zijn welkom! Zie [CONTRIBUTING.md](CONTRIBUTING.md) voor richtlijnen.

## Licentie

Dit project is gelicentieerd onder de **European Union Public Licence v. 1.2 (EUPL-1.2)**. Zie [LICENSE](LICENSE).

## Contact

- **CodeLabs B.V.** — Maker en open source steward
- **Adres**: Kamperingweg 45C, 2803 PE Gouda
- **E-mail**: info@codelabs.nl
- **Telefoon**: +31 (0)85 212 9557
- **Website**: [code-labs.nl](https://code-labs.nl)
