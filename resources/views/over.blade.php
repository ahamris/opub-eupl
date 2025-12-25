@extends('layouts.app')

@section('title', ($aboutSettings->page_title ?? 'Over OpenPublicaties') . ' - Open Source Woo-Voorziening')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => $aboutSettings->page_title ?? 'Over OpenPublicaties', 'href' => null, 'current' => true],
    ];
@endphp

@section('content')
    <!-- Premium Page Header Section -->
    <div class="relative bg-gradient-to-b from-slate-50 to-white overflow-hidden">
        <!-- Subtle grid pattern -->
        <div class="absolute inset-0 -z-10">
            <svg class="absolute inset-0 h-full w-full stroke-slate-200/50" fill="none">
                <defs>
                    <pattern id="over-header-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M.5 40V.5H40" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#over-header-grid)" />
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
                <p class="text-sm font-medium uppercase">{{ $aboutSettings->page_eyebrow ?? 'Open source Woo-voorziening' }}</p>
                <h1 class="mt-2 font-semibold">{{ $aboutSettings->page_title ?? 'Over OpenPublicaties' }}</h1>
                <p class="mt-4 text-base text-[var(--color-on-surface-variant)] leading-relaxed">
                    {{ $aboutSettings->page_description ?? 'Een volledig open source, lichtgewicht en state-of-the-art Woo-voorziening die actieve openbaarmaking eenvoudig, betrouwbaar en duurzaam ondersteunt.' }}
                </p>
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
        <div class="text-base/7 text-[var(--color-on-surface-variant)]">
            <div class="space-y-12">
                <!-- Introduction -->
                @if($aboutSettings->intro_content)
                <div>
                    <p class="text-base/7 text-[var(--color-on-surface-variant)]">
                        {{ $aboutSettings->intro_content }}
                    </p>
                </div>
                @endif
                
                <!-- Section 1: Projectdoelstelling -->
                @if($aboutSettings->section1_is_active)
                <div>
                    <h2 class="text-[var(--font-size-headline-medium)] font-bold tracking-tight !text-[var(--color-primary-dark)] mb-4">
                        {{ $aboutSettings->section1_title }}
                    </h2>
                    <div class="text-base/7 text-[var(--color-on-surface-variant)] prose prose-slate max-w-none">
                        {!! $aboutSettings->section1_content !!}
                    </div>
                </div>
                @endif

                <!-- Section 2: Technische Realisatie -->
                @if($aboutSettings->section2_is_active)
                <div>
                    <h2 class="text-[var(--font-size-headline-medium)] font-bold tracking-tight !text-[var(--color-primary-dark)] mb-4">
                        {{ $aboutSettings->section2_title }}
                    </h2>
                    @if($aboutSettings->section2_intro)
                    <p class="text-base/7 text-[var(--color-on-surface-variant)] mb-4">
                        {{ $aboutSettings->section2_intro }}
                    </p>
                    @endif
                    @if($aboutSettings->section2_features && count($aboutSettings->section2_features) > 0)
                    <ul role="list" class="mt-4 space-y-3 text-base/7 text-[var(--color-on-surface-variant)]">
                        @foreach($aboutSettings->section2_features as $feature)
                        <li class="flex gap-x-3">
                            <i class="fas fa-check-circle text-[var(--color-primary)] mt-1 flex-none" aria-hidden="true"></i>
                            <span><strong class="font-semibold text-[var(--color-on-surface)]">{{ $feature['title'] }}</strong> {{ $feature['description'] }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    @if($aboutSettings->section2_outro)
                    <p class="text-base/7 text-[var(--color-on-surface-variant)] mt-4">
                        {{ $aboutSettings->section2_outro }}
                    </p>
                    @endif
                </div>
                @endif
                
                <!-- Section 3: Kernwaarden -->
                @if($aboutSettings->section3_is_active && $aboutSettings->section3_values && count($aboutSettings->section3_values) > 0)
                <div>
                    <h2 class="text-[var(--font-size-headline-medium)] font-bold tracking-tight !text-[var(--color-primary-dark)] mb-4">
                        {{ $aboutSettings->section3_title }}
                    </h2>
                    <ul role="list" class="mt-4 space-y-6 text-base/7 text-[var(--color-on-surface-variant)]">
                        @foreach($aboutSettings->section3_values as $value)
                        <li class="flex gap-x-3">
                            <i class="{{ $value['icon'] ?? 'fas fa-star' }} text-[var(--color-primary)] mt-1 size-5 flex-none" aria-hidden="true"></i>
                            <span>
                                <strong class="font-semibold text-[var(--color-on-surface)]">{{ $value['title'] }}</strong>
                                {{ $value['description'] }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Section 4: Van proof-of-concept -->
                @if($aboutSettings->section4_is_active)
                <div>
                    <h2 class="text-[var(--font-size-headline-medium)] font-bold tracking-tight !text-[var(--color-primary-dark)] mb-4">
                        {{ $aboutSettings->section4_title }}
                    </h2>
                    <div class="text-base/7 text-[var(--color-on-surface-variant)] prose prose-slate max-w-none">
                        {!! $aboutSettings->section4_content !!}
                    </div>
                </div>
                @endif
                
                <!-- Section 5: Bijdrage aan Woo -->
                @if($aboutSettings->section5_is_active)
                <div>
                    <h2 class="text-[var(--font-size-headline-medium)] font-bold tracking-tight !text-[var(--color-primary-dark)] mb-4">
                        {{ $aboutSettings->section5_title }}
                    </h2>
                    <p class="text-base/7 text-[var(--color-on-surface-variant)]">
                        {{ $aboutSettings->section5_content }}
                    </p>
                </div>
                @endif

                <!-- Contact Section -->
                @if($aboutSettings->contact_is_active)
                <div class="flex items-start gap-4 pt-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-md bg-[var(--color-primary)]/10 flex items-center justify-center shrink-0">
                            <i class="fas fa-question-circle text-lg text-[var(--color-primary)]" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-[var(--font-size-headline-medium)] font-bold tracking-tight !text-[var(--color-primary-dark)] mb-4">
                            {{ $aboutSettings->contact_title }}
                        </h2>
                        <p class="text-base/7 text-[var(--color-on-surface-variant)] mb-4">
                            {{ $aboutSettings->contact_content }}
                        </p>
                        @if($aboutSettings->contact_link_url)
                        <a href="{{ $aboutSettings->contact_link_url }}" class="inline-flex items-center gap-2 text-[var(--color-primary)] font-medium text-base hover:text-[var(--color-primary-dark)] focus:outline-none transition-colors duration-200">
                            {{ $aboutSettings->contact_link_text }}
                            <i class="fas fa-chevron-right text-sm" aria-hidden="true"></i>
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>
@endsection
