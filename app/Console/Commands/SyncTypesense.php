<?php

namespace App\Console\Commands;

use App\Services\Typesense\TypesenseSyncService;
use Illuminate\Console\Command;

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
            $service->syncToTypesense();
            $this->info('Typesense sync completed successfully!');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Typesense sync failed: '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}
