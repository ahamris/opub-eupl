<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AboutSetting extends Model
{
    protected $table = 'about_settings';

    protected $fillable = [
        // Page Header
        'page_eyebrow',
        'page_title',
        'page_description',
        // Introduction
        'intro_content',
        // Section 1
        'section1_title',
        'section1_content',
        'section1_is_active',
        // Section 2
        'section2_title',
        'section2_intro',
        'section2_features',
        'section2_outro',
        'section2_is_active',
        // Section 3
        'section3_title',
        'section3_values',
        'section3_is_active',
        // Section 4
        'section4_title',
        'section4_content',
        'section4_is_active',
        // Section 5
        'section5_title',
        'section5_content',
        'section5_is_active',
        // Contact
        'contact_title',
        'contact_content',
        'contact_link_text',
        'contact_link_url',
        'contact_is_active',
    ];

    protected $casts = [
        'section1_is_active' => 'boolean',
        'section2_is_active' => 'boolean',
        'section3_is_active' => 'boolean',
        'section4_is_active' => 'boolean',
        'section5_is_active' => 'boolean',
        'contact_is_active' => 'boolean',
        'section2_features' => 'array',
        'section3_values' => 'array',
    ];

    /**
     * Get or create the singleton instance
     */
    public static function getInstance(): self
    {
        return Cache::remember('about_settings', 3600, function () {
            return self::firstOrCreate([], self::getDefaults());
        });
    }

    /**
     * Clear the cache
     */
    public static function clearCache(): void
    {
        Cache::forget('about_settings');
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }

    /**
     * Get default values
     */
    public static function getDefaults(): array
    {
        return [
            'page_eyebrow' => 'Open source Woo-voorziening',
            'page_title' => 'Over OpenPublicaties',
            'page_description' => 'Een volledig open source, lichtgewicht en state-of-the-art Woo-voorziening die actieve openbaarmaking eenvoudig, betrouwbaar en duurzaam ondersteunt.',
            'intro_content' => 'Open.overheid.nl bundelt actief openbaar gemaakte documenten op één centrale plek. Met OpenPublicaties (opub.nl) hebben wij een volledig open source Woo-voorziening ontwikkeld die hierop aansluit: een moderne, lichte referentie-implementatie die laat zien hoe actieve openbaarmaking sneller, transparanter en beter beheersbaar kan worden ingericht – in nauwe samenhang met de Woo-index en de landelijke voorzieningen.',
            'section1_title' => 'Projectdoelstelling',
            'section1_content' => '<p>Het doel van het project <em>OpenPublicaties</em> is het realiseren van een volledig open source, lichtgewicht en state-of-the-art Woo-voorziening die actief openbaar maken eenvoudig, betrouwbaar en duurzaam ondersteunt. De voorziening fungeert als een <strong>blauwdruk</strong> voor bestuursorganen en als <strong>innovatieve referentie-implementatie</strong> voor het Ministerie van BZK, waarmee wordt aangetoond dat moderne technieken het Woo-proces aanzienlijk kunnen versnellen en vereenvoudigen.</p><p>Het project levert een werkende, schaalbare, modulair uitbreidbare voorziening waarmee documenten automatisch worden geharvest, gemetadateerd, geïndexeerd en actief openbaar gemaakt, in nauwe aansluiting op de Woo-index en open.overheid.nl.</p>',
            'section1_is_active' => true,
            'section2_title' => 'Technische Realisatie',
            'section2_intro' => 'OpenPublicaties is door ons gebouwd als een werkende, actieve proof-of-concept Woo-voorziening. Geen cosmetische schil, maar een end-to-end keten op basis van state-of-the-art open source tooling:',
            'section2_features' => [
                ['title' => 'Laravel 12', 'description' => 'voor de applicatielaag'],
                ['title' => 'Go', 'description' => 'voor het actief harvesten van bronnen zoals open.overheid.nl en zoek.openraadsinformatie.nl'],
                ['title' => 'PostgreSQL 12 en Typesense', 'description' => 'voor opslag en razendsnelle zoekfunctionaliteit'],
                ['title' => 'Ollama AI (local)', 'description' => 'voor slimme vind- en duidfuncties'],
                ['title' => 'Tailwind CSS 4', 'description' => 'voor een moderne, toegankelijke front-end'],
            ],
            'section2_outro' => 'Met uitsluitend de broodnodige packages blijft de oplossing licht, beheersbaar en goed uitlegbaar.',
            'section2_is_active' => true,
            'section3_title' => 'Kernwaarden',
            'section3_values' => [
                ['icon' => 'fas fa-cloud-upload-alt', 'title' => 'Moderne, transparante architectuur.', 'description' => 'De volledige keten is opgebouwd uit open source componenten met een heldere scheiding tussen opslag, indexing, AI-ondersteuning en presentatie. Dat maakt de oplossing niet alleen snel en schaalbaar, maar ook eenvoudig te auditen, te beheren en zo nodig door andere leveranciers over te nemen of voort te zetten.'],
                ['icon' => 'fas fa-lock', 'title' => 'Actieve openbaarmaking als uitgangspunt.', 'description' => 'De inrichting volgt de logica van de Wet open overheid: zaakcontext en MDTO-metadata vormen het vertrekpunt. De oplossing ondersteunt het actief publiceren van Woo-categorieën, sluit aan bij de Woo-index en kan documenten en dossiers voorbereiden voor aanlevering aan landelijke voorzieningen, zonder bestaande portalen te vervangen.'],
                ['icon' => 'fas fa-users', 'title' => 'Samen leren, samen versnellen.', 'description' => 'OpenPublicaties is bewust als open source referentie-implementatie gebouwd. Bestuursorganen kunnen de blauwdruk hergebruiken, uitbreiden en samen met ons – of met andere partijen – doorontwikkelen. Daarmee ontstaat ruimte om te experimenteren, zonder de verantwoordelijkheid voor de landelijke voorzieningen of bestaande leveranciersrelaties te doorkruisen.'],
            ],
            'section3_is_active' => true,
            'section4_title' => 'Van proof-of-concept naar gezamenlijke voorziening',
            'section4_content' => '<p>Met deze oplossing reiken wij een concreet alternatief aan: geen papieren architectuur, maar een werkende voorziening die vandaag al draait en in de praktijk wordt beproefd. CodeLabs B.V. ondersteunt bestuursorganen graag bij het verkennen van deze aanpak – van een eerste pilot tot een structurele, multi-tenant inrichting naast of in aanvulling op bestaande Woo-voorzieningen.</p><p>We nodigen het ministerie van BZK en andere belanghebbenden van harte uit om samen in gesprek te gaan: niet om met de vinger te wijzen, maar om vanuit een gedeelde verantwoordelijkheid voor de rechtsstaat en de <em>Wet open overheid</em> te laten zien dat het daadwerkelijk eenvoudiger, sneller en toekomstbestendig kan. OpenPublicaties is onze uitgestoken hand – een transparante blauwdruk die we graag samen verder inkleuren.</p>',
            'section4_is_active' => true,
            'section5_title' => 'Bijdrage aan de Wet open overheid (Woo)',
            'section5_content' => 'OpenPublicaties versterkt de doelen van de Woo door actieve openbaarmaking te vereenvoudigen, toegankelijkheid en vindbaarheid te verbeteren, de transparantie van digitale overheidsinformatie te vergroten, en bestuursorganen meer grip te geven op hun informatiehuishouding.',
            'section5_is_active' => true,
            'contact_title' => 'Vraag en ondersteuning',
            'contact_content' => 'Heeft u vragen of suggesties over de website?',
            'contact_link_text' => 'Neem contact op',
            'contact_link_url' => '/contact',
            'contact_is_active' => true,
        ];
    }
}
