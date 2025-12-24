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
        Schema::create('header_menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('header_menu_items')->cascadeOnDelete();
            $table->string('label');
            $table->string('slug')->nullable();
            $table->enum('item_type', ['link', 'dropdown', 'megamenu'])->default('link');
            $table->string('route_name')->nullable();
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
            $table->string('description')->nullable();
            $table->string('badge_text')->nullable();
            $table->string('badge_color')->nullable();
            $table->boolean('is_disabled')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->string('target')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('options')->nullable();
            $table->timestamps();

            $table->index(['parent_id', 'position']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('header_menu_items');
    }
};
