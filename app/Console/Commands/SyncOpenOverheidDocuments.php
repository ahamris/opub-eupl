<?php

namespace App\Console\Commands;

use App\Jobs\SyncDocumentToTypesense;
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
                            {--recent : Sync recent documents (default: last 7 days)}
                            {--days=7 : Number of days back (when using --recent)}
                            {--all : Sync ALL documents from all time (default if no options provided)}
                            {--from= : Start date (DD-MM-YYYY format)}
                            {--to= : End date (DD-MM-YYYY format)}
                            {--skip-typesense : Skip immediate Typesense sync}
                            {--id= : Sync a specific document by external ID}
                            {--week : Sync documents from this week}
                            {--no-retry : Skip retrying failed documents}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize Open Overheid documents to PostgreSQL and Typesense';

    /**
     * Execute the console command.
     */
    public function handle(OpenOverheidSyncService $service): int
    {
        $this->info('🚀 Starting Open Overheid sync...');
        $this->newLine();

        $id = $this->option('id');

        if ($id) {
            $this->info("📥 Syncing document: {$id}");
            try {
                $service->syncDocument($id);
                $this->info('✅ Document synced successfully!');
                
                // Dispatch Typesense sync unless skipped
                if (! $this->option('skip-typesense')) {
                    $this->dispatchTypesenseSync();
                }

                return self::SUCCESS;
            } catch (\Exception $e) {
                $this->error('Error syncing document: '.$e->getMessage());

                return self::FAILURE;
            }
        }

        // Check for --all option
        $all = $this->option('all');
        
        // Check for --recent option
        $recent = $this->option('recent');
        $days = (int) $this->option('days');

        // Check for date range options
        $from = $this->option('from');
        $to = $this->option('to');
        $week = $this->option('week');

        // Handle --recent option
        if ($recent) {
            $from = now()->subDays($days)->format('d-m-Y');
            $to = now()->format('d-m-Y');
            $this->info("📥 Step 1: Syncing from API to PostgreSQL...");
            $this->line("   Fetching last {$days} days...");
        } elseif ($week) {
            // Calculate this week's date range (Monday to Sunday)
            $now = now();
            $startOfWeek = $now->copy()->startOfWeek(); // Monday
            $endOfWeek = $now->copy()->endOfWeek(); // Sunday

            $from = $startOfWeek->format('d-m-Y');
            $to = $endOfWeek->format('d-m-Y');

            $this->info("📥 Step 1: Syncing from API to PostgreSQL...");
            $this->line("   Syncing documents from this week ({$from} to {$to})...");
        } elseif ($from || $to) {
            $this->info("📥 Step 1: Syncing from API to PostgreSQL...");
            $this->line('   Syncing documents from date range...');
            if ($from) {
                $this->line("   From: {$from}");
            }
            if ($to) {
                $this->line("   To: {$to}");
            }
        } elseif ($all) {
            $this->info("📥 Step 1: Syncing from API to PostgreSQL...");
            $this->line('   Syncing ALL documents from all time (this may take a while)...');
        } else {
            // Default behavior: sync all documents if no options provided
            $this->info("📥 Step 1: Syncing from API to PostgreSQL...");
            $this->line('   Syncing ALL documents from all time (this may take a while)...');
            $this->line('   💡 Tip: Use --recent --days=7 to sync only recent documents');
        }

        try {
            $result = null;
            
            if ($recent || $from || $to || $week) {
                // Sync by date range
                $result = $service->syncByDateRange($from, $to, $this);
            } else {
                // Default behavior: sync all documents (when no parameters or --all is provided)
                $result = $service->syncAll($this);
            }

            if ($result['synced'] > 0 || $result['total'] > 0) {
                $this->newLine();
                $this->info("   ✓ Synced {$result['synced']} documents to PostgreSQL");
                if (isset($result['created']) && $result['created'] > 0) {
                    $this->line("   Created: {$result['created']} documents");
                }
                if (isset($result['updated']) && $result['updated'] > 0) {
                    $this->line("   Updated: {$result['updated']} documents");
                }
                if (isset($result['skipped']) && $result['skipped'] > 0) {
                    $this->line("   Skipped: {$result['skipped']} documents (already up-to-date)");
                }
                if (isset($result['retried']) && $result['retried'] > 0) {
                    $this->line("   Retried: {$result['retried']} documents");
                }
                if ($result['errors'] > 0) {
                    $this->warn("   Errors: {$result['errors']} documents");
                    $this->line('   Check sync errors log for details: storage/logs/sync-errors.log');
                }
            } else {
                $this->info('   No documents to sync.');
            }

            // Step 2: Typesense sync
            if (! $this->option('skip-typesense')) {
                $this->newLine();
                $this->info("📤 Step 2: Syncing PostgreSQL → Typesense...");
                $this->dispatchTypesenseSync();
            }

            $this->newLine();
            $this->info('✅ Sync completed successfully!');
            
            if (! $this->option('skip-typesense')) {
                $this->newLine();
                $this->line('💡 Tip: Typesense sync runs automatically every minute via scheduler');
                $this->line('   Run: php artisan schedule:work (in development)');
                $this->line('   Or set up cron: * * * * * cd /path && php artisan schedule:run');
            }

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error during sync: '.$e->getMessage());
            $this->error($e->getTraceAsString());

            return self::FAILURE;
        }
    }

    /**
     * Dispatch Typesense sync job
     */
    protected function dispatchTypesenseSync(): void
    {
        SyncDocumentToTypesense::dispatch();
        $this->info('   ✓ Typesense sync job dispatched');
        $this->line('   ℹ️  Scheduled sync runs every minute automatically');
    }
}
