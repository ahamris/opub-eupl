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
                            {--to= : End date (DD-MM-YYYY format)}';

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
                $this->info('Done!');

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
            $this->info('Starting full sync (all documents)...');
        }

        try {
            if ($from || $to || $week) {
                $service->syncByDateRange($from, $to);
            } else {
                $service->syncAll();
            }
            $this->info('Sync completed!');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error during sync: '.$e->getMessage());
            $this->error($e->getTraceAsString());

            return self::FAILURE;
        }
    }
}
