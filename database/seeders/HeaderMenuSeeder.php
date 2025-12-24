<?php

namespace Database\Seeders;

use App\Models\HeaderMenuItem;
use Illuminate\Database\Seeder;

class HeaderMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing items
        HeaderMenuItem::truncate();

        $position = 0;

        // ─────────────────────────────────────────────────────────────
        // Zoeken (Simple Link)
        // ─────────────────────────────────────────────────────────────
        HeaderMenuItem::create([
            'label' => 'Zoeken',
            'slug' => 'zoeken',
            'item_type' => 'link',
            'route_name' => 'zoeken',
            'position' => $position++,
            'is_active' => true,
        ]);

        // ─────────────────────────────────────────────────────────────
        // Collectie (Dropdown)
        // ─────────────────────────────────────────────────────────────
        $collectie = HeaderMenuItem::create([
            'label' => 'Collectie',
            'slug' => 'collectie',
            'item_type' => 'dropdown',
            'position' => $position++,
            'is_active' => true,
            'options' => [
                'active_pattern' => 'dossiers.*|themas.*|verwijzingen',
            ],
        ]);

        // Collectie children
        $childPosition = 0;

        // Dossiers (Disabled with Coming Soon badge)
        HeaderMenuItem::create([
            'parent_id' => $collectie->id,
            'label' => 'Dossiers',
            'slug' => 'dossiers',
            'item_type' => 'link',
            'route_name' => 'dossiers.index',
            'icon' => 'folder-open',
            'description' => 'Bekijk alle overheidsdossiers en bijbehorende documenten',
            'badge_text' => 'Coming Soon',
            'badge_color' => 'purple',
            'is_disabled' => true,
            'position' => $childPosition++,
            'is_active' => true,
        ]);

        // Thema's
        HeaderMenuItem::create([
            'parent_id' => $collectie->id,
            'label' => "Thema's",
            'slug' => 'themas',
            'item_type' => 'link',
            'route_name' => 'themas.index',
            'icon' => 'tags',
            'description' => 'Verken documenten op basis van thema en onderwerp',
            'position' => $childPosition++,
            'is_active' => true,
        ]);

        // Verwijzingen
        HeaderMenuItem::create([
            'parent_id' => $collectie->id,
            'label' => 'Verwijzingen',
            'slug' => 'verwijzingen',
            'item_type' => 'link',
            'route_name' => 'verwijzingen',
            'icon' => 'link',
            'description' => 'Ontdek gerelateerde bronnen en externe koppelingen',
            'position' => $childPosition++,
            'is_active' => true,
        ]);

        // ─────────────────────────────────────────────────────────────
        // Dashboard (Simple Link)
        // ─────────────────────────────────────────────────────────────
        HeaderMenuItem::create([
            'label' => 'Dashboard',
            'slug' => 'dashboard',
            'item_type' => 'link',
            'route_name' => 'reports.index',
            'position' => $position++,
            'is_active' => true,
        ]);

        // ─────────────────────────────────────────────────────────────
        // Kennisbank (Simple Link)
        // ─────────────────────────────────────────────────────────────
        HeaderMenuItem::create([
            'label' => 'Kennisbank',
            'slug' => 'kennisbank',
            'item_type' => 'link',
            'route_name' => 'blog.index',
            'position' => $position++,
            'is_active' => true,
        ]);

        // ─────────────────────────────────────────────────────────────
        // Contact (Dropdown)
        // ─────────────────────────────────────────────────────────────
        $contact = HeaderMenuItem::create([
            'label' => 'Contact',
            'slug' => 'contact',
            'item_type' => 'dropdown',
            'position' => $position++,
            'is_active' => true,
            'options' => [
                'active_pattern' => 'contact|over',
            ],
        ]);

        // Contact children
        $contactChildPosition = 0;

        // Contact
        HeaderMenuItem::create([
            'parent_id' => $contact->id,
            'label' => 'Contact',
            'slug' => 'contact-page',
            'item_type' => 'link',
            'route_name' => 'contact',
            'icon' => 'envelope',
            'description' => 'Neem contact met ons op',
            'position' => $contactChildPosition++,
            'is_active' => true,
        ]);

        // Over ons
        HeaderMenuItem::create([
            'parent_id' => $contact->id,
            'label' => 'Over ons',
            'slug' => 'over-ons',
            'item_type' => 'link',
            'route_name' => 'over',
            'icon' => 'info-circle',
            'description' => 'Meer over OpenPublicaties',
            'position' => $contactChildPosition++,
            'is_active' => true,
        ]);

        // Clear cache
        HeaderMenuItem::clearCache();
    }
}
