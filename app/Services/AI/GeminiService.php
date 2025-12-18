<?php

namespace App\Services\AI;

use HosseinHezami\LaravelGemini\Facades\Gemini;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $model = 'gemini-2.0-flash-exp';

    protected string $ttsModel = 'gemini-2.5-flash-preview-tts';

    protected int $cacheTtl = 2592000; // 30 days in seconds

    public function __construct()
    {
        $this->model = config('open_overheid.gemini.model', 'gemini-2.0-flash-exp');
        $this->ttsModel = config('open_overheid.gemini.tts_model', 'gemini-2.5-flash-preview-tts');
        $this->cacheTtl = config('open_overheid.gemini.cache_ttl', 2592000);
    }

    /**
     * Generate a summary for a dossier (multiple documents)
     */
    public function summarizeDossier(array $documents, int $maxWords = 500): ?string
    {
        if (empty($documents)) {
            return null;
        }

        // Build content from documents
        $content = $this->buildDocumentContent($documents);

        $cacheKey = 'gemini:dossier_summary:'.md5($content);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($content, $maxWords) {
            $prompt = $this->buildDossierSummaryPrompt($content, $maxWords);

            return $this->generateText($prompt);
        });
    }

    /**
     * Enhance/improve a title to be more understandable (B1 level)
     */
    public function enhanceTitle(string $originalTitle, ?string $context = null): ?string
    {
        if (empty(trim($originalTitle))) {
            return null;
        }

        $cacheKey = 'gemini:enhanced_title:'.md5($originalTitle.($context ?? ''));

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($originalTitle, $context) {
            $prompt = $this->buildTitleEnhancementPrompt($originalTitle, $context);

            return $this->generateText($prompt, 100); // Limit to ~100 tokens for title
        });
    }

    /**
     * Generate an enhanced description (B1 level)
     */
    public function enhanceDescription(string $originalDescription, ?string $content = null): ?string
    {
        if (empty(trim($originalDescription))) {
            return null;
        }

        $cacheKey = 'gemini:enhanced_description:'.md5($originalDescription.($content ?? ''));

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($originalDescription, $content) {
            $prompt = $this->buildDescriptionEnhancementPrompt($originalDescription, $content);

            return $this->generateText($prompt, 300); // Limit to ~300 tokens for description
        });
    }

    /**
     * Extract keywords from text
     */
    public function extractKeywords(string $text, int $maxKeywords = 10): array
    {
        if (empty(trim($text))) {
            return [];
        }

        $cacheKey = 'gemini:keywords:'.md5($text);

        $keywordsJson = Cache::remember($cacheKey, $this->cacheTtl, function () use ($text, $maxKeywords) {
            $prompt = $this->buildKeywordExtractionPrompt($text, $maxKeywords);

            return $this->generateText($prompt, 100);
        });

        if (empty($keywordsJson)) {
            return [];
        }

        // Try to parse as JSON (array of strings)
        $keywords = json_decode($keywordsJson, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($keywords)) {
            return array_slice($keywords, 0, $maxKeywords);
        }

        // Fallback: split by comma/newline
        $keywords = preg_split('/[,;\n]/', $keywordsJson);
        $keywords = array_map('trim', $keywords);
        $keywords = array_filter($keywords, fn ($k) => ! empty($k));

        return array_slice($keywords, 0, $maxKeywords);
    }

    /**
     * Generate audio/podcast from text (TTS)
     * Returns URL to audio file or null if failed
     */
    public function generateAudio(string $text, string $voiceName = 'Kore'): ?string
    {
        if (empty(trim($text))) {
            return null;
        }

        // Limit text length for TTS (max ~5000 characters)
        $text = mb_substr($text, 0, 5000);

        $cacheKey = 'gemini:audio:'.md5($text.$voiceName);

        // Check if audio already exists
        $cachedUrl = Cache::get($cacheKey);
        if ($cachedUrl) {
            return $cachedUrl;
        }

        try {
            // Generate audio using Gemini TTS
            $audioPath = storage_path('app/public/audio/'.uniqid('dossier_', true).'.mp3');

            // Ensure directory exists
            $audioDir = dirname($audioPath);
            if (! is_dir($audioDir)) {
                mkdir($audioDir, 0755, true);
            }

            $response = Gemini::audio()
                ->model($this->ttsModel)
                ->voiceName($voiceName)
                ->prompt($text)
                ->generate();

            // Save audio file
            $response->save($audioPath);

            // Get relative URL path
            $url = '/storage/audio/'.basename($audioPath);

            // Cache the URL
            Cache::put($cacheKey, $url, $this->cacheTtl);

            return $url;
        } catch (\Exception $e) {
            Log::channel('ai_enhancement')->error('Gemini TTS generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Generate text using Gemini API
     */
    protected function generateText(string $prompt, int $maxOutputTokens = 1000): ?string
    {
        $apiKey = config('open_overheid.gemini.api_key', env('GEMINI_API_KEY'));

        if (empty($apiKey)) {
            Log::channel('ai_enhancement')->warning('Gemini API key not configured');

            return null;
        }

        try {
            $response = Gemini::text()
                ->model($this->model)
                ->system('Je bent een assistent die Nederlandse overheidsdocumenten begrijpelijk maakt voor B1-niveau lezers.')
                ->prompt($prompt)
                ->temperature(0.7)
                ->maxTokens($maxOutputTokens)
                ->generate();

            $content = $response->content();

            if (! empty($content)) {
                return trim($content);
            }

            return null;
        } catch (\Exception $e) {
            Log::channel('ai_enhancement')->error('Gemini API request failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Build content string from documents array
     */
    protected function buildDocumentContent(array $documents): string
    {
        $content = [];

        foreach ($documents as $doc) {
            $docContent = [];

            if (! empty($doc->title)) {
                $docContent[] = "Titel: {$doc->title}";
            }

            if (! empty($doc->description)) {
                $docContent[] = 'Omschrijving: '.mb_substr($doc->description, 0, 500);
            }

            if (! empty($doc->content)) {
                $docContent[] = 'Inhoud: '.mb_substr($doc->content, 0, 2000);
            }

            if (! empty($docContent)) {
                $content[] = implode("\n", $docContent);
            }
        }

        return implode("\n\n---\n\n", $content);
    }

    /**
     * Build prompt for dossier summary
     */
    protected function buildDossierSummaryPrompt(string $content, int $maxWords): string
    {
        return <<<PROMPT
Je bent een assistent die Nederlandse overheidsdocumenten samenvat in begrijpelijke taal (B1-niveau).

Vereisten:
- Maximaal {$maxWords} woorden
- Eenvoudige zinnen, geen jargon
- Belangrijkste informatie eerst
- Concrete voorbeelden waar mogelijk
- Geschikt voor mensen met dyslexie
- Schrijf in Nederlands

Documenten:
{$content}

Geef een duidelijke, begrijpelijke samenvatting van dit dossier:
PROMPT;
    }

    /**
     * Build prompt for title enhancement
     */
    protected function buildTitleEnhancementPrompt(string $originalTitle, ?string $context): string
    {
        $contextPart = $context ? "\n\nContext: {$context}" : '';

        return <<<PROMPT
Verbeter deze titel naar een duidelijke, begrijpelijke Nederlandse titel voor B1-niveau lezers.
Maximaal 80 karakters.
Gebruik eenvoudige woorden en vermijd jargon.

Originele titel: {$originalTitle}{$contextPart}

Geef alleen de verbeterde titel terug, zonder uitleg:
PROMPT;
    }

    /**
     * Build prompt for description enhancement
     */
    protected function buildDescriptionEnhancementPrompt(string $originalDescription, ?string $content): string
    {
        $contentPart = $content ? "\n\nExtra context: ".mb_substr($content, 0, 1000) : '';

        return <<<PROMPT
Verbeter deze omschrijving naar begrijpelijke taal (B1-niveau).
Maximaal 200 woorden.
Gebruik eenvoudige zinnen en vermijd jargon.

Originele omschrijving: {$originalDescription}{$contentPart}

Geef alleen de verbeterde omschrijving terug, zonder uitleg:
PROMPT;
    }

    /**
     * Generate an answer to a question based on found documents
     * Returns array with 'answer' and 'sources'
     */
    public function answerQuestion(string $question, array $documents, int $maxWords = 300): array
    {
        if (empty($documents)) {
            return ['answer' => null, 'sources' => []];
        }

        // Build context from documents (limit to top 5 most relevant)
        $relevantDocs = array_slice($documents, 0, 5);
        $context = $this->buildDocumentContextForAnswer($relevantDocs);

        // Build sources array for response
        $sources = $this->buildSourcesArray($relevantDocs);

        $cacheKey = 'gemini:answer:'.md5($question.$context);

        $answer = Cache::remember($cacheKey, $this->cacheTtl, function () use ($question, $context, $maxWords) {
            $prompt = $this->buildAnswerPrompt($question, $context, $maxWords);

            return $this->generateText($prompt, 2000);
        });

        return [
            'answer' => $answer,
            'sources' => $sources,
        ];
    }

    /**
     * Build sources array from documents for display
     */
    protected function buildSourcesArray(array $documents): array
    {
        $sources = [];

        foreach ($documents as $index => $doc) {
            $source = [
                'number' => (string) ($index + 1), // Convert to string for frontend
                'id' => $doc['external_id'] ?? $doc['id'] ?? $doc->external_id ?? $doc->id ?? null,
                'title' => $doc['title'] ?? $doc->title ?? 'Geen titel',
                'url' => '/open-overheid/documents/'.($doc['external_id'] ?? $doc['id'] ?? $doc->external_id ?? $doc->id ?? ''),
            ];

            if (! empty($doc['organisation'] ?? $doc->organisation ?? null)) {
                $source['organisation'] = $doc['organisation'] ?? $doc->organisation;
            }

            if (! empty($doc['publication_date'] ?? $doc->publication_date ?? null)) {
                $pubDate = $doc['publication_date'] ?? $doc->publication_date;
                if (is_numeric($pubDate)) {
                    $pubDate = date('Y-m-d', $pubDate);
                }
                $source['publication_date'] = $pubDate;
            }

            if (! empty($doc['category'] ?? $doc->category ?? null)) {
                $source['category'] = $doc['category'] ?? $doc->category;
            }

            $sources[] = $source;
        }

        return $sources;
    }

    /**
     * Build document context for answer generation (more focused than dossier summary)
     */
    protected function buildDocumentContextForAnswer(array $documents): string
    {
        $context = [];

        foreach ($documents as $index => $doc) {
            $docContext = [];
            $docContext[] = 'Document '.($index + 1).':';

            // Title
            if (! empty($doc['title'] ?? $doc->title ?? null)) {
                $title = $doc['title'] ?? $doc->title;
                $docContext[] = "Titel: {$title}";
            }

            // Description
            if (! empty($doc['description'] ?? $doc->description ?? null)) {
                $description = $doc['description'] ?? $doc->description;
                $docContext[] = 'Omschrijving: '.mb_substr($description, 0, 500);
            }

            // Content - try multiple sources
            $content = null;
            if (! empty($doc['content'] ?? $doc->content ?? null)) {
                $content = $doc['content'] ?? $doc->content;
            } elseif (isset($doc['metadata']) && is_array($doc['metadata'])) {
                // Try to extract content from metadata
                if (isset($doc['metadata']['document']['content'])) {
                    $content = $doc['metadata']['document']['content'];
                } elseif (isset($doc['metadata']['versies'][0]['bestanden'][0]['content'])) {
                    $content = $doc['metadata']['versies'][0]['bestanden'][0]['content'];
                }
            }

            if (! empty($content)) {
                $docContext[] = 'Inhoud: '.mb_substr($content, 0, 1500);
            }

            // Organisation
            if (! empty($doc['organisation'] ?? $doc->organisation ?? null)) {
                $org = $doc['organisation'] ?? $doc->organisation;
                $docContext[] = "Organisatie: {$org}";
            }

            // Publication date
            if (! empty($doc['publication_date'] ?? $doc->publication_date ?? null)) {
                $pubDate = $doc['publication_date'] ?? $doc->publication_date;
                if (is_numeric($pubDate)) {
                    $pubDate = date('Y-m-d', $pubDate);
                }
                $docContext[] = "Publicatiedatum: {$pubDate}";
            }

            // Category
            if (! empty($doc['category'] ?? $doc->category ?? null)) {
                $category = $doc['category'] ?? $doc->category;
                $docContext[] = "Categorie: {$category}";
            }

            if (! empty($docContext) && count($docContext) > 1) {
                $context[] = implode("\n", $docContext);
            }
        }

        return implode("\n\n---\n\n", $context);
    }

    /**
     * Build prompt for answering questions based on documents
     */
    protected function buildAnswerPrompt(string $question, string $context, int $maxWords): string
    {
        return <<<PROMPT
Je bent een behulpzame assistent die vragen beantwoordt op basis van Nederlandse overheidsdocumenten.

BELANGRIJK - CONTEXT & CONTENT FOCUS:
- Gebruik ALLEEN informatie die expliciet in de bovenstaande documenten staat
- Verzin GEEN informatie die niet in de documenten staat (geen hallucinaties)
- Als informatie niet beschikbaar is, zeg dat duidelijk
- Baseer je antwoord op de titel, omschrijving, en inhoud van de documenten
- **VERWIJS ALTIJD NAAR BRONNEN**: Gebruik nummers (1, 2, 3, etc.) om te verwijzen naar de documenten
  Bijvoorbeeld: "Volgens document 1..." of "In document 2 staat..." of "Document 3 vermeldt..."
- **DATA ONTLEDEN EN ANALYSEREN**: 
  * Analyseer en interpreteer de data uit de documenten
  * Geef concrete cijfers, aantallen, percentages, bedragen
  * Vermeld specifieke datums en periodes
  * Vergelijk data tussen documenten als relevant
  * Geef concrete voorbeelden en details uit de documenten
  * Tel en bereken waar nodig (bijvoorbeeld: "In totaal zijn er X incidenten...")

Vraag van de gebruiker: {$question}

Relevante documenten:
{$context}

Instructies:
- Beantwoord de vraag op basis van de informatie in de bovenstaande documenten
- **ONTLEED EN ANALYSEER DE DATA**: 
  * Analyseer cijfers, aantallen, percentages, bedragen uit de documenten
  * Vermeld exacte datums en periodes
  * Tel en bereken waar nodig (bijvoorbeeld totaal aantal, gemiddelden)
  * Vergelijk data tussen documenten als relevant
  * Geef concrete voorbeelden met specifieke details
- Gebruik begrijpelijke taal (B1-niveau) - eenvoudige zinnen, geen jargon
- Maximaal {$maxWords} woorden
- Wees concreet en specifiek met feiten uit de documenten
- Als de informatie niet in de documenten staat, zeg dat duidelijk: "Deze informatie staat niet in de beschikbare documenten."
- **VERWIJS NAAR BRONNEN**: Gebruik altijd nummers (1, 2, 3) om te verwijzen naar de documenten waar je informatie vandaan haalt
- Schrijf in Nederlands
- Geef een direct, nuttig antwoord
- Begin met het belangrijkste antwoord, geef dan details met bronverwijzingen

Voorbeelden van goede data-analyse:
- "Volgens document 1 zijn er in 2024 20 incidenten geregistreerd, terwijl document 2 meldt dat er in 2023 15 incidenten waren. Dit betekent een stijging van 33%."
- "In document 3 staat dat tussen januari en juli 2024 in totaal 45 meldingen zijn gedaan."
- "Document 1 vermeldt dat het budget €2,5 miljoen bedraagt, wat volgens document 2 een stijging is van €500.000 ten opzichte van 2023."

Antwoord:
PROMPT;
    }

    /**
     * Build prompt for keyword extraction
     */
    protected function buildKeywordExtractionPrompt(string $text, int $maxKeywords): string
    {
        $limitedText = mb_substr($text, 0, 3000);

        return <<<PROMPT
Extracteer de {$maxKeywords} belangrijkste Nederlandse keywords uit deze tekst.
Retourneer als JSON array van strings.

Tekst:
{$limitedText}

Geef alleen de JSON array terug, bijvoorbeeld: ["keyword1", "keyword2", "keyword3"]
PROMPT;
    }
}
