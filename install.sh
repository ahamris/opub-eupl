#!/bin/bash

# Open Overheid Platform - One-Click Installer
# This script provides a complete one-click installation including:
# - Docker & Docker Compose
# - Node.js v25.2.1 (Current) & npm - https://nodejs.org/en/download/current
# - PostgreSQL 18 (latest) in Docker - https://www.postgresql.org/
# - pgAdmin 4 (latest) - https://www.pgadmin.org/
# - Typesense (latest) - https://typesense.org/
# - Redis (latest) - https://redis.io/
# - Dockge (latest) - https://github.com/louislam/dockge
# - Laravel application setup

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Print colored output
print_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running as root
check_root() {
    if [ "$EUID" -eq 0 ]; then 
        print_error "Please do not run this script as root"
        exit 1
    fi
}

# Check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Detect OS
detect_os() {
    if [[ "$OSTYPE" == "linux-gnu"* ]]; then
        if [ -f /etc/os-release ]; then
            . /etc/os-release
            OS=$ID
            OS_VERSION=$VERSION_ID
        else
            print_error "Cannot detect OS version"
            exit 1
        fi
    elif [[ "$OSTYPE" == "darwin"* ]]; then
        OS="macos"
    else
        print_error "Unsupported OS: $OSTYPE"
        exit 1
    fi
    print_info "Detected OS: $OS"
}

# Install Docker
install_docker() {
    if command_exists docker; then
        print_success "Docker is already installed: $(docker --version)"
        return 0
    fi

    print_info "Installing Docker..."

    if [ "$OS" == "macos" ]; then
        print_warning "Please install Docker Desktop for macOS from https://www.docker.com/products/docker-desktop"
        print_info "Waiting for Docker to be installed..."
        while ! command_exists docker; do
            sleep 2
        done
        print_success "Docker detected!"
    elif [ "$OS" == "ubuntu" ] || [ "$OS" == "debian" ]; then
        sudo apt-get update
        sudo apt-get install -y \
            ca-certificates \
            curl \
            gnupg \
            lsb-release
        
        sudo mkdir -p /etc/apt/keyrings
        curl -fsSL https://download.docker.com/linux/$OS/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
        
        echo \
          "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/$OS \
          $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
        
        sudo apt-get update
        sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
        
        # Add current user to docker group
        sudo usermod -aG docker $USER
        print_warning "You may need to log out and log back in for Docker group changes to take effect"
    elif [ "$OS" == "fedora" ] || [ "$OS" == "rhel" ] || [ "$OS" == "centos" ]; then
        sudo dnf install -y dnf-plugins-core
        sudo dnf config-manager --add-repo https://download.docker.com/linux/$OS/docker-ce.repo
        sudo dnf install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
        sudo systemctl start docker
        sudo systemctl enable docker
        sudo usermod -aG docker $USER
        print_warning "You may need to log out and log back in for Docker group changes to take effect"
    else
        print_error "Automatic Docker installation not supported for $OS"
        print_info "Please install Docker manually from https://docs.docker.com/get-docker/"
        exit 1
    fi

    print_success "Docker installed successfully"
}

# Install Docker Compose (standalone if needed)
install_docker_compose() {
    if command_exists docker && docker compose version >/dev/null 2>&1; then
        print_success "Docker Compose is available: $(docker compose version)"
        return 0
    fi

    if command_exists docker-compose; then
        print_success "Docker Compose is installed: $(docker-compose --version)"
        return 0
    fi

    print_info "Docker Compose plugin should be included with Docker. If not, please install it manually."
}

