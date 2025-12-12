# Epic: AI-Bevraging met Context & Content Focus

## Overzicht
Dit epic implementeert een betrouwbare AI-chat interface voor het bevragen van overheidsdocumenten met focus op **context behoud**, **content accuracy**, en **transparantie**. Gebruikers kunnen vragen stellen in natuurlijke taal en krijgen AI-gegenereerde antwoorden gebaseerd op gevonden documenten, met volledige bronvermelding en disclaimer.

**Epic Owner**: Development Team  
**Status**: 🚧 In Progress  
**Prioriteit**: High  
**Business Value**: Verhoogt toegankelijkheid van overheidsinformatie, verbetert gebruikerservaring, en zorgt voor transparantie en verifieerbaarheid

---

## Doelstellingen

### Primair
1. **Context Behouden**: AI-antwoorden zijn gebaseerd op daadwerkelijke documenten, geen hallucinaties
2. **Content Accuracy**: Antwoorden zijn accuraat en verifieerbaar via bronnen
3. **Transparantie**: Volledige bronvermelding bij elk antwoord
4. **Betrouwbaarheid**: Duidelijke disclaimer over AI-limitaties

### Secundair
- Verbeterde UX voor natuurlijke taal queries
- Follow-up vragen mogelijkheid
- Feedback mechanisme voor antwoord kwaliteit
- Conversatie geschiedenis

---

## Features Breakdown

### Feature 1: AI-Antwoord Generatie met Context
**Status**: ✅ Completed (Basis)  
**Priority**: P0 (Critical)

#### User Stories
1. **US-1.1**: Als gebruiker wil ik een antwoord krijgen op mijn vraag gebaseerd op echte documenten
   - **Acceptance Criteria**:
     - AI gebruikt alleen informatie uit gevonden documenten
     - Antwoord bevat geen informatie die niet in documenten staat
     - Antwoord is B1-niveau begrijpelijk
     - Maximaal 300 woorden
   - **Story Points**: 5

2. **US-1.2**: Als gebruiker wil ik zien welke documenten gebruikt zijn voor het antwoord
   - **Acceptance Criteria**:
     - Top 5 meest relevante documenten worden gebruikt als context
     - Documenten zijn traceerbaar in het antwoord
     - Gebruikte documenten zijn zichtbaar in UI
   - **Story Points**: 3

3. **US-1.3**: Als systeem wil ik document content volledig gebruiken voor context
   - **Acceptance Criteria**:
     - Titel, beschrijving, en content worden meegenomen
     - Organisatie informatie wordt gebruikt
     - Maximaal 1000 karakters per document content
     - Maximaal 300 karakters per beschrijving
   - **Story Points**: 2

#### Technische Details
- **Service**: `GeminiService::answerQuestion()`
- **Gemini Model**: `gemini-2.0-flash-exp`
- **Context**: Top 5 documenten (titel, beschrijving, content, organisatie)
- **Prompt**: Specifiek voor overheidsdata, B1-niveau, geen hallucinaties
- **Caching**: 30 dagen TTL (cache key: vraag + document context hash)

#### Testen
- ⏳ Unit: `GeminiService::answerQuestion()` test
- ⏳ Integration: Volledige flow met Typesense search + AI generatie
- ⏳ UI: Antwoord display met correcte formatting

---

### Feature 2: Bronvermelding & Transparantie
**Status**: ⏳ Pending  
**Priority**: P0 (Critical)

#### User Stories
1. **US-2.1**: Als gebruiker wil ik een "Bronnen" sectie zien met alle gebruikte documenten
   - **Acceptance Criteria**:
     - Genummerde lijst van bronnen (1, 2, 3, ...)
     - Elke bron is klikbaar en linkt naar document detail
     - Bronnen zijn gesorteerd op relevantie
     - Minimaal 3, maximaal 10 bronnen getoond
   - **Story Points**: 3

2. **US-2.2**: Als gebruiker wil ik zien welke delen van documenten gebruikt zijn
   - **Acceptance Criteria**:
     - Snippets van relevante passages worden getoond
     - Snippets zijn highlightbaar/quote-achtig
     - Link naar volledige document context
   - **Story Points**: 5

