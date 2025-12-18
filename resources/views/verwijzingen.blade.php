@extends('layouts.app')

@section('title', 'Verwijzingen - Open Overheid')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'Verwijzingen', 'href' => null, 'current' => true],
    ];
@endphp

@section('content')
    <!-- Premium Page Header Section -->
    <div class="relative bg-gradient-to-b from-slate-50 to-white overflow-hidden">
        <!-- Subtle grid pattern -->
        <div class="absolute inset-0 -z-10">
            <svg class="absolute inset-0 h-full w-full stroke-slate-200/50" fill="none">
                <defs>
                    <pattern id="verwijzingen-header-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M.5 40V.5H40" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#verwijzingen-header-grid)" />
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
                <p class="text-sm font-medium uppercase">Gerelateerde links en bronnen</p>
                <h1 class="mt-2 font-semibold">Verwijzingen</h1>
                <p class="mt-4 text-base text-[var(--color-on-surface-variant)] leading-relaxed">
                    Links naar andere relevante portalen en websites voor overheidsinformatie.
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
        <div class="mx-auto max-w-2xl lg:max-w-none">
            <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-12 lg:max-w-none lg:grid-cols-3">
                <div class="flex flex-col">
                    <dt class="text-base font-semibold leading-7 text-[var(--color-on-surface)]">
                        <div class="mb-4 inline-flex items-center justify-center w-10 h-10 rounded-md bg-[var(--color-primary)]/10">
                            <i class="fas fa-gavel text-lg text-[var(--color-primary)]" aria-hidden="true"></i>
                        </div>
                        Wet- en regelgeving
                    </dt>
                    <dd class="mt-1 flex flex-auto flex-col text-sm leading-6 text-[var(--color-on-surface-variant)]">
                        <p class="flex-auto">
                            Officiële publicaties van wet- en regelgeving van de Nederlandse overheid.
                        </p>
                        <p class="mt-4">
                            <a href="https://wetten.overheid.nl" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold leading-6 text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] focus:outline-none transition-colors duration-200 inline-flex items-center gap-1.5">
                                wetten.nl
                                <i class="fas fa-external-link-alt text-xs" aria-hidden="true"></i>
                            </a>
                        </p>
                    </dd>
                </div>
                <div class="flex flex-col">
                    <dt class="text-base font-semibold leading-7 text-[var(--color-on-surface)]">
                        <div class="mb-4 inline-flex items-center justify-center w-10 h-10 rounded-md bg-[var(--color-primary)]/10">
                            <i class="fas fa-search text-lg text-[var(--color-primary)]" aria-hidden="true"></i>
                        </div>
                        Woo-index
                    </dt>
                    <dd class="mt-1 flex flex-auto flex-col text-sm leading-6 text-[var(--color-on-surface-variant)]">
                        <p class="flex-auto">
                            Vind contactgegevens van bestuursorganen voor het indienen van Woo-verzoeken.
                        </p>
                        <p class="mt-4">
                            <a href="https://www.woo-index.nl" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold leading-6 text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] focus:outline-none transition-colors duration-200 inline-flex items-center gap-1.5">
                                woo-index.nl
                                <i class="fas fa-external-link-alt text-xs" aria-hidden="true"></i>
                            </a>
                        </p>
                    </dd>
                </div>
                <div class="flex flex-col">
                    <dt class="text-base font-semibold leading-7 text-[var(--color-on-surface)]">
                        <div class="mb-4 inline-flex items-center justify-center w-10 h-10 rounded-md bg-[var(--color-primary)]/10">
                            <i class="fas fa-building text-lg text-[var(--color-primary)]" aria-hidden="true"></i>
                        </div>
                        Overheid.nl
                    </dt>
                    <dd class="mt-1 flex flex-auto flex-col text-sm leading-6 text-[var(--color-on-surface-variant)]">
                        <p class="flex-auto">
                            Centrale toegangspoort tot alle informatie van de Nederlandse overheid.
                        </p>
                        <p class="mt-4">
                            <a href="https://www.overheid.nl" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold leading-6 text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] focus:outline-none transition-colors duration-200 inline-flex items-center gap-1.5">
                                overheid.nl
                                <i class="fas fa-external-link-alt text-xs" aria-hidden="true"></i>
                            </a>
                        </p>
                    </dd>
                </div>
            </dl>
        </div>
    </main>
@endsection

