<?php

use App\Jobs\SyncDocumentToTypesense;
use App\Jobs\SyncOpenOverheidDocumentsJob;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


// Schedule Open Overheid sync command to run daily at 2 AM
// Syncs last 7 days by default
Schedule::command('open-overheid:sync --recent --days=7')
    ->dailyAt('02:00')
    ->name('sync-open-overheid-documents')
    ->withoutOverlapping()
    ->onFailure(function (\Illuminate\Console\Scheduling\Event $event) {
        \Log::channel('sync_errors')->error('Open Overheid sync command failed', [
            'command' => $event->command,
            'exit_code' => $event->exitCode,
            'output' => $event->output ?? 'No output captured',
        ]);
    })
    ->appendOutputTo(storage_path('logs/sync-command-output.log'));

// Alternative: Use job instead of command (syncs ALL documents)
// Uncomment if you prefer to sync all documents instead of recent ones
// Schedule::job(new SyncOpenOverheidDocumentsJob)
//     ->dailyAt('02:00')
//     ->name('sync-open-overheid-documents')
//     ->withoutOverlapping()
//     ->onFailure(function () {
//         \Log::channel('sync_errors')->error('Open Overheid sync job failed');
//     });

// Schedule Typesense sync every minute
Schedule::command('typesense:sync')
    ->everyMinute()
    ->name('sync-document-to-typesense')
    ->withoutOverlapping()
    ->onFailure(function (\Illuminate\Console\Scheduling\Event $event) {
        \Log::channel('typesense_errors')->error('Typesense sync command failed', [
            'command' => $event->command,
            'exit_code' => $event->exitCode,
        ]);
    });
