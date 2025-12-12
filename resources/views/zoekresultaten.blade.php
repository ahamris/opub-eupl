<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zoekresultaten - Open Overheid</title>
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&family=Roboto+Mono&display=swap" rel="stylesheet">
    
    {{-- Font Awesome 6.5.2 --}}
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
</head>
<body class="bg-surface text-on-surface min-h-screen flex flex-col">
    <!-- Navigation Header -->
    <header class="bg-primary text-on-primary shadow-sm" role="banner">
        <nav class="max-w-7xl mx-auto px-4 py-4" role="navigation" aria-label="Hoofdnavigatie">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-headline-small font-normal hover:opacity-90 transition-opacity duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-sm">
                        Overheid.nl
                    </a>
                    <span class="text-body-medium opacity-90">Open overheid</span>
                </div>
                <ul class="flex gap-6 flex-wrap">
                    <li>
                        <a href="{{ route('home') }}" class="text-body-large hover:opacity-90 transition-opacity duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-sm px-2 py-1">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('verwijzingen') }}" class="text-body-large hover:opacity-90 transition-opacity duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-sm px-2 py-1">
                            Verwijzingen
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('over') }}" class="text-body-large hover:opacity-90 transition-opacity duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-sm px-2 py-1">
                            Over
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="bg-surface text-on-surface-variant border-b border-outline-variant">
            <div class="max-w-7xl mx-auto px-4 py-2">
                <p class="text-label-medium">
                    U bent hier: 
                    <a href="{{ route('home') }}" class="text-primary hover:underline focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm">Home</a> 
                    / Zoekresultaten
                </p>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-[300px_1fr] gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:sticky lg:top-8 h-fit" aria-label="Zoekfilters">
                <div class="bg-surface rounded-xl p-6 shadow-sm border border-outline-variant">
                    <h2 class="text-title-large font-medium mb-6 text-on-surface pb-4 border-b border-outline-variant">
                        Verfijn zoekopdracht
                    </h2>
                    
                    <form action="/zoeken" method="GET" id="filter-form" class="space-y-6">
                        <input type="hidden" name="sort" id="hidden-sort" value="{{ request('sort', 'relevance') }}">
                        <input type="hidden" name="per_page" id="hidden-per-page" value="{{ request('per_page', 20) }}">
                        
                        <!-- Search Keywords -->
                        <div class="space-y-3">
                            <label for="sidebar-zoeken" class="block text-label-large font-medium text-on-surface">
                                Zoekwoorden
                            </label>
                            <input 
                                type="text" 
                                id="sidebar-zoeken" 
                                name="zoeken" 
                                class="w-full px-4 py-3 rounded-lg 
                                       border-2 border-outline bg-surface
                                       text-body-large text-on-surface
                                       focus:border-primary focus:outline-2 focus:outline-primary focus:outline-offset-2
                                       transition-colors duration-200
                                       min-h-[44px]"
                                value="{{ request('zoeken') }}"
                                placeholder="Zoekwoorden..."
                            >
                            <div class="flex items-center gap-3">
                                <input 
                                    type="checkbox" 
                                    id="sidebar-titles-only" 
                                    name="titles_only" 
                                    value="1"
                                    {{ request('titles_only') ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border border-outline
                                               focus:ring-2 focus:ring-primary focus:ring-offset-2
                                               cursor-pointer text-primary
                                               checked:bg-primary checked:border-primary
                                               transition-all duration-200" 
                                           focus:outline-2 focus:outline-primary focus:outline-offset-2
                                           cursor-pointer min-h-[44px] min-w-[44px]"
                                >
                                <label for="sidebar-titles-only" class="text-body-medium text-on-surface cursor-pointer">
                                    Zoek alleen in titels
                                </label>
                            </div>
                            <button 
                                type="submit" 
                                class="w-full bg-primary text-on-primary 
                                       hover:bg-primary/90 active:bg-primary/80
                                       focus:outline-2 focus:outline-primary focus:outline-offset-2
                                       px-4 py-3 rounded-full font-medium
                                       transition-colors duration-200
                                       min-h-[44px]">
                                Zoeken
                            </button>
                            <button 
                                type="button" 
                                onclick="window.location.href='/zoeken'"
                                class="w-full border-2 border-outline text-primary
                                       hover:bg-primary-container
                                       focus:outline-2 focus:outline-primary focus:outline-offset-2
                                       px-4 py-3 rounded-full font-medium
                                       transition-colors duration-200
                                       min-h-[44px]">
                                Selectie wissen
                            </button>
                        </div>
                        
                        <!-- Date Filter -->
                        <div class="space-y-3">
                            <h3 class="text-title-medium font-medium text-on-surface">Datum beschikbaar</h3>
                            <div class="space-y-2">
                                <div class="flex items-center gap-3">
                                    <input 
                                        type="radio" 
                                        id="datum-geen" 
                                        name="beschikbaarSinds" 
                                        value=""
                                        {{ !request('beschikbaarSinds') ? 'checked' : '' }}
                                        onchange="document.getElementById('filter-form').submit()"
                                        class="w-4 h-4 border border-outline 
                                               focus:ring-2 focus:ring-primary focus:ring-offset-2
                                               cursor-pointer text-primary
                                               checked:bg-primary checked:border-primary
                                               transition-all duration-200"
                                    >
                                    <label for="datum-geen" class="text-body-medium text-on-surface cursor-pointer flex-1">
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
                                        class="w-4 h-4 border border-outline 
                                               focus:ring-2 focus:ring-primary focus:ring-offset-2
                                               cursor-pointer text-primary
                                               checked:bg-primary checked:border-primary
                                               transition-all duration-200"
                                    >
                                    <label for="datum-week" class="text-body-medium text-on-surface cursor-pointer flex-1">
                                        Afgelopen week
                                    </label>
                                    <span class="text-label-medium text-on-surface-variant" id="count-week">({{ $filterCounts['week'] ?? 0 }})</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input 
                                        type="radio" 
                                        id="datum-maand" 
                                        name="beschikbaarSinds" 
                                        value="maand"
                                        {{ request('beschikbaarSinds') === 'maand' ? 'checked' : '' }}
                                        onchange="document.getElementById('filter-form').submit()"
                                        class="w-4 h-4 border border-outline 
                                               focus:ring-2 focus:ring-primary focus:ring-offset-2
                                               cursor-pointer text-primary
                                               checked:bg-primary checked:border-primary
                                               transition-all duration-200"
                                    >
                                    <label for="datum-maand" class="text-body-medium text-on-surface cursor-pointer flex-1">
                                        Afgelopen maand
                                    </label>
                                    <span class="text-label-medium text-on-surface-variant" id="count-maand">({{ $filterCounts['maand'] ?? 0 }})</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input 
                                        type="radio" 
                                        id="datum-jaar" 
                                        name="beschikbaarSinds" 
                                        value="jaar"
                                        {{ request('beschikbaarSinds') === 'jaar' ? 'checked' : '' }}
                                        onchange="document.getElementById('filter-form').submit()"
                                        class="w-4 h-4 border border-outline 
                                               focus:ring-2 focus:ring-primary focus:ring-offset-2
                                               cursor-pointer text-primary
                                               checked:bg-primary checked:border-primary
                                               transition-all duration-200"
                                    >
                                    <label for="datum-jaar" class="text-body-medium text-on-surface cursor-pointer flex-1">
                                        Afgelopen jaar
                                    </label>
                                    <span class="text-label-medium text-on-surface-variant" id="count-jaar">({{ $filterCounts['jaar'] ?? 0 }})</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input 
                                        type="radio" 
                                        id="datum-zelf" 
                                        name="beschikbaarSinds" 
                                        value="zelf"
                                        {{ request('beschikbaarSinds') === 'zelf' || request('publicatiedatum_van') || request('publicatiedatum_tot') ? 'checked' : '' }}
                                        onchange="toggleCustomDateRange()"
                                        class="w-4 h-4 border border-outline 
                                               focus:ring-2 focus:ring-primary focus:ring-offset-2
                                               cursor-pointer text-primary
                                               checked:bg-primary checked:border-primary
                                               transition-all duration-200"
                                    >
                                    <label for="datum-zelf" class="text-body-medium text-on-surface cursor-pointer flex-1">
                                        Aangepaste periode
                                    </label>
                                </div>
                                <!-- Custom Date Range Inputs -->
                                <div id="custom-date-range" class="space-y-3 mt-3 pl-7 {{ request('beschikbaarSinds') === 'zelf' || (request('publicatiedatum_van') && !request('beschikbaarSinds')) || (request('publicatiedatum_tot') && !request('beschikbaarSinds')) ? '' : 'hidden' }}">
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                        <label for="publicatiedatum_van" class="text-label-medium text-on-surface-variant whitespace-nowrap">
                                            Vanaf (dd-mm-jjjj):
                                        </label>
                                        <input 
                                            type="text" 
                                            id="publicatiedatum_van" 
                                            name="publicatiedatum_van" 
                                            value="{{ request('publicatiedatum_van') }}"
                                            placeholder="dd-mm-jjjj"
                                            pattern="\d{2}-\d{2}-\d{4}"
                                            onchange="document.getElementById('filter-form').submit()"
                                            class="flex-1 px-3 py-2 rounded-lg border-2 border-outline bg-surface
                                                   text-body-medium text-on-surface
                                                   focus:border-primary focus:outline-2 focus:outline-primary focus:outline-offset-2
                                                   transition-colors duration-200
                                                   min-h-[44px] max-w-[150px]"
                                        >
                                    </div>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                        <label for="publicatiedatum_tot" class="text-label-medium text-on-surface-variant whitespace-nowrap">
                                            Tot en met (dd-mm-jjjj):
                                        </label>
                                        <input 
                                            type="text" 
                                            id="publicatiedatum_tot" 
                                            name="publicatiedatum_tot" 
                                            value="{{ request('publicatiedatum_tot') }}"
                                            placeholder="dd-mm-jjjj"
                                            pattern="\d{2}-\d{2}-\d{4}"
                                            onchange="document.getElementById('filter-form').submit()"
                                            class="flex-1 px-3 py-2 rounded-lg border-2 border-outline bg-surface
                                                   text-body-medium text-on-surface
                                                   focus:border-primary focus:outline-2 focus:outline-primary focus:outline-offset-2
                                                   transition-colors duration-200
                                                   min-h-[44px] max-w-[150px]"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Information Category Filter (Woo Informatiecategorie) -->
                        <div class="space-y-3">
                            <h3 class="text-title-medium font-medium text-on-surface">Informatiecategorie</h3>
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
                                            class="w-4 h-4 border border-outline 
                                                   focus:ring-2 focus:ring-primary focus:ring-offset-2
                                                   cursor-pointer text-primary
                                                   checked:bg-primary checked:border-primary
                                                   transition-all duration-200"
                                        >
                                        <label for="categorie-{{ md5($category) }}" class="text-body-medium text-on-surface cursor-pointer flex-1">
                                            {{ $category }}
                                        </label>
                                        <span class="text-label-medium text-on-surface-variant">({{ $filterCounts['informatiecategorie'][$category] ?? 0 }})</span>
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
                                                class="w-4 h-4 border border-outline 
                                                       focus:ring-2 focus:ring-primary focus:ring-offset-2
                                                       cursor-pointer text-primary
                                                       checked:bg-primary checked:border-primary
                                                       transition-all duration-200"
                                            >
                                            <label for="categorie-{{ md5($category) }}" class="text-body-medium text-on-surface cursor-pointer flex-1">
                                                {{ $category }}
                                            </label>
                                            <span class="text-label-medium text-on-surface-variant">({{ $filterCounts['informatiecategorie'][$category] ?? 0 }})</span>
                                        </div>
                                    @endforeach
                                </div>
                                <button 
                                    type="button" 
                                    onclick="toggleFilterSection('informatiecategorie-more', 'informatiecategorie-toggle')"
                                    id="informatiecategorie-toggle"
                                    class="text-primary font-medium text-body-medium hover:underline 
                                           focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm">
                                    Toon meer
                                </button>
                                @endif
                                @if($selectedCategory)
                                <div class="pt-2">
                                    <button 
                                        type="button" 
                                        onclick="document.getElementById('categorie-none').checked = true; document.getElementById('filter-form').submit();"
                                        class="text-primary font-medium text-body-small hover:underline 
                                               focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm">
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
                            <h3 class="text-title-medium font-medium text-on-surface">Documentsoort</h3>
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
                                            class="w-4 h-4 rounded border border-outline 
                                                   focus:ring-2 focus:ring-primary focus:ring-offset-2
                                                   cursor-pointer text-primary
                                                   checked:bg-primary checked:border-primary
                                                   transition-all duration-200"
                                        >
                                        <label for="soort-{{ str_replace(' ', '-', strtolower($type)) }}" class="text-body-medium text-on-surface cursor-pointer flex-1">
                                            {{ $type }}
                                        </label>
                                        <span class="text-label-medium text-on-surface-variant">({{ $filterCounts['documentsoort'][$type] ?? 0 }})</span>
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
                                                class="w-4 h-4 rounded border border-outline 
                                                       focus:ring-2 focus:ring-primary focus:ring-offset-2
                                                       cursor-pointer text-primary
                                                       checked:bg-primary checked:border-primary
                                                       transition-all duration-200"
                                            >
                                            <label for="soort-{{ str_replace(' ', '-', strtolower($type)) }}" class="text-body-medium text-on-surface cursor-pointer flex-1">
                                                {{ $type }}
                                            </label>
                                            <span class="text-label-medium text-on-surface-variant">({{ $filterCounts['documentsoort'][$type] ?? 0 }})</span>
                                        </div>
                                    @endforeach
                                </div>
                                <button 
                                    type="button" 
                                    onclick="toggleFilterSection('documentsoort-more', 'documentsoort-toggle')"
                                    id="documentsoort-toggle"
                                    class="text-primary font-medium text-body-medium hover:underline 
                                           focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm">
                                    Toon meer
                                </button>
                                @endif
                            </div>
                        </div>
                        
                        <!-- File Type Filter -->
                        <div class="space-y-3">
                            <h3 class="text-title-medium font-medium text-on-surface">Type bronbestand</h3>
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
                                            class="w-4 h-4 rounded border border-outline 
                                                   focus:ring-2 focus:ring-primary focus:ring-offset-2
                                                   cursor-pointer text-primary
                                                   checked:bg-primary checked:border-primary
                                                   transition-all duration-200"
                                        >
                                        <label for="bestandstype-{{ strtolower(str_replace([' ', '-'], ['', ''], $label)) }}" class="text-body-medium text-on-surface cursor-pointer flex-1">
                                            {{ $label }}
                                        </label>
                                        <span class="text-label-medium text-on-surface-variant">({{ $filterCounts['bestandstype'][$label] ?? 0 }})</span>
                                    </div>
                                @endforeach
                                <button 
                                    type="button" 
                                    class="text-primary font-medium text-body-medium hover:underline 
                                           focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm">
                                    Toon meer
                                </button>
                            </div>
                        </div>
                        
                        <!-- Theme Filter -->
                        <div class="space-y-3">
                            <h3 class="text-title-medium font-medium text-on-surface">Thema</h3>
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
                                            class="w-4 h-4 rounded border border-outline 
                                                   focus:ring-2 focus:ring-primary focus:ring-offset-2
                                                   cursor-pointer text-primary
                                                   checked:bg-primary checked:border-primary
                                                   transition-all duration-200"
                                        >
                                        <label for="thema-{{ str_replace(' ', '-', strtolower($theme)) }}" class="text-body-medium text-on-surface cursor-pointer flex-1">
                                            {{ $theme }}
                                        </label>
                                        <span class="text-label-medium text-on-surface-variant">({{ $filterCounts['thema'][$theme] ?? 0 }})</span>
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
                                                class="w-4 h-4 rounded border border-outline 
                                                       focus:ring-2 focus:ring-primary focus:ring-offset-2
                                                       cursor-pointer text-primary
                                                       checked:bg-primary checked:border-primary
                                                       transition-all duration-200"
                                            >
                                            <label for="thema-{{ str_replace(' ', '-', strtolower($theme)) }}" class="text-body-medium text-on-surface cursor-pointer flex-1">
                                                {{ $theme }}
                                            </label>
                                            <span class="text-label-medium text-on-surface-variant">({{ $filterCounts['thema'][$theme] ?? 0 }})</span>
                                        </div>
                                    @endforeach
                                </div>
                                <button 
                                    type="button" 
                                    onclick="toggleFilterSection('thema-more', 'thema-toggle')"
                                    id="thema-toggle"
                                    class="text-primary font-medium text-body-medium hover:underline 
                                           focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm">
                                    Toon meer
                                </button>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Organisation Filter -->
                        <div class="space-y-3">
                            <h3 class="text-title-medium font-medium text-on-surface">Organisatie</h3>
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
                                            class="w-4 h-4 rounded border border-outline 
                                                   focus:ring-2 focus:ring-primary focus:ring-offset-2
                                                   cursor-pointer text-primary
                                                   checked:bg-primary checked:border-primary
                                                   transition-all duration-200"
                                        >
                                        <label for="org-{{ md5($org) }}" class="text-body-medium text-on-surface cursor-pointer flex-1">
                                            {{ $org }}
                                        </label>
                                        <span class="text-label-medium text-on-surface-variant">({{ number_format($filterCounts['organisatie'][$org] ?? 0, 0, ',', '.') }})</span>
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
                                                class="w-4 h-4 rounded border border-outline 
                                                       focus:ring-2 focus:ring-primary focus:ring-offset-2
                                                       cursor-pointer text-primary
                                                       checked:bg-primary checked:border-primary
                                                       transition-all duration-200"
                                            >
                                            <label for="org-{{ md5($org) }}" class="text-body-medium text-on-surface cursor-pointer flex-1">
                                                {{ $org }}
                                            </label>
                                            <span class="text-label-medium text-on-surface-variant">({{ number_format($filterCounts['organisatie'][$org] ?? 0, 0, ',', '.') }})</span>
                                        </div>
                                    @endforeach
                                </div>
                                <button 
                                    type="button" 
                                    onclick="toggleFilterSection('organisatie-more', 'organisatie-toggle')"
                                    id="organisatie-toggle"
                                    class="text-primary font-medium text-body-medium hover:underline 
                                           focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm">
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
                <div class="bg-surface rounded-xl shadow-sm border border-outline-variant divide-y divide-outline-variant">
                    <!-- Quick Filter Combobox -->
                    <div class="p-6">
                        <label for="quick-filter" class="block text-label-large font-medium text-on-surface mb-3">
                            Snel filteren
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-search text-on-surface-variant" aria-hidden="true"></i>
                            </div>
                            <input 
                                type="text" 
                                id="quick-filter"
                                name="quick-filter"
                                placeholder="Type om te filteren op organisatie, thema, documentsoort of informatiecategorie..."
                                class="block w-full pl-10 pr-3 py-3 rounded-lg border-2 border-outline bg-surface
                                       text-body-medium text-on-surface placeholder-on-surface-variant
                                       focus:border-primary focus:outline-2 focus:outline-primary focus:outline-offset-2
                                       transition-colors duration-200"
                                autocomplete="off"
                                onkeyup="filterQuickOptions(this.value)"
                            >
                            <div id="quick-filter-results" class="absolute z-10 mt-1 w-full bg-surface rounded-lg shadow-lg border border-outline-variant hidden max-h-60 overflow-auto">
                                <!-- Results populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Active Filters -->
                    @if(!empty($activeFilters))
                    <div class="p-6">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-label-large font-medium text-on-surface mr-2">Actieve filters:</span>
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
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-full 
                                          bg-primary/10 text-primary border border-primary/20
                                          hover:bg-primary/20 hover:border-primary/30
                                          focus:outline-2 focus:outline-primary focus:outline-offset-2
                                          transition-all duration-200 font-medium text-sm
                                          group"
                                   title="Verwijder filter: {{ $filter['label'] }}">
                                    <span>{{ $filter['label'] }}</span>
                                    <i class="fas fa-times text-xs opacity-70 group-hover:opacity-100 transition-opacity" aria-hidden="true"></i>
                                </a>
                            @endforeach
                            <a href="/zoeken{{ request('zoeken') ? '?zoeken=' . urlencode(request('zoeken')) : '' }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-full 
                                      bg-surface-variant text-on-surface-variant border border-outline-variant
                                      hover:bg-surface-variant/80
                                      focus:outline-2 focus:outline-primary focus:outline-offset-2
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
                <div class="bg-surface rounded-xl p-6 shadow-sm border border-outline-variant">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h2 class="text-title-large font-medium text-on-surface">
                            Zoekresultaten {{ (($results['page'] ?? 1) - 1) * ($results['perPage'] ?? 20) + 1 }}-{{ min(($results['page'] ?? 1) * ($results['perPage'] ?? 20), $results['total'] ?? 0) }} van de {{ number_format($results['total'] ?? 0, 0, ',', '.') }} resultaten
                        </h2>
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex items-center gap-2">
                                <span class="text-label-large text-on-surface-variant">Sorteer op:</span>
                                <select 
                                    name="sort" 
                                    class="px-4 py-2 rounded-lg border-2 border-outline bg-surface
                                           text-body-medium text-on-surface
                                           focus:border-primary focus:outline-2 focus:outline-primary focus:outline-offset-2
                                           transition-colors duration-200
                                           min-h-[44px] cursor-pointer"
                                    onchange="updateSort(this.value)"
                                >
                                    <option value="relevance" {{ request('sort', 'relevance') === 'relevance' ? 'selected' : '' }}>Relevantie</option>
                                    <option value="publication_date" {{ request('sort') === 'publication_date' ? 'selected' : '' }}>Publicatiedatum</option>
                                    <option value="modified_date" {{ request('sort') === 'modified_date' ? 'selected' : '' }}>Laatst gewijzigd</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-label-large text-on-surface-variant">Aantal:</span>
                                <div class="flex gap-1">
                                    <a href="{{ request()->fullUrlWithQuery(['per_page' => 10, 'pagina' => 1]) }}" 
                                       class="px-4 py-2.5 rounded-lg text-body-medium font-medium transition-colors duration-200 min-h-[48px] min-w-[48px] flex items-center justify-center
                                              {{ request('per_page', 20) == 10 ? 'bg-primary text-on-primary shadow-sm' : 'text-primary hover:bg-primary-container border border-outline-variant' }}
                                              focus:outline-2 focus:outline-primary focus:outline-offset-2">
                                        10
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['per_page' => 20, 'pagina' => 1]) }}" 
                                       class="px-4 py-2.5 rounded-lg text-body-medium font-medium transition-colors duration-200 min-h-[48px] min-w-[48px] flex items-center justify-center
                                              {{ request('per_page', 20) == 20 ? 'bg-primary text-on-primary shadow-sm' : 'text-primary hover:bg-primary-container border border-outline-variant' }}
                                              focus:outline-2 focus:outline-primary focus:outline-offset-2">
                                        20
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['per_page' => 50, 'pagina' => 1]) }}" 
                                       class="px-4 py-2.5 rounded-lg text-body-medium font-medium transition-colors duration-200 min-h-[48px] min-w-[48px] flex items-center justify-center
                                              {{ request('per_page', 20) == 50 ? 'bg-primary text-on-primary shadow-sm' : 'text-primary hover:bg-primary-container border border-outline-variant' }}
                                              focus:outline-2 focus:outline-primary focus:outline-offset-2">
                                        50
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Error Message -->
                @if(isset($error))
                    <div class="bg-error-container text-on-error-container p-4 rounded-xl border border-error" role="alert">
                        <p class="text-body-medium text-error font-medium">
                            Er is een fout opgetreden: {{ $error }}
                        </p>
                    </div>
                @endif
                
                <!-- Results List -->
                @if(empty($results['items']))
                    <div class="bg-surface rounded-xl p-12 text-center border border-outline-variant">
                        <p class="text-body-large text-on-surface-variant mb-2">Geen resultaten gevonden.</p>
                        <p class="text-body-medium text-on-surface-variant">Probeer andere zoekwoorden of filters aan te passen.</p>
                    </div>
                @else
                    <!-- Simple List with Heading - Tailwind UI Style -->
                    <div class="bg-surface rounded-xl shadow-sm border border-outline-variant overflow-hidden">
                        <div class="px-6 py-4 border-b border-outline-variant bg-surface-variant/30">
                            <h3 class="text-title-medium font-medium text-on-surface">
                                Documenten
                            </h3>
                        </div>
                        <ul role="list" class="divide-y divide-outline-variant">
                            @foreach($results['items'] as $item)
                                <li class="px-6 py-5 hover:bg-surface-variant/30 transition-colors duration-150">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start gap-3 mb-2">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start justify-between gap-2 mb-1">
                                                        <a href="/open-overheid/documents/{{ $item->external_id }}" 
                                                           class="text-title-medium font-medium text-on-surface block
                                                                  hover:text-primary focus:outline-2 focus:outline-primary focus:outline-offset-2
                                                                  transition-colors duration-200 rounded-sm flex-1">
                                                            {{ $item->title ?? 'Geen titel' }}
                                                        </a>
                                                        @if($item->category)
                                                            <a href="/zoeken?informatiecategorie={{ urlencode($item->category) }}{{ request('zoeken') ? '&zoeken=' . urlencode(request('zoeken')) : '' }}" 
                                                               class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                                                                      bg-primary/10 text-primary border border-primary/20
                                                                      hover:bg-primary/20 hover:border-primary/30
                                                                      focus:outline-2 focus:outline-primary focus:outline-offset-2
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
                                                              bg-primary/5 text-primary border border-primary/20
                                                              hover:bg-primary/10 hover:border-primary/30
                                                              focus:outline-2 focus:outline-primary focus:outline-offset-2
                                                              transition-all duration-200 text-xs font-medium
                                                              group shrink-0"
                                                       title="Bekijk op open.overheid.nl">
                                                        <i class="fas fa-external-link-alt text-xs" aria-hidden="true"></i>
                                                        <span class="hidden sm:inline">Open.overheid.nl</span>
                                                    </a>
                                                @endif
                                            </div>
                                            @if($item->description)
                                                <p class="text-body-medium text-on-surface-variant mb-3 line-clamp-2">
                                                    {{ \Illuminate\Support\Str::limit($item->description, 150) }}
                                                </p>
                                            @endif
                                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-label-medium text-on-surface-variant">
                                                <span class="inline-flex items-center gap-1.5">
                                                    <i class="fas fa-file-pdf text-xs text-red-600" aria-hidden="true"></i>
                                                    <span class="font-medium text-on-surface">PDF</span>
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
                                                    <a href="/zoeken?organisatie[]={{ urlencode($item->organisation) }}" 
                                                       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md 
                                                              bg-primary/10 text-primary border border-primary/20
                                                              hover:bg-primary/20 hover:border-primary/30
                                                              focus:outline-2 focus:outline-primary focus:outline-offset-2
                                                              transition-all duration-200 font-medium text-xs
                                                              group"
                                                       title="Filter op {{ $item->organisation }}">
                                                        <i class="fas fa-building text-xs" aria-hidden="true"></i>
                                                        <span>{{ $item->organisation }}</span>
                                                        <i class="fas fa-filter text-xs opacity-70 group-hover:opacity-100 transition-opacity" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            @if($item->metadata && isset($item->metadata['document']['weblocatie']))
                                                <div class="mt-3">
                                                    <a href="{{ $item->metadata['document']['weblocatie'] }}" 
                                                       target="_blank" 
                                                       rel="noopener noreferrer"
                                                       class="text-primary font-medium text-sm inline-flex items-center gap-1.5
                                                              hover:underline focus:outline-2 focus:outline-primary focus:outline-offset-2
                                                              transition-all duration-200 rounded-sm">
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
                        <nav class="flex items-center justify-center gap-2 flex-wrap" aria-label="Paginatie">
                            @if(($results['hasPreviousPage'] ?? false))
                                <a href="{{ request()->fullUrlWithQuery(['pagina' => ($results['page'] ?? 1) - 1]) }}" 
                                   class="px-4 py-2 rounded-full text-body-medium text-primary
                                          hover:bg-primary-container focus:outline-2 focus:outline-primary focus:outline-offset-2
                                          transition-colors duration-200 min-h-[44px] min-w-[44px] flex items-center justify-center"
                                   aria-label="Vorige pagina">
                                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                                </a>
                            @else
                                <span class="px-4 py-2 rounded-full text-body-medium text-on-surface-variant 
                                            min-h-[44px] min-w-[44px] flex items-center justify-center" 
                                      aria-disabled="true"
                                      aria-label="Vorige pagina">
                                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                                </span>
                            @endif
                            
                            @php
                                $currentPage = $results['page'] ?? 1;
                                $totalPages = ceil(($results['total'] ?? 0) / ($results['perPage'] ?? 20));
                                $showPages = 5;
                                $startPage = max(1, $currentPage - floor($showPages / 2));
                                $endPage = min($totalPages, $startPage + $showPages - 1);
                            @endphp
                            
                            @if($startPage > 1)
                                <a href="{{ request()->fullUrlWithQuery(['pagina' => 1]) }}" 
                                   class="px-4 py-2 rounded-full text-body-medium text-primary
                                          hover:bg-primary-container focus:outline-2 focus:outline-primary focus:outline-offset-2
                                          transition-colors duration-200 min-h-[44px] min-w-[44px] flex items-center justify-center">
                                    1
                                </a>
                                @if($startPage > 2)
                                    <span class="px-4 py-2 text-body-medium text-on-surface-variant">...</span>
                                @endif
                            @endif
                            
                            @for($i = $startPage; $i <= $endPage; $i++)
                                <a 
                                    href="{{ request()->fullUrlWithQuery(['pagina' => $i]) }}" 
                                    class="px-4 py-2 rounded-full text-body-medium transition-colors duration-200
                                           min-h-[44px] min-w-[44px] flex items-center justify-center
                                           focus:outline-2 focus:outline-primary focus:outline-offset-2
                                           {{ $i == $currentPage ? 'bg-primary text-on-primary' : 'text-primary hover:bg-primary-container' }}">
                                    {{ $i }}
                                </a>
                            @endfor
                            
                            @if($endPage < $totalPages)
                                @if($endPage < $totalPages - 1)
                                    <span class="px-4 py-2 text-body-medium text-on-surface-variant">...</span>
                                @endif
                                <a href="{{ request()->fullUrlWithQuery(['pagina' => $totalPages]) }}" 
                                   class="px-4 py-2 rounded-full text-body-medium text-primary
                                          hover:bg-primary-container focus:outline-2 focus:outline-primary focus:outline-offset-2
                                          transition-colors duration-200 min-h-[44px] min-w-[44px] flex items-center justify-center">
                                    {{ $totalPages }}
                                </a>
                            @endif
                            
                            @if(($results['hasNextPage'] ?? false))
                                <a href="{{ request()->fullUrlWithQuery(['pagina' => ($results['page'] ?? 1) + 1]) }}" 
                                   class="px-4 py-2 rounded-full text-body-medium text-primary
                                          hover:bg-primary-container focus:outline-2 focus:outline-primary focus:outline-offset-2
                                          transition-colors duration-200 min-h-[44px] min-w-[44px] flex items-center justify-center"
                                   aria-label="Volgende pagina">
                                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                                </a>
                            @else
                                <span class="px-4 py-2 rounded-full text-body-medium text-on-surface-variant 
                                            min-h-[44px] min-w-[44px] flex items-center justify-center" 
                                      aria-disabled="true"
                                      aria-label="Volgende pagina">
                                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                                </span>
                            @endif
                        </nav>
                    @endif
                @endif
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-surface-variant border-t border-outline-variant mt-16" role="contentinfo">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <nav class="flex flex-wrap gap-6" aria-label="Footer navigatie">
                <a href="#" 
                   class="text-body-medium text-on-surface-variant 
                          hover:text-primary transition-colors duration-200
                          focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm">
                    Over deze website
                </a>
                <a href="#" 
                   class="text-body-medium text-on-surface-variant 
                          hover:text-primary transition-colors duration-200
                          focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm">
                    Overheid.nl
                </a>
                <a href="#" 
                   class="text-body-medium text-on-surface-variant 
                          hover:text-primary transition-colors duration-200
                          focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm">
                    Privacy & Cookies
                </a>
                <a href="#" 
                   class="text-body-medium text-on-surface-variant 
                          hover:text-primary transition-colors duration-200
                          focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm">
                    Toegankelijkheid
                </a>
            </nav>
        </div>
    </footer>
    
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

        // Quick Filter Combobox functionality
        const quickFilterOptions = {
            organisatie: @json($allFilterOptions['organisatie'] ?? []),
            thema: @json($allFilterOptions['thema'] ?? []),
            documentsoort: @json($allFilterOptions['documentsoort'] ?? []),
            informatiecategorie: @json($allFilterOptions['informatiecategorie'] ?? []),
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

            // Search in organisations
            quickFilterOptions.organisatie.forEach(org => {
                if (org.toLowerCase().includes(lowerQuery)) {
                    matches.push({
                        type: 'organisatie',
                        label: org,
                        value: org,
                        secondary: 'Organisatie'
                    });
                }
            });

            // Search in themes
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

            // Search in document types
            quickFilterOptions.documentsoort.forEach(type => {
                if (type.toLowerCase().includes(lowerQuery)) {
                    matches.push({
                        type: 'documentsoort',
                        label: type,
                        value: type,
                        secondary: 'Documentsoort'
                    });
                }
            });

            // Search in information categories
            quickFilterOptions.informatiecategorie.forEach(category => {
                if (category.toLowerCase().includes(lowerQuery)) {
                    matches.push({
                        type: 'informatiecategorie',
                        label: category,
                        value: category,
                        secondary: 'Informatiecategorie'
                    });
                }
            });

            if (matches.length === 0) {
                resultsDiv.innerHTML = '<div class="px-4 py-3 text-body-medium text-on-surface-variant">Geen resultaten gevonden</div>';
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
                <a href="/zoeken?${paramName}=${encodeURIComponent(match.value)}${searchParam}" 
                   class="block px-4 py-3 hover:bg-surface-variant transition-colors duration-150 border-b border-outline-variant last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-body-medium font-medium text-on-surface">${match.label}</div>
                            <div class="text-label-medium text-on-surface-variant">${match.secondary}</div>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-on-surface-variant" aria-hidden="true"></i>
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
</body>
</html>
