<?php

namespace App\Console\Commands;

use App\Models\OpenOverheidDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EnrichDocuments extends Command
{
    protected $signature = 'documents:enrich
                            {--limit=100 : Maximum documents per run}
                            {--recent : Only enrich documents from last 7 days}
                            {--id= : Enrich a specific document by external_id}
                            {--force : Re-enrich already enhanced documents}
                            {--concurrency=4 : Number of parallel Ollama requests}
                            {--offset=0 : Skip first N documents (for parallel runs)}';

    protected $description = 'Enrich documents with AI-generated title, summary, and keywords via Ollama';

    protected string $ollamaUrl;
    protected string $model;

    public function handle(): int
    {
        $this->ollamaUrl = rtrim(config('ollama.base_url', 'http://45.140.140.31:11434'), '/');
        $this->model = config('ollama.model', 'bramvanroy/geitje-7b-ultra:Q4_K_M');

        if ($id = $this->option('id')) {
            return $this->enrichSingle($id);
        }

        $limit = (int) $this->option('limit');
        $concurrency = (int) $this->option('concurrency');
        $offset = (int) $this->option('offset');

        $query = OpenOverheidDocument::query();

        if (! $this->option('force')) {
            $query->whereNull('ai_enhanced_at');
        }
        if ($this->option('recent')) {
            $query->where('synced_at', '>=', now()->subDays(7));
        }

        $query->orderByRaw("CASE WHEN description IS NOT NULL AND description != '' THEN 0 ELSE 1 END")
              ->orderByDesc('publication_date');

        $total = (clone $query)->count();
        $this->info("Total: {$total} | Processing: {$limit} | Concurrency: {$concurrency} | Offset: {$offset}");

        if ($total === 0) {
            $this->info('Nothing to do.');
            return self::SUCCESS;
        }

        $documents = $query->offset($offset)->limit($limit)->get();
        $bar = $this->output->createProgressBar($documents->count());
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s% %memory:6s%');
        $bar->start();

        $enriched = 0;
        $errors = 0;
        $chunks = $documents->chunk($concurrency);

        foreach ($chunks as $chunk) {
            // Fire concurrent requests
            $results = $this->enrichBatch($chunk);

            foreach ($results as $result) {
                if ($result['success']) {
                    $enriched++;
                } else {
                    $errors++;
                }
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done: {$enriched} enriched, {$errors} errors");
        $this->line("Remaining: " . max(0, $total - $offset - $documents->count()));

        return self::SUCCESS;
    }

    /**
     * Enrich a batch of documents concurrently using HTTP pool.
     */
    protected function enrichBatch($documents): array
    {
        $prompts = [];
        $docMap = [];

        foreach ($documents as $doc) {
            $context = $this->buildContext($doc);
            $prompts[$doc->id] = [
                'model' => $this->model,
                'prompt' => $this->buildPrompt($context),
                'system' => 'Je bent een expert in het samenvatten van Nederlandse overheidsdocumenten. Antwoord ALLEEN met valid JSON.',
                'stream' => false,
                'options' => ['temperature' => 0.3, 'num_predict' => 512],
            ];
            $docMap[$doc->id] = $doc;
        }

        // Use Laravel HTTP pool for concurrent requests
        $responses = Http::pool(function ($pool) use ($prompts) {
            foreach ($prompts as $id => $body) {
                $pool->as($id)
                     ->timeout(90)
                     ->post("{$this->ollamaUrl}/api/generate", $body);
            }
        });

        $results = [];

        foreach ($docMap as $id => $doc) {
            try {
                $response = $responses[$id];

                if ($response instanceof \Throwable) {
                    throw $response;
                }

                if (! $response->successful()) {
                    throw new \RuntimeException("HTTP {$response->status()}");
                }

                $text = trim($response->json('response', ''));

                if (preg_match('/\{[\s\S]*\}/m', $text, $match)) {
                    $text = $match[0];
                }

                $data = json_decode($text, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \RuntimeException("Invalid JSON: " . mb_substr($text, 0, 100));
                }

                $doc->update([
                    'ai_enhanced_title' => mb_substr($data['titel'] ?? '', 0, 1000) ?: null,
                    'ai_summary' => $data['samenvatting'] ?? null,
                    'ai_keywords' => ! empty($data['trefwoorden']) ? array_slice($data['trefwoorden'], 0, 10) : null,
                    'ai_enhanced_at' => now(),
                ]);

                $results[] = ['success' => true, 'id' => $doc->external_id];
            } catch (\Exception $e) {
                Log::channel('ai_enhancement')->error('Enrich failed', [
                    'id' => $doc->external_id,
                    'error' => $e->getMessage(),
                ]);
                $results[] = ['success' => false, 'id' => $doc->external_id, 'error' => $e->getMessage()];
            }
        }

        return $results;
    }

    protected function enrichSingle(string $externalId): int
    {
        $doc = OpenOverheidDocument::where('external_id', $externalId)->first();
        if (! $doc) {
            $this->error("Document not found: {$externalId}");
            return self::FAILURE;
        }

        $this->info("Enriching: {$doc->title}");
        $context = $this->buildContext($doc);

        try {
            $response = Http::timeout(60)->post("{$this->ollamaUrl}/api/generate", [
                'model' => $this->model,
                'prompt' => $this->buildPrompt($context),
                'system' => 'Je bent een expert in het samenvatten van Nederlandse overheidsdocumenten. Antwoord ALLEEN met valid JSON.',
                'stream' => false,
                'options' => ['temperature' => 0.3, 'num_predict' => 512],
            ]);

            $text = trim($response->json('response', ''));
            if (preg_match('/\{[\s\S]*\}/m', $text, $match)) {
                $text = $match[0];
            }

            $data = json_decode($text, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException("Invalid JSON");
            }

            $doc->update([
                'ai_enhanced_title' => mb_substr($data['titel'] ?? '', 0, 1000) ?: null,
                'ai_summary' => $data['samenvatting'] ?? null,
                'ai_keywords' => ! empty($data['trefwoorden']) ? array_slice($data['trefwoorden'], 0, 10) : null,
                'ai_enhanced_at' => now(),
            ]);

            $doc->refresh();
            $this->info("ai_enhanced_title: {$doc->ai_enhanced_title}");
            $this->info("ai_summary: " . mb_substr($doc->ai_summary ?? '', 0, 200));
            $this->info("ai_keywords: " . json_encode($doc->ai_keywords));
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed: {$e->getMessage()}");
            return self::FAILURE;
        }
    }

    protected function buildPrompt(string $context): string
    {
        return <<<PROMPT
Analyseer dit Nederlandse overheidsdocument en geef het volgende terug in EXACT dit JSON formaat (geen markdown, alleen pure JSON):

{
  "titel": "Een korte, begrijpelijke titel in gewoon Nederlands (B1-niveau, max 100 tekens)",
  "samenvatting": "Een heldere samenvatting van 2-3 zinnen die uitlegt wat dit document inhoudt en waarom het relevant is voor burgers (max 200 woorden)",
  "trefwoorden": ["trefwoord1", "trefwoord2", "trefwoord3", "trefwoord4", "trefwoord5"]
}

Document:
{$context}

Antwoord alleen met de JSON, geen uitleg of markdown:
PROMPT;
    }

    protected function buildContext(OpenOverheidDocument $doc): string
    {
        $parts = ["Titel: {$doc->title}"];

        if ($doc->organisation) $parts[] = "Organisatie: {$doc->organisation}";
        if ($doc->publication_date) $parts[] = "Publicatiedatum: {$doc->publication_date->format('d-m-Y')}";
        if ($doc->document_type) $parts[] = "Documenttype: {$doc->document_type}";
        if ($doc->category) $parts[] = "Categorie: {$doc->category}";
        if ($doc->theme) $parts[] = "Thema: {$doc->theme}";
        if ($doc->description) $parts[] = "Omschrijving: " . mb_substr($doc->description, 0, 1000);

        return implode("\n", $parts);
    }
}
