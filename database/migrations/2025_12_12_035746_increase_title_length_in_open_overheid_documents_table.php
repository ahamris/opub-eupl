<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (config('database.default') === 'pgsql') {
            // Drop the generated column and index first
            DB::statement('DROP INDEX IF EXISTS open_overheid_documents_search_vector_idx');
            DB::statement('ALTER TABLE open_overheid_documents DROP COLUMN IF EXISTS search_vector');
        }

        // Change title from varchar(255) to varchar(1000) to accommodate longer titles
        Schema::table('open_overheid_documents', function (Blueprint $table) {
            $table->string('title', 1000)->nullable()->change();
        });

        if (config('database.default') === 'pgsql') {
            // Recreate the generated column and index
            DB::statement('
                ALTER TABLE open_overheid_documents
                ADD COLUMN search_vector tsvector
                GENERATED ALWAYS AS (
                    setweight(to_tsvector(\'dutch\', COALESCE(title, \'\')), \'A\') ||
                    setweight(to_tsvector(\'dutch\', COALESCE(description, \'\')), \'B\') ||
                    setweight(to_tsvector(\'dutch\', COALESCE(content, \'\')), \'C\')
                ) STORED
            ');

            DB::statement('
                CREATE INDEX open_overheid_documents_search_vector_idx
                ON open_overheid_documents
                USING GIN (search_vector)
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') === 'pgsql') {
            // Drop the generated column and index first
            DB::statement('DROP INDEX IF EXISTS open_overheid_documents_search_vector_idx');
            DB::statement('ALTER TABLE open_overheid_documents DROP COLUMN IF EXISTS search_vector');
        }

        // Revert back to varchar(255)
        Schema::table('open_overheid_documents', function (Blueprint $table) {
            $table->string('title', 255)->nullable()->change();
        });

        if (config('database.default') === 'pgsql') {
            // Recreate the generated column and index
            DB::statement('
                ALTER TABLE open_overheid_documents
                ADD COLUMN search_vector tsvector
                GENERATED ALWAYS AS (
                    setweight(to_tsvector(\'dutch\', COALESCE(title, \'\')), \'A\') ||
                    setweight(to_tsvector(\'dutch\', COALESCE(description, \'\')), \'B\') ||
                    setweight(to_tsvector(\'dutch\', COALESCE(content, \'\')), \'C\')
                ) STORED
            ');

            DB::statement('
                CREATE INDEX open_overheid_documents_search_vector_idx
                ON open_overheid_documents
                USING GIN (search_vector)
            ');
        }
    }
};
