@extends('layouts.app')

@section('title', ($jsonData['title'] ?? 'Document Details') . ' - Open Overheid')

@push('styles')
    <style>
        .document-detail {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 16px;
            color: #01689b;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .document-title-block {
            background-color: #e3f2fd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .document-title {
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 12px 0;
            color: #333;
        }

        .document-quick-info {
            font-size: 14px;
            color: #666;
            margin-bottom: 12px;
        }

        .document-quick-info span {
            margin-right: 16px;
        }

        .document-external-link {
            display: inline-block;
            color: #01689b;
            text-decoration: none;
            font-size: 14px;
            margin-top: 8px;
        }

        .document-external-link:hover {
            text-decoration: underline;
        }

        .document-external-link svg {
            display: inline-block;
            margin-left: 4px;
            vertical-align: middle;
        }

        .characteristics-section {
            margin-top: 24px;
        }

        .characteristics-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #333;
        }

        .characteristics-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px 32px;
        }

        .characteristic-item {
            display: flex;
            flex-direction: column;
        }

        .characteristic-label {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 4px;
        }

        .characteristic-value {
            font-size: 14px;
            color: #666;
        }

        .characteristic-value:empty::before {
            content: 'Onbekend';
            color: #999;
            font-style: italic;
        }

        .characteristics-extended {
            display: none;
        }

        .characteristics-extended.show {
            display: contents;
        }

        .show-more-button,         .show-more-button, .show-less-button {
            background-color: #e3f2fd;
            color: #01689b;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 16px;
        }

        .show-more-button:hover, .show-less-button:hover {
            background-color: #bbdefb;
        }

        .show-more-button.hidden, .show-less-button.hidden {
            display: none;
        }

        .publisher-info {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 14px;
            color: #666;
        }

        .view-toggle {
            margin: 20px 0;
            display: flex;
            gap: 12px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e0e0e0;
        }

        .view-toggle-button {
            background: none;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            color: #666;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -14px;
            position: relative;
        }

        .view-toggle-button.active {
            color: #01689b;
            border-bottom-color: #01689b;
            font-weight: 600;
        }

        .view-toggle-button:hover {
            color: #01689b;
        }

        .json-view {
            display: none;
        }

        .json-view.active {
            display: block;
        }

        .metadata-view {
            display: block;
        }

        .metadata-view.hidden {
            display: none;
        }

        .json-container {
            background-color: #fff;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
            overflow: hidden;
            margin-top: 20px;
        }

        .json-header {
            background-color: #f5f5f5;
            padding: 16px 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .json-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .json-actions {
            display: flex;
            gap: 12px;
        }

        .json-button {
            background-color: #01689b;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        .json-button:hover {
            background-color: #014d74;
        }

        .json-button.secondary {
            background-color: #666;
        }

        .json-button.secondary:hover {
            background-color: #555;
        }

        .json-content {
            padding: 20px;
            overflow-x: auto;
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.6;
            background-color: #fafafa;
        }

        .json-content pre {
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .json-key {
            color: #881391;
            font-weight: 600;
        }

        .json-string {
            color: #1a1aa6;
        }

        .json-number {
            color: #0b6e99;
        }

        .json-boolean {
            color: #0b6e99;
            font-weight: 600;
        }

        .json-null {
            color: #808080;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .document-detail {
                padding: 12px;
            }

            .characteristics-grid {
                grid-template-columns: 1fr;
            }

            .json-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .json-actions {
                width: 100%;
                flex-direction: column;
            }

            .json-button {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endpush

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'Zoekresultaten', 'href' => route('zoeken')],
        ['label' => 'Document', 'href' => null, 'current' => true],
    ];
@endphp

@section('content')
    <main class="max-w-7xl mx-auto w-full px-4 py-8">
        <div class="space-y-6">
            <a href="/zoeken{{ request()->get('from') ? '?zoeken=' . urlencode(request()->get('from')) : '' }}" 
               class="text-primary font-medium inline-flex items-center gap-2
                      hover:underline focus:outline-2 focus:outline-primary focus:outline-offset-2
                      transition-all duration-200 rounded-sm">
                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                <span>Terug naar zoekresultaten</span>
            </a>

            <div class="bg-primary-container rounded-xl p-6 border border-outline-variant">
                <h1 class="text-headline-medium font-normal mb-4 text-on-primary-container">
                    {{ $jsonData['title'] ?? 'Geen titel beschikbaar' }}
                </h1>
                <div class="flex flex-wrap gap-4 mb-4 text-body-medium text-on-primary-container/80">
                    @php
                        $metadata = $jsonData['metadata'] ?? [];
                        $documentMeta = $metadata['document'] ?? [];
                        $versies = $metadata['versies'] ?? [];
                        $firstVersion = $versies[0] ?? [];
                        $bestanden = $firstVersion['bestanden'] ?? [];
                        $firstBestand = $bestanden[0] ?? [];
                        $pageCount = $firstBestand['aantalPaginas'] ?? null;
                        $fileSize = $firstBestand['grootte'] ?? null;
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-red-50 text-red-700 border border-red-200">
                        <i class="fas fa-file-pdf text-sm" aria-hidden="true"></i>
                        <span class="font-medium">PDF</span>
                    </span>
                    @if($pageCount)
                        <span>{{ $pageCount }} pagina's</span>
                    @endif
                    @if($fileSize)
                        <span>{{ number_format($fileSize, 0, ',', '.') }} bytes</span>
                    @endif
                    @if(isset($jsonData['publication_date']))
                        <span>Gepubliceerd op: {{ \Carbon\Carbon::parse($jsonData['publication_date'])->format('d-m-Y') }}</span>
                    @endif
                    @if(isset($jsonData['updated_at']))
                        <span>Laatst gewijzigd: {{ \Carbon\Carbon::parse($jsonData['updated_at'])->format('d-m-Y') }}</span>
                    @endif
                </div>
                <div class="flex flex-wrap gap-3 mt-4">
                    @if(isset($jsonData['external_id']))
                        <a href="https://open.overheid.nl/details/{{ $jsonData['external_id'] }}" 
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                                  bg-primary text-on-primary border border-primary
                                  hover:bg-primary/90 hover:border-primary/80
                                  focus:outline-2 focus:outline-primary focus:outline-offset-2
                                  transition-all duration-200 text-sm font-medium">
                            <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                            <span>Bekijk op open.overheid.nl</span>
                        </a>
                    @endif
                    @if(isset($documentMeta['weblocatie']))
                        <a href="{{ $documentMeta['weblocatie'] }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                                  bg-surface-variant text-on-surface-variant border border-outline-variant
                                  hover:bg-surface-variant/80
                                  focus:outline-2 focus:outline-primary focus:outline-offset-2
                                  transition-all duration-200 text-sm font-medium">
                            <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                            <span>Officielebekendmakingen.nl</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- View Toggle -->
            <div class="flex gap-2 border-b-2 border-outline-variant">
                <button 
                    class="px-4 py-3 text-body-medium font-medium text-primary border-b-2 border-primary -mb-[2px]
                           focus:outline-2 focus:outline-primary focus:outline-offset-2
                           transition-colors duration-200 rounded-t-lg
                           min-h-[44px]"
                    onclick="showMetadataView()"
                    id="metadata-toggle-btn">
                    Metadata
                </button>
                <button 
                    class="px-4 py-3 text-body-medium font-medium text-on-surface-variant border-b-2 border-transparent -mb-[2px]
                           hover:text-primary focus:outline-2 focus:outline-primary focus:outline-offset-2
                           transition-colors duration-200 rounded-t-lg
                           min-h-[44px]"
                    onclick="showJSONView()"
                    id="json-toggle-btn">
                    JSON
                </button>
            </div>

            <div class="metadata-view" id="metadata-view">
                <div class="mt-6">
                    <h2 class="text-title-large font-medium mb-6 text-on-surface">Kenmerken</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            $classificatie = $documentMeta['classificatiecollectie'] ?? [];
                            $documentsoorten = $classificatie['documentsoorten'] ?? [];
                            $informatiecategorieen = $classificatie['informatiecategorieen'] ?? [];
                            $themas = $classificatie['themas'] ?? [];
                            $wooInfoCategorie = $informatiecategorieen[0]['label'] ?? null;
                            $verantwoordelijke = $documentMeta['verantwoordelijke'] ?? [];
                            $publisher = $documentMeta['publisher'] ?? [];
                            $opsteller = $documentMeta['opsteller'] ?? [];
                            $geldigheid = $documentMeta['geldigheid'] ?? [];
                            $identifiers = $documentMeta['identifiers'] ?? [];
                            $language = $documentMeta['language'] ?? [];
                            $vergaderjaar = $documentMeta['extrametadata']['vergaderjaar'] ?? null;
                            $documentsubsoort = $documentMeta['extrametadata']['documentsubsoort'] ?? null;
                        @endphp

                        <!-- Basic characteristics (always visible) -->
                        <div class="space-y-1">
                            <span class="text-label-large font-medium text-on-surface block">Documentsoort:</span>
                            <span class="text-body-medium text-on-surface-variant block">{{ $jsonData['document_type'] ?? ($documentsoorten[0]['label'] ?? 'Onbekend') }}</span>
                        </div>

                        @if($wooInfoCategorie)
                        <div class="space-y-1">
                            <span class="text-label-large font-medium text-on-surface block">Woo-Informatiecategorie:</span>
                            <span class="text-body-medium text-on-surface-variant block">{{ $wooInfoCategorie }}</span>
                        </div>
                        @endif

                        <div class="space-y-1">
                            <span class="text-label-large font-medium text-on-surface block mb-2">Publicerende organisatie:</span>
                            @php
                                // Use Eloquent model organisation first, then fallback to metadata
                                $orgName = (isset($document) && is_object($document) && $document->organisation) 
                                    ? $document->organisation 
                                    : ($jsonData['organisation'] ?? $publisher['label'] ?? null);
                            @endphp
                            @if($orgName)
                                <a href="/zoeken?organisatie[]={{ urlencode($orgName) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-full 
                                          bg-primary/10 text-primary border border-primary/20
                                          hover:bg-primary/20 hover:border-primary/30
                                          focus:outline-2 focus:outline-primary focus:outline-offset-2
                                          transition-all duration-200 font-medium text-sm
                                          group"
                                   title="Filter op {{ $orgName }}">
                                    <i class="fas fa-building text-xs" aria-hidden="true"></i>
                                    <span>{{ $orgName }}</span>
                                    <i class="fas fa-filter text-xs opacity-70 group-hover:opacity-100 transition-opacity" aria-hidden="true"></i>
                                </a>
                            @else
                                <span class="text-body-medium text-on-surface-variant block">Onbekend</span>
                            @endif
                        </div>

                        <!-- Extended characteristics (hidden by default) -->
                        <div class="characteristics-extended" id="extended-characteristics">
                            @if(isset($firstVersion['openbaarmakingsdatum']))
                            <div class="space-y-1">
                                <span class="text-label-large font-medium text-on-surface block">Gepubliceerd op:</span>
                                <span class="text-body-medium text-on-surface-variant block">
                                    {{ \Carbon\Carbon::parse($firstVersion['openbaarmakingsdatum'])->format('d-m-Y, H:i') }}
                                </span>
                            </div>
                            @endif

                            @if(isset($firstVersion['mutatiedatumtijd']))
                            <div class="space-y-1">
                                <span class="text-label-large font-medium text-on-surface block">Laatst gewijzigd:</span>
                                <span class="text-body-medium text-on-surface-variant block">
                                    {{ \Carbon\Carbon::parse($firstVersion['mutatiedatumtijd'])->format('d-m-Y, H:i') }}
                                </span>
                            </div>
                            @endif

                            @if(isset($documentMeta['creatiedatum']))
                            <div class="space-y-1">
                                <span class="text-label-large font-medium text-on-surface block">Document creatiedatum:</span>
                                <span class="text-body-medium text-on-surface-variant block">
                                    {{ \Carbon\Carbon::parse($documentMeta['creatiedatum'])->format('Y-m-d') }}
                                </span>
                            </div>
                            @endif

                            <div class="space-y-1">
                                <span class="text-label-large font-medium text-on-surface block">Verantwoordelijke:</span>
                                <span class="text-body-medium text-on-surface-variant block">{{ $verantwoordelijke['label'] ?? 'Onbekend' }}</span>
                            </div>

                            <div class="space-y-1">
                                <span class="text-label-large font-medium text-on-surface block">Thema's:</span>
                                <span class="text-body-medium text-on-surface-variant block">
                                    @if(!empty($themas))
                                        {{ implode(', ', array_column($themas, 'label')) }}
                                    @else
                                        {{ $jsonData['theme'] ?? 'Onbekend' }}
                                    @endif
                                </span>
                            </div>

                            @if(isset($geldigheid['begindatum']))
                            <div class="space-y-1">
                                <span class="text-label-large font-medium text-on-surface block">Geldig van:</span>
                                <span class="text-body-medium text-on-surface-variant block">
                                    {{ \Carbon\Carbon::parse($geldigheid['begindatum'])->format('d-m-Y, H:i') }}
                                </span>
                            </div>
                            @endif

                            <div class="space-y-1">
                                <span class="text-label-large font-medium text-on-surface block">Opsteller:</span>
                                <span class="text-body-medium text-on-surface-variant block">{{ $opsteller['label'] ?? 'Onbekend' }}</span>
                            </div>

                            @if(!empty($identifiers))
                            <div class="space-y-1">
                                <span class="text-label-large font-medium text-on-surface block">Identificatiekenmerk:</span>
                                <span class="text-body-medium text-on-surface-variant block">{{ is_array($identifiers) ? $identifiers[0] : $identifiers }}</span>
                            </div>
                            @endif

                            @if(!empty($firstBestand))
                            <div class="space-y-1">
                                <span class="text-label-large font-medium text-on-surface block">Bestandstype:</span>
                                <span class="text-body-medium text-on-surface-variant block">
                                    @php
                                        $mimeType = $firstBestand['mime-type'] ?? 'application/pdf';
                                        if (str_contains($mimeType, 'pdf')) {
                                            echo 'PDF';
                                        } else {
                                            echo strtoupper($mimeType);
                                        }
                                    @endphp
                                </span>
                            </div>
                            @endif

                            @if(!empty($language))
                            <div class="space-y-1">
                                <span class="text-label-large font-medium text-on-surface block">Taal:</span>
                                <span class="text-body-medium text-on-surface-variant block">{{ $language['label'] ?? 'Nederlands' }}</span>
                            </div>
                            @endif

                            @if($vergaderjaar)
                            <div class="space-y-1">
                                <span class="text-label-large font-medium text-on-surface block">vergaderjaar:</span>
                                <span class="text-body-medium text-on-surface-variant block">{{ $vergaderjaar }}</span>
                            </div>
                            @endif

                            @if($documentsubsoort)
                            <div class="space-y-1">
                                <span class="text-label-large font-medium text-on-surface block">documentsubsoort:</span>
                                <span class="text-body-medium text-on-surface-variant block">{{ $documentsubsoort }}</span>
                            </div>
                            @endif

                            @if(isset($jsonData['category']))
                            <div class="space-y-1">
                                <span class="text-label-large font-medium text-on-surface block">Informatiecategorie:</span>
                                <span class="text-body-medium text-on-surface-variant block">{{ $jsonData['category'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <button 
                        class="mt-6 bg-primary-container text-on-primary-container 
                               hover:bg-primary-container/80
                               focus:outline-2 focus:outline-primary focus:outline-offset-2
                               px-6 py-3 rounded-full font-medium
                               transition-colors duration-200
                               min-h-[44px]"
                        id="show-more-btn" 
                        onclick="toggleCharacteristics()">
                        Toon alle kenmerken
                    </button>
                    <button 
                        class="mt-6 bg-primary-container text-on-primary-container hidden
                               hover:bg-primary-container/80
                               focus:outline-2 focus:outline-primary focus:outline-offset-2
                               px-6 py-3 rounded-full font-medium
                               transition-colors duration-200
                               min-h-[44px]"
                        id="show-less-btn" 
                        onclick="toggleCharacteristics()">
                        Toon minder kenmerken
                    </button>
                </div>

                @if(isset($dossierMembers) && $dossierMembers->isNotEmpty())
                <div class="mt-8 pt-6 border-t border-outline-variant">
                    <h2 class="text-title-large font-medium mb-4 text-on-surface">Dossier</h2>
                    <p class="text-body-medium text-on-surface-variant mb-4">
                        Dit document maakt deel uit van een dossier met {{ $dossierMembers->count() }} gerelateerd{{ $dossierMembers->count() !== 1 ? 'e' : '' }} document{{ $dossierMembers->count() !== 1 ? 'en' : '' }}:
                    </p>
                    <div class="space-y-3">
                        @foreach($dossierMembers as $member)
                            <a href="/document/{{ $member->external_id }}{{ request()->get('from') ? '?from=' . urlencode(request()->get('from')) : '' }}" 
                               class="block p-4 rounded-lg border border-outline-variant
                                      bg-surface hover:bg-surface-variant
                                      focus:outline-2 focus:outline-primary focus:outline-offset-2
                                      transition-all duration-200
                                      group">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-body-large font-medium text-on-surface group-hover:text-primary transition-colors mb-2 line-clamp-2">
                                            {{ $member->title ?? 'Geen titel beschikbaar' }}
                                        </h3>
                                        <div class="flex flex-wrap gap-3 text-body-small text-on-surface-variant">
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
                                            @if($member->organisation)
                                                <span class="inline-flex items-center gap-1.5">
                                                    <i class="fas fa-building text-xs" aria-hidden="true"></i>
                                                    <span>{{ $member->organisation }}</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-on-surface-variant group-hover:text-primary transition-colors mt-1 flex-shrink-0" aria-hidden="true"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                @php
                    $publisherOrg = $document->organisation ?? $publisher['label'] ?? null;
                @endphp
                @if($publisherOrg)
                <div class="mt-8 pt-6 border-t border-outline-variant">
                    <p class="text-body-medium text-on-surface-variant mb-3">
                        Dit document is gepubliceerd door:
                    </p>
                    <a href="/zoeken?organisatie[]={{ urlencode($publisherOrg) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-full 
                              bg-primary/10 text-primary border border-primary/20
                              hover:bg-primary/20 hover:border-primary/30
                              focus:outline-2 focus:outline-primary focus:outline-offset-2
                              transition-all duration-200 font-medium text-sm
                              group">
                        <i class="fas fa-building text-xs" aria-hidden="true"></i>
                        <span>{{ $publisherOrg }}</span>
                        <i class="fas fa-filter text-xs opacity-70 group-hover:opacity-100 transition-opacity" aria-hidden="true"></i>
                    </a>
                </div>
                @endif
            </div>

            <div class="json-view hidden" id="json-view">
                <div class="bg-surface rounded-xl border border-outline-variant mt-6 overflow-hidden">
                    <div class="bg-surface-variant px-6 py-4 border-b border-outline-variant flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h2 class="text-title-large font-medium text-on-surface">JSON Data</h2>
                        <div class="flex flex-wrap gap-3">
                            <button 
                                class="bg-secondary-container text-on-secondary-container
                                       hover:bg-secondary-container/80
                                       focus:outline-2 focus:outline-secondary focus:outline-offset-2
                                       px-4 py-2 rounded-full font-medium
                                       transition-colors duration-200
                                       min-h-[44px]"
                                onclick="copyJSON()">
                                Kopieer JSON
                            </button>
                            <a href="{{ url()->current() }}?format=json" 
                               target="_blank"
                               rel="noopener noreferrer"
                               class="bg-secondary-container text-on-secondary-container
                                      hover:bg-secondary-container/80
                                      focus:outline-2 focus:outline-secondary focus:outline-offset-2
                                      px-4 py-2 rounded-full font-medium
                                      transition-colors duration-200
                                      min-h-[44px] inline-flex items-center justify-center">
                                Download JSON
                            </a>
                            <a href="{{ url()->current() }}?format=xml" 
                               class="bg-secondary-container text-on-secondary-container
                                      hover:bg-secondary-container/80
                                      focus:outline-2 focus:outline-secondary focus:outline-offset-2
                                      px-4 py-2 rounded-full font-medium
                                      transition-colors duration-200
                                      min-h-[44px] inline-flex items-center justify-center">
                                Download XML
                            </a>
                        </div>
                    </div>
                    <div class="p-6 overflow-x-auto bg-surface-variant">
                        <pre id="json-display" class="font-mono text-body-small text-on-surface-variant"></pre>
                    </div>
                </div>
            </div>
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
                // Escape HTML and preserve special characters
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

        function showMetadataView() {
            const metadataView = document.getElementById('metadata-view');
            const jsonView = document.getElementById('json-view');
            const metadataBtn = document.getElementById('metadata-toggle-btn');
            const jsonBtn = document.getElementById('json-toggle-btn');
            
            if (!metadataView || !jsonView || !metadataBtn || !jsonBtn) {
                console.error('Elements not found for showMetadataView');
                return;
            }
            
            // Show metadata view, hide JSON view
            metadataView.classList.remove('hidden');
            jsonView.classList.remove('active');
            jsonView.classList.add('hidden');
            
            // Update button styles
            metadataBtn.classList.remove('text-on-surface-variant', 'border-transparent');
            metadataBtn.classList.add('text-primary', 'border-primary');
            jsonBtn.classList.remove('text-primary', 'border-primary');
            jsonBtn.classList.add('text-on-surface-variant', 'border-transparent');
        }

        function showJSONView() {
            const metadataView = document.getElementById('metadata-view');
            const jsonView = document.getElementById('json-view');
            const metadataBtn = document.getElementById('metadata-toggle-btn');
            const jsonBtn = document.getElementById('json-toggle-btn');
            
            if (!metadataView || !jsonView || !metadataBtn || !jsonBtn) {
                console.error('Elements not found for showJSONView');
                return;
            }
            
            // Show JSON view, hide metadata view
            metadataView.classList.add('hidden');
            jsonView.classList.remove('hidden');
            jsonView.classList.add('active');
            
            // Update button styles
            jsonBtn.classList.remove('text-on-surface-variant', 'border-transparent');
            jsonBtn.classList.add('text-primary', 'border-primary');
            metadataBtn.classList.remove('text-primary', 'border-primary');
            metadataBtn.classList.add('text-on-surface-variant', 'border-transparent');
            
            // Initialize JSON display if not already done
            const display = document.getElementById('json-display');
            if (display && (!display.innerHTML || display.innerHTML.trim() === '')) {
                try {
                    const jsonData = @json($jsonData);
                    display.innerHTML = formatJSONValue(jsonData);
                } catch (error) {
                    console.error('Error formatting JSON:', error);
                    display.innerHTML = '<span class="text-error">Error loading JSON data</span>';
                }
            }
        }

        function toggleCharacteristics() {
            const extended = document.getElementById('extended-characteristics');
            const showMoreBtn = document.getElementById('show-more-btn');
            const showLessBtn = document.getElementById('show-less-btn');
            
            if (!extended || !showMoreBtn || !showLessBtn) {
                console.error('Elements not found for toggleCharacteristics');
                return;
            }
            
            if (extended.classList.contains('show')) {
                // Hide extended characteristics
                extended.classList.remove('show');
                showMoreBtn.classList.remove('hidden');
                showLessBtn.classList.add('hidden');
            } else {
                // Show extended characteristics
                extended.classList.add('show');
                showMoreBtn.classList.add('hidden');
                showLessBtn.classList.remove('hidden');
            }
        }

        // Copy JSON to clipboard
        function copyJSON() {
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

        // Initialize JSON display on page load (for download)
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
