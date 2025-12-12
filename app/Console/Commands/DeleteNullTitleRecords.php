<?php

namespace App\Console\Commands;

use App\Models\OpenOverheidDocument;
use Illuminate\Console\Command;

class DeleteNullTitleRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-null-title-records {--force : Force deletion without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all records from open_overheid_documents table where title is null';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = OpenOverheidDocument::whereNull('title')->count();

        if ($count === 0) {
            $this->info('No records found with null title.');

            return Command::SUCCESS;
        }

        $this->info("Found {$count} record(s) with null title.");

        if ($this->option('force') || $this->confirm('Do you want to delete these records?', true)) {
            $deleted = OpenOverheidDocument::whereNull('title')->delete();
            $this->info("Successfully deleted {$deleted} record(s).");

            return Command::SUCCESS;
        }

        $this->info('Deletion cancelled.');

        return Command::SUCCESS;
    }
}
