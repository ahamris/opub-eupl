<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Blog Categories
        $categories = [
            [
                'name' => 'Basis',
                'slug' => 'basis',
                'description' => 'Basisdacumenten en uitleg over de Woo',
                'color' => '#6366f1',
            ],
            [
                'name' => 'Gebruik',
                'slug' => 'gebruik',
                'description' => 'Tips en handleidingen voor het gebruik van het platform',
                'color' => '#8b5cf6',
            ],
            [
                'name' => 'Achtergrond',
                'slug' => 'achtergrond',
                'description' => 'Achtergrondinformatie over transparantie en overheid',
                'color' => '#a855f7',
            ],
        ];

        $categoryMap = [];
        foreach ($categories as $category) {
            $cat = BlogCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
            $categoryMap[$category['name']] = $cat->id;
        }

        // Create Blog Posts
        $blogs = [
            [
                'title' => 'Wat is de Wet open overheid (Woo)?',
                'slug' => 'wat-is-de-wet-open-overheid-woo',
                'short_body' => 'Leer alles over de Woo en hoe deze wet transparantie bevordert in de Nederlandse overheid. Ontdek wat de Woo betekent voor burgers en organisaties.',
                'long_body' => '<h2>Introductie</h2>
<p>De Wet open overheid (Woo) is de opvolger van de Wet openbaarheid van bestuur (Wob). Deze wet regelt het recht van burgers op toegang tot overheidsinformatie en verplicht de overheid om informatie actief openbaar te maken.</p>

<h2>Wat betekent de Woo voor u?</h2>
<p>Als burger heeft u het recht om informatie op te vragen bij de overheid. De Woo zorgt ervoor dat:</p>
<ul>
<li>De overheid transparanter wordt</li>
<li>Besluitvorming beter navolgbaar is</li>
<li>U toegang heeft tot belangrijke documenten</li>
</ul>

<h2>Conclusie</h2>
<p>De Woo is een belangrijke stap richting een transparantere overheid en geeft burgers meer mogelijkheden om de overheid te controleren.</p>',
                'category' => 'Basis',
                'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?q=80&w=2070&auto=format&fit=crop',
                'days_ago' => 5,
            ],
            [
                'title' => 'Hoe zoek je effectief in overheidsdocumenten?',
                'slug' => 'hoe-zoek-je-effectief-in-overheidsdocumenten',
                'short_body' => 'Tips en trucs om snel de juiste documenten te vinden met geavanceerde zoekfuncties en filters.',
                'long_body' => '<h2>Zoektips</h2>
<p>Het vinden van de juiste overheidsdocumenten kan een uitdaging zijn. Met deze tips vindt u sneller wat u zoekt.</p>

<h2>Gebruik filters</h2>
<p>Filters helpen u om de zoekresultaten te verfijnen:</p>
<ul>
<li>Filter op organisatie</li>
<li>Filter op documenttype</li>
<li>Filter op datum</li>
<li>Filter op thema</li>
</ul>

<h2>Geavanceerd zoeken</h2>
<p>Gebruik aanhalingstekens voor exacte frasen en combineer zoekwoorden voor betere resultaten.</p>',
                'category' => 'Gebruik',
                'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2072&auto=format&fit=crop',
                'days_ago' => 12,
            ],
            [
                'title' => 'Open data en transparantie in Nederland',
                'slug' => 'open-data-en-transparantie-in-nederland',
                'short_body' => 'Ontdek hoe open data bijdraagt aan een transparantere overheid en betere democratie.',
                'long_body' => '<h2>Open Data</h2>
<p>Open data zijn gegevens die vrij beschikbaar zijn voor iedereen om te gebruiken en te verspreiden.</p>

<h2>Voordelen van open data</h2>
<p>Open data biedt veel voordelen voor de samenleving:</p>
<ul>
<li>Transparantie en verantwoording</li>
<li>Innovatie en economische groei</li>
<li>Betere besluitvorming</li>
<li>Democratische participatie</li>
</ul>

<h2>Nederland als koploper</h2>
<p>Nederland loopt voorop in Europa als het gaat om open data en transparantie van de overheid.</p>',
                'category' => 'Achtergrond',
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2070&auto=format&fit=crop',
                'days_ago' => 20,
            ],
            [
                'title' => 'Woo-verzoeken indienen: een praktische gids',
                'slug' => 'woo-verzoeken-indienen-een-praktische-gids',
                'short_body' => 'Stap-voor-stap instructies voor het indienen van een Woo-verzoek en wat je kunt verwachten.',
                'long_body' => '<h2>Wat is een Woo-verzoek?</h2>
<p>Een Woo-verzoek is een formeel verzoek om informatie bij een overheidsorganisatie.</p>

<h2>Stappen voor het indienen</h2>
<ol>
<li>Bepaal welke informatie u zoekt</li>
<li>Identificeer de juiste overheidsinstantie</li>
<li>Dien uw verzoek schriftelijk in</li>
<li>Wacht op een reactie (binnen 4 weken)</li>
</ol>

<h2>Tips</h2>
<p>Wees zo specifiek mogelijk in uw verzoek voor de beste resultaten.</p>',
                'category' => 'Gebruik',
                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=2070&auto=format&fit=crop',
                'days_ago' => 30,
            ],
            [
                'title' => 'Privacy en openbaarheid: de balans',
                'slug' => 'privacy-en-openbaarheid-de-balans',
                'short_body' => 'Hoe de Woo omgaat met privacy en welke informatie wel en niet openbaar gemaakt kan worden.',
                'long_body' => '<h2>Privacy vs Openbaarheid</h2>
<p>De Woo kent uitzonderingsgronden die bepalen wanneer informatie niet openbaar gemaakt hoeft te worden.</p>

<h2>Bescherming van persoonsgegevens</h2>
<p>Persoonsgegevens worden beschermd onder de AVG. Dit betekent dat:</p>
<ul>
<li>Namen vaak worden geanonimiseerd</li>
<li>Contactgegevens worden verwijderd</li>
<li>Persoonlijke informatie wordt beschermd</li>
</ul>

<h2>Andere uitzonderingen</h2>
<p>Naast privacy zijn er andere redenen om informatie niet openbaar te maken, zoals nationale veiligheid of bedrijfsgeheimen.</p>',
                'category' => 'Achtergrond',
                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=2070&auto=format&fit=crop',
                'days_ago' => 45,
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::updateOrCreate(
                ['slug' => $blog['slug']],
                [
                    'blog_category_id' => $categoryMap[$blog['category']],
                    'author_id' => 1, // Assuming admin user exists
                    'title' => $blog['title'],
                    'slug' => $blog['slug'],
                    'short_body' => $blog['short_body'],
                    'long_body' => $blog['long_body'],
                    'image' => $blog['image'],
                    'is_active' => true,
                    'is_featured' => false,
                    'created_at' => now()->subDays($blog['days_ago']),
                    'updated_at' => now()->subDays($blog['days_ago']),
                ]
            );
        }

        // Clear carousel cache after seeding
        Blog::clearCarouselCache();
    }
}
