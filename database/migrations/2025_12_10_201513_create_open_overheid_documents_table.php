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
        Schema::create('open_overheid_documents', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique()->comment('Unique identifier from API (e.g. oep-...)');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->date('publication_date')->nullable();
            $table->string('document_type')->nullable()->comment('documentsoort');
            $table->string('category')->nullable()->comment('informatiecategorie');
            $table->string('theme')->nullable()->comment('thema');
            $table->string('organisation')->nullable();
            $table->jsonb('metadata')->nullable()->comment('Full API response stored as JSON');
            $table->timestamp('synced_at')->nullable()->comment('Last successful sync timestamp');
            $table->timestamps();

            // Indexes for filter fields
            $table->index('publication_date');
            $table->index('document_type');
            $table->index('category');
            $table->index('theme');
            $table->index('organisation');
            $table->index('synced_at');
        });

        // Full-text search index for PostgreSQL
        // This creates a tsvector column and index for efficient full-text search
        if (config('database.default') === 'pgsql') {
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
        Schema::dropIfExists('open_overheid_documents');
    }
};
