@extends('layouts.app')

@section('title', 'Zoekresultaten - Open Overheid')

@php
    $searchQuery = request('zoeken');
    
    // Helper function to highlight search terms
    if (!function_exists('highlightSearchTerms')) {
        function highlightSearchTerms($text, $query) {
            if (empty($query) || empty($text)) {
                return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            }
            
            $terms = array_filter(explode(' ', trim($query)), fn($term) => strlen($term) > 2);
            $highlighted = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            
            foreach ($terms as $term) {
                $pattern = '/' . preg_quote(htmlspecialchars($term, ENT_QUOTES, 'UTF-8'), '/') . '/i';
                $highlighted = preg_replace($pattern, '<mark class="bg-yellow-200 dark:bg-yellow-800 px-0.5 rounded">$0</mark>', $highlighted);
            }
            
            return $highlighted;
        }
    }

    // Breadcrumbs: used by layouts.app global breadcrumb component
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        isset($isDossier) && $isDossier
            ? ['label' => 'Dossiers', 'href' => null, 'current' => true]
            : ['label' => 'Zoekresultaten', 'href' => null, 'current' => true],
    ];
@endphp

@section('content')
    <!-- Premium Page Header Section -->
    <div class="relative bg-gradient-to-b from-slate-50 to-white overflow-hidden">
        <!-- Subtle grid pattern -->
        <div class="absolute inset-0 -z-10">
            <svg class="absolute inset-0 h-full w-full stroke-slate-200/50" fill="none">
                <defs>
                    <pattern id="zoekresultaten-header-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M.5 40V.5H40" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#zoekresultaten-header-grid)" />
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
                @if(isset($isDossier) && $isDossier)
                    <p class="text-sm font-medium uppercase">Verken complete dossiers</p>
                    <h1 class="mt-2 font-semibold">Dossiers</h1>
                    <p class="mt-4 text-base text-[var(--color-on-surface-variant)] leading-relaxed">
                        Verken complete dossiers met alle bijbehorende documenten en verbanden.
                    </p>
                @else
                    <p class="text-sm font-medium uppercase">Zoek in overheidsdocumenten</p>
                    <h1 class="mt-2 font-semibold">Uitgebreid zoeken</h1>
                    <p class="mt-4 text-base text-[var(--color-on-surface-variant)]">
                        Gebruik filters op datum, organisatie, categorie of thema om precies te vinden wat je zoekt.
                    </p>
                @endif
            </div>
        </div>
        
        <!-- Bottom gradient line -->
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
    </div>
    
    <style>
        @keyframes float-slow { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-12px) rotate(3deg); } }
        @keyframes float-slower { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-8px) rotate(-2deg); } }
    </style>
    
    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-6 lg:px-8 pt-10 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-[300px_1fr] gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:sticky lg:top-[88px] h-fit lg:z-40" aria-label="Zoekfilters" x-data="{ mobileFiltersOpen: false }">
                <!-- Mobile Toggle Button -->
                <button 
                    @click="mobileFiltersOpen = !mobileFiltersOpen" 
                    class="lg:hidden w-full mb-4 flex items-center justify-between bg-[var(--color-surface)] p-4 rounded-md border border-[var(--color-outline-variant)] hover:bg-[var(--color-surface-variant)] transition-colors"
                    type="button"
                >
                    <span class="font-semibold text-[var(--color-on-surface)] flex items-center gap-2">
                        <i class="fas fa-filter text-[var(--color-primary)]"></i>
                        Filters & Verfijnen
                    </span>
                    <i class="fas" :class="mobileFiltersOpen ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>

                <div class="bg-[var(--color-surface)] rounded-md p-6 border border-[var(--color-outline-variant)] hidden lg:block" :class="{'!block': mobileFiltersOpen}">
                    <h2 class="text-base font-semibold mb-6 text-[var(--color-on-surface)] pb-4 border-b border-[var(--color-outline-variant)]">
                        Verfijn zoekopdracht
                    </h2>
                    
                    <form action="{{ isset($isDossier) ? route('dossiers.index') : route('zoeken') }}" method="GET" id="filter-form" class="space-y-6" onsubmit="convertDateInputsToFormat(event)">
                        <input type="hidden" name="sort" id="hidden-sort" value="{{ request('sort', 'relevance') }}">
                        <input type="hidden" name="per_page" id="hidden-per-page" value="{{ request('per_page', 20) }}">
                        @if(request('zoeken'))
                            <input type="hidden" name="zoeken" value="{{ request('zoeken') }}">
                        @endif
                        @if(request('titles_only'))
                            <input type="hidden" name="titles_only" value="1">
                        @endif
                        
                        <!-- Publication Destination (Source) Filter - TOP PRIORITY -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface)]">Bron</h3>
                            <div class="space-y-2">
                                @php
                                    $allSources = $allFilterOptions['publicatiebestemming'] ?? ['rijksoverheid.nl'];
                                    $selectedSources = (array)request('publicatiebestemming', []);
                                @endphp
                                @foreach($allSources as $source)
                                    <div class="flex items-center gap-3">
                                        <input 
                                            type="checkbox" 
                                            id="bron-{{ str_replace(['.', ' '], ['-', '-'], strtolower($source)) }}" 
                                            name="publicatiebestemming[]" 
                                            value="{{ $source }}"
                                            {{ in_array($source, $selectedSources) ? 'checked' : '' }}
                                            onchange="document.getElementById('filter-form').submit()"
                                            class="w-4 h-4 rounded border border-[var(--color-outline)] 
                                                   focus:outline-none
                                                   cursor-pointer text-[var(--color-primary-dark)]
                                                   checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                                   transition-all duration-200"
                                        >
                                        <label for="bron-{{ str_replace(['.', ' '], ['-', '-'], strtolower($source)) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $source }}
                                        </label>
                                        <x-ui.badge size="sm" variant="primary-dark">{{ $filterCounts['publicatiebestemming'][$source] ?? 0 }}</x-ui.badge>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Date Filter -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface)]">Datum beschikbaar</h3>
                            <div class="space-y-2">
                                <div class="flex items-center gap-3">
                                    <input 
                                        type="radio" 
                                        id="datum-geen" 
                                        name="beschikbaarSinds" 
                                        value=""
                                        {{ !request('beschikbaarSinds') ? 'checked' : '' }}
                                        onchange="document.getElementById('filter-form').submit()"
                                        class="w-4 h-4 border border-[var(--color-primary-dark)]
                                               focus:outline-none
                                               cursor-pointer text-[var(--color-primary-dark)]
                                               checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                               transition-all duration-200"
                                    >
                                    <label for="datum-geen" class="text-sm cursor-pointer flex-1">
                                        Geen periode
                                    </label>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input 
                                        type="radio" 
                                        id="datum-week" 
                                        name="beschikbaarSinds" 
                                        value="week"
                                        {{ request('beschikbaarSinds') === 'week' ? 'checked' : '' }}
                                        onchange="document.getElementById('filter-form').submit()"
                                        class="w-4 h-4 border border-[var(--color-primary-dark)]
                                               focus:outline-none
                                               cursor-pointer text-[var(--color-primary-dark)]
                                               checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                               transition-all duration-200"
                                    >
                                    <label for="datum-week" class="text-sm cursor-pointer flex-1">
                                        Afgelopen week
                                    </label>
                                    <x-ui.badge size="sm" variant="primary-dark" id="count-week">{{ $filterCounts['week'] ?? 0 }}</x-ui.badge>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input 
                                        type="radio" 
                                        id="datum-maand" 
                                        name="beschikbaarSinds" 
                                        value="maand"
                                        {{ request('beschikbaarSinds') === 'maand' ? 'checked' : '' }}
                                        onchange="document.getElementById('filter-form').submit()"
                                        class="w-4 h-4 border border-[var(--color-outline)] 
                                               focus:outline-none
                                               cursor-pointer text-[var(--color-primary-dark)]
                                               checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                               transition-all duration-200"
                                    >
                                    <label for="datum-maand" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                        Afgelopen maand
                                    </label>
                                    <x-ui.badge size="sm" variant="primary-dark" id="count-maand">{{ $filterCounts['maand'] ?? 0 }}</x-ui.badge>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input 
                                        type="radio" 
                                        id="datum-jaar" 
                                        name="beschikbaarSinds" 
                                        value="jaar"
                                        {{ request('beschikbaarSinds') === 'jaar' ? 'checked' : '' }}
                                        onchange="document.getElementById('filter-form').submit()"
                                        class="w-4 h-4 border border-[var(--color-outline)] 
                                               focus:outline-none
                                               cursor-pointer text-[var(--color-primary-dark)]
                                               checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                               transition-all duration-200"
                                    >
                                    <label for="datum-jaar" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                        Afgelopen jaar
                                    </label>
                                    <x-ui.badge size="sm" variant="primary-dark" id="count-jaar">{{ $filterCounts['jaar'] ?? 0 }}</x-ui.badge>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input 
                                        type="radio" 
                                        id="datum-zelf" 
                                        name="beschikbaarSinds" 
                                        value="zelf"
                                        {{ request('beschikbaarSinds') === 'zelf' || request('publicatiedatum_van') || request('publicatiedatum_tot') ? 'checked' : '' }}
                                        onchange="toggleCustomDateRange()"
                                        class="w-4 h-4 border border-[var(--color-outline)] 
                                               focus:outline-none
                                               cursor-pointer text-[var(--color-primary-dark)]
                                               checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                               transition-all duration-200"
                                    >
                                    <label for="datum-zelf" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                        Aangepaste periode
                                    </label>
                                </div>
                                <!-- Custom Date Range Inputs -->
                                <div id="custom-date-range" class="space-y-3 mt-3 pl-7 {{ request('beschikbaarSinds') === 'zelf' || (request('publicatiedatum_van') && !request('beschikbaarSinds')) || (request('publicatiedatum_tot') && !request('beschikbaarSinds')) ? '' : 'hidden' }}">
                                    <div class="flex flex-col gap-2">
                                        <label for="publicatiedatum_van" class="text-sm font-medium text-[var(--color-on-surface)]">
                                            Vanaf
                                        </label>
                                        <input 
                                            type="date"
                                            name="publicatiedatum_van"
                                            id="publicatiedatum_van"
                                            value="{{ request('publicatiedatum_van') ? (function($date) { try { return \Carbon\Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d'); } catch(\Exception $e) { return $date; } })(request('publicatiedatum_van')) : '' }}"
                                            onchange="document.getElementById('filter-form').submit()"
                                            class="w-full px-3 py-2 rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)]
                                                   text-sm text-[var(--color-on-surface)]
                                                   focus:outline-none focus:border-[var(--color-primary)]
                                                   transition-colors duration-200"
                                        />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="publicatiedatum_tot" class="text-sm font-medium text-[var(--color-on-surface)]">
                                            Tot en met
                                        </label>
                                        <input 
                                            type="date"
                                            name="publicatiedatum_tot"
                                            id="publicatiedatum_tot"
                                            value="{{ request('publicatiedatum_tot') ? (function($date) { try { return \Carbon\Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d'); } catch(\Exception $e) { return $date; } })(request('publicatiedatum_tot')) : '' }}"
                                            onchange="document.getElementById('filter-form').submit()"
                                            class="w-full px-3 py-2 rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)]
                                                   text-sm text-[var(--color-on-surface)]
                                                   focus:outline-none focus:border-[var(--color-primary)]
                                                   transition-colors duration-200"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if(isset($isDossier))
                        <!-- Status Filter (Dossiers only) -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface)]">Status</h3>
                            <div class="space-y-2">
                                @php
                                    $selectedStatus = request('status');
                                @endphp
                                <div class="flex items-center gap-3">
                                    <input 
                                        type="radio" 
                                        id="status-geen" 
                                        name="status" 
                                        value=""
                                        {{ !$selectedStatus ? 'checked' : '' }}
                                        onchange="document.getElementById('filter-form').submit()"
                                        class="w-4 h-4 border border-[var(--color-outline)] 
                                               focus:outline-none
                                               cursor-pointer text-[var(--color-primary-dark)]
                                               checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                               transition-all duration-200"
                                    >
                                    <label for="status-geen" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                        Alle statussen
                                    </label>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input 
                                        type="radio" 
                                        id="status-actief" 
                                        name="status" 
                                        value="actief"
                                        {{ $selectedStatus === 'actief' ? 'checked' : '' }}
                                        onchange="document.getElementById('filter-form').submit()"
                                        class="w-4 h-4 border border-[var(--color-outline)] 
                                               focus:outline-none
                                               cursor-pointer text-[var(--color-primary-dark)]
                                               checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                               transition-all duration-200"
                                    >
                                    <label for="status-actief" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                        Actief
                                    </label>
                                        <x-ui.badge size="sm" variant="primary-dark">{{ $filterCounts['status']['actief'] ?? 0 }}</x-ui.badge>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input 
                                        type="radio" 
                                        id="status-gesloten" 
                                        name="status" 
                                        value="gesloten"
                                        {{ $selectedStatus === 'gesloten' ? 'checked' : '' }}
                                        onchange="document.getElementById('filter-form').submit()"
                                        class="w-4 h-4 border border-[var(--color-outline)] 
                                               focus:outline-none
                                               cursor-pointer text-[var(--color-primary-dark)]
                                               checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                               transition-all duration-200"
                                    >
                                    <label for="status-gesloten" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                        Gesloten
                                    </label>
                                    <x-ui.badge size="sm" variant="primary-dark">{{ $filterCounts['status']['gesloten'] ?? 0 }}</x-ui.badge>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Information Category Filter (Woo Informatiecategorie) -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface)]">Informatiecategorie</h3>
                            <div class="space-y-2">
                                @php
                                    $allCategories = $allFilterOptions['informatiecategorie'] ?? [];
                                    $visibleCategories = array_slice($allCategories, 0, 3);
                                    $hiddenCategories = array_slice($allCategories, 3);
                                    $selectedCategory = request('informatiecategorie');
                                @endphp
                                @foreach($visibleCategories as $category)
                                    <div class="flex items-center gap-3">
                                        <input 
                                            type="radio" 
                                            id="categorie-{{ md5($category) }}" 
                                            name="informatiecategorie" 
                                            value="{{ $category }}"
                                            {{ $selectedCategory === $category ? 'checked' : '' }}
                                            onchange="document.getElementById('filter-form').submit()"
                                            class="w-4 h-4 border border-[var(--color-outline)] 
                                                   focus:outline-none
                                                   cursor-pointer text-[var(--color-primary-dark)]
                                                   checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                                   transition-all duration-200"
                                        >
                                        <label for="categorie-{{ md5($category) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $category }}
                                        </label>
                                        <x-ui.badge size="sm" variant="primary-dark">{{ $filterCounts['informatiecategorie'][$category] ?? 0 }}</x-ui.badge>
                                    </div>
                                @endforeach
                                @if(!empty($hiddenCategories))
                                <div id="informatiecategorie-more" class="hidden space-y-2">
                                    @foreach($hiddenCategories as $category)
                                        <div class="flex items-center gap-3">
                                            <input 
                                                type="radio" 
                                                id="categorie-{{ md5($category) }}" 
                                                name="informatiecategorie" 
                                                value="{{ $category }}"
                                                {{ $selectedCategory === $category ? 'checked' : '' }}
                                                onchange="document.getElementById('filter-form').submit()"
                                                class="w-4 h-4 border border-[var(--color-outline)] 
                                                       focus:outline-none
                                                       cursor-pointer text-[var(--color-primary-dark)]
                                                       checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                                       transition-all duration-200"
                                            >
                                            <label for="categorie-{{ md5($category) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                                {{ $category }}
                                            </label>
                                            <x-ui.badge size="sm" variant="primary-dark">{{ $filterCounts['informatiecategorie'][$category] ?? 0 }}</x-ui.badge>
                                        </div>
                                    @endforeach
                                </div>
                                <button 
                                    type="button" 
                                    onclick="toggleFilterSection('informatiecategorie-more', 'informatiecategorie-toggle')"
                                    id="informatiecategorie-toggle"
                                    class="text-[var(--color-primary)] font-medium text-[var(--font-size-body-medium)] hover:underline 
                                           focus:outline-none rounded-md">
                                    Toon meer
                                </button>
                                @endif
                                @if($selectedCategory)
                                <div class="pt-2">
                                    <button 
                                        type="button" 
                                        onclick="document.getElementById('categorie-none').checked = true; document.getElementById('filter-form').submit();"
                                        class="text-[var(--color-primary)] font-medium text-[var(--font-size-body-small)] hover:underline 
                                               focus:outline-none rounded-md">
                                        <i class="fas fa-times text-xs" aria-hidden="true"></i> Categorie wissen
                                    </button>
                                </div>
                                @endif
                                <input 
                                    type="radio" 
                                    id="categorie-none" 
                                    name="informatiecategorie" 
                                    value=""
                                    {{ !$selectedCategory ? 'checked' : '' }}
                                    class="hidden"
                                    onchange="document.getElementById('filter-form').submit()"
                                >
                            </div>
                        </div>
                        
                        @if(!isset($isDossier))
                        <!-- Document Type Filter -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface)]">Documentsoort</h3>
                            <div class="space-y-2">
                                @php
                                    $allDocumentTypes = $allFilterOptions['documentsoort'] ?? [];
                                    $visibleTypes = array_slice($allDocumentTypes, 0, 2);
                                    $hiddenTypes = array_slice($allDocumentTypes, 2);
                                    $selectedTypes = (array)request('documentsoort', []);
                                @endphp
                                @foreach($visibleTypes as $type)
                                    <div class="flex items-center gap-3">
                                        <input 
                                            type="checkbox" 
                                            id="soort-{{ str_replace(' ', '-', strtolower($type)) }}" 
                                            name="documentsoort[]" 
                                            value="{{ $type }}"
                                            {{ in_array($type, $selectedTypes) ? 'checked' : '' }}
                                            onchange="document.getElementById('filter-form').submit()"
                                            class="w-4 h-4 rounded border border-[var(--color-outline)] 
                                                   focus:outline-none
                                                   cursor-pointer text-[var(--color-primary-dark)]
                                                   checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                                   transition-all duration-200"
                                        >
                                        <label for="soort-{{ str_replace(' ', '-', strtolower($type)) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $type }}
                                        </label>
                                        <x-ui.badge size="sm" variant="primary-dark">{{ $filterCounts['documentsoort'][$type] ?? 0 }}</x-ui.badge>
                                    </div>
                                @endforeach
                                @if(!empty($hiddenTypes))
                                <div id="documentsoort-more" class="hidden space-y-2">
                                    @foreach($hiddenTypes as $type)
                                        <div class="flex items-center gap-3">
                                            <input 
                                                type="checkbox" 
                                                id="soort-{{ str_replace(' ', '-', strtolower($type)) }}" 
                                                name="documentsoort[]" 
                                                value="{{ $type }}"
                                                {{ in_array($type, $selectedTypes) ? 'checked' : '' }}
                                                onchange="document.getElementById('filter-form').submit()"
                                                class="w-4 h-4 rounded border border-[var(--color-outline)] 
                                                       focus:outline-none
                                                       cursor-pointer text-[var(--color-primary-dark)]
                                                       checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                                       transition-all duration-200"
                                            >
                                            <label for="soort-{{ str_replace(' ', '-', strtolower($type)) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                                {{ $type }}
                                            </label>
                                            <x-ui.badge size="sm" variant="primary-dark">{{ $filterCounts['documentsoort'][$type] ?? 0 }}</x-ui.badge>
                                        </div>
                                    @endforeach
                                </div>
                                <button 
                                    type="button" 
                                    onclick="toggleFilterSection('documentsoort-more', 'documentsoort-toggle')"
                                    id="documentsoort-toggle"
                                    class="text-[var(--color-primary)] font-medium text-[var(--font-size-body-medium)] hover:underline 
                                           focus:outline-none rounded-md">
                                    Toon meer
                                </button>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <!-- File Type Filter -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface)]">Type bronbestand</h3>
                            <div class="space-y-2">
                                @php
                                    $fileTypes = [
                                        'PDF' => 'application/pdf',
                                        'Word-document' => 'application/msword|application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                        'E-mailbericht' => 'message/rfc822|text/plain',
                                        'Presentatie' => 'application/vnd.ms-powerpoint|application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                        'Spreadsheet' => 'application/vnd.ms-excel|application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                        'Afbeelding' => 'image/',
                                    ];
                                    $selectedFileTypes = (array)request('bestandstype', []);
                                @endphp
                                @foreach($fileTypes as $label => $mimePattern)
                                    <div class="flex items-center gap-3">
                                        <input 
                                            type="checkbox" 
                                            id="bestandstype-{{ strtolower(str_replace([' ', '-'], ['', ''], $label)) }}" 
                                            name="bestandstype[]" 
                                            value="{{ $label }}"
                                            {{ in_array($label, $selectedFileTypes) ? 'checked' : '' }}
                                            onchange="document.getElementById('filter-form').submit()"
                                            class="w-4 h-4 rounded border border-[var(--color-outline)] 
                                                   focus:outline-none
                                                   cursor-pointer text-[var(--color-primary-dark)]
                                                   checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                                   transition-all duration-200"
                                        >
                                        <label for="bestandstype-{{ strtolower(str_replace([' ', '-'], ['', ''], $label)) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $label }}
                                        </label>
                                        <x-ui.badge size="sm" variant="primary-dark">{{ $filterCounts['bestandstype'][$label] ?? 0 }}</x-ui.badge>
                                    </div>
                                @endforeach
                                <button 
                                    type="button" 
                                    class="text-[var(--color-primary)] font-medium text-[var(--font-size-body-medium)] hover:underline 
                                           focus:outline-none rounded-md">
                                    Toon meer
                                </button>
                            </div>
                        </div>
                        
                        <!-- Theme Filter -->
                        @if(isset($isDossier) || !empty($allFilterOptions['thema'] ?? []))
                        <div class="space-y-3">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface)]">Thema</h3>
                            <div class="space-y-2">
                                @php
                                    $allThemes = $allFilterOptions['thema'] ?? [];
                                    $visibleThemes = array_slice($allThemes, 0, isset($isDossier) ? 3 : 1);
                                    $hiddenThemes = array_slice($allThemes, isset($isDossier) ? 3 : 1);
                                    $selectedThemes = (array)request('thema', []);
                                @endphp
                                @foreach($visibleThemes as $theme)
                                    <div class="flex items-center gap-3">
                                        <input 
                                            type="checkbox" 
                                            id="thema-{{ str_replace(' ', '-', strtolower($theme)) }}" 
                                            name="thema[]" 
                                            value="{{ $theme }}"
                                            {{ in_array($theme, $selectedThemes) ? 'checked' : '' }}
                                            onchange="document.getElementById('filter-form').submit()"
                                            class="w-4 h-4 rounded border border-[var(--color-outline)] 
                                                   focus:outline-none
                                                   cursor-pointer text-[var(--color-primary-dark)]
                                                   checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                                   transition-all duration-200"
                                        >
                                        <label for="thema-{{ str_replace(' ', '-', strtolower($theme)) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $theme }}
                                        </label>
                                        <x-ui.badge size="sm" variant="primary-dark">{{ $filterCounts['thema'][$theme] ?? 0 }}</x-ui.badge>
                                    </div>
                                @endforeach
                                @if(!empty($hiddenThemes))
                                <div id="thema-more" class="hidden space-y-2">
                                    @foreach($hiddenThemes as $theme)
                                        <div class="flex items-center gap-3">
                                            <input 
                                                type="checkbox" 
                                                id="thema-{{ str_replace(' ', '-', strtolower($theme)) }}" 
                                                name="thema[]" 
                                                value="{{ $theme }}"
                                                {{ in_array($theme, $selectedThemes) ? 'checked' : '' }}
                                                onchange="document.getElementById('filter-form').submit()"
                                                class="w-4 h-4 rounded border border-[var(--color-outline)] 
                                                       focus:outline-none
                                                       cursor-pointer text-[var(--color-primary-dark)]
                                                       checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                                       transition-all duration-200"
                                            >
                                            <label for="thema-{{ str_replace(' ', '-', strtolower($theme)) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                                {{ $theme }}
                                            </label>
                                            <x-ui.badge size="sm" variant="primary-dark">{{ $filterCounts['thema'][$theme] ?? 0 }}</x-ui.badge>
                                        </div>
                                    @endforeach
                                </div>
                                <button 
                                    type="button" 
                                    onclick="toggleFilterSection('thema-more', 'thema-toggle')"
                                    id="thema-toggle"
                                    class="text-[var(--color-primary)] font-medium text-[var(--font-size-body-medium)] hover:underline 
                                           focus:outline-none rounded-md">
                                    Toon meer
                                </button>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <!-- Organisation Filter -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface)]">Organisatie</h3>
                            <div class="space-y-2">
                                @php
                                    $allOrganisations = $allFilterOptions['organisatie'] ?? [];
                                    $visibleOrgs = array_slice($allOrganisations, 0, 1);
                                    $hiddenOrgs = array_slice($allOrganisations, 1);
                                    $selectedOrgs = (array)request('organisatie', []);
                                @endphp
                                @foreach($visibleOrgs as $org)
                                    <div class="flex items-center gap-3">
                                        <input 
                                            type="checkbox" 
                                            id="org-{{ md5($org) }}" 
                                            name="organisatie[]" 
                                            value="{{ $org }}"
                                            {{ in_array($org, $selectedOrgs) ? 'checked' : '' }}
                                            onchange="document.getElementById('filter-form').submit()"
                                            class="w-4 h-4 rounded border border-[var(--color-outline)] 
                                                   focus:outline-none
                                                   cursor-pointer text-[var(--color-primary-dark)]
                                                   checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                                   transition-all duration-200"
                                        >
                                        <label for="org-{{ md5($org) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $org }}
                                        </label>
                                        <x-ui.badge size="sm" variant="primary-dark">{{ number_format($filterCounts['organisatie'][$org] ?? 0, 0, ',', '.') }}</x-ui.badge>
                                    </div>
                                @endforeach
                                @if(!empty($hiddenOrgs))
                                <div id="organisatie-more" class="hidden space-y-2">
                                    @foreach($hiddenOrgs as $org)
                                        <div class="flex items-center gap-3">
                                            <input 
                                                type="checkbox" 
                                                id="org-{{ md5($org) }}" 
                                                name="organisatie[]" 
                                                value="{{ $org }}"
                                                {{ in_array($org, $selectedOrgs) ? 'checked' : '' }}
                                                onchange="document.getElementById('filter-form').submit()"
                                                class="w-4 h-4 rounded border border-[var(--color-outline)] 
                                                       focus:outline-none
                                                       cursor-pointer text-[var(--color-primary-dark)]
                                                       checked:bg-[var(--color-primary-dark)] checked:border-[var(--color-primary-dark)]
                                                       transition-all duration-200"
                                            >
                                            <label for="org-{{ md5($org) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                                {{ $org }}
                                            </label>
                                            <x-ui.badge size="sm" variant="primary-dark">{{ number_format($filterCounts['organisatie'][$org] ?? 0, 0, ',', '.') }}</x-ui.badge>
                                        </div>
                                    @endforeach
                                </div>
                                <button 
                                    type="button" 
                                    onclick="toggleFilterSection('organisatie-more', 'organisatie-toggle')"
                                    id="organisatie-toggle"
                                    class="text-[var(--color-primary)] font-medium text-[var(--font-size-body-medium)] hover:underline 
                                           focus:outline-none rounded-md">
                                    Toon meer
                                </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </aside>
            
            <!-- Results Section -->
            <div class="space-y-6">
                <!-- Active Filters Ribbons -->
                @php
                    $activeFilters = [];
                    if (request('beschikbaarSinds') && request('beschikbaarSinds') !== 'zelf') {
                        $periods = ['week' => 'Afgelopen week', 'maand' => 'Afgelopen maand', 'jaar' => 'Afgelopen jaar'];
                        $activeFilters[] = ['type' => 'beschikbaarSinds', 'value' => request('beschikbaarSinds'), 'label' => $periods[request('beschikbaarSinds')] ?? request('beschikbaarSinds')];
                    }
                    if (request('publicatiedatum_van') || request('publicatiedatum_tot')) {
                        $dateLabel = '';
                        if (request('publicatiedatum_van') && request('publicatiedatum_tot')) {
                            $dateLabel = request('publicatiedatum_van') . ' - ' . request('publicatiedatum_tot');
                        } elseif (request('publicatiedatum_van')) {
                            $dateLabel = 'Vanaf ' . request('publicatiedatum_van');
                        } elseif (request('publicatiedatum_tot')) {
                            $dateLabel = 'Tot ' . request('publicatiedatum_tot');
                        }
                        $activeFilters[] = ['type' => 'date', 'value' => 'custom', 'label' => $dateLabel];
                    }
                    if (request('documentsoort') && !isset($isDossier)) {
                        foreach ((array)request('documentsoort') as $type) {
                            $activeFilters[] = ['type' => 'documentsoort', 'value' => $type, 'label' => $type];
                        }
                    }
                    if (request('status') && isset($isDossier)) {
                        $statusLabels = ['actief' => 'Actief', 'gesloten' => 'Gesloten'];
                        $activeFilters[] = ['type' => 'status', 'value' => request('status'), 'label' => $statusLabels[request('status')] ?? request('status')];
                    }
                    if (request('thema')) {
                        foreach ((array)request('thema') as $theme) {
                            $activeFilters[] = ['type' => 'thema', 'value' => $theme, 'label' => $theme];
                        }
                    }
                    if (request('organisatie')) {
                        foreach ((array)request('organisatie') as $org) {
                            $activeFilters[] = ['type' => 'organisatie', 'value' => $org, 'label' => $org];
                        }
                    }
                    if (request('informatiecategorie')) {
                        $activeFilters[] = ['type' => 'informatiecategorie', 'value' => request('informatiecategorie'), 'label' => request('informatiecategorie')];
                    }
                    if (request('bestandstype')) {
                        foreach ((array)request('bestandstype') as $fileType) {
                            $activeFilters[] = ['type' => 'bestandstype', 'value' => $fileType, 'label' => $fileType];
                        }
                    }
                    if (request('publicatiebestemming')) {
                        foreach ((array)request('publicatiebestemming') as $source) {
                            $activeFilters[] = ['type' => 'publicatiebestemming', 'value' => $source, 'label' => $source];
                        }
                    }
                    if (request('titles_only')) {
                        $activeFilters[] = ['type' => 'titles_only', 'value' => '1', 'label' => 'Alleen in titels'];
                    }
                    if (request('zoeken')) {
                        $activeFilters[] = ['type' => 'zoeken', 'value' => request('zoeken'), 'label' => 'Zoekterm: ' . request('zoeken')];
                    }
                @endphp
                
                <!-- Unified Search, Filters & Results Header Card -->
                <div id="search-header-card" class="bg-[var(--color-surface)] rounded-md border border-[var(--color-outline-variant)]">
                    <div class="p-4 sm:p-6 space-y-5">
                        <!-- Top Row: Search Section -->
                        <div class="space-y-3">
                            <label for="unified-search" class="block text-sm font-semibold text-[var(--color-on-surface)]">
                                Zoeken in {{ isset($isDossier) ? 'dossiers' : 'documenten' }} & filters
                            </label>
                            <div class="relative">
                                <x-input 
                                    type="text"
                                    name="zoeken"
                                    id="unified-search"
                                    value="{{ request('zoeken') }}"
                                    placeholder="Zoek {{ isset($isDossier) ? 'dossiers' : 'documenten' }} of filter op organisatie, thema..."
                                    leadingIcon="fas fa-search"
                                    autocomplete="off"
                                    class="block w-full pr-3 py-2.5 rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)]
                                           text-sm text-[var(--color-on-surface)] placeholder-[var(--color-on-surface-variant)]
                                           focus:outline-none focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary)]/20
                                           transition-all duration-200"
                                />
                                <div id="unified-search-results" class="absolute z-50 mt-1 w-full bg-[var(--color-surface)] rounded-md border border-[var(--color-outline-variant)] shadow-lg hidden max-h-96 overflow-auto">
                                    <!-- Results populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bottom Row: Results Info & Controls -->
                        <div class="pt-3 border-t border-[var(--color-outline-variant)]">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <!-- Results Count -->
                                <div class="flex-shrink-0">
                                    <h2 class="text-base font-semibold text-[var(--color-on-surface)]">
                                        {{ isset($isDossier) ? 'Dossiers' : 'Zoekresultaten' }}
                                    </h2>
                                    <p class="mt-1 text-sm text-[var(--color-on-surface-variant)]">
                                        {{ (($results['page'] ?? 1) - 1) * ($results['perPage'] ?? 20) + 1 }}-{{ min(($results['page'] ?? 1) * ($results['perPage'] ?? 20), $results['total'] ?? 0) }} van de {{ number_format($results['total'] ?? 0, 0, ',', '.') }} {{ isset($isDossier) ? 'dossiers' : 'resultaten' }}
                                    </p>
                                </div>
                                
                                <!-- Sort & Per Page Controls -->
                                <div class="flex flex-wrap items-center gap-4">
                                    <div class="flex items-center gap-2">
                                        <label for="sort-select" class="text-sm font-medium text-[var(--color-on-surface-variant)] whitespace-nowrap">Sorteer op:</label>
                                        <select 
                                            id="sort-select"
                                            name="sort" 
                                            class="rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)] px-3 py-1.5 text-sm text-[var(--color-on-surface)] 
                                                   focus:outline-none focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary)]/20
                                                   cursor-pointer transition-all duration-200"
                                            onchange="updateSort(this.value)"
                                        >
                                            <option value="relevance" {{ request('sort', 'relevance') === 'relevance' ? 'selected' : '' }}>Relevantie</option>
                                            <option value="publication_date" {{ request('sort') === 'publication_date' ? 'selected' : '' }}>Publicatiedatum</option>
                                            <option value="modified_date" {{ request('sort') === 'modified_date' ? 'selected' : '' }}>Laatst gewijzigd</option>
                                        </select>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="text-sm font-medium text-[var(--color-on-surface-variant)] whitespace-nowrap">Aantal:</label>
                                        <div class="inline-flex rounded-md border border-[var(--color-outline-variant)] overflow-hidden">
                                            <a href="{{ request()->fullUrlWithQuery(['per_page' => 10, 'pagina' => 1]) }}" 
                                               class="px-3 py-1.5 text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20
                                                      {{ request('per_page', 20) == 10 ? 'bg-[var(--color-primary)] text-[var(--color-on-primary)]' : 'text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)]' }}
                                                      {{ request('per_page', 20) == 10 ? '' : 'border-r border-[var(--color-outline-variant)]' }}">
                                                10
                                            </a>
                                            <a href="{{ request()->fullUrlWithQuery(['per_page' => 20, 'pagina' => 1]) }}" 
                                               class="px-3 py-1.5 text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20 border-r border-[var(--color-outline-variant)]
                                                      {{ request('per_page', 20) == 20 ? 'bg-[var(--color-primary)] text-[var(--color-on-primary)]' : 'text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)]' }}">
                                                20
                                            </a>
                                            <a href="{{ request()->fullUrlWithQuery(['per_page' => 50, 'pagina' => 1]) }}" 
                                               class="px-3 py-1.5 text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20 border-r border-[var(--color-outline-variant)]
                                                      {{ request('per_page', 20) == 50 ? 'bg-[var(--color-primary)] text-[var(--color-on-primary)]' : 'text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)]' }}">
                                                50
                                            </a>
                                            <a href="{{ request()->fullUrlWithQuery(['per_page' => 100, 'pagina' => 1]) }}" 
                                               class="px-3 py-1.5 text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20
                                                      {{ request('per_page', 20) == 100 ? 'bg-[var(--color-primary)] text-[var(--color-on-primary)]' : 'text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)]' }}">
                                                100
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Active Filters -->
                        @if(!empty($activeFilters))
                        <div class="pt-4 border-t border-[var(--color-outline-variant)]">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-[var(--color-on-surface)]">Actieve filters:</span>
                                <div class="flex items-center gap-2">
                                    <a href="{{ isset($isDossier) ? route('dossiers.index') : route('zoeken') }}" 
                                       class="inline-flex items-center gap-1 px-2 py-1 rounded-md 
                                              bg-[var(--color-surface-variant)] text-[var(--color-on-surface-variant)] border border-[var(--color-outline-variant)]
                                              hover:bg-[var(--color-surface-variant)]/80
                                              focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20
                                              transition-all duration-200 font-medium text-xs">
                                        <i class="fas fa-times-circle text-xs" aria-hidden="true"></i>
                                        <span>Alle filters wissen</span>
                                    </a>
                                    
                                    <!-- Subscription/Bell Icon -->
                                    <button type="button" 
                                            command="show-modal" 
                                            commandfor="subscription-drawer"
                                            class="inline-flex items-center justify-center px-2 py-1 rounded-md 
                                                   bg-[var(--color-primary)]/10 text-[var(--color-primary)] border border-[var(--color-primary)]/20
                                                   hover:bg-[var(--color-primary)]/20 hover:border-[var(--color-primary)]/30
                                                   focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20
                                                   transition-all duration-200 font-medium text-xs h-[26px]"
                                            title="Abonneren op deze zoekopdracht">
                                        <i class="fas fa-bell text-xs" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 relative">
                                @foreach($activeFilters as $filter)
                                    @php
                                        $removeUrl = request()->fullUrlWithQuery(['pagina' => 1]);
                                        if ($filter['type'] === 'beschikbaarSinds') {
                                            $removeUrl = request()->fullUrlWithQuery(['beschikbaarSinds' => null, 'pagina' => 1]);
                                        } elseif ($filter['type'] === 'date') {
                                            $removeUrl = request()->fullUrlWithQuery(['publicatiedatum_van' => null, 'publicatiedatum_tot' => null, 'beschikbaarSinds' => null, 'pagina' => 1]);
                                        } elseif ($filter['type'] === 'status') {
                                            $removeUrl = request()->fullUrlWithQuery(['status' => null, 'pagina' => 1]);
                                        } elseif ($filter['type'] === 'titles_only') {
                                            $removeUrl = request()->fullUrlWithQuery(['titles_only' => null, 'pagina' => 1]);
                                        } elseif ($filter['type'] === 'informatiecategorie') {
                                            $removeUrl = request()->fullUrlWithQuery(['informatiecategorie' => null, 'pagina' => 1]);
                                        } elseif ($filter['type'] === 'zoeken') {
                                            $removeUrl = request()->fullUrlWithQuery(['zoeken' => null, 'pagina' => 1]);
                                        } else {
                                            $currentValues = (array)request($filter['type'], []);
                                            $newValues = array_values(array_filter($currentValues, fn($v) => $v !== $filter['value']));
                                            $removeUrl = request()->fullUrlWithQuery([$filter['type'] => $newValues, 'pagina' => 1]);
                                        }
                                    @endphp
                                    <a href="{{ $removeUrl }}" 
                                       class="inline-flex items-center gap-1 px-2 py-1 rounded-md 
                                              bg-[var(--color-primary)]/10 text-[var(--color-primary)] border border-[var(--color-primary)]/20
                                              hover:bg-[var(--color-primary)]/20 hover:border-[var(--color-primary)]/30
                                              focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20
                                              transition-all duration-200 font-medium text-xs
                                              group"
                                       title="Verwijder filter: {{ $filter['label'] }}">
                                        <span>{{ $filter['label'] }}</span>
                                        <i class="fas fa-times text-[10px] opacity-70 group-hover:opacity-100 transition-opacity" aria-hidden="true"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Error Message -->
                @if(isset($error))
                    <div class="bg-error-container text-on-error-container p-4 rounded-md border border-error" role="alert">
                        <p class="text-[var(--font-size-body-medium)] text-error font-medium">
                            Er is een fout opgetreden: {{ $error }}
                        </p>
                    </div>
                @endif
                
                <!-- Results List -->
                <div id="search-results-area" class="space-y-6">
                @if(empty($results['items']))
                    <div class="bg-[var(--color-surface)] rounded-md p-12 text-center border border-[var(--color-outline-variant)]">
                        <p class="text-[var(--font-size-body-large)] text-[var(--color-on-surface-variant)] mb-2">Geen resultaten gevonden.</p>
                        <p class="text-[var(--font-size-body-medium)] text-[var(--color-on-surface-variant)]">Probeer andere zoekwoorden of filters aan te passen.</p>
                    </div>
                @else
                    <!-- Columnar Full-Width Layout - Premium Design -->
                    <div class="flex flex-col gap-4">
                        @foreach($results['items'] as $item)
                            <article class="rounded-lg bg-[var(--color-surface)] border border-[var(--color-outline-variant)]/60 overflow-hidden hover:border-[var(--color-primary)]/40 transition-all duration-300">
                                <!-- Item Header -->
                                <div class="px-5 py-2.5 bg-[var(--color-surface-variant)]/50 border-b border-[var(--color-outline-variant)]/40 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        @php
                                            // Determine type from JSON or fallback to isDossier check
                                            $type = strtoupper($item->type ?? ($item->document_type ?? (isset($isDossier) ? 'DOSSIER' : 'DOCUMENT')));
                                            
                                            // Map type to badge colors
                                            $typeColors = [
                                                'DOCUMENT' => ['bg' => 'bg-[var(--color-primary-dark)]', 'text' => 'text-[var(--color-on-primary)]'],
                                                'THEMA' => ['bg' => 'bg-[var(--color-primary)]', 'text' => 'text-[var(--color-on-primary)]'],
                                                'DOSSIER' => ['bg' => 'bg-sky-100 dark:bg-sky-900/50', 'text' => 'text-sky-700 dark:text-sky-300'],
                                                'REPORT' => ['bg' => 'bg-yellow-100 dark:bg-yellow-900/50', 'text' => 'text-yellow-700 dark:text-yellow-300'],
                                            ];
                                            
                                            $badgeColors = $typeColors[$type] ?? $typeColors['DOCUMENT'];
                                        @endphp
                                        <span class="inline-flex items-center font-semibold rounded-full px-2.5 py-0.5 text-[11px] leading-tight tracking-wide {{ $badgeColors['bg'] }} {{ $badgeColors['text'] }}">
                                            {{ $type }}
                                        </span>

                                        @if($item->category)
                                            <span class="h-4 w-px bg-[var(--color-outline-variant)]/40"></span>
                                            <a href="{{ request()->fullUrlWithQuery(['informatiecategorie' => $item->category]) }}" class="text-[12px] font-semibold text-purple-700 dark:text-purple-400 uppercase tracking-wide flex items-center gap-1.5 hover:text-[var(--color-primary)] transition-colors">
                                                <i class="fas fa-folder-open text-[10px] opacity-70"></i>
                                                {{ $item->formatted_category ?? $item->category }}
                                            </a>
                                        @endif
                                    </div>
                                    
                                    <!-- Date Ribbon -->
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-[var(--color-surface)] text-[var(--color-on-surface-variant)] border border-[var(--color-outline-variant)]/50 text-[11px] font-medium tracking-wide">
                                        <i class="far fa-calendar text-[10px] text-[var(--color-primary)] opacity-80"></i>
                                        {{ $item->publication_date ? $item->publication_date->format('d-m-Y') : 'Geen datum' }}
                                    </div>
                                </div>

                                <div class="flex flex-col md:flex-row items-stretch">
                                    <!-- Column 1: Title, Organisation, Description & Keywords (Main Info) -->
                                    <div class="flex-1 px-6 py-5 border-b md:border-b-0 md:border-r border-[var(--color-outline-variant)]/30">
                                        <!-- Title -->
                                        <h3 class="text-[17px] font-semibold text-[var(--color-on-surface)] leading-snug tracking-[-0.01em] mb-1.5">
                                            <a href="{{ isset($isDossier) ? route('dossiers.show', $item->external_id) : '/open-overheid/documents/' . $item->external_id }}" class="hover:text-[var(--color-primary)] transition-colors duration-200">
                                                {!! $searchQuery ? highlightSearchTerms($item->ai_enhanced_title ?? $item->title ?? 'Geen titel', $searchQuery) : ($item->ai_enhanced_title ?? $item->title ?? 'Geen titel') !!}
                                            </a>
                                        </h3>

                                        <!-- Organisation (moved here) -->
                                        @if($item->organisation)
                                            <div class="flex items-center gap-1.5 mb-2.5">
                                                <i class="far fa-building text-[11px] text-[var(--color-primary)]/70" aria-hidden="true"></i>
                                                <a href="{{ request()->fullUrlWithQuery(['organisatie' => [$item->organisation]]) }}" class="text-[12px] font-medium text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)] transition-colors" title="{{ $item->organisation }}">
                                                    {{ $item->organisation }}
                                                </a>
                                            </div>
                                        @endif

                                        <!-- Short Description -->
                                        @if(isset($isDossier) && isset($item->ai_summary) && !empty($item->ai_summary))
                                            <div class="text-[14px] text-[var(--color-on-surface-variant)]/90 leading-relaxed italic border-l-2 border-purple-300/60 pl-4 py-1 bg-purple-50/20 dark:bg-purple-900/10 rounded-r mb-3">
                                                {!! $searchQuery ? highlightSearchTerms(\Illuminate\Support\Str::limit($item->ai_summary, 150), $searchQuery) : \Illuminate\Support\Str::limit($item->ai_summary, 150) !!}
                                            </div>
                                        @elseif($item->description)
                                            <p class="text-[14px] text-[var(--color-on-surface-variant)]/80 leading-relaxed line-clamp-2 tracking-[0.01em] mb-3">
                                                {!! $searchQuery ? highlightSearchTerms(\Illuminate\Support\Str::limit($item->description, 150), $searchQuery) : \Illuminate\Support\Str::limit($item->description, 150) !!}
                                            </p>
                                        @endif

                                        <!-- Keywords as Badges -->
                                        @if(isset($item->keywords) && !empty($item->keywords))
                                            <div class="flex flex-wrap gap-2">
                                                @foreach(array_slice((array)$item->keywords, 0, 3) as $keyword)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                        <i class="fas fa-hashtag text-[8px] mr-1 opacity-60"></i>
                                                        {{ $keyword }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Column 2: Themes -->
                                    <div class="w-full md:w-56 px-5 py-4 bg-[var(--color-surface-variant)]/5 flex flex-col justify-center gap-2 border-b md:border-b-0 md:border-r border-[var(--color-outline-variant)]/30">
                                        @if(isset($item->theme) || isset($item->themes))
                                            <div class="text-[10px] font-semibold text-[var(--color-on-surface-variant)]/60 uppercase tracking-wider mb-1">Thema's</div>
                                            @if(isset($item->themes) && is_array($item->themes))
                                                @foreach($item->themes as $theme)
                                                    <a href="{{ request()->fullUrlWithQuery(['thema' => [$theme]]) }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--color-on-surface-variant)] font-medium hover:text-[var(--color-primary)] transition-colors">
                                                        <i class="fas fa-tag text-[10px] text-[var(--color-primary)]/70" aria-hidden="true"></i>
                                                        {{ $theme }}
                                                    </a>
                                                @endforeach
                                            @elseif(isset($item->theme))
                                                <a href="{{ request()->fullUrlWithQuery(['thema' => [$item->theme]]) }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--color-on-surface-variant)] font-medium hover:text-[var(--color-primary)] transition-colors">
                                                    <i class="fas fa-tag text-[10px] text-[var(--color-primary)]/70" aria-hidden="true"></i>
                                                    {{ $item->theme }}
                                                </a>
                                            @endif
                                        @else
                                            <div class="text-[11px] text-[var(--color-on-surface-variant)]/50 italic">Geen thema's</div>
                                        @endif
                                    </div>

                                    <!-- Column 3: Subtle Action Indicator -->
                                    <div class="w-full md:w-14 flex items-center justify-center bg-[var(--color-surface-variant)]/5">
                                        <a href="{{ isset($isDossier) ? route('dossiers.show', $item->external_id) : '/open-overheid/documents/' . $item->external_id }}" 
                                           class="w-9 h-9 rounded-full border border-[var(--color-outline-variant)]/50 flex items-center justify-center text-[var(--color-outline-variant)] hover:border-[var(--color-primary)] hover:text-[var(--color-primary)] hover:translate-x-0.5 transition-all duration-300"
                                           title="Bekijk {{ isset($isDossier) ? 'dossier' : 'document' }}">
                                            <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    @if(($results['total'] ?? 0) > ($results['perPage'] ?? 20))
                        @php
                            $currentPage = $results['page'] ?? 1;
                            $perPage = $results['perPage'] ?? 20;
                            $total = $results['total'] ?? 0;
                            $totalPages = ceil($total / $perPage);
                            $startItem = (($currentPage - 1) * $perPage) + 1;
                            $endItem = min($currentPage * $perPage, $total);
                            $showPages = 5;
                            $startPage = max(1, $currentPage - floor($showPages / 2));
                            $endPage = min($totalPages, $startPage + $showPages - 1);
                        @endphp
                        <div class="flex items-center justify-between bg-[var(--color-surface)] px-4 py-3 sm:px-6">
                            <!-- Mobile: Previous/Next -->
                            <div class="flex flex-1 justify-between sm:hidden">
                                @if(($results['hasPreviousPage'] ?? false))
                                    <a href="{{ request()->fullUrlWithQuery(['pagina' => $currentPage - 1]) }}" 
                                       class="relative inline-flex items-center rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)] px-4 py-2 text-sm font-medium text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)] dark:border-white/10 dark:bg-[var(--color-surface)]/5 dark:text-gray-200 dark:hover:bg-[var(--color-surface)]/10">
                                        Vorige
                                    </a>
                                @else
                                    <span class="relative inline-flex items-center rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)] px-4 py-2 text-sm font-medium text-[var(--color-on-surface-variant)] opacity-50 cursor-not-allowed dark:border-white/10 dark:bg-[var(--color-surface)]/5 dark:text-gray-400">
                                        Vorige
                                    </span>
                                @endif
                                @if(($results['hasNextPage'] ?? false))
                                    <a href="{{ request()->fullUrlWithQuery(['pagina' => $currentPage + 1]) }}" 
                                       class="relative ml-3 inline-flex items-center rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)] px-4 py-2 text-sm font-medium text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)] dark:border-white/10 dark:bg-[var(--color-surface)]/5 dark:text-gray-200 dark:hover:bg-[var(--color-surface)]/10">
                                        Volgende
                                    </a>
                                @else
                                    <span class="relative ml-3 inline-flex items-center rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)] px-4 py-2 text-sm font-medium text-[var(--color-on-surface-variant)] opacity-50 cursor-not-allowed dark:border-white/10 dark:bg-[var(--color-surface)]/5 dark:text-gray-400">
                                        Volgende
                                    </span>
                                @endif
                            </div>
                            <!-- Desktop: Showing text + Page numbers -->
                            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-[var(--color-on-surface-variant)] dark:text-gray-300">
                                        Toont
                                        <span class="font-medium text-[var(--color-on-surface)]">{{ number_format($startItem, 0, ',', '.') }}</span>
                                        tot
                                        <span class="font-medium text-[var(--color-on-surface)]">{{ number_format($endItem, 0, ',', '.') }}</span>
                                        van
                                        <span class="font-medium text-[var(--color-on-surface)]">{{ number_format($total, 0, ',', '.') }}</span>
                                        resultaten
                                    </p>
                                </div>
                                <div>
                                    <nav aria-label="Paginatie" class="isolate inline-flex -space-x-px rounded-md">
                                        @if(($results['hasPreviousPage'] ?? false))
                                            <a href="{{ request()->fullUrlWithQuery(['pagina' => $currentPage - 1]) }}" 
                                               class="relative inline-flex items-center rounded-md px-2 py-2 text-[var(--color-on-surface-variant)]  hover:bg-[var(--color-surface-variant)] focus:z-20 focus:outline-none  dark:hover:bg-[var(--color-surface)]/5">
                                                <span class="sr-only">Vorige</span>
                                                <i class="fas fa-chevron-left text-sm" aria-hidden="true"></i>
                                            </a>
                                        @else
                                            <span class="relative inline-flex items-center rounded-md px-2 py-2 text-[var(--color-on-surface-variant)]  opacity-50 cursor-not-allowed ">
                                                <span class="sr-only">Vorige</span>
                                                <i class="fas fa-chevron-left text-sm" aria-hidden="true"></i>
                                            </span>
                                        @endif
                                        
                                        @if($startPage > 1)
                                            <a href="{{ request()->fullUrlWithQuery(['pagina' => 1]) }}" 
                                               class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-[var(--color-on-surface)]  hover:bg-[var(--color-surface-variant)] focus:z-20 focus:outline-none dark:text-gray-200  dark:hover:bg-[var(--color-surface)]/5">
                                                1
                                            </a>
                                            @if($startPage > 2)
                                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-[var(--color-on-surface-variant)]  focus:outline-none dark:text-gray-400 ">...</span>
                                            @endif
                                        @endif
                                        
                                        @for($i = $startPage; $i <= $endPage; $i++)
                                            @if($i == $currentPage)
                                                <a href="#" aria-current="page" class="relative z-10 inline-flex items-center bg-[var(--color-primary)] px-4 py-2 text-sm font-semibold text-[var(--color-on-primary)] rounded-md focus:z-20 focus-visible:outline-none dark:bg-[var(--color-primary)] dark:focus-visible:outline-none">
                                                    {{ $i }}
                                                </a>
                                            @else
                                                <a href="{{ request()->fullUrlWithQuery(['pagina' => $i]) }}" 
                                                   class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-[var(--color-on-surface)] rounded-md hover:bg-[var(--color-surface-variant)] focus:z-20 focus:outline-none dark:text-gray-200  dark:hover:bg-[var(--color-surface)]/5">
                                                    {{ $i }}
                                                </a>
                                            @endif
                                        @endfor
                                        
                                        @if($endPage < $totalPages)
                                            @if($endPage < $totalPages - 1)
                                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-[var(--color-on-surface-variant)]  focus:outline-none dark:text-gray-400 ">...</span>
                                            @endif
                                            <a href="{{ request()->fullUrlWithQuery(['pagina' => $totalPages]) }}" 
                                               class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-[var(--color-on-surface)] rounded-md hover:bg-[var(--color-surface-variant)] focus:z-20 focus:outline-none dark:text-gray-200  dark:hover:bg-[var(--color-surface)]/5">
                                                {{ $totalPages }}
                                            </a>
                                        @endif
                                        
                                        @if(($results['hasNextPage'] ?? false))
                                            <a href="{{ request()->fullUrlWithQuery(['pagina' => $currentPage + 1]) }}" 
                                               class="relative inline-flex items-center rounded-md px-2 py-2 text-[var(--color-on-surface-variant)]  hover:bg-[var(--color-surface-variant)] focus:z-20 focus:outline-none  dark:hover:bg-[var(--color-surface)]/5">
                                                <span class="sr-only">Volgende</span>
                                                <i class="fas fa-chevron-right text-sm" aria-hidden="true"></i>
                                            </a>
                                        @else
                                            <span class="relative inline-flex items-center rounded-md px-2 py-2 text-[var(--color-on-surface-variant)]  opacity-50 cursor-not-allowed ">
                                                <span class="sr-only">Volgende</span>
                                                <i class="fas fa-chevron-right text-sm" aria-hidden="true"></i>
                                            </span>
                                        @endif
                                    </nav>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                </div>
            </div>
        </div>
    </main>
    
    <script>
        function updateSort(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', value);
            url.searchParams.set('pagina', '1');
            window.location.href = url.toString();
        }


        // Convert date inputs from Y-m-d (HTML date input) to d-m-Y (backend format) on form submit
        function convertDateInputsToFormat(event) {
            const vanInput = document.getElementById('publicatiedatum_van');
            const totInput = document.getElementById('publicatiedatum_tot');
            
            if (vanInput && vanInput.value) {
                // Date input provides Y-m-d format, convert to d-m-Y
                const dateParts = vanInput.value.split('-');
                if (dateParts.length === 3) {
                    vanInput.value = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
                }
            }
            
            if (totInput && totInput.value) {
                // Date input provides Y-m-d format, convert to d-m-Y
                const dateParts = totInput.value.split('-');
                if (dateParts.length === 3) {
                    totInput.value = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
                }
            }
        }

        function toggleCustomDateRange() {
            const customRange = document.getElementById('custom-date-range');
            const zelfRadio = document.getElementById('datum-zelf');
            if (zelfRadio && zelfRadio.checked && customRange) {
                customRange.classList.remove('hidden');
                // Set beschikbaarSinds to zelf when showing custom range
                document.getElementById('datum-zelf').checked = true;
            } else if (customRange) {
                customRange.classList.add('hidden');
            }
        }

        function clearCustomDates() {
            // Clear custom date inputs when selecting predefined period
            const vanInput = document.getElementById('publicatiedatum_van');
            const totInput = document.getElementById('publicatiedatum_tot');
            if (vanInput) vanInput.value = '';
            if (totInput) totInput.value = '';
        }

        function toggleFilterSection(sectionId, buttonId) {
            const section = document.getElementById(sectionId);
            const button = document.getElementById(buttonId);
            if (!section || !button) return;
            
            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                button.textContent = 'Toon minder';
            } else {
                section.classList.add('hidden');
                button.textContent = 'Toon meer';
            }
        }

        // Unified Search functionality with Typesense autocomplete
        let searchTimeout;
        let selectedIndex = -1;
        let dropdownShouldStayOpen = false;
        const autocompleteEndpoint = '{{ route("api.autocomplete") }}';
        const isDossierPage = @json(isset($isDossier) ? true : false);
        const searchRoute = isDossierPage ? '{{ route("dossiers.index") }}' : '{{ route("zoeken") }}';
        const unifiedSearchInput = document.getElementById('unified-search');
        const unifiedSearchResults = document.getElementById('unified-search-results');

        if (unifiedSearchInput && unifiedSearchResults) {
            // Keep dropdown open when input has focus
            unifiedSearchInput.addEventListener('focus', function(e) {
                const query = e.target.value.trim();
                if (query.length >= 2) {
                    // Re-fetch if we have content
                    if (unifiedSearchResults.innerHTML.trim() !== '') {
                        unifiedSearchResults.classList.remove('hidden');
                    } else {
                        fetchAutocomplete(query);
                    }
                }
                dropdownShouldStayOpen = true;
            });
            
            unifiedSearchInput.addEventListener('input', function(e) {
                const query = e.target.value.trim();
                
                // Clear previous timeouts
                clearTimeout(searchTimeout);
                
                // Reset selected index
                selectedIndex = -1;
                dropdownShouldStayOpen = true;
                
                if (query.length < 2) {
                    hideResults();
                    dropdownShouldStayOpen = false;
                    return;
                }
                
                // Debounced autocomplete
                searchTimeout = setTimeout(() => {
                    fetchAutocomplete(query);
                }, 300);
            });

            // Keyboard navigation
            unifiedSearchInput.addEventListener('keydown', function(e) {
                if (!unifiedSearchResults || unifiedSearchResults.classList.contains('hidden')) {
                    return;
                }

                const items = unifiedSearchResults.querySelectorAll('a');
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                    updateSelectedItem(items);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    updateSelectedItem(items);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (selectedIndex >= 0 && items[selectedIndex]) {
                        items[selectedIndex].click();
                    } else if (items.length > 0) {
                        items[0].click();
                    } else {
                        performLiveSearch(unifiedSearchInput.value.trim());
                    }
                } else if (e.key === 'Escape') {
                    hideResults();
                    unifiedSearchInput.blur();
                }
            });

            // Simplified click outside handler
            document.addEventListener('click', function(event) {
                if (!unifiedSearchInput || !unifiedSearchResults) {
                    return;
                }
                
                // Check if click is inside search input or results dropdown
                const isInside = unifiedSearchInput.contains(event.target) || 
                                unifiedSearchResults.contains(event.target);
                
                // Only hide if clicking outside AND dropdown is visible AND we're not keeping it open
                if (!isInside && 
                    !unifiedSearchResults.classList.contains('hidden') &&
                    !dropdownShouldStayOpen) {
                    hideResults();
                }
            });
            
            // Handle blur event with longer delay
            unifiedSearchInput.addEventListener('blur', function(e) {
                // Don't hide immediately - wait to see if user clicked on dropdown
                setTimeout(() => {
                    const activeElement = document.activeElement;
                    const isFocusInDropdown = unifiedSearchResults.contains(activeElement);
                    const isInputFocused = activeElement === unifiedSearchInput;
                    
                    // Only hide if focus is truly outside both
                    if (!isInputFocused && !isFocusInDropdown && !dropdownShouldStayOpen) {
                        hideResults();
                    }
                }, 250);
            });
        }

        async function fetchAutocomplete(query) {
            if (!unifiedSearchResults) return;
            
            try {
                // Show loading state
                unifiedSearchResults.innerHTML = '<div class="px-4 py-3 text-sm text-[var(--color-on-surface-variant)]"><i class="fas fa-spinner fa-spin mr-2"></i>Zoeken...</div>';
                unifiedSearchResults.classList.remove('hidden');
                
                const response = await fetch(`${autocompleteEndpoint}?q=${encodeURIComponent(query)}&limit=10`);
                const data = await response.json();
                renderSuggestions(data.suggestions || [], data.query_type, data.is_filter_value, data.filter_type, data.query || query);
            } catch (error) {
                console.error('Autocomplete error:', error);
                unifiedSearchResults.innerHTML = '<div class="px-4 py-3 text-sm text-[var(--color-on-surface-variant)]">Fout bij zoeken</div>';
            }
        }

        function renderSuggestions(suggestions, queryType = 'search', isFilterValue = false, filterType = null, originalQuery = '') {
            if (!unifiedSearchResults) return;
            
            if (!suggestions.length) {
                unifiedSearchResults.innerHTML = `<div class="px-4 py-3 text-sm text-[var(--color-on-surface-variant)]">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-search text-[var(--color-primary)]"></i>
                        <span>Zoeken naar "${escapeHtml(originalQuery)}"</span>
                    </div>
                </div>`;
                unifiedSearchResults.classList.remove('hidden');
                return;
            }
            
            // Separate by type
            const searchActions = suggestions.filter(s => s.type === 'search_action');
            const filterActions = suggestions.filter(s => s.type === 'filter_action');
            const documents = suggestions.filter(s => s.type === 'document' || s.type === 'suggestion');
            const filters = suggestions.filter(s => s.type.startsWith('filter_') && s.type !== 'filter_action');
            
            let html = '';
            let itemIndex = 0;
            
            // Quick Actions Section (Search & Filter actions)
            if (searchActions.length > 0 || filterActions.length > 0) {
                html += `<div class="px-3 py-2 text-xs font-semibold text-[var(--color-on-surface-variant)] uppercase bg-[var(--color-surface-variant)]/50">
                    <span>Snelle acties</span>
                </div>`;
                
                // Search action (always first)
                searchActions.forEach((action) => {
                    // Build URL with search term and preserve existing filters
                    const url = new URL(window.location.href);
                    url.searchParams.set('zoeken', action.query);
                    url.searchParams.set('pagina', '1');
                    // Keep existing filter parameters
                    const existingFilters = ['beschikbaarSinds', 'publicatiedatum_van', 'publicatiedatum_tot', 
                                           'documentsoort', 'thema', 'organisatie', 'informatiecategorie', 
                                           'bestandstype', 'status', 'titles_only'];
                    existingFilters.forEach(filter => {
                        const currentValue = new URL(window.location.href).searchParams.get(filter);
                        if (currentValue) {
                            url.searchParams.set(filter, currentValue);
                        }
                        // Handle array parameters
                        const currentValues = new URL(window.location.href).searchParams.getAll(filter + '[]');
                        if (currentValues.length > 0) {
                            url.searchParams.delete(filter + '[]');
                            currentValues.forEach(val => url.searchParams.append(filter + '[]', val));
                        }
                    });
                    // Trigger live search instead of redirecting
                    html += `<a href="#" 
                        onclick="event.preventDefault(); triggerLiveSearchFromSuggestion('${escapeHtml(action.query)}'); return false;"
                        class="block px-4 py-3 hover:bg-[var(--color-primary-light)]/30 transition-colors border-b border-[var(--color-outline-variant)] unified-search-item bg-[var(--color-primary)]/5"
                        data-index="${itemIndex++}">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-[var(--color-primary)]/20 flex items-center justify-center">
                                <i class="fas fa-search text-sm text-[var(--color-primary)]"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-[var(--color-on-surface)]">${escapeHtml(action.label)}</div>
                                <div class="text-xs text-[var(--color-on-surface-variant)] mt-0.5">${escapeHtml(action.description || 'Zoek in alle documenten')}</div>
                            </div>
                            <i class="fas fa-arrow-right text-xs text-[var(--color-on-surface-variant)] flex-shrink-0"></i>
                        </div>
                    </a>`;
                });
                
                // Filter action (if detected)
                filterActions.forEach((action) => {
                    const filterTypeLabels = {
                        'organisatie': 'Organisatie',
                        'thema': 'Thema',
                        'documentsoort': 'Documentsoort',
                        'informatiecategorie': 'Categorie'
                    };
                    // Use AJAX for filter actions too
                    html += `<a href="#" 
                        onclick="event.preventDefault(); applyFilterViaAjax('${action.filter_type}', '${escapeHtml(action.filter_value)}'); return false;"
                        class="block px-4 py-3 hover:bg-[var(--color-primary-light)]/30 transition-colors border-b border-[var(--color-outline-variant)] unified-search-item bg-blue-50"
                        data-index="${itemIndex++}">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-filter text-sm text-blue-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-[var(--color-on-surface)]">${escapeHtml(action.label)}</div>
                                <div class="text-xs text-[var(--color-on-surface-variant)] mt-0.5">${escapeHtml(action.description || 'Filter documenten')}</div>
                            </div>
                            <i class="fas fa-arrow-right text-xs text-[var(--color-on-surface-variant)] flex-shrink-0"></i>
                        </div>
                    </a>`;
                });
            }
            
            // Documents section
            if (documents.length) {
                const sectionTitle = isDossierPage ? 'Dossiers' : 'Documenten';
                html += `<div class="px-3 py-2 text-xs font-semibold text-[var(--color-on-surface-variant)] uppercase bg-[var(--color-surface-variant)]/50 border-t border-[var(--color-outline-variant)]">
                    <div class="flex items-center gap-2">
                        <i class="fas ${isDossierPage ? 'fa-folder' : 'fa-file'} text-[var(--color-primary)]"></i>
                        <span>${sectionTitle}</span>
                    </div>
                </div>`;
                documents.forEach((doc) => {
                    const docUrl = isDossierPage 
                        ? `/dossiers/${doc.id || ''}`
                        : `/open-overheid/documents/${doc.id || ''}`;
                    const displayText = doc.query || doc.title || '';
                    html += `<a href="${docUrl}" 
                        class="block px-4 py-2.5 hover:bg-[var(--color-primary-light)]/30 transition-colors border-b border-[var(--color-outline-variant)] last:border-b-0 unified-search-item"
                        data-index="${itemIndex++}">
                        <div class="flex items-start gap-3">
                            <i class="fas ${isDossierPage ? 'fa-folder-open' : 'fa-file-pdf'} text-xs text-[var(--color-primary)] mt-0.5 flex-shrink-0"></i>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-[var(--color-on-surface)] line-clamp-1">${escapeHtml(displayText)}</div>
                                ${doc.category ? `<div class="text-xs text-[var(--color-on-surface-variant)] mt-0.5">${escapeHtml(doc.category)}</div>` : ''}
                            </div>
                        </div>
                    </a>`;
                });
            }
            
            // Filters section (other filter suggestions)
            if (filters.length) {
                html += `<div class="px-3 py-2 text-xs font-semibold text-[var(--color-on-surface-variant)] uppercase bg-[var(--color-surface-variant)]/50 border-t border-[var(--color-outline-variant)]">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-filter text-[var(--color-primary)]"></i>
                        <span>Filter suggesties</span>
                    </div>
                </div>`;
                filters.forEach((filter) => {
                    const filterTypeLabels = {
                        'organisatie': 'Organisatie',
                        'thema': 'Thema',
                        'documentsoort': 'Documentsoort',
                        'informatiecategorie': 'Categorie'
                    };
                    const paramName = filter.filter_type === 'informatiecategorie' ? filter.filter_type : `${filter.filter_type}[]`;
                    const currentParams = getCurrentQueryParams();
                    html += `<a href="${searchRoute}?${paramName}=${encodeURIComponent(filter.value)}${currentParams}" 
                       class="block px-4 py-2.5 hover:bg-[var(--color-primary-light)]/30 transition-colors border-b border-[var(--color-outline-variant)] last:border-b-0 unified-search-item"
                       data-index="${itemIndex++}">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[var(--color-primary)]/10 text-[var(--color-primary)] border border-[var(--color-primary)]/20 flex-shrink-0">
                                ${filterTypeLabels[filter.filter_type] || filter.filter_type}
                            </span>
                            <span class="text-sm text-[var(--color-on-surface)] flex-1 min-w-0 truncate">${escapeHtml(filter.label)}</span>
                            <i class="fas fa-arrow-right text-xs text-[var(--color-on-surface-variant)] flex-shrink-0"></i>
                        </div>
                    </a>`;
                });
            }
            
            unifiedSearchResults.innerHTML = html;
            
            // Ensure dropdown is visible
            if (unifiedSearchResults.classList.contains('hidden')) {
                unifiedSearchResults.classList.remove('hidden');
            }
            
            // Prevent dropdown from closing when interacting with items
            const items = unifiedSearchResults.querySelectorAll('a.unified-search-item');
            items.forEach((item) => {
                // Keep dropdown open when mouse enters item
                item.addEventListener('mouseenter', function() {
                    dropdownShouldStayOpen = true;
                });
                
                // On click, allow navigation but keep flag briefly
                item.addEventListener('click', function(e) {
                    dropdownShouldStayOpen = true;
                    // Navigation happens via href - don't prevent
                });
            });
            
            // Keep dropdown visible when hovering over it
            unifiedSearchResults.addEventListener('mouseenter', function() {
                dropdownShouldStayOpen = true;
            });
            
            unifiedSearchResults.addEventListener('mouseleave', function() {
                // Allow dropdown to close when mouse leaves (unless input is focused)
                if (document.activeElement !== unifiedSearchInput) {
                    setTimeout(() => {
                        if (document.activeElement !== unifiedSearchInput) {
                            dropdownShouldStayOpen = false;
                        }
                    }, 200);
                }
            });
        }

        function updateSelectedItem(items) {
            items.forEach((item, index) => {
                if (index === selectedIndex) {
                    item.classList.add('bg-[var(--color-primary-light)]/30');
                    item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                } else {
                    item.classList.remove('bg-[var(--color-primary-light)]/30');
                }
            });
        }

        function hideResults() {
            if (unifiedSearchResults && !unifiedSearchResults.classList.contains('hidden')) {
                // Only hide if we're not supposed to keep it open
                if (!dropdownShouldStayOpen) {
                    unifiedSearchResults.classList.add('hidden');
                }
            }
            selectedIndex = -1;
        }

        function performLiveSearch(query) {
            if (!query) return;
            
            const url = new URL(window.location.href);
            url.searchParams.set('zoeken', query);
            url.searchParams.set('pagina', '1');
            
            // Neuro search removed - now premium-only via chat interface
            
            window.location.href = url.toString();
        }

        function getCurrentQueryParams() {
            const url = new URL(window.location.href);
            const searchQuery = url.searchParams.get('zoeken');
            return searchQuery ? '&zoeken=' + encodeURIComponent(searchQuery) : '';
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // LIGHTNING FAST search - uses fast-search API and updates page via AJAX
        async function triggerLiveSearchFromSuggestion(query) {
            if (!query) return;
            
            // Update input value
            if (unifiedSearchInput) {
                unifiedSearchInput.value = query;
            }
            
            // Hide dropdown
            if (unifiedSearchResults) {
                unifiedSearchResults.classList.add('hidden');
            }
            
            // Show loading state in results area
            const resultsArea = document.getElementById('search-results-area');
            if (!resultsArea) {
                console.error('Results area not found');
                return;
            }
            
            resultsArea.innerHTML = `
                <div class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-3xl text-[var(--color-primary)] mb-4"></i>
                        <p class="text-[var(--color-on-surface-variant)]">Zoeken naar "${escapeHtml(query)}"...</p>
                    </div>
                </div>
            `;
            
            try {
                // Use LIGHTNING FAST search API - single Typesense query
                const fastSearchEndpoint = '{{ route("api.fast-search") }}';
                const params = new URLSearchParams({
                    q: query,
                    page: 1,
                    per_page: 20,
                });
                
                // Preserve existing filters
                const currentUrl = new URL(window.location.href);
                
                // Preserve single value filters
                ['beschikbaarSinds', 'publicatiedatum_van', 'publicatiedatum_tot', 'sort', 'status', 'informatiecategorie', 'titles_only'].forEach(filter => {
                    const val = currentUrl.searchParams.get(filter);
                    if (val) {
                        params.set(filter, val);
                    }
                });
                
                // Handle array params (correct format: key[])
                ['thema', 'organisatie', 'documentsoort', 'bestandstype'].forEach(key => {
                    const values = currentUrl.searchParams.getAll(key + '[]');
                    values.forEach(val => {
                        if (val) {
                            params.append(key + '[]', val);
                        }
                    });
                });
                
                const response = await fetch(`${fastSearchEndpoint}?${params.toString()}`);
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.message || 'Search failed');
                }
                
                // Store search state for pagination (reuse currentUrl from above)
                // Note: We now read from URL directly in loadPage, but keep state for reference
                currentSearchState = {
                    query: query,
                    filters: {},
                    perPage: data.per_page || 20
                };
                
                // Preserve existing filters in state (reuse currentUrl)
                ['beschikbaarSinds', 'publicatiedatum_van', 'publicatiedatum_tot', 'sort', 'status', 'informatiecategorie', 'titles_only'].forEach(filter => {
                    const val = currentUrl.searchParams.get(filter);
                    if (val) {
                        currentSearchState.filters[filter] = val;
                    }
                });
                ['thema', 'organisatie', 'documentsoort', 'bestandstype'].forEach(filter => {
                    const vals = currentUrl.searchParams.getAll(filter + '[]');
                    if (vals.length > 0) {
                        currentSearchState.filters[filter] = vals;
                    }
                });
                
                // Update URL without reload - append zoeken parameter (multiple searches allowed)
                const newUrl = new URL(window.location.href);
                // Check if this search term already exists
                const existingSearches = newUrl.searchParams.getAll('zoeken');
                if (!existingSearches.includes(query)) {
                    newUrl.searchParams.append('zoeken', query);
                }
                newUrl.searchParams.set('pagina', '1');
                history.pushState({}, '', newUrl.toString());
                
                // Update results header with new count
                updateResultsHeader(data);
                
                // Update active filters (including new search term)
                updateActiveFilters();
                
                // Render results with pagination using shared function
                renderSearchResults(resultsArea, data);
                
                // Update filter counts if available
                if (data.filter_counts) {
                    updateFilterCounts(data.filter_counts);
                }
                
            } catch (error) {
                console.error('Fast search error:', error);
                resultsArea.innerHTML = `
                    <div class="text-center py-12">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                        <p class="text-red-600">Fout bij zoeken. Probeer het opnieuw.</p>
                    </div>
                `;
            }
        }
        
        // Update results header with new count and page info
        function updateResultsHeader(data) {
            // Find the results info section - look for h2 with "Zoekresultaten" or "Dossiers"
            const titleEl = document.querySelector('h2');
            if (titleEl && (titleEl.textContent.includes('Zoekresultaten') || titleEl.textContent.includes('Dossiers'))) {
                const resultsInfo = titleEl.parentElement;
                if (resultsInfo) {
                    const countEl = resultsInfo.querySelector('p');
                    if (countEl) {
                        const start = ((data.page - 1) * data.per_page) + 1;
                        const end = Math.min(data.page * data.per_page, data.found);
                        const formattedTotal = data.found.toLocaleString('nl-NL');
                        const isDossier = window.location.pathname.includes('/dossiers');
                        
                        titleEl.textContent = isDossier ? 'Dossiers' : 'Zoekresultaten';
                        countEl.textContent = `${start}-${end} van de ${formattedTotal} ${isDossier ? 'dossiers' : 'resultaten'}`;
                    }
                }
            }
        }
        
        // Update active filters dynamically
        function updateActiveFilters() {
            const currentUrl = new URL(window.location.href);
            const activeFilters = [];
            
            // Get all active filters from URL
            if (currentUrl.searchParams.get('beschikbaarSinds') && currentUrl.searchParams.get('beschikbaarSinds') !== 'zelf') {
                const periods = { 'week': 'Afgelopen week', 'maand': 'Afgelopen maand', 'jaar': 'Afgelopen jaar' };
                const value = currentUrl.searchParams.get('beschikbaarSinds');
                activeFilters.push({ type: 'beschikbaarSinds', value: value, label: periods[value] || value });
            }
            
            if (currentUrl.searchParams.get('publicatiedatum_van') || currentUrl.searchParams.get('publicatiedatum_tot')) {
                let dateLabel = '';
                const van = currentUrl.searchParams.get('publicatiedatum_van');
                const tot = currentUrl.searchParams.get('publicatiedatum_tot');
                if (van && tot) {
                    dateLabel = `${van} - ${tot}`;
                } else if (van) {
                    dateLabel = `Vanaf ${van}`;
                } else if (tot) {
                    dateLabel = `Tot ${tot}`;
                }
                activeFilters.push({ type: 'date', value: 'custom', label: dateLabel });
            }
            
            // Array filters
            ['documentsoort', 'thema', 'organisatie', 'bestandstype', 'publicatiebestemming'].forEach(filterType => {
                const values = currentUrl.searchParams.getAll(filterType + '[]');
                values.forEach(value => {
                    activeFilters.push({ type: filterType, value: value, label: value });
                });
            });
            
            // Single value filters
            if (currentUrl.searchParams.get('status')) {
                const statusLabels = { 'actief': 'Actief', 'gesloten': 'Gesloten' };
                const value = currentUrl.searchParams.get('status');
                activeFilters.push({ type: 'status', value: value, label: statusLabels[value] || value });
            }
            
            if (currentUrl.searchParams.get('informatiecategorie')) {
                const value = currentUrl.searchParams.get('informatiecategorie');
                activeFilters.push({ type: 'informatiecategorie', value: value, label: value });
            }
            
            if (currentUrl.searchParams.get('titles_only')) {
                activeFilters.push({ type: 'titles_only', value: '1', label: 'Alleen in titels' });
            }
            
            // Search term - add each search as a separate filter
            const zoekenValues = currentUrl.searchParams.getAll('zoeken');
            zoekenValues.forEach(value => {
                if (value && value.trim()) {
                    activeFilters.push({ type: 'zoeken', value: value, label: 'Zoekterm: ' + value });
                }
            });
            
            // Render active filters
            const headerCard = document.getElementById('search-header-card');
            if (!headerCard) return;
            
            // Find or create active filters container
            let filtersContainer = headerCard.querySelector('.active-filters-container');
            if (!filtersContainer) {
                // Find the results info section (div with border-t)
                const resultsInfoSection = headerCard.querySelector('.pt-3.border-t');
                if (resultsInfoSection && resultsInfoSection.parentElement) {
                    filtersContainer = document.createElement('div');
                    filtersContainer.className = 'pt-4 border-t border-[var(--color-outline-variant)] active-filters-container';
                    // Insert after results info section
                    resultsInfoSection.parentElement.insertBefore(filtersContainer, resultsInfoSection.nextSibling);
                } else {
                    // Fallback: append to the main container
                    const mainContainer = headerCard.querySelector('.space-y-5');
                    if (mainContainer) {
                        filtersContainer = document.createElement('div');
                        filtersContainer.className = 'pt-4 border-t border-[var(--color-outline-variant)] active-filters-container';
                        mainContainer.appendChild(filtersContainer);
                    } else {
                        return;
                    }
                }
            }
            
            if (activeFilters.length === 0) {
                filtersContainer.style.display = 'none';
                return;
            }
            
            filtersContainer.style.display = 'block';
            
            // Build filter HTML
            const clearUrl = new URL(window.location.pathname, window.location.origin);
            const isDossier = window.location.pathname.includes('/dossiers');
            
            let filtersHtml = '<div class="flex items-center justify-between mb-2">';
            filtersHtml += '<span class="text-sm font-medium text-[var(--color-on-surface)]">Actieve filters:</span>';
            filtersHtml += `
                <a href="${clearUrl.toString()}" 
                   class="inline-flex items-center gap-1 px-2 py-1 rounded-md 
                          bg-[var(--color-surface-variant)] text-[var(--color-on-surface-variant)] border border-[var(--color-outline-variant)]
                          hover:bg-[var(--color-surface-variant)]/80
                          focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20
                          transition-all duration-200 font-medium text-xs">
                    <i class="fas fa-times-circle text-xs" aria-hidden="true"></i>
                    <span>Alle filters wissen</span>
                </a>
            `;
            filtersHtml += '</div>';
            filtersHtml += '<div class="flex flex-wrap items-center gap-2">';
            
            activeFilters.forEach(filter => {
                const removeUrl = new URL(window.location.href);
                removeUrl.searchParams.set('pagina', '1');
                
                if (filter.type === 'beschikbaarSinds') {
                    removeUrl.searchParams.delete('beschikbaarSinds');
                } else if (filter.type === 'date') {
                    removeUrl.searchParams.delete('publicatiedatum_van');
                    removeUrl.searchParams.delete('publicatiedatum_tot');
                    removeUrl.searchParams.delete('beschikbaarSinds');
                } else if (filter.type === 'status') {
                    removeUrl.searchParams.delete('status');
                } else if (filter.type === 'titles_only') {
                    removeUrl.searchParams.delete('titles_only');
                } else if (filter.type === 'informatiecategorie') {
                    removeUrl.searchParams.delete('informatiecategorie');
                } else if (filter.type === 'zoeken') {
                    // Remove this specific search term
                    const zoekenParams = removeUrl.searchParams.getAll('zoeken');
                    removeUrl.searchParams.delete('zoeken');
                    zoekenParams.forEach(val => {
                        if (val !== filter.value) {
                            removeUrl.searchParams.append('zoeken', val);
                        }
                    });
                } else {
                    // Array filters
                    const currentValues = removeUrl.searchParams.getAll(filter.type + '[]');
                    const newValues = currentValues.filter(v => v !== filter.value);
                    removeUrl.searchParams.delete(filter.type + '[]');
                    newValues.forEach(v => removeUrl.searchParams.append(filter.type + '[]', v));
                }
                
                filtersHtml += `
                    <a href="${removeUrl.toString()}" 
                       class="inline-flex items-center gap-1 px-2 py-1 rounded-md 
                              bg-[var(--color-primary)]/10 text-[var(--color-primary)] border border-[var(--color-primary)]/20
                              hover:bg-[var(--color-primary)]/20 hover:border-[var(--color-primary)]/30
                              focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20
                              transition-all duration-200 font-medium text-xs
                              group"
                       title="Verwijder filter: ${escapeHtml(filter.label)}">
                        <span>${escapeHtml(filter.label)}</span>
                        <i class="fas fa-times text-[10px] opacity-70 group-hover:opacity-100 transition-opacity" aria-hidden="true"></i>
                    </a>
                `;
            });
            
            filtersHtml += '</div>';
            filtersContainer.innerHTML = filtersHtml;
        }
        
        // Update filter counts dynamically
        function updateFilterCounts(counts) {
            // Update documentsoort counts
            if (counts.documentsoort) {
                Object.entries(counts.documentsoort).forEach(([value, count]) => {
                    const countEl = document.querySelector(`[data-filter-count="documentsoort-${value}"]`);
                    if (countEl) countEl.textContent = `(${count})`;
                });
            }
            // Similar for other filter types...
        }
        
        // Render pagination HTML
        function renderPagination(currentPage, totalPages, perPage) {
            if (totalPages <= 1) return '';
            
            const hasPrevious = currentPage > 1;
            const hasNext = currentPage < totalPages;
            
            // Calculate page range (show max 5 pages around current)
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 2);
            
            // Adjust range to always show 5 pages if possible
            if (endPage - startPage < 4) {
                if (startPage === 1) {
                    endPage = Math.min(totalPages, 5);
                } else if (endPage === totalPages) {
                    startPage = Math.max(1, totalPages - 4);
                }
            }
            
            let html = `
                <nav class="mt-8 flex items-center justify-between border-t border-[var(--color-outline-variant)] bg-white px-4 py-3 sm:px-6 rounded-b-lg" aria-label="Pagination">
                    <div class="hidden sm:block">
                        <p class="text-sm text-[var(--color-on-surface-variant)]">
                            Pagina <span class="font-semibold">${currentPage}</span> van <span class="font-semibold">${totalPages}</span>
                        </p>
                    </div>
                    <div class="flex flex-1 justify-between sm:justify-end gap-2">
            `;
            
            // Previous button
            if (hasPrevious) {
                html += `
                    <button onclick="loadPage(${currentPage - 1})" 
                            class="relative inline-flex items-center rounded-md border border-[var(--color-outline-variant)] bg-white px-3 py-2 text-sm font-medium text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)]">
                        <i class="fas fa-chevron-left mr-1"></i> Vorige
                    </button>
                `;
            }
            
            // Page numbers (desktop only)
            html += `<div class="hidden sm:flex gap-1">`;
            
            if (startPage > 1) {
                html += `
                    <button onclick="loadPage(1)" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)] rounded-md">1</button>
                `;
                if (startPage > 2) {
                    html += `<span class="relative inline-flex items-center px-4 py-2 text-sm text-[var(--color-on-surface-variant)]">...</span>`;
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                if (i === currentPage) {
                    html += `
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold bg-[var(--color-primary)] text-white rounded-md">${i}</span>
                    `;
                } else {
                    html += `
                        <button onclick="loadPage(${i})" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)] rounded-md">${i}</button>
                    `;
                }
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    html += `<span class="relative inline-flex items-center px-4 py-2 text-sm text-[var(--color-on-surface-variant)]">...</span>`;
                }
                html += `
                    <button onclick="loadPage(${totalPages})" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)] rounded-md">${totalPages}</button>
                `;
            }
            
            html += `</div>`;
            
            // Next button
            if (hasNext) {
                html += `
                    <button onclick="loadPage(${currentPage + 1})" 
                            class="relative inline-flex items-center rounded-md border border-[var(--color-outline-variant)] bg-white px-3 py-2 text-sm font-medium text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)]">
                        Volgende <i class="fas fa-chevron-right ml-1"></i>
                    </button>
                `;
            }
            
            html += `</div></nav>`;
            return html;
        }
        
        // Store current search state for pagination
        let currentSearchState = {
            query: '',
            filters: {},
            perPage: 20
        };
        
        // Load specific page via AJAX
        async function loadPage(page) {
            const resultsArea = document.getElementById('search-results-area');
            if (!resultsArea) {
                console.error('Results area not found');
                return;
            }
            
            resultsArea.innerHTML = `
                <div class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-3xl text-[var(--color-primary)] mb-4"></i>
                        <p class="text-[var(--color-on-surface-variant)]">Pagina ${page} laden...</p>
                    </div>
                </div>
            `;
            
            try {
                const fastSearchEndpoint = '{{ route("api.fast-search") }}';
                const params = new URLSearchParams({
                    page: page,
                    per_page: 20,
                });
                
                // Read all filters from URL (more reliable than state)
                const currentUrl = new URL(window.location.href);
                
                // Get search query
                const zoeken = currentUrl.searchParams.get('zoeken');
                if (zoeken) {
                    params.set('q', zoeken);
                }
                
                // Preserve existing single value filters
                ['beschikbaarSinds', 'publicatiedatum_van', 'publicatiedatum_tot', 'status', 'informatiecategorie', 'titles_only', 'sort'].forEach(key => {
                    const val = currentUrl.searchParams.get(key);
                    if (val) {
                        params.set(key, val);
                    }
                });
                
                // Preserve existing array filters (correct format: key[])
                ['thema', 'organisatie', 'documentsoort', 'bestandstype'].forEach(key => {
                    const values = currentUrl.searchParams.getAll(key + '[]');
                    values.forEach(v => {
                        if (v) {
                            params.append(key + '[]', v);
                        }
                    });
                });
                
                const response = await fetch(`${fastSearchEndpoint}?${params.toString()}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.message || 'Failed to load page');
                }
                
                // Update URL
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.set('pagina', page.toString());
                history.pushState({}, '', newUrl.toString());
                
                // Update results header with new count
                updateResultsHeader(data);
                
                // Update active filters
                updateActiveFilters();
                
                // Render results with pagination
                renderSearchResults(resultsArea, data);
                
                // Scroll to top of results
                resultsArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
                
            } catch (error) {
                console.error('Page load error:', error);
                resultsArea.innerHTML = `
                    <div class="text-center py-12">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                        <p class="text-red-600">Fout bij laden van pagina. Probeer het opnieuw.</p>
                    </div>
                `;
            }
        }
        
        // Shared function to render search results with pagination
        function renderSearchResults(container, data) {
            if (data.hits && data.hits.length > 0) {
                let html = `<div class="flex flex-col gap-4">`;
                
                data.hits.forEach(hit => {
                    // Format publication date
                    let pubDate = '';
                    if (hit.publication_date) {
                        try {
                            const date = new Date(hit.publication_date);
                            if (!isNaN(date.getTime())) {
                                const day = String(date.getDate()).padStart(2, '0');
                                const month = String(date.getMonth() + 1).padStart(2, '0');
                                const year = date.getFullYear();
                                pubDate = `${day}-${month}-${year}`;
                            }
                        } catch(e) {
                            pubDate = hit.publication_date;
                        }
                    }
                    
                    // Determine type badge colors
                    const type = (hit.document_type || 'DOCUMENT').toUpperCase();
                    const typeColors = {
                        'DOCUMENT': { bg: 'bg-[var(--color-primary-dark)]', text: 'text-[var(--color-on-primary)]' },
                        'THEMA': { bg: 'bg-[var(--color-primary)]', text: 'text-[var(--color-on-primary)]' },
                        'DOSSIER': { bg: 'bg-sky-100 dark:bg-sky-900/50', text: 'text-sky-700 dark:text-sky-300' },
                        'REPORT': { bg: 'bg-yellow-100 dark:bg-yellow-900/50', text: 'text-yellow-700 dark:text-yellow-300' }
                    };
                    const badgeColors = typeColors[type] || typeColors['DOCUMENT'];
                    
                    html += `
                        <article class="rounded-lg bg-[var(--color-surface)] border border-[var(--color-outline-variant)]/60 overflow-hidden hover:border-[var(--color-primary)]/40 transition-all duration-300">
                            <!-- Item Header -->
                            <div class="px-5 py-2.5 bg-[var(--color-surface-variant)]/50 border-b border-[var(--color-outline-variant)]/40 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center font-semibold rounded-full px-2.5 py-0.5 text-[11px] leading-tight tracking-wide ${badgeColors.bg} ${badgeColors.text}">
                                        ${type}
                                    </span>
                                    ${hit.category ? `
                                        <span class="h-4 w-px bg-[var(--color-outline-variant)]/40"></span>
                                        <span class="text-[12px] font-semibold text-purple-700 dark:text-purple-400 uppercase tracking-wide flex items-center gap-1.5">
                                            <i class="fas fa-folder-open text-[10px] opacity-70"></i>
                                            ${escapeHtml(hit.category)}
                                        </span>
                                    ` : ''}
                                </div>
                                ${pubDate ? `
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-[var(--color-surface)] text-[var(--color-on-surface-variant)] border border-[var(--color-outline-variant)]/50 text-[11px] font-medium tracking-wide">
                                        <i class="far fa-calendar text-[10px] text-[var(--color-primary)] opacity-80"></i>
                                        ${pubDate}
                                    </div>
                                ` : ''}
                            </div>

                            <div class="flex flex-col md:flex-row items-stretch">
                                <!-- Column 1: Title, Organisation, Description -->
                                <div class="flex-1 px-6 py-5 border-b md:border-b-0 md:border-r border-[var(--color-outline-variant)]/30">
                                    <h3 class="text-[17px] font-semibold text-[var(--color-on-surface)] leading-snug tracking-[-0.01em] mb-1.5">
                                        <a href="/open-overheid/documents/${hit.id}" class="hover:text-[var(--color-primary)] transition-colors duration-200">
                                            ${escapeHtml(hit.title || 'Geen titel')}
                                        </a>
                                    </h3>
                                    ${hit.organisation ? `
                                        <div class="flex items-center gap-1.5 mb-2.5">
                                            <i class="far fa-building text-[11px] text-[var(--color-primary)]/70" aria-hidden="true"></i>
                                            <span class="text-[12px] font-medium text-[var(--color-on-surface-variant)]">
                                                ${escapeHtml(hit.organisation)}
                                            </span>
                                        </div>
                                    ` : ''}
                                    ${hit.description ? `
                                        <p class="text-[14px] text-[var(--color-on-surface-variant)]/80 leading-relaxed line-clamp-2 tracking-[0.01em] mb-3">
                                            ${escapeHtml(hit.description.length > 150 ? hit.description.substring(0, 150) + '...' : hit.description)}
                                        </p>
                                    ` : ''}
                                </div>

                                <!-- Column 2: Themes (if available) -->
                                <div class="w-full md:w-56 px-5 py-4 bg-[var(--color-surface-variant)]/5 flex flex-col justify-center gap-2 border-b md:border-b-0 md:border-r border-[var(--color-outline-variant)]/30">
                                    ${hit.theme ? `
                                        <div class="text-[10px] font-semibold text-[var(--color-on-surface-variant)]/60 uppercase tracking-wider mb-1">Thema's</div>
                                        <a href="#" 
                                           onclick="event.preventDefault(); applyThemeFilter('${escapeHtml(hit.theme)}'); return false;"
                                           class="inline-flex items-center gap-1.5 text-[12px] text-[var(--color-on-surface-variant)] font-medium hover:text-[var(--color-primary)] transition-colors">
                                            <i class="fas fa-tag text-[10px] text-[var(--color-primary)]/70" aria-hidden="true"></i>
                                            ${escapeHtml(hit.theme)}
                                        </a>
                                    ` : '<div class="text-[11px] text-[var(--color-on-surface-variant)]/50 italic">Geen thema\'s</div>'}
                                </div>

                                <!-- Column 3: Action Button -->
                                <div class="w-full md:w-14 flex items-center justify-center bg-[var(--color-surface-variant)]/5">
                                    <a href="/open-overheid/documents/${hit.id}" 
                                       class="w-9 h-9 rounded-full border border-[var(--color-outline-variant)]/50 flex items-center justify-center text-[var(--color-outline-variant)] hover:border-[var(--color-primary)] hover:text-[var(--color-primary)] hover:translate-x-0.5 transition-all duration-300"
                                       title="Bekijk document">
                                        <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    `;
                });
                
                html += `</div>`;
                
                // Add pagination
                if (data.total_pages > 1) {
                    html += renderPagination(data.page, data.total_pages, data.per_page);
                }
                
                container.innerHTML = html;
            } else {
                container.innerHTML = `
                    <div class="bg-[var(--color-surface)] rounded-md p-12 text-center border border-[var(--color-outline-variant)]">
                        <p class="text-[var(--font-size-body-large)] text-[var(--color-on-surface-variant)] mb-2">Geen resultaten gevonden.</p>
                        <p class="text-[var(--font-size-body-medium)] text-[var(--color-on-surface-variant)]">Probeer andere zoekwoorden of filters aan te passen.</p>
                    </div>
                `;
            }
        }
        
        // Apply theme filter (wrapper for applyFilterViaAjax)
        async function applyThemeFilter(themeValue) {
            // Get current URL to preserve existing filters
            const currentUrl = new URL(window.location.href);
            const existingThemes = currentUrl.searchParams.getAll('thema[]');
            
            // Check if theme is already in filters
            if (existingThemes.includes(themeValue)) {
                return; // Already filtered
            }
            
            // Apply the theme filter
            await applyFilterViaAjax('thema', themeValue);
        }
        
        // Apply filter via AJAX (lightning fast)
        async function applyFilterViaAjax(filterType, filterValue) {
            // Hide dropdown
            if (unifiedSearchResults) {
                unifiedSearchResults.classList.add('hidden');
            }
            
            // Show loading state
            const resultsArea = document.getElementById('search-results-area');
            if (!resultsArea) {
                console.error('Results area not found');
                return;
            }
            
            resultsArea.innerHTML = `
                <div class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-3xl text-[var(--color-primary)] mb-4"></i>
                        <p class="text-[var(--color-on-surface-variant)]">Filter toepassen...</p>
                    </div>
                </div>
            `;
            
            try {
                const fastSearchEndpoint = '{{ route("api.fast-search") }}';
                const params = new URLSearchParams({
                    page: 1,
                    per_page: 20,
                });
                
                // Get current search query and all existing filters
                const currentUrl = new URL(window.location.href);
                const currentQuery = currentUrl.searchParams.get('zoeken') || '';
                if (currentQuery) params.set('q', currentQuery);
                
                // Preserve existing filters
                ['beschikbaarSinds', 'publicatiedatum_van', 'publicatiedatum_tot', 'status', 'informatiecategorie', 'titles_only'].forEach(key => {
                    const val = currentUrl.searchParams.get(key);
                    if (val) params.set(key, val);
                });
                
                // Preserve existing array filters
                ['thema', 'organisatie', 'documentsoort', 'bestandstype'].forEach(key => {
                    const values = currentUrl.searchParams.getAll(key + '[]');
                    values.forEach(v => {
                        if (v && v !== filterValue) { // Don't duplicate the new filter value
                            params.append(key + '[]', v);
                        }
                    });
                });
                
                // Add the new filter
                if (filterType === 'informatiecategorie') {
                    params.set(filterType, filterValue);
                } else {
                    // For array filters, append the new value
                    params.append(filterType + '[]', filterValue);
                }
                
                const response = await fetch(`${fastSearchEndpoint}?${params.toString()}`);
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.message || 'Filter failed');
                }
                
                // Store search state for pagination
                const existingFilters = {};
                ['beschikbaarSinds', 'publicatiedatum_van', 'publicatiedatum_tot', 'status', 'informatiecategorie', 'titles_only'].forEach(key => {
                    const val = currentUrl.searchParams.get(key);
                    if (val) existingFilters[key] = val;
                });
                ['thema', 'organisatie', 'documentsoort', 'bestandstype'].forEach(key => {
                    const values = currentUrl.searchParams.getAll(key + '[]');
                    if (values.length > 0) {
                        existingFilters[key] = values;
                    }
                });
                
                // Add new filter to state
                if (filterType === 'informatiecategorie') {
                    existingFilters[filterType] = filterValue;
                } else {
                    if (!existingFilters[filterType]) {
                        existingFilters[filterType] = [];
                    }
                    if (!existingFilters[filterType].includes(filterValue)) {
                        existingFilters[filterType].push(filterValue);
                    }
                }
                
                currentSearchState = {
                    query: currentQuery,
                    filters: existingFilters,
                    perPage: data.per_page || 20
                };
                
                // Update URL - preserve all existing params and add new filter
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.set('pagina', '1');
                
                // Add the new filter to URL
                if (filterType === 'informatiecategorie') {
                    newUrl.searchParams.set(filterType, filterValue);
                } else {
                    // Check if already exists
                    const existingValues = newUrl.searchParams.getAll(filterType + '[]');
                    if (!existingValues.includes(filterValue)) {
                        newUrl.searchParams.append(filterType + '[]', filterValue);
                    }
                }
                
                history.pushState({}, '', newUrl.toString());
                
                // Update results header with new count
                updateResultsHeader(data);
                
                // Render results with pagination using shared function
                renderSearchResults(resultsArea, data);
                
            } catch (error) {
                console.error('Filter apply error:', error);
                resultsArea.innerHTML = `
                    <div class="text-center py-12">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                        <p class="text-red-600">Fout bij toepassen filter. Probeer het opnieuw.</p>
                    </div>
                `;
            }
        }

        // Neuro search removed - now premium-only via chat interface

        // Initialize custom date range visibility on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleCustomDateRange();
            
            // Check if TWPLUS elements are loaded (silently)
            if (!customElements.get('el-dialog')) {
                window.addEventListener('elements:ready', function() {
                    // TWPLUS elements ready
                });
            }

            // Handle subscription form submission
            const subscriptionForm = document.getElementById('subscription-form');
            if (subscriptionForm) {
                subscriptionForm.addEventListener('submit', function(e) {
                    // Collect all current filters from URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const allFilters = {};
                    
                    // Get search query
                    const zoeken = urlParams.get('zoeken');
                    if (zoeken) {
                        allFilters['zoeken'] = zoeken;
                    }
                    
                    // Get array filters - try both formats: key[] and key
                    ['thema', 'organisatie', 'documentsoort', 'bestandstype'].forEach(key => {
                        let values = [];
                        
                        // Try with brackets first (thema[])
                        const valuesWithBrackets = urlParams.getAll(key + '[]');
                        if (valuesWithBrackets.length > 0) {
                            values = valuesWithBrackets;
                        } else {
                            // Try without brackets (thema)
                            const valuesWithoutBrackets = urlParams.getAll(key);
                            if (valuesWithoutBrackets.length > 0) {
                                values = valuesWithoutBrackets;
                            } else {
                                // Try single value
                                const singleValue = urlParams.get(key);
                                if (singleValue) {
                                    values = [singleValue];
                                }
                            }
                        }
                        
                        // Filter out empty values
                        values = values.filter(v => v && v.trim() !== '');
                        
                        if (values.length > 0) {
                            allFilters[key] = values;
                        }
                    });
                    
                    // Get single value filters
                    ['status', 'informatiecategorie', 'beschikbaarSinds', 'publicatiedatum_van', 'publicatiedatum_tot', 'titles_only'].forEach(key => {
                        const value = urlParams.get(key);
                        if (value && value.trim() !== '') {
                            allFilters[key] = value;
                        }
                    });
                    
                    // Update hidden input with collected filters
                    const filtersInput = subscriptionForm.querySelector('input[name="filters"]');
                    if (filtersInput) {
                        filtersInput.value = JSON.stringify(allFilters);
                        console.log('Filters collected from URL:', allFilters);
                        console.log('Filters JSON:', filtersInput.value);
                    }
                    
                    // Add loading state
                    const submitButton = subscriptionForm.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.textContent = 'Bezig met verzenden...';
                    }
                });
            }
        });
    </script>

    <!-- Subscription Drawer -->
    <el-dialog>
        <dialog id="subscription-drawer" aria-labelledby="subscription-drawer-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-hidden bg-transparent not-open:hidden backdrop:bg-transparent">
            <el-dialog-backdrop class="absolute inset-0 bg-gray-500/75 transition-opacity duration-500 ease-in-out data-closed:opacity-0 dark:bg-gray-900/50"></el-dialog-backdrop>

            <div tabindex="0" class="absolute inset-0 pl-10 focus:outline-none sm:pl-16">
                <el-dialog-panel class="ml-auto block size-full max-w-md transform transition duration-500 ease-in-out data-closed:translate-x-full sm:duration-700">
                    <div class="relative flex h-full flex-col overflow-y-auto bg-white py-6 shadow-xl dark:bg-gray-800 dark:after:absolute dark:after:inset-y-0 dark:after:left-0 dark:after:w-px dark:after:bg-white/10">
                        <div class="px-4 sm:px-6">
                            <div class="flex items-start justify-between">
                                <h2 id="subscription-drawer-title" class="text-base font-semibold text-gray-900 dark:text-white">Abonneren op de laatste documenten</h2>
                                <div class="ml-3 flex h-7 items-center">
                                    <button type="button" command="close" commandfor="subscription-drawer" class="relative rounded-md text-gray-400 hover:text-gray-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:hover:text-white dark:focus-visible:outline-indigo-500">
                                        <span class="absolute -inset-2.5"></span>
                                        <span class="sr-only">Sluiten</span>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                                            <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="relative mt-6 flex-1 px-4 sm:px-6 pb-6">
                        <form id="subscription-form" method="POST" action="{{ route('subscriptions.store') }}" class="space-y-6">
                            @csrf
                            
                            <!-- Store current search query -->
                            <input type="hidden" name="search_query" value="{{ request('zoeken') }}">
                            
                            <!-- Store all active filters -->
                            @php
                                $allFilters = [];
                                
                                // Search query
                                if (request('zoeken')) {
                                    $allFilters['zoeken'] = request('zoeken');
                                }
                                
                                // Array filters (thema[], organisatie[], documentsoort[], bestandstype[], publicatiebestemming[])
                                foreach (['thema', 'organisatie', 'documentsoort', 'bestandstype', 'publicatiebestemming'] as $filterKey) {
                                    $values = request($filterKey, []);
                                    if (!empty($values)) {
                                        $allFilters[$filterKey] = is_array($values) ? $values : [$values];
                                    }
                                }
                                
                                // Single value filters
                                if (request('status')) {
                                    $allFilters['status'] = request('status');
                                }
                                
                                if (request('informatiecategorie')) {
                                    $allFilters['informatiecategorie'] = request('informatiecategorie');
                                }
                                
                                if (request('beschikbaarSinds')) {
                                    $allFilters['beschikbaarSinds'] = request('beschikbaarSinds');
                                }
                                
                                if (request('publicatiedatum_van')) {
                                    $allFilters['publicatiedatum_van'] = request('publicatiedatum_van');
                                }
                                
                                if (request('publicatiedatum_tot')) {
                                    $allFilters['publicatiedatum_tot'] = request('publicatiedatum_tot');
                                }
                                
                                if (request('titles_only')) {
                                    $allFilters['titles_only'] = request('titles_only');
                                }
                            @endphp
                            <input type="hidden" name="filters" value="{{ json_encode($allFilters) }}">

                            <!-- Active Filters Display -->
                            @if(!empty($activeFilters))
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <i class="fas fa-filter text-[var(--color-primary)] text-xs"></i>
                                    Actieve filters
                                </h3>
                                <div class="flex flex-wrap items-center gap-2">
                                    @foreach($activeFilters as $filter)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md 
                                                   bg-[var(--color-primary)]/10 text-[var(--color-primary)] border border-[var(--color-primary)]/20
                                                   text-xs font-medium">
                                            <i class="fas fa-tag text-[10px]"></i>
                                            {{ $filter['label'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Frequentie (Frequency) -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">
                                    Frequentie <span class="text-red-500">(verplicht)</span>
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    Selecteer met welke frequentie u e-mailattenderingen wilt ontvangen.
                                </p>
                                <div class="space-y-3">
                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input type="radio" name="frequency" value="immediate" class="mt-1 h-4 w-4 text-[var(--color-primary)] focus:ring-[var(--color-primary)] border-gray-300">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">Direct na publicatie</span>
                                        </div>
                                    </label>
                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input type="radio" name="frequency" value="daily" checked class="mt-1 h-4 w-4 text-[var(--color-primary)] focus:ring-[var(--color-primary)] border-gray-300">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">Dagelijks</span>
                                        </div>
                                    </label>
                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input type="radio" name="frequency" value="weekly" class="mt-1 h-4 w-4 text-[var(--color-primary)] focus:ring-[var(--color-primary)] border-gray-300">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">Wekelijks</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            @auth
                            <!-- Logged in user - show their email as readonly -->
                            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                            
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700 p-4">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                                    <div>
                                        <h4 class="text-sm font-semibold text-green-800 dark:text-green-300 mb-1">
                                            U bent ingelogd
                                        </h4>
                                        <p class="text-sm text-green-700 dark:text-green-400">
                                            Meldingen worden verstuurd naar: <strong>{{ auth()->user()->email }}</strong>
                                        </p>
                                        <p class="text-xs text-green-600 dark:text-green-500 mt-2">
                                            U kunt uw abonnementen beheren via <a href="{{ route('user.subscriptions') }}" class="underline hover:no-underline">Mijn Abonnementen</a>.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden consent for logged in users (auto-accepted) -->
                            <input type="hidden" name="consent" value="1">

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit" 
                                        class="w-full rounded-md bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-dark)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)] transition-colors duration-200">
                                    Abonneren
                                </button>
                            </div>
                            @else
                            <!-- Guest user - full form with email input -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">
                                    E-mailadres <span class="text-red-500">(verplicht)</span>
                                </h3>
                                <input type="email" 
                                       name="email" 
                                       required
                                       placeholder="naam@domein.nl"
                                       class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)] sm:text-sm px-3 py-2">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Voer hier uw e-mailadres in. Voorbeeld: 'naam@domein.nl'
                                </p>
                            </div>

                            <!-- Wat doen we met uw gegevens? -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                    Wat doen we met uw gegevens?
                                </h3>
                                <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                                    <p>
                                        Deze website gebruikt diensten van het ministerie van Algemene Zaken (AZ). 
                                        AZ gebruikt uw e-mailadres om u de afgesproken e-maileditie te sturen. 
                                        Uw gegevens worden gedeeld met een e-mailserviceprovider.
                                    </p>
                                    <p>
                                        We verzamelen verzendstatistieken. We gebruiken een pixel om te zien of u de e-mail heeft geopend. 
                                        We gebruiken unieke ID's voor hyperlinks om te zien of u op een link heeft geklikt. 
                                        AZ gebruikt alleen geanonimiseerde gegevens om de e-maileditie te optimaliseren.
                                    </p>
                                    <p>
                                        Uw e-mailadres bewaren we totdat u zich uitschrijft.
                                    </p>
                                </div>
                                
                                <!-- Expandable extra information -->
                                <div x-data="{ open: false }" class="mt-3">
                                    <button type="button" 
                                            @click="open = !open"
                                            class="flex items-center gap-2 text-sm text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] focus:outline-none">
                                        <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                        <span>Toon extra informatie over de verwerking van uw persoonsgegevens</span>
                                    </button>
                                    <div x-show="open" 
                                         x-transition
                                         class="mt-3 text-sm text-gray-700 dark:text-gray-300 space-y-2">
                                        <p>
                                            Voor meer informatie over hoe we uw persoonsgegevens verwerken, 
                                            verwijzen we u naar ons privacybeleid. U heeft het recht om uw 
                                            persoonsgegevens in te zien, te corrigeren of te verwijderen.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Akkoordverklaring -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                    Akkoordverklaring <span class="text-red-500">(verplicht)</span>
                                </h3>
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" 
                                           name="consent" 
                                           required
                                           class="mt-1 h-4 w-4 text-[var(--color-primary)] focus:ring-[var(--color-primary)] border-gray-300 rounded">
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        Ik ga akkoord en begrijp wat er met mijn persoonsgegevens wordt gedaan.
                                    </span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit" 
                                        class="w-full rounded-md bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-dark)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)] transition-colors duration-200">
                                    Aanvragen
                                </button>
                            </div>

                            <!-- Login suggestion for guests -->
                            <div class="text-center pt-2">
                                <p class="text-xs text-gray-500">
                                    Heeft u al een account? 
                                    <a href="{{ route('user.login') }}" class="text-[var(--color-primary)] hover:underline">Inloggen</a>
                                </p>
                            </div>
                            @endauth
                        </form>
                        </div>
                    </div>
                </el-dialog-panel>
            </div>
        </dialog>
    </el-dialog>
@endsection

