# Project Documentation

This directory contains organized project documentation, epics, feature analysis, implementation details, and reference materials.

> **Note:** Core project documents (PID, SA, PSA, Way of Work) are located in the **root directory** as they are always referenced.

## 📁 Directory Structure

```
guides/project/
├── epics/              # Epic documentation
├── features/           # Feature analysis and requirements
├── implementation/     # Implementation plans and summaries
├── reference/          # Reference documentation (Woo, guidelines)
├── setup/              # Setup and configuration guides
└── README.md           # This file
```

## 📚 Documentation Categories

### 🎯 Epics (`epics/`)

Large-scale feature initiatives and project epics:

- **`AI_ENHANCEMENT_EPIC.md`** - AI-powered features for dossiers (TTS, B1 language, premium research)
- **`AI_CHAT_EPIC.md`** - AI-bevraging met context & content focus voor overheidsdocumenten
- **`SEARCH_FILTER_IMPROVEMENT_EPIC.md`** - Search and filter functionality improvements

### 🔍 Features (`features/`)

Feature analysis, requirements, and missing features documentation:

- **`MISSING_FEATURES.md`** - Overview of missing features
- **`missing-features-analysis.md`** - Comprehensive analysis comparing implementation with open.minvws.nl
  - Currently implemented features
  - Missing features with priority levels
  - Implementation recommendations
  - Feature comparison table
- **`missing-pages.md`** - Documentation of missing pages and routes
- **`pages-ui-blocks.md`** - UI blocks and page structure documentation

### 🛠️ Implementation (`implementation/`)

Implementation plans, summaries, and architecture details:

- **`AI_ENHANCEMENT_PLAN.md`** - Detailed plan for AI enhancement features
- **`AI_ENHANCEMENT_IMPLEMENTATION_SUMMARY.md`** - Summary of AI enhancement implementation
- **`AI_ENHANCEMENT_TEST_RESULTS.md`** - Test results for AI enhancement features
- **`solutions-architecture-features.md`** - Architecture features and recommendations

### 📖 Reference (`reference/`)

Reference documentation and guidelines:

- **`woo.md`** - Wet Open Overheid (Woo) reference documentation
- **`woo-guidelines-law.md`** - Woo guidelines and legal requirements

### ⚙️ Setup (`setup/`)

Setup and configuration guides:

- **`GITHUB_SETUP.md`** - GitHub repository setup and configuration

## 🎯 Current Status

### Implemented Features ✅
- Basic search functionality
- Date filters (predefined periods)
- Document type, theme, and organization filters
- Sorting and pagination
- Dynamic filter counts
- External links to open.overheid.nl

### Missing Features ❌
See `features/missing-features-analysis.md` for complete list, including:
- Custom date range picker (High Priority)
- File type filter
- Enhanced sorting labels
- Collapsible filter sections
- And more...

## 📊 Feature Priority

Features are categorized by priority:
- **High Priority** - Core functionality gaps
- **Medium Priority** - Important UX improvements
- **Low Priority** - Nice-to-have enhancements

## 🔗 Related Documentation

- **Core Project Documents** (in this directory):
  - `PID.md` - Project Initiatief Document
  - `SA.md` - Solution Architecture
  - `WAY_OF_WORK.md` - Way of Work methodology
  - `PSA.md` - Project Start Aanvraag (if exists)

- **Other Guides**:
  - [Design System](../design/)
  - [Test Reports](../test/)
  - [Reference Documents](../reference/)
  - [Installation Guides](../installation/)

## 🚀 Quick Access

- **Epics**: `epics/`
- **Feature Analysis**: `features/missing-features-analysis.md`
- **Implementation Plans**: `implementation/`
- **Woo Reference**: `reference/woo.md`
- **Setup Guides**: `setup/`

---

**Last Updated:** 2025-12-12  
**Status**: ✅ Organized
