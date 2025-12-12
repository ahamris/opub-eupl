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
        Schema::create('dossier_metadata', function (Blueprint $table) {
            $table->id();
            $table->string('dossier_external_id')->unique()->comment('External ID of the dossier document');
            $table->string('status')->default('gesloten')->comment('actief or gesloten');
            $table->integer('member_count')->default(1)->comment('Number of documents in this dossier');
            $table->date('latest_publication_date')->nullable()->comment('Latest publication date in dossier');
            $table->date('earliest_publication_date')->nullable()->comment('Earliest publication date in dossier');
            $table->string('organisation')->nullable()->comment('Organisation from main document');
            $table->string('category')->nullable()->comment('Category from main document');
            $table->string('theme')->nullable()->comment('Theme from main document');
            $table->timestamp('computed_at')->nullable()->comment('When metadata was last computed');
            $table->timestamps();

            // Indexes for fast filtering
            $table->index('status');
            $table->index('organisation');
            $table->index('category');
            $table->index('theme');
            $table->index('latest_publication_date');
            $table->index('computed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossier_metadata');
    }
};
