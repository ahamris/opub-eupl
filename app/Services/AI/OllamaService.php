<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaService
{
    protected string $baseUrl;
    protected string $model;
    protected int $cacheTtl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('ollama.base_url', 'http://45.140.140.31:11434'), '/');
        $this->model = config('ollama.model', 'gemma2:9b');
        $this->cacheTtl = config('ollama.cache_ttl', 2592000); // 30 days
    }

    /**
     * Generate an answer to a question based on found documents.
     * Drop-in replacement for GeminiService::answerQuestion()
     */
    public function answerQuestion(string $question, array $documents, int $maxWords = 300): array
    {
        if (empty($documents)) {
            return ['answer' => null, 'sources' => []];
        }

        $relevantDocs = array_slice($documents, 0, 5);
        $context = $this->buildDocumentContext($relevantDocs);
        $sources = $this->buildSourcesArray($relevantDocs);

        $cacheKey = 'ollama:answer:' . md5($question . $context);

        $answer = Cache::remember($cacheKey, $this->cacheTtl, function () use ($question, $context, $maxWords) {
            return $this->generateAnswer($question, $context, $maxWords);
        });

        return [
            'answer' => $answer,
            'sources' => $sources,
        ];
    }

    /**
     * Generate answer using Ollama API
     */
    protected function generateAnswer(string $question, string $context, int $maxWords): ?string
    {
        $systemPrompt = 'Je bent een behulpzame assistent die vragen beantwoordt op basis van Nederlandse overheidsdocumenten. '
            . 'Gebruik ALLEEN informatie uit de gegeven documenten. Verzin GEEN informatie. '
            . 'Verwijs naar bronnen met nummers (document 1, document 2, etc.). '
            . 'Schrijf in begrijpelijk Nederlands (B1-niveau). '
            . "Maximaal {$maxWords} woorden.";

        $prompt = "Vraag: {$question}\n\nRelevante documenten:\n{$context}\n\n"
            . "Geef een concreet antwoord op basis van bovenstaande documenten. "
            . "Verwijs naar de documenten met nummers. Als de informatie niet beschikbaar is, zeg dat duidelijk.";

        try {
            $response = Http::timeout(120)
                ->post("{$this->baseUrl}/api/generate", [
                    'model' => $this->model,
                    'system' => $systemPrompt,
                    'prompt' => $prompt,
                    'stream' => false,
                    'options' => [
                        'temperature' => 0.7,
                        'num_predict' => 1024,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $answer = trim($data['response'] ?? '');
                return ! empty($answer) ? $answer : null;
            }

            Log::warning('Ollama API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Ollama API request failed', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Build document context for the prompt
     */
    protected function buildDocumentContext(array $documents): string
    {
        $context = [];

        foreach ($documents as $index => $doc) {
            $parts = [];
            $parts[] = 'Document ' . ($index + 1) . ':';

            $title = $doc['title'] ?? $doc->title ?? null;
            if ($title) {
                $parts[] = "Titel: {$title}";
            }

            $description = $doc['description'] ?? $doc->description ?? null;
            if ($description) {
                $parts[] = 'Omschrijving: ' . mb_substr($description, 0, 500);
            }

            $content = $doc['content'] ?? $doc->content ?? null;
            if ($content) {
                $parts[] = 'Inhoud: ' . mb_substr($content, 0, 1500);
            }

            $org = $doc['organisation'] ?? $doc->organisation ?? null;
            if ($org) {
                $parts[] = "Organisatie: {$org}";
            }

            $pubDate = $doc['publication_date'] ?? $doc->publication_date ?? null;
            if ($pubDate) {
                if (is_numeric($pubDate)) {
                    $pubDate = date('Y-m-d', $pubDate);
                }
                $parts[] = "Publicatiedatum: {$pubDate}";
            }

            if (count($parts) > 1) {
                $context[] = implode("\n", $parts);
            }
        }

        return implode("\n\n---\n\n", $context);
    }

    /**
     * Build sources array for the frontend
     */
    protected function buildSourcesArray(array $documents): array
    {
        $sources = [];

        foreach ($documents as $index => $doc) {
            $id = $doc['external_id'] ?? $doc['id'] ?? $doc->external_id ?? $doc->id ?? null;

            $source = [
                'number' => (string) ($index + 1),
                'id' => $id,
                'title' => $doc['title'] ?? $doc->title ?? 'Geen titel',
                'url' => '/open-overheid/documents/' . ($id ?? ''),
            ];

            $org = $doc['organisation'] ?? $doc->organisation ?? null;
            if ($org) {
                $source['organisation'] = $org;
            }

            $pubDate = $doc['publication_date'] ?? $doc->publication_date ?? null;
            if ($pubDate) {
                if (is_numeric($pubDate)) {
                    $pubDate = date('Y-m-d', $pubDate);
                }
                $source['publication_date'] = $pubDate;
            }

            $sources[] = $source;
        }

        return $sources;
    }
}
