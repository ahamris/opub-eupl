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
            $table->timestamp('typesense_synced_at')->nullable()->after('synced_at')
                ->comment('Last successful Typesense sync timestamp');

            $table->index('typesense_synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('open_overheid_documents', function (Blueprint $table) {
            $table->dropIndex(['typesense_synced_at']);
            $table->dropColumn('typesense_synced_at');
        });
    }
};