# Install Node.js (Latest Current: v25.2.1)
install_nodejs() {
    if command_exists node; then
        NODE_VERSION=$(node --version | sed 's/v//')
        print_success "Node.js is already installed: v$NODE_VERSION"
        
        # Check if version is recent enough (v18+)
        MAJOR_VERSION=$(echo $NODE_VERSION | cut -d. -f1)
        if [ "$MAJOR_VERSION" -ge 18 ]; then
            print_info "Node.js version is sufficient (v$NODE_VERSION)"
            return 0
        else
            print_warning "Node.js version is too old (v$NODE_VERSION). Installing latest..."
        fi
    fi

    print_info "Installing Node.js v25.2.1 (Current)..."

    if [ "$OS" == "macos" ]; then
        print_warning "Please install Node.js for macOS from https://nodejs.org/en/download/current"
        print_info "Or use Homebrew: brew install node"
        print_info "Waiting for Node.js to be installed..."
        while ! command_exists node; do
            sleep 2
        done
        print_success "Node.js detected!"
    elif [ "$OS" == "ubuntu" ] || [ "$OS" == "debian" ]; then
        # Install Node.js using NodeSource repository
        curl -fsSL https://deb.nodesource.com/setup_25.x | sudo -E bash -
        sudo apt-get install -y nodejs
        
        # Verify npm is included
        if ! command_exists npm; then
            print_error "npm was not installed with Node.js"
            exit 1
        fi
    elif [ "$OS" == "fedora" ] || [ "$OS" == "rhel" ] || [ "$OS" == "centos" ]; then
        # Install Node.js using NodeSource repository
        curl -fsSL https://rpm.nodesource.com/setup_25.x | sudo bash -
        sudo dnf install -y nodejs
        
        # Verify npm is included
        if ! command_exists npm; then
            print_error "npm was not installed with Node.js"
            exit 1
        fi
    elif [ "$OS" == "arch" ]; then
        sudo pacman -S --noconfirm nodejs npm
    else
        print_error "Automatic Node.js installation not supported for $OS"
        print_info "Please install Node.js manually from https://nodejs.org/en/download/current"
        exit 1
    fi

    # Verify installation
    if command_exists node && command_exists npm; then
        print_success "Node.js installed: $(node --version)"
        print_success "npm installed: $(npm --version)"
    else
        print_error "Node.js or npm installation failed"
        exit 1
    fi
}

# Create docker-compose.yml with PostgreSQL, pgAdmin, Typesense, and Redis
create_docker_compose() {
    print_info "Creating docker-compose.yml with PostgreSQL, pgAdmin, Typesense, and Redis..."

    # Generate random passwords
    POSTGRES_PASSWORD=${POSTGRES_PASSWORD:-$(openssl rand -base64 32 | tr -d "=+/" | cut -c1-25)}
    POSTGRES_USER=${POSTGRES_USER:-openoverheid}
    POSTGRES_DB=${POSTGRES_DB:-open_overheid}
    PGADMIN_EMAIL=${PGADMIN_EMAIL:-admin@openoverheid.local}
    PGADMIN_PASSWORD=${PGADMIN_PASSWORD:-$(openssl rand -base64 32 | tr -d "=+/" | cut -c1-25)}
    TYPESENSE_API_KEY=${TYPESENSE_API_KEY:-$(openssl rand -hex 32)}

    # Save passwords to a file for reference
    cat > .docker-secrets.txt << EOF
# Docker Services Credentials
# Generated on: $(date)

PostgreSQL:
  Host: localhost
  Port: 5432
  Database: $POSTGRES_DB
  Username: $POSTGRES_USER
  Password: $POSTGRES_PASSWORD

pgAdmin:
  URL: http://localhost:5050
  Email: $PGADMIN_EMAIL
  Password: $PGADMIN_PASSWORD

Typesense:
  URL: http://localhost:8108
  API Key: $TYPESENSE_API_KEY

Redis:
  Host: localhost
  Port: 6379
  Password: (none - no authentication required)

Dockge:
  URL: http://localhost:5001
  Purpose: Docker Compose stack manager
  Stacks Directory: /opt/stacks

EOF
    chmod 600 .docker-secrets.txt
    print_success "Credentials saved to .docker-secrets.txt (read-only)"

    # Create docker-compose.yml with variable substitution
    # Using a here-document that allows variable expansion
    cat > docker-compose.yml << EOFCONFIG
version: '3.8'

services:
  postgres:
    image: postgres:18-alpine
    container_name: openoverheid-postgres
    environment:
      POSTGRES_USER: ${POSTGRES_USER}
      POSTGRES_PASSWORD: ${POSTGRES_PASSWORD}
      POSTGRES_DB: ${POSTGRES_DB}
      PGDATA: /var/lib/postgresql/data/pgdata
    ports:
      - "5432:5432"
    volumes:
      - postgres-data:/var/lib/postgresql/data
    restart: unless-stopped
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U ${POSTGRES_USER}"]
      interval: 10s
      timeout: 5s
      retries: 5
    networks:
      - openoverheid-network

  pgadmin:
    image: dpage/pgadmin4:latest
    container_name: openoverheid-pgadmin
    environment:
      PGADMIN_DEFAULT_EMAIL: ${PGADMIN_EMAIL}
      PGADMIN_DEFAULT_PASSWORD: ${PGADMIN_PASSWORD}
      PGADMIN_CONFIG_SERVER_MODE: 'False'
      PGADMIN_CONFIG_MASTER_PASSWORD_REQUIRED: 'False'
    ports:
      - "5050:80"
    volumes:
      - pgadmin-data:/var/lib/pgadmin
    depends_on:
      postgres:
        condition: service_healthy
    restart: unless-stopped
    networks:
      - openoverheid-network

  typesense:
    image: typesense/typesense:latest
    container_name: openoverheid-typesense
    ports:
      - "8108:8108"
    volumes:
      - typesense-data:/data
    command: "--data-dir /data --api-key=${TYPESENSE_API_KEY} --enable-cors"
    restart: unless-stopped
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:8108/health"]
      interval: 30s
      timeout: 10s
      retries: 3
    networks:
      - openoverheid-network

  redis:
    image: redis:7-alpine
    container_name: openoverheid-redis
    ports:
      - "6379:6379"
    volumes:
      - redis-data:/data
    command: redis-server --appendonly yes
    restart: unless-stopped
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 10s
      timeout: 3s
      retries: 3
    networks:
      - openoverheid-network

  dockge:
    image: louislam/dockge:latest
    container_name: openoverheid-dockge
    restart: unless-stopped
    ports:
      - "5001:5001"
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock
      - dockge-data:/app/data
      - dockge-stacks:/opt/stacks
    environment:
      - DOCKGE_STACKS_DIR=/opt/stacks
    networks:
      - openoverheid-network

volumes:
  postgres-data:
    driver: local
  pgadmin-data:
    driver: local
  typesense-data:
    driver: local
  redis-data:
    driver: local
  dockge-data:
    driver: local
  dockge-stacks:
    driver: local

networks:
  openoverheid-network:
    driver: bridge
EOFCONFIG

    # Export variables for later use
    export POSTGRES_PASSWORD
    export POSTGRES_USER
    export POSTGRES_DB
    export PGADMIN_EMAIL
    export PGADMIN_PASSWORD
    export TYPESENSE_API_KEY

    print_success "docker-compose.yml created with PostgreSQL, pgAdmin, Typesense, and Redis"
}

