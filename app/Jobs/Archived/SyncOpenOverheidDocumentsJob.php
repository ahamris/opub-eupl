<?php

namespace App\Jobs\Archived;

use App\Services\OpenOverheid\OpenOverheidSyncService;
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
class SyncOpenOverheidDocumentsJob implements ShouldQueue
{
    use Queueable;

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
    public function handle(OpenOverheidSyncService $service): void
    {
        try {
            Log::info('Open Overheid sync job started');
            $service->syncAll();
            Log::info('Open Overheid sync job completed');
        } catch (\Exception $e) {
            Log::channel('sync_errors')->error('Open Overheid sync job failed', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e; // Re-throw to mark job as failed
        }
    }
}