3. **US-2.3**: Als gebruiker wil ik een disclaimer zien dat AI fouten kan maken
   - **Acceptance Criteria**:
     - Gele/waarschuwingskleur disclaimer box
     - Tekst: "Open.Overheid.nl kan fouten maken. Controleer altijd de bronnen."
     - Disclaimer is dismissable (X knop)
     - Disclaimer verschijnt bij eerste AI antwoord
   - **Story Points**: 2

#### Technische Details
- **UI Component**: `sources-section.blade.php`
- **Data Structure**: Array van bronnen met `id`, `title`, `url`, `snippet`, `relevance_score`
- **Storage**: Bronnen worden opgeslagen in message object (frontend state)

#### Testen
- ⏳ Unit: Bronnen extractie en formatting
- ⏳ Integration: Bronnen display na AI antwoord
- ⏳ UI: Klikbare bronnen, disclaimer functionaliteit

---

### Feature 3: Zoekresultaten Integratie
**Status**: ✅ Completed (Basis)  
**Priority**: P1 (High)

#### User Stories
1. **US-3.1**: Als gebruiker wil ik zoekresultaten zien onder het AI antwoord
   - **Acceptance Criteria**:
     - Zoekresultaten worden getoond na AI antwoord
     - Resultaten tonen: titel, beschrijving, categorie, organisatie, datum
     - Resultaten zijn klikbaar naar document detail
     - Maximaal 6 resultaten getoond
   - **Story Points**: 3

2. **US-3.2**: Als gebruiker wil ik "Toon alle zoekresultaten" kunnen klikken
   - **Acceptance Criteria**:
     - Link naar zoekresultaten pagina met volledige query
     - Query wordt doorgegeven via URL parameter
     - Pagina toont alle gevonden documenten
   - **Story Points**: 1

#### Technische Details
- **Endpoint**: `POST /api/natural-language-search`
- **Response**: `{ answer, hits, found, sources }`
- **Frontend**: Alpine.js component met message array

#### Testen
- ✅ Integration: Zoekresultaten display werkt
- ⏳ UI: Resultaten formatting en links

---

### Feature 4: Follow-up Vragen & Conversatie
**Status**: ⏳ Pending  
**Priority**: P1 (High)

#### User Stories
1. **US-4.1**: Als gebruiker wil ik follow-up vragen kunnen stellen
   - **Acceptance Criteria**:
     - Input veld blijft beschikbaar na antwoord
     - Nieuwe vraag wordt toegevoegd aan conversatie
     - AI gebruikt vorige context voor betere antwoorden
     - Conversatie geschiedenis is zichtbaar
   - **Story Points**: 5

2. **US-4.2**: Als gebruiker wil ik een "Nieuw gesprek" knop zien
   - **Acceptance Criteria**:
     - Knop reset conversatie geschiedenis
     - Knop is duidelijk zichtbaar
     - Bevestiging bij reset (optioneel)
   - **Story Points**: 2

3. **US-4.3**: Als systeem wil ik context behouden tussen vragen
   - **Acceptance Criteria**:
     - Vorige documenten blijven beschikbaar als context
     - AI kan refereren naar eerdere antwoorden
     - Maximaal 10 berichten in conversatie
   - **Story Points**: 8

#### Technische Details
- **Frontend State**: Alpine.js `messages` array
- **Context Management**: Laatste 5 documenten blijven in context
- **API**: Zelfde endpoint, maar met conversation history

#### Testen
- ⏳ Integration: Follow-up vragen flow
- ⏳ UI: Conversatie geschiedenis display
- ⏳ E2E: Volledige conversatie scenario

---

### Feature 5: Feedback & Kwaliteitsmeting
**Status**: ⏳ Pending  
**Priority**: P2 (Medium)

#### User Stories
1. **US-5.1**: Als gebruiker wil ik feedback kunnen geven op AI antwoorden
   - **Acceptance Criteria**:
     - Thumbs up/down buttons bij elk antwoord
     - Feedback wordt opgeslagen (optioneel: analytics)
     - Visuele feedback na klikken
   - **Story Points**: 3

