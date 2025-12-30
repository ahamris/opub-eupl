@extends('user.layouts.dashboard')

@section('dashboard-content')
<!-- Breadcrumb -->
<div class="text-sm text-gray-500 mb-2">
    <a href="{{ route('user.dashboard') }}" class="hover:text-[var(--color-primary)]">Home</a>
    <span class="mx-1">></span>
    <a href="{{ route('user.berichtenbox') }}" class="hover:text-[var(--color-primary)]">Berichtenbox</a>
    <span class="mx-1">></span>
    <span>Bericht</span>
</div>

<!-- Back Button -->
<div class="mb-4">
    <a href="{{ route('user.berichtenbox') }}" class="inline-flex items-center gap-2 text-[var(--color-primary)] font-semibold text-sm hover:underline">
        <i class="fas fa-arrow-left"></i>
        Terug naar Berichtenbox
    </a>
</div>

<!-- Message Card -->
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <!-- Message Header -->
    <div class="border-b border-gray-200 px-6 py-4">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900 mb-2">{{ $bericht['onderwerp'] }}</h1>
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-building"></i>
                        {{ $bericht['afzender'] }}
                    </span>
                    <span class="flex items-center gap-2">
                        <i class="far fa-calendar-alt"></i>
                        {{ $bericht['ontvangen']->format('d F Y') }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button class="p-2 text-gray-500 hover:text-[var(--color-primary)] hover:bg-gray-100 rounded-md transition-colors" title="Archiveren">
                    <i class="fas fa-archive"></i>
                </button>
                <button class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-md transition-colors" title="Verwijderen">
                    <i class="fas fa-trash"></i>
                </button>
                <button class="p-2 text-gray-500 hover:text-[var(--color-primary)] hover:bg-gray-100 rounded-md transition-colors" title="Afdrukken">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Message Content -->
    <div class="px-6 py-6">
        <div class="prose prose-sm max-w-none text-gray-700">
            {!! $bericht['inhoud'] !!}
        </div>
    </div>

    <!-- Message Footer -->
    <div class="border-t border-gray-200 px-6 py-4 bg-gray-50">
        <div class="flex items-center justify-between">
            <a href="{{ route('user.berichtenbox') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium text-sm">
                <i class="fas fa-arrow-left"></i>
                Terug naar overzicht
            </a>
            <div class="flex items-center gap-3">
                <button class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fas fa-archive"></i>
                    Archiveren
                </button>
                <button class="inline-flex items-center gap-2 px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-colors">
                    <i class="fas fa-trash"></i>
                    Verwijderen
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Related Actions -->
<div class="mt-8">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Acties bij dit bericht</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-4 hover:bg-gray-50 transition-colors">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-[var(--color-primary)]/10 flex items-center justify-center shrink-0">
                    <i class="fas fa-download text-[var(--color-primary)]"></i>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900 mb-1">Download als PDF</h3>
                    <p class="text-sm text-gray-500">Sla dit bericht op als PDF-bestand</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4 hover:bg-gray-50 transition-colors">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-[var(--color-primary)]/10 flex items-center justify-center shrink-0">
                    <i class="fas fa-question-circle text-[var(--color-primary)]"></i>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900 mb-1">Vraag over dit bericht</h3>
                    <p class="text-sm text-gray-500">Neem contact op met {{ $bericht['afzender'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Info Notice -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg px-4 py-3">
    <div class="flex items-start gap-3">
        <i class="fas fa-info-circle text-[var(--color-primary)] mt-0.5"></i>
        <p class="text-sm text-gray-700">
            Dit bericht is verzonden door <strong>{{ $bericht['afzender'] }}</strong>. Voor vragen over de inhoud kunt u rechtstreeks contact opnemen met deze organisatie.
        </p>
    </div>
</div>
@endsection
