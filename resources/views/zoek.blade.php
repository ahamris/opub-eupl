@extends('layouts.app')

@section('title', 'Zoek overheidsdocumenten - Open Overheid')

@section('content')
    <!-- Info Banner -->
    <div class="bg-primary-container border-b border-outline-variant" role="alert">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-3">
            <p class="text-body-medium text-on-primary-container">
                Welkom op het opensource Woo-voorziening
            </p>
        </div>
    </div>
    
    <!-- Hero Section with Search - Only on Homepage -->
    @if(request()->routeIs('home'))
    <x-hero-split-search 
        badge="Open source Woo-voorziening"
        badgeText="Volledig operationeel"
        title="OpenPublicaties: Open Source Woo-Voorziening"
        :description="'Een volledig open source, lichtgewicht en state-of-the-art Woo-voorziening die actief openbaar maken eenvoudig, betrouwbaar en duurzaam ondersteunt.'"
        :documentCount="$documentCount"
    />
    @endif
    
    <!-- Statistics Section -->
    @if(isset($statistics) && !empty($statistics))
    <div style="position: relative; z-index: 0;">
        <x-statistics-section :statistics="$statistics" />
    </div>
    @endif

    <!-- Content Section -->
    <div class="mx-auto max-w-7xl px-6 lg:px-8 pb-24">
        <!-- Recent Documents -->
        @if(isset($recentDocuments) && count($recentDocuments) > 0)
        <section class="mb-12">
            <h2 class="text-title-large font-medium text-on-surface mb-6">
                Recent toegevoegde documenten
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recentDocuments as $doc)
                <article class="oo-result-card group">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                            <i class="fas fa-file-alt text-primary" aria-hidden="true"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="oo-result-title mb-2">
                                <a href="/open-overheid/documents/{{ $doc->external_id }}" 
                                   class="text-primary hover:text-primary-dark">
                                    {{ $doc->title ?? 'Geen titel' }}
                                </a>
                            </h3>
                            @if($doc->publication_date)
                            <div class="oo-result-meta">
                                <span class="inline-flex items-center gap-1.5">
                                    <i class="fas fa-calendar text-xs" aria-hidden="true"></i>
                                    {{ $doc->publication_date->format('d-m-Y') }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @if($doc->description)
                    <p class="oo-result-description line-clamp-3 text-sm">
                        {{ \Illuminate\Support\Str::limit($doc->description, 120) }}
                    </p>
                    @endif
                    <a href="/open-overheid/documents/{{ $doc->external_id }}" 
                       class="mt-4 inline-flex items-center gap-2 text-primary font-semibold text-sm
                              hover:text-primary-dark transition-colors duration-200">
                        Bekijk document
                        <i class="fas fa-arrow-right text-xs transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true"></i>
                    </a>
                </article>
                @endforeach
            </div>
        </section>
        @endif
    </div>
@endsection
