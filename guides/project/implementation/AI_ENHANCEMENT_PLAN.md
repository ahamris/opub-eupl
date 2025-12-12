# AI Enhancement Plan: Typesense & Gemini Integration

## Samenvatting

Dit plan beschrijft de optimalisatie van search (altijd Typesense) en de integratie van Google Gemini API voor dossier verrijking, samenvattingen en audio generatie.

## 1. Search Optimalisatie: Altijd Typesense

### Huidige Situatie
- Search gebruikt PostgreSQL als primaire methode
- Typesense wordt alleen gebruikt voor neuro search
- Fallback naar PostgreSQL als Typesense faalt

### Nieuwe Strategie
- **Primair**: Typesense voor alle searches (sneller, betere typo tolerance, faceting)
- **Fallback**: PostgreSQL als Typesense niet beschikbaar is
- **Reden**: Typesense is geoptimaliseerd voor search, PostgreSQL is beter voor relationele data

### Implementatie
1. Update `SearchController` om altijd Typesense te proberen eerst
2. PostgreSQL als fallback bij Typesense failures
3. Voeg caching toe voor veelgebruikte queries

---

## 2. Gemini API Integratie: Dossier Verrijking

### Functionaliteiten

#### 2.1 Dossier Samenvatting
- **Trigger**: Op aanvraag (button "Maak samenvatting") of automatisch bij eerste access
- **Input**: Alle documenten in een dossier
- **Output**: 
  - B1 Nederlands samenvatting (max 500 woorden)
  - Verbeterde titel voor dossier
  - Kernpunten/keywords
- **Opslag**: Nieuwe kolommen in database of aparte tabel

#### 2.2 Document Context Verbetering
- **Titel optimalisatie**: Verbeter titels naar begrijpelijke Nederlandse titels
- **Omschrijving generatie**: B1-niveau omschrijvingen van documenten
- **Metadata extractie**: Belangrijke data punten extraheren

#### 2.3 Audio/Podcast Generatie
- **Voor dyslectische gebruikers**: Text-to-Speech (TTS) van samenvatting
- **Formaat**: MP3 of WebM
- **Opslag**: Storage met CDN URL
- **Duur**: Max 5 minuten (korte podcast-stijl)

### Database Schema Uitbreiding

```sql
-- Nieuwe kolommen voor open_overheid_documents tabel
ALTER TABLE open_overheid_documents ADD COLUMN ai_enhanced_title TEXT;
ALTER TABLE open_overheid_documents ADD COLUMN ai_enhanced_description TEXT;
ALTER TABLE open_overheid_documents ADD COLUMN ai_summary TEXT;
ALTER TABLE open_overheid_documents ADD COLUMN ai_keywords JSONB;
ALTER TABLE open_overheid_documents ADD COLUMN ai_enhanced_at TIMESTAMP;

-- Nieuwe tabel voor dossier-level AI content
CREATE TABLE dossier_ai_content (
    id BIGSERIAL PRIMARY KEY,
    dossier_external_id VARCHAR(255) NOT NULL,
    summary TEXT,
    enhanced_title TEXT,
    keywords JSONB,
    audio_url TEXT,
    audio_duration_seconds INTEGER,
    generated_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW(),
    UNIQUE(dossier_external_id)
);

CREATE INDEX idx_dossier_ai_content_dossier_id ON dossier_ai_content(dossier_external_id);
```

---

## 3. Gemini API Best Practices & Optimalisatie

### 3.1 Model Keuze
- **Voor samenvattingen**: `gemini-2.0-flash-exp` of `gemini-2.5-flash-lite` (snel, goedkoop)
- **Voor complexe taken**: `gemini-2.5-pro` (beter begrip, duurder)
- **Voor TTS**: Gemini TTS API (`gemini-2.5-flash-tts` of `gemini-2.5-pro-tts`)

