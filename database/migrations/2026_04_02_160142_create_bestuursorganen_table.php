<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bestuursorganen', function (Blueprint $table) {
            $table->id();
            $table->string('systeem_id')->unique();
            $table->string('naam');
            $table->string('afkorting')->nullable();
            $table->string('slug')->unique()->index();
            $table->string('type')->index(); // Gemeente, Ministerie, Inspectie, etc.
            $table->string('subtype')->nullable();

            // Bezoekadres
            $table->string('straat')->nullable();
            $table->string('huisnummer')->nullable();
            $table->string('postcode')->nullable();
            $table->string('woonplaats')->nullable();
            $table->string('provincie')->nullable();

            // Postadres
            $table->string('postbus')->nullable();
            $table->string('post_postcode')->nullable();
            $table->string('post_woonplaats')->nullable();

            // Woo-adres
            $table->string('woo_straat')->nullable();
            $table->string('woo_huisnummer')->nullable();
            $table->string('woo_postbus')->nullable();
            $table->string('woo_postcode')->nullable();
            $table->string('woo_woonplaats')->nullable();
            $table->string('woo_email')->nullable();

            // Contact
            $table->string('telefoon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('contactformulier_url')->nullable();

            // Identifiers
            $table->string('tooi_identifier')->nullable()->index();
            $table->string('owms_identifier')->nullable();

            // Relations
            $table->string('relatie_ministerie')->nullable();

            // Woo
            $table->boolean('is_woo_plichtig')->default(false);
            $table->string('woo_url')->nullable();

            // Description from XML
            $table->text('beschrijving')->nullable();

            // Claimed / self-managed
            $table->string('logo_url')->nullable();
            $table->text('custom_beschrijving')->nullable();
            $table->foreignId('claimed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('claimed_at')->nullable();

            // Matching to open_overheid_documents.organisation
            $table->string('document_match_name')->nullable()->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bestuursorganen');
    }
};
