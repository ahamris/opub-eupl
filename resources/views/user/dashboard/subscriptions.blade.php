@extends('user.layouts.dashboard')

@section('dashboard-content')
<!-- Breadcrumb -->
<div class="text-sm text-gray-500 mb-2">
    <a href="{{ route('user.dashboard') }}" class="hover:text-[var(--color-primary)]">Home</a>
    <span class="mx-1">></span>
    <span>Mijn Abonnementen</span>
</div>
<h1 class="text-2xl font-semibold text-gray-900 mb-6">Mijn Abonnementen</h1>

<div class="space-y-6">
    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <i class="fas fa-check-circle text-green-600"></i>
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-600"></i>
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Info Text -->
    <p class="text-sm text-gray-600">
        Beheer uw zoekopdracht abonnementen. U ontvangt meldingen wanneer nieuwe documenten aan uw criteria voldoen.
    </p>

    <!-- Subscriptions List -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        @if($subscriptions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zoekopdracht</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Frequentie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Aangemaakt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($subscriptions as $subscription)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg bg-[var(--color-primary)]/10 flex items-center justify-center shrink-0">
                                    <i class="fas fa-bell text-[var(--color-primary)]"></i>
                                </div>
                                <div class="min-w-0">
                                    @if($subscription->search_query)
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        "{{ $subscription->search_query }}"
                                    </p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $subscription->formatted_filters }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $subscription->frequency_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                            {{ $subscription->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($subscription->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                Actief
                            </span>
                            @elseif(!$subscription->isVerified())
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span>
                                Niet geverifieerd
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>
                                Gepauzeerd
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('user.subscription.destroy', $subscription) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Weet u zeker dat u dit abonnement wilt verwijderen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-500 hover:text-red-600 p-2 transition-colors" title="Verwijderen">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($subscriptions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $subscriptions->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <div class="w-12 h-12 mx-auto mb-6 bg-[var(--color-primary)]/10 rounded-full flex items-center justify-center">
                <i class="fas fa-bell-slash text-[var(--color-primary)] text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Geen abonnementen</h3>
            <p class="text-sm text-gray-500 mb-6">U heeft nog geen zoekopdracht abonnementen.</p>
            <a href="{{ route('zoeken') }}" class="inline-flex items-center gap-2 bg-[var(--color-primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--color-primary-dark)] transition-colors font-semibold">
                <i class="fas fa-search"></i>
                Zoeken en abonneren
            </a>
        </div>
        @endif
    </div>

    <!-- How it works -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-3">Hoe werken abonnementen?</h3>
        <ul class="space-y-2 text-sm text-gray-700">
            <li class="flex items-start gap-2">
                <span class="w-5 h-5 rounded-full bg-[var(--color-primary)] text-white text-xs flex items-center justify-center shrink-0 mt-0.5">1</span>
                <span>Voer een zoekopdracht uit op de <a href="{{ route('zoeken') }}" class="text-[var(--color-primary)] underline hover:no-underline">zoekpagina</a>.</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="w-5 h-5 rounded-full bg-[var(--color-primary)] text-white text-xs flex items-center justify-center shrink-0 mt-0.5">2</span>
                <span>Klik op "Abonneren op deze zoekopdracht" en vul uw e-mailadres in.</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="w-5 h-5 rounded-full bg-[var(--color-primary)] text-white text-xs flex items-center justify-center shrink-0 mt-0.5">3</span>
                <span>Bevestig uw e-mailadres via de verificatielink in uw inbox.</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="w-5 h-5 rounded-full bg-[var(--color-primary)] text-white text-xs flex items-center justify-center shrink-0 mt-0.5">4</span>
                <span>Ontvang automatisch meldingen wanneer nieuwe documenten worden gepubliceerd.</span>
            </li>
        </ul>
    </div>
</div>
@endsection