### 3.2 Prompt Engineering
```python
# Voor dossier samenvatting
system_prompt = """
Je bent een assistent die Nederlandse overheidsdocumenten samenvat in begrijpelijke taal (B1-niveau).
Vereisten:
- Maximaal 500 woorden
- Eenvoudige zinnen, geen jargon
- Belangrijkste informatie eerst
- Concrete voorbeelden waar mogelijk
- Geschikt voor mensen met dyslexie
"""

# Voor titel optimalisatie
title_prompt = """
Verbeter deze titel naar een duidelijke, begrijpelijke Nederlandse titel voor B1-niveau lezers.
Maximaal 80 karakters.
Origineel: {original_title}
Context: {context}
"""

# Voor TTS/podcast
podcast_prompt = """
Schrijf een korte podcast introductie (max 2 minuten leestijd) voor dit dossier.
Gebruik een vriendelijke, toegankelijke toon.
Begin met: "Welkom bij deze podcast over..."
"""
```

### 3.3 Caching Strategie
- **Cache AI responses**: 30 dagen (content verandert niet vaak)
- **Cache keys**: `ai_content:dossier:{external_id}`, `ai_title:{document_id}`
- **Invalidatie**: Alleen bij nieuwe documenten in dossier

### 3.4 Rate Limiting & Kosten Optimalisatie
- **Queue jobs**: AI generatie in background (Laravel Queue)
- **Batch processing**: Verwerk meerdere dossiers in één batch
- **Smart caching**: Check cache voordat je API aanroept
- **Token optimalisatie**: Trim content voor API calls (eerste 5000 woorden per document)

### 3.5 Error Handling
- **Retry logic**: 3 pogingen met exponential backoff
- **Fallback**: Toon originele titel/omschrijving als AI faalt
- **Monitoring**: Log alle API calls en response times

---

## 4. Architectuur

### 4.1 Service Classes

```php
// app/Services/AI/GeminiService.php
class GeminiService {
    public function summarizeDossier(array $documents): array
    public function enhanceTitle(string $originalTitle, ?string $context = null): string
    public function enhanceDescription(string $originalDescription, ?string $content = null): string
    public function generateAudio(string $text): string // Returns audio URL
    public function extractKeywords(string $text): array
}

// app/Services/AI/DossierEnhancementService.php
class DossierEnhancementService {
    public function enhanceDossier(string $dossierExternalId): void
    public function getOrGenerateSummary(string $dossierExternalId): ?string
    public function getOrGenerateAudio(string $dossierExternalId): ?string
}
```

### 4.2 Jobs
```php
// app/Jobs/EnhanceDossierJob.php
// Queue job voor background AI processing

// app/Jobs/GenerateDossierAudioJob.php
// Queue job voor audio generatie (kan lang duren)
```

### 4.3 Controllers
```php
// app/Http/Controllers/OpenOverheid/DossierController.php
// Uitbreiden met:
public function enhance(string $id) // Trigger enhancement
public function getSummary(string $id) // Get/return summary
public function getAudio(string $id) // Get audio URL
```

---

## 5. UI/UX Features

### 5.1 Dossier Detail Pagina
- **Button**: "Maak AI-samenvatting" (alleen als niet beschikbaar)
- **Section**: Toon samenvatting wanneer beschikbaar
- **Audio player**: Inline player voor podcast/samenvatting
- **Loading states**: Toon progress tijdens generatie

### 5.2 Search Resultaten
- **Badge**: "AI-verbeterd" op documenten met AI-enhanced content
- **Tooltip**: "Deze titel is door AI verbeterd voor betere leesbaarheid"

### 5.3 Admin/Backend
- **Command**: `php artisan ai:enhance-dossiers` (batch enhancement)
- **Command**: `php artisan ai:regenerate-failed` (retry failed enhancements)

---

## 6. Implementatie Volgorde

### Fase 1: Search Optimalisatie (Week 1)
1. ✅ Update SearchController om altijd Typesense te gebruiken
2. ✅ PostgreSQL fallback implementeren
3. ✅ Caching toevoegen voor veelgebruikte queries
4. ✅ Testing & performance monitoring

### Fase 2: Gemini Service Setup (Week 1-2)
1. ✅ Install Google Gemini PHP SDK of gebruik HTTP client
2. ✅ Create GeminiService class met basis methods
3. ✅ Config toevoegen voor API key en model settings
4. ✅ Error handling en retry logic

