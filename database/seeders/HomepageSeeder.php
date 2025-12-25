<?php

namespace Database\Seeders;

use App\Models\BentoItem;
use App\Models\CtaPanel;
use App\Models\HomepageSetting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class HomepageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create HomepageSetting instance (singleton with defaults)
        HomepageSetting::getInstance();

        // Create CTA Panels
        CtaPanel::updateOrCreate(
            ['slug' => 'ai-assistant'],
            [
                'title' => 'Stel vragen aan onze AI-assistent',
                'description' => 'Vraag in gewone taal naar overheidsdocumenten en ontvang direct antwoord. Onze AI doorzoekt honderden duizenden documenten en vindt precies wat u zoekt.',
                'primary_button_text' => 'Probeer nu gratis',
                'primary_button_url' => '/chat',
                'secondary_button_text' => 'Hoe werkt het?',
                'secondary_button_url' => '/over',
                'variant' => 'purple',
                'sort_order' => 0,
                'is_active' => true,
            ]
        );

        CtaPanel::updateOrCreate(
            ['slug' => 'bottom-cta'],
            [
                'title' => 'Begin vandaag nog met transparante publicaties',
                'description' => 'Ontdek hoe OpenPublicaties uw organisatie kan helpen bij het voldoen aan de Wet open overheid. Eenvoudig, betrouwbaar en volledig open source.',
                'primary_button_text' => 'Neem contact op',
                'primary_button_url' => '/contact',
                'secondary_button_text' => 'Meer informatie',
                'secondary_button_url' => '/over',
                'variant' => 'primary',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // Create Bento Items
        BentoItem::updateOrCreate(
            ['title' => "Thema's"],
            [
                'description' => 'Zoek documenten op onderwerp zoals ruimtelijke ordening, onderwijs of zorg.',
                'url' => '/themas',
                'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?q=80&w=2070&auto=format&fit=crop',
                'col_span' => 4,
                'is_coming_soon' => false,
                'sort_order' => 0,
                'is_active' => true,
            ]
        );

        BentoItem::updateOrCreate(
            ['title' => 'Dossiers'],
            [
                'description' => 'Verken complete dossiers met alle bijbehorende documenten.',
                'url' => null,
                'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2072&auto=format&fit=crop',
                'col_span' => 2,
                'is_coming_soon' => true,
                'coming_soon_text' => 'Coming Soon',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        BentoItem::updateOrCreate(
            ['title' => 'Uitgebreid zoeken'],
            [
                'description' => 'Filters op datum, organisatie of categorie.',
                'url' => '/zoeken',
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2070&auto=format&fit=crop',
                'col_span' => 2,
                'is_coming_soon' => false,
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        BentoItem::updateOrCreate(
            ['title' => 'Verwijzingen'],
            [
                'description' => 'Vind handige links naar gerelateerde websites en informatiebronnen.',
                'url' => '/verwijzingen',
                'image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=2084&auto=format&fit=crop',
                'col_span' => 4,
                'is_coming_soon' => false,
                'sort_order' => 3,
                'is_active' => true,
            ]
        );

        // Create Testimonials
        $testimonials = [
            [
                'quote' => 'Dit platform maakt het zoeken naar overheidsdocumenten eindelijk eenvoudig. De zoekfunctie is snel en de filters helpen me precies te vinden wat ik nodig heb.',
                'author' => 'Sarah van der Berg',
                'role' => 'Onderzoeker',
                'organization' => 'Universiteit van Amsterdam',
                'rating' => 5,
                'sort_order' => 0,
            ],
            [
                'quote' => 'Als journalist gebruik ik dit platform dagelijks. De transparantie en toegankelijkheid van documenten is ongeëvenaard.',
                'author' => 'Mark Jansen',
                'role' => 'Journalist',
                'organization' => 'NRC Handelsblad',
                'rating' => 5,
                'sort_order' => 1,
            ],
            [
                'quote' => 'Fantastisch platform voor het vinden van overheidsinformatie. De interface is intuïtief en de documenten zijn goed georganiseerd.',
                'author' => 'Lisa de Vries',
                'role' => 'Beleidsmedewerker',
                'organization' => 'Gemeente Rotterdam',
                'rating' => 4,
                'sort_order' => 2,
            ],
            [
                'quote' => 'De Woo-voorziening is een game-changer. Eindelijk kunnen we eenvoudig toegang krijgen tot alle openbare documenten.',
                'author' => 'Tom Bakker',
                'role' => 'Advocaat',
                'organization' => 'Bakker & Partners',
                'rating' => 5,
                'sort_order' => 3,
            ],
            [
                'quote' => 'Als burger waardeer ik de transparantie enorm. Dit platform maakt de overheid toegankelijker voor iedereen.',
                'author' => 'Emma Smit',
                'role' => 'Burger',
                'organization' => null,
                'rating' => 4,
                'sort_order' => 4,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                ['author' => $testimonial['author']],
                array_merge($testimonial, ['is_active' => true])
            );
        }
    }
}
