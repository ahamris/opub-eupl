# TOOI (Toegankelijkheid Ondersteunende Openbare Informatie) Reference Guide

**Complete guide for working with TOOI 1.4.0-rc standard, waardelijsten (value lists), and government organization identifiers**

---

## Table of Contents

1. [Overview](#1-overview)
2. [TOOI 1.4.0-rc Structure](#2-tooi-140-rc-structure)
3. [Waardelijsten (Value Lists)](#3-waardelijsten-value-lists)
4. [Government Organization Registers](#4-government-organization-registers)
5. [TOOI Ontology](#5-tooi-ontology)
6. [URI Strategy](#6-uri-strategy)
7. [JSON Data Structure](#7-json-data-structure)
8. [Implementation Examples](#8-implementation-examples)
9. [AI Tool Guidelines](#9-ai-tool-guidelines)

---

## 1. Overview

### 1.1 Purpose

TOOI (Toegankelijkheid Ondersteunende Openbare Informatie) is a Dutch government standard for describing government information and organizations using Linked Data principles. This guide provides a complete reference for working with TOOI 1.4.0-rc, including waardelijsten (value lists) and government organization registers.

### 1.2 TOOI 1.4.0-rc Status

- **Status**: Release Candidate (RC)
- **Published**: July 8, 2025
- **Public Consultation**: September 5 - October 5, 2025
- **Next Version**: If no objections, will become TOOI-1.4.1

### 1.3 Key Components

TOOI 1.4.0-rc consists of:

- **TOOI-inleiding 1.0.5**: Introduction and structure
- **TOOI-URI-strategie 1.0.4**: URI strategy for minting TOOI URIs
- **TOOI-ontologie 1.6.2**: Core ontology (`tooiont`) for government information
- **TOOI-geo 1.0.1**: New ontology (`tooigeo`) for spatial objects and locations
- **TOOI-waardelijstontologie 1.1.4**: Ontology (`tooiwl`) for value lists
- **TOOI-thesauri 1.0.4**: Thesaurus structure and maintenance
- **TOOI-thesaurus-kern 1.5.0**: Core thesaurus (`tooikern`)
- **TOOI-registers 1.0.4**: Register structure and maintenance
- **TOOI-waardelijsten 1.0.6**: Value lists documentation

### 1.4 What's New in 1.4.0

- **TOOI-geo**: New ontology for spatial objects and locations
- **Basisvoorziening Bestuurlijke Gebieden**: Extension for administrative boundaries
- **Updated TOOI-Kernthesaurus**: New concepts in various schemas
- **Z-versions**: Editorial updates to other modules

---

## 2. TOOI 1.4.0-rc Structure

### 2.1 Core Ontology (tooiont)

The TOOI ontology describes government information and organizations using RDF/OWL principles.

**Key Classes:**
- `overheid:Organisatie` - Government organization
- `overheid:Ministerie` - Ministry
- `overheid:Gemeente` - Municipality
- `overheid:Provincie` - Province
- `overheid:Waterschap` - Water board
- `overheid:ZBO` - Independent administrative body
- `overheid:Informatietype` - Information type
- `overheid:Document` - Document

**Key Properties:**
- `overheid:heeftVerantwoordelijke` - Has responsible organization
- `overheid:heeftPublisher` - Has publisher
- `overheid:heeftOpsteller` - Has creator
- `overheid:heeftThema` - Has theme
- `overheid:heeftDocumentsoort` - Has document type

### 2.2 TOOI-geo (New in 1.4.0)

Extension ontology for spatial objects and locations:

- Links government organizations to administrative boundaries
- Supports geographic registrations
- Part of "Zicht op Nederland" project

### 2.3 Value Lists (Waardelijsten)

TOOI maintains several value lists (registers) for government organizations:

1. **Gemeenten** (Municipalities)
2. **Provincies** (Provinces)
3. **Ministeries** (Ministries)
4. **Waterschappen** (Water boards)
5. **ZBO** (Zelfstandige bestuursorganen - Independent administrative bodies)
6. **Caribische openbare lichamen** (Caribbean public bodies)
7. **Overige overheidsorganisaties** (Other government organizations)
8. **Samenwerkingsorganisaties** (Cooperation organizations)

---

## 3. Waardelijsten (Value Lists)

### 3.1 Register Structure

Each waardelijst (value list) follows this structure:

**Work URI Pattern:**
```
https://identifier.overheid.nl/tooi/set/{register_name}
```

**Expression URI Pattern:**
```
https://identifier.overheid.nl/tooi/set/{register_name}/{version}
```

**Item URI Pattern:**
```
https://identifier.overheid.nl/tooi/id/{type}/{identifier}
```

### 3.2 Available Registers

#### Gemeenten (Municipalities)
- **Work URI**: `https://identifier.overheid.nl/tooi/set/rwc_gemeenten_compleet`
- **Latest Version**: v4 (as of December 22, 2022)
- **Description**: Complete register of municipalities that have existed, exist, or will exist
- **Responsible**: Ministerie van Binnenlandse Zaken en Koninkrijksrelaties
- **Updates**: Includes municipal reorganizations

#### Provincies (Provinces)
- **Work URI**: `https://identifier.overheid.nl/tooi/set/rwc_provincies_compleet`
- **Description**: Complete register of provinces

#### Ministeries (Ministries)
- **Work URI**: `https://identifier.overheid.nl/tooi/set/rwc_ministeries_compleet`
- **Description**: Complete register of ministries

#### Waterschappen (Water Boards)
- **Work URI**: `https://identifier.overheid.nl/tooi/set/rwc_waterschappen_compleet`
- **Description**: Complete register of water boards

#### ZBO (Independent Administrative Bodies)
- **Work URI**: `https://identifier.overheid.nl/tooi/set/rwc_zbo_compleet`
- **Description**: Complete register of independent administrative bodies

#### Caribische Openbare Lichamen (Caribbean Public Bodies)
- **Work URI**: `https://identifier.overheid.nl/tooi/set/rwc_caribische_openbare_lichamen_compleet`
- **Latest Version**: v1 (as of November 15, 2021)
- **Description**: Register of Caribbean public bodies

#### Overige Overheidsorganisaties (Other Government Organizations)
- **Work URI**: `https://identifier.overheid.nl/tooi/set/rwc_overige_overheidsorganisaties_compleet`
- **Description**: Register of other government organizations

#### Samenwerkingsorganisaties (Cooperation Organizations)
- **Work URI**: `https://identifier.overheid.nl/tooi/set/rwc_samenwerkingsorganisaties_compleet`
- **Description**: Register of cooperation organizations

### 3.3 Register Metadata

Each register has the following metadata:

```json
{
  "identifier": "https://identifier.overheid.nl/tooi/set/rwc_gemeenten_compleet",
  "title": "Register gemeenten compleet",
  "description": "Waardelijst met gemeenten die voor zover bekend op de zichtdatum bestaan hebben, bestaan of zullen bestaan.",
  "responsible": "https://identifier.overheid.nl/tooi/id/ministerie/mnre1034",
  "derivedFrom": "https://identifier.overheid.nl/tooi/id/gemeente",
  "versions": [
    {
      "version": "4",
      "versionInfo": "Gemeentelijke herindeling 1 januari 2023 verwerkt.",
      "createdAt": "2022-12-22T14:42:00Z",
      "publicationDate": "2022-12-22",
      "visibilityDate": "2022-12-22"
    }
  ]
}
```

---

## 4. Government Organization Registers

### 4.1 Organization Types

TOOI distinguishes between different types of government organizations:

| Type | URI Pattern | Example |
|------|-------------|---------|
| Ministerie | `/tooi/id/ministerie/{id}` | `https://identifier.overheid.nl/tooi/id/ministerie/mnre1058` |
| Gemeente | `/tooi/id/gemeente/{id}` | `https://identifier.overheid.nl/tooi/id/gemeente/{gemeentecode}` |
| Provincie | `/tooi/id/provincie/{id}` | `https://identifier.overheid.nl/tooi/id/provincie/{provinciecode}` |
| Waterschap | `/tooi/id/waterschap/{id}` | `https://identifier.overheid.nl/tooi/id/waterschap/{code}` |
| ZBO | `/tooi/id/zbo/{id}` | `https://identifier.overheid.nl/tooi/id/zbo/{code}` |

### 4.2 Organization Properties

Each organization in TOOI has:

- **Identifier**: Unique TOOI URI
- **Label**: Official name (in Dutch)
- **Type**: Organization type (e.g., `overheid:Ministerie`)
- **Valid From**: Start date (if applicable)
- **Valid To**: End date (if applicable)
- **Status**: Current status (active, inactive, etc.)

---

## 5. TOOI Ontology

### 5.1 Core Classes

**Organisatie (Organization)**
```turtle
overheid:Organisatie a owl:Class ;
    rdfs:label "Organisatie"@nl ;
    rdfs:comment "Een overheidsorganisatie"@nl .
```

**Document (Document)**
```turtle
overheid:Document a owl:Class ;
    rdfs:label "Document"@nl ;
    rdfs:comment "Een overheidsdocument"@nl .
```

### 5.2 Core Properties

**heeftVerantwoordelijke (has responsible)**
```turtle
overheid:heeftVerantwoordelijke a owl:ObjectProperty ;
    rdfs:label "heeft verantwoordelijke"@nl ;
    rdfs:domain overheid:Document ;
    rdfs:range overheid:Organisatie .
```

**heeftPublisher (has publisher)**
```turtle
overheid:heeftPublisher a owl:ObjectProperty ;
    rdfs:label "heeft publisher"@nl ;
    rdfs:domain overheid:Document ;
    rdfs:range overheid:Organisatie .
```

### 5.3 Thesaurus Integration

TOOI uses thesauri for classification:

- **tooikern**: Core concepts (document types, themes)
- **tooitop**: Theme classification
- **tooiwep**: Electronic publications law
- **tooibwb**: Basiswettenbestand taxonomies

---

## 6. URI Strategy

### 6.1 URI Patterns

TOOI uses persistent URIs following this strategy:

**Organization URIs:**
```
https://identifier.overheid.nl/tooi/id/{type}/{identifier}
```

**Value List URIs:**
```
https://identifier.overheid.nl/tooi/set/{list_name}
```

**Value List Expression URIs:**
```
https://identifier.overheid.nl/tooi/set/{list_name}/{version}
```

**Thesaurus Concept URIs:**
```
https://identifier.overheid.nl/tooi/def/thes/{thesaurus}/{concept_id}
```

### 6.2 URI Resolution

TOOI URIs resolve to:

- **HTML**: Human-readable representation (default)
- **JSON-LD**: Machine-readable Linked Data (with `Accept: application/ld+json`)
- **RDF/XML**: RDF/XML format (with `Accept: application/rdf+xml`)
- **Turtle**: Turtle format (with `Accept: text/turtle`)

### 6.3 Content Negotiation

Request JSON-LD:
```bash
curl -H "Accept: application/ld+json" \
  "https://identifier.overheid.nl/tooi/id/ministerie/mnre1058"
```

Request RDF/XML:
```bash
curl -H "Accept: application/rdf+xml" \
  "https://identifier.overheid.nl/tooi/id/ministerie/mnre1058"
```

---

## 7. JSON Data Structure

### 7.1 Flat JSON Format

For easier processing, TOOI data can be flattened into a simple JSON structure. See `tooi_waardelijsten.json` for the complete dataset.

**Structure:**
```json
{
  "metadata": {
    "exported_at": "2025-12-11T00:00:00Z",
    "tooi_version": "1.4.0-rc",
    "source": "https://standaarden.overheid.nl/tooi/"
  },
  "registers": {
    "gemeenten": {
      "work_uri": "https://identifier.overheid.nl/tooi/set/rwc_gemeenten_compleet",
      "latest_version": "4",
      "items": []
    },
    "provincies": {
      "work_uri": "https://identifier.overheid.nl/tooi/set/rwc_provincies_compleet",
      "items": []
    }
  }
}
```

### 7.2 Organization Item Structure

Each organization item in the flat JSON:

```json
{
  "id": "https://identifier.overheid.nl/tooi/id/ministerie/mnre1058",
  "type": "overheid:Ministerie",
  "label": "ministerie van Justitie en Veiligheid",
  "register": "ministeries",
  "valid_from": null,
  "valid_to": null,
  "status": "active"
}
```

### 7.3 Municipality Item Structure

```json
{
  "id": "https://identifier.overheid.nl/tooi/id/gemeente/0363",
  "type": "overheid:Gemeente",
  "label": "Utrecht",
  "code": "0363",
  "register": "gemeenten",
  "valid_from": "2021-01-01",
  "valid_to": null,
  "status": "active"
}
```

---

## 8. Implementation Examples

### 8.1 Fetching Organization Data

**Using cURL:**
```bash
# Get JSON-LD representation
curl -H "Accept: application/ld+json" \
  "https://identifier.overheid.nl/tooi/id/ministerie/mnre1058"
```

**Using PHP (Laravel):**
```php
use Illuminate\Support\Facades\Http;

$response = Http::withHeaders([
    'Accept' => 'application/ld+json'
])->get('https://identifier.overheid.nl/tooi/id/ministerie/mnre1058');

$data = $response->json();
```

**Using JavaScript:**
```javascript
const response = await fetch(
  'https://identifier.overheid.nl/tooi/id/ministerie/mnre1058',
  {
    headers: {
      'Accept': 'application/ld+json'
    }
  }
);
const data = await response.json();
```

### 8.2 Loading Value Lists

**From Local JSON File:**
```php
$waardelijsten = json_decode(
    file_get_contents(storage_path('app/tooi_waardelijsten.json')),
    true
);

$gemeenten = $waardelijsten['registers']['gemeenten']['items'];
```

**From JSON File (JavaScript):**
```javascript
const response = await fetch('/storage/tooi_waardelijsten.json');
const data = await response.json();
const gemeenten = data.registers.gemeenten.items;
```

### 8.3 Building Organization Selector

**HTML with Tailwind CSS:**
```html
<label for="organisation" class="block text-label-large font-medium mb-2">
  Organisatie
</label>
<select id="organisation" 
        name="organisation"
        class="w-full px-4 py-3 rounded-lg border-2 border-outline bg-surface
               focus:border-primary focus:outline-2 focus:outline-primary focus:outline-offset-2
               min-h-[44px]">
  <option value="">Selecteer een organisatie</option>
</select>
```

**Populating with JavaScript:**
```javascript
async function populateOrganisations() {
  const response = await fetch('/storage/tooi_waardelijsten.json');
  const data = await response.json();
  const select = document.getElementById('organisation');
  
  // Add ministries
  data.registers.ministeries.items.forEach(org => {
    const option = document.createElement('option');
    option.value = org.id;
    option.textContent = org.label;
    select.appendChild(option);
  });
  
  // Add municipalities
  data.registers.gemeenten.items.forEach(org => {
    const option = document.createElement('option');
    option.value = org.id;
    option.textContent = org.label;
    select.appendChild(option);
  });
}

populateOrganisations();
```

### 8.4 Filtering Documents by Organization

**Laravel Example:**
```php
use App\Models\OpenOverheidDocument;

// Filter by ministry
$ministryId = 'https://identifier.overheid.nl/tooi/id/ministerie/mnre1058';
$documents = OpenOverheidDocument::whereJsonContains(
    'metadata->document->verantwoordelijke->id',
    $ministryId
)->get();
```

### 8.5 Displaying Organization Information

**Blade Template:**
```blade
@php
    $orgId = $document->metadata['document']['verantwoordelijke']['id'] ?? null;
    $orgLabel = $document->metadata['document']['verantwoordelijke']['label'] ?? null;
@endphp

@if($orgId && $orgLabel)
    <div class="mb-4">
        <span class="text-label-medium text-on-surface-variant">Verantwoordelijke:</span>
        <a href="{{ $orgId }}" 
           target="_blank"
           rel="noopener noreferrer"
           class="text-primary hover:underline ml-2">
            {{ $orgLabel }}
        </a>
    </div>
@endif
```

---

## 9. AI Tool Guidelines

### 9.1 Code Generation Rules

When generating code that uses TOOI data, AI tools should:

1. **Always use TOOI URIs** for organization references:
   ```php
   // Good
   $ministryId = 'https://identifier.overheid.nl/tooi/id/ministerie/mnre1058';
   
   // Bad
   $ministryId = 'mnre1058';
   ```

2. **Load from local JSON file** when available:
   ```php
   $data = json_decode(
       file_get_contents(storage_path('app/tooi_waardelijsten.json')),
       true
   );
   ```

3. **Handle organization types correctly**:
   ```php
   // Check organization type
   if (str_contains($orgId, '/ministerie/')) {
       $type = 'ministerie';
   } elseif (str_contains($orgId, '/gemeente/')) {
       $type = 'gemeente';
   }
   ```

4. **Use proper JSON path queries**:
   ```php
   // Laravel JSON query
   ->whereJsonContains('metadata->document->verantwoordelijke->id', $orgId)
   ```

5. **Include organization labels** in UI:
   ```html
   <span>{{ $document->metadata['document']['verantwoordelijke']['label'] }}</span>
   ```

### 9.2 Data Access Patterns

**Pattern 1: Get Organization by ID**
```php
function getOrganizationById(string $orgId, array $waardelijsten): ?array {
    foreach ($waardelijsten['registers'] as $register) {
        foreach ($register['items'] as $item) {
            if ($item['id'] === $orgId) {
                return $item;
            }
        }
    }
    return null;
}
```

**Pattern 2: Get Organizations by Type**
```php
function getOrganizationsByType(string $type, array $waardelijsten): array {
    if (!isset($waardelijsten['registers'][$type])) {
        return [];
    }
    return $waardelijsten['registers'][$type]['items'];
}
```

**Pattern 3: Search Organizations by Label**
```php
function searchOrganizations(string $query, array $waardelijsten): array {
    $results = [];
    foreach ($waardelijsten['registers'] as $register) {
        foreach ($register['items'] as $item) {
            if (stripos($item['label'], $query) !== false) {
                $results[] = $item;
            }
        }
    }
    return $results;
}
```

### 9.3 Validation Checklist

Before finalizing code that uses TOOI data:

- [ ] Uses TOOI URIs (not local IDs)
- [ ] Loads from `tooi_waardelijsten.json` when available
- [ ] Handles missing organization data gracefully
- [ ] Uses proper JSON path queries for metadata
- [ ] Includes organization labels in UI
- [ ] Validates organization IDs before use
- [ ] Handles both active and inactive organizations

---

## Quick Reference

### TOOI Base URI
```
https://identifier.overheid.nl/tooi/
```

### Organization URI Pattern
```
https://identifier.overheid.nl/tooi/id/{type}/{identifier}
```

### Value List URI Pattern
```
https://identifier.overheid.nl/tooi/set/{list_name}
```

### Common Organization Types
- `ministerie` - Ministry
- `gemeente` - Municipality
- `provincie` - Province
- `waterschap` - Water board
- `zbo` - Independent administrative body

### Content Types
- `application/ld+json` - JSON-LD
- `application/rdf+xml` - RDF/XML
- `text/turtle` - Turtle
- `text/html` - HTML (default)

---

## References

- **TOOI 1.4.0-rc**: https://standaarden.overheid.nl/tooi/tooi-1.4.0-rc
- **TOOI Waardelijsten**: https://standaarden.overheid.nl/tooi/waardelijsten/
- **TOOI Downloads**: https://standaarden.overheid.nl/tooi/downloads
- **Identifier Service**: https://identifier.overheid.nl/tooi/

---

## Data File

The complete flattened JSON dataset is available in:
- `storage/app/tooi_waardelijsten.json`

This file contains all government organization registers in a flat, easy-to-use JSON structure.

---

**Last Updated**: Based on TOOI 1.4.0-rc (Release Candidate, published July 8, 2025)

**Note**: TOOI 1.4.0-rc is currently in public consultation (September 5 - October 5, 2025). If no objections are raised, it will become TOOI-1.4.1.

