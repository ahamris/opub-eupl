# Open Overheid Platform

A modern, accessible search platform for Dutch government documents (Wet Open Overheid - Woo). This application provides a user-friendly interface to search, filter, and explore publicly available government documents from the Open Overheid API.

## 🎯 Overview

The Open Overheid Platform enables citizens to:
- **Search** through government documents with full-text search
- **Filter** by date, document type, theme, and organization
- **View** detailed document metadata and information
- **Export** documents in JSON/XML formats
- **Access** documents directly from open.overheid.nl

## ✨ Features

### Current Features ✅

- **Advanced Search**
  - Full-text search across titles, descriptions, and content
  - Search only in titles option
  - PostgreSQL full-text search with Dutch language support

- **Filtering & Sorting**
  - Date filters (week, month, year)
  - Document type filter
  - Theme filter
  - Organization filter
  - Dynamic filter counts based on current results
  - Sort by relevance, publication date, or modified date

- **User Interface**
  - Material Design 3 inspired UI
  - WCAG 2.2 AA compliant
  - Responsive design
  - Premium, Apple-like aesthetic
  - Font Awesome icons
  - Dark mode support

- **Document Management**
  - Detailed document view with metadata
  - JSON/XML export
  - Direct links to open.overheid.nl
  - Clickable organization filter buttons
  - PDF icon indicators

- **Pagination & Navigation**
  - Results per page (10, 20, 50)
  - Page navigation
  - Breadcrumb navigation

## 🚀 Quick Start

### Option 1: One-Click Installation (Recommended)

The easiest way to get started is using our one-click installer:

```bash
chmod +x install.sh
./install.sh
```

This will install and configure everything automatically:
- Docker & Docker Compose
- PostgreSQL 18 (in Docker)
- pgAdmin 4 (database UI)
- Typesense (search engine)
- Redis (cache and session storage)
- Laravel application

**Prerequisites for installer:**
- Linux/macOS/Windows (with WSL2)
- Internet connection
- sudo/administrator access (for Docker installation)

After installation, just run `php artisan serve` and you're ready!

### Option 2: Manual Installation

If you prefer to set up manually or already have services running:

**Prerequisites:**
- PHP 8.4+ (8.2+ minimum)
- Composer
- PostgreSQL 18+ (or use Docker)
- Redis 7+ (or use Docker)
- Node.js v25.2.1+ & npm
- Typesense (or use Docker)
- Laravel Herd (or equivalent local development environment)

**Quick Installation (Recommended):**
```bash
chmod +x install.sh
./install.sh
```
See [guides/installation/INSTALLATION.md](guides/installation/INSTALLATION.md) for details.

**Manual Installation Steps:**

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd oo
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure services**
   - Update `.env` with your PostgreSQL credentials
   - Configure Redis connection (default: localhost:6379)
   - Configure Typesense (default: localhost:8108)
   - Run migrations:
     ```bash
     php artisan migrate
     ```

5. **Build assets**
   ```bash
   npm run build
   # or for development:
   npm run dev
   ```

6. **Start the application**
   ```bash
   php artisan serve
   ```

## 🧪 Testing

