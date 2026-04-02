<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('open_overheid_documents', function (Blueprint $table) {
            $table->timestamp('embedding_generated_at')->nullable()->index()->after('ai_enhanced_at');
        });
    }

    public function down(): void
    {
        Schema::table('open_overheid_documents', function (Blueprint $table) {
            $table->dropColumn('embedding_generated_at');
        });
    }
};
