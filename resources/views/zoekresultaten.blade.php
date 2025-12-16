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
    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-6 lg:px-8 pt-10 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-[300px_1fr] gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:sticky lg:top-8 h-fit" aria-label="Zoekfilters">
                <div class="bg-white rounded-md p-6 shadow-sm border border-[var(--color-outline-variant)]">
                    <h2 class="text-[var(--font-size-headline-small)] font-semibold mb-6 text-[var(--color-on-surface)] pb-4 border-b border-[var(--color-outline-variant)]">
                        Verfijn zoekopdracht
                    </h2>
                    
                    <form action="{{ isset($isDossier) ? route('dossiers.index') : route('zoeken') }}" method="GET" id="filter-form" class="space-y-6">
                        <input type="hidden" name="sort" id="hidden-sort" value="{{ request('sort', 'relevance') }}">
                        <input type="hidden" name="per_page" id="hidden-per-page" value="{{ request('per_page', 20) }}">
                        @if(request('zoeken'))
                            <input type="hidden" name="zoeken" value="{{ request('zoeken') }}">
                        @endif
                        @if(request('titles_only'))
                            <input type="hidden" name="titles_only" value="1">
                        @endif
                        
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
                                    <span class="text-xs text-[var(--color-on-surface-variant)]" id="count-week">({{ $filterCounts['week'] ?? 0 }})</span>
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
                                    <span class="text-xs text-[var(--color-on-surface-variant)]" id="count-maand">({{ $filterCounts['maand'] ?? 0 }})</span>
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
                                    <span class="text-xs text-[var(--color-on-surface-variant)]" id="count-jaar">({{ $filterCounts['jaar'] ?? 0 }})</span>
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
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                        <label for="publicatiedatum_van" class="text-[var(--font-size-label-medium)] text-[var(--color-on-surface-variant)] whitespace-nowrap">
                                            Vanaf (dd-mm-jjjj):
                                        </label>
                                        <x-input 
                                            type="text"
                                            name="publicatiedatum_van"
                                            id="publicatiedatum_van"
                                            value="{{ request('publicatiedatum_van') }}"
                                            placeholder="dd-mm-jjjj"
                                            pattern="\d{2}-\d{2}-\d{4}"
                                            onchange="document.getElementById('filter-form').submit()"
                                            class="flex-1 px-3 py-2 rounded-md border-2 border-[var(--color-outline)] bg-white
                                                   text-[var(--font-size-body-medium)] text-[var(--color-on-surface)]
                                                   focus:outline-none focus:border-[var(--color-primary)]
                                                   transition-colors duration-200
                                                   min-h-[44px] max-w-[150px]"
                                        />
                                    </div>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                        <label for="publicatiedatum_tot" class="text-[var(--font-size-label-medium)] text-[var(--color-on-surface-variant)] whitespace-nowrap">
                                            Tot en met (dd-mm-jjjj):
                                        </label>
                                        <x-input 
                                            type="text"
                                            name="publicatiedatum_tot"
                                            id="publicatiedatum_tot"
                                            value="{{ request('publicatiedatum_tot') }}"
                                            placeholder="dd-mm-jjjj"
                                            pattern="\d{2}-\d{2}-\d{4}"
                                            onchange="document.getElementById('filter-form').submit()"
                                            class="flex-1 px-3 py-2 rounded-md border-2 border-[var(--color-outline)] bg-white
                                                   text-[var(--font-size-body-medium)] text-[var(--color-on-surface)]
                                                   focus:outline-none focus:border-[var(--color-primary)]
                                                   transition-colors duration-200
                                                   min-h-[44px] max-w-[150px]"
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
                                               cursor-pointer text-[var(--color-primary)]
                                               checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
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
                                               cursor-pointer text-[var(--color-primary)]
                                               checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                               transition-all duration-200"
                                    >
                                    <label for="status-actief" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                        Actief
                                    </label>
                                    <span class="text-xs text-[var(--color-on-surface-variant)]">({{ $filterCounts['status']['actief'] ?? 0 }})</span>
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
                                               cursor-pointer text-[var(--color-primary)]
                                               checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                               transition-all duration-200"
                                    >
                                    <label for="status-gesloten" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                        Gesloten
                                    </label>
                                    <span class="text-xs text-[var(--color-on-surface-variant)]">({{ $filterCounts['status']['gesloten'] ?? 0 }})</span>
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
                                                   cursor-pointer text-[var(--color-primary)]
                                                   checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                                   transition-all duration-200"
                                        >
                                        <label for="categorie-{{ md5($category) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $category }}
                                        </label>
                                        <span class="text-xs text-[var(--color-on-surface-variant)]">({{ $filterCounts['informatiecategorie'][$category] ?? 0 }})</span>
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
                                            <span class="text-xs text-[var(--color-on-surface-variant)]">({{ $filterCounts['informatiecategorie'][$category] ?? 0 }})</span>
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
                                                   cursor-pointer text-[var(--color-primary)]
                                                   checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                                   transition-all duration-200"
                                        >
                                        <label for="soort-{{ str_replace(' ', '-', strtolower($type)) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $type }}
                                        </label>
                                        <span class="text-xs text-[var(--color-on-surface-variant)]">({{ $filterCounts['documentsoort'][$type] ?? 0 }})</span>
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
                                            <span class="text-xs text-[var(--color-on-surface-variant)]">({{ $filterCounts['documentsoort'][$type] ?? 0 }})</span>
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
                                                   cursor-pointer text-[var(--color-primary)]
                                                   checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                                   transition-all duration-200"
                                        >
                                        <label for="bestandstype-{{ strtolower(str_replace([' ', '-'], ['', ''], $label)) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $label }}
                                        </label>
                                        <span class="text-xs text-[var(--color-on-surface-variant)]">({{ $filterCounts['bestandstype'][$label] ?? 0 }})</span>
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
                                                   cursor-pointer text-[var(--color-primary)]
                                                   checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                                   transition-all duration-200"
                                        >
                                        <label for="thema-{{ str_replace(' ', '-', strtolower($theme)) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $theme }}
                                        </label>
                                        <span class="text-xs text-[var(--color-on-surface-variant)]">({{ $filterCounts['thema'][$theme] ?? 0 }})</span>
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
                                            <span class="text-xs text-[var(--color-on-surface-variant)]">({{ $filterCounts['thema'][$theme] ?? 0 }})</span>
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
                                                   cursor-pointer text-[var(--color-primary)]
                                                   checked:bg-[var(--color-primary)] checked:border-[var(--color-primary)]
                                                   transition-all duration-200"
                                        >
                                        <label for="org-{{ md5($org) }}" class="text-sm text-[var(--color-on-surface)] cursor-pointer flex-1">
                                            {{ $org }}
                                        </label>
                                        <span class="text-xs text-[var(--color-on-surface-variant)]">({{ number_format($filterCounts['organisatie'][$org] ?? 0, 0, ',', '.') }})</span>
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
                                            <span class="text-xs text-[var(--color-on-surface-variant)]">({{ number_format($filterCounts['organisatie'][$org] ?? 0, 0, ',', '.') }})</span>
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
            <div id="search-results-area" class="space-y-6">
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
                    if (request('titles_only')) {
                        $activeFilters[] = ['type' => 'titles_only', 'value' => '1', 'label' => 'Alleen in titels'];
                    }
                    if (request('zoeken')) {
                        $activeFilters[] = ['type' => 'zoeken', 'value' => request('zoeken'), 'label' => 'Zoekterm: ' . request('zoeken')];
                    }
                @endphp
                
                <!-- Unified Search & Active Filters Card -->
                <div class="bg-white rounded-md shadow-sm border border-[var(--color-outline-variant)] divide-y divide-[var(--color-outline-variant)]">
                    <!-- Unified Search (Documents & Filters) -->
                    <div class="p-4">
                        <label for="unified-search" class="block text-sm font-medium text-[var(--color-on-surface)] mb-2">
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
                                class="block w-full pr-3 py-2.5 rounded-md border-2 border-[var(--color-outline)] bg-white
                                       text-sm text-[var(--color-on-surface)] placeholder-[var(--color-on-surface-variant)]
                                       focus:outline-none focus:border-[var(--color-primary)]
                                       transition-colors duration-200"
                            />
                            <div id="unified-search-results" class="absolute z-50 mt-1 w-full bg-white rounded-md shadow-sm border border-[var(--color-outline-variant)] hidden max-h-96 overflow-auto">
                                <!-- Results populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Active Filters -->
                    @if(!empty($activeFilters))
                    <div class="p-6">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-[var(--color-on-surface)] mr-2">Actieve filters:</span>
                            @foreach($activeFilters as $filter)
                                @php
                                    $removeUrl = request()->fullUrlWithQuery(['pagina' => 1]);
                                    if ($filter['type'] === 'beschikbaarSinds') {
                                        $removeUrl = request()->fullUrlWithQuery(['beschikbaarSinds' => null, 'pagina' => 1]);
                                    } else                                    if ($filter['type'] === 'date') {
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
                            <a href="{{ isset($isDossier) ? route('dossiers.index') : route('zoeken') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-md 
                                      bg-[var(--color-surface-variant)] text-[var(--color-on-surface-variant)] border border-[var(--color-outline-variant)]
                                      hover:bg-[var(--color-surface-variant)]/80
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
                <div class="bg-white rounded-md p-6 shadow-sm border border-[var(--color-outline-variant)]">
                    <div class="flex items-center justify-between gap-3 flex-nowrap overflow-x-auto">
                        <h2 class="text-lg font-medium text-[var(--color-on-surface)] truncate flex-shrink min-w-0">
                            {{ isset($isDossier) ? 'Dossiers' : 'Zoekresultaten' }} {{ (($results['page'] ?? 1) - 1) * ($results['perPage'] ?? 20) + 1 }}-{{ min(($results['page'] ?? 1) * ($results['perPage'] ?? 20), $results['total'] ?? 0) }} van de {{ number_format($results['total'] ?? 0, 0, ',', '.') }} {{ isset($isDossier) ? 'dossiers' : 'resultaten' }}
                        </h2>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-sm text-[var(--color-on-surface-variant)] whitespace-nowrap">Sorteer:</span>
                            <select 
                                name="sort" 
                                class="px-3 py-1.5 rounded-md border-2 border-[var(--color-outline)] bg-white
                                       text-sm text-[var(--color-on-surface)]
                                       focus:outline-none focus:border-[var(--color-primary)]
                                       transition-colors duration-200
                                       min-h-[40px] cursor-pointer"
                                onchange="updateSort(this.value)"
                            >
                                <option value="relevance" {{ request('sort', 'relevance') === 'relevance' ? 'selected' : '' }}>Relevantie</option>
                                <option value="publication_date" {{ request('sort') === 'publication_date' ? 'selected' : '' }}>Publicatiedatum</option>
                                <option value="modified_date" {{ request('sort') === 'modified_date' ? 'selected' : '' }}>Laatst gewijzigd</option>
                            </select>
                            <span class="text-sm text-[var(--color-on-surface-variant)] whitespace-nowrap ml-2">Aantal:</span>
                            <div class="flex gap-1">
                                <a href="{{ request()->fullUrlWithQuery(['per_page' => 10, 'pagina' => 1]) }}" 
                                   class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-200 min-h-[40px] min-w-[40px] flex items-center justify-center
                                          {{ request('per_page', 20) == 10 ? 'bg-[var(--color-primary)] text-[var(--color-on-primary)] shadow-sm' : 'text-[var(--color-primary)] hover:bg-[var(--color-primary-container)] border border-[var(--color-outline-variant)]' }}
                                          focus:outline-none">
                                    10
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['per_page' => 20, 'pagina' => 1]) }}" 
                                   class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-200 min-h-[40px] min-w-[40px] flex items-center justify-center
                                          {{ request('per_page', 20) == 20 ? 'bg-[var(--color-primary)] text-[var(--color-on-primary)] shadow-sm' : 'text-[var(--color-primary)] hover:bg-[var(--color-primary-container)] border border-[var(--color-outline-variant)]' }}
                                          focus:outline-none">
                                    20
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['per_page' => 50, 'pagina' => 1]) }}" 
                                   class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-200 min-h-[40px] min-w-[40px] flex items-center justify-center
                                          {{ request('per_page', 20) == 50 ? 'bg-[var(--color-primary)] text-[var(--color-on-primary)] shadow-sm' : 'text-[var(--color-primary)] hover:bg-[var(--color-primary-container)] border border-[var(--color-outline-variant)]' }}
                                          focus:outline-none">
                                    50
                                </a>
                            </div>
                        </div>
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
                @if(empty($results['items']))
                    <div class="bg-white rounded-md p-12 text-center border border-[var(--color-outline-variant)]">
                        <p class="text-[var(--font-size-body-large)] text-[var(--color-on-surface-variant)] mb-2">Geen resultaten gevonden.</p>
                        <p class="text-[var(--font-size-body-medium)] text-[var(--color-on-surface-variant)]">Probeer andere zoekwoorden of filters aan te passen.</p>
                    </div>
                @else
                    <!-- Simple List with Heading - Tailwind UI Style -->
                    <div class="bg-white rounded-md shadow-sm border border-[var(--color-outline-variant)] overflow-hidden">
                        <div class="px-6 py-4 border-b border-[var(--color-outline-variant)] bg-[var(--color-surface-variant)]/30">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface)]">
                                {{ isset($isDossier) ? 'Dossiers' : 'Documenten' }}
                            </h3>
                        </div>
                        <ul role="list" class="divide-y divide-[var(--color-outline-variant)]">
                            @foreach($results['items'] as $item)
                                <li class="px-6 py-5 hover:bg-[var(--color-surface-variant)]/30 transition-colors duration-150">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start gap-3 mb-2">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start justify-between gap-2 mb-1">
                                                        <a href="{{ isset($isDossier) ? route('dossiers.show', $item->external_id) : '/open-overheid/documents/' . $item->external_id }}" 
                                                           class="text-sm font-medium text-[var(--color-on-surface)] block
                                                                  hover:text-[var(--color-primary)] focus:outline-none
                                                                  transition-colors duration-200 rounded-md flex-1">
                                                            @if(isset($isDossier) && isset($item->ai_enhanced_title) && !empty($item->ai_enhanced_title))
                                                                <span class="inline-flex items-center gap-1.5">
                                                                    <span>{!! $searchQuery ? highlightSearchTerms($item->ai_enhanced_title, $searchQuery) : $item->ai_enhanced_title !!}</span>
                                                                    <i class="fas fa-sparkles text-[var(--color-primary)] text-xs" aria-label="AI-geoptimaliseerd" title="AI-geoptimaliseerde titel"></i>
                                                                </span>
                                                            @else
                                                                {!! $searchQuery ? highlightSearchTerms($item->title ?? 'Geen titel', $searchQuery) : ($item->title ?? 'Geen titel') !!}
                                                            @endif
                                                        </a>
                                                        @if($item->category)
                                                            <a href="{{ isset($isDossier) ? route('dossiers.index') : route('zoeken') }}?informatiecategorie={{ urlencode($item->category) }}{{ request('zoeken') ? '&zoeken=' . urlencode(request('zoeken')) : '' }}" 
                                                               class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium 
                                                                      bg-[var(--color-primary)]/10 text-[var(--color-primary)] border border-[var(--color-primary)]/20
                                                                      hover:bg-[var(--color-primary)]/20 hover:border-[var(--color-primary)]/30
                                                                      focus:outline-none
                                                                      transition-all duration-200 shrink-0"
                                                               title="Filter op {{ $item->formatted_category ?? $item->category }}">
                                                                {{ $item->formatted_category ?? $item->category }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($item->external_id)
                                                    <a href="https://open.overheid.nl/details/{{ $item->external_id }}" 
                                                       target="_blank"
                                                       rel="noopener noreferrer"
                                                       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md
                                                              bg-[var(--color-primary)]/5 text-[var(--color-primary)] border border-[var(--color-primary)]/20
                                                              hover:bg-[var(--color-primary)]/10 hover:border-[var(--color-primary)]/30
                                                              focus:outline-none
                                                              transition-all duration-200 text-xs font-medium
                                                              group shrink-0"
                                                       title="Bekijk op open.overheid.nl">
                                                        <i class="fas fa-external-link-alt text-xs" aria-hidden="true"></i>
                                                        <span class="hidden sm:inline">Open.overheid.nl</span>
                                                    </a>
                                                @endif
                                            </div>
                                            @if(isset($isDossier))
                                                @if(isset($item->ai_summary) && !empty($item->ai_summary))
                                                    <p class="text-xs text-[var(--color-on-surface-variant)] mb-3">
                                                        <span class="inline-flex items-center gap-1 mb-1">
                                                            <i class="fas fa-sparkles text-[var(--color-primary)] text-xs" aria-hidden="true"></i>
                                                            <span class="text-[10px] font-medium text-[var(--color-primary)] uppercase">AI-samenvatting</span>
                                                        </span>
                                                        <span class="block mt-1">
                                                            {!! $searchQuery ? highlightSearchTerms(\Illuminate\Support\Str::limit($item->ai_summary, 250), $searchQuery) : \Illuminate\Support\Str::limit($item->ai_summary, 250) !!}
                                                        </span>
                                                    </p>
                                                @elseif($item->description)
                                                    <p class="text-xs text-[var(--color-on-surface-variant)] mb-3 line-clamp-2">
                                                        {!! $searchQuery ? highlightSearchTerms(\Illuminate\Support\Str::limit($item->description, 150), $searchQuery) : \Illuminate\Support\Str::limit($item->description, 150) !!}
                                                    </p>
                                                @endif
                                                
                                                {{-- Lijst van documenten in dossier --}}
                                                @if(isset($item->dossier_documents) && !empty($item->dossier_documents) && count($item->dossier_documents) > 1)
                                                    <div class="mt-3 pt-3 border-t border-[var(--color-outline-variant)]">
                                                        <p class="text-xs font-medium text-[var(--color-on-surface)] mb-2">
                                                            <i class="fas fa-file-alt text-[var(--color-primary)] text-xs mr-1" aria-hidden="true"></i>
                                                            Documenten in dit dossier ({{ count($item->dossier_documents) }}):
                                                        </p>
                                                        <ul class="space-y-1.5">
                                                            @foreach(array_slice($item->dossier_documents, 0, 5) as $doc)
                                                                <li class="text-xs text-[var(--color-on-surface-variant)] flex items-start gap-2">
                                                                    <i class="fas fa-file-pdf text-[var(--color-primary)] text-[10px] mt-1 flex-shrink-0" aria-hidden="true"></i>
                                                                    <span class="flex-1">
                                                                        <a href="{{ route('detail', ['id' => $doc['id']]) }}" 
                                                                           class="hover:text-[var(--color-primary)] hover:underline">
                                                                            {{ $doc['title'] ?? 'Geen titel' }}
                                                                        </a>
                                                                        @if($doc['publication_date'])
                                                                            <span class="text-[10px] text-[var(--color-on-surface-variant)] ml-2">
                                                                                ({{ $doc['publication_date'] }})
                                                                            </span>
                                                                        @endif
                                                                    </span>
                                                                </li>
                                                            @endforeach
                                                            @if(count($item->dossier_documents) > 5)
                                                                <li class="text-xs text-[var(--color-on-surface-variant)] italic">
                                                                    ... en {{ count($item->dossier_documents) - 5 }} meer document{{ count($item->dossier_documents) - 5 !== 1 ? 'en' : '' }}
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @endif
                                            @elseif($item->description)
                                                <p class="text-xs text-[var(--color-on-surface-variant)] mb-3 line-clamp-2">
                                                    {!! $searchQuery ? highlightSearchTerms(\Illuminate\Support\Str::limit($item->description, 150), $searchQuery) : \Illuminate\Support\Str::limit($item->description, 150) !!}
                                                </p>
                                            @endif
                                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-[var(--color-on-surface-variant)]">
                                                <span class="inline-flex items-center gap-1.5">
                                                    <i class="fas fa-file-pdf text-xs text-red-600" aria-hidden="true"></i>
                                                    <span class="font-medium text-[var(--color-on-surface)]">PDF</span>
                                                </span>
                                                @if($item->publication_date)
                                                    <span class="inline-flex items-center gap-1.5">
                                                        <i class="fas fa-calendar-alt text-xs opacity-70" aria-hidden="true"></i>
                                                        <span>Gepubliceerd op {{ $item->publication_date->format('d-m-Y') }}</span>
                                                    </span>
                                                @endif
                                                @if($item->updated_at)
                                                    <span class="inline-flex items-center gap-1.5">
                                                        <i class="fas fa-edit text-xs opacity-70" aria-hidden="true"></i>
                                                        <span>Gewijzigd {{ $item->updated_at->format('d-m-Y') }}</span>
                                                    </span>
                                                @endif
                                                @if($item->organisation)
                                                    <a href="{{ isset($isDossier) ? route('dossiers.index') : route('zoeken') }}?organisatie[]={{ urlencode($item->organisation) }}" 
                                                       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md 
                                                              bg-[var(--color-primary)]/10 text-[var(--color-primary)] border border-[var(--color-primary)]/20
                                                              hover:bg-[var(--color-primary)]/20 hover:border-[var(--color-primary)]/30
                                                              focus:outline-none
                                                              transition-all duration-200 font-medium text-xs
                                                              group"
                                                       title="Filter op {{ $item->organisation }}">
                                                        <i class="fas fa-building text-xs" aria-hidden="true"></i>
                                                        <span>{{ $item->organisation }}</span>
                                                        <i class="fas fa-filter text-xs opacity-70 group-hover:opacity-100 transition-opacity" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                                @if(isset($isDossier))
                                                    @if(isset($item->dossier_status))
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md 
                                                                  {{ $item->dossier_status === 'actief' ? 'bg-green-100 text-green-700 border-green-300' : 'bg-gray-100 text-gray-700 border-gray-300' }}
                                                                  font-medium text-xs">
                                                            <i class="fas {{ $item->dossier_status === 'actief' ? 'fa-circle-check' : 'fa-circle-xmark' }} text-xs" aria-hidden="true"></i>
                                                            <span>{{ ucfirst($item->dossier_status) }}</span>
                                                        </span>
                                                    @endif
                                                    @if(isset($item->dossier_member_count) && $item->dossier_member_count > 0)
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md 
                                                                  bg-[var(--color-primary)]/10 text-[var(--color-primary)] border border-[var(--color-primary)]/20
                                                                  font-medium text-xs">
                                                            <i class="fas fa-file text-xs" aria-hidden="true"></i>
                                                            <span>{{ $item->dossier_member_count }} document{{ $item->dossier_member_count !== 1 ? 'en' : '' }}</span>
                                                        </span>
                                                    @endif
                                                    @if(isset($item->has_audio) && $item->has_audio)
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md 
                                                                  bg-purple-100 text-purple-700 border-purple-300
                                                                  font-medium text-xs"
                                                              title="Audio beschikbaar voor toegankelijkheid">
                                                            <i class="fas fa-headphones text-xs" aria-hidden="true"></i>
                                                            <span>Audio</span>
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                            @if(isset($item->metadata) && isset($item->metadata['document']['weblocatie']))
                                                <div class="mt-3">
                                                    <a href="{{ $item->metadata['document']['weblocatie'] }}" 
                                                       target="_blank" 
                                                       rel="noopener noreferrer"
                                                       class="text-[var(--color-primary)] font-medium text-sm inline-flex items-center gap-1.5
                                                              hover:underline focus:outline-none
                                                              transition-all duration-200 rounded-md">
                                                        Open via officielebekendmakingen.nl
                                                        <i class="fas fa-external-link-alt text-xs" aria-hidden="true"></i>
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
                        <div class="flex items-center justify-between border-t border-[var(--color-outline-variant)] bg-[var(--color-surface)] px-4 py-3 sm:px-6 dark:border-white/10 dark:bg-transparent">
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
                                        resultaten
                                    </p>
                                </div>
                                <div>
                                    <nav aria-label="Paginatie" class="isolate inline-flex -space-x-px rounded-md shadow-sm dark:shadow-none">
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
    
    <script>
        function updateSort(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', value);
            url.searchParams.set('pagina', '1');
            window.location.href = url.toString();
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
            if (resultsArea) {
                resultsArea.innerHTML = `
                    <div class="flex items-center justify-center py-12">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin text-3xl text-[var(--color-primary)] mb-4"></i>
                            <p class="text-[var(--color-on-surface-variant)]">Zoeken naar "${escapeHtml(query)}"...</p>
                        </div>
                    </div>
                `;
            }
            
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
                ['beschikbaarSinds', 'publicatiedatum_van', 'publicatiedatum_tot', 'sort'].forEach(filter => {
                    const val = currentUrl.searchParams.get(filter);
                    if (val) params.set(filter, val);
                });
                // Handle array params
                ['documentsoort[]', 'thema[]', 'organisatie[]', 'informatiecategorie[]'].forEach(filter => {
                    currentUrl.searchParams.getAll(filter).forEach(val => params.append(filter, val));
                });
                
                const response = await fetch(`${fastSearchEndpoint}?${params.toString()}`);
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.message || 'Search failed');
                }
                
                // Update URL without reload
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.set('zoeken', query);
                newUrl.searchParams.set('pagina', '1');
                history.pushState({}, '', newUrl.toString());
                
                // Render results
                if (resultsArea) {
                    if (data.hits && data.hits.length > 0) {
                        let html = `
                            <div class="mb-4 flex items-center justify-between">
                                <p class="text-sm text-[var(--color-on-surface-variant)]">
                                    <span class="font-semibold">${data.found.toLocaleString('nl-NL')}</span> resultaten gevonden
                                    <span class="text-xs text-gray-400 ml-2">(${data.search_time_ms}ms)</span>
                                </p>
                            </div>
                            <div class="space-y-4">
                        `;
                        
                        data.hits.forEach(hit => {
                            html += `
                                <article class="bg-white rounded-lg border border-[var(--color-outline-variant)] p-4 hover:shadow-md transition-shadow">
                                    <a href="/open-overheid/documents/${hit.id}" class="block">
                                        <h3 class="text-lg font-semibold text-[var(--color-on-surface)] mb-2 hover:text-[var(--color-primary)]">
                                            ${escapeHtml(hit.title || 'Geen titel')}
                                        </h3>
                                        ${hit.description ? `<p class="text-sm text-[var(--color-on-surface-variant)] mb-3 line-clamp-2">${escapeHtml(hit.description)}</p>` : ''}
                                        <div class="flex flex-wrap gap-2 text-xs text-[var(--color-on-surface-variant)]">
                                            ${hit.document_type ? `<span class="bg-[var(--color-surface-variant)] px-2 py-1 rounded">${escapeHtml(hit.document_type)}</span>` : ''}
                                            ${hit.organisation ? `<span class="bg-[var(--color-surface-variant)] px-2 py-1 rounded">${escapeHtml(hit.organisation)}</span>` : ''}
                                            ${hit.publication_date ? `<span class="text-gray-400">${hit.publication_date}</span>` : ''}
                                        </div>
                                    </a>
                                </article>
                            `;
                        });
                        
                        html += '</div>';
                        
                        // Pagination
                        if (data.total_pages > 1) {
                            html += `
                                <div class="mt-6 flex items-center justify-center gap-2">
                                    <span class="text-sm text-[var(--color-on-surface-variant)]">
                                        Pagina ${data.page} van ${data.total_pages}
                                    </span>
                                </div>
                            `;
                        }
                        
                        resultsArea.innerHTML = html;
                    } else {
                        resultsArea.innerHTML = `
                            <div class="text-center py-12">
                                <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                                <p class="text-[var(--color-on-surface-variant)]">Geen resultaten gevonden voor "${escapeHtml(query)}"</p>
                            </div>
                        `;
                    }
                }
                
                // Update filter counts if available
                if (data.filter_counts) {
                    updateFilterCounts(data.filter_counts);
                }
                
            } catch (error) {
                console.error('Fast search error:', error);
                if (resultsArea) {
                    resultsArea.innerHTML = `
                        <div class="text-center py-12">
                            <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                            <p class="text-red-600">Fout bij zoeken. Probeer het opnieuw.</p>
                        </div>
                    `;
                }
            }
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
        
        // Apply filter via AJAX (lightning fast)
        async function applyFilterViaAjax(filterType, filterValue) {
            // Hide dropdown
            if (unifiedSearchResults) {
                unifiedSearchResults.classList.add('hidden');
            }
            
            // Show loading state
            const resultsArea = document.getElementById('search-results-area');
            if (resultsArea) {
                resultsArea.innerHTML = `
                    <div class="flex items-center justify-center py-12">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin text-3xl text-[var(--color-primary)] mb-4"></i>
                            <p class="text-[var(--color-on-surface-variant)]">Filter toepassen...</p>
                        </div>
                    </div>
                `;
            }
            
            try {
                const fastSearchEndpoint = '{{ route("api.fast-search") }}';
                const params = new URLSearchParams({
                    page: 1,
                    per_page: 20,
                });
                
                // Get current search query
                const currentUrl = new URL(window.location.href);
                const currentQuery = currentUrl.searchParams.get('zoeken') || '';
                if (currentQuery) params.set('q', currentQuery);
                
                // Add the new filter
                const paramName = filterType === 'informatiecategorie' ? filterType : filterType;
                params.set(paramName, filterValue);
                
                const response = await fetch(`${fastSearchEndpoint}?${params.toString()}`);
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.message || 'Filter failed');
                }
                
                // Update URL
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.set('pagina', '1');
                const urlParamName = filterType === 'informatiecategorie' ? filterType : `${filterType}[]`;
                newUrl.searchParams.append(urlParamName, filterValue);
                history.pushState({}, '', newUrl.toString());
                
                // Render results
                if (resultsArea && data.hits) {
                    if (data.hits.length > 0) {
                        let html = `
                            <div class="mb-4 flex items-center justify-between">
                                <p class="text-sm text-[var(--color-on-surface-variant)]">
                                    <span class="font-semibold">${data.found.toLocaleString('nl-NL')}</span> resultaten gevonden
                                    <span class="text-xs text-gray-400 ml-2">(${data.search_time_ms}ms)</span>
                                </p>
                            </div>
                            <div class="space-y-4">
                        `;
                        
                        data.hits.forEach(hit => {
                            html += `
                                <article class="bg-white rounded-lg border border-[var(--color-outline-variant)] p-4 hover:shadow-md transition-shadow">
                                    <a href="/open-overheid/documents/${hit.id}" class="block">
                                        <h3 class="text-lg font-semibold text-[var(--color-on-surface)] mb-2 hover:text-[var(--color-primary)]">
                                            ${escapeHtml(hit.title || 'Geen titel')}
                                        </h3>
                                        ${hit.description ? `<p class="text-sm text-[var(--color-on-surface-variant)] mb-3 line-clamp-2">${escapeHtml(hit.description)}</p>` : ''}
                                        <div class="flex flex-wrap gap-2 text-xs text-[var(--color-on-surface-variant)]">
                                            ${hit.document_type ? `<span class="bg-[var(--color-surface-variant)] px-2 py-1 rounded">${escapeHtml(hit.document_type)}</span>` : ''}
                                            ${hit.organisation ? `<span class="bg-[var(--color-surface-variant)] px-2 py-1 rounded">${escapeHtml(hit.organisation)}</span>` : ''}
                                            ${hit.publication_date ? `<span class="text-gray-400">${hit.publication_date}</span>` : ''}
                                        </div>
                                    </a>
                                </article>
                            `;
                        });
                        
                        html += '</div>';
                        resultsArea.innerHTML = html;
                    } else {
                        resultsArea.innerHTML = `
                            <div class="text-center py-12">
                                <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                                <p class="text-[var(--color-on-surface-variant)]">Geen resultaten gevonden voor dit filter</p>
                            </div>
                        `;
                    }
                }
                
            } catch (error) {
                console.error('Filter apply error:', error);
                if (resultsArea) {
                    resultsArea.innerHTML = `
                        <div class="text-center py-12">
                            <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                            <p class="text-red-600">Fout bij toepassen filter. Probeer het opnieuw.</p>
                        </div>
                    `;
                }
            }
        }

        // Neuro search removed - now premium-only via chat interface

        // Initialize custom date range visibility on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleCustomDateRange();
        });
    </script>
@endsection

