# Open Overheid Platform - Project Structure

## 📁 Directory Organization

### Root Directory
```
oo/
├── app/                    # Laravel application code
├── bootstrap/              # Laravel bootstrap files
├── config/                 # Configuration files
├── database/               # Migrations, seeders, factories
├── guides/                 # 📚 All documentation (organized)
├── public/                 # Public assets
├── resources/              # Views, CSS, JS
├── routes/                 # Route definitions
├── storage/                # Logs, cache, uploads
├── tests/                  # Test files
├── vendor/                 # Composer dependencies
├── node_modules/           # npm dependencies
├── install.sh              # One-click installer
├── vps-setup.sh            # VPS setup script
├── composer.json           # PHP dependencies
├── package.json            # Node.js dependencies
└── README.md               # Main project README
```

### Guides Directory Structure
```
guides/
├── installation/           # 🔧 Installation & Setup
│   ├── README.md
│   ├── INSTALLATION.md
│   ├── DEPENDENCIES.md
│   ├── VPS_SETUP.md
│   ├── VPS_SETUP_QUICKSTART.md
│   └── INSTALLER_UPDATE_SUMMARY.md
│
├── project/                # 📋 Project Documentation
│   ├── README.md
│   ├── GITHUB_SETUP.md
│   ├── MISSING_FEATURES.md
│   ├── missing-features-analysis.md
│   ├── missing-pages.md
│   ├── solutionarchitecture.md
│   ├── solutions-architecture-features.md
│   └── pages-ui-blocks.md
│
├── design/                 # 🎨 Design System
│   ├── README.md
│   ├── UI_MODERNIZATION_PLAN.md
│   ├── UI_MODERNIZATION_SUMMARY.md
│   ├── materialdesign_llm.md
│   ├── tailwind_llm.md
│   ├── wcag_llm.md
│   └── fa_llm.txt
│
├── test/                   # 🧪 Testing
│   ├── README.md
│   ├── TESTING_SUMMARY.md
│   ├── TEST_RESULTS.md
│   ├── FEATURE_STATUS_REPORT.md
│   ├── generate-test-report.php
│   └── test-report-*.md
│
├── reference/              # 📖 Reference Docs
│   ├── README.md
│   ├── oo_llm_final.md
│   ├── oo_llm_v2.txt
│   ├── oo_llm.txt
│   └── CTER_DOCUMENTEN_OVERZICHT.md
│
└── README.md               # Guides index
```

## 📚 Documentation Categories

### Installation (`guides/installation/`)
- One-click installer guide
- Dependency lists
- VPS setup scripts
- Installation updates

### Project (`guides/project/`)
- GitHub setup
- Missing features
- Architecture documentation
- Project planning

### Design (`guides/design/`)
- UI modernization plans
- Design system guides
- Accessibility guidelines
- Component documentation

### Test (`guides/test/`)
- Test reports
- Testing summaries
- Test generation tools
- Feature status reports

### Reference (`guides/reference/`)
- API specifications
- Historical documentation
- Reference materials

## 🎯 Quick Access

- **Installation**: `guides/installation/INSTALLATION.md`
- **Dependencies**: `guides/installation/DEPENDENCIES.md`
- **VPS Setup**: `guides/installation/VPS_SETUP.md`
- **Project Docs**: `guides/project/README.md`
- **Test Reports**: `guides/test/`

## 📝 File Organization Rules

1. **All .md files** → `guides/` directory
2. **Installation docs** → `guides/installation/`
3. **Project docs** → `guides/project/`
4. **Test docs** → `guides/test/`
5. **Design docs** → `guides/design/`
6. **Reference docs** → `guides/reference/`
7. **README.md** stays in root (main project README)

---

**Last Updated**: 2025-01-XX  
**Status**: ✅ Organized

