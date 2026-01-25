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
        Schema::create('sent_emails', function (Blueprint $table) {
            $table->id();
            $table->string('to');
            $table->string('cc')->nullable();
            $table->string('subject');
            $table->text('body');
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->string('mailable_type')->nullable()->comment('Class name of the mailable (e.g., ContactNotificationMail)');
            $table->unsignedBigInteger('mailable_id')->nullable()->comment('ID of related model (e.g., ContactSubmission)');
            $table->text('error_message')->nullable();
            $table->integer('attempts')->default(0);
            $table->timestamps();
            
            // Indexes for common queries
            $table->index('is_sent');
            $table->index('to');
            $table->index('mailable_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sent_emails');
    }
};