The project uses [Pest PHP](https://pestphp.com) for testing.

### Run Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Generate test report
php guides/test/generate-test-report.php
```

### Test Coverage

- **55 tests passing** ✅
- **14 tests skipped** - Missing features (intentionally skipped)
- **0 tests failing** ✅

All core functionality is working correctly. Skipped tests represent planned features documented in [`guides/project/missing-features-analysis.md`](guides/project/missing-features-analysis.md).

See [`guides/test/`](guides/test/) for detailed test reports.

## 📁 Project Structure

```
oo/
├── app/
│   ├── Console/Commands/     # Artisan commands
│   ├── Http/Controllers/      # Application controllers
│   ├── Models/                # Eloquent models
│   └── Services/             # Business logic services
├── database/
│   ├── factories/             # Model factories
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── guides/                    # Documentation and guides
│   ├── design/                # Design system documentation
│   ├── project/               # Project documentation
│   ├── reference/             # Reference documents
│   └── test/                  # Test reports and documentation
├── resources/
│   ├── css/                   # Stylesheets
│   ├── js/                    # JavaScript files
│   └── views/                 # Blade templates
├── routes/
│   ├── web.php                # Web routes
│   └── api.php                # API routes
└── tests/
    └── Feature/               # Feature tests
```

## 🎨 Design System

The platform follows:
- **Material Design 3** principles
- **NL Design System** guidelines (Dutch government)
- **WCAG 2.2 AA** accessibility standards
- **Tailwind CSS v4** for styling
- **Flux UI Theme** (blue accent, neutral base)

See [`guides/design/`](guides/design/) for detailed design documentation.

## 📚 Documentation

Comprehensive documentation is available in the [`guides/`](guides/) directory:

- **[Design System](guides/design/)** - UI/UX guidelines and design patterns
- **[Project Documentation](guides/project/)** - Feature analysis and architecture
- **[Test Reports](guides/test/)** - Testing documentation and reports
- **[Reference Documents](guides/reference/)** - Specifications and historical docs

## 🔧 Configuration

### Environment Variables

Key environment variables:

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
```

See `.env.example` for complete configuration options.

## 🛠️ Technology Stack

- **Backend**: Laravel 12 (PHP 8.4+)
- **Database**: PostgreSQL 18+ with full-text search
- **Cache**: Redis 7+ (required for optimal performance)
- **Search Engine**: Typesense (required for advanced search features)
- **Frontend**: Blade templates, Tailwind CSS v4, Vite
- **Icons**: Font Awesome Free 6.5.2
- **Testing**: Pest PHP v4

## 📊 Current Status

### Working Features ✅
- Search functionality
- Filtering (date, type, theme, organization)
- Sorting and pagination
- Document detail view
- JSON/XML export
- Dynamic filter counts
- External links to open.overheid.nl

### Missing Features ❌
See [`guides/project/missing-features-analysis.md`](guides/project/missing-features-analysis.md) for complete list:
- Custom date range picker (High Priority)
- File type filter
- Enhanced sorting labels
- Collapsible filter sections
- And more...

## 🚀 Deployment

### Production Deployment

For production deployment, see the comprehensive [DEPLOYMENT.md](DEPLOYMENT.md) guide which includes:
- Production deployment script (`deploy.sh`)
- Docker production configuration (`docker-compose.prod.yml`)
- Web server configuration (Nginx/Apache)
- Queue worker setup
- Monitoring and backup strategies
- Security hardening checklist

**Quick Production Deploy:**
```bash
chmod +x deploy.sh
APP_ENV=production ./deploy.sh
```

### Development Installation

For a complete development setup with everything included, use the provided installation script:

```bash
chmod +x install.sh
./install.sh
```

This script provides a **complete one-click installation** that sets up:

✅ **Docker & Docker Compose** (if not installed)  
✅ **PostgreSQL 18** (latest version in Docker - [PostgreSQL.org](https://www.postgresql.org/))  
✅ **pgAdmin 4** (latest - database management UI at http://localhost:5050 - [pgAdmin.org](https://www.pgadmin.org/))  
✅ **Typesense** (latest - search engine at http://localhost:8108 - [Typesense.org](https://typesense.org/))  
✅ **Redis** (latest - cache and session storage at localhost:6379 - [Redis.io](https://redis.io/))  
✅ **Laravel Application** (configured and ready)  
✅ **Database Migrations** (automatically run)  

**What you get:**
- All services running in Docker containers
- Auto-generated secure passwords (saved to `.docker-secrets.txt`)
- Fully configured `.env` file
- Ready-to-use development environment

**After installation:**
1. Start Laravel: `php artisan serve`
2. Access app: http://localhost:8000
3. Access pgAdmin: http://localhost:5050 (credentials in `.docker-secrets.txt`)
4. Access Typesense: http://localhost:8108 (API key in `.docker-secrets.txt`)
5. Redis is available at localhost:6379 (no password by default)

See [`install.sh`](install.sh) for complete details.

### Manual VPS Deployment

The application is ready for VPS deployment. See deployment documentation in [`guides/project/`](guides/project/).

## 🤝 Contributing

1. Review the [project documentation](guides/project/)
2. Check [missing features](guides/project/missing-features-analysis.md)
3. Write tests for new features
4. Follow the [design system](guides/design/)
5. Generate test reports after changes

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

## 📋 Production Checklist

Before deploying to production, review the [PRODUCTION_CHECKLIST.md](PRODUCTION_CHECKLIST.md) to ensure all security, performance, and configuration requirements are met.

## 🔗 Links

- **Open Overheid API**: https://open.overheid.nl
- **Reference Site**: https://open.minvws.nl
- **Laravel Documentation**: https://laravel.com/docs
- **Pest PHP**: https://pestphp.com

## 📞 Support

For questions or issues:
1. Check the [documentation](guides/)
2. Review [test reports](guides/test/)
3. Check [missing features analysis](guides/project/missing-features-analysis.md)

---

---

**Version**: 1.0  
**Last Updated**: 2024-12-12  
**Status**: Production Ready (Core Features Complete)

## 📋 Missing Features

The following features are planned but not yet implemented. See [`guides/project/missing-features-analysis.md`](guides/project/missing-features-analysis.md) for details:

### High Priority
- Custom date range picker (date input fields with calendar)
- File type filter (PDF, Word, Email, etc.)

### Medium Priority
- Hierarchical/expandable filter categories
- Decision type filter (Soort besluit)
- Enhanced result display (page count, disclosure status, document number)

### Low Priority
- Assessment grounds filter (Beoordelingsgronden)
- Result limit notice
- Collapsible filter sections
- Enhanced sorting labels ("Nieuwste bovenaan", "Oudste bovenaan")
# osoo
