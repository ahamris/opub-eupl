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
        Schema::create('homepage_settings', function (Blueprint $table) {
            $table->id();
            
            // Hero Section
            $table->string('hero_badge')->nullable();
            $table->string('hero_badge_text')->nullable();
            $table->string('hero_badge_url')->nullable();
            $table->string('hero_title');
            $table->text('hero_description')->nullable();
            $table->boolean('hero_is_active')->default(true);
            
            // Newsletter Section
            $table->string('newsletter_eyebrow')->nullable();
            $table->string('newsletter_title')->nullable();
            $table->text('newsletter_description')->nullable();
            $table->string('newsletter_button_text')->nullable();
            $table->string('newsletter_feature_1_title')->nullable();
            $table->text('newsletter_feature_1_description')->nullable();
            $table->string('newsletter_feature_2_title')->nullable();
            $table->text('newsletter_feature_2_description')->nullable();
            $table->boolean('newsletter_is_active')->default(true);
            
            // Bento Section Header
            $table->string('bento_eyebrow')->nullable();
            $table->string('bento_title')->nullable();
            $table->text('bento_description')->nullable();
            $table->boolean('bento_is_active')->default(true);
            
            // Kennisbank Section Header
            $table->string('kennisbank_eyebrow')->nullable();
            $table->string('kennisbank_title')->nullable();
            $table->text('kennisbank_description')->nullable();
            $table->boolean('kennisbank_is_active')->default(true);
            
            // Testimonials Section Header
            $table->string('testimonials_eyebrow')->nullable();
            $table->string('testimonials_title')->nullable();
            $table->text('testimonials_description')->nullable();
            $table->boolean('testimonials_is_active')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_settings');
    }
};
