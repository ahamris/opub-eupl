@extends('layouts.app')

@section('title', 'Verwijzingen - Open Overheid')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'Verwijzingen', 'href' => null, 'current' => true],
    ];
@endphp

@section('content')
    <!-- Header Section -->
    <div class="bg-[var(--color-primary-light)] py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <!-- Breadcrumb -->
            @if(!empty($breadcrumbs ?? []))
            <div class="mb-8">
                <x-breadcrumbs :items="$breadcrumbs" />
            </div>
            @endif
            
            <div class="mx-auto max-w-2xl lg:mx-0">
                <p class="text-base/7 font-semibold text-[var(--color-primary)]">Gerelateerde links en bronnen</p>
                <h1 class="mt-2 text-4xl font-semibold tracking-tight text-[var(--color-on-surface)] sm:text-5xl">
                    Verwijzingen
                </h1>
                <p class="mt-6 text-lg font-medium text-pretty text-[var(--color-on-surface-variant)] sm:text-xl/8">
                    Links naar andere relevante portalen en websites voor overheidsinformatie.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-6 lg:px-8 pt-10 pb-20">
        <div class="mx-auto max-w-2xl lg:max-w-none">
            <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-12 lg:max-w-none lg:grid-cols-3">
                <div class="flex flex-col">
                    <dt class="text-base font-semibold leading-7 text-[var(--color-on-surface)]">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-md bg-[var(--color-primary-light)]">
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
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-md bg-[var(--color-primary-light)]">
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
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-md bg-[var(--color-primary-light)]">
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

