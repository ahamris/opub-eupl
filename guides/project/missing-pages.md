# Missing Pages & Features

This document identifies pages and features that are referenced or suggested but not yet implemented in the application.

---

## Table of Contents

1. [Missing Pages](#1-missing-pages)
2. [Missing Features](#2-missing-features)
3. [Missing API Endpoints](#3-missing-api-endpoints)
4. [Missing Admin/Management Pages](#4-missing-adminmanagement-pages)
5. [Missing Documentation Pages](#5-missing-documentation-pages)

---

## 1. Missing Pages

### 1.1 Navigation Links Referenced but Not Implemented

#### Verwijzingen (References) Page
**Location**: Referenced in navigation (`/verwijzingen`)
**Status**: ❌ Not implemented
**Priority**: Low
**Description**: 
- Navigation link exists but route/view missing
- Should contain links to external resources, related websites, or reference materials
- Suggested content: Links to Woo-index, official documentation, related government sites

**Implementation Needed**:
- Route: `Route::get('/verwijzingen', [ReferencesController::class, 'index'])->name('verwijzingen');`
- View: `resources/views/verwijzingen.blade.php`
- Controller: `app/Http/Controllers/ReferencesController.php`

#### Over (About) Page
**Location**: Referenced in navigation (`/over`)
**Status**: ❌ Not implemented
**Priority**: Low
**Description**:
- Navigation link exists but route/view missing
- Should contain information about the application, its purpose, and how it works
- Suggested content: Project description, data sources, usage guidelines, contact information

**Implementation Needed**:
- Route: `Route::get('/over', [AboutController::class, 'index'])->name('over');`
- View: `resources/views/over.blade.php`
- Controller: `app/Http/Controllers/AboutController.php`

### 1.2 Footer Links Referenced but Not Implemented

#### Over deze website (About this website)
**Location**: Footer link
**Status**: ❌ Not implemented
**Priority**: Medium
**Description**:
- Should provide detailed information about the website, its purpose, technical details
- Could include: Architecture overview, data sources, update frequency, API access

**Implementation Needed**:
- Route: `Route::get('/over-deze-website', [PageController::class, 'aboutWebsite']);`
- View: `resources/views/pages/about-website.blade.php`

#### Privacy & Cookies
**Location**: Footer link
**Status**: ❌ Not implemented
**Priority**: High (Legal requirement)
**Description**:
- Privacy policy page required for compliance
- Cookie policy if cookies are used
- GDPR compliance information

**Implementation Needed**:
- Route: `Route::get('/privacy', [PageController::class, 'privacy']);`
- Route: `Route::get('/cookies', [PageController::class, 'cookies']);`
- Views: `resources/views/pages/privacy.blade.php`, `resources/views/pages/cookies.blade.php`

#### Toegankelijkheid (Accessibility)
**Location**: Footer link
**Status**: ❌ Not implemented
**Priority**: High (Legal requirement)
**Description**:
- Accessibility statement required for government websites
- WCAG compliance information
- Accessibility features and contact information

**Implementation Needed**:
- Route: `Route::get('/toegankelijkheid', [PageController::class, 'accessibility']);`
- View: `resources/views/pages/accessibility.blade.php`

### 1.3 Information Banner Links

#### "Welkom op het vernieuwde open overheid portaal! Wat er nieuw is leest u hier."
**Location**: Info banner on search page
**Status**: ❌ Link target missing
**Priority**: Low
**Description**:
- Link to "What's new" or changelog page

**Implementation Needed**:
- Route: `Route::get('/wat-is-nieuw', [PageController::class, 'whatsNew']);`
- View: `resources/views/pages/whats-new.blade.php`

#### "Lees meer over deze website"
**Location**: Info box on search page
**Status**: ❌ Link target missing
**Priority**: Medium
**Description**:
- Could link to About page or detailed information page

**Implementation Needed**:
- Link to existing `/over` page (when implemented) or create dedicated page

#### "Bekijk de Woo-index"
**Location**: Info box on search page
**Status**: ❌ Link target missing
**Priority**: Medium
**Description**:
- Should link to Woo-index (external or internal page)
- Could be external link: `https://www.woo-index.nl/`

**Implementation Needed**:
- External link or internal page with Woo-index information

---

## 2. Missing Features

### 2.1 Search Page Features

#### Uitgebreid zoeken (Advanced Search)
**Location**: Search form (`zoek.blade.php`)
**Status**: ⚠️ Partially implemented
**Priority**: Medium
**Description**:
- Link exists but advanced search modal/page not fully implemented
- Should provide more filter options in expanded view
- Date range picker, multiple filter selections, etc.

**Implementation Needed**:
- JavaScript modal or dedicated advanced search page
- Enhanced filter UI

#### Filter Counts
**Location**: Search results sidebar filters
**Status**: ⚠️ Placeholder values
**Priority**: Low
**Description**:
- Filter options show "(0)" counts - not dynamically calculated
- Should show actual document counts per filter option

**Implementation Needed**:
- Query database for filter counts
- Update SearchController to provide filter statistics
- JavaScript to update counts dynamically

### 2.2 Document Detail Page Features

#### PDF Preview/Viewer
**Status**: ❌ Not implemented
**Priority**: Medium
**Description**:
- Document detail page shows metadata but no PDF preview
- Could embed PDF viewer or provide inline preview

**Implementation Needed**:
- PDF URL extraction from metadata
- PDF.js integration or iframe embed
- View: Enhance `detail.blade.php` with PDF viewer section

#### Related Documents
**Status**: ❌ Not implemented
**Priority**: Low
**Description**:
- Show related documents based on theme, organisation, or keywords

**Implementation Needed**:
- Service method to find related documents
- Display section in detail view

#### Share Functionality
**Status**: ❌ Not implemented
**Priority**: Low
**Description**:
- Social sharing buttons
- Copy link functionality

**Implementation Needed**:
- Share buttons component
- JavaScript for sharing functionality

### 2.3 Search Results Features

#### Export Search Results
**Status**: ❌ Not implemented
**Priority**: Low
**Description**:
- Export current search results to CSV, Excel, or JSON

**Implementation Needed**:
- Export button in search results
- Controller method for export generation
- Service for formatting export data

#### Save Search
**Status**: ❌ Not implemented
**Priority**: Low
**Description**:
- Allow users to save search queries for later use
- Requires user authentication

**Implementation Needed**:
- User authentication system
- Saved searches table/model
- UI for saving/loading searches

---

## 3. Missing API Endpoints

### 3.1 Document Statistics API

**Endpoint**: `GET /api/open-overheid/stats`
**Status**: ❌ Not implemented
**Priority**: Low
**Description**:
- Return statistics: total documents, documents by type, recent additions, etc.

**Implementation Needed**:
- Route in `routes/api.php` or web routes
- Controller method to calculate statistics
- JSON response format

### 3.2 Filter Options API

**Endpoint**: `GET /api/open-overheid/filters`
**Status**: ❌ Not implemented
**Priority**: Medium
**Description**:
- Return available filter values (document types, organisations, themes, etc.)
- Used for dynamic filter population

**Implementation Needed**:
- Route definition
- Service method to query distinct filter values
- JSON response with filter options

### 3.3 Bulk Export API

**Endpoint**: `POST /api/open-overheid/export`
**Status**: ❌ Not implemented
**Priority**: Low
**Description**:
- Export multiple documents based on search criteria
- Support multiple formats (JSON, XML, CSV)

**Implementation Needed**:
- Route definition
- Export service
- Queue job for large exports

---

## 4. Missing Admin/Management Pages

### 4.1 Admin Dashboard

**Route**: `/admin`
**Status**: ❌ Not implemented
**Priority**: Medium
**Description**:
- Overview of sync status, statistics, system health
- Access to sync controls, error logs

**Implementation Needed**:
- Authentication/authorization middleware
- Admin routes group
- Dashboard view and controller
- Admin layout template

### 4.2 Sync Management Page

**Route**: `/admin/sync`
**Status**: ❌ Not implemented
**Priority**: Medium
**Description**:
- Manual sync triggers
- Sync history/logs
- Sync status monitoring

**Implementation Needed**:
- Controller for sync management
- View with sync controls and logs
- Real-time sync status updates (optional)

### 4.3 Document Management

**Route**: `/admin/documents`
**Status**: ❌ Not implemented
**Priority**: Low
**Description**:
- List all documents
- Search/filter documents
- Edit/delete documents
- Re-sync individual documents

**Implementation Needed**:
- CRUD operations
- Admin document controller
- Management interface

---

## 5. Missing Documentation Pages

### 5.1 API Documentation

**Route**: `/api/documentation`
**Status**: ❌ Not implemented
**Priority**: Medium
**Description**:
- Interactive API documentation (OpenAPI/Swagger)
- Endpoint descriptions, parameters, examples

**Implementation Needed**:
- OpenAPI specification file
- Swagger UI integration
- Route to documentation page

### 5.2 User Guide

**Route**: `/gebruikershandleiding`
**Status**: ❌ Not implemented
**Priority**: Low
**Description**:
- How to use the search interface
- Filter explanations
- Export functionality guide

**Implementation Needed**:
- Documentation page
- Screenshots/examples
- Step-by-step guides

### 5.3 Developer Documentation

**Route**: `/developers`
**Status**: ❌ Not implemented
**Priority**: Low
**Description**:
- API usage examples
- Integration guides
- Code samples

**Implementation Needed**:
- Developer documentation page
- Code examples
- Integration tutorials

---

## 6. Priority Summary

### High Priority (Legal/Compliance)
1. ✅ Privacy & Cookies pages
2. ✅ Accessibility statement page

### Medium Priority (User Experience)
1. ✅ About page (`/over`)
2. ✅ Advanced search functionality
3. ✅ Filter statistics/dynamic counts
4. ✅ Admin dashboard
5. ✅ API documentation

### Low Priority (Nice to Have)
1. ✅ References page
2. ✅ What's new page
3. ✅ PDF preview
4. ✅ Related documents
5. ✅ Export search results
6. ✅ Saved searches
7. ✅ Share functionality
8. ✅ User guide
9. ✅ Developer documentation

---

## 7. Implementation Notes

### 7.1 Quick Wins

These can be implemented quickly with minimal code:

1. **External Links**: Update footer links to point to external URLs where appropriate
2. **Placeholder Pages**: Create basic pages for missing routes to avoid 404 errors
3. **Filter Counts**: Add database queries to calculate filter statistics

### 7.2 Requires Authentication

These features need user authentication system:

- Saved searches
- Admin pages
- User preferences

### 7.3 Requires Additional Services

These features may need third-party integrations:

- PDF preview (PDF.js)
- Social sharing (ShareThis, AddThis, or custom)
- Analytics (Google Analytics, or custom)

---

## 8. Suggested Implementation Order

1. **Phase 1**: Legal/Compliance pages (Privacy, Cookies, Accessibility)
2. **Phase 2**: Basic information pages (About, References)
3. **Phase 3**: Enhanced search features (Advanced search, filter counts)
4. **Phase 4**: API documentation and developer resources
5. **Phase 5**: Admin dashboard and management tools
6. **Phase 6**: Advanced features (PDF preview, related documents, exports)

---

**Last Updated**: 2025-12-20  
**Status**: Active tracking document


