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
        Schema::create('about_settings', function (Blueprint $table) {
            $table->id();
            
            // Page Header
            $table->string('page_eyebrow', 255)->default('Open source Woo-voorziening');
            $table->string('page_title', 255)->default('Over OpenPublicaties');
            $table->text('page_description')->nullable();
            
            // Introduction Section
            $table->text('intro_content')->nullable();
            
            // Section 1: Projectdoelstelling
            $table->string('section1_title', 255)->default('Projectdoelstelling');
            $table->text('section1_content')->nullable();
            $table->boolean('section1_is_active')->default(true);
            
            // Section 2: Technische Realisatie
            $table->string('section2_title', 255)->default('Technische Realisatie');
            $table->text('section2_intro')->nullable();
            $table->json('section2_features')->nullable(); // Array of features with title and description
            $table->text('section2_outro')->nullable();
            $table->boolean('section2_is_active')->default(true);
            
            // Section 3: Kernwaarden
            $table->string('section3_title', 255)->default('Kernwaarden');
            $table->json('section3_values')->nullable(); // Array of values with icon, title, description
            $table->boolean('section3_is_active')->default(true);
            
            // Section 4: Van proof-of-concept naar gezamenlijke voorziening
            $table->string('section4_title', 255)->default('Van proof-of-concept naar gezamenlijke voorziening');
            $table->text('section4_content')->nullable();
            $table->boolean('section4_is_active')->default(true);
            
            // Section 5: Bijdrage aan de Wet open overheid
            $table->string('section5_title', 255)->default('Bijdrage aan de Wet open overheid (Woo)');
            $table->text('section5_content')->nullable();
            $table->boolean('section5_is_active')->default(true);
            
            // Contact Section
            $table->string('contact_title', 255)->default('Vraag en ondersteuning');
            $table->text('contact_content')->nullable();
            $table->string('contact_link_text', 255)->default('Link naar contact');
            $table->string('contact_link_url', 500)->nullable();
            $table->boolean('contact_is_active')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_settings');
    }
};