### Fase 3: Database & Models (Week 2)
1. ✅ Migration voor nieuwe kolommen
2. ✅ Migration voor dossier_ai_content tabel
3. ✅ Model updates (fillable, casts)
4. ✅ Relationships

### Fase 4: Dossier Enhancement (Week 2-3)
1. ✅ DossierEnhancementService implementeren
2. ✅ Queue jobs maken
3. ✅ Background processing setup
4. ✅ Cache layer implementeren

### Fase 5: UI Integration (Week 3)
1. ✅ Buttons en UI elements
2. ✅ API endpoints voor AJAX calls
3. ✅ Audio player component
4. ✅ Loading states en feedback

### Fase 6: Audio Generation (Week 4)
1. ✅ Gemini TTS API integratie
2. ✅ Audio storage setup (S3/local)
3. ✅ Audio player in UI
4. ✅ Streaming voor grote bestanden

### Fase 7: Testing & Optimization (Week 4-5)
1. ✅ Unit tests voor services
2. ✅ Feature tests voor UI
3. ✅ Performance testing
4. ✅ Cost optimization review
5. ✅ User acceptance testing

---

## 7. Kosten Schatting (Gemini API)

### Prijzen (per 1M tokens)
- **Gemini 2.0 Flash**: ~$0.075 input, ~$0.30 output
- **Gemini 2.5 Pro**: ~$1.25 input, ~$5.00 output
- **Gemini TTS**: ~$0.015 per 1000 characters

### Schatting per dossier
- **Samenvatting**: ~3000 tokens input, ~500 tokens output = ~$0.0005 per dossier
- **Audio (TTS)**: ~2000 characters = ~$0.00003 per dossier
- **Totaal**: ~$0.0005 per dossier enhancement

**Bij 10,000 dossiers**: ~$5 per volledige enhancement run
**Maandelijks (alleen nieuwe)**: ~$0.50-1.00

---

## 8. Monitoring & Analytics

### Metrics
- API call counts
- Response times
- Error rates
- Cache hit rates
- Cost per dossier
- User engagement (button clicks, audio plays)

### Tools
- Laravel Log voor errors
- Custom metrics dashboard
- Google Cloud Monitoring (als gebruikt)
- Queue monitoring (Horizon of custom)

---

## 9. Security & Privacy

### Data Handling
- **Geen PII in prompts**: Strip persoonlijke informatie voor API calls
- **API key security**: Environment variables, no hardcoding
- **Rate limiting**: Per-user limits om abuse te voorkomen
- **Audit logging**: Log alle AI-generated content

### Compliance
- **GDPR**: AI-generated content is geen persoonsgegevens
- **Transparantie**: Duidelijk labelen dat content AI-gegenereerd is
- **Rechten**: Gebruikers kunnen verzoeken om AI-content te verwijderen

---

## 10. Future Enhancements

### Mogelijke uitbreidingen
- **Multi-language support**: Engels, Fries, etc.
- **Video summaries**: Korte video's met AI voice-over
- **Interactive Q&A**: Chat met AI over dossier inhoud
- **Translation**: Vertaal documenten naar andere talen
- **Semantic search**: Gebruik embeddings voor betere search
- **Auto-tagging**: AI-genereerde tags voor betere categorisatie

---

## 11. Resources

### Documentation
- [Google Gemini API Docs](https://ai.google.dev/docs)
- [Typesense Natural Language Search](https://typesense.org/docs/guide/natural-language-search.html)
- [Laravel Queues](https://laravel.com/docs/queues)
- [Laravel Cache](https://laravel.com/docs/cache)

### Libraries
- Google Gemini PHP SDK (of HTTP client)
- Laravel Horizon (queue monitoring)
- Laravel Telescope (debugging)

---

## Aanpassingen & Feedback

Dit plan is een levend document. Pas aan op basis van:
- Gebruikersfeedback
- Performance metrics
- Kosten analyses
- Nieuwe Gemini API features
