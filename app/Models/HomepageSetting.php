<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperHomepageSetting
 */
class HomepageSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        // Hero Section
        'hero_badge',
        'hero_badge_text',
        'hero_badge_url',
        'hero_title',
        'hero_description',
        'hero_is_active',
        // Newsletter Section
        'newsletter_eyebrow',
        'newsletter_title',
        'newsletter_description',
        'newsletter_button_text',
        'newsletter_feature_1_title',
        'newsletter_feature_1_description',
        'newsletter_feature_2_title',
        'newsletter_feature_2_description',
        'newsletter_is_active',
        // Bento Section
        'bento_eyebrow',
        'bento_title',
        'bento_description',
        'bento_is_active',
        // Kennisbank Section
        'kennisbank_eyebrow',
        'kennisbank_title',
        'kennisbank_description',
        'kennisbank_is_active',
        // Testimonials Section
        'testimonials_eyebrow',
        'testimonials_title',
        'testimonials_description',
        'testimonials_is_active',
    ];

    protected $casts = [
        'hero_is_active' => 'boolean',
        'newsletter_is_active' => 'boolean',
        'bento_is_active' => 'boolean',
        'kennisbank_is_active' => 'boolean',
        'testimonials_is_active' => 'boolean',
    ];

    /**
     * Get the singleton instance of homepage settings.
     */
    public static function getInstance(): self
    {
        return Cache::remember('homepage_settings', 3600, function () {
            return self::firstOrCreate([], [
                'hero_title' => 'OpenPublicaties: Open Source Woo-Voorziening',
                'hero_badge' => 'Open source Woo-voorziening',
                'hero_badge_text' => 'Volledig operationeel',
                'hero_description' => 'Vind en bekijk alle actief openbaar gemaakte overheidsdocumenten. Eenvoudig, betrouwbaar en volledig transparant.',
                'bento_eyebrow' => 'Snel aan de slag',
                'bento_title' => 'Alles wat je nodig hebt',
                'bento_description' => 'Verken de verschillende manieren om overheidsdocumenten te vinden en te raadplegen.',
                'kennisbank_eyebrow' => 'Leren & ontdekken',
                'kennisbank_title' => 'Kennisbank',
                'kennisbank_description' => 'Leer meer over open data, transparantie en hoe je het platform effectief gebruikt.',
                'testimonials_eyebrow' => 'Testimonials',
                'testimonials_title' => 'Wat gebruikers vinden',
                'testimonials_description' => 'Ontdek hoe anderen het platform gebruiken voor hun onderzoek en werk.',
                'newsletter_eyebrow' => 'Nieuwsbrief',
                'newsletter_title' => 'Blijf op de hoogte',
                'newsletter_description' => 'Schrijf je in voor updates en ontvang het laatste nieuws over nieuwe documenten en platformupdates.',
                'newsletter_button_text' => 'Inschrijven',
                'newsletter_feature_1_title' => 'Regelmatige updates',
                'newsletter_feature_1_description' => 'Wekelijks overzicht van nieuwe documenten en ontwikkelingen.',
                'newsletter_feature_2_title' => 'Geen spam',
                'newsletter_feature_2_description' => 'Alleen relevante updates. Je kunt je altijd uitschrijven.',
            ]);
        });
    }

    /**
     * Clear the cache when settings are updated.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('homepage_settings');
        });
    }
}