2. **US-5.2**: Als gebruiker wil ik antwoorden kunnen kopiëren
   - **Acceptance Criteria**:
     - Copy knop bij elk antwoord
     - Antwoord wordt gekopieerd naar clipboard
     - Success feedback (toast/notification)
   - **Story Points**: 2

3. **US-5.3**: Als gebruiker wil ik antwoorden kunnen refreshen
   - **Acceptance Criteria**:
     - Refresh knop genereert nieuw antwoord
     - Nieuwe antwoord kan verschillen van vorige
     - Loading state tijdens refresh
   - **Story Points**: 3

#### Technische Details
- **Storage**: Feedback in localStorage (optioneel: backend)
- **Analytics**: Event tracking voor kwaliteitsmeting
- **API**: `POST /api/chat/feedback` (optioneel)

#### Testen
- ⏳ UI: Feedback buttons functionaliteit
- ⏳ Integration: Copy/refresh functionaliteit

---

## Technische Architectuur

### Backend Services

#### GeminiService
```php
public function answerQuestion(
    string $question, 
    array $documents, 
    int $maxWords = 300
): ?string
```

**Prompt Structuur**:
- System prompt: "Je bent een assistent die vragen beantwoordt op basis van Nederlandse overheidsdocumenten."
- Context: Top 5 documenten (titel, beschrijving, content, organisatie)
- Instructies: 
  - Alleen informatie uit documenten gebruiken
  - Geen hallucinaties
  - B1-niveau taal
  - Maximaal 300 woorden
  - Concreet en specifiek

#### SearchController
```php
public function naturalLanguageSearch(Request $request): JsonResponse
```

**Response Structuur**:
```json
{
  "answer": "AI gegenereerd antwoord...",
  "hits": [...],
  "found": 319,
  "sources": [
    {
      "id": "doc-123",
      "title": "Document titel",
      "url": "/open-overheid/documents/doc-123",
      "relevance_score": 0.95
    }
  ],
  "query": "vraag van gebruiker",
  "search_time_ms": 150
}
```

### Frontend Components

#### Chat Interface
- **Alpine.js Component**: `chatInterface()`
- **State Management**: `messages` array met `{ type, text, answer, results, sources, timestamp }`
- **UI Sections**:
  1. Welcome screen met suggested questions
  2. Chat messages (user + AI)
  3. AI answer (prominent)
  4. Sources section (genummerd, klikbaar)
  5. Search results (document cards)
  6. Input area (follow-up questions)

---

## Content & Context Requirements

### Document Context
Voor overheidsdata is **volledige context** cruciaal:

1. **Titel**: Altijd meenemen (identificatie)
2. **Beschrijving**: Eerste 300 karakters (samenvatting)
3. **Content**: Eerste 1000 karakters (belangrijkste informatie)
4. **Organisatie**: Altijd meenemen (bron autoriteit)
5. **Publicatiedatum**: Voor temporaliteit
6. **Categorie**: Voor classificatie

### AI Prompt Engineering

**Systeem Prompt**:
```
Je bent een behulpzame assistent die vragen beantwoordt op basis van Nederlandse overheidsdocumenten.

BELANGRIJK:
- Gebruik ALLEEN informatie die expliciet in de bovenstaande documenten staat
- Verzin GEEN informatie die niet in de documenten staat
- Als informatie niet beschikbaar is, zeg dat duidelijk
- Verwijs naar de documenten waar je informatie vandaan haalt
- Wees concreet en specifiek
- Gebruik begrijpelijke taal (B1-niveau)
- Maximaal 300 woorden
```

**Context Format**:
```
Document 1:
Titel: [titel]
Omschrijving: [beschrijving - max 300 chars]
Inhoud: [content - max 1000 chars]
Organisatie: [organisatie]

---

Document 2:
...
```

---

## Veiligheid & Betrouwbaarheid

