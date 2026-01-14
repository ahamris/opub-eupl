<?php

// DISABLED: Jobs moved to Archived folder
// use App\Jobs\SyncDocumentToTypesense;
// use App\Jobs\SyncOpenOverheidDocumentsJob;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


// DISABLED: Open Overheid sync command moved to Archived folder
// This command was causing high load on the main application.
// See: app/Console/Archived/README.md for re-enabling instructions
//
// Schedule Open Overheid sync command to run daily at 2 AM
// Syncs last 7 days by default
// Schedule::command('open-overheid:sync --recent --days=7')
//     ->dailyAt('02:00')
//     ->name('sync-open-overheid-documents')
//     ->withoutOverlapping()
//     ->onFailure(function (\Illuminate\Console\Scheduling\Event $event) {
//         \Log::channel('sync_errors')->error('Open Overheid sync command failed', [
//             'command' => $event->command,
//             'exit_code' => $event->exitCode,
//             'output' => $event->output ?? 'No output captured',
//         ]);
//     })
//     ->appendOutputTo(storage_path('logs/sync-command-output.log'));

// DISABLED: Job moved to Archived folder
// Alternative: Use job instead of command (syncs ALL documents)
// Uncomment if you prefer to sync all documents instead of recent ones
// Note: You'll need to move the job back from app/Jobs/Archived/ first
// Schedule::job(new \App\Jobs\SyncOpenOverheidDocumentsJob)
//     ->dailyAt('02:00')
//     ->name('sync-open-overheid-documents')
//     ->withoutOverlapping()
//     ->onFailure(function () {
//         \Log::channel('sync_errors')->error('Open Overheid sync job failed');
//     });

// DISABLED: Typesense sync command moved to Archived folder
// This command was causing high load on the main application.
// See: app/Console/Archived/README.md for re-enabling instructions
//
// Schedule Typesense sync every minute
// Schedule::command('typesense:sync')
//     ->everyMinute()
//     ->name('sync-document-to-typesense')
//     ->withoutOverlapping()
//     ->onFailure(function (\Illuminate\Console\Scheduling\Event $event) {
//         \Log::channel('typesense_errors')->error('Typesense sync command failed', [
//             'command' => $event->command,
//             'exit_code' => $event->exitCode,
//         ]);
//     });
