@extends('layouts.app')

@section('title', 'Open Overheid in cijfers - Rapportage')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'In cijfers', 'href' => null, 'current' => true],
    ];
@endphp

@section('content')
    <!-- Header Section -->
    <div class="bg-[var(--color-primary-light)] py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <!-- Breadcrumb -->
            @if(!empty($breadcrumbs ?? []))
            <div class="mb-8">
                <x-breadcrumbs :items="$breadcrumbs" />
            </div>
            @endif
            
            <div class="mx-auto max-w-2xl lg:mx-0">
                <h1 class="text-4xl font-semibold tracking-tight text-[var(--color-on-surface)] sm:text-5xl">Open Overheid in cijfers</h1>
                <p class="mt-6 text-lg font-medium text-pretty text-[var(--color-on-surface-variant)] sm:text-xl/8">
                    Op deze pagina zie je statistieken over actief openbaar gemaakte overheidsdocumenten. 
                    De data wordt regelmatig bijgewerkt en geeft inzicht in transparantie en openbaarheid.
                </p>
            </div>
            
            <!-- Minimal Filter -->
            <div class="mx-auto mt-8 max-w-2xl lg:mx-0">
                <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col sm:flex-row items-start sm:items-end gap-3">
                    <div class="flex-1 min-w-0">
                        <label for="jaar-select" class="block text-sm font-medium text-[var(--color-on-surface-variant)] mb-2">Jaar</label>
                        <select id="jaar-select" name="jaar" class="w-full px-3 py-2 rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)] text-sm text-[var(--color-on-surface)] focus:outline-none focus:border-[var(--color-primary)]">
                            @for($y = now()->year; $y >= now()->year - 5; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="flex-1 min-w-0">
                        <label for="kwartaal-select" class="block text-sm font-medium text-[var(--color-on-surface-variant)] mb-2">Kwartaal</label>
                        <select id="kwartaal-select" name="kwartaal" class="w-full px-3 py-2 rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)] text-sm text-[var(--color-on-surface)] focus:outline-none focus:border-[var(--color-primary)]">
                            <option value="">Hele jaar</option>
                            <option value="1" {{ $quarter == 1 ? 'selected' : '' }}>Q1</option>
                            <option value="2" {{ $quarter == 2 ? 'selected' : '' }}>Q2</option>
                            <option value="3" {{ $quarter == 3 ? 'selected' : '' }}>Q3</option>
                            <option value="4" {{ $quarter == 4 ? 'selected' : '' }}>Q4</option>
                        </select>
                    </div>
                    <div class="sm:pt-6">
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-[var(--color-primary)] text-[var(--color-on-primary)] font-medium rounded-md hover:bg-[var(--color-primary-dark)] transition-colors duration-200 focus:outline-none text-sm whitespace-nowrap">
                            Toepassen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                        <div class="w-10 h-10 rounded-md bg-[var(--color-primary)]/10 flex items-center justify-center shrink-0">
                            <i class="fas fa-check-circle text-[var(--color-primary)] text-base" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-base font-semibold text-[var(--color-on-surface)]">Afgehandeld</h3>
                    </div>
                    <p class="text-3xl font-bold text-[var(--color-primary)] mb-1">
                        {{ number_format($documentsWithDecision, 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-[var(--color-on-surface-variant)]/70">
                        Met publicatiedatum
                    </p>
                </div>

                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-md bg-[var(--color-primary)]/10 flex items-center justify-center shrink-0">
                            <i class="fas fa-clock text-[var(--color-primary)] text-base" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-base font-semibold text-[var(--color-on-surface)]">In behandeling</h3>
                    </div>
                    <p class="text-3xl font-bold text-[var(--color-primary)] mb-1">
                        {{ number_format($documentsInProgress, 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-[var(--color-on-surface-variant)]/70">
                        Recent gepubliceerd
                    </p>
                </div>

                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-md bg-[var(--color-primary)]/10 flex items-center justify-center shrink-0">
                            <i class="fas fa-chart-line text-[var(--color-primary)] text-base" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-base font-semibold text-[var(--color-on-surface)]">Gemiddelde tijd</h3>
                    </div>
                    <p class="text-3xl font-bold text-[var(--color-primary)] mb-1">
                        {{ $avgProcessingDays }} dagen
                    </p>
                    <p class="text-sm text-[var(--color-on-surface-variant)]/70">
                        Verwerkingstijd
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
                                <p class="text-sm font-medium text-[var(--color-on-surface)] mb-1.5 truncate">{{ $item['organisation'] }}</p>
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
                        <button class="text-sm text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] font-medium focus:outline-none transition-colors duration-200">
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
                                <p class="text-sm font-medium text-[var(--color-on-surface)] mb-1.5 truncate">{{ $formattedCategory }}</p>
                                <div class="w-full bg-[var(--color-outline-variant)]/30 rounded-full h-2">
                                    <div class="bg-[var(--color-primary)] h-2 rounded-full transition-all duration-700" style="width: {{ $maxCategoryCount > 0 ? min(($item['count'] / $maxCategoryCount) * 100, 100) : 0 }}%"></div>
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
                    @foreach($monthlyTrend as $month)
                    <div class="text-center">
                        <p class="text-xs text-[var(--color-on-surface-variant)]/70 mb-2">{{ $month['monthName'] }}</p>
                        <div class="bg-[var(--color-primary)]/10 border border-[var(--color-primary)]/20 rounded-md p-3">
                            <p class="text-lg font-bold text-[var(--color-primary)]">{{ number_format($month['count'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
