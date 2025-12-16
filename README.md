# Open Overheid Platform

Een modern, toegankelijk zoekplatform voor Nederlandse overheidsdocumenten (Wet Open Overheid - Woo). Deze applicatie biedt een gebruiksvriendelijke interface om te zoeken, filteren en verkennen in publiek beschikbare overheidsdocumenten van de Open Overheid API.

[![Laravel](https://img.shields.io/badge/Laravel-12-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4+-blue.svg)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-18+-blue.svg)](https://postgresql.org)
[![Pest](https://img.shields.io/badge/Pest-4-green.svg)](https://pestphp.com)

## 🎯 Overzicht

Het Open Overheid Platform stelt burgers in staat om:
- **Zoeken** door overheidsdocumenten met full-text search en semantische zoekopdrachten
- **Filteren** op datum, documenttype, thema, organisatie en categorie
- **Bekijken** van gedetailleerde documentmetadata en informatie
- **AI-bevraging** met natuurlijke taal queries en context-aware antwoorden
- **Exporteren** van documenten in JSON/XML formaten
- **Toegang** tot documenten direct van open.overheid.nl

## ✨ Features

### 🔍 Geavanceerd Zoeken

- **Full-Text Search**
  - PostgreSQL full-text search met Nederlandse taalondersteuning
  - Typesense integratie voor snelle semantische zoekopdrachten
  - Zoeken in titels, beschrijvingen en content
  - Zoeken alleen in titels optie

- **Intelligente Autocomplete**
  - Real-time zoeksuggesties tijdens typen
  - Duidelijke scheiding tussen "Zoeken naar..." en "Filter op..." acties
  - Query parsing service voor detectie van zoekwoorden vs. filterwaarden
  - Meervoud/singular matching voor categorieën
  - Keyboard navigatie (pijltjestoetsen, Enter)

- **Live Search**
  - Real-time resultaten tijdens typen
  - Debounced input voor optimale performance
  - Context-aware filter suggesties

### 🎛️ Filteren & Sorteren

- **Datumfilters**
  - Afgelopen week, maand, jaar
  - Custom datum range (van/tot)
  - Publicatiedatum en wijzigingsdatum

- **Document Filters**
  - Documenttype (Besluit, Kamerstuk, etc.)
  - Thema (Milieu, Onderwijs, etc.)
  - Organisatie (Ministeries, Gemeenten, etc.)
  - Informatiecategorie (Advies, Beleid, etc.)
  - Bestandstype (PDF, Word, etc.)

- **Dynamische Filter Counts**
  - Context-aware filter opties gebaseerd op huidige resultaten
  - Real-time updates bij filter wijzigingen
  - Caching voor optimale performance

- **Actieve Filters Ribbon**
  - Overzicht van alle actieve filters
  - Eén-klik verwijderen van individuele filters
  - "Alle filters wissen" functionaliteit
  - Zoekterm als actief filter

- **Sorteren**
  - Op relevantie (standaard)
  - Op publicatiedatum (nieuwste eerst / oudste eerst)
  - Op wijzigingsdatum

### 🤖 AI Features

- **AI Chat Interface**
  - Natuurlijke taal queries
  - Context-aware antwoorden gebaseerd op gevonden documenten
  - Volledige bronvermelding bij elk antwoord
  - B1-niveau begrijpelijke antwoorden
  - Transparantie en verifieerbaarheid
  - Disclaimer over AI-limitaties

- **Dossier AI Enhancement**
  - Automatische samenvattingen voor dossiers
  - B1-niveau tekstconversie
  - Audio generatie (TTS) - gepland
  - Premium research features

### 📄 Document Management

- **Documentweergave**
  - Gedetailleerde documentweergave met volledige metadata
  - JSON/XML export functionaliteit
  - Directe links naar open.overheid.nl
  - Klikbare organisatie- en thema-filter knoppen
  - PDF icoon indicatoren
  - Highlighting van zoektermen in resultaten

- **Dossiers & Thema's**
  - Bladeren door documenten per dossier
  - Dossierdetails met gerelateerde documenten
  - Bladeren door documenten per thema
  - Thema-gebaseerd filteren en navigeren
  - Dossier metadata pre-computing voor performance

### 👤 Gebruikersbeheer

- **Authenticatie & Profiel**
  - Gebruikersregistratie en login (Laravel Breeze)
  - Email verificatie
  - Wachtwoord reset functionaliteit
  - Gebruikersprofielbeheer
  - Beschermde admin routes

### 🛠️ Admin Features

- **Typesense Admin GUI**
  - Web-gebaseerde interface voor Typesense beheer
  - Collections bekijken en beheren
  - Documenten zoeken binnen collections
  - Collection schema's en statistieken bekijken
  - Document CRUD operaties (bekijken, toevoegen, verwijderen)
  - Beschermd door authenticatie

### 🎨 User Interface

- **Design System**
  - Material Design 3 geïnspireerde UI
  - WCAG 2.2 AA compliant
  - Responsive design (mobile-first)
  - Premium, Apple-achtige esthetiek
  - Font Awesome iconen
  - Dark mode ondersteuning
  - Tailwind CSS v4

- **Navigatie & Paginering**
  - Resultaten per pagina (10, 20, 50)
  - Pagina navigatie met eerste/laatste pagina links
  - Breadcrumb navigatie
  - Keyboard navigatie in autocomplete
  - Smooth scrolling en transitions

## 🚀 Quick Start

### Vereisten

- **PHP**: 8.4+ (8.2+ minimum)
- **Composer**: 2.0+
- **PostgreSQL**: 18+ (of gebruik Docker)
- **Redis**: 7+ (of gebruik Docker)
- **Node.js**: v25.2.1+ & npm
- **Typesense**: 0.25+ (of gebruik Docker)
- **Laravel Herd**: (of equivalent lokale ontwikkelomgeving)

### Installatie

1. **Clone de repository**
   ```bash
   git clone <repository-url>
   cd oo
   ```

2. **Installeer dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configureer environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configureer services in `.env`**
   ```env
   # Database
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=open_overheid
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

   # Redis Cache
   REDIS_HOST=127.0.0.1
   REDIS_PORT=6379
   CACHE_STORE=redis

   # Typesense Search Engine
   TYPESENSE_SYNC_ENABLED=true
   TYPESENSE_API_KEY=your_api_key
   TYPESENSE_HOST=localhost
   TYPESENSE_PORT=8108
   TYPESENSE_PROTOCOL=http

   # Open Overheid API
   OPEN_OVERHEID_BASE_URL=https://open.overheid.nl/overheid/openbaarmakingen/api/v0
   OPEN_OVERHEID_SYNC_ENABLED=true
   OPEN_OVERHEID_USE_LOCAL_SEARCH=true

   # AI Features (Gemini) - Optioneel
   GEMINI_API_KEY=your_gemini_api_key
   GEMINI_MODEL=gemini-2.0-flash-exp
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Build assets**
   ```bash
   npm run build
   # of voor development:
   npm run dev
   ```

7. **Start de applicatie**
   ```bash
   php artisan serve
   ```

### Docker Setup (Optioneel)

Voor een complete development setup met Docker, zie [`guides/installation/INSTALLATION.md`](guides/installation/INSTALLATION.md).

## 📥 Document Synchronisatie

Het platform synchroniseert documenten van de Open Overheid API naar de lokale PostgreSQL database. Gebruik het `open-overheid:sync` commando om documenten te synchroniseren.

### Sync Commando Parameters

Het `open-overheid:sync` commando ondersteunt de volgende opties:

| Parameter | Type | Beschrijving | Voorbeeld |
|-----------|------|--------------|-----------|
| `--id=` | string | Sync een specifiek document via external ID | `--id=oep-ob-12345` |
| `--week` | flag | Sync alle documenten van deze week (maandag t/m zondag) | `--week` |
| `--from=` | string | Startdatum in DD-MM-YYYY formaat | `--from=01-12-2024` |
| `--to=` | string | Einddatum in DD-MM-YYYY formaat | `--to=31-12-2024` |
| `--no-retry` | flag | Sla retry van gefaalde documenten over | `--no-retry` |

**Let op:** Als geen parameters worden opgegeven, synchroniseert het commando **alle** documenten uit de API.

### Gebruiksvoorbeelden

#### 1. Sync alle documenten
```bash
# Sync alle beschikbare documenten (kan lang duren!)
php artisan open-overheid:sync
```

#### 2. Sync een specifiek document
```bash
# Sync een document via external ID
php artisan open-overheid:sync --id=oep-ob-12345
```

#### 3. Sync documenten van deze week
```bash
# Sync alle documenten gepubliceerd van maandag t/m zondag van deze week
php artisan open-overheid:sync --week
```

#### 4. Sync documenten in een datum bereik
```bash
# Sync documenten van 1 december 2024 tot 31 december 2024
php artisan open-overheid:sync --from=01-12-2024 --to=31-12-2024

# Sync documenten vanaf een specifieke datum (tot nu)
php artisan open-overheid:sync --from=01-01-2024

# Sync documenten tot een specifieke datum (vanaf het begin)
php artisan open-overheid:sync --to=31-12-2024
```

#### 5. Sync zonder retry van gefaalde documenten
```bash
# Sync documenten maar probeer gefaalde documenten niet opnieuw
php artisan open-overheid:sync --from=01-12-2024 --to=31-12-2024 --no-retry
```

#### 6. Gecombineerde voorbeelden
```bash
# Sync deze week zonder retry
php artisan open-overheid:sync --week --no-retry

# Sync laatste maand
php artisan open-overheid:sync --from=01-11-2024 --to=30-11-2024
```

### Sync Output

Het commando toont gedetailleerde informatie tijdens het synchroniseren:

```
Syncing documents from date range...
  From: 01-12-2024
  To: 31-12-2024
Found 1250 documents to sync.

 1250/1250 [████████████████████████] 100% ETA: 00:00:00

✅ Sync completed!
   Total: 1250 documents
   Created: 850 documents
   Updated: 350 documents
   Skipped: 50 documents (already up-to-date)
   Retried: 5 documents
   Errors: 0 documents
```

### Sync Gedrag

- **Automatische Retry**: Gefaalde documenten worden automatisch opnieuw geprobeerd (tenzij `--no-retry` wordt gebruikt)
- **Incrementele Updates**: Alleen gewijzigde documenten worden bijgewerkt
- **Progress Bar**: Real-time voortgang met ETA, error count en skipped count
- **Logging**: Alle sync activiteiten worden gelogd in `storage/logs/laravel.log`
- **Dossier Detectie**: Documenten die deel uitmaken van dossiers triggeren automatisch metadata pre-computing jobs

### Configuratie

Sync gedrag kan worden geconfigureerd in `config/open_overheid.php`:

```php
'sync' => [
    'enabled' => env('OPEN_OVERHEID_SYNC_ENABLED', true),
    'batch_size' => env('OPEN_OVERHEID_SYNC_BATCH_SIZE', 50),
    'days_back' => env('OPEN_OVERHEID_SYNC_DAYS_BACK', 1), // Standaard sync laatste 24 uur
],
```

### Automatische Synchronisatie

Voor productie omgevingen, overweeg het instellen van een cron job of scheduler:

```php
// In app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Sync recente documenten elke 6 uur
    $schedule->command('open-overheid:sync --week')
             ->everySixHours();
}
```

## 🧪 Testing

Het project gebruikt [Pest PHP v4](https://pestphp.com) voor testing.

### Tests Uitvoeren

```bash
# Run alle tests
php artisan test

# Run specifieke test suite
php artisan test --testsuite=Feature

# Run met filter
php artisan test --filter=SearchResultsTest

# Run met coverage
php artisan test --coverage
```

### Test Status

- **55+ tests passing** ✅
- **0 tests failing** ✅
- **Feature tests**: Search, Filter, Navigation, Dossier, AI
- **Unit tests**: Services, Models

Zie [`tests/`](tests/) voor gedetailleerde test rapporten.

## 📁 Project Structuur

```
oo/
├── app/
│   ├── Console/Commands/          # Artisan commands (sync, precompute, etc.)
│   ├── Http/Controllers/         # Application controllers
│   │   ├── Auth/                  # Authentication controllers
│   │   ├── OpenOverheid/          # Document search controllers
│   │   └── TypesenseGuiController.php  # Typesense admin GUI
│   ├── Jobs/                      # Queue jobs (sync, enhancement, etc.)
│   ├── Models/                    # Eloquent models
│   ├── Services/                  # Business logic services
│   │   ├── AI/                    # AI services (Gemini)
│   │   ├── OpenOverheid/         # Open Overheid API services
│   │   └── Typesense/            # Typesense services
│   └── View/                      # View composers
├── database/
│   ├── factories/                 # Model factories
│   ├── migrations/                # Database migrations
│   └── seeders/                   # Database seeders
├── guides/                        # Documentatie en guides
│   ├── design/                    # Design system documentatie
│   ├── installation/              # Installatie guides
│   ├── project/                   # Project documentatie
│   │   ├── epics/                 # Epic documentatie
│   │   ├── features/              # Feature analyse
│   │   └── implementation/         # Implementatie plannen
│   ├── reference/                 # Referentie documenten
│   └── test/                      # Test rapporten
├── resources/
│   ├── css/                       # Stylesheets
│   ├── js/                        # JavaScript bestanden
│   └── views/                     # Blade templates
│       ├── auth/                  # Authentication views
│       ├── dossiers/              # Dossier views
│       ├── themas/                # Theme views
│       └── tsgui/                 # Typesense GUI views
├── routes/
│   ├── web.php                    # Web routes
│   ├── auth.php                   # Authentication routes
│   └── ai.php                     # AI routes
└── tests/
    ├── Feature/                   # Feature tests
    └── Unit/                       # Unit tests
```

## 🎨 Design System

Het platform volgt:
- **Material Design 3** principes
- **NL Design System** richtlijnen (Nederlandse overheid)
- **WCAG 2.2 AA** toegankelijkheidsstandaarden
- **Tailwind CSS v4** voor styling
- **Flux UI Theme** (blauw accent, neutrale basis)

Zie [`guides/design/`](guides/design/) voor gedetailleerde design documentatie.

## 📚 Documentatie

Uitgebreide documentatie is beschikbaar in de [`guides/`](guides/) directory:

- **[Design System](guides/design/)** - UI/UX richtlijnen en design patronen
- **[Project Documentatie](guides/project/)** - Feature analyse en architectuur
- **[Epics](guides/project/epics/)** - Grote feature initiatieven
  - [AI Chat Epic](guides/project/epics/AI_CHAT_EPIC.md)
  - [Search & Filter Improvement Epic](guides/project/epics/SEARCH_FILTER_IMPROVEMENT_EPIC.md)
- **[Test Rapporten](guides/test/)** - Testing documentatie en rapporten
- **[Installatie Guides](guides/installation/)** - Installatie en deployment
- **[Referentie Documenten](guides/reference/)** - Specificaties en historische docs

## 🛠️ Technology Stack

- **Backend**: Laravel 12 (PHP 8.4+)
- **Database**: PostgreSQL 18+ met full-text search
- **Cache**: Redis 7+ (vereist voor optimale performance)
- **Search Engine**: Typesense (vereist voor geavanceerde zoekfeatures)
- **AI**: Google Gemini API (voor AI features)
- **Frontend**: Blade templates, Tailwind CSS v4, Vite
- **Icons**: Font Awesome Free 6.5.2
- **Testing**: Pest PHP v4

## 📊 Huidige Status

### Werkende Features ✅

- **Core Search**: Full-text search met PostgreSQL en Typesense
- **Filtering**: Datum, type, thema, organisatie, categorie, bestandstype filters
- **Sorting & Pagination**: Meerdere sorteeropties en configureerbare resultaten per pagina
- **Document Management**: Detailweergave, JSON/XML export, metadata weergave
- **Dossiers**: Bladeren en bekijken van documenten per dossier
- **Thema's**: Bladeren door documenten per thema
- **Authenticatie**: Volledig gebruikersauthenticatiesysteem
- **Typesense GUI**: Admin interface voor Typesense beheer
- **Live Search**: Real-time zoeksuggesties en autocomplete
- **Dynamische Filter Counts**: Context-aware filter opties
- **AI Chat**: Natuurlijke taal queries met context-aware antwoorden
- **Dossier AI Enhancement**: Automatische samenvattingen en B1-conversie
- **Query Parsing**: Intelligente detectie van zoekwoorden vs. filterwaarden

### Recent Toegevoegde Features 🆕

- Intelligente query parsing (zoekwoord vs. filter detectie)
- Verbeterde autocomplete met duidelijke acties ("Zoeken naar..." vs "Filter op...")
- Zoekterm als actief filter in ribbon
- AI chat interface met bronvermelding en context-aware antwoorden
- Dossier metadata pre-computing voor performance
- Bestandstype filter met correcte counts
- Performance optimalisaties met caching

## 🚀 Deployment

Voor productie deployment, zie de uitgebreide [DEPLOYMENT.md](guides/installation/DEPLOYMENT.md) guide die bevat:
- Productie deployment script
- Docker productie configuratie
- Web server configuratie (Nginx/Apache)
- Queue worker setup
- Monitoring en backup strategieën
- Security hardening checklist

## 🤝 Contributing

1. Review de [project documentatie](guides/project/)
2. Check [missing features](guides/project/features/MISSING_FEATURES.md)
3. Schrijf tests voor nieuwe features
4. Volg het [design system](guides/design/)
5. Genereer test rapporten na wijzigingen
6. Run `vendor/bin/pint --dirty` voor code formatting

## 📝 License

Dit project is open source en beschikbaar onder de [MIT License](LICENSE).

## 🔗 Links

- **Open Overheid API**: https://open.overheid.nl
- **Referentie Site**: https://open.minvws.nl
- **Laravel Documentatie**: https://laravel.com/docs
- **Pest PHP**: https://pestphp.com
- **Typesense**: https://typesense.org
- **Material Design 3**: https://m3.material.io

## 📞 Support

Voor vragen of problemen:
1. Check de [documentatie](guides/)
2. Review [test rapporten](guides/test/)
3. Check [missing features analyse](guides/project/features/missing-features-analysis.md)

---

**Versie**: 2.0  
**Laatste Update**: 2025-01-20  
**Status**: Production Ready (Core Features Complete + AI Features)
