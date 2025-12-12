# Guides Directory

This directory contains all documentation, guides, and reference materials for the Open Overheid platform project.

## 📁 Directory Structure

```
guides/
├── design/              # Design system and UI guidelines
├── installation/        # Installation, setup, and deployment guides
├── project/             # Project documentation and analysis
├── reference/           # Reference documents and specifications
├── test/                # Test reports and testing documentation
├── PROJECT_STRUCTURE.md # Project structure documentation
├── CLAUDE.md            # Claude AI assistant guidelines
└── README.md            # This file
```

## 📚 Contents

### 🎨 Design (`/design`)

Design system guides and UI/UX documentation:

- **`materialdesign_llm.md`** - Material Design 3 implementation guide
- **`tailwind_llm.md`** - Tailwind CSS configuration and usage
- **`wcag_llm.md`** - WCAG 2.2 AA accessibility guidelines
- **`fa_llm.txt`** - Font Awesome icon usage guide
- **`tooi_llm.md`** - TOOI (The Open Overheid Index) specifications

### 🔧 Installation (`/installation`)

Installation guides, setup instructions, deployment, and dependency information:

- **`INSTALLATION.md`** - One-click installer guide
- **`DEPENDENCIES.md`** - Complete dependency list
- **`VPS_SETUP.md`** - VPS setup guide (SSH & GitHub)
- **`VPS_SETUP_QUICKSTART.md`** - Quick VPS setup guide
- **`INSTALLER_UPDATE_SUMMARY.md`** - Latest installer updates
- **`DEPLOYMENT.md`** - Deployment guide
- **`PRODUCTION_CHECKLIST.md`** - Production deployment checklist

### 📋 Project (`/project`)

Project documentation, feature analysis, architecture, and core project documents:

**Core Documents:**
- **`PID.md`** - Project Initiatief Document
- **`SA.md`** - Solution Architecture
- **`WAY_OF_WORK.md`** - Way of Work methodology

**Project Organization:**
- **`epics/`** - Epic documentation
- **`features/`** - Feature analysis and requirements
- **`implementation/`** - Implementation plans and summaries
- **`reference/`** - Reference documentation (Woo, guidelines)
- **`setup/`** - Setup and configuration guides

See [`project/README.md`](project/README.md) for detailed structure.

### 📖 Reference (`/reference`)

Reference documents, specifications, and historical documentation:

- **`oo_llm.txt`** - Open Overheid LLM reference (v1)
- **`oo_llm_v2.txt`** - Open Overheid LLM reference (v2)
- **`oo_llm_final.md`** - Open Overheid LLM reference (final)
- **`REVIEW_oo_llm_v2.txt`** - Review of v2 specifications
- **`CTER_DOCUMENTEN_OVERZICHT.md`** - CTER documents overview

### 🧪 Test (`/test`)

Test reports, test documentation, and testing utilities:

- **`README.md`** - Test documentation and report generation guide
- **`generate-test-report.php`** - Automated test report generator
- **`FEATURE_STATUS_REPORT.md`** - Detailed feature status report
- **`TESTING_SUMMARY.md`** - Testing summary report
- **`TEST_RESULTS.md`** - Test results documentation
- **`test-report-*.md`** - Timestamped test execution reports
- **`latest-test-output.txt`** - Latest test execution output

See [`test/README.md`](test/README.md) for detailed testing documentation.

## 🚀 Quick Links

### Design System
- [Material Design 3 Guide](design/materialdesign_llm.md)
- [Tailwind CSS Guide](design/tailwind_llm.md)
- [WCAG Accessibility Guide](design/wcag_llm.md)
- [Font Awesome Guide](design/fa_llm.txt)

### Project Status
- [Missing Features Analysis](project/missing-features-analysis.md)
- [Feature Status Report](test/FEATURE_STATUS_REPORT.md)
- [Latest Test Report](test/) - Check for most recent timestamped report

### Testing
- [Test Documentation](test/README.md)
- [Generate Test Report](test/generate-test-report.php)

## 📝 Usage

### Generate Test Report

```bash
php guides/test/generate-test-report.php
```

This will create a timestamped test report in `guides/test/`.

### View Latest Test Report

```bash
ls -t guides/test/test-report-*.md | head -1 | xargs cat
```

## 🔍 Finding Documentation

- **Design questions?** → Check `/design`
- **Feature status?** → Check `/project` and `/test`
- **Reference specs?** → Check `/reference`
- **Test results?** → Check `/test`

## 📊 Project Status

Current test status (as of latest report):
- ✅ **44 tests passing** (64.7%)
- ❌ **10 tests failing** (14.7%)
- ⏭️ **14 tests skipped** (20.6%) - Missing features

See the latest test report in `/test` for detailed status.

## 🎯 Next Steps

1. **Review Missing Features** - See `project/missing-features-analysis.md`
2. **Fix Failing Tests** - Check latest test report in `test/`
3. **Implement Missing Features** - Prioritize based on analysis documents
4. **Generate New Test Report** - Run test report generator after changes

---

**Last Updated:** 2025-12-10  
**Project:** Open Overheid Platform  
**Status:** Active Development

