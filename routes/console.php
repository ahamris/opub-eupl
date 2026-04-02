<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Open Overheid sync: daily at 2 AM, last 7 days
Schedule::command("open-overheid:sync --recent --days=7")
    ->dailyAt("02:00")
    ->name("sync-open-overheid-documents")
    ->withoutOverlapping()
    ->onFailure(function (\Illuminate\Console\Scheduling\Event $event) {
        \Log::channel("sync_errors")->error("Open Overheid sync command failed", [
            "command" => $event->command,
            "exit_code" => $event->exitCode,
            "output" => $event->output ?? "No output captured",
        ]);
    })
    ->appendOutputTo(storage_path("logs/sync-command-output.log"));

// Typesense sync: every 5 minutes
Schedule::command("typesense:sync")
    ->everyFiveMinutes()
    ->name("sync-document-to-typesense")
    ->withoutOverlapping()
    ->onFailure(function (\Illuminate\Console\Scheduling\Event $event) {
        \Log::channel("typesense_errors")->error("Typesense sync command failed", [
            "command" => $event->command,
            "exit_code" => $event->exitCode,
        ]);
    });

// AI document enrichment: every 6 hours, 200 docs per run
Schedule::command("documents:enrich --limit=1000 --concurrency=4")
    ->everyTwoHours()
    ->name("enrich-documents")
    ->withoutOverlapping()
    ->appendOutputTo(storage_path("logs/enrich-output.log"));
