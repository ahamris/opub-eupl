<?php

namespace App\Console\Commands;

use App\Services\OpenOverheid\OpenOverheidSyncService;
use Illuminate\Console\Command;

class SyncOpenOverheidDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'open-overheid:sync 
                            {--id= : Sync a specific document by external ID}
                            {--week : Sync documents from this week}
                            {--from= : Start date (DD-MM-YYYY format)}
                            {--to= : End date (DD-MM-YYYY format)}
                            {--no-retry : Skip retrying failed documents}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize Open Overheid documents to PostgreSQL';

    /**
     * Execute the console command.
     */
    public function handle(OpenOverheidSyncService $service): int
    {
        $id = $this->option('id');

        if ($id) {
            $this->info("Syncing document: {$id}");
            try {
                $service->syncDocument($id);
                $this->info('✅ Document synced successfully!');

                return self::SUCCESS;
            } catch (\Exception $e) {
                $this->error('Error syncing document: '.$e->getMessage());

                return self::FAILURE;
            }
        }

        // Check for date range options
        $from = $this->option('from');
        $to = $this->option('to');
        $week = $this->option('week');

        if ($week) {
            // Calculate this week's date range (Monday to Sunday)
            $now = now();
            $startOfWeek = $now->copy()->startOfWeek(); // Monday
            $endOfWeek = $now->copy()->endOfWeek(); // Sunday

            $from = $startOfWeek->format('d-m-Y');
            $to = $endOfWeek->format('d-m-Y');

            $this->info("Syncing documents from this week ({$from} to {$to})...");
        } elseif ($from || $to) {
            $this->info('Syncing documents from date range...');
            if ($from) {
                $this->line("  From: {$from}");
            }
            if ($to) {
                $this->line("  To: {$to}");
            }
        } else {
            $this->info('Syncing documents to PostgreSQL...');
        }

        try {
            if ($from || $to || $week) {
                $result = $service->syncByDateRange($from, $to, $this);
            } else {
                $result = $service->syncAll($this);
            }

            if ($result['synced'] > 0 || $result['total'] > 0) {
                $this->newLine();
                $this->info('✅ Sync completed!');
                $this->info("   Total: {$result['total']} documents");
                if (isset($result['created']) && $result['created'] > 0) {
                    $this->info("   Created: {$result['created']} documents");
                }
                if (isset($result['updated']) && $result['updated'] > 0) {
                    $this->info("   Updated: {$result['updated']} documents");
                }
                if (isset($result['skipped']) && $result['skipped'] > 0) {
                    $this->line("   Skipped: {$result['skipped']} documents (already up-to-date)");
                }
                if (isset($result['retried']) && $result['retried'] > 0) {
                    $this->info("   Retried: {$result['retried']} documents");
                }
                if ($result['errors'] > 0) {
                    $this->warn("   Errors: {$result['errors']} documents");
                    $this->line('   Check logs for details: storage/logs/laravel.log');
                }
            } else {
                $this->info('No documents to sync.');
            }

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error during sync: '.$e->getMessage());
            $this->error($e->getTraceAsString());

            return self::FAILURE;
        }
    }
}
