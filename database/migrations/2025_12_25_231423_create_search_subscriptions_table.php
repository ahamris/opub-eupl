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
        Schema::create('search_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->enum('frequency', ['immediate', 'daily', 'weekly'])->default('daily');
            $table->string('search_query')->nullable();
            $table->json('filters')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('verification_token')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();
            
            $table->index('email');
            $table->index('is_active');
            $table->index('frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_subscriptions');
    }
};
