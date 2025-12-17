@extends('layouts.app')

@section('title', 'Thema\'s - Open Overheid')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => "Thema's", 'href' => null, 'current' => true],
    ];
@endphp

@section('content')
    <x-page-header 
        eyebrow="Verken documenten op onderwerp"
        title="Thema's"
        description="Zoek documenten op onderwerp zoals ruimtelijke ordening, onderwijs of zorg. Verken alle thema's en vind gerelateerde documenten."
        :breadcrumbs="$breadcrumbs"
    />

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-6 lg:px-8 pt-10 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-[300px_1fr] gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:sticky lg:top-[88px] h-fit lg:z-40" aria-label="Zoekfilters">
                <div class="bg-[var(--color-surface)] rounded-md p-6 border border-[var(--color-outline-variant)]">
                    <h2 class="text-base font-semibold mb-6 text-[var(--color-on-surface)] pb-4 border-b border-[var(--color-outline-variant)]">
                        Verfijn zoekopdracht
                    </h2>
                    
                    <form action="{{ route('themas.index') }}" method="GET" id="filter-form" class="space-y-6" onsubmit="convertDateInputsToFormat(event)">
                        <input type="hidden" name="sort" id="hidden-sort" value="{{ request('sort', 'relevance') }}">
                        <input type="hidden" name="per_page" id="hidden-per-page" value="{{ request('per_page', 20) }}">
                        
                        <!-- Search Keywords -->
                        <div class="space-y-3">
                            <x-input 
                                type="text"
                                name="zoeken"
                                id="sidebar-zoeken"
                                label="Zoekwoorden"
                                value="{{ request('zoeken') }}"
                                placeholder="Zoekwoorden..."
                                class="w-full px-3 py-2 rounded-md 
                                       border border-[var(--color-outline-variant)] bg-[var(--color-surface)]
                                       text-sm text-[var(--color-on-surface)]
                                       focus:outline-none focus:border-[var(--color-primary)]
                                       transition-colors duration-200"
                            />
                            <div class="flex items-center gap-3">
                                <input 
                                    type="checkbox" 
                                    id="sidebar-titles-only" 
                                    name="titles_only" 
                                    value="1"
                                    {{ request('titles_only') ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border border-[var(--color-outline)]
                                               focus:outline-none
                                               cursor-pointer text-[var(--color-primary)]
                                               checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                               transition-all duration-200"
                                >
                                <label for="sidebar-titles-only" class="text-sm text-[var(--color-on-surface)] cursor-pointer">
                                    Zoek alleen in titels
                                </label>
                            </div>
                            <button 
                                type="submit" 
                                class="w-full bg-[var(--color-primary)] text-[var(--color-on-primary)] 
                                       hover:bg-[var(--color-primary-dark)]
                                       focus:outline-none
                                       px-4 py-2 rounded-md font-medium text-sm
                                       transition-colors duration-200">
                                Zoeken
                            </button>
                            <button 
                                type="button" 
                                onclick="window.location.href='{{ route('themas.index') }}'"
                                class="w-full border border-[var(--color-outline-variant)] text-[var(--color-primary)]
                                       hover:bg-[var(--color-primary)]/10
                                       focus:outline-none
                                       px-4 py-2 rounded-md font-medium text-sm
                                       transition-colors duration-200">
                                Selectie wissen
                            </button>
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
                                        class="w-4 h-4 border border-[var(--color-outline)] 
                                               focus:outline-none
                                               cursor-pointer text-[var(--color-primary)]
                                               checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                               transition-all duration-200"
                                    >
                                    <label for="datum-geen" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
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
                                        class="w-4 h-4 border border-[var(--color-outline)] 
                                               focus:outline-none
                                               cursor-pointer text-[var(--color-primary)]
                                               checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                               transition-all duration-200"
                                    >
                                    <label for="datum-week" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                        Afgelopen week
                                    </label>
                                    <span class="text-sm text-[var(--color-on-surface-variant)]" id="count-week">({{ $filterCounts['week'] ?? 0 }})</span>
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
                                               cursor-pointer text-[var(--color-primary)]
                                               checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                               transition-all duration-200"
                                    >
                                    <label for="datum-maand" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                        Afgelopen maand
                                    </label>
                                    <span class="text-sm text-[var(--color-on-surface-variant)]" id="count-maand">({{ $filterCounts['maand'] ?? 0 }})</span>
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
                                               cursor-pointer text-[var(--color-primary)]
                                               checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                               transition-all duration-200"
                                    >
                                    <label for="datum-jaar" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                        Afgelopen jaar
                                    </label>
                                    <span class="text-sm text-[var(--color-on-surface-variant)]" id="count-jaar">({{ $filterCounts['jaar'] ?? 0 }})</span>
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
                                               cursor-pointer text-[var(--color-primary)]
                                               checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
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
                                                   cursor-pointer text-[var(--color-primary)]
                                                   checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                                   transition-all duration-200"
                                        >
                                        <label for="categorie-{{ md5($category) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $category }}
                                        </label>
                                        <span class="text-sm text-[var(--color-on-surface-variant)]">({{ $filterCounts['informatiecategorie'][$category] ?? 0 }})</span>
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
                                                       cursor-pointer text-[var(--color-primary)]
                                                       checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                                       transition-all duration-200"
                                            >
                                            <label for="categorie-{{ md5($category) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                                {{ $category }}
                                            </label>
                                            <span class="text-sm text-[var(--color-on-surface-variant)]">({{ $filterCounts['informatiecategorie'][$category] ?? 0 }})</span>
                                        </div>
                                    @endforeach
                                </div>
                                <button 
                                    type="button" 
                                    onclick="toggleFilterSection('informatiecategorie-more', 'informatiecategorie-toggle')"
                                    id="informatiecategorie-toggle"
                                    class="text-sm text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] font-medium 
                                           focus:outline-none transition-colors duration-200">
                                    Toon meer
                                </button>
                                @endif
                                @if($selectedCategory)
                                <div class="pt-2">
                                    <button 
                                        type="button" 
                                        onclick="document.getElementById('categorie-none').checked = true; document.getElementById('filter-form').submit();"
                                        class="text-xs text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] font-medium 
                                               focus:outline-none transition-colors duration-200 inline-flex items-center gap-1">
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
                                                   cursor-pointer text-[var(--color-primary)]
                                                   checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                                   transition-all duration-200"
                                        >
                                        <label for="soort-{{ str_replace(' ', '-', strtolower($type)) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $type }}
                                        </label>
                                        <span class="text-sm text-[var(--color-on-surface-variant)]">({{ $filterCounts['documentsoort'][$type] ?? 0 }})</span>
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
                                                       cursor-pointer text-[var(--color-primary)]
                                                       checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                                       transition-all duration-200"
                                            >
                                            <label for="soort-{{ str_replace(' ', '-', strtolower($type)) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                                {{ $type }}
                                            </label>
                                            <span class="text-sm text-[var(--color-on-surface-variant)]">({{ $filterCounts['documentsoort'][$type] ?? 0 }})</span>
                                        </div>
                                    @endforeach
                                </div>
                                <button 
                                    type="button" 
                                    onclick="toggleFilterSection('documentsoort-more', 'documentsoort-toggle')"
                                    id="documentsoort-toggle"
                                    class="text-sm text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] font-medium 
                                           focus:outline-none transition-colors duration-200">
                                    Toon meer
                                </button>
                                @endif
                            </div>
                        </div>
                        
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
                                                   cursor-pointer text-[var(--color-primary)]
                                                   checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                                   transition-all duration-200"
                                        >
                                        <label for="bestandstype-{{ strtolower(str_replace([' ', '-'], ['', ''], $label)) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $label }}
                                        </label>
                                        <span class="text-sm text-[var(--color-on-surface-variant)]">({{ $filterCounts['bestandstype'][$label] ?? 0 }})</span>
                                    </div>
                                @endforeach
                                <button 
                                    type="button" 
                                    class="text-sm text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] font-medium 
                                           focus:outline-none transition-colors duration-200">
                                    Toon meer
                                </button>
                            </div>
                        </div>
                        
                        <!-- Theme Filter -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface)]">Thema</h3>
                            <div class="space-y-2">
                                @php
                                    $allThemes = $allFilterOptions['thema'] ?? [];
                                    $visibleThemes = array_slice($allThemes, 0, 1);
                                    $hiddenThemes = array_slice($allThemes, 1);
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
                                                   cursor-pointer text-[var(--color-primary)]
                                                   checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                                   transition-all duration-200"
                                        >
                                        <label for="thema-{{ str_replace(' ', '-', strtolower($theme)) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $theme }}
                                        </label>
                                        <span class="text-sm text-[var(--color-on-surface-variant)]">({{ $filterCounts['thema'][$theme] ?? 0 }})</span>
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
                                                       cursor-pointer text-[var(--color-primary)]
                                                       checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                                       transition-all duration-200"
                                            >
                                            <label for="thema-{{ str_replace(' ', '-', strtolower($theme)) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                                {{ $theme }}
                                            </label>
                                            <span class="text-sm text-[var(--color-on-surface-variant)]">({{ $filterCounts['thema'][$theme] ?? 0 }})</span>
                                        </div>
                                    @endforeach
                                </div>
                                <button 
                                    type="button" 
                                    onclick="toggleFilterSection('thema-more', 'thema-toggle')"
                                    id="thema-toggle"
                                    class="text-sm text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] font-medium 
                                           focus:outline-none transition-colors duration-200">
                                    Toon meer
                                </button>
                                @endif
                            </div>
                        </div>
                        
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
                                                   cursor-pointer text-[var(--color-primary)]
                                                   checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                                   transition-all duration-200"
                                        >
                                        <label for="org-{{ md5($org) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $org }}
                                        </label>
                                        <span class="text-sm text-[var(--color-on-surface-variant)]">({{ number_format($filterCounts['organisatie'][$org] ?? 0, 0, ',', '.') }})</span>
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
                                                       cursor-pointer text-[var(--color-primary)]
                                                       checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                                       transition-all duration-200"
                                            >
                                            <label for="org-{{ md5($org) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                                {{ $org }}
                                            </label>
                                            <span class="text-sm text-[var(--color-on-surface-variant)]">({{ number_format($filterCounts['organisatie'][$org] ?? 0, 0, ',', '.') }})</span>
                                        </div>
                                    @endforeach
                                </div>
                                <button 
                                    type="button" 
                                    onclick="toggleFilterSection('organisatie-more', 'organisatie-toggle')"
                                    id="organisatie-toggle"
                                    class="text-sm text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] font-medium 
                                           focus:outline-none transition-colors duration-200">
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
                    if (request('documentsoort')) {
                        foreach ((array)request('documentsoort') as $type) {
                            $activeFilters[] = ['type' => 'documentsoort', 'value' => $type, 'label' => $type];
                        }
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
                    if (request('titles_only')) {
                        $activeFilters[] = ['type' => 'titles_only', 'value' => '1', 'label' => 'Alleen in titels'];
                    }
                @endphp
                
                <!-- Quick Filter & Active Filters Card -->
                <div class="bg-[var(--color-surface)] rounded-md border border-[var(--color-outline-variant)] divide-y divide-[var(--color-outline-variant)]">
                    <!-- Quick Filter Combobox -->
                    <div class="p-4">
                        <label for="quick-filter" class="block text-sm font-medium text-[var(--color-on-surface)] mb-2">
                            Snel filteren
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-search text-[var(--color-on-surface-variant)]" aria-hidden="true"></i>
                            </div>
                            <input 
                                type="text" 
                                id="quick-filter"
                                name="quick-filter"
                                placeholder="Type om te filteren op thema..."
                                class="block w-full pl-10 pr-3 py-2 rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)]
                                       text-sm text-[var(--color-on-surface)] placeholder:text-[var(--color-on-surface-variant)]
                                       focus:outline-none focus:border-[var(--color-primary)]
                                       transition-colors duration-200"
                                autocomplete="off"
                                onkeyup="filterQuickOptions(this.value)"
                            >
                            <div id="quick-filter-results" class="absolute z-10 mt-1 w-full bg-[var(--color-surface)] rounded-md border border-[var(--color-outline-variant)] hidden max-h-60 overflow-auto">
                                <!-- Results populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Active Filters -->
                    @if(!empty($activeFilters))
                    <div class="p-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-[var(--color-on-surface)] mr-2">Actieve filters:</span>
                            @foreach($activeFilters as $filter)
                                @php
                                    $removeUrl = request()->fullUrlWithQuery(['pagina' => 1]);
                                    if ($filter['type'] === 'beschikbaarSinds') {
                                        $removeUrl = request()->fullUrlWithQuery(['beschikbaarSinds' => null, 'pagina' => 1]);
                                    } elseif ($filter['type'] === 'date') {
                                        $removeUrl = request()->fullUrlWithQuery(['publicatiedatum_van' => null, 'publicatiedatum_tot' => null, 'beschikbaarSinds' => null, 'pagina' => 1]);
                                    } elseif ($filter['type'] === 'titles_only') {
                                        $removeUrl = request()->fullUrlWithQuery(['titles_only' => null, 'pagina' => 1]);
                                    } elseif ($filter['type'] === 'informatiecategorie') {
                                        $removeUrl = request()->fullUrlWithQuery(['informatiecategorie' => null, 'pagina' => 1]);
                                    } else {
                                        $currentValues = (array)request($filter['type'], []);
                                        $newValues = array_values(array_filter($currentValues, fn($v) => $v !== $filter['value']));
                                        $removeUrl = request()->fullUrlWithQuery([$filter['type'] => $newValues, 'pagina' => 1]);
                                    }
                                @endphp
                                <a href="{{ $removeUrl }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-md 
                                          bg-[var(--color-primary)]/10 text-[var(--color-primary)] border border-[var(--color-primary)]/20
                                          hover:bg-[var(--color-primary)]/20 hover:border-[var(--color-primary)]/30
                                          focus:outline-none
                                          transition-all duration-200 font-medium text-sm
                                          group"
                                   title="Verwijder filter: {{ $filter['label'] }}">
                                    <span>{{ $filter['label'] }}</span>
                                    <i class="fas fa-times text-xs opacity-70 group-hover:opacity-100 transition-opacity" aria-hidden="true"></i>
                                </a>
                            @endforeach
                            <a href="{{ route('themas.index') }}{{ request('zoeken') ? '?zoeken=' . urlencode(request('zoeken')) : '' }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-md 
                                      bg-[var(--color-surface)]-variant text-[var(--color-on-surface-variant)] border border-[var(--color-outline-variant)]
                                      hover:bg-[var(--color-surface)]-variant/80
                                      focus:outline-none
                                      transition-all duration-200 font-medium text-sm
                                      ml-auto">
                                <i class="fas fa-times-circle text-sm" aria-hidden="true"></i>
                                <span>Alle filters wissen</span>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Results Header -->
                <div class="bg-[var(--color-surface)] rounded-md border border-[var(--color-outline-variant)]">
                    <div class="px-4 py-3 sm:px-6 sm:py-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h2 class="text-base font-semibold text-[var(--color-on-surface)]">
                                    Thema's
                                </h2>
                                <p class="mt-1 text-sm text-[var(--color-on-surface-variant)]">
                                    {{ (($results['page'] ?? 1) - 1) * ($results['perPage'] ?? 20) + 1 }}-{{ min(($results['page'] ?? 1) * ($results['perPage'] ?? 20), $results['total'] ?? 0) }} van de {{ number_format($results['total'] ?? 0, 0, ',', '.') }} documenten
                                </p>
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                <div class="flex items-center gap-2">
                                    <label for="sort-select" class="text-sm text-[var(--color-on-surface-variant)]">Sorteer op:</label>
                                    <select 
                                        id="sort-select"
                                        name="sort" 
                                        class="rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)] px-3 py-1.5 text-sm text-[var(--color-on-surface)] focus:outline-none focus:border-[var(--color-primary)] cursor-pointer"
                                        onchange="updateSort(this.value)"
                                    >
                                        <option value="relevance" {{ request('sort', 'relevance') === 'relevance' ? 'selected' : '' }}>Relevantie</option>
                                        <option value="publication_date" {{ request('sort') === 'publication_date' ? 'selected' : '' }}>Publicatiedatum</option>
                                        <option value="modified_date" {{ request('sort') === 'modified_date' ? 'selected' : '' }}>Laatst gewijzigd</option>
                                    </select>
                                </div>
                                <div class="flex items-center gap-2">
                                    <label class="text-sm text-[var(--color-on-surface-variant)]">Aantal:</label>
                                    <div class="inline-flex rounded-md border border-[var(--color-outline-variant)]">
                                        <a href="{{ request()->fullUrlWithQuery(['per_page' => 10, 'pagina' => 1]) }}" 
                                           class="px-3 py-1.5 text-sm font-medium transition-colors duration-200 focus:outline-none
                                                  {{ request('per_page', 20) == 10 ? 'bg-[var(--color-primary)] text-[var(--color-on-primary)]' : 'text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)]' }}
                                                  {{ request('per_page', 20) == 10 ? '' : 'border-r border-[var(--color-outline-variant)]' }}">
                                            10
                                        </a>
                                        <a href="{{ request()->fullUrlWithQuery(['per_page' => 20, 'pagina' => 1]) }}" 
                                           class="px-3 py-1.5 text-sm font-medium transition-colors duration-200 focus:outline-none border-r border-[var(--color-outline-variant)]
                                                  {{ request('per_page', 20) == 20 ? 'bg-[var(--color-primary)] text-[var(--color-on-primary)]' : 'text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)]' }}">
                                            20
                                        </a>
                                        <a href="{{ request()->fullUrlWithQuery(['per_page' => 50, 'pagina' => 1]) }}" 
                                           class="px-3 py-1.5 text-sm font-medium transition-colors duration-200 focus:outline-none
                                                  {{ request('per_page', 20) == 50 ? 'bg-[var(--color-primary)] text-[var(--color-on-primary)]' : 'text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)]' }}">
                                            50
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Error Message -->
                @if(isset($error))
                    <div class="bg-red-50 text-red-900 dark:bg-red-900/20 dark:text-red-200 p-4 rounded-md border border-error" role="alert">
                        <p class="text-sm text-red-600 dark:text-red-400 font-medium">
                            Er is een fout opgetreden: {{ $error }}
                        </p>
                    </div>
                @endif
                
                <!-- Results List -->
                @if(empty($results['items']))
                    <div class="bg-[var(--color-surface)] rounded-md p-12 text-center border border-[var(--color-outline-variant)]">
                        <p class="text-base text-[var(--color-on-surface-variant)] mb-2">Geen resultaten gevonden.</p>
                        <p class="text-sm text-[var(--color-on-surface-variant)]">Probeer andere zoekwoorden of filters aan te passen.</p>
                    </div>
                @else
                    <!-- Simple List with Heading - Tailwind UI Style -->
                    <div class="bg-[var(--color-surface)] rounded-md border border-[var(--color-outline-variant)] overflow-hidden">
                        <div class="px-6 py-4 border-b border-[var(--color-outline-variant)] bg-[var(--color-surface)]-variant/30">
                            <h3 class="text-base font-medium text-[var(--color-on-surface)]">
                                Documenten met thema
                            </h3>
                        </div>
                        <ul role="list" class="divide-y divide-[var(--color-outline-variant)]">
                            @foreach($results['items'] as $item)
                                <li class="px-4 py-3 hover:bg-[var(--color-primary)]/5 transition-colors duration-150">
                                    <a href="/open-overheid/documents/{{ $item->external_id }}" 
                                       class="block focus:outline-none group">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-2 mb-1.5">
                                                    <h3 class="text-sm font-semibold text-[var(--color-on-surface)] group-hover:text-[var(--color-primary)] transition-colors duration-200 line-clamp-2 flex-1 pr-2">
                                                        {{ $item->title ?? 'Geen titel' }}
                                                    </h3>
                                                    <div class="flex items-center gap-2 shrink-0">
                                                        @if($item->category)
                                                            <a href="{{ route('themas.index') }}?informatiecategorie={{ urlencode($item->category) }}{{ request('zoeken') ? '&zoeken=' . urlencode(request('zoeken')) : '' }}" 
                                                               onclick="event.stopPropagation();"
                                                               class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium 
                                                                      bg-[var(--color-primary)]/10 text-[var(--color-primary)] border border-[var(--color-primary)]/20
                                                                      hover:bg-[var(--color-primary)]/20 hover:border-[var(--color-primary)]/30
                                                                      focus:outline-none
                                                                      transition-all duration-200 shrink-0"
                                                               title="Filter op {{ $item->formatted_category ?? $item->category }}">
                                                                {{ $item->formatted_category ?? $item->category }}
                                                            </a>
                                                        @endif
                                                        @if($item->external_id)
                                                            <a href="https://open.overheid.nl/details/{{ $item->external_id }}" 
                                                               target="_blank"
                                                               rel="noopener noreferrer"
                                                               onclick="event.stopPropagation();"
                                                               class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md
                                                                      bg-[var(--color-primary)]/5 text-[var(--color-primary)] border border-[var(--color-primary)]/20
                                                                      hover:bg-[var(--color-primary)]/10 hover:border-[var(--color-primary)]/30
                                                                      focus:outline-none
                                                                      transition-all duration-200 text-[11px] font-medium
                                                                      shrink-0"
                                                               title="Bekijk op open.overheid.nl">
                                                                <i class="fas fa-external-link-alt text-[10px]" aria-hidden="true"></i>
                                                                <span class="hidden sm:inline">Open.overheid.nl</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @if($item->description)
                                                <p class="text-xs text-[var(--color-on-surface-variant)] mb-2.5 line-clamp-2 leading-relaxed">
                                                    {{ \Illuminate\Support\Str::limit($item->description, 150) }}
                                                </p>
                                            @endif
                                            
                                            {{-- Metadata Grid - Modern & Compact --}}
                                            <div class="mt-2.5 pt-2.5 border-t border-[var(--color-outline-variant)]/50">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    {{-- File Type --}}
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-medium
                                                           bg-[var(--color-surface-variant)]/50 text-[var(--color-on-surface-variant)] border border-[var(--color-outline-variant)]/50">
                                                        <i class="fas fa-file-pdf text-[10px] text-red-500" aria-hidden="true"></i>
                                                        <span>PDF</span>
                                                    </span>
                                                    
                                                    {{-- Publication Date --}}
                                                    @if($item->publication_date)
                                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-medium
                                                               bg-[var(--color-surface-variant)]/50 text-[var(--color-on-surface-variant)] border border-[var(--color-outline-variant)]/50">
                                                            <i class="fas fa-calendar text-[10px] text-[var(--color-primary)]" aria-hidden="true"></i>
                                                            <span>{{ $item->publication_date->format('d-m-Y') }}</span>
                                                        </span>
                                                    @endif
                                                    
                                                    {{-- Updated Date --}}
                                                    @if($item->updated_at)
                                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-medium
                                                               bg-[var(--color-surface-variant)]/50 text-[var(--color-on-surface-variant)] border border-[var(--color-outline-variant)]/50">
                                                            <i class="fas fa-edit text-[10px] text-[var(--color-primary)]" aria-hidden="true"></i>
                                                            <span>{{ $item->updated_at->format('d-m-Y') }}</span>
                                                        </span>
                                                    @endif
                                                    
                                                    {{-- Theme --}}
                                                    @if($item->theme)
                                                        <a href="{{ route('themas.index') }}?thema[]={{ urlencode($item->theme) }}" 
                                                           onclick="event.stopPropagation();"
                                                           class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-medium
                                                                  bg-[var(--color-primary)]/10 text-[var(--color-primary)] border border-[var(--color-primary)]/20
                                                                  hover:bg-[var(--color-primary)]/20 hover:border-[var(--color-primary)]/30
                                                                  focus:outline-none
                                                                  transition-all duration-200"
                                                           title="Filter op {{ $item->theme }}">
                                                            <i class="fas fa-tag text-[10px]" aria-hidden="true"></i>
                                                            <span class="max-w-[100px] truncate">{{ $item->theme }}</span>
                                                            <i class="fas fa-filter text-[9px] opacity-60" aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    {{-- Organisation --}}
                                                    @if($item->organisation)
                                                        <a href="{{ route('themas.index') }}?organisatie[]={{ urlencode($item->organisation) }}" 
                                                           onclick="event.stopPropagation();"
                                                           class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-medium
                                                                  bg-[var(--color-primary)]/10 text-[var(--color-primary)] border border-[var(--color-primary)]/20
                                                                  hover:bg-[var(--color-primary)]/20 hover:border-[var(--color-primary)]/30
                                                                  focus:outline-none
                                                                  transition-all duration-200"
                                                           title="Filter op {{ $item->organisation }}">
                                                            <i class="fas fa-building text-[10px]" aria-hidden="true"></i>
                                                            <span class="max-w-[120px] truncate">{{ $item->organisation }}</span>
                                                            <i class="fas fa-filter text-[9px] opacity-60" aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @if(isset($item->metadata) && isset($item->metadata['document']['weblocatie']))
                                                <div class="mt-2.5 pt-2.5 border-t border-[var(--color-outline-variant)]/50">
                                                    <a href="{{ $item->metadata['document']['weblocatie'] }}" 
                                                       target="_blank" 
                                                       rel="noopener noreferrer"
                                                       onclick="event.stopPropagation();"
                                                       class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-medium
                                                              bg-[var(--color-primary)]/10 text-[var(--color-primary)] border border-[var(--color-primary)]/20
                                                              hover:bg-[var(--color-primary)]/20 hover:border-[var(--color-primary)]/30
                                                              focus:outline-none
                                                              transition-all duration-200">
                                                        <i class="fas fa-external-link-alt text-[10px]" aria-hidden="true"></i>
                                                        <span>Officielebekendmakingen.nl</span>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
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
                                       class="relative inline-flex items-center rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)] px-4 py-2 text-sm font-medium text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)] dark:border-white/10 dark:bg-white/5 dark:text-gray-200 dark:hover:bg-white/10">
                                        Vorige
                                    </a>
                                @else
                                    <span class="relative inline-flex items-center rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)] px-4 py-2 text-sm font-medium text-[var(--color-on-surface-variant)] opacity-50 cursor-not-allowed dark:border-white/10 dark:bg-white/5 dark:text-gray-400">
                                        Vorige
                                    </span>
                                @endif
                                @if(($results['hasNextPage'] ?? false))
                                    <a href="{{ request()->fullUrlWithQuery(['pagina' => $currentPage + 1]) }}" 
                                       class="relative ml-3 inline-flex items-center rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)] px-4 py-2 text-sm font-medium text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)] dark:border-white/10 dark:bg-white/5 dark:text-gray-200 dark:hover:bg-white/10">
                                        Volgende
                                    </a>
                                @else
                                    <span class="relative ml-3 inline-flex items-center rounded-md border border-[var(--color-outline-variant)] bg-[var(--color-surface)] px-4 py-2 text-sm font-medium text-[var(--color-on-surface-variant)] opacity-50 cursor-not-allowed dark:border-white/10 dark:bg-white/5 dark:text-gray-400">
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
                                        documenten
                                    </p>
                                </div>
                                <div>
                                    <nav aria-label="Paginatie" class="isolate inline-flex -space-x-px rounded-md">
                                        @if(($results['hasPreviousPage'] ?? false))
                                            <a href="{{ request()->fullUrlWithQuery(['pagina' => $currentPage - 1]) }}" 
                                               class="relative inline-flex items-center rounded-md px-2 py-2 text-[var(--color-on-surface-variant)]  hover:bg-[var(--color-surface-variant)] focus:z-20 focus:outline-none  dark:hover:bg-white/5">
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
                                               class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-[var(--color-on-surface)]  hover:bg-[var(--color-surface-variant)] focus:z-20 focus:outline-none dark:text-gray-200  dark:hover:bg-white/5">
                                                1
                                            </a>
                                            @if($startPage > 2)
                                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-[var(--color-on-surface-variant)]  focus:outline-none dark:text-gray-400 ">...</span>
                                            @endif
                                        @endif
                                        
                                        @for($i = $startPage; $i <= $endPage; $i++)
                                            @if($i == $currentPage)
                                                <a href="#" aria-current="page" class="relative z-10 inline-flex items-center bg-[var(--color-primary)] px-4 py-2 text-sm font-semibold text-[var(--color-on-primary)] focus:z-20 focus-visible:outline-none dark:bg-[var(--color-primary)] dark:focus-visible:outline-none">
                                                    {{ $i }}
                                                </a>
                                            @else
                                                <a href="{{ request()->fullUrlWithQuery(['pagina' => $i]) }}" 
                                                   class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-[var(--color-on-surface)]  hover:bg-[var(--color-surface-variant)] focus:z-20 focus:outline-none dark:text-gray-200  dark:hover:bg-white/5">
                                                    {{ $i }}
                                                </a>
                                            @endif
                                        @endfor
                                        
                                        @if($endPage < $totalPages)
                                            @if($endPage < $totalPages - 1)
                                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-[var(--color-on-surface-variant)]  focus:outline-none dark:text-gray-400 ">...</span>
                                            @endif
                                            <a href="{{ request()->fullUrlWithQuery(['pagina' => $totalPages]) }}" 
                                               class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-[var(--color-on-surface)]  hover:bg-[var(--color-surface-variant)] focus:z-20 focus:outline-none dark:text-gray-200  dark:hover:bg-white/5">
                                                {{ $totalPages }}
                                            </a>
                                        @endif
                                        
                                        @if(($results['hasNextPage'] ?? false))
                                            <a href="{{ request()->fullUrlWithQuery(['pagina' => $currentPage + 1]) }}" 
                                               class="relative inline-flex items-center rounded-md px-2 py-2 text-[var(--color-on-surface-variant)]  hover:bg-[var(--color-surface-variant)] focus:z-20 focus:outline-none  dark:hover:bg-white/5">
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
    </main>
@endsection

@push('scripts')
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

        // Quick Filter Combobox functionality
        const quickFilterOptions = {
            thema: @json($allFilterOptions['thema'] ?? []),
        };

        function filterQuickOptions(query) {
            const resultsDiv = document.getElementById('quick-filter-results');
            if (!resultsDiv) return;

            if (!query || query.length < 2) {
                resultsDiv.classList.add('hidden');
                return;
            }

            const lowerQuery = query.toLowerCase();
            const matches = [];

            // Search only in themes (thema domain)
            quickFilterOptions.thema.forEach(theme => {
                if (theme.toLowerCase().includes(lowerQuery)) {
                    matches.push({
                        type: 'thema',
                        label: theme,
                        value: theme,
                        secondary: 'Thema'
                    });
                }
            });

            if (matches.length === 0) {
                resultsDiv.innerHTML = '<div class="px-4 py-3 text-sm text-[var(--color-on-surface-variant)]">Geen resultaten gevonden</div>';
                resultsDiv.classList.remove('hidden');
                return;
            }

            // Limit to 8 results
            const limitedMatches = matches.slice(0, 8);
            const searchQuery = @json(request('zoeken', ''));
            const searchParam = searchQuery ? '&zoeken=' + encodeURIComponent(searchQuery) : '';
            resultsDiv.innerHTML = limitedMatches.map(match => {
                // Handle single value filters (informatiecategorie) vs array filters
                const paramName = match.type === 'informatiecategorie' ? match.type : `${match.type}[]`;
                return `
                <a href="/themas?${paramName}=${encodeURIComponent(match.value)}${searchParam}" 
                   class="block px-4 py-3 hover:bg-[var(--color-surface)]-variant transition-colors duration-150 border-b border-[var(--color-outline-variant)] last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-[var(--color-on-surface)]">${match.label}</div>
                            <div class="text-sm text-[var(--color-on-surface-variant)]">${match.secondary}</div>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-[var(--color-on-surface-variant)]" aria-hidden="true"></i>
                    </div>
                </a>
            `;
            }).join('');

            resultsDiv.classList.remove('hidden');
        }

        // Close quick filter results when clicking outside
        document.addEventListener('click', function(event) {
            const quickFilter = document.getElementById('quick-filter');
            const resultsDiv = document.getElementById('quick-filter-results');
            if (quickFilter && resultsDiv && !quickFilter.contains(event.target) && !resultsDiv.contains(event.target)) {
                resultsDiv.classList.add('hidden');
            }
        });

        // Initialize custom date range visibility on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleCustomDateRange();
        });
    </script>
@endpush
