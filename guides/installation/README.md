# Installation Guides

This directory contains all installation and setup documentation for the Open Overheid Platform.

## 📁 Contents

### Core Installation
- **`INSTALLATION.md`** - One-click installer guide
- **`DEPENDENCIES.md`** - Complete dependency list
- **`INSTALLER_UPDATE_SUMMARY.md`** - Latest installer updates

### VPS Setup
- **`VPS_SETUP.md`** - Complete VPS setup guide (SSH & GitHub)
- **`VPS_SETUP_QUICKSTART.md`** - Quick start guide for VPS setup

## 🚀 Quick Links

### For New Users
1. Start with [INSTALLATION.md](INSTALLATION.md) for one-click setup
2. Check [DEPENDENCIES.md](DEPENDENCIES.md) for system requirements

### For VPS Deployment
1. Use [VPS_SETUP_QUICKSTART.md](VPS_SETUP_QUICKSTART.md) for fast setup
2. See [VPS_SETUP.md](VPS_SETUP.md) for detailed instructions

## 📋 Installation Methods

### Method 1: One-Click Installer (Recommended)
```bash
chmod +x install.sh
./install.sh
```

### Method 2: Manual Setup
See [INSTALLATION.md](INSTALLATION.md) for step-by-step instructions.

### Method 3: VPS Setup
Use the VPS setup script for clean server deployment:
```bash
sudo ./vps-setup.sh --github-username YOUR_USER --github-email YOUR_EMAIL
```

## 🔧 What Gets Installed

- Docker & Docker Compose
- Node.js v25.2.1 & npm
- PostgreSQL 18
- pgAdmin 4
- Typesense
- Dockge (Docker Compose manager)
- Laravel application

## 📚 Related Documentation

- **Project Docs**: [../project/README.md](../project/README.md)
- **Test Docs**: [../test/README.md](../test/README.md)
- **Design Docs**: [../design/README.md](../design/README.md)

---

**Last Updated**: 2025-01-XX