# Start all Docker services
start_docker_services() {
    print_info "Starting Docker services (PostgreSQL, pgAdmin, Typesense, Redis)..."

    docker compose up -d

    # Wait for PostgreSQL to be ready
    print_info "Waiting for PostgreSQL to be ready..."
    for i in {1..60}; do
        if docker compose exec -T postgres pg_isready -U $POSTGRES_USER >/dev/null 2>&1; then
            print_success "PostgreSQL is ready!"
            break
        fi
        if [ $i -eq 60 ]; then
            print_error "PostgreSQL failed to start. Check logs with: docker compose logs postgres"
            exit 1
        fi
        sleep 2
    done

    # Wait for Typesense to be ready
    print_info "Waiting for Typesense to be ready..."
    for i in {1..30}; do
        if curl -f http://localhost:8108/health >/dev/null 2>&1; then
            print_success "Typesense is ready!"
            break
        fi
        if [ $i -eq 30 ]; then
            print_warning "Typesense may not be ready yet. Check logs with: docker compose logs typesense"
        fi
        sleep 2
    done

    print_success "All Docker services are running!"
}

# Setup Laravel application
setup_laravel() {
    print_info "Setting up Laravel application..."

    # Check if .env exists
    if [ ! -f .env ]; then
        if [ -f .env.example ]; then
            print_info "Copying .env.example to .env"
            cp .env.example .env
        else
            print_error ".env.example not found. Please create .env manually."
            exit 1
        fi
    fi

    # Install PHP dependencies
    if command_exists composer; then
        print_info "Installing PHP dependencies..."
        composer install --no-interaction --prefer-dist --optimize-autoloader
    else
        print_warning "Composer not found. Please install it from https://getcomposer.org/"
        print_info "Skipping PHP dependency installation"
    fi

    # Generate application key if not set
    if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
        if command_exists php; then
            print_info "Generating application key..."
            php artisan key:generate --force
        else
            print_warning "PHP not found. Please set APP_KEY manually in .env"
        fi
    fi

    # Install Node dependencies (npm should be available after Node.js installation)
    if command_exists npm; then
        print_info "Installing Node dependencies..."
        npm install --no-audit --no-fund
    else
        print_error "npm not found. Node.js installation may have failed."
        print_info "Please install Node.js manually from https://nodejs.org/en/download/current"
        exit 1
    fi

    # Build assets
    if command_exists npm && [ -d node_modules ]; then
        print_info "Building frontend assets with Vite..."
        npm run build
    else
        print_error "Failed to build assets. node_modules directory not found."
        exit 1
    fi
}

