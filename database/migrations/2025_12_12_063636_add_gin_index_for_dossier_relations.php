<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            // PostgreSQL: Add GIN index on metadata->'documentrelaties' for faster dossier queries
            // This significantly speeds up the EXISTS query with jsonb_array_elements
            DB::statement("
                CREATE INDEX IF NOT EXISTS idx_open_overheid_documents_metadata_documentrelaties_gin 
                ON open_overheid_documents 
                USING GIN ((metadata->'documentrelaties'))
            ");
        }
        // MariaDB/MySQL: JSON indexing requires generated columns, which is complex for array data.
        // The queries will still work but may be slower on large datasets.
        // Consider adding a generated column index if performance becomes an issue.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS idx_open_overheid_documents_metadata_documentrelaties_gin');
        }
    }
};
