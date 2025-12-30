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
     * Berichtenbox inbox
     */
    public function berichtenbox(Request $request)
    {
        // Empty collection - no messages yet
        $berichten = collect([]);
        $tab = 'inbox';
        
        return view('user.dashboard.berichtenbox', compact('berichten', 'tab'));
    }

    /**
     * Berichtenbox archief
     */
    public function berichtenboxArchief()
    {
        // Empty collection - no messages yet
        $berichten = collect([]);
        $tab = 'archief';
        
        return view('user.dashboard.berichtenbox', compact('berichten', 'tab'));
    }

    /**
     * Berichtenbox prullenbak
     */
    public function berichtenboxPrullenbak()
    {
        // Empty collection - no messages yet
        $berichten = collect([]);
        $tab = 'prullenbak';
        
        return view('user.dashboard.berichtenbox', compact('berichten', 'tab'));
    }

    /**
     * Show individual bericht
     */
    public function berichtShow($id)
    {
        $bericht = $this->getDummyBerichtById($id);
        
        if (!$bericht) {
            abort(404);
        }
        
        return view('user.dashboard.bericht-show', compact('bericht'));
    }

    /**
     * Subscriptions page - shows user's search subscriptions
     */
    public function subscriptions()
    {
        $user = auth()->user();
        
        // Get subscriptions for the authenticated user's email
        $subscriptions = \App\Models\SearchSubscription::where('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('user.dashboard.subscriptions', compact('subscriptions'));
    }

    /**
     * Delete a subscription
     */
    public function destroySubscription(\App\Models\SearchSubscription $subscription)
    {
        $user = auth()->user();
        
        // Verify ownership - user can only delete their own subscriptions
        if ($subscription->email !== $user->email) {
            abort(403, 'U bent niet gemachtigd om dit abonnement te verwijderen.');
        }
        
        $subscription->delete();
        
        return redirect()
            ->route('user.subscriptions')
            ->with('success', 'Abonnement succesvol verwijderd.');
    }

    /**
     * Get dummy berichten based on type
     */
    private function getDummyBerichten($type = 'inbox')
    {
        $allBerichten = [
            [
                'id' => 1,
                'afzender' => 'Belastingdienst',
                'onderwerp' => 'Uw nieuwe voorschotbeschikking toeslagen 2026',
                'ontvangen' => now()->subDays(5),
                'is_read' => false,
                'type' => 'inbox',
            ],
            [
                'id' => 2,
                'afzender' => 'Belastingdienst',
                'onderwerp' => 'Definitieve aanslag inkomstenbelasting 2023',
                'ontvangen' => now()->subDays(45),
                'is_read' => true,
                'type' => 'inbox',
            ],
            [
                'id' => 3,
                'afzender' => 'Centraal Justitieel Incassobureau',
                'onderwerp' => 'Laatste herinnering',
                'ontvangen' => now()->subDays(60),
                'is_read' => true,
                'type' => 'inbox',
            ],
            [
                'id' => 4,
                'afzender' => 'Belastingdienst',
                'onderwerp' => 'Definitieve aanslag inkomstenbelasting 2022',
                'ontvangen' => now()->subDays(120),
                'is_read' => true,
                'type' => 'inbox',
            ],
            [
                'id' => 5,
                'afzender' => 'Justis',
                'onderwerp' => 'Uw Verklaring Omtrent het Gedrag (VOG)',
                'ontvangen' => now()->subDays(150),
                'is_read' => true,
                'type' => 'inbox',
            ],
            [
                'id' => 6,
                'afzender' => 'Stichting Pensioenfonds Horeca & Catering',
                'onderwerp' => 'Je pensioen gaat veranderen',
                'ontvangen' => now()->subDays(180),
                'is_read' => true,
                'type' => 'inbox',
            ],
            [
                'id' => 7,
                'afzender' => 'Belastingdienst',
                'onderwerp' => 'Uw nieuwe voorschotbeschikking toeslagen 2025',
                'ontvangen' => now()->subDays(200),
                'is_read' => true,
                'type' => 'inbox',
            ],
            [
                'id' => 8,
                'afzender' => 'Belastingdienst',
                'onderwerp' => 'Definitieve aanslag inkomstenbelasting 2020',
                'ontvangen' => now()->subDays(250),
                'is_read' => true,
                'type' => 'inbox',
            ],
            [
                'id' => 9,
                'afzender' => 'Belastingdienst',
                'onderwerp' => 'Definitieve aanslag inkomstenbelasting 2021',
                'ontvangen' => now()->subDays(300),
                'is_read' => true,
                'type' => 'inbox',
            ],
            [
                'id' => 10,
                'afzender' => 'Belastingdienst',
                'onderwerp' => 'Definitieve aanslag zorgverzekeringswet 2021',
                'ontvangen' => now()->subDays(350),
                'is_read' => true,
                'type' => 'inbox',
            ],
            // Archief berichten
            [
                'id' => 11,
                'afzender' => 'Gemeente Amsterdam',
                'onderwerp' => 'Uw aanvraag parkeervergunning',
                'ontvangen' => now()->subMonths(6),
                'is_read' => true,
                'type' => 'archief',
            ],
            [
                'id' => 12,
                'afzender' => 'RDW',
                'onderwerp' => 'Bevestiging kentekenregistratie',
                'ontvangen' => now()->subMonths(8),
                'is_read' => true,
                'type' => 'archief',
            ],
            // Prullenbak berichten
            [
                'id' => 13,
                'afzender' => 'DUO',
                'onderwerp' => 'Uw studiefinanciering update',
                'ontvangen' => now()->subYear(),
                'is_read' => true,
                'type' => 'prullenbak',
            ],
        ];

        return collect($allBerichten)->where('type', $type)->values();
    }

    /**
     * Get a single bericht by ID
     */
    private function getDummyBerichtById($id)
    {
        $berichten = [
            1 => [
                'id' => 1,
                'afzender' => 'Belastingdienst',
                'onderwerp' => 'Uw nieuwe voorschotbeschikking toeslagen 2026',
                'ontvangen' => now()->subDays(5),
                'is_read' => false,
                'inhoud' => '<p>Geachte heer/mevrouw,</p>
                <p>U ontvangt deze brief omdat wij uw voorschot toeslagen voor 2026 hebben berekend.</p>
                <p>Uw voorschottelijke berekening:</p>
                <ul>
                    <li>Zorgtoeslag: € 123,00 per maand</li>
                    <li>Huurtoeslag: € 234,00 per maand</li>
                </ul>
                <p>Dit bedrag wordt maandelijks op uw rekening gestort.</p>
                <p>Met vriendelijke groet,<br>Belastingdienst/Toeslagen</p>',
            ],
            2 => [
                'id' => 2,
                'afzender' => 'Belastingdienst',
                'onderwerp' => 'Definitieve aanslag inkomstenbelasting 2023',
                'ontvangen' => now()->subDays(45),
                'is_read' => true,
                'inhoud' => '<p>Geachte heer/mevrouw,</p>
                <p>Hierbij ontvangt u uw definitieve aanslag inkomstenbelasting 2023.</p>
                <p>Op basis van uw aangifte hebben wij het volgende berekend:</p>
                <ul>
                    <li>Verzamelinkomen: € 45.000,00</li>
                    <li>Te betalen belasting: € 12.500,00</li>
                    <li>Reeds ingehouden: € 12.000,00</li>
                    <li>Nog te betalen: € 500,00</li>
                </ul>
                <p>U kunt dit bedrag binnen 6 weken overmaken.</p>
                <p>Met vriendelijke groet,<br>Belastingdienst</p>',
            ],
            3 => [
                'id' => 3,
                'afzender' => 'Centraal Justitieel Incassobureau',
                'onderwerp' => 'Laatste herinnering',
                'ontvangen' => now()->subDays(60),
                'is_read' => true,
                'inhoud' => '<p>Geachte heer/mevrouw,</p>
                <p>Uit onze administratie blijkt dat u nog een openstaand bedrag heeft van € 95,00.</p>
                <p>Wij verzoeken u dit bedrag binnen 14 dagen te voldoen om verdere incassomaatregelen te voorkomen.</p>
                <p>Met vriendelijke groet,<br>CJIB</p>',
            ],
            4 => [
                'id' => 4,
                'afzender' => 'Belastingdienst',
                'onderwerp' => 'Definitieve aanslag inkomstenbelasting 2022',
                'ontvangen' => now()->subDays(120),
                'is_read' => true,
                'inhoud' => '<p>Geachte heer/mevrouw,</p>
                <p>Hierbij ontvangt u uw definitieve aanslag inkomstenbelasting 2022.</p>
                <p>U ontvangt een teruggave van € 350,00. Dit bedrag is overgemaakt naar uw rekening.</p>
                <p>Met vriendelijke groet,<br>Belastingdienst</p>',
            ],
            5 => [
                'id' => 5,
                'afzender' => 'Justis',
                'onderwerp' => 'Uw Verklaring Omtrent het Gedrag (VOG)',
                'ontvangen' => now()->subDays(150),
                'is_read' => true,
                'inhoud' => '<p>Geachte heer/mevrouw,</p>
                <p>Uw aanvraag voor een Verklaring Omtrent het Gedrag (VOG) is goedgekeurd.</p>
                <p>De VOG wordt binnen 5 werkdagen per post naar u verzonden.</p>
                <p>Met vriendelijke groet,<br>Justis</p>',
            ],
        ];

        return $berichten[$id] ?? null;
    }
}
