# Pages & UI Blocks Definition

This document defines all pages in the application and their current UI blocks/components. Use this to specify which UI blocks you would like on each page.

---

## Table of Contents

1. [Home / Search Page](#1-home--search-page)
2. [Search Results Page](#2-search-results-page)
3. [Document Detail Page](#3-document-detail-page)
4. [V2026 Landing Page](#4-v2026-landing-page)
5. [Missing Pages (Not Yet Implemented)](#5-missing-pages-not-yet-implemented)

---

## 1. Home / Search Page

**Route**: `/` or `/zoek`  
**View**: `resources/views/zoek.blade.php`  
**Controller**: `SearchController::searchPage()`  
**Status**: ✅ Implemented

### Current UI Blocks:

1. **Navigation Header**
   - Logo/Branding: "Overheid.nl" + "Open overheid"
   - Navigation links: Home, Verwijzingen, Over
   - Background: Primary blue (#01689B)

2. **Breadcrumb Bar**
   - Current location indicator
   - Background: Surface color

3. **Info Banner**
   - Welcome message with link to "what's new"
   - Background: Light blue (#E3F2FD)

4. **Main Search Section**
   - Page title: "Vind overheidsdocumenten"
   - Document count display
   - Search input field
   - "Zoek alleen in titels" checkbox
   - "Uitgebreid zoeken" link
   - Search button with icon

5. **Info Cards (2 columns)**
   - Card 1: "Wat kunt u met deze website? Toegang tot overheidsdocumenten"
   - Card 2: "Wilt u weten wat openbaar is of documenten opvragen? De Woo-index helpt u op weg"
   - Each card has title, description, and link

6. **Footer**
   - Links: Over deze website, Overheid.nl, Privacy & Cookies, Toegankelijkheid
   - Background: Surface variant

### Suggested UI Blocks to Add/Modify:

- [ ] Hero section with search
- [ ] Feature highlights
- [ ] Statistics/numbers display
- [ ] Quick links section
- [ ] Recent documents preview
- [ ] Popular searches
- [ ] Newsletter signup
- [ ] Social media links

---

## 2. Search Results Page

**Route**: `/zoeken`  
**View**: `resources/views/zoekresultaten.blade.php`  
**Controller**: `SearchController::searchResults()`  
**Status**: ✅ Implemented

### Current UI Blocks:

1. **Navigation Header**
   - Same as Home page
   - Breadcrumb: Home / Zoekresultaten

2. **Main Content Grid (2 columns)**
   - **Left Sidebar (Filters)**
     - Search keywords input
     - "Zoek alleen in titels" checkbox
     - Date filters (Beschikbaar sinds)
       - Radio buttons: Week, Maand, Jaar, Zelf
       - Date pickers (when "Zelf" selected)
     - Document type filter (Documentsoort)
       - Checkboxes with counts
     - Theme filter (Thema)
       - Checkboxes with counts
     - Organization filter (Organisatie)
       - Checkboxes with counts
     - File type filter (Bestandstype)
       - Checkboxes with counts
     - Information category filter (Informatiecategorie)
       - Checkboxes with counts
     - "Filters wissen" (Clear filters) button
     - "Zoekopdracht uitvoeren" (Apply filters) button

   - **Right Content Area**
     - Results header
       - Total results count
       - Sort dropdown (Relevantie, Publicatiedatum, Wijzigingsdatum)
       - Results per page selector (10, 20, 50)
     - Search results list
       - Each result shows:
         - Document type badge
         - Title (linked)
         - Description/excerpt
         - Metadata (date, organization, theme)
         - "Bekijk document" link
     - Pagination
       - Previous/Next buttons
       - Page numbers
       - Results count info

3. **Footer**
   - Same as Home page

### Suggested UI Blocks to Add/Modify:

- [ ] Export results button
- [ ] Save search functionality
- [ ] Share search results
- [ ] Related searches suggestions
- [ ] Search tips/help
- [ ] Advanced search modal
- [ ] Filter presets
- [ ] Search history
- [ ] Results visualization (charts/graphs)

---

## 3. Document Detail Page

**Route**: `/open-overheid/documents/{id}`  
**View**: `resources/views/detail.blade.php`  
**Controller**: `DocumentController::show()`  
**Status**: ✅ Implemented

### Current UI Blocks:

1. **Navigation Header**
   - Same as other pages
   - Breadcrumb: Home / Document details

2. **Back Link**
   - "← Terug naar zoekresultaten" link

3. **Document Title Block**
   - Title
   - Quick info (date, organization, document type)
   - External link to original document

4. **Document Characteristics Section**
   - Grid layout (2 columns)
   - Fields displayed:
     - Document type
     - Publication date
     - Modification date
     - Organization
     - Theme
     - Information category
     - File type
     - Language
     - And more metadata fields

5. **Document Description**
   - Full description text

6. **Document Files Section**
   - List of downloadable files
   - File name, size, type
   - Download links

7. **Export Options**
   - JSON export button
   - XML export button

8. **Footer**
   - Same as other pages

### Suggested UI Blocks to Add/Modify:

- [ ] Document preview/embed
- [ ] PDF viewer
- [ ] Related documents section
- [ ] Share document buttons
- [ ] Print button
- [ ] Document timeline/history
- [ ] Tags/keywords display
- [ ] Citation information
- [ ] Download all files button
- [ ] Document statistics

---

## 4. V2026 Landing Page

**Route**: `/v2026`  
**View**: `resources/views/v2026.blade.php`  
**Controller**: `SearchController::v2026LandingPage()`  
**Status**: ✅ Implemented

### Current UI Blocks:

1. **Header (Absolute positioned)**
   - Logo: "Open Overheid"
   - Desktop navigation: Zoeken, Over, Documenten, Kennisbank, Bouw mee!
   - Mobile menu button
   - "Zoek documenten" CTA link

2. **Mobile Menu Dialog**
   - Logo
   - Navigation links
   - Close button

3. **Hero Section** 
   - Background pattern
   - "Nieuw" badge with link
   - Main heading: "Vind overheidsdocumenten"
   - Subheading with document count
   - **Search Form** (integrated)
     - Search input
     - Search button
     - Helper text
   - Mobile app screenshot placeholder

4. **Logo Cloud Section**
   - Grid of organization logos/names
   - Rijksoverheid, Gemeenten, Provincies, Waterschappen, Open Data

5. **Feature Section (Dark Background)**
   - 2-column grid
   - Left: Heading and description
   - Right: Screenshot image
   - Feature list with icons:
     - Snel zoeken
     - Veilig en betrouwbaar
     - Volledig open source

6. **Feature Section (Light Background)**
   - Centered heading
   - 3-column feature grid:
     - Geavanceerd zoeken
     - Betrouwbare bronnen
     - Real-time updates
   - Each feature has icon, title, description, and link

7. **Newsletter Section**
   - Dark background with gradient
   - Heading and description
   - Email input form
   - Subscribe button
   - Background decorative SVG

8. **Testimonials Section**
   - Heading
   - Grid of testimonial cards
   - Multiple testimonials with:
     - Quote text
     - Avatar/initials
     - Name and role

9. **Footer**
   - Logo and description
   - 4-column link grid:
     - Oplossingen (Zoeken, Documenten, Filters, API)
     - Ondersteuning (Documentatie, Gidsen, Help)
     - Over (Over deze website, Open source, Contact)
     - Juridisch (Servicevoorwaarden, Privacybeleid, Licentie)
   - Newsletter signup form
   - Copyright notice

### Suggested UI Blocks to Add/Modify:

- [ ] Statistics/metrics section
- [ ] How it works section
- [ ] API showcase
- [ ] Integration examples
- [ ] Video demo
- [ ] FAQ section
- [ ] Blog/news section
- [ ] Community section
- [ ] Pricing/plans (if applicable)

---

## 5. Missing Pages (Not Yet Implemented)

These pages are referenced in navigation/footer but don't exist yet:

### 5.1 Verwijzingen (References) Page

**Route**: `/verwijzingen` (not implemented)  
**Status**: ❌ Missing

### Suggested UI Blocks:

- [ ] Page header with title
- [ ] Links grid/list
- [ ] Categories/sections
- [ ] External link indicators
- [ ] Search/filter links
- [ ] Description text

---

### 5.2 Over (About) Page

**Route**: `/over` (not implemented)  
**Status**: ❌ Missing

### Suggested UI Blocks:

- [ ] Hero section
- [ ] Mission/vision section
- [ ] How it works
- [ ] Data sources
- [ ] Team/contributors
- [ ] Contact information
- [ ] Timeline/history
- [ ] Statistics

---

### 5.3 Over deze website (About this website) Page

**Route**: Not defined (referenced in footer)  
**Status**: ❌ Missing

### Suggested UI Blocks:

- [ ] Page header
- [ ] Website description
- [ ] Technical details
- [ ] Open source information
- [ ] Version/changelog
- [ ] Credits/acknowledgments

---

### 5.4 Privacy & Cookies Page

**Route**: Not defined (referenced in footer)  
**Status**: ❌ Missing

### Suggested UI Blocks:

- [ ] Privacy policy content
- [ ] Cookie policy
- [ ] Data processing information
- [ ] User rights
- [ ] Contact for privacy matters

---

### 5.5 Toegankelijkheid (Accessibility) Page

**Route**: Not defined (referenced in footer)  
**Status**: ❌ Missing

### Suggested UI Blocks:

- [ ] Accessibility statement
- [ ] WCAG compliance information
- [ ] Keyboard navigation info
- [ ] Screen reader support
- [ ] Contact for accessibility issues

---

## 6. UI Block Library

Common UI blocks that can be reused across pages:

### Navigation & Header Blocks
- [ ] Top navigation bar
- [ ] Breadcrumb navigation
- [ ] Mobile menu
- [ ] User menu (if authenticated)
- [ ] Language selector

### Search Blocks
- [ ] Simple search input
- [ ] Advanced search form
- [ ] Search filters sidebar
- [ ] Search suggestions/autocomplete
- [ ] Search history
- [ ] Saved searches

### Content Blocks
- [ ] Hero section
- [ ] Feature cards
- [ ] Statistics/metrics display
- [ ] Testimonials grid
- [ ] FAQ accordion
- [ ] Timeline
- [ ] Image gallery
- [ ] Video embed
- [ ] Code examples

### List & Grid Blocks
- [ ] Document list
- [ ] Card grid
- [ ] Table view
- [ ] Pagination
- [ ] Infinite scroll
- [ ] Filter chips

### Form Blocks
- [ ] Contact form
- [ ] Newsletter signup
- [ ] Feedback form
- [ ] Search form
- [ ] Filter form

### Action Blocks
- [ ] CTA buttons
- [ ] Download buttons
- [ ] Share buttons
- [ ] Export options
- [ ] Print button

### Information Blocks
- [ ] Info banner
- [ ] Alert/notification
- [ ] Tooltip
- [ ] Help text
- [ ] Metadata display

### Footer Blocks
- [ ] Footer links
- [ ] Social media links
- [ ] Newsletter signup
- [ ] Copyright notice
- [ ] Legal links

---

## 7. How to Use This Document

1. **Review each page** and its current UI blocks
2. **Specify which blocks** you want to keep, remove, or modify
3. **Add new blocks** from the UI Block Library or suggest custom ones
4. **Note any specific requirements** for each block (styling, behavior, content)

### Example Format for Feedback:

```
## 1. Home / Search Page

Keep:
- Navigation Header
- Main Search Section
- Info Cards

Modify:
- Hero section: Add background image
- Search form: Make it larger and more prominent

Add:
- Statistics section: Show document count, organizations count
- Recent documents preview: Show 5 most recent documents
- Popular searches: Show top 10 search terms

Remove:
- Info Banner (move content elsewhere)
```

---

## 8. Notes

- All pages currently use Material Design 3 color scheme
- Primary color: #01689B (blue)
- Font: Roboto
- Responsive design with mobile-first approach
- Accessibility features should be maintained
- All text is in Dutch

---

**Last Updated**: {{ date('Y-m-d') }}

