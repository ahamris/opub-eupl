@extends('layouts.app')

@section('title', 'Open Overheid in cijfers - Rapportage')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'In cijfers', 'href' => null, 'current' => true],
    ];
@endphp

@section('content')
    <!-- Hero Section -->
    <div class="relative isolate overflow-hidden bg-gradient-to-br from-[var(--color-primary)] via-[var(--color-primary-dark)] to-[var(--color-primary)]">
        <div class="absolute inset-0 -z-10 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        
        <div class="mx-auto max-w-7xl px-10 lg:px-10 py-16 lg:py-24">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8">
                <div class="flex-1">
                    <h1 class="text-[var(--font-size-display-large)] font-bold tracking-[-0.02em] text-[var(--color-on-primary)] mb-6">
                        Open Overheid in cijfers
                    </h1>
                    <p class="text-[var(--font-size-body-large)] text-[var(--color-on-primary)]/90 leading-relaxed max-w-2xl mb-6">
                        Op deze pagina zie je statistieken over actief openbaar gemaakte overheidsdocumenten. 
                        De data wordt regelmatig bijgewerkt en geeft inzicht in transparantie en openbaarheid.
                    </p>
                    <div class="flex flex-wrap gap-4 mt-6">
                        <div class="bg-[var(--color-on-primary)]/10 backdrop-blur-sm px-4 py-2 rounded-sm border border-on-primary/20">
                            <p class="text-[var(--font-size-label-small)] text-[var(--color-on-primary)]/80 mb-1">Getoonde periode</p>
                            <p class="text-[var(--font-size-body-medium)] font-semibold text-[var(--color-on-primary)]">
                                {{ $startDate->format('d F Y') }} t/m {{ $endDate->format('d F Y') }}
                            </p>
                        </div>
                        <div class="bg-[var(--color-on-primary)]/10 backdrop-blur-sm px-4 py-2 rounded-sm border border-on-primary/20">
                            <p class="text-[var(--font-size-label-small)] text-[var(--color-on-primary)]/80 mb-1">Peildatum</p>
                            <p class="text-[var(--font-size-body-medium)] font-semibold text-[var(--color-on-primary)]">
                                {{ now()->format('d F Y') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-auto lg:ml-auto">
                    <div class="bg-[var(--color-on-primary)]/10 backdrop-blur-sm rounded-sm p-6 border border-on-primary/20">
                        <label class="block text-[var(--font-size-label-medium)] font-semibold text-[var(--color-on-primary)] mb-3">Filter periode</label>
                        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col sm:flex-row gap-3">
                            <select name="jaar" class="px-4 py-2 rounded-sm bg-[var(--color-on-primary)]/20 text-[var(--color-on-primary)] border border-on-primary/30 focus:ring-2 focus:ring-on-primary/50 focus:border-on-primary/50">
                                @for($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                            <select name="kwartaal" class="px-4 py-2 rounded-sm bg-[var(--color-on-primary)]/20 text-[var(--color-on-primary)] border border-on-primary/30 focus:ring-2 focus:ring-on-primary/50 focus:border-on-primary/50">
                                <option value="">Hele jaar</option>
                                <option value="1" {{ $quarter == 1 ? 'selected' : '' }}>Q1</option>
                                <option value="2" {{ $quarter == 2 ? 'selected' : '' }}>Q2</option>
                                <option value="3" {{ $quarter == 3 ? 'selected' : '' }}>Q3</option>
                                <option value="4" {{ $quarter == 4 ? 'selected' : '' }}>Q4</option>
                            </select>
                            <button type="submit" class="px-6 py-2 bg-[var(--color-on-primary)] text-[var(--color-primary)] font-semibold rounded-sm hover:bg-[var(--color-on-primary)]/90 transition-colors duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2">
                                Toepassen
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Statistics Section -->
    <div class="bg-[var(--color-surface)] py-16">
        <div class="mx-auto max-w-7xl px-10 lg:px-10">
            <!-- Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)]/40 rounded-sm p-8 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-sm bg-[var(--color-primary)]/10 flex items-center justify-center">
                            <i class="fas fa-file-alt text-[var(--color-primary)] text-lg" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-[var(--font-size-headline-medium)] font-semibold text-[var(--color-on-surface)]">Totaal documenten</h3>
                    </div>
                    <p class="text-[var(--font-size-display-large)] font-bold text-[var(--color-primary)] mb-2">
                        {{ number_format($totalDocuments, 0, ',', '.') }}
                    </p>
                    <p class="text-[var(--font-size-body-small)] text-[var(--color-on-surface)]-variant/70">
                        In geselecteerde periode
                    </p>
                </div>

                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)]/40 rounded-sm p-8 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-sm bg-green-500/10 flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-lg" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-[var(--font-size-headline-medium)] font-semibold text-[var(--color-on-surface)]">Afgehandeld</h3>
                    </div>
                    <p class="text-[var(--font-size-display-large)] font-bold text-green-600 mb-2">
                        {{ number_format($documentsWithDecision, 0, ',', '.') }}
                    </p>
                    <p class="text-[var(--font-size-body-small)] text-[var(--color-on-surface)]-variant/70">
                        Met publicatiedatum
                    </p>
                </div>

                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)]/40 rounded-sm p-8 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-sm bg-orange-500/10 flex items-center justify-center">
                            <i class="fas fa-clock text-orange-600 text-lg" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-[var(--font-size-headline-medium)] font-semibold text-[var(--color-on-surface)]">In behandeling</h3>
                    </div>
                    <p class="text-[var(--font-size-display-large)] font-bold text-orange-600 mb-2">
                        {{ number_format($documentsInProgress, 0, ',', '.') }}
                    </p>
                    <p class="text-[var(--font-size-body-small)] text-[var(--color-on-surface)]-variant/70">
                        Recent gepubliceerd
                    </p>
                </div>

                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)]/40 rounded-sm p-8 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-sm bg-blue-500/10 flex items-center justify-center">
                            <i class="fas fa-chart-line text-blue-600 text-lg" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-[var(--font-size-headline-medium)] font-semibold text-[var(--color-on-surface)]">Gemiddelde tijd</h3>
                    </div>
                    <p class="text-[var(--font-size-display-large)] font-bold text-blue-600 mb-2">
                        {{ $avgProcessingDays }} dagen
                    </p>
                    <p class="text-[var(--font-size-body-small)] text-[var(--color-on-surface)]-variant/70">
                        Verwerkingstijd
                    </p>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <!-- Documents per Organisation -->
                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)]/40 rounded-sm p-8 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[var(--font-size-headline-large)] font-bold text-[var(--color-on-surface)]">Documenten per organisatie</h2>
                        <button class="text-[var(--color-primary)] hover:text-[var(--color-primary)]-dark text-[var(--font-size-body-small)] font-medium">
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
                                <p class="text-[var(--font-size-body-medium)] font-medium text-[var(--color-on-surface)] mb-1 truncate">{{ $item['organisation'] }}</p>
                                <div class="w-full bg-[var(--color-outline-variant)]/30 rounded-full h-2">
                                    <div class="bg-[var(--color-primary)] h-2 rounded-full transition-all duration-700" style="width: {{ $maxOrgCount > 0 ? min(($item['count'] / $maxOrgCount) * 100, 100) : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-[var(--font-size-headline-small)] font-bold text-[var(--color-primary)]">{{ number_format($item['count'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-[var(--font-size-body-medium)] text-[var(--color-on-surface)]-variant/70">Geen gegevens beschikbaar voor deze periode.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Documents per Category -->
                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)]/40 rounded-sm p-8 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[var(--font-size-headline-large)] font-bold text-[var(--color-on-surface)]">Documenten per categorie</h2>
                        <button class="text-[var(--color-primary)] hover:text-[var(--color-primary)]-dark text-[var(--font-size-body-small)] font-medium">
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
                                <p class="text-[var(--font-size-body-medium)] font-medium text-[var(--color-on-surface)] mb-1 truncate">{{ $formattedCategory }}</p>
                                <div class="w-full bg-[var(--color-outline-variant)]/30 rounded-full h-2">
                                    <div class="bg-[var(--color-primary)] h-2 rounded-full transition-all duration-700" style="width: {{ $maxCategoryCount > 0 ? min(($item['count'] / $maxCategoryCount) * 100, 100) : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-[var(--font-size-headline-small)] font-bold text-[var(--color-primary)]">{{ number_format($item['count'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-[var(--font-size-body-medium)] text-[var(--color-on-surface)]-variant/70">Geen gegevens beschikbaar voor deze periode.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Monthly Trend -->
            @if(!empty($monthlyTrend))
            <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)]/40 rounded-sm p-8 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-[var(--font-size-headline-large)] font-bold text-[var(--color-on-surface)]">Maandelijkse trend</h2>
                    <button class="text-[var(--color-primary)] hover:text-[var(--color-primary)]-dark text-[var(--font-size-body-small)] font-medium">
                        Toon datatabel
                    </button>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-12 gap-4">
                    @foreach($monthlyTrend as $month)
                    <div class="text-center">
                        <p class="text-[var(--font-size-label-small)] text-[var(--color-on-surface)]-variant/70 mb-2">{{ $month['monthName'] }}</p>
                        <div class="bg-[var(--color-primary)]/10 border border-primary/20 rounded-sm p-3 hover:bg-[var(--color-primary)]/15 transition-colors duration-200">
                            <p class="text-[var(--font-size-headline-small)] font-bold text-[var(--color-primary)]">{{ number_format($month['count'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