# Update .env with database and Typesense configuration
update_env() {
    print_info "Updating .env with database and Typesense configuration..."

    # Backup existing .env if it exists
    if [ -f .env ]; then
        cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
        print_info "Backed up existing .env file"
    fi

    # Update or add database configuration
    if grep -q "DB_CONNECTION=" .env 2>/dev/null; then
        # Update existing database config
        sed -i.bak "s/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/" .env
        sed -i.bak "s/^DB_HOST=.*/DB_HOST=127.0.0.1/" .env
        sed -i.bak "s/^DB_PORT=.*/DB_PORT=5432/" .env
        sed -i.bak "s/^DB_DATABASE=.*/DB_DATABASE=$POSTGRES_DB/" .env
        sed -i.bak "s/^DB_USERNAME=.*/DB_USERNAME=$POSTGRES_USER/" .env
        sed -i.bak "s/^DB_PASSWORD=.*/DB_PASSWORD=$POSTGRES_PASSWORD/" .env
        rm -f .env.bak
        print_info "Updated existing database configuration"
    else
        # Add database configuration
        cat >> .env << EOF

# Database Configuration (Docker PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=$POSTGRES_DB
DB_USERNAME=$POSTGRES_USER
DB_PASSWORD=$POSTGRES_PASSWORD
EOF
        print_info "Added database configuration"
    fi

    # Update or add Typesense configuration
    if grep -q "TYPESENSE_" .env 2>/dev/null; then
        sed -i.bak "s/^TYPESENSE_API_KEY=.*/TYPESENSE_API_KEY=$TYPESENSE_API_KEY/" .env
        sed -i.bak "s/^TYPESENSE_HOST=.*/TYPESENSE_HOST=localhost/" .env
        sed -i.bak "s/^TYPESENSE_PORT=.*/TYPESENSE_PORT=8108/" .env
        sed -i.bak "s/^TYPESENSE_PROTOCOL=.*/TYPESENSE_PROTOCOL=http/" .env
        sed -i.bak "s/^TYPESENSE_SYNC_ENABLED=.*/TYPESENSE_SYNC_ENABLED=true/" .env
        rm -f .env.bak
        print_info "Updated existing Typesense configuration"
    else
        cat >> .env << EOF

# Typesense Configuration
TYPESENSE_SYNC_ENABLED=true
TYPESENSE_API_KEY=$TYPESENSE_API_KEY
TYPESENSE_HOST=localhost
TYPESENSE_PORT=8108
TYPESENSE_PROTOCOL=http
EOF
        print_info "Added Typesense configuration"
    fi

    # Update or add Redis configuration
    if grep -q "REDIS_HOST=" .env 2>/dev/null; then
        sed -i.bak "s/^REDIS_HOST=.*/REDIS_HOST=127.0.0.1/" .env
        sed -i.bak "s/^REDIS_PORT=.*/REDIS_PORT=6379/" .env
        sed -i.bak "s/^CACHE_STORE=.*/CACHE_STORE=redis/" .env
        rm -f .env.bak
        print_info "Updated existing Redis configuration"
    else
        cat >> .env << EOF

# Redis Cache Configuration
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
CACHE_STORE=redis
REDIS_CLIENT=phpredis
EOF
        print_info "Added Redis configuration"
    fi

    print_success ".env file configured with all services"
}

