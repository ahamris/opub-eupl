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
        Schema::create('admin_menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_menu_id')
                ->constrained('admin_menus')
                ->cascadeOnDelete();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('admin_menu_items')
                ->cascadeOnDelete();
            $table->string('item_type')->default('link'); // link, section, divider
            $table->string('label');
            $table->string('slug')->nullable();
            $table->string('route_name')->nullable();
            $table->json('route_parameters')->nullable();
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
            $table->string('badge_text')->nullable();
            $table->string('badge_color')->nullable();
            $table->text('active_pattern')->nullable();
            $table->string('target')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('options')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_menu_items');
    }
};
