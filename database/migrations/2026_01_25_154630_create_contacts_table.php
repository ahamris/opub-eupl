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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('full_name')->nullable();
            $table->string('organisation')->nullable();
            $table->string('phone')->nullable();
            $table->string('status')->default('new')->comment('new, active, pending, resolved, closed');
            $table->string('priority')->default('normal')->comment('low, normal, high, urgent');
            $table->text('notes')->nullable();
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamps();
            
            // Indexes for common queries
            $table->index('email');
            $table->index('status');
            $table->index('priority');
            $table->index('last_contacted_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
