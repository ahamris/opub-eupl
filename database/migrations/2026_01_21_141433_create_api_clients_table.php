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
        Schema::create('api_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // First characters of the key for display/debugging (non-secret)
            $table->string('key_prefix', 16)->nullable();
            // sha256 hex string
            $table->string('api_key_hash', 64)->unique();
            // List of allowed origins/domains (e.g. ["app.example.com","*.example.com"] or ["*"])
            $table->json('allowed_domains')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->string('last_used_ip', 45)->nullable();
            $table->text('last_used_user_agent')->nullable();
            $table->timestamps();

            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_clients');
    }
};
