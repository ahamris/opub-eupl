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
        Schema::create('dossier_ai_content', function (Blueprint $table) {
            $table->id();
            $table->string('dossier_external_id')->unique()->comment('External ID of the dossier document');
            $table->text('summary')->nullable()->comment('B1-level summary of the dossier');
            $table->text('enhanced_title')->nullable()->comment('AI-enhanced title for the dossier');
            $table->json('keywords')->nullable()->comment('Extracted keywords');
            $table->text('audio_url')->nullable()->comment('URL to generated audio/podcast file');
            $table->integer('audio_duration_seconds')->nullable()->comment('Duration of audio in seconds');
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('dossier_external_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossier_ai_content');
    }
};
