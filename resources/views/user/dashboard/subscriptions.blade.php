@extends('user.layouts.dashboard')

@section('dashboard-content')
<!-- Breadcrumb -->
<div class="text-sm text-gray-500 mb-2">Dashboard</div>
<h1 class="text-2xl font-semibold text-gray-900 mb-6">Mijn Abonnementen</h1>

<div class="space-y-6">
    <!-- Actions -->
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">Beheer uw zoekopdrachten en meldingen</p>
        <button class="inline-flex items-center gap-2 bg-[var(--color-primary)] text-white font-semibold px-4 py-2 rounded-md hover:bg-[var(--color-primary-dark)] transition-colors text-sm">
            <i class="fas fa-plus"></i>
            Nieuw abonnement
        </button>
    </div>

    <!-- Subscriptions List -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        @if(count($subscriptions) > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Naam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Aangemaakt</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nieuw</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($subscriptions as $subscription)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[var(--color-primary)]/10 flex items-center justify-center">
                                <i class="fas fa-bell text-[var(--color-primary)]"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $subscription['name'] }}</p>
                                <p class="text-xs text-gray-500 font-mono">{{ $subscription['query'] }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $subscription['type'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                        {{ $subscription['created_at']->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($subscription['new_count'] > 0)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            {{ $subscription['new_count'] }} nieuw
                        </span>
                        @else
                        <span class="text-sm text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button class="text-gray-500 hover:text-[var(--color-primary)] p-2 transition-colors" title="Bekijken">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-gray-500 hover:text-[var(--color-primary)] p-2 transition-colors" title="Bewerken">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-gray-500 hover:text-red-600 p-2 transition-colors" title="Verwijderen">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="text-center py-12">
            <div class="w-12 h-12 mx-auto mb-6 bg-[var(--color-primary)]/10 rounded-full flex items-center justify-center">
                <i class="fas fa-bell-slash text-[var(--color-primary)] text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Geen abonnementen</h3>
            <p class="text-sm text-gray-500 mb-6">U heeft nog geen zoekopdrachten opgeslagen.</p>
            <button class="inline-flex items-center gap-2 bg-[var(--color-primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--color-primary-dark)] transition-colors font-semibold">
                <i class="fas fa-plus"></i>
                Maak uw eerste abonnement
            </button>
        </div>
        @endif
    </div>
</div>
@endsection
