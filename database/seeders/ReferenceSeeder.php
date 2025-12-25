<?php

namespace Database\Seeders;

use App\Models\Reference;
use Illuminate\Database\Seeder;

class ReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $references = [
            [
                'icon' => 'fas fa-gavel',
                'title' => 'Wet- en regelgeving',
                'description' => 'Officiële publicaties van wet- en regelgeving van de Nederlandse overheid.',
                'link_url' => 'https://wetten.overheid.nl',
                'link_text' => 'wetten.nl',
                'sort_order' => 0,
                'is_active' => true,
            ],
            [
                'icon' => 'fas fa-search',
                'title' => 'Woo-index',
                'description' => 'Vind contactgegevens van bestuursorganen voor het indienen van Woo-verzoeken.',
                'link_url' => 'https://www.woo-index.nl',
                'link_text' => 'woo-index.nl',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'icon' => 'fas fa-building',
                'title' => 'Overheid.nl',
                'description' => 'Centrale toegangspoort tot alle informatie van de Nederlandse overheid.',
                'link_url' => 'https://www.overheid.nl',
                'link_text' => 'overheid.nl',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($references as $reference) {
            Reference::updateOrCreate(
                ['title' => $reference['title']],
                $reference
            );
        }
    }
}
