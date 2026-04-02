<?php

namespace App\Console\Commands;

use App\Jobs\SyncDocumentToTypesense;
use App\Services\Raadsinformatie\RaadsinformatieSyncService;
use Illuminate\Console\Command;

class SyncRaadsinformatie extends Command
{
    protected $signature = 'ori:sync
                            {--recent : Sync recent documents (default: last 7 days)}
                            {--days=7 : Number of days back (when using --recent)}
                            {--all : Sync all available documents}
                            {--from= : Start date (ISO 8601 format, e.g. 2024-01-01)}
                            {--to= : End date (ISO 8601 format, e.g. 2024-12-31)}
                            {--index= : Specific ORI index pattern (e.g. ori_amsterdam*)}
                            {--skip-typesense : Skip Typesense sync after import}
                            {--list-indices : List all available ORI indices}';

    protected $description = 'Synchronize Open Raadsinformatie documents to PostgreSQL and Typesense';

    public function handle(RaadsinformatieSyncService $syncService): int
    {
        // Handle --list-indices
        if ($this->option('list-indices')) {
            return $this->listIndices();
        }

        $this->info('Starting Open Raadsinformatie (ORI) sync...');
        $this->newLine();

        try {
            $result = $this->runSync($syncService);
        } catch (\Exception $e) {
            $this->error('Error during ORI sync: ' . $e->getMessage());
            $this->error($e->getTraceAsString());

            return self::FAILURE;
        }

        // Display results
        $this->displayResults($result);

        // Typesense sync
        if (! $this->option('skip-typesense') && $result['synced'] > 0) {
            $this->newLine();
            $this->info('Dispatching Typesense sync...');
            SyncDocumentToTypesense::dispatch();
            $this->info('   Typesense sync job dispatched.');
        }

        $this->newLine();
        $this->info('ORI sync completed!');

        return self::SUCCESS;
    }

    protected function runSync(RaadsinformatieSyncService $syncService): array
    {
        $index = $this->option('index');

        if ($index) {
            $this->line("   Index pattern: {$index}");

            return $syncService->syncByIndex($index, $this);
        }

        if ($this->option('recent')) {
            $days = (int) $this->option('days');
            $this->line("   Syncing last {$days} days...");

            return $syncService->syncRecent($days, $this);
        }

        $from = $this->option('from');
        $to = $this->option('to');

        if ($from || $to) {
            $this->line('   Syncing date range...');
            if ($from) {
                $this->line("   From: {$from}");
            }
            if ($to) {
                $this->line("   To: {$to}");
            }

            return $syncService->syncByDateRange($from, $to, $this);
        }

        // Default: sync all
        $this->line('   Syncing all available documents...');
        $this->line('   Note: Elasticsearch limits results to 10,000 per query.');
        $this->line('   Use --index=ori_gemeente* to sync specific municipalities.');

        return $syncService->syncAll($this);
    }

    protected function displayResults(array $result): void
    {
        $this->newLine();
        $this->info("   Total found: {$result['total']}");
        $this->info("   Synced: {$result['synced']}");

        if (isset($result['created']) && $result['created'] > 0) {
            $this->line("   Created: {$result['created']}");
        }
        if (isset($result['updated']) && $result['updated'] > 0) {
            $this->line("   Updated: {$result['updated']}");
        }
        if (isset($result['skipped']) && $result['skipped'] > 0) {
            $this->line("   Skipped: {$result['skipped']} (already up-to-date)");
        }
        if ($result['errors'] > 0) {
            $this->warn("   Errors: {$result['errors']}");
            $this->line('   Check: storage/logs/sync-errors.log');
        }
    }

    protected function listIndices(): int
    {
        $this->info('Fetching available ORI indices...');

        try {
            $searchService = app(\App\Services\Raadsinformatie\RaadsinformatieSearchService::class);
            $indices = $searchService->listIndices();

            if (empty($indices)) {
                $this->warn('No indices found.');

                return self::SUCCESS;
            }

            $headers = ['Index', 'Health', 'Status', 'Docs Count', 'Size'];
            $rows = [];

            foreach ($indices as $index) {
                $name = $index['index'] ?? '';
                if (! str_starts_with($name, 'ori_')) {
                    continue;
                }
                $rows[] = [
                    $name,
                    $index['health'] ?? '-',
                    $index['status'] ?? '-',
                    $index['docs.count'] ?? '-',
                    $index['store.size'] ?? '-',
                ];
            }

            usort($rows, fn ($a, $b) => $a[0] <=> $b[0]);

            $this->table($headers, $rows);
            $this->info('Total ORI indices: ' . count($rows));

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error listing indices: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
