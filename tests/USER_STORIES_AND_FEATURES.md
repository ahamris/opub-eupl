# User Stories, Features, and Epic Tests

## Table of Contents
1. [Epics](#epics)
2. [Features](#features)
3. [User Stories](#user-stories)
4. [Missing Features](#missing-features)
5. [Suggested Features Based on Data Model](#suggested-features-based-on-data-model)

---

## Epics

### Epic 1: Document Discovery & Search
**Goal**: Enable users to discover and search government documents efficiently

**Features**:
- Basic search functionality
- Advanced filtering
- Search results display
- Live search suggestions
- Autocomplete

**Acceptance Criteria**:
- Users can search by keywords
- Users can filter by multiple criteria
- Results are relevant and fast
- Search suggestions appear in real-time

---

### Epic 2: Document Viewing & Details
**Goal**: Provide comprehensive document information and metadata

**Features**:
- Document detail page
- Metadata display
- Export functionality
- Related documents
- Dossier relationships

**Acceptance Criteria**:
- All document metadata is displayed
- Users can export documents
- Related documents are shown
- Dossier relationships are visible

---

### Epic 3: Navigation & Organization
**Goal**: Help users navigate and organize documents by themes, dossiers, and categories

**Features**:
- Themes page
- Dossiers page
- Category filtering
- Organization filtering
- Quick filters

**Acceptance Criteria**:
- Users can browse by theme
- Users can browse by dossier
- Filters work within domain context
- Quick filters provide instant results

---

### Epic 4: Statistics & Analytics
**Goal**: Provide insights into document collection

**Features**:
- Homepage statistics
- Category statistics
- Theme statistics
- Organization statistics
- Dossier count

**Acceptance Criteria**:
- Statistics are accurate
- Statistics are clickable
- Statistics update dynamically
- Statistics follow Woo guidelines

---

## Features

### Feature 1: Homepage Search
**Priority**: High  
**Status**: ✅ Implemented

**User Stories**:
- As a user, I want to see the homepage with search functionality
- As a user, I want to see document count statistics
- As a user, I want to access quick links

**Tests**: `SearchPageTest.php`

---

### Feature 2: Advanced Search
**Priority**: High  
**Status**: ✅ Implemented

**User Stories**:
- As a user, I want to search by keywords
- As a user, I want to filter by date range
- As a user, I want to filter by document type
- As a user, I want to filter by theme
- As a user, I want to filter by organization
- As a user, I want to filter by category
- As a user, I want to search only in titles
- As a user, I want to sort results
- As a user, I want to change results per page

**Tests**: `SearchResultsTest.php`

---

### Feature 3: Document Detail View
**Priority**: High  
**Status**: ✅ Implemented

**User Stories**:
- As a user, I want to view document details
- As a user, I want to see all metadata
- As a user, I want to export as JSON
- As a user, I want to export as XML
- As a user, I want to see related documents
- As a user, I want to see dossier members

**Tests**: `DocumentDetailTest.php`

---

### Feature 4: Themes Page
**Priority**: Medium  
**Status**: ✅ Implemented

**User Stories**:
- As a user, I want to browse documents by theme
- As a user, I want to filter themes within theme domain
- As a user, I want to see theme-specific statistics

**Tests**: `ThemesPageTest.php` (to be created)

---

### Feature 5: Dossiers Page
**Priority**: Medium  
**Status**: ✅ Implemented

**User Stories**:
- As a user, I want to browse dossiers
- As a user, I want to see dossier member count
- As a user, I want to view dossier details
- As a user, I want to filter dossiers within dossier domain

**Tests**: `DossiersPageTest.php` (to be created)

---

### Feature 6: Live Search API
**Priority**: Medium  
**Status**: ✅ Implemented

**User Stories**:
- As a developer, I want to use live search API
- As a user, I want to see search suggestions

**Tests**: `APITest.php`

---

### Feature 7: Statistics Display
**Priority**: Medium  
**Status**: ✅ Implemented

**User Stories**:
- As a user, I want to see document statistics
- As a user, I want to see category statistics
- As a user, I want to see theme statistics
- As a user, I want to click on statistics to filter

**Tests**: `UIComponentsTest.php`

---

## User Stories

### Search & Discovery

**US-001**: As a user, I want to search for government documents by keywords  
**Priority**: High  
**Status**: ✅ Implemented  
**Tests**: `SearchResultsTest::test('user can perform a basic text search')`

**US-002**: As a user, I want to filter search results by publication date  
**Priority**: High  
**Status**: ✅ Implemented  
**Tests**: `SearchResultsTest::test('user can filter by predefined date periods')`

**US-003**: As a user, I want to filter search results by document type  
**Priority**: High  
**Status**: ✅ Implemented  
**Tests**: `SearchResultsTest::test('user can filter by document type')`

**US-004**: As a user, I want to filter search results by theme  
**Priority**: High  
**Status**: ✅ Implemented  
**Tests**: `SearchResultsTest::test('user can filter by theme')`

**US-005**: As a user, I want to filter search results by organization  
**Priority**: High  
**Status**: ✅ Implemented  
**Tests**: `SearchResultsTest::test('user can filter by organization')`

**US-006**: As a user, I want to filter search results by information category  
**Priority**: High  
**Status**: ✅ Implemented  
**Tests**: Needs test

**US-007**: As a user, I want to search only in document titles  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: `SearchResultsTest::test('user can search only in titles')`

**US-008**: As a user, I want to sort results by relevance, date, or modified date  
**Priority**: High  
**Status**: ✅ Implemented  
**Tests**: `SearchResultsTest::test('user can sort by relevance')`

**US-009**: As a user, I want to change the number of results per page  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: `SearchResultsTest::test('user can change results per page')`

**US-010**: As a user, I want to see filter counts for each filter option  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: `SearchResultsTest::test('search results page shows dynamic filter counts')`

---

### Document Viewing

**US-011**: As a user, I want to view detailed information about a document  
**Priority**: High  
**Status**: ✅ Implemented  
**Tests**: `DocumentDetailTest::test('user can view document detail page')`

**US-012**: As a user, I want to see all document metadata  
**Priority**: High  
**Status**: ✅ Implemented  
**Tests**: `DocumentDetailTest::test('document detail page shows all metadata')`

**US-013**: As a user, I want to export a document as JSON  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: `DocumentDetailTest::test('user can export document as JSON')`

**US-014**: As a user, I want to export a document as XML  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: `DocumentDetailTest::test('user can export document as XML')`

**US-015**: As a user, I want to see related documents in a dossier  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: Needs test

**US-016**: As a user, I want to access the original document on open.overheid.nl  
**Priority**: High  
**Status**: ✅ Implemented  
**Tests**: `DocumentDetailTest::test('document detail page shows link to open.overheid.nl')`

---

### Navigation & Browsing

**US-017**: As a user, I want to browse documents by theme  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: Needs test

**US-018**: As a user, I want to browse dossiers  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: Needs test

**US-019**: As a user, I want to view dossier details with all members  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: Needs test

**US-020**: As a user, I want to filter within theme domain on themes page  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: Needs test

**US-021**: As a user, I want to filter within dossier domain on dossiers page  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: Needs test

---

### Statistics & Analytics

**US-022**: As a user, I want to see total document count on homepage  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: `SearchPageTest::test('search page displays document count')`

**US-023**: As a user, I want to see top categories with article references  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: Needs test

**US-024**: As a user, I want to see top themes  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: Needs test

**US-025**: As a user, I want to see dossier count  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: Needs test

**US-026**: As a user, I want to click on statistics to filter documents  
**Priority**: Medium  
**Status**: ✅ Implemented  
**Tests**: Needs test

---

### API & Integration

**US-027**: As a developer, I want to use live search API endpoint  
**Priority**: Low  
**Status**: ✅ Implemented  
**Tests**: `APITest.php`

**US-028**: As a developer, I want to use autocomplete API endpoint  
**Priority**: Low  
**Status**: ✅ Implemented  
**Tests**: `APITest.php`

---

## Missing Features

### High Priority Missing Features

1. **Category Page** ❌
   - Browse documents by Woo information category
   - Category-specific statistics
   - Category detail view

2. **Organization Page** ❌
   - Browse documents by organization
   - Organization-specific statistics
   - Organization detail view

3. **Search History** ❌
   - View recent searches
   - Re-run previous searches
   - Save favorite searches

4. **Document Comparison** ❌
   - Compare multiple documents side-by-side
   - Highlight differences
   - Export comparison

5. **Bulk Export** ❌
   - Export multiple documents at once
   - Export search results
   - Export filtered results

6. **RSS Feeds** ❌
   - RSS feed for new documents
   - RSS feed for search results
   - RSS feed for categories/themes

### Medium Priority Missing Features

7. **Saved Searches** ❌
   - Save search queries
   - Get notified of new results
   - Manage saved searches

8. **Favorites/Bookmarks** ❌
   - Bookmark documents
   - Organize bookmarks
   - Share bookmarks

9. **Document Preview** ❌
   - Preview document content
   - Extract text from PDFs
   - Show document structure

10. **Timeline View** ❌
    - View documents on timeline
    - Filter by date range visually
    - See document relationships over time

11. **Related Documents** ❌
    - Show semantically related documents
    - Show documents with similar themes
    - Show documents from same organization

12. **Advanced Statistics Dashboard** ❌
    - Visual charts and graphs
    - Trends over time
    - Category distribution
    - Organization activity

### Low Priority Missing Features

13. **Document Analytics** ❌
    - View count per document
    - Popular documents
    - Search trends

14. **API Documentation** ❌
    - Interactive API docs
    - Code examples
    - Authentication guide

15. **Export Formats** ❌
    - CSV export
    - Excel export
    - PDF export of results

16. **Document Sharing** ❌
    - Share document links
    - Generate shareable links
    - Social media sharing

17. **Accessibility Features** ❌
    - Screen reader optimization
    - Keyboard navigation
    - High contrast mode

18. **Multi-language Support** ❌
    - English interface
    - Document translation
    - Multi-language search

---

## Suggested Features Based on Data Model

Based on the `open_overheid_documents` table structure and metadata JSONB field, here are advisable features:

### 1. Document Relationships Visualization
**Data Source**: `metadata->documentrelaties`  
**Feature**: Visual graph showing document relationships
- Show all related documents
- Display relationship types (identiteitsgroep, etc.)
- Interactive relationship explorer

### 2. Advanced Dossier Management
**Data Source**: `metadata->documentrelaties` with `role: identiteitsgroep`  
**Feature**: Enhanced dossier features
- Dossier timeline view
- Dossier member hierarchy
- Dossier statistics
- Dossier export

### 3. Metadata Explorer
**Data Source**: `metadata` JSONB field  
**Feature**: Explore all metadata fields
- Search within metadata
- Filter by metadata fields
- Export metadata schema
- Metadata validation

### 4. Category Taxonomy Browser
**Data Source**: `category` + WooCategoryService  
**Feature**: Browse Woo category taxonomy
- Category tree view
- Category hierarchy
- Category statistics
- Category articles reference

### 5. Organization Network
**Data Source**: `organisation` field  
**Feature**: Organization relationship network
- Organization collaboration graph
- Organization statistics
- Organization timeline
- Cross-organization document flow

### 6. Theme Analysis
**Data Source**: `theme` field  
**Feature**: Theme-based analysis
- Theme trends over time
- Theme correlation
- Theme popularity
- Theme distribution charts

### 7. Document Type Analytics
**Data Source**: `document_type` field  
**Feature**: Document type insights
- Type distribution
- Type trends
- Type by organization
- Type by category

### 8. Publication Date Analytics
**Data Source**: `publication_date` field  
**Feature**: Time-based analytics
- Publication trends
- Seasonal patterns
- Year-over-year comparison
- Publication calendar view

### 9. Content Analysis
**Data Source**: `content`, `description`, `title` fields  
**Feature**: Content insights
- Keyword extraction
- Topic modeling
- Content similarity
- Content search within documents

### 10. Sync Status Monitoring
**Data Source**: `synced_at`, `typesense_synced_at`  
**Feature**: Sync status dashboard
- Last sync time
- Sync errors
- Sync statistics
- Manual sync trigger

### 11. Search Vector Optimization
**Data Source**: `search_vector` tsvector  
**Feature**: Search optimization tools
- Search vector analysis
- Search performance metrics
- Search term suggestions
- Search relevance tuning

### 12. External ID Management
**Data Source**: `external_id` field  
**Feature**: External ID utilities
- ID validation
- ID lookup
- ID history
- ID relationships

---

## Test Coverage Summary

### Implemented Tests ✅
- `SearchPageTest.php` - Homepage and search page
- `SearchResultsTest.php` - Search functionality and filters
- `DocumentDetailTest.php` - Document detail view
- `APITest.php` - API endpoints
- `UIComponentsTest.php` - UI components
- `MissingFeaturesTest.php` - Missing features validation

### Missing Tests ❌
- `ThemesPageTest.php` - Themes page functionality
- `DossiersPageTest.php` - Dossiers page functionality
- `DossierDetailTest.php` - Dossier detail view
- `StatisticsTest.php` - Statistics display and interaction
- `CategoryFilterTest.php` - Category filtering
- `QuickFilterTest.php` - Quick filter functionality
- `ExportTest.php` - Export functionality
- `NavigationTest.php` - Navigation and routing

---

## Next Steps

All future development work will follow the **Workflow and Code of Ethics** document located at `.cursor/WORKFLOW_AND_ETHICS.md`.

The workflow follows these steps:
1. **Plan** → Analyze requirements and create detailed plan
2. **Check** → Get user approval before proceeding
3. **Act** → Write test scripts based on approved plan
4. **Build** → Implement feature following conventions
5. **Test** → Verify implementation works correctly
6. **Report** → Provide comprehensive summary with test results

### Immediate Next Steps

1. **Create Missing Tests**: Implement all missing test files
2. **Run Full Test Suite**: Execute all tests and fix failures
3. **Implement High Priority Missing Features**: Start with category and organization pages
4. **Data Model Features**: Prioritize document relationships and dossier enhancements
5. **Performance Testing**: Add performance and load tests
6. **Accessibility Testing**: Add WCAG compliance tests
7. **Integration Testing**: Test API integrations
8. **Browser Testing**: Use Pest v4 browser testing for UI

**Note**: All future work will follow the Plan → Check → Act → Build → Test → Report workflow.