### Disclaimer
**Verplichte disclaimer bij elk AI antwoord**:
> "Open.Overheid.nl kan fouten maken. Controleer altijd de bronnen. Het antwoord op je vraag wordt gegenereerd door AI met als doel om jou te ondersteunen bij het vinden van informatie."

### Validatie
- ✅ AI antwoord wordt alleen getoond als documenten gevonden zijn
- ✅ Bronnen zijn altijd traceerbaar
- ✅ Gebruikers kunnen altijd naar originele documenten
- ⏳ Hallucinatie detectie (toekomstig)

### Error Handling
- **Geen documenten gevonden**: "Ik heb geen documenten gevonden die direct relevant zijn voor je vraag."
- **AI generatie faalt**: Fallback naar alleen zoekresultaten
- **API timeout**: Graceful degradation met alleen zoekresultaten

---

## Testen Strategie

### Unit Tests
- [ ] `GeminiService::answerQuestion()` met verschillende document sets
- [ ] `GeminiService::buildDocumentContextForAnswer()` formatting
- [ ] Context limitatie (max 5 documenten, max karakters)
- [ ] Prompt generation correctheid

### Integration Tests
- [ ] Volledige flow: Search → AI Answer → Response
- [ ] Error handling bij AI failure
- [ ] Caching functionaliteit
- [ ] Bronnen extractie en formatting

### Feature Tests
- [ ] Gebruiker stelt vraag → krijgt antwoord + bronnen
- [ ] Follow-up vraag gebruikt vorige context
- [ ] "Nieuw gesprek" reset conversatie
- [ ] Feedback buttons werken

### Browser Tests (Pest)
- [ ] Chat interface renderen
- [ ] Vraag stellen en antwoord ontvangen
- [ ] Bronnen klikbaar
- [ ] Follow-up vragen
- [ ] Disclaimer dismissable

---

## Metrics & Analytics

### Kwaliteitsmeting
- **Antwoord lengte**: Gemiddeld aantal woorden
- **Bronnen per antwoord**: Gemiddeld aantal gebruikte bronnen
- **Feedback ratio**: Thumbs up vs down
- **Follow-up rate**: Percentage vragen met follow-up
- **Error rate**: Percentage gefaalde AI generaties

### Performance
- **AI generatie tijd**: Gemiddelde response tijd
- **Cache hit rate**: Percentage gecachte antwoorden
- **Search time**: Typesense search performance

---

## Dependencies

### Externe Services
- **Gemini API**: Voor AI antwoord generatie
- **Typesense**: Voor document search
- **PostgreSQL**: Voor document data

### Packages
- `hosseinhezami/laravel-gemini`: Gemini API client
- `typesense/typesense-php`: Typesense client
- Alpine.js: Frontend interactiviteit

---

## Open Issues & Toekomstige Verbeteringen

### Korte Termijn
- [ ] Bronnen sectie implementeren
- [ ] Disclaimer toevoegen
- [ ] Follow-up vragen functionaliteit
- [ ] Feedback mechanisme

### Lange Termijn
- [ ] Conversatie geschiedenis opslaan (backend)
- [ ] Hallucinatie detectie
- [ ] Multi-turn conversatie met context behoud
- [ ] Export conversatie functionaliteit
- [ ] Analytics dashboard voor kwaliteitsmeting

---

## Acceptance Criteria (Epic Level)

✅ **Epic is compleet wanneer**:
1. Gebruikers kunnen vragen stellen in natuurlijke taal
2. AI antwoorden zijn gebaseerd op echte documenten (geen hallucinaties)
3. Alle gebruikte bronnen zijn traceerbaar en klikbaar
4. Disclaimer is zichtbaar bij elk antwoord
5. Gebruikers kunnen follow-up vragen stellen
6. Zoekresultaten zijn geïntegreerd onder antwoorden
7. Feedback mechanisme is beschikbaar
8. Alle tests zijn geschreven en passing

---

**Last Updated**: 2025-01-XX  
**Status**: 🚧 In Progress (Feature 1 & 3 Completed, Features 2, 4, 5 Pending)

