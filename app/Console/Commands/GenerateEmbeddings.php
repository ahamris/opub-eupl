<?php

namespace App\Console\Commands;

use App\Models\OpenOverheidDocument;
use App\Services\AI\EmbeddingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Typesense\Client;

class GenerateEmbeddings extends Command
{
    protected $signature = 'embeddings:generate
                            {--limit=1000 : Max documents to process}
                            {--batch=50 : Batch size for Typesense upserts}
                            {--enriched-only : Only process AI-enriched documents first}
                            {--force : Re-generate embeddings for documents that already have them}
                            {--dry-run : Count eligible documents without processing}';

    protected $description = 'Generate vector embeddings for documents and sync to Typesense';

    protected Client $typesense;

    protected EmbeddingService $embeddings;

    protected string $collection = 'open_overheid_documents';

    public function handle(): int
    {
        if (!config('open_overheid.embeddings.enabled', true)) {
            $this->error('Embeddings are disabled. Set EMBEDDINGS_ENABLED=true in .env');
            return self::FAILURE;
        }

        $this->embeddings = new EmbeddingService();

        $config = config('open_overheid.typesense', []);
        $this->typesense = new Client([
            'api_key' => $config['api_key'] ?? env('TYPESENSE_API_KEY'),
            'nodes' => [[
                'host' => $config['host'] ?? env('TYPESENSE_HOST', 'localhost'),
                'port' => (int) ($config['port'] ?? env('TYPESENSE_PORT', 8108)),
                'protocol' => $config['protocol'] ?? env('TYPESENSE_PROTOCOL', 'http'),
            ]],
            'connection_timeout_seconds' => 5,
        ]);

        $limit = (int) $this->option('limit');
        $batchSize = (int) $this->option('batch');
        $enrichedOnly = $this->option('enriched-only');
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');

        // Build query for documents that need embeddings
        $query = OpenOverheidDocument::query()
            ->whereNotNull('title')
            ->where('title', '!=', '');

        if ($enrichedOnly) {
            $query->whereNotNull('ai_enhanced_at');
        }

        if (!$force) {
            // Only documents not yet embedded — track via a DB column
            $query->whereNull('embedding_generated_at');
        }

        $totalEligible = (clone $query)->count();
        $this->info("Eligible documents: {$totalEligible}");

        if ($dryRun) {
            $this->info("Already embedded: " . OpenOverheidDocument::whereNotNull('embedding_generated_at')->count());
            return self::SUCCESS;
        }

        if ($totalEligible === 0) {
            $this->info('No documents to process.');
            return self::SUCCESS;
        }

        $toProcess = min($limit, $totalEligible);
        $this->info("Processing {$toProcess} documents...");

        $bar = $this->output->createProgressBar($toProcess);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s% %memory:6s%');
        $bar->start();

        $generated = 0;
        $failed = 0;
        $typesenseBatch = [];

        $query->orderByDesc('ai_enhanced_at')
            ->orderByDesc('publication_date')
            ->limit($limit)
            ->chunk($batchSize, function ($documents) use (&$generated, &$failed, &$typesenseBatch, $batchSize, $bar) {
                foreach ($documents as $doc) {
                    $text = $this->embeddings->buildDocumentText(
                        $doc->ai_enhanced_title ?? $doc->title,
                        $doc->description,
                        $doc->ai_summary
                    );

                    $embedding = $this->embeddings->embed($text);

                    if ($embedding) {
                        $typesenseBatch[] = [
                            'id' => (string) $doc->id,
                            'embedding' => $embedding,
                        ];

                        $doc->update(['embedding_generated_at' => now()]);
                        $generated++;
                    } else {
                        $failed++;
                        Log::warning('GenerateEmbeddings: failed', [
                            'id' => $doc->id,
                            'external_id' => $doc->external_id,
                        ]);
                    }

                    $bar->advance();

                    // Flush Typesense batch
                    if (count($typesenseBatch) >= $batchSize) {
                        $this->flushToTypesense($typesenseBatch);
                        $typesenseBatch = [];
                    }
                }
            });

        // Flush remaining
        if (!empty($typesenseBatch)) {
            $this->flushToTypesense($typesenseBatch);
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Generated: {$generated}, Failed: {$failed}");
        $this->info("Total with embeddings: " . OpenOverheidDocument::whereNotNull('embedding_generated_at')->count());

        return self::SUCCESS;
    }

    /**
     * Patch embedding vectors into existing Typesense documents.
     */
    protected function flushToTypesense(array $batch): void
    {
        if (empty($batch)) return;

        $errors = 0;
        foreach ($batch as $item) {
            try {
                $this->typesense->collections[$this->collection]
                    ->documents[(string) $item['id']]
                    ->update(['embedding' => $item['embedding']]);
            } catch (\Exception $e) {
                $errors++;
                if ($errors <= 3) {
                    Log::warning('GenerateEmbeddings: Typesense update failed', [
                        'id' => $item['id'],
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        if ($errors > 0) {
            $this->warn("  {$errors} Typesense update errors in batch of " . count($batch));
        }
    }
}
