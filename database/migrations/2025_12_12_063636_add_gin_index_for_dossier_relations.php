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
        // Add GIN index on metadata->'documentrelaties' for faster dossier queries
        // This significantly speeds up the EXISTS query with jsonb_array_elements
        DB::statement("
            CREATE INDEX IF NOT EXISTS idx_open_overheid_documents_metadata_documentrelaties_gin 
            ON open_overheid_documents 
            USING GIN ((metadata->'documentrelaties'))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS idx_open_overheid_documents_metadata_documentrelaties_gin');
    }
};
