<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('open_overheid_documents', function (Blueprint $table) {
            // Source tracking — always know where data came from
            $table->string('source_url', 2048)->nullable()->after('metadata')
                ->comment('Direct URL to original document at source');
            $table->string('source_type', 50)->nullable()->after('source_url')
                ->comment('Data source: open_overheid, raadsinformatie');

            // Structured metadata fields
            $table->jsonb('subjects')->nullable()->after('theme')
                ->comment('Onderwerpen/themas array from source');

            // AI-enriched metadata — fills gaps when source data is incomplete
            $table->text('ai_description_short')->nullable()->after('ai_enhanced_description')
                ->comment('AI-generated korte omschrijving when source lacks one');
            $table->text('ai_description_long')->nullable()->after('ai_description_short')
                ->comment('AI-generated lange omschrijving based on content');
            $table->jsonb('ai_subjects')->nullable()->after('ai_keywords')
                ->comment('AI-extracted onderwerpen/themas');

            // Index for filtering by source
            $table->index('source_type');
        });

        // Backfill source_type for existing documents
        \Illuminate\Support\Facades\DB::statement("
            UPDATE open_overheid_documents
            SET source_type = CASE
                WHEN external_id LIKE 'ori-%' THEN 'raadsinformatie'
                ELSE 'open_overheid'
            END
            WHERE source_type IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('open_overheid_documents', function (Blueprint $table) {
            $table->dropIndex(['source_type']);
            $table->dropColumn([
                'source_url',
                'source_type',
                'subjects',
                'ai_description_short',
                'ai_description_long',
                'ai_subjects',
            ]);
        });
    }
};
