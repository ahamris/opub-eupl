<?php

use App\Jobs\SyncDocumentToTypesense;
use App\Jobs\SyncOpenOverheidDocumentsJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule Open Overheid sync job to run daily at 2 AM
Schedule::job(new SyncOpenOverheidDocumentsJob)
    ->dailyAt('02:00')
    ->name('sync-open-overheid-documents')
    ->withoutOverlapping()
    ->onFailure(function () {
        \Log::channel('sync_errors')->error('Open Overheid sync job failed');
    });

// Schedule Typesense sync every minute
Schedule::job(SyncDocumentToTypesense::class)
    ->everyMinute()
    ->name('sync-document-to-typesense')
    ->withoutOverlapping();
