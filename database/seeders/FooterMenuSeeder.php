<?php

namespace Database\Seeders;

use App\Models\FooterMenuItem;
use Illuminate\Database\Seeder;

class FooterMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing items
        FooterMenuItem::truncate();

        $position = 0;

        // ─────────────────────────────────────────────────────────────
        // Over deze website (Dropdown)
        // ─────────────────────────────────────────────────────────────
        $overDezeWebsite = FooterMenuItem::create([
            'label' => 'Over deze website',
            'slug' => 'over-deze-website',
            'item_type' => 'dropdown',
            'position' => $position++,
            'is_active' => true,
        ]);

        $childPosition = 0;

        FooterMenuItem::create([
            'parent_id' => $overDezeWebsite->id,
            'label' => 'Over open.overheid.nl',
            'slug' => 'over-open-overheid',
            'item_type' => 'link',
            'route_name' => 'over',
            'position' => $childPosition++,
            'is_active' => true,
        ]);

        FooterMenuItem::create([
            'parent_id' => $overDezeWebsite->id,
            'label' => 'Verwijzingen',
            'slug' => 'verwijzingen',
            'item_type' => 'link',
            'route_name' => 'verwijzingen',
            'position' => $childPosition++,
            'is_active' => true,
        ]);

        // ─────────────────────────────────────────────────────────────
        // Recht & Privacy (Dropdown)
        // ─────────────────────────────────────────────────────────────
        $rechtPrivacy = FooterMenuItem::create([
            'label' => 'Recht & Privacy',
            'slug' => 'recht-privacy',
            'item_type' => 'dropdown',
            'position' => $position++,
            'is_active' => true,
        ]);

        $childPosition = 0;

        FooterMenuItem::create([
            'parent_id' => $rechtPrivacy->id,
            'label' => 'Privacy & Cookies',
            'slug' => 'privacy-cookies',
            'item_type' => 'link',
            'url' => '#',
            'position' => $childPosition++,
            'is_active' => true,
        ]);

        FooterMenuItem::create([
            'parent_id' => $rechtPrivacy->id,
            'label' => 'Toegankelijkheid',
            'slug' => 'toegankelijkheid',
            'item_type' => 'link',
            'url' => '#',
            'position' => $childPosition++,
            'is_active' => true,
        ]);

        // ─────────────────────────────────────────────────────────────
        // Externe links (Dropdown)
        // ─────────────────────────────────────────────────────────────
        $externeLinks = FooterMenuItem::create([
            'label' => 'Externe links',
            'slug' => 'externe-links',
            'item_type' => 'dropdown',
            'position' => $position++,
            'is_active' => true,
        ]);

        $childPosition = 0;

        FooterMenuItem::create([
            'parent_id' => $externeLinks->id,
            'label' => 'Overheid.nl',
            'slug' => 'overheid-nl',
            'item_type' => 'link',
            'url' => 'https://www.overheid.nl',
            'target' => '_blank',
            'position' => $childPosition++,
            'is_active' => true,
        ]);

        FooterMenuItem::create([
            'parent_id' => $externeLinks->id,
            'label' => 'Woo-index',
            'slug' => 'woo-index',
            'item_type' => 'link',
            'url' => 'https://www.woo-index.nl',
            'target' => '_blank',
            'position' => $childPosition++,
            'is_active' => true,
        ]);

        // Clear cache
        FooterMenuItem::clearCache();
    }
}

