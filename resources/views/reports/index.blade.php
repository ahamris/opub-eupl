@extends('layouts.app')

@section('title', 'Open Overheid in cijfers - Rapportage')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'In cijfers', 'href' => null, 'current' => true],
    ];
@endphp

@section('content')
    <!-- Premium Page Header Section with Filter -->
    <div class="relative bg-gradient-to-b from-slate-50 to-white overflow-hidden">
        <!-- Subtle grid pattern -->
        <div class="absolute inset-0 -z-10">
            <svg class="absolute inset-0 h-full w-full stroke-slate-200/50" fill="none">
                <defs>
                    <pattern id="reports-header-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M.5 40V.5H40" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#reports-header-grid)" />
            </svg>
            <!-- Fade overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
        </div>
        
        <!-- Animated floating squares -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-8 right-[15%] w-16 h-16 rounded-md bg-[var(--color-purple)]/[0.04] animate-[float-slow_6s_ease-in-out_infinite]"></div>
            <div class="absolute top-16 left-[10%] w-12 h-12 rounded-md bg-[var(--color-primary)]/[0.03] animate-[float-slower_8s_ease-in-out_infinite]"></div>
            <div class="absolute top-1/2 right-[8%] w-20 h-20 rounded-md bg-[var(--color-purple)]/[0.05] animate-[float-slow_6s_ease-in-out_infinite_-2s]"></div>
            <div class="absolute bottom-12 left-[20%] w-14 h-14 rounded-md bg-[var(--color-primary)]/[0.04] animate-[float-slower_8s_ease-in-out_infinite_-3s]"></div>
            <div class="absolute top-12 right-[35%] w-10 h-10 rounded-md bg-[var(--color-purple)]/[0.03] animate-[float-slow_6s_ease-in-out_infinite_-1s]"></div>
        </div>
        
        <div class="mx-auto max-w-7xl px-6 lg:px-8 py-12 sm:py-16 relative z-10">
            <!-- Breadcrumb -->
            @if(!empty($breadcrumbs))
            <div class="mb-6">
                <x-breadcrumbs :items="$breadcrumbs" />
            </div>
            @endif
            
            <div class="mx-auto max-w-2xl lg:mx-0">
                <p class="text-sm font-medium uppercase">Statistieken & Rapportage</p>
                <h1 class="mt-2 font-semibold">Open Overheid in cijfers</h1>
                <p class="mt-4 text-base text-[var(--color-on-surface-variant)] leading-relaxed">
                    Op deze pagina zie je statistieken over actief openbaar gemaakte overheidsdocumenten.
                </p>
            </div>
            
            <!-- Filter Form -->
            <div class="mx-auto mt-8 w-full">
                <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col lg:flex-row items-end gap-4 w-full">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 w-full">
                        <div class="w-full">
                            <label for="jaar-select" class="block text-sm font-medium text-[var(--color-on-surface-variant)] mb-2">Jaar</label>
                            <select id="jaar-select" name="jaar" class="w-full px-3 py-2 rounded-md border border-slate-200 bg-white text-sm text-[var(--color-on-surface)] focus:outline-none focus:border-[var(--color-primary)] focus:ring-1 focus:ring-[var(--color-primary)]">
                                @for($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="w-full">
                            <label for="kwartaal-select" class="block text-sm font-medium text-[var(--color-on-surface-variant)] mb-2">Kwartaal</label>
                            <select id="kwartaal-select" name="kwartaal" class="w-full px-3 py-2 rounded-md border border-slate-200 bg-white text-sm text-[var(--color-on-surface)] focus:outline-none focus:border-[var(--color-primary)] focus:ring-1 focus:ring-[var(--color-primary)]">
                                <option value="">Hele jaar</option>
                                <option value="1" {{ $quarter == 1 ? 'selected' : '' }}>Q1</option>
                                <option value="2" {{ $quarter == 2 ? 'selected' : '' }}>Q2</option>
                                <option value="3" {{ $quarter == 3 ? 'selected' : '' }}>Q3</option>
                                <option value="4" {{ $quarter == 4 ? 'selected' : '' }}>Q4</option>
                            </select>
                        </div>
                        <div class="w-full">
                            <label for="organisatie-select" class="block text-sm font-medium text-[var(--color-on-surface-variant)] mb-2">Organisatie</label>
                            <select id="organisatie-select" name="organisatie" class="w-full px-3 py-2 rounded-md border border-slate-200 bg-white text-sm text-[var(--color-on-surface)] focus:outline-none focus:border-[var(--color-primary)] focus:ring-1 focus:ring-[var(--color-primary)]">
                                <option value="">Alle organisaties</option>
                                @foreach($allOrganisations ?? [] as $org)
                                    <option value="{{ $org }}" {{ $selectedOrganisation == $org ? 'selected' : '' }}>{{ $org }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full">
                            <label for="informatiecategorie-select" class="block text-sm font-medium text-[var(--color-on-surface-variant)] mb-2">Categorie</label>
                            <select id="informatiecategorie-select" name="informatiecategorie" class="w-full px-3 py-2 rounded-md border border-slate-200 bg-white text-sm text-[var(--color-on-surface)] focus:outline-none focus:border-[var(--color-primary)] focus:ring-1 focus:ring-[var(--color-primary)]">
                                <option value="">Alle categorieën</option>
                                @foreach($allCategories ?? [] as $cat)
                                    <option value="{{ $cat['value'] }}" {{ $selectedCategory == $cat['value'] ? 'selected' : '' }}>{{ $cat['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="w-full lg:w-auto">
                        <button type="submit" class="w-full lg:w-auto px-6 py-2 bg-[var(--color-primary-dark)] text-white font-medium rounded-md hover:bg-[var(--color-primary)] transition-colors duration-200 focus:outline-none text-sm whitespace-nowrap shadow-sm">
                            Toepassen
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Bottom gradient line -->
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
    </div>
    
    <style>
        @keyframes float-slow { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-12px) rotate(3deg); } }
        @keyframes float-slower { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-8px) rotate(-2deg); } }
    </style>

    <!-- Main Statistics Section -->
    <div class="bg-[var(--color-surface)] py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <!-- Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-md bg-[var(--color-primary)]/10 flex items-center justify-center shrink-0">
                            <i class="fas fa-file-alt text-[var(--color-primary)] text-base" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-base font-semibold text-[var(--color-on-surface)]">Totaal documenten</h3>
                    </div>
                    <p class="text-3xl font-bold text-[var(--color-primary)] mb-1">
                        {{ number_format($totalDocuments, 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-[var(--color-on-surface-variant)]/70">
                        In geselecteerde periode
                    </p>
                </div>

                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-md bg-[var(--color-primary-dark)]/10 flex items-center justify-center shrink-0">
                            <i class="fas fa-check-circle text-[var(--color-primary-dark)] text-base" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-base font-semibold text-[var(--color-on-surface)]">Afgehandeld</h3>
                    </div>
                    <p class="text-3xl font-bold text-[var(--color-primary-dark)] mb-1">
                        {{ number_format($documentsWithDecision, 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-[var(--color-on-surface-variant)]/70">
                        Met publicatiedatum
                    </p>
                </div>

                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6 opacity-60 cursor-not-allowed relative" title="Deze functie komt binnenkort beschikbaar">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-md bg-[var(--color-outline-variant)]/30 flex items-center justify-center shrink-0">
                            <i class="fas fa-clock text-[var(--color-on-surface-variant)]/50 text-base" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-base font-semibold text-[var(--color-on-surface)]/60">In behandeling</h3>
                    </div>
                    <p class="text-3xl font-bold text-[var(--color-on-surface-variant)]/50 mb-1">
                        —
                    </p>
                    <p class="text-sm text-[var(--color-on-surface-variant)]/50">
                        Binnenkort beschikbaar
                    </p>
                </div>

                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6 opacity-60 cursor-not-allowed relative" title="Deze functie komt binnenkort beschikbaar">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-md bg-[var(--color-outline-variant)]/30 flex items-center justify-center shrink-0">
                            <i class="fas fa-chart-line text-[var(--color-on-surface-variant)]/50 text-base" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-base font-semibold text-[var(--color-on-surface)]/60">Gemiddelde tijd</h3>
                    </div>
                    <p class="text-3xl font-bold text-[var(--color-on-surface-variant)]/50 mb-1">
                        —
                    </p>
                    <p class="text-sm text-[var(--color-on-surface-variant)]/50">
                        Binnenkort beschikbaar
                    </p>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">
                <!-- Documents per Organisation -->
                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-[var(--color-on-surface)]">Documenten per organisatie</h2>
                        <button class="text-sm text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] font-medium focus:outline-none transition-colors duration-200">
                            Toon datatabel
                        </button>
                    </div>
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @php
                            $maxOrgCount = !empty($documentsPerOrganisation) ? max(array_column($documentsPerOrganisation, 'count')) : 0;
                        @endphp
                        @forelse(array_slice($documentsPerOrganisation, 0, 10) as $item)
                        <div class="flex items-center gap-4">
                            <div class="flex-1 min-w-0">
                                @php
                                    $orgUrl = route('zoeken') . '?organisatie[]=' . urlencode($item['organisation']);
                                @endphp
                                <a href="{{ $orgUrl }}" class="text-sm font-medium text-[var(--color-on-surface)] mb-1.5 truncate hover:text-[var(--color-primary)] transition-colors duration-200 block">
                                    {{ $item['organisation'] }}
                                </a>
                                <div class="w-full bg-[var(--color-outline-variant)]/30 rounded-full h-2">
                                    <div class="bg-[var(--color-primary)] h-2 rounded-full transition-all duration-700" style="width: {{ $maxOrgCount > 0 ? min(($item['count'] / $maxOrgCount) * 100, 100) : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-lg font-bold text-[var(--color-primary)]">{{ number_format($item['count'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-[var(--color-on-surface-variant)]/70">Geen gegevens beschikbaar voor deze periode.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Documents per Category -->
                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-[var(--color-on-surface)]">Documenten per categorie</h2>
                        <button class="text-sm text-[var(--color-primary-dark)] hover:text-[var(--color-primary)] font-medium focus:outline-none transition-colors duration-200">
                            Toon datatabel
                        </button>
                    </div>
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @php
                            $wooCategoryService = app(\App\Services\OpenOverheid\WooCategoryService::class);
                            $maxCategoryCount = !empty($documentsPerCategory) ? max(array_column($documentsPerCategory, 'count')) : 0;
                        @endphp
                        @forelse($documentsPerCategory as $item)
                            @php
                                $formattedCategory = $wooCategoryService->formatCategoryForDisplay($item['category']) ?? $item['category'];
                            @endphp
                        <div class="flex items-center gap-4">
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('zoeken') }}?informatiecategorie={{ urlencode($item['category']) }}" class="text-sm font-medium text-[var(--color-on-surface)] mb-1.5 truncate hover:text-[var(--color-primary-dark)] transition-colors duration-200 block">
                                    {{ $formattedCategory }}
                                </a>
                                <div class="w-full bg-[var(--color-outline-variant)]/30 rounded-full h-2">
                                    <div class="bg-[var(--color-primary-dark)] h-2 rounded-full transition-all duration-700" style="width: {{ $maxCategoryCount > 0 ? min(($item['count'] / $maxCategoryCount) * 100, 100) : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-lg font-bold text-[var(--color-primary-dark)]">{{ number_format($item['count'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-[var(--color-on-surface-variant)]/70">Geen gegevens beschikbaar voor deze periode.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Monthly Trend -->
            @if(!empty($monthlyTrend))
            <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-[var(--color-on-surface)]">Maandelijkse trend</h2>
                    <button class="text-sm text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] font-medium focus:outline-none transition-colors duration-200">
                        Toon datatabel
                    </button>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-12 gap-4">
                    @foreach($monthlyTrend as $index => $month)
                    <div class="text-center">
                        <p class="text-xs text-[var(--color-on-surface-variant)]/70 mb-2">{{ $month['monthName'] }}</p>
                        <div class="{{ $index % 2 === 0 ? 'bg-[var(--color-primary)]/10 border border-[var(--color-primary)]/20' : 'bg-[var(--color-primary-dark)]/10 border border-[var(--color-primary-dark)]/20' }} rounded-md p-3">
                            <p class="text-lg font-bold {{ $index % 2 === 0 ? 'text-[var(--color-primary)]' : 'text-[var(--color-primary-dark)]' }}">{{ number_format($month['count'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
