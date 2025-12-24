@extends('layouts.app')

@section('title', $page->og_title ?? $page->title)

@section('meta_description', $page->meta_description ?? '')

@push('styles')
    {{-- SEO Meta Tags --}}
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif
    @if($page->meta_robots)
        <meta name="robots" content="{{ $page->meta_robots }}">
    @endif
    @if($page->canonical_url)
        <link rel="canonical" href="{{ $page->canonical_url }}">
    @endif
    
    {{-- Open Graph Meta Tags --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $page->og_title ?? $page->title }}">
    <meta property="og:description" content="{{ $page->og_description ?? $page->meta_description ?? $page->short_description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($page->og_image)
        <meta property="og:image" content="{{ asset('storage/' . $page->og_image) }}">
    @endif
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $page->og_title ?? $page->title }}">
    <meta name="twitter:description" content="{{ $page->og_description ?? $page->meta_description ?? $page->short_description }}">
    @if($page->og_image)
        <meta name="twitter:image" content="{{ asset('storage/' . $page->og_image) }}">
    @endif
@endpush

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => $page->title, 'href' => null, 'current' => true],
    ];
    
    // Button style classes
    $buttonStyles = [
        'primary' => 'bg-[var(--color-primary)] text-white hover:bg-[var(--color-primary-dark)]',
        'secondary' => 'bg-zinc-100 text-zinc-800 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700',
        'outline' => 'border border-[var(--color-primary)] text-[var(--color-primary)] hover:bg-[var(--color-primary)]/10',
    ];
@endphp

@section('content')
    <!-- Premium Page Header Section -->
    <div class="relative bg-gradient-to-b from-slate-50 to-white overflow-hidden">
        <!-- Subtle grid pattern -->
        <div class="absolute inset-0 -z-10">
            <svg class="absolute inset-0 h-full w-full stroke-slate-200/50" fill="none">
                <defs>
                    <pattern id="page-header-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M.5 40V.5H40" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#page-header-grid)" />
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
                @if($page->subtitle)
                    <p class="text-sm font-medium uppercase">{{ $page->subtitle }}</p>
                @endif
                <h1 class="mt-2 font-semibold">{{ $page->title }}</h1>
                @if($page->short_description)
                    <p class="mt-4 text-base text-[var(--color-on-surface-variant)] leading-relaxed">
                        {{ $page->short_description }}
                    </p>
                @endif
                
                <!-- Optional Buttons -->
                @if($page->button_1_text || $page->button_2_text)
                <div class="mt-6 flex flex-wrap items-center gap-4">
                    @if($page->button_1_text && $page->button_1_url)
                        <a 
                            href="{{ $page->button_1_url }}" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-medium transition-colors duration-200 {{ $buttonStyles[$page->button_1_style ?? 'primary'] }}"
                            @if($page->button_1_new_tab) target="_blank" rel="noopener noreferrer" @endif
                        >
                            {{ $page->button_1_text }}
                            @if($page->button_1_icon)
                                <i class="fas fa-{{ $page->button_1_icon }} text-sm" aria-hidden="true"></i>
                            @endif
                        </a>
                    @endif
                    @if($page->button_2_text && $page->button_2_url)
                        <a 
                            href="{{ $page->button_2_url }}" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-medium transition-colors duration-200 {{ $buttonStyles[$page->button_2_style ?? 'secondary'] }}"
                            @if($page->button_2_new_tab) target="_blank" rel="noopener noreferrer" @endif
                        >
                            {{ $page->button_2_text }}
                            @if($page->button_2_icon)
                                <i class="fas fa-{{ $page->button_2_icon }} text-sm" aria-hidden="true"></i>
                            @endif
                        </a>
                    @endif
                </div>
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
        <div class="prose prose-lg dark:prose-invert max-w-none">
            {!! $page->content !!}
        </div>
    </main>
@endsection
