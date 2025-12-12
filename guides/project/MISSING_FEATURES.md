# Missing Features Summary

This document provides a quick reference of features that are planned but not yet implemented.

## Test Status

- ✅ **55 tests passing** - All core functionality working
- ⏭️ **14 tests skipped** - Missing features (intentionally skipped)

## Missing Features by Priority

### 🔴 High Priority

1. **Custom Date Range Picker**
   - Status: Controller supports it, but UI doesn't show date input fields
   - Needed: Date input fields with calendar picker for "vanaf" and "tot en met"
   - Test: `tests/Feature/MissingFeaturesTest.php:20`

2. **File Type Filter**
   - Status: Not implemented
   - Needed: Filter by file type (PDF, Word, Email, Spreadsheet, etc.)
   - Needed: Show correct file type icons in results (not just PDF)
   - Tests: `tests/Feature/MissingFeaturesTest.php:38, 51`

### 🟡 Medium Priority

3. **Hierarchical/Expandable Filter Categories**
   - Status: Flat structure exists, but no expandable subcategories
   - Needed: Expandable filter sections with "Toon meer" / "Toon minder"
   - Test: `tests/Feature/MissingFeaturesTest.php:76`

4. **Decision Type Filter (Soort besluit)**
   - Status: Not implemented
   - Needed: Filter by decision type (Geen openbaarmaking, Gedeeltelijke openbaarmaking, etc.)
   - Test: `tests/Feature/MissingFeaturesTest.php:91`

5. **Enhanced Result Display**
   - Status: Partially implemented
   - Needed:
     - Page count display
     - Disclosure status (Gedeeltelijke openbaarmaking, Reeds openbaar)
     - Document number display
     - "Onderdeel van" relationship display
   - Tests: `tests/Feature/MissingFeaturesTest.php:138, 160, 172, 193`

6. **Enhanced Sorting Labels**
   - Status: Partially implemented (has sorting, but labels could be clearer)
   - Needed: Explicit "Nieuwste bovenaan" and "Oudste bovenaan" options
   - Test: `tests/Feature/MissingFeaturesTest.php:207`

### 🟢 Low Priority

7. **Assessment Grounds Filter (Beoordelingsgronden)**
   - Status: Not implemented
   - Needed: Filter by legal assessment grounds
   - Test: `tests/Feature/MissingFeaturesTest.php:107`

8. **Result Limit Notice**
   - Status: Not implemented
   - Needed: Show notice when results exceed limit (e.g., "De eerste 10.000 resultaten...")
   - Test: `tests/Feature/MissingFeaturesTest.php:121`

9. **Collapsible Filter Sections**
   - Status: Not implemented
   - Needed: Ability to collapse/expand filter sections
   - Test: `tests/Feature/MissingFeaturesTest.php:222`

10. **"Ga naar de zoekresultaten" Links**
    - Status: Not implemented
    - Needed: Quick navigation links in filter sections
    - Test: `tests/Feature/MissingFeaturesTest.php:238`

## Implementation Notes

- All missing features are documented in `guides/project/missing-features-analysis.md`
- Tests for missing features are in `tests/Feature/MissingFeaturesTest.php` (all skipped)
- Controller already supports custom date ranges (`publicatiedatum_van`, `publicatiedatum_tot`)
- Database schema supports all needed metadata fields

## Reference

- Full analysis: [`guides/project/missing-features-analysis.md`](guides/project/missing-features-analysis.md)
- Reference site: https://open.minvws.nl

