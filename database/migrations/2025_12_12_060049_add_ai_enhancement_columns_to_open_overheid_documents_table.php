<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('open_overheid_documents', function (Blueprint $table) {
            $table->text('ai_enhanced_title')->nullable()->after('title');
            $table->text('ai_enhanced_description')->nullable()->after('description');
            $table->text('ai_summary')->nullable()->after('content');
            $table->jsonb('ai_keywords')->nullable()->after('ai_summary');
            $table->timestamp('ai_enhanced_at')->nullable()->after('ai_keywords');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('open_overheid_documents', function (Blueprint $table) {
            $table->dropColumn([
                'ai_enhanced_title',
                'ai_enhanced_description',
                'ai_summary',
                'ai_keywords',
                'ai_enhanced_at',
            ]);
        });
    }
};
