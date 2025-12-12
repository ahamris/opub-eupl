# Dossier Filters & Metadata Plan

## Probleem
1. SQL error: `DISTINCT` met `ORDER BY` op niet-SELECT kolommen
2. Filters zijn niet dossier-specifiek
3. AI-content wordt niet prominent getoond
4. Status (actief/gesloten) ontbreekt
5. Aantal documenten per dossier niet zichtbaar

## Doel: Dossier-specifieke metadata en filters

### 1. Metadata die we willen tonen:
- **Organisatie** ✅ (bestaat al)
- **Status** (actief/gesloten) - nieuw, berekenen op basis van laatste publicatiedatum
- **Aantal documenten** - berekenen per dossier
- **Title** - AI-geoptimaliseerd tonen (bestaat in `dossier_ai_content`)
- **Omschrijving** - AI-geoptimaliseerd tonen (summary uit `dossier_ai_content`)
- **Audio indicator** - tonen wanneer beschikbaar

### 2. Filters voor dossiers:
- **Organisatie** ✅ (behouden)
- **Status** (actief/gesloten) - nieuw
- **Informatiecategorie** ✅ (behouden, relevant)
- **Publicatiedatum** ✅ (behouden)
- **Aantal documenten** (range filter: 1-5, 6-10, 11-20, 21+) - nieuw
- **Documentsoort** ❌ (verwijderen - minder relevant op dossier niveau)
- **Thema** ✅ (behouden, relevant)

### 3. Implementatie stappen:

#### Stap 1: Fix SQL error
- Verwijder `orderBy` voor `distinct()` queries
- Sorteer in PHP na `pluck()`

#### Stap 2: Bereken dossier metadata
- Status: "actief" als laatste document < 2 jaar geleden, anders "gesloten"
- Aantal documenten: bereken via `getDossierMembers()` count (efficiënt)

#### Stap 3: AI-content tonen in lijst
- Toon AI-enhanced title met sparkle icon ✨
- Toon AI summary als description (fallback naar normale description)
- Toon audio badge wanneer beschikbaar

#### Stap 4: Filter sidebar aanpassen
- Voeg status filter toe (Actief/Gesloten)
- Voeg aantal documenten filter toe (ranges)
- Verwijder documentsoort filter (minder relevant)
- Behoud organisatie, informatiecategorie, thema, datum

#### Stap 5: Performance optimalisatie
- Cache dossier member counts
- Bereken status in één query waar mogelijk
- Gebruik eager loading voor AI-content

## Prioriteit
1. Fix SQL error (kritiek)
2. AI-content tonen (core feature)
3. Status filter toevoegen
4. Aantal documenten filter
5. Performance optimalisatie
