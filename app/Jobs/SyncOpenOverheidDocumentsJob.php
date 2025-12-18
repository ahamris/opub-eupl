<?php

namespace App\Jobs;

use App\Services\OpenOverheid\OpenOverheidSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

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
