<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard home
     */
    public function index()
    {
        // Dummy data for recent activity
        $recentActivity = [
            [
                'id' => 'SUB-001',
                'title' => 'Nieuwe documenten beschikbaar',
                'description' => 'Er zijn 5 nieuwe documenten gepubliceerd in uw abonnement.',
                'status' => 'Nieuw',
                'status_color' => 'green',
                'date' => now()->subDays(1),
            ],
            [
                'id' => 'SUB-002',
                'title' => 'Woo-besluit update',
                'description' => 'Een Woo-besluit in uw collectie is bijgewerkt.',
                'status' => 'Bekeken',
                'status_color' => 'gray',
                'date' => now()->subDays(3),
            ],
            [
                'id' => 'SUB-003',
                'title' => 'Nieuw openbaar document',
                'description' => 'Een nieuw document is toegevoegd aan open.overheid.nl.',
                'status' => 'Nieuw',
                'status_color' => 'green',
                'date' => now()->subDays(5),
            ],
        ];

        // Dummy FAQ
        $faqs = [
            'Hoe kan ik mijn abonnementen beheren?',
            'Wat betekent de status "Nieuw"?',
            'Hoe vaak worden documenten bijgewerkt?',
        ];

        return view('user.dashboard.index', compact('recentActivity', 'faqs'));
    }

    /**
     * Subscriptions page
     */
    public function subscriptions()
    {
        // Dummy subscriptions
        $subscriptions = [
            [
                'id' => 1,
                'name' => 'Woo-besluiten Gemeente Amsterdam',
                'type' => 'Zoekterm',
                'query' => 'gemeente amsterdam woo',
                'new_count' => 3,
                'created_at' => now()->subMonths(2),
            ],
            [
                'id' => 2,
                'name' => 'Milieu en duurzaamheid',
                'type' => 'Categorie',
                'query' => 'category:milieu',
                'new_count' => 7,
                'created_at' => now()->subMonths(1),
            ],
            [
                'id' => 3,
                'name' => 'Ministerie van BZK',
                'type' => 'Organisatie',
                'query' => 'organization:bzk',
                'new_count' => 0,
                'created_at' => now()->subWeeks(2),
            ],
        ];

        return view('user.dashboard.subscriptions', compact('subscriptions'));
    }
}
