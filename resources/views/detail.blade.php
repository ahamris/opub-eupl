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
    <header class="bg-[var(--color-surface)] border-b border-[var(--color-outline-variant)]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
            
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm text-[var(--color-on-surface-variant)] mb-4" aria-label="Breadcrumb">
                @foreach($breadcrumbs as $index => $crumb)
                    @if($index > 0)
                        <span class="text-[var(--color-outline)]">›</span>
                    @endif
                    @if(isset($crumb['current']) && $crumb['current'])
                        <span class="font-medium text-[var(--color-on-surface)]">{{ $crumb['label'] }}</span>
                    @else
                        <a href="{{ $crumb['href'] }}" class="hover:text-[var(--color-primary)] transition-colors">{{ $crumb['label'] }}</a>
                    @endif
                @endforeach
            </nav>
            
            <!-- Badges and Date -->
            <div class="flex flex-wrap items-center gap-2 mb-4">
                @if(isset($jsonData['status']))
                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                        {{ ucfirst($jsonData['status']) }}
                    </span>
                @endif
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-[var(--color-primary)]/10 text-[var(--color-primary)] border border-[var(--color-primary)]/20">
                    {{ $documentType }}
                </span>
                @if($pubDate)
                    <span class="text-sm text-[var(--color-on-surface-variant)]">Gepubliceerd {{ $pubDate }}</span>
                @endif
            </div>
            
            <!-- Title -->
            <h1 class="text-2xl lg:text-3xl font-bold text-[var(--color-on-surface)] mb-2">
                {{ $jsonData['ai_enhanced_title'] ?? $jsonData['title'] ?? 'Geen titel beschikbaar' }}
            </h1>
            
            <!-- Official Title -->
            @if(isset($jsonData['title']) && isset($jsonData['ai_enhanced_title']) && $jsonData['title'] !== $jsonData['ai_enhanced_title'])
                <p class="text-[var(--color-on-surface-variant)]">
                    Officieel: <span class="italic">{{ $jsonData['title'] }}</span>
                </p>
            @endif
            
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-6 lg:px-8 pt-8 pb-20">

        <!-- Main Layout with Tabs and Sidebar -->
        <div x-data="{
                activeTab: 'context',
                isPlaying: false,
                showExtended: true
            }">
            
            <!-- Nav Tabs -->
            <div
                x-on:keydown.right.prevent.stop="$focus.wrap().next()"
                x-on:keydown.left.prevent.stop="$focus.wrap().previous()"
                x-on:keydown.home.prevent.stop="$focus.first()"
                x-on:keydown.end.prevent.stop="$focus.last()"
                class="flex items-center gap-1 border-b border-[var(--color-outline-variant)] lg:max-w-[66.666667%]"
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
                        'text-[var(--color-primary)] border-[var(--color-primary)]': activeTab === 'context',
                        'text-[var(--color-on-surface-variant)] border-transparent hover:text-[var(--color-on-surface)] hover:border-[var(--color-outline)]': activeTab !== 'context',
                    }"
                    class="-mb-px px-4 py-3 text-sm font-medium border-b-2 focus:outline-none transition-colors duration-200"
                >
                    Samenvatting & Context
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
                        'text-[var(--color-primary)] border-[var(--color-primary)]': activeTab === 'metadata',
                        'text-[var(--color-on-surface-variant)] border-transparent hover:text-[var(--color-on-surface)] hover:border-[var(--color-outline)]': activeTab !== 'metadata',
                    }"
                    class="-mb-px px-4 py-3 text-sm font-medium border-b-2 focus:outline-none transition-colors duration-200"
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
                        'text-[var(--color-primary)] border-[var(--color-primary)]': activeTab === 'json',
                        'text-[var(--color-on-surface-variant)] border-transparent hover:text-[var(--color-on-surface)] hover:border-[var(--color-outline)]': activeTab !== 'json',
                    }"
                    class="-mb-px px-4 py-3 text-sm font-medium border-b-2 focus:outline-none transition-colors duration-200"
                >
                    JSON
                </button>
            </div>
            <!-- END Nav Tabs -->

            <!-- Two Column Grid (Tab Content + Sidebar) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- LEFT COLUMN: Tab Content (8 cols) -->
                <div class="lg:col-span-8">
                    
                    <!-- Tab Content -->
                    <div class="pt-6">
                        
                        <!-- Context Tab -->
                        <div
                            x-show="activeTab === 'context'"
                            id="context-tab-pane"
                            role="tabpanel"
                            aria-labelledby="context-tab"
                            tabindex="0"
                        >
                    
                    <!-- AI Badge & Audio Player -->
                    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                        @if($aiSummary)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" class="size-4">
                                    <path fill-rule="evenodd" d="M5 4a.75.75 0 0 1 .738.616l.252 1.388A1.25 1.25 0 0 0 6.996 7.01l1.388.252a.75.75 0 0 1 0 1.476l-1.388.252A1.25 1.25 0 0 0 5.99 9.996l-.252 1.388a.75.75 0 0 1-1.476 0L4.01 9.996A1.25 1.25 0 0 0 3.004 8.99l-1.388-.252a.75.75 0 0 1 0-1.476l1.388-.252A1.25 1.25 0 0 0 4.01 6.004l.252-1.388A.75.75 0 0 1 5 4ZM12 1a.75.75 0 0 1 .721.544l.195.682c.118.415.443.74.858.858l.682.195a.75.75 0 0 1 0 1.442l-.682.195a1.25 1.25 0 0 0-.858.858l-.195.682a.75.75 0 0 1-1.442 0l-.195-.682a1.25 1.25 0 0 0-.858-.858l-.682-.195a.75.75 0 0 1 0-1.442l.682-.195a1.25 1.25 0 0 0 .858-.858l.195-.682A.75.75 0 0 1 12 1ZM10 11a.75.75 0 0 1 .728.568.968.968 0 0 0 .704.704.75.75 0 0 1 0 1.456.968.968 0 0 0-.704.704.75.75 0 0 1-1.456 0 .968.968 0 0 0-.704-.704.75.75 0 0 1 0-1.456.968.968 0 0 0 .704-.704A.75.75 0 0 1 10 11Z" clip-rule="evenodd"></path>
                                </svg>
                            Gegenereerd met AI
                        </span>
                        @endif
                        
                        <!-- Audio Player -->
                        <button 
                            @click="isPlaying = !isPlaying"
                            :class="isPlaying ? 'bg-[var(--color-primary)] text-[var(--color-on-primary)]' : 'bg-[var(--color-surface)] text-[var(--color-on-surface)]'"
                            class="ml-auto inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-[var(--color-outline-variant)] hover:border-[var(--color-primary)] transition-all focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20"
                        >
                            <svg x-show="!isPlaying" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                            <svg x-show="isPlaying" x-cloak class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span class="text-sm font-medium" x-text="isPlaying ? 'Voorlezen...' : 'Lees voor'"></span>
                            <span class="text-xs opacity-70">(1:15)</span>
                        </button>
                    </div>

                    <!-- Main Text Content -->
                    <article class="max-w-none">
                        @if($aiSummary)
                            <p class="text-base text-[var(--color-on-surface)] leading-relaxed">
                                {!! nl2br(e($aiSummary)) !!}
                            </p>
                        @elseif($description)
                            <p class="text-base text-[var(--color-on-surface)] leading-relaxed">
                                {{ $description }}
                            </p>
                        @else
                            <p class="text-[var(--color-on-surface-variant)]">
                                Geen samenvatting beschikbaar voor dit document.
                            </p>
                        @endif
                        
                        <!-- Action Info Box -->
                        @if(isset($jsonData['ai_analysis']['action_needed']) && $jsonData['ai_analysis']['action_needed'])
                        <div class="mt-6 p-4 rounded-lg border border-[var(--color-outline-variant)]">
                            <h4 class="text-sm font-medium text-[var(--color-on-surface)] mb-2">Wat betekent dit voor u?</h4>
                            <ul class="space-y-1 text-sm text-[var(--color-on-surface-variant)]">
                                <li>• Controleer of dit document invloed heeft op uw situatie</li>
                                <li>• Mogelijk moet u actie ondernemen (bijv. zienswijze indienen)</li>
                            </ul>
                        </div>
                        @endif

                        <!-- Document Link -->
                        <div class="mt-8 pt-6 border-t border-[var(--color-outline-variant)]">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface-variant)] uppercase tracking-wider mb-4">Bekijk origineel</h3>
                            <div class="flex flex-wrap gap-3">
                                @if(isset($jsonData['external_id']))
                                    <a href="https://open.overheid.nl/details/{{ $jsonData['external_id'] }}" 
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg
                                              bg-[var(--color-primary)] text-[var(--color-on-primary)]
                                              hover:bg-[var(--color-primary-dark)]
                                              focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20
                                              transition-colors duration-200 text-sm font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span>open.overheid.nl</span>
                                    </a>
                                @endif
                                @if(isset($documentMeta['weblocatie']))
                                    <a href="{{ $documentMeta['weblocatie'] }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg
                                              bg-[var(--color-surface)] text-[var(--color-on-surface)] border border-[var(--color-outline-variant)]
                                              hover:bg-[var(--color-surface-variant)] hover:border-[var(--color-outline)]
                                              focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20
                                              transition-colors duration-200 text-sm font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span>officielebekendmakingen.nl</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </article>
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
                    <div class="rounded-lg border border-[var(--color-outline-variant)] overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-[var(--color-outline-variant)] flex items-center justify-between">
                            <h3 class="text-sm font-medium text-[var(--color-on-surface)]">Woo-Classificatie</h3>
                            <span class="text-xs text-[var(--color-on-surface-variant)]">Wet open overheid</span>
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
                    <div class="rounded-lg border border-[var(--color-outline-variant)] overflow-hidden">
                        <div class="px-6 py-4 border-b border-[var(--color-outline-variant)]">
                            <h3 class="text-sm font-medium text-[var(--color-on-surface)]">Documentdetails</h3>
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
                                <span x-text="showExtended ? 'Minder tonen' : 'Meer tonen'"></span>
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
                    <!-- END Tab Content -->
                </div>
                <!-- END LEFT COLUMN -->

            <!-- RIGHT COLUMN: Sidebar (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Downloads -->
                <div>
                    <h3 class="text-sm font-medium text-[var(--color-on-surface)] mb-3">Downloads</h3>
                    <div class="space-y-2">
                        @if($pdfUrl)
                        <a href="{{ $pdfUrl }}" target="_blank" class="block w-full bg-[var(--color-primary)] hover:bg-[var(--color-primary-dark)] text-[var(--color-on-primary)] font-medium py-2.5 px-4 rounded-lg transition-colors text-center text-sm">
                            Download PDF
                        </a>
                        @endif
                        <a href="{{ url()->current() }}?format=xml" class="block w-full border border-[var(--color-outline-variant)] hover:border-[var(--color-outline)] text-[var(--color-on-surface)] font-medium py-2 px-4 rounded-lg transition-colors text-center text-sm">
                            Download XML
                        </a>
                    </div>
                </div>

                <!-- Location -->
                @if($orgName)
                <div>
                    <h3 class="text-sm font-medium text-[var(--color-on-surface)] mb-3">Betreft Locatie</h3>
                    
                    <!-- Map Placeholder -->
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($orgName . ', Nederland') }}" 
                       target="_blank" 
                       class="block w-full h-32 bg-[var(--color-surface-variant)] rounded-lg mb-3 overflow-hidden relative group border border-[var(--color-outline-variant)]">
                        <!-- Static Map from OpenStreetMap -->
                        <img src="https://staticmap.openstreetmap.de/staticmap.php?center=52.1326,5.2913&zoom=7&size=400x160&maptype=osmarenderer" 
                             alt="Kaart van Nederland" 
                             class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity"
                             loading="lazy">
                        <!-- Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/10 transition-colors">
                            <div class="bg-white/90 px-3 py-1.5 rounded-full text-xs font-medium text-[var(--color-on-surface)] flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-[var(--color-primary)]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                Bekijk op kaart
                            </div>
                        </div>
                    </a>
                    
                    <div class="text-sm">
                        <p class="text-[var(--color-on-surface)]">{{ $orgName }}</p>
                        <p class="text-[var(--color-on-surface-variant)]">Nederland</p>
                    </div>
                </div>
                @endif

                <!-- Tags -->
                @if(!empty($tagLabels))
                <div>
                    <h3 class="text-sm font-medium text-[var(--color-on-surface)] mb-3">Onderwerpen</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tagLabels as $tag)
                            <a href="/zoeken?thema[]={{ urlencode($tag) }}"
                               class="px-2.5 py-1 rounded text-xs border border-[var(--color-outline-variant)] text-[var(--color-on-surface-variant)] hover:border-[var(--color-primary)] hover:text-[var(--color-primary)] transition-colors">
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