# Setup database
setup_database() {
    print_info "Setting up database..."

    if ! command_exists php; then
        print_warning "PHP not found. Skipping database setup"
        print_info "Run 'php artisan migrate' manually after installing PHP"
        return 0
    fi

    # Wait a bit more for PostgreSQL to be fully ready
    print_info "Ensuring PostgreSQL is ready for connections..."
    sleep 5

    # Test database connection
    print_info "Testing database connection..."
    if php artisan db:show >/dev/null 2>&1; then
        print_success "Database connection successful!"
    else
        print_warning "Database connection test failed, but continuing..."
    fi

    # Run migrations
    print_info "Running database migrations..."
    php artisan migrate --force || {
        print_warning "Database migration failed. This might be normal if migrations were already run."
        print_info "You can run migrations manually with: php artisan migrate"
    }

    print_success "Database setup completed"
}

# Print summary
print_summary() {
    echo ""
    echo "=========================================="
    print_success "Installation completed successfully!"
    echo "=========================================="
    echo ""
    echo "📦 Services Running:"
    echo "  ✅ PostgreSQL:     localhost:5432"
    echo "  ✅ pgAdmin:         http://localhost:5050"
    echo "  ✅ Typesense:       http://localhost:8108"
    echo "  ✅ Redis:           localhost:6379"
    echo "  ✅ Dockge:          http://localhost:5001"
    echo ""
    echo "🔐 Credentials saved to: .docker-secrets.txt"
    echo ""
    echo "📋 Quick Access:"
    echo "  - pgAdmin:         http://localhost:5050"
    echo "    Email:           $PGADMIN_EMAIL"
    echo "    Password:        (see .docker-secrets.txt)"
    echo ""
    echo "  - Database:"
    echo "    Host:            localhost"
    echo "    Port:            5432"
    echo "    Database:        $POSTGRES_DB"
    echo "    Username:        $POSTGRES_USER"
    echo "    Password:        (see .docker-secrets.txt)"
    echo ""
    echo "🚀 Next Steps:"
    echo "  1. Start Laravel server: php artisan serve"
    echo "  2. Access application: http://localhost:8000"
    echo "  3. Access Dockge: http://localhost:5001 (Docker Compose manager)"
    echo "  4. (Optional) Sync documents: php artisan open-overheid:sync"
    echo ""
    echo "📦 Installed Software:"
    echo "  ✅ Node.js:         $(node --version 2>/dev/null || echo 'Not found')"
    echo "  ✅ npm:             $(npm --version 2>/dev/null || echo 'Not found')"
    echo "  ✅ Docker:          $(docker --version 2>/dev/null || echo 'Not found')"
    echo ""
    echo "📊 pgAdmin Setup (First Time):"
    echo "  1. Open http://localhost:5050"
    echo "  2. Login with credentials from .docker-secrets.txt"
    echo "  3. Right-click 'Servers' → 'Register' → 'Server'"
    echo "  4. General tab: Name = 'Open Overheid DB'"
    echo "  5. Connection tab:"
    echo "     - Host: postgres (or localhost if connecting from host)"
    echo "     - Port: 5432"
    echo "     - Database: $POSTGRES_DB"
    echo "     - Username: $POSTGRES_USER"
    echo "     - Password: (from .docker-secrets.txt)"
    echo "  6. Click 'Save'"
    echo ""
    echo "🛠️  Useful Commands:"
    echo "  - View all logs:        docker compose logs -f"
    echo "  - View PostgreSQL logs: docker compose logs -f postgres"
    echo "  - View Typesense logs:  docker compose logs -f typesense"
    echo "  - View Redis logs:      docker compose logs -f redis"
    echo "  - Stop all services:    docker compose stop"
    echo "  - Start all services:   docker compose start"
    echo "  - Restart all:          docker compose restart"
    echo "  - Stop and remove:      docker compose down"
    echo ""
    echo "📝 Note: If you need to log out and back in for Docker group changes,"
    echo "   you can run 'newgrp docker' to apply changes in current session."
    echo ""
}

# Install Dockge
install_dockge() {
    print_info "Setting up Dockge (Docker Compose manager)..."
    
    # Dockge is already included in docker-compose.yml
    # Just verify it's running
    if docker compose ps dockge 2>/dev/null | grep -q "Up"; then
        print_success "Dockge is running!"
    else
        print_info "Dockge will be started with Docker services"
    fi
}

# Main installation flow
main() {
    echo "=========================================="
    echo "Open Overheid Platform Installation"
    echo "=========================================="
    echo ""

    check_root
    detect_os
    install_docker
    install_docker_compose
    install_nodejs
    create_docker_compose
    start_docker_services
    install_dockge
    setup_laravel
    update_env
    setup_database
    print_summary
}

# Run main function
main

