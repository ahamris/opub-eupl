<?php

namespace App\Jobs;

use App\Services\Typesense\TypesenseSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * ARCHIVED JOB - DISABLED
 * 
 * This job has been moved to the Archived folder and its scheduled execution
 * has been disabled due to high load on the main application.
 * 
 * This job will be moved to a separate service/project in the future.
 * 
 * To re-enable this job, see README.md in app/Console/Archived/ folder.
 */
class SyncDocumentToTypesense implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(TypesenseSyncService $service): void
    {
        try {
            Log::channel('typesense_errors')->info('Typesense sync job started');
            
            // Process up to 100 documents per run
            $service->syncPending(100);
            
            Log::channel('typesense_errors')->info('Typesense sync job completed');
        } catch (\Exception $e) {
            Log::channel('typesense_errors')->error('Typesense sync job failed', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e; // Re-throw to mark job as failed
        }
    }
}
