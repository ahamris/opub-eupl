<?php

namespace App\Console\Commands;

use App\Jobs\PrecomputeDossierMetadataJob;
use App\Models\OpenOverheidDocument;
use Illuminate\Console\Command;

class PrecomputeAllDossiersMetadata extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dossiers:precompute-metadata 
                            {--batch-size=100 : Number of dossiers to process per batch}
                            {--limit= : Limit number of dossiers to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre-compute metadata for all dossiers in background jobs';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting dossier metadata pre-computation...');

        // Get all dossier external IDs
        $dossierIds = OpenOverheidDocument::inDossier()
            ->pluck('external_id');

        if ($dossierIds->isEmpty()) {
            $this->warn('No dossiers found.');

            return self::FAILURE;
        }

        $total = $dossierIds->count();
        $limit = $this->option('limit') ? (int) $this->option('limit') : $total;
        $batchSize = (int) $this->option('batch-size');

        $this->info("Found {$total} dossiers. Processing {$limit}...");

        // Process in batches
        $dossierIds->take($limit)->chunk($batchSize)->each(function ($batch, $index) {
            $this->info('Dispatching batch '.($index + 1)." ({$batch->count()} dossiers)...");

            foreach ($batch as $externalId) {
                PrecomputeDossierMetadataJob::dispatch($externalId);
            }

            $this->info('Batch '.($index + 1).' dispatched.');
        });

        $this->info("✅ Dispatched {$limit} jobs to pre-compute dossier metadata.");
        $this->info("Run 'php artisan queue:work' to process the jobs.");

        return self::SUCCESS;
    }
}
