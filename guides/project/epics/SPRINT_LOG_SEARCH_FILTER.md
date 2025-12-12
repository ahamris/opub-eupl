# Sprint Log: Zoek- en Filterfunctionaliteit Verbetering

## Sprint Overzicht
**Sprint Start**: 2025-01-XX  
**Epic**: Zoek- en Filterfunctionaliteit Verbetering  
**Status**: 🚧 In Progress

---

## ✅ Voltooid (Done)

### Backend Services
- [x] **QueryParsingService** - Detecteert filter vs zoekwoord
  - `isFilterValue()` - Check of query filterwaarde is
  - `parseQuery()` - Parseert query type
  - `detectFilterType()` - Detecteert filter type
  - `getFilterSuggestions()` - Genereert filter suggesties
  - **Test**: ⏳ Pending

- [x] **FilterCountService** - Berekent filter counts met caching
  - `calculateFilterCounts()` - Dynamische counts
  - `getAllFilterOptions()` - Alle beschikbare filters
  - `calculateFileTypeCounts()` - Bestandstype counts (FIXED)
  - **Test**: ⏳ Pending

- [x] **SearchController Updates**
  - Dependency injection voor nieuwe services
  - Autocomplete endpoint uitgebreid met query type detectie
  - Response bevat `query_type`, `is_filter_value`, `filter_type`
  - Oude private methodes verwijderd (refactored)
  - **Test**: ⏳ Pending

### Fixes
- [x] **Bestandstype Filter Counts** - FIXED
  - Probleem: Counts werden niet berekend
  - Oplossing: PostgreSQL JSON query voor mime-type extraction
  - Mapping: mime-type → display labels (PDF, Word-document, etc.)

---

## 🚧 In Progress

### Frontend Components
- [x] **Active Filters Component** - ✅ Al aanwezig in view
- [x] **Autocomplete Dropdown** - ✅ Volledig herzien
  - ✅ Altijd "Zoeken naar..." optie als eerste
  - ✅ "Filter op..." optie wanneer filter gedetecteerd
  - ✅ Duidelijke visuele scheiding (Snelle acties sectie)
  - ✅ Betere UX met icons en beschrijvingen
  - ✅ Meervoud/singular matching voor categorieën (advies → adviezen)
  - **Test**: ⏳ Pending

- [x] **Search Highlighting** - ✅ Geïmplementeerd
  - Highlighting in titel en beschrijving
  - Mark tags met styling
  - Case-insensitive matching
  - **Test**: ⏳ Pending

---

## 📋 To Do

### Frontend
- [x] Active Filters Component - ✅ Al aanwezig
- [x] Autocomplete dropdown verbeteren - ✅ Voltooid
- [x] Search highlighting implementeren - ✅ Voltooid
- [ ] Empty state verbeteren - ⏳ Pending

### Tests
- [ ] Unit tests: QueryParsingService (5 tests)
- [ ] Unit tests: FilterCountService (6 tests)
- [ ] Integration tests: Autocomplete flow (3 tests)
- [ ] Feature tests: Search + Filter combinatie (5 tests)
- [ ] Browser tests: UI interactie (4 tests)

---

## 🧪 Test Resultaten

### Unit Tests
| Service | Tests | Status | Notes |
|---------|-------|--------|-------|
| QueryParsingService | 0/5 | ⏳ Pending | - |
| FilterCountService | 0/6 | ⏳ Pending | - |

### Integration Tests
| Feature | Tests | Status | Notes |
|---------|-------|--------|-------|
| Autocomplete | 0/3 | ⏳ Pending | - |
| Filter Counts | 0/2 | ⏳ Pending | - |

### Feature Tests
| Feature | Tests | Status | Notes |
|---------|-------|--------|-------|
| Search + Filter | 0/5 | ⏳ Pending | - |

---

## 📊 Metrics

### Code Quality
- ✅ Laravel Pint: Passed
- ✅ Linter: No errors
- ✅ Type Hints: Complete
- ✅ PHPDoc: Complete

### Performance
- ⏳ Autocomplete response: Not measured yet
- ⏳ Filter count updates: Not measured yet

---

## 🐛 Issues & Fixes

### Issue #1: Bestandstype Filter Counts
**Status**: ✅ Fixed  
**Probleem**: Filter counts werden niet berekend voor bestandstype  
**Oplossing**: PostgreSQL JSON query toegevoegd in FilterCountService  
**Test**: ⏳ Pending

### Issue #2: Autocomplete Werkt Niet / Onduidelijk
**Status**: ✅ Fixed  
**Probleem**: 
- Autocomplete toonde geen duidelijke "Zoeken naar..." optie
- Onduidelijk verschil tussen zoeken en filteren
- "advies" werd niet herkend als "adviezen" categorie
- Dropdown verdween meteen (click handler probleem)
- Live search herlaadde pagina na 500ms

**Oplossing**: 
- Altijd "Zoeken naar X" optie als eerste suggestie
- Expliciete "Filter op X" optie wanneer filter gedetecteerd
- Meervoud/singular matching voor categorieën
- Betere visuele scheiding met "Snelle acties" sectie
- Icons en beschrijvingen voor duidelijkheid
- **Dropdown blijft open**: 
  - `dropdownShouldStayOpen` flag systeem
  - Live search verwijderd (verstoorde dropdown)
  - Verbeterde blur handling (250ms delay)
  - Mouse enter/leave handlers
  - Click outside handler verbeterd

**Test**: ⏳ Pending

---

## 📝 Notes

- Bestandstype wordt opgehaald uit `metadata->versies[0]->bestanden[0]->mime-type`
- Caching: 5 minuten TTL voor filter counts
- PostgreSQL JSON queries gebruikt voor efficiëntie

---

## 🔄 Next Actions

1. ✅ Active Filters Component - Al aanwezig
2. ✅ Autocomplete dropdown verbeteren - Voltooid
3. ✅ Search highlighting - Voltooid
4. ⏳ Tests schrijven - Next priority
5. ⏳ Performance metingen
6. ⏳ Empty state verbeteren

## 📈 Sprint Progress

**Voltooid**: 8/11 taken (73%)  
**In Progress**: 0/11 taken (0%)  
**Pending**: 3/11 taken (27%)

### Completed Features
- ✅ QueryParsingService
- ✅ FilterCountService  
- ✅ Bestandstype counts fix
- ✅ Autocomplete volledig herzien (met "Zoeken naar" + "Filter op" opties)
- ✅ Meervoud/singular matching voor categorieën
- ✅ Search highlighting
- ✅ SearchController refactoring
- ✅ Frontend autocomplete UI verbetering

### Remaining Work
- ⏳ Tests (Unit, Integration, Feature, Browser)
- ⏳ Empty state verbetering
- ⏳ Performance optimalisatie
