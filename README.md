# Open Overheid Platform

Een modern, toegankelijk zoekplatform voor Nederlandse overheidsdocumenten (Wet Open Overheid - Woo). Deze applicatie biedt een gebruiksvriendelijke interface om te zoeken, filteren en verkennen in publiek beschikbare overheidsdocumenten van de Open Overheid API.

## 🎯 Overzicht

Het Open Overheid Platform stelt burgers in staat om:
- **Zoeken** door overheidsdocumenten met full-text search
- **Filteren** op datum, documenttype, thema en organisatie
- **Bekijken** van gedetailleerde documentmetadata en informatie
- **Exporteren** van documenten in JSON/XML formaten
- **Toegang** tot documenten direct van open.overheid.nl
- **AI-bevraging** met natuurlijke taal queries (premium feature)

## ✨ Features

### 🔍 Zoeken & Filteren

- **Geavanceerd Zoeken**
  - Full-text search in titels, beschrijvingen en content
  - Zoeken alleen in titels optie
  - PostgreSQL full-text search met Nederlandse taalondersteuning
  - Live search met real-time resultaten
  - Intelligente autocomplete met duidelijke scheiding tussen zoeken en filteren
  - Typesense integratie voor snelle semantische zoekopdrachten
  - Query parsing service voor detectie van zoekwoorden vs. filterwaarden

- **Filteren & Sorteren**
  - Datumfilters (week, maand, jaar, custom range)
  - Documenttype filter
  - Thema filter
  - Organisatie filter
  - Informatiecategorie filter
  - Bestandstype filter
  - Dynamische filter counts gebaseerd op huidige resultaten
  - Sorteren op relevantie, publicatiedatum of wijzigingsdatum
  - Actieve filters ribbon met verwijderfunctionaliteit

### 🤖 AI Features

- **AI Chat Interface**
  - Natuurlijke taal queries
  - Context-aware antwoorden gebaseerd op gevonden documenten
  - Volledige bronvermelding
  - B1-niveau begrijpelijke antwoorden
  - Transparantie en verifieerbaarheid

- **Dossier AI Enhancement**
  - Automatische samenvattingen voor dossiers
  - B1-niveau tekstconversie
  - Audio generatie (TTS)
  - Premium research features

### 📄 Document Management

- **Documentweergave**
  - Gedetailleerde documentweergave met metadata
  - JSON/XML export
  - Directe links naar open.overheid.nl
  - Klikbare organisatiefilter knoppen
  - PDF icoon indicatoren
  - Highlighting van zoektermen

- **Dossiers & Thema's**
  - Bladeren door documenten per dossier
  - Dossierdetails en gerelateerde documenten bekijken
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
  - Responsive design
  - Premium, Apple-achtige esthetiek
  - Font Awesome iconen
  - Dark mode ondersteuning
  - Tailwind CSS v4

- **Navigatie & Paginering**
  - Resultaten per pagina (10, 20, 50)
  - Pagina navigatie
  - Breadcrumb navigatie
  - Keyboard navigatie in autocomplete

## 🚀 Quick Start

### Vereisten

- PHP 8.4+ (8.2+ minimum)
- Composer
- PostgreSQL 18+ (of gebruik Docker)
- Redis 7+ (of gebruik Docker)
- Node.js v25.2.1+ & npm
- Typesense (of gebruik Docker)
- Laravel Herd (of equivalent lokale ontwikkelomgeving)

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

4. **Configureer services**
   - Update `.env` met je PostgreSQL credentials
   - Configureer Redis connectie (default: localhost:6379)
   - Configureer Typesense (default: localhost:8108)
   - Configureer Gemini API key voor AI features (optioneel)
   - Run migrations:
     ```bash
     php artisan migrate
     ```

5. **Build assets**
   ```bash
   npm run build
   # of voor development:
   npm run dev
   ```

6. **Start de applicatie**
   ```bash
   php artisan serve
   ```

### Docker Setup (Optioneel)

Voor een complete development setup met Docker, zie [`guides/installation/INSTALLATION.md`](guides/installation/INSTALLATION.md).

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
```

### Test Coverage

- **55+ tests passing** ✅
- **0 tests failing** ✅

Alle core functionaliteit werkt correct. Zie [`tests/`](tests/) voor gedetailleerde test rapporten.

## 📁 Project Structuur

```
oo/
├── app/
│   ├── Console/Commands/          # Artisan commands
│   ├── Http/Controllers/         # Application controllers
│   │   ├── Auth/                  # Authentication controllers
│   │   ├── OpenOverheid/          # Document search controllers
│   │   └── TypesenseGuiController.php  # Typesense admin GUI
│   ├── Jobs/                      # Queue jobs
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
│   │   ├── implementation/         # Implementatie plannen
│   │   └── reference/             # Referentie documenten
│   ├── reference/                 # Referentie documenten
│   └── test/                      # Test rapporten
├── resources/
│   ├── css/                       # Stylesheets
│   ├── js/                        # JavaScript bestanden
│   └── views/                     # Blade templates
│       ├── auth/                  # Authentication views
│       ├── dossiers/              # Dossier views
│       ├── themas/                # Theme views
│       ├── tsgui/                 # Typesense GUI views
│       └── profile/               # Profile views
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
- **[Test Rapporten](guides/test/)** - Testing documentatie en rapporten
- **[Installatie Guides](guides/installation/)** - Installatie en deployment
- **[Referentie Documenten](guides/reference/)** - Specificaties en historische docs

## 🔧 Configuratie

### Environment Variabelen

Belangrijke environment variabelen:

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
REDIS_PASSWORD=null
REDIS_PORT=6379
CACHE_STORE=redis
REDIS_CLIENT=phpredis

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

# AI Features (Gemini)
GEMINI_API_KEY=your_gemini_api_key
GEMINI_MODEL=gemini-2.0-flash-exp
```

Zie `.env.example` voor complete configuratie opties.

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

### Recent Toegevoegde Features 🆕

- Intelligente query parsing (zoekwoord vs. filter detectie)
- Verbeterde autocomplete met duidelijke acties
- Zoekterm als actief filter in ribbon
- AI chat interface met bronvermelding
- Dossier metadata pre-computing
- Performance optimalisaties

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

## 📝 License

Dit project is open source en beschikbaar onder de [MIT License](LICENSE).

## 🔗 Links

- **Open Overheid API**: https://open.overheid.nl
- **Referentie Site**: https://open.minvws.nl
- **Laravel Documentatie**: https://laravel.com/docs
- **Pest PHP**: https://pestphp.com
- **Typesense**: https://typesense.org

## 📞 Support

Voor vragen of problemen:
1. Check de [documentatie](guides/)
2. Review [test rapporten](guides/test/)
3. Check [missing features analyse](guides/project/features/missing-features-analysis.md)

---

**Versie**: 2.0  
**Laatste Update**: 2025-01-20  
**Status**: Production Ready (Core Features Complete + AI Features)
