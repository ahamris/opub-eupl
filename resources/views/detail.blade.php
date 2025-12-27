@extends('layouts.app')

@section('title', ($jsonData['ai_enhanced_title'] ?? $jsonData['title'] ?? 'Document Details') . ' - Open Overheid')
    
@push('styles')
    <style>
        /* Tab transitions */
        .tab-content {
            display: none;
            animation: fadeIn 0.2s ease-out;
        }
        .tab-content.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(2px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Audio Visualizer Animation */
        .bar {
            width: 3px;
            background: var(--color-outline);
            border-radius: 2px;
            height: 4px;
            transition: height 0.2s, background-color 0.2s;
        }
        .playing .bar {
            background: var(--color-primary);
            animation: bounce 1s infinite ease-in-out;
        }
        @keyframes bounce {
            0%, 100% { height: 6px; }
            50% { height: 16px; }
        }
        .playing .bar:nth-child(1) { animation-delay: 0.1s; }
        .playing .bar:nth-child(2) { animation-delay: 0.3s; }
        .playing .bar:nth-child(3) { animation-delay: 0.2s; }
        .playing .bar:nth-child(4) { animation-delay: 0.4s; }

        /* JSON Syntax Highlighting - Theme Compatible */
        .json-key { color: var(--color-primary); font-weight: 600; }
        .json-string { color: var(--color-secondary, #0b6e99); }
        .json-number { color: var(--color-tertiary, #0b6e99); }
        .json-boolean { color: var(--color-tertiary, #0b6e99); font-weight: 600; }
        .json-null { color: var(--color-on-surface-variant); font-style: italic; }

        /* Extended characteristics toggle */
        .characteristics-extended {
            display: none;
        }
        .characteristics-extended.show {
            display: contents;
        }
    </style>
@endpush

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'Open Overheid', 'href' => route('zoeken')],
        ['label' => Str::limit($jsonData['title'] ?? 'Document', 50), 'href' => null, 'current' => true],
    ];
    
    // Extract metadata (nested JSON data from API)
    $metadata = $jsonData['metadata'] ?? [];
    $documentMeta = $metadata['document'] ?? [];
    $versies = $metadata['versies'] ?? [];
    $firstVersion = $versies[0] ?? [];
    $bestanden = $firstVersion['bestanden'] ?? [];
    $firstBestand = $bestanden[0] ?? [];
    $classificatie = $documentMeta['classificatiecollectie'] ?? [];
    $documentsoorten = $classificatie['documentsoorten'] ?? [];
    $informatiecategorieen = $classificatie['informatiecategorieen'] ?? [];
    $themasMeta = $classificatie['themas'] ?? [];
    $verantwoordelijke = $documentMeta['verantwoordelijke'] ?? [];
    $publisher = $documentMeta['publisher'] ?? [];
    $geldigheid = $documentMeta['geldigheid'] ?? [];
    
    // Publication date - use model's publication_date first
    $pubDate = null;
    if (isset($jsonData['publication_date'])) {
        try {
            $pubDate = \Carbon\Carbon::parse($jsonData['publication_date'])->format('d M Y');
        } catch (\Exception $e) {
            $pubDate = $jsonData['publication_date'];
        }
    }
    
    // Organisation - use model's organisation field first, then fallback to metadata
    $orgName = $jsonData['organisation'] 
        ?? $publisher['label'] 
        ?? $verantwoordelijke['naam'] 
        ?? null;
    
    // Woo info category - check woo_informatiecategorie first, then category, then metadata
    $wooInfoCategorie = $jsonData['woo_informatiecategorie'] 
        ?? $jsonData['category'] 
        ?? ($informatiecategorieen[0]['label'] ?? null);
    
    // Theme - use model's theme field first, then fallback to metadata
    $theme = $jsonData['theme'] 
        ?? (!empty($themasMeta) ? $themasMeta[0]['label'] : null);
    
    // AI keywords for tags - use ai_keywords array if available
    $aiKeywords = $jsonData['ai_keywords'] ?? [];
    
    // Build tags from ai_keywords, theme, or metadata themas
    $tagLabels = [];
    if (!empty($aiKeywords) && is_array($aiKeywords)) {
        $tagLabels = $aiKeywords;
    } elseif (!empty($themasMeta)) {
        foreach ($themasMeta as $t) {
            if (isset($t['label'])) {
                $tagLabels[] = $t['label'];
            }
        }
    } elseif ($theme) {
        $tagLabels = [$theme];
    }
    
    // Document type - use model's document_type field first
    $documentType = $jsonData['document_type'] 
        ?? ($documentsoorten[0]['label'] ?? 'Document');
    
    // AI enhanced title
    $aiTitle = $jsonData['ai_enhanced_title'] ?? null;
    
    // AI summary - check ai_analysis.summary first, then other fields
    $aiAnalysis = $jsonData['ai_analysis'] ?? [];
    $aiSummary = $aiAnalysis['summary'] 
        ?? $jsonData['ai_summary'] 
        ?? $jsonData['ai_enhanced_description'] 
        ?? null;
    $actionNeeded = $aiAnalysis['action_needed'] ?? false;
    
    // Get geldig vanaf date from metadata
    $geldigVanaf = null;
    if (isset($geldigheid['begindatum'])) {
        try {
            $geldigVanaf = \Carbon\Carbon::parse($geldigheid['begindatum'])->format('d F Y');
        } catch (\Exception $e) {
            $geldigVanaf = $geldigheid['begindatum'];
        }
    }
    
    // PDF URL from metadata
    $pdfUrl = $firstBestand['bestandsnaam'] ?? null;
    if ($pdfUrl && !str_starts_with($pdfUrl, 'http')) {
        $pdfUrl = 'https://repository.overheid.nl/' . $pdfUrl;
    }
    
    // Description/summary
    $description = $jsonData['description'] ?? $jsonData['summary'] ?? null;
    
    // Web location for original source link
    $weblocatie = $documentMeta['weblocatie'] ?? null;
    
    // Format publication date for display
    $pubDateFormatted = null;
    if (isset($jsonData['publication_date'])) {
        try {
            $pubDateFormatted = \Carbon\Carbon::parse($jsonData['publication_date'])->format('d F Y');
        } catch (\Exception $e) {
            $pubDateFormatted = $pubDate;
        }
    }
@endphp

@section('content')
    <!-- Header Section -->
    <x-page-header 
        eyebrow="Document details"
        :title="$jsonData['ai_enhanced_title'] ?? $jsonData['title'] ?? 'Geen titel beschikbaar'"
        :breadcrumbs="$breadcrumbs"
    />

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-6 lg:px-8 pt-8 pb-20">
        
        <!-- Back link -->
        <a href="/zoeken{{ request()->get('from') ? '?zoeken=' . urlencode(request()->get('from')) : '' }}" 
           class="text-[var(--color-primary)] font-medium inline-flex items-center gap-2
                  hover:text-[var(--color-primary-dark)] focus:outline-none
                  transition-colors duration-200 mb-6">
            <i class="fas fa-arrow-left" aria-hidden="true"></i>
            <span>Terug naar zoekresultaten</span>
        </a>

        <!-- Document Header Badges -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-2 mb-3">
                @if(isset($jsonData['status']))
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                        ● {{ $jsonData['status'] }}
                    </span>
                @endif
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-[var(--color-surface-variant)] text-[var(--color-on-surface-variant)] border border-[var(--color-outline-variant)]">
                    {{ $documentType }}
                </span>
                @if($pubDate)
                    <span class="text-xs text-[var(--color-on-surface-variant)] self-center ml-1">Gepubliceerd {{ $pubDate }}</span>
                @endif
            </div>
            
            @if(isset($jsonData['title']) && isset($jsonData['ai_enhanced_title']) && $jsonData['title'] !== $jsonData['ai_enhanced_title'])
                <p class="text-[var(--color-on-surface-variant)] text-sm">
                    Officieel: <span class="italic">{{ $jsonData['title'] }}</span>
                </p>
            @endif
        </div>

        <!-- Main Layout with Tabs and Sidebar -->
        <div x-data="{
                activeTab: 'context',
                isPlaying: false,
                showExtended: false
            }">
            
            <!-- Nav Tabs (Full Width) -->
            <div
                x-on:keydown.right.prevent.stop="$focus.wrap().next()"
                x-on:keydown.left.prevent.stop="$focus.wrap().previous()"
                x-on:keydown.home.prevent.stop="$focus.first()"
                x-on:keydown.end.prevent.stop="$focus.last()"
                class="flex items-center text-sm lg:max-w-[66.666667%]"
            >
                <button
                    x-on:click="activeTab = 'context'"
                    x-on:focus="activeTab = 'context'"
                    type="button"
                    id="context-tab"
                    role="tab"
                    aria-controls="context-tab-pane"
                    x-bind:aria-selected="activeTab === 'context' ? 'true' : 'false'"
                    x-bind:tabindex="activeTab === 'context' ? '0' : '-1'"
                    x-bind:class="{
                        'text-[var(--color-on-surface)] border-[var(--color-outline-variant)] bg-[var(--color-surface)]': activeTab === 'context',
                        'text-[var(--color-on-surface-variant)] border-transparent hover:text-[var(--color-primary)]': activeTab !== 'context',
                    }"
                    class="z-10 -mb-px flex items-center gap-2 rounded-t-lg border-x border-t px-5 py-3 font-medium focus:outline-none transition-colors duration-200"
                >
                    Context
                </button>
                <button
                    x-on:click="activeTab = 'metadata'"
                    x-on:focus="activeTab = 'metadata'"
                    type="button"
                    id="metadata-tab"
                    role="tab"
                    aria-controls="metadata-tab-pane"
                    x-bind:aria-selected="activeTab === 'metadata' ? 'true' : 'false'"
                    x-bind:tabindex="activeTab === 'metadata' ? '0' : '-1'"
                    x-bind:class="{
                        'text-[var(--color-on-surface)] border-[var(--color-outline-variant)] bg-[var(--color-surface)]': activeTab === 'metadata',
                        'text-[var(--color-on-surface-variant)] border-transparent hover:text-[var(--color-primary)]': activeTab !== 'metadata',
                    }"
                    class="z-10 -mb-px flex items-center gap-2 rounded-t-lg border-x border-t px-5 py-3 font-medium focus:outline-none transition-colors duration-200"
                >
                    Metadata
                </button>
                <button
                    x-on:click="activeTab = 'json'"
                    x-on:focus="activeTab = 'json'"
                    type="button"
                    id="json-tab"
                    role="tab"
                    aria-controls="json-tab-pane"
                    x-bind:aria-selected="activeTab === 'json' ? 'true' : 'false'"
                    x-bind:tabindex="activeTab === 'json' ? '0' : '-1'"
                    x-bind:class="{
                        'text-[var(--color-on-surface)] border-[var(--color-outline-variant)] bg-[var(--color-surface)]': activeTab === 'json',
                        'text-[var(--color-on-surface-variant)] border-transparent hover:text-[var(--color-primary)]': activeTab !== 'json',
                    }"
                    class="z-10 -mb-px flex items-center gap-2 rounded-t-lg border-x border-t px-5 py-3 font-medium focus:outline-none transition-colors duration-200"
                >
                    JSON
                </button>
            </div>
            <!-- END Nav Tabs -->

            <!-- Two Column Grid (Tab Content + Sidebar) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- LEFT COLUMN: Tab Content (8 cols) -->
                <div class="lg:col-span-8">
                    
                    <!-- Tab Content Container -->
                    <div class="rounded-b-lg lg:rounded-tr-lg border border-[var(--color-outline-variant)] bg-[var(--color-surface)] p-5">
                        
                        <!-- Context Tab -->
                        <div
                            x-show="activeTab === 'context'"
                            id="context-tab-pane"
                            role="tabpanel"
                            aria-labelledby="context-tab"
                            tabindex="0"
                        >
                    
                    <!-- AI Badge & Audio Player -->
                    <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                            ✨ Gegenereerd met AI
                        </span>
                        
                        <!-- Slim Audio Player -->
                        <div :class="{ 'playing': isPlaying }" 
                             class="flex items-center gap-3 bg-[var(--color-surface-variant)] border border-[var(--color-outline-variant)] rounded-full pl-1 pr-4 py-1 transition-all hover:border-[var(--color-primary)]">
                            <button @click="isPlaying = !isPlaying" 
                                    class="w-8 h-8 rounded-full bg-[var(--color-surface)] border border-[var(--color-outline-variant)] flex items-center justify-center text-[var(--color-primary)] hover:bg-[var(--color-primary)]/10 focus:outline-none transition-colors">
                                <svg x-show="!isPlaying" class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" /></svg>
                                <svg x-show="isPlaying" x-cloak class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                            </button>
                            <span class="text-xs font-medium text-[var(--color-on-surface-variant)]" x-text="isPlaying ? 'Aan het voorlezen...' : 'Lees voor (1:15)'"></span>
                            <div class="flex items-center gap-0.5 h-3">
                                <div class="bar"></div><div class="bar"></div><div class="bar"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Text Content -->
                    <div class="prose prose-slate max-w-none">
                        @if($aiSummary)
                            <p class="text-lg text-[var(--color-on-surface-variant)] leading-relaxed">
                                {!! nl2br(e($aiSummary)) !!}
                            </p>
                        @elseif($description)
                            <p class="text-lg text-[var(--color-on-surface-variant)] leading-relaxed">
                                {{ $description }}
                            </p>
                        @else
                            <p class="text-lg text-[var(--color-on-surface-variant)] leading-relaxed">
                                @if($orgName)
                                    <strong>{{ $orgName }}</strong> heeft dit document gepubliceerd.
                                @else
                                    Geen samenvatting beschikbaar voor dit document.
                                @endif
                            </p>
                        @endif
                        
                        <!-- Action Info Box -->
                        @if(isset($jsonData['ai_analysis']['action_needed']) && $jsonData['ai_analysis']['action_needed'])
                        <div class="my-8 bg-[var(--color-primary)]/5 rounded-xl p-6 border border-[var(--color-primary)]/20">
                            <h3 class="text-[var(--color-primary-dark)] text-base font-bold uppercase tracking-wider mb-4 mt-0">Wat betekent dit voor u?</h3>
                            <ul class="list-none p-0 m-0 space-y-3">
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-[var(--color-primary)]/20 text-[var(--color-primary)] flex items-center justify-center text-xs font-bold">1</span>
                                    <span class="text-[var(--color-on-surface-variant)] text-sm"><strong>Voor omwonenden:</strong> Er verandert mogelijk iets in uw omgeving. Het is slim om te kijken wat er precies verandert.</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-[var(--color-primary)]/20 text-[var(--color-primary)] flex items-center justify-center text-xs font-bold">2</span>
                                    <span class="text-[var(--color-on-surface-variant)] text-sm"><strong>Voor betrokkenen:</strong> Dit document bevat mogelijk regels waar u zich aan moet houden.</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-[var(--color-primary)]/20 text-[var(--color-primary)] flex items-center justify-center text-xs font-bold">!</span>
                                    <span class="text-[var(--color-on-surface-variant)] text-sm"><strong>Status:</strong> Controleer of u actie moet ondernemen (bijv. een zienswijze indienen).</span>
                                </li>
                            </ul>
                        </div>
                        @endif

                        <!-- Document Link -->
                        <div class="mt-6 pt-6 border-t border-[var(--color-outline-variant)]">
                            <h3 class="text-lg font-semibold mb-4 text-[var(--color-on-surface)]">Bekijk origineel</h3>
                            <div class="flex flex-wrap gap-3">
                                @if(isset($jsonData['external_id']))
                                    <a href="https://open.overheid.nl/details/{{ $jsonData['external_id'] }}" 
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center gap-2 px-4 py-2 rounded-md
                                              bg-[var(--color-primary)] text-[var(--color-on-primary)] border border-[var(--color-primary)]
                                              hover:bg-[var(--color-primary-dark)]
                                              focus:outline-none
                                              transition-colors duration-200 text-sm font-medium">
                                        <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                                        <span>Bekijk op open.overheid.nl</span>
                                    </a>
                                @endif
                                @if(isset($documentMeta['weblocatie']))
                                    <a href="{{ $documentMeta['weblocatie'] }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center gap-2 px-4 py-2 rounded-md
                                              bg-[var(--color-surface)] text-[var(--color-on-surface)] border border-[var(--color-outline-variant)]
                                              hover:bg-[var(--color-surface-variant)]
                                              focus:outline-none
                                              transition-colors duration-200 text-sm font-medium">
                                        <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                                        <span>Officielebekendmakingen.nl</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                        </div>
                        <!-- END Context Tab -->

                        <!-- Metadata Tab -->
                        <div
                            x-cloak
                            x-show="activeTab === 'metadata'"
                            id="metadata-tab-pane"
                            role="tabpanel"
                            aria-labelledby="metadata-tab"
                            tabindex="0"
                        >
                    
                    <!-- Woo Classificatie Section -->
                    <div class="bg-[var(--color-surface)] rounded-lg border border-[var(--color-outline-variant)] shadow-sm overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-[var(--color-outline-variant)] bg-[var(--color-primary)]/5 flex items-center gap-2">
                            <h3 class="text-base font-bold text-[var(--color-on-surface)]">Woo-Classificatie</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[var(--color-surface)] text-[var(--color-primary)] border border-[var(--color-primary)]/20">
                                Wet open overheid
                            </span>
                        </div>
                        <dl class="divide-y divide-[var(--color-outline-variant)]">
                            @if($wooInfoCategorie)
                            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 hover:bg-[var(--color-surface-variant)] transition-colors">
                                <dt class="text-sm font-medium text-[var(--color-on-surface-variant)]">Informatiecategorie</dt>
                                <dd class="text-sm font-semibold text-[var(--color-on-surface)] sm:col-span-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[var(--color-primary)]/10 text-[var(--color-primary)]">
                                        {{ $wooInfoCategorie }}
                                    </span>
                                </dd>
                            </div>
                            @endif
                            @if($theme)
                            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 hover:bg-[var(--color-surface-variant)] transition-colors">
                                <dt class="text-sm font-medium text-[var(--color-on-surface-variant)]">Thema</dt>
                                <dd class="text-sm text-[var(--color-on-surface)] sm:col-span-2">{{ $theme }}</dd>
                            </div>
                            @endif
                            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 hover:bg-[var(--color-surface-variant)] transition-colors">
                                <dt class="text-sm font-medium text-[var(--color-on-surface-variant)]">Documentsoort</dt>
                                <dd class="text-sm text-[var(--color-on-surface)] sm:col-span-2">{{ $documentType }}</dd>
                            </div>
                            @if(isset($dossierMembers) && $dossierMembers->isNotEmpty())
                            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 hover:bg-[var(--color-surface-variant)] transition-colors">
                                <dt class="text-sm font-medium text-[var(--color-on-surface-variant)]">Dossier / Rubriek</dt>
                                <dd class="text-sm text-[var(--color-on-surface)] sm:col-span-2">
                                    {{ $dossierMembers->count() }} gerelateerde documenten
                                </dd>
                            </div>
                            @else
                            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 hover:bg-[var(--color-surface-variant)] transition-colors">
                                <dt class="text-sm font-medium text-[var(--color-on-surface-variant)]">Dossier / Rubriek</dt>
                                <dd class="text-sm text-[var(--color-on-surface-variant)] sm:col-span-2 italic">
                                    Geen dossier gekoppeld
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Technical Details Section -->
                    <div class="bg-[var(--color-surface)] rounded-lg border border-[var(--color-outline-variant)] shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-[var(--color-outline-variant)] bg-[var(--color-surface-variant)]">
                            <h3 class="text-base font-semibold text-[var(--color-on-surface)]">Documentdetails</h3>
                        </div>
                        <dl class="divide-y divide-[var(--color-outline-variant)]">
                            @if(isset($jsonData['external_id']))
                            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 hover:bg-[var(--color-surface-variant)] transition-colors">
                                <dt class="text-sm font-medium text-[var(--color-on-surface-variant)]">Document ID</dt>
                                <dd class="text-sm text-[var(--color-on-surface-variant)] sm:col-span-2 font-mono">{{ $jsonData['external_id'] }}</dd>
                            </div>
                            @endif
                            @if($pubDateFormatted)
                            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 hover:bg-[var(--color-surface-variant)] transition-colors">
                                <dt class="text-sm font-medium text-[var(--color-on-surface-variant)]">Publicatiedatum</dt>
                                <dd class="text-sm text-[var(--color-on-surface)] sm:col-span-2">{{ $pubDateFormatted }}</dd>
                            </div>
                            @endif
                            @if($geldigVanaf)
                            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 hover:bg-[var(--color-surface-variant)] transition-colors">
                                <dt class="text-sm font-medium text-[var(--color-on-surface-variant)]">Geldig vanaf</dt>
                                <dd class="text-sm text-[var(--color-on-surface)] sm:col-span-2">{{ $geldigVanaf }}</dd>
                            </div>
                            @endif
                            @if($orgName)
                            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 hover:bg-[var(--color-surface-variant)] transition-colors">
                                <dt class="text-sm font-medium text-[var(--color-on-surface-variant)]">Organisatie</dt>
                                <dd class="text-sm text-[var(--color-on-surface)] sm:col-span-2">{{ $orgName }}</dd>
                            </div>
                            @endif
                            @if($weblocatie || isset($jsonData['external_id']))
                            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 hover:bg-[var(--color-surface-variant)] transition-colors">
                                <dt class="text-sm font-medium text-[var(--color-on-surface-variant)]">Originele bron</dt>
                                <dd class="text-sm text-[var(--color-on-surface)] sm:col-span-2">
                                    <a href="{{ $weblocatie ?? 'https://open.overheid.nl/details/' . $jsonData['external_id'] }}" target="_blank" class="text-[var(--color-primary)] hover:underline inline-flex items-center">
                                        {{ $weblocatie ? 'officielebekendmakingen.nl' : 'open.overheid.nl' }}
                                        <svg class="ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </dd>
                            </div>
                            @endif

                            <!-- Extended characteristics -->
                            <template x-if="showExtended">
                                <div class="contents">
                                    @if(isset($verantwoordelijke['label']))
                                    <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 hover:bg-[var(--color-surface-variant)] transition-colors">
                                        <dt class="text-sm font-medium text-[var(--color-on-surface-variant)]">Verantwoordelijke</dt>
                                        <dd class="text-sm text-[var(--color-on-surface)] sm:col-span-2">{{ $verantwoordelijke['label'] }}</dd>
                                    </div>
                                    @endif
                                    @php
                                        $opsteller = $documentMeta['opsteller'] ?? [];
                                    @endphp
                                    @if(isset($opsteller['label']))
                                    <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 hover:bg-[var(--color-surface-variant)] transition-colors">
                                        <dt class="text-sm font-medium text-[var(--color-on-surface-variant)]">Opsteller</dt>
                                        <dd class="text-sm text-[var(--color-on-surface)] sm:col-span-2">{{ $opsteller['label'] }}</dd>
                                    </div>
                                    @endif
                                    @if(isset($firstBestand['aantalPaginas']))
                                    <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 hover:bg-[var(--color-surface-variant)] transition-colors">
                                        <dt class="text-sm font-medium text-[var(--color-on-surface-variant)]">Aantal pagina's</dt>
                                        <dd class="text-sm text-[var(--color-on-surface)] sm:col-span-2">{{ $firstBestand['aantalPaginas'] }}</dd>
                                    </div>
                                    @endif
                                    @if(isset($firstBestand['grootte']))
                                    <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 hover:bg-[var(--color-surface-variant)] transition-colors">
                                        <dt class="text-sm font-medium text-[var(--color-on-surface-variant)]">Bestandsgrootte</dt>
                                        <dd class="text-sm text-[var(--color-on-surface)] sm:col-span-2">{{ number_format($firstBestand['grootte'], 0, ',', '.') }} bytes</dd>
                                    </div>
                                    @endif
                                    @php
                                        $language = $documentMeta['language'] ?? [];
                                    @endphp
                                    @if(isset($language['label']))
                                    <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 hover:bg-[var(--color-surface-variant)] transition-colors">
                                        <dt class="text-sm font-medium text-[var(--color-on-surface-variant)]">Taal</dt>
                                        <dd class="text-sm text-[var(--color-on-surface)] sm:col-span-2">{{ $language['label'] }}</dd>
                                    </div>
                                    @endif
                                </div>
                            </template>
                        </dl>
                        <div class="px-6 py-4 border-t border-[var(--color-outline-variant)]">
                            <button @click="showExtended = !showExtended"
                                    class="text-sm font-medium text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] transition-colors">
                                <span x-text="showExtended ? 'Toon minder kenmerken' : 'Toon alle kenmerken'"></span>
                                <i class="fas ml-1" :class="showExtended ? 'fa-chevron-up' : 'fa-chevron-down'" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Dossier Section -->
                    @if(isset($dossierMembers) && $dossierMembers->isNotEmpty())
                    <div class="mt-8 pt-6 border-t border-[var(--color-outline-variant)]">
                        <h2 class="text-xl font-semibold mb-4 text-[var(--color-on-surface)]">Dossier</h2>
                        <p class="text-sm text-[var(--color-on-surface-variant)] mb-4">
                            Dit document maakt deel uit van een dossier met {{ $dossierMembers->count() }} gerelateerd{{ $dossierMembers->count() !== 1 ? 'e' : '' }} document{{ $dossierMembers->count() !== 1 ? 'en' : '' }}:
                        </p>
                        <div class="space-y-3">
                            @foreach($dossierMembers as $member)
                                <a href="/open-overheid/documents/{{ $member->external_id }}{{ request()->get('from') ? '?from=' . urlencode(request()->get('from')) : '' }}" 
                                   class="block p-4 rounded-md border border-[var(--color-outline-variant)]
                                          bg-[var(--color-surface)] hover:bg-[var(--color-surface-variant)]
                                          focus:outline-none
                                          transition-colors duration-200
                                          group">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-base font-medium text-[var(--color-on-surface)] group-hover:text-[var(--color-primary)] transition-colors mb-2 line-clamp-2">
                                                {{ $member->title ?? 'Geen titel beschikbaar' }}
                                            </h3>
                                            <div class="flex flex-wrap gap-3 text-xs text-[var(--color-on-surface-variant)]">
                                                @if($member->document_type)
                                                    <span class="inline-flex items-center gap-1.5">
                                                        <i class="fas fa-file-alt text-xs" aria-hidden="true"></i>
                                                        <span>{{ $member->document_type }}</span>
                                                    </span>
                                                @endif
                                                @if($member->publication_date)
                                                    <span class="inline-flex items-center gap-1.5">
                                                        <i class="fas fa-calendar text-xs" aria-hidden="true"></i>
                                                        <span>{{ $member->publication_date->format('d-m-Y') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <i class="fas fa-chevron-right text-[var(--color-on-surface-variant)] group-hover:text-[var(--color-primary)] transition-colors mt-1 flex-shrink-0" aria-hidden="true"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                        </div>
                        <!-- END Metadata Tab -->

                        <!-- JSON Tab -->
                        <div
                            x-cloak
                            x-show="activeTab === 'json'"
                            x-init="$watch('activeTab', value => { if(value === 'json') initJSONDisplay() })"
                            id="json-tab-pane"
                            role="tabpanel"
                            aria-labelledby="json-tab"
                            tabindex="0"
                        >
                            <div class="relative bg-[var(--color-surface-variant)] rounded-lg p-4 font-mono text-sm overflow-x-auto border border-[var(--color-outline-variant)]">
                                <button onclick="copyJSON(event)" class="absolute top-4 right-4 text-xs bg-[var(--color-primary)] hover:bg-[var(--color-primary-dark)] text-[var(--color-on-primary)] px-3 py-1 rounded transition-colors">Kopieer</button>
                                <pre id="json-display" class="text-[var(--color-on-surface)]"><code></code></pre>
                            </div>
                        </div>
                        <!-- END JSON Tab -->

                    </div>
                    <!-- END Tab Content Container -->
                </div>
                <!-- END LEFT COLUMN -->

            <!-- RIGHT COLUMN: Sidebar (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Action Card / Downloads -->
                <div class="bg-[var(--color-surface)] p-5 rounded-lg border border-[var(--color-outline-variant)] shadow-sm">
                    <h3 class="font-semibold text-[var(--color-on-surface)] mb-4 text-sm uppercase tracking-wide">Downloads</h3>
                    
                    <!-- PDF Button -->
                    @if($pdfUrl)
                    <a href="{{ $pdfUrl }}" target="_blank" class="group block w-full mb-3 text-decoration-none">
                        <div class="bg-[var(--color-primary)] hover:bg-[var(--color-primary-dark)] text-[var(--color-on-primary)] font-medium py-3 px-4 rounded-md transition-all shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Download PDF
                        </div>
                        <div class="text-xs text-[var(--color-on-surface-variant)] text-center mt-1 group-hover:text-[var(--color-primary)] transition-colors">
                            Inclusief samenvatting & metadata
                        </div>
                    </a>
                    @endif

                    <!-- XML Button -->
                    <a href="{{ url()->current() }}?format=xml" class="group block w-full text-decoration-none">
                        <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] hover:bg-[var(--color-surface-variant)] text-[var(--color-on-surface)] font-medium py-2.5 px-4 rounded-md transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-[var(--color-on-surface-variant)] group-hover:text-[var(--color-on-surface)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            Download XML (Bron)
                        </div>
                    </a>
                </div>

                <!-- Location Card / Betreft Locatie -->
                @if($orgName)
                <div class="bg-[var(--color-surface)] p-5 rounded-lg border border-[var(--color-outline-variant)] shadow-sm">
                    <h3 class="font-semibold text-[var(--color-on-surface)] mb-3 text-sm uppercase tracking-wide">Betreft Locatie</h3>
                    <div class="relative w-full h-40 bg-[var(--color-surface-variant)] rounded-md mb-3 overflow-hidden flex items-center justify-center group cursor-pointer border border-[var(--color-outline-variant)]">
                        <!-- Simple Map Pattern -->
                        <div class="absolute inset-0 opacity-20">
                            <svg class="w-full h-full text-[var(--color-outline)]" fill="currentColor" viewBox="0 0 100 100"><rect x="0" y="0" width="10" height="100"/><rect x="20" y="0" width="10" height="100"/><rect x="50" y="0" width="30" height="100"/><rect x="0" y="40" width="100" height="5"/></svg>
                        </div>
                        <div class="z-10 bg-[var(--color-surface)] p-2 rounded shadow-md transform group-hover:-translate-y-1 transition-transform">
                            <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium text-[var(--color-on-surface)]">{{ $orgName }}</p>
                            <p class="text-sm text-[var(--color-on-surface-variant)]">Nederland</p>
                        </div>
                    </div>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($orgName) }}" target="_blank" class="mt-3 inline-flex items-center text-sm text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] font-medium">
                        Open in Google Maps <span class="ml-1">&rarr;</span>
                    </a>
                </div>
                @endif

                <!-- Tags / Onderwerpen -->
                @if(!empty($tagLabels))
                <div>
                    <h3 class="font-semibold text-[var(--color-on-surface)] mb-3 text-sm uppercase tracking-wide">Onderwerpen</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tagLabels as $tag)
                            <a href="/zoeken?thema[]={{ urlencode($tag) }}"
                               class="px-2.5 py-1 bg-[var(--color-surface-variant)] text-[var(--color-on-surface-variant)] rounded border border-[var(--color-outline-variant)] text-xs font-medium hover:bg-[var(--color-primary)]/10 hover:text-[var(--color-primary)] hover:border-[var(--color-primary)]/20 cursor-pointer transition-colors">
                                {{ ucfirst($tag) }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
            <!-- END RIGHT COLUMN -->
        </div>

    </main>
@endsection

@push('scripts')
    <script>
        // Format JSON with syntax highlighting
        function formatJSONValue(value, indent = 0) {
            const indentStr = '  '.repeat(indent);
            
            if (value === null) {
                return '<span class="json-null">null</span>';
            }
            
            if (typeof value === 'boolean') {
                return `<span class="json-boolean">${value}</span>`;
            }
            
            if (typeof value === 'number') {
                return `<span class="json-number">${value}</span>`;
            }
            
            if (typeof value === 'string') {
                const escaped = value
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;');
                return `<span class="json-string">"${escaped}"</span>`;
            }
            
            if (Array.isArray(value)) {
                if (value.length === 0) {
                    return '[]';
                }
                
                let html = '[\n';
                value.forEach((item, index) => {
                    html += indentStr + '  ' + formatJSONValue(item, indent + 1);
                    if (index < value.length - 1) html += ',';
                    html += '\n';
                });
                html += indentStr + ']';
                return html;
            }
            
            if (typeof value === 'object') {
                const keys = Object.keys(value);
                if (keys.length === 0) {
                    return '{}';
                }
                
                let html = '{\n';
                keys.forEach((key, index) => {
                    const escapedKey = key
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;');
                    html += indentStr + '  <span class="json-key">"' + escapedKey + '"</span>: ' + formatJSONValue(value[key], indent + 1);
                    if (index < keys.length - 1) html += ',';
                    html += '\n';
                });
                html += indentStr + '}';
                return html;
            }
            
            return String(value);
        }

        // Initialize JSON display when JSON tab is shown
        function initJSONDisplay() {
            const display = document.getElementById('json-display');
            if (display && (!display.innerHTML || display.innerHTML.trim() === '' || display.innerHTML.trim() === '<code></code>')) {
                try {
                    const jsonData = @json($jsonData);
                    display.innerHTML = formatJSONValue(jsonData);
                } catch (error) {
                    console.error('Error formatting JSON:', error);
                    display.innerHTML = '<span class="text-red-400">Error loading JSON data</span>';
                }
            }
        }

        // Copy JSON to clipboard
        function copyJSON(event) {
            const jsonString = JSON.stringify(@json($jsonData), null, 2);
            navigator.clipboard.writeText(jsonString).then(() => {
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'Gekopieerd!';
                setTimeout(() => {
                    button.textContent = originalText;
                }, 2000);
            }).catch(err => {
                alert('Kon JSON niet kopiëren: ' + err);
            });
        }

        // Handle format=json download
        document.addEventListener('DOMContentLoaded', function() {
            @if(request('format') === 'json')
                const jsonData = @json($jsonData);
                const jsonString = JSON.stringify(jsonData, null, 2);
                const blob = new Blob([jsonString], { type: 'application/json' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = '{{ ($jsonData['external_id'] ?? 'document') }}.json';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            @endif
        });
    </script>
@endpush
