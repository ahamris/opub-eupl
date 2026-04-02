<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmbeddingService
{
    protected string $baseUrl;
    protected string $model;
    protected int $dimensions;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('ai.providers.ollama.url', env('OLLAMA_BASE_URL', 'http://localhost:11434')), '/');
        $this->model = config('open_overheid.embeddings.model', 'nomic-embed-text');
        $this->dimensions = config('open_overheid.embeddings.dimensions', 768);
    }

    /**
     * Generate embedding for a single text.
     *
     * @return float[]|null
     */
    public function embed(string $text): ?array
    {
        $text = $this->prepareText($text);

        if (empty($text)) {
            return null;
        }

        try {
            $response = Http::timeout(30)
                ->post("{$this->baseUrl}/api/embeddings", [
                    'model' => $this->model,
                    'prompt' => $text,
                ]);

            if ($response->successful()) {
                $embedding = $response->json('embedding');
                if (is_array($embedding) && count($embedding) === $this->dimensions) {
                    return $embedding;
                }
            }

            Log::warning('EmbeddingService: unexpected response', [
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('EmbeddingService: failed to generate embedding', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Generate embeddings for multiple texts (sequential, Ollama doesn't batch).
     *
     * @param  string[]  $texts
     * @return array<int, float[]|null>
     */
    public function embedBatch(array $texts): array
    {
        $results = [];
        foreach ($texts as $i => $text) {
            $results[$i] = $this->embed($text);
        }
        return $results;
    }

    /**
     * Build the text to embed from a document's fields.
     * Combines title + description for a rich semantic representation.
     */
    public function buildDocumentText(string $title, ?string $description = null, ?string $aiSummary = null): string
    {
        $parts = array_filter([
            $title,
            $aiSummary ?: $description,
        ]);

        return $this->prepareText(implode('. ', $parts));
    }

    /**
     * Get the embedding dimensions for this model.
     */
    public function getDimensions(): int
    {
        return $this->dimensions;
    }

    /**
     * Truncate and clean text for embedding.
     */
    protected function prepareText(string $text): string
    {
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        // nomic-embed-text handles ~8192 tokens; truncate at ~6000 chars to be safe
        if (mb_strlen($text) > 6000) {
            $text = mb_substr($text, 0, 6000);
        }

        return $text;
    }
}
