<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration enables PostgreSQL extensions required for the application.
     * It should run before any migrations that depend on these extensions.
     */
    public function up(): void
    {
        // Only run for PostgreSQL
        if (config('database.default') !== 'pgsql') {
            return;
        }

        // Enable pg_trgm extension for trigram similarity (optional, for fuzzy search)
        // This extension is useful for advanced text search features
        try {
            DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');
        } catch (\Exception $e) {
            // Log warning but don't fail migration if extension can't be enabled
            // This might happen if the user doesn't have superuser privileges
            \Illuminate\Support\Facades\Log::warning('Could not enable pg_trgm extension: '.$e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only run for PostgreSQL
        if (config('database.default') !== 'pgsql') {
            return;
        }

        // Note: We don't drop the extension in down() because:
        // 1. Other parts of the application might use it
        // 2. Dropping extensions requires superuser privileges
        // 3. It's safer to leave extensions enabled
    }
};



