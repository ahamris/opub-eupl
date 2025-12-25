@extends('user.layouts.dashboard')

@section('dashboard-content')
<!-- Breadcrumb -->
<div class="text-sm text-gray-500 mb-2">Dashboard</div>
<h1 class="text-2xl font-semibold text-gray-900 mb-6">Home</h1>

<div class="space-y-6">
    <!-- Recent Activity -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
            <h3 class="text-base font-semibold text-gray-900">Recente Activiteit</h3>
            <p class="mt-1 text-sm text-gray-500">Uw laatste meldingen en updates</p>
        </div>
        
        @if(count($recentActivity) > 0)
        <div class="divide-y divide-gray-200">
            @foreach($recentActivity as $activity)
            <div class="px-4 py-4 sm:px-6 hover:bg-gray-50 transition-colors cursor-pointer">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-bell text-[var(--color-primary)]"></i>
                            <span class="font-mono text-sm font-semibold text-[var(--color-primary)]">{{ $activity['id'] }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $activity['title'] }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $activity['description'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ $activity['status_color'] === 'green' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $activity['status_color'] === 'green' ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                            {{ $activity['status'] }}
                        </span>
                        <div class="text-right hidden sm:block">
                            <div class="text-sm text-gray-900">{{ $activity['date']->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $activity['date']->format('H:i') }}</div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="px-4 py-4 text-right border-t border-gray-100">
            <a href="{{ route('user.subscriptions') }}" class="inline-flex items-center gap-2 bg-[var(--color-primary)] text-white font-semibold px-3 py-2 rounded-md hover:bg-[var(--color-primary-dark)] transition-colors text-sm">
                <i class="fas fa-list"></i>
                Bekijk alle meldingen
                <i class="fas fa-chevron-right ml-1"></i>
            </a>
        </div>
        @else
        <div class="text-center py-12">
            <div class="w-12 h-12 mx-auto mb-6 bg-[var(--color-primary)]/10 rounded-full flex items-center justify-center">
                <i class="fas fa-bell text-[var(--color-primary)] text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Geen recente activiteit</h3>
            <p class="text-sm text-gray-500 mb-6">Maak een abonnement aan om meldingen te ontvangen.</p>
            <a href="{{ route('user.subscriptions') }}" class="inline-flex items-center gap-2 bg-[var(--color-primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--color-primary-dark)] transition-colors font-semibold">
                <i class="fas fa-plus"></i>
                Nieuw abonnement
            </a>
        </div>
        @endif
    </div>

    <!-- FAQ -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
            <h3 class="text-base font-semibold text-gray-900">Veelgestelde vragen</h3>
            <p class="mt-1 text-sm text-gray-500">Frequently asked questions</p>
        </div>
        <div class="px-4 py-4 sm:px-6 space-y-2">
            @foreach($faqs as $faq)
            <details class="group">
                <summary class="flex cursor-pointer list-none items-center justify-between bg-gray-50 p-3 font-medium text-[var(--color-primary)] hover:bg-gray-100 rounded-md transition-colors border border-gray-200">
                    <span>{{ $faq }}</span>
                    <i class="fas fa-chevron-right transition-transform duration-200 group-open:rotate-90 text-gray-500"></i>
                </summary>
                <div class="p-4 bg-white rounded-lg mt-2 border border-gray-100">
                    <p class="text-sm text-gray-600">Dit is een placeholder antwoord. Voeg hier de echte inhoud toe.</p>
                </div>
            </details>
            @endforeach
            
            <div class="py-4 text-right">
                <a href="#" class="inline-flex items-center gap-2 text-[var(--color-primary)] font-semibold">
                    <span class="underline hover:no-underline">Bekijk alle veelgestelde vragen</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('info-panel')
<x-user.info-box 
    title="Informatie" 
    text="Hier vindt u nuttige informatie en tips over het gebruik van uw dashboard."
>
    <ul class="mt-4 space-y-2 text-sm text-gray-600">
        <li class="flex items-start gap-2">
            <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
            <span>Maak abonnementen om updates te ontvangen</span>
        </li>
        <li class="flex items-start gap-2">
            <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
            <span>Bekijk uw meldingen in het overzicht</span>
        </li>
        <li class="flex items-start gap-2">
            <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
            <span>Pas uw voorkeuren aan in instellingen</span>
        </li>
    </ul>
</x-user.info-box>
@endsection
