# Missing Features Analysis: open.minvws.nl vs Our Implementation

Based on analysis of https://open.minvws.nl/zoeken?q=

## ✅ Currently Implemented

1. **Basic Search** - Text search functionality
2. **Date Filters** - Radio buttons for predefined periods (week, month, year)
3. **Document Type Filter** - Filter by documentsoort
4. **Theme Filter** - Filter by thema
5. **Organization Filter** - Filter by organisatie
6. **Sorting** - Sort by relevance, publication date, modified date
7. **Pagination** - Page navigation
8. **Results per page** - 10, 20, 50 options
9. **Dynamic filter counts** - Counts based on current results
10. **External links** - Links to open.overheid.nl

## ❌ Missing Features

### 1. **Custom Date Range Picker** (High Priority)
**open.minvws.nl has:**
- Two date input fields: "vanaf (dd/mm/yyyy)" and "tot en met (dd/mm/yyyy)"
- Calendar icon pickers
- Custom date range selection

**Our implementation:**
- Only radio buttons for predefined periods (week, month, year)
- No custom date range picker

**Implementation needed:**
- Add date input fields with date picker
- Support for `publicatiedatum_van` and `publicatiedatum_tot` (already in controller validation)
- Date picker UI component

### 2. **File Type Filter** (High Priority)
**open.minvws.nl has:**
- "Type bronbestand" filter section
- Options: Word-document, E-mailbericht, PDF, Chatbericht, Presentatie, Spreadsheet, Afbeelding, etc.
- Counts for each file type

**Our implementation:**
- No file type filter
- Only shows PDF icon in results

**Implementation needed:**
- Extract file type from metadata (mime-type or bestand type)
- Add file type filter section
- Show file type icons in results (not just PDF)

### 3. **Hierarchical/Expandable Filter Categories** (Medium Priority)
**open.minvws.nl has:**
- Filters with subcategories (e.g., "Woo-besluiten" → "Publicaties", "Documenten", "Hoofddocument", "Bijlagen")
- Expandable/collapsible sections
- "Toon meer" / "Toon minder" buttons

**Our implementation:**
- Flat filter structure
- "Toon meer" buttons exist but don't expand subcategories

**Implementation needed:**
- Hierarchical filter structure
- Expandable/collapsible filter sections
- JavaScript for show/hide functionality

### 4. **Decision Type Filter** (Medium Priority)
**open.minvws.nl has:**
- "Soort besluit" filter
- Options: "Geen openbaarmaking", "Gedeeltelijke openbaarmaking", "Reeds openbaar", "Openbaarmaking"
- Counts for each type

**Our implementation:**
- No decision type filter

**Implementation needed:**
- Extract decision type from metadata
- Add "Soort besluit" filter section
- Display decision type in results

### 5. **Assessment Grounds Filter** (Low Priority)
**open.minvws.nl has:**
- "Beoordelingsgronden" filter
- Many legal grounds (5.1.2e, 10.2e, 11.1, etc.)
- Counts for each ground

**Our implementation:**
- No assessment grounds filter

**Implementation needed:**
- Extract assessment grounds from metadata
- Add filter section (may be complex due to many options)
- Consider "Toon meer" for this section

### 6. **Result Limit Notice** (Low Priority)
**open.minvws.nl has:**
- Notice: "De eerste 10.000 resultaten van uw zoekopdracht worden getoond. Verfijn uw zoekopdracht door middel van specifieke zoektermen en de beschikbare filteropties."

**Our implementation:**
- No such notice

**Implementation needed:**
- Add informational notice when results exceed limit
- Help users understand they should refine search

### 7. **Enhanced Result Display** (Medium Priority)
**open.minvws.nl shows:**
- File type icon (not just PDF)
- Page count ("1 pagina's")
- Disclosure status ("Gedeeltelijke openbaarmaking", "Reeds openbaar")
- Document number ("Documentnummer 665555")
- "Onderdeel van:" (Part of) with link to parent document/decision

**Our implementation:**
- Shows PDF icon (hardcoded)
- Shows publication date, modified date
- Shows organization
- Missing: page count, disclosure status, document number, "Onderdeel van"

**Implementation needed:**
- Extract and display page count from metadata
- Extract and display disclosure status
- Extract and display document number
- Extract and display "Onderdeel van" relationship
- Show correct file type icon (not just PDF)

### 8. **Enhanced Sorting Options** (Low Priority)
**open.minvws.nl has:**
- "Sorteren op: Relevantie"
- "Sorteren op: Publicatiedatum (Nieuwste bovenaan)"
- "Sorteren op: Publicatiedatum (Oudste bovenaan)"

**Our implementation:**
- "Relevantie"
- "Publicatiedatum"
- "Laatst gewijzigd"

**Implementation needed:**
- Split "Publicatiedatum" into "Nieuwste bovenaan" and "Oudste bovenaan"
- More explicit sorting labels

### 9. **Collapsible Filter Sections** (Medium Priority)
**open.minvws.nl has:**
- Filter sections can be expanded/collapsed
- Better organization of many filters

**Our implementation:**
- All filters always visible
- Can be overwhelming with many options

**Implementation needed:**
- Add collapse/expand functionality to filter sections
- Remember user preferences (localStorage)
- Better visual organization

### 10. **"Ga naar de zoekresultaten" Links** (Low Priority)
**open.minvws.nl has:**
- "Ga naar de zoekresultaten" links in filter sections
- Allows quick jump to results after applying filters

**Our implementation:**
- No such quick navigation

**Implementation needed:**
- Add scroll-to-results functionality
- Or auto-submit on filter change (already implemented)

## Implementation Priority

### High Priority (Core Functionality)
1. Custom Date Range Picker
2. File Type Filter
3. Enhanced Result Display (page count, disclosure status, document number)

### Medium Priority (User Experience)
4. Hierarchical/Expandable Filter Categories
5. Decision Type Filter
6. Collapsible Filter Sections

### Low Priority (Nice to Have)
7. Assessment Grounds Filter
8. Result Limit Notice
9. Enhanced Sorting Options
10. "Ga naar de zoekresultaten" Links

## Technical Notes

- Date range picker validation already exists in controller (`publicatiedatum_van`, `publicatiedatum_tot`)
- File type can be extracted from `metadata['versies'][0]['bestanden'][0]['mime-type']`
- Disclosure status likely in metadata under decision/verzoek information
- Document number should be in metadata
- "Onderdeel van" relationship needs to be extracted from metadata structure

