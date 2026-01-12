<?php

namespace App\Console\Archived;

use App\Services\Typesense\TypesenseSyncService;
use Illuminate\Console\Command;

/**
 * ARCHIVED COMMAND - DISABLED
 * 
 * This command has been moved to the Archived folder and its scheduled execution
 * has been disabled due to high load on the main application.
 * 
 * This command will be moved to a separate service/project in the future.
 * 
 * To re-enable this command, see README.md in this folder.
 */
class SyncTypesense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'typesense:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync PostgreSQL documents to Typesense';

    /**
     * Execute the console command.
     */
    public function handle(TypesenseSyncService $service): int
    {
        $this->info('Syncing documents to Typesense...');

        try {
            $result = $service->syncToTypesense($this);

            if ($result['synced'] > 0) {
                $this->newLine();
                $this->info('✅ Sync completed!');
                $this->info("   Synced: {$result['synced']} documents");
                if ($result['errors'] > 0) {
                    $this->warn("   Errors: {$result['errors']} documents");
                }
            } else {
                $this->info('No documents to sync.');
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Typesense sync failed: '.$e->getMessage());
            $this->error($e->getTraceAsString());

            return Command::FAILURE;
        }
    }
}
