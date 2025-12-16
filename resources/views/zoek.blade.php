@extends('layouts.app')

@section('title', 'Zoek overheidsdocumenten - Open Overheid' . (isset($documentCount) ? ' (' . number_format($documentCount, 0, ',', '.') . ' documenten)' : ''))

@php
    // No breadcrumbs on homepage
    $breadcrumbs = [];
@endphp

@section('content')
    <!-- Minimalistic Info Banner -->
    <div class="bg-[var(--color-primary-light)] border-b border-[var(--color-primary)]/20" role="alert">
        <div class="mx-auto max-w-7xl px-6 lg:px-8 py-4">
            <p class="text-sm font-medium text-[var(--color-on-surface-variant)]">
                Welkom bij de open source Woo-voorziening. Vind en bekijk overheidsdocumenten eenvoudig en snel.
            </p>
        </div>
    </div>
    
    <!-- Header Section - Only on Zoeken Page -->
    @if(request()->routeIs('zoeken'))
    <div class="bg-[var(--color-surface)] py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:mx-0">
                <p class="text-base/7 font-semibold text-[var(--color-primary)]">Zoek in overheidsdocumenten</p>
                <h1 class="mt-2 text-4xl font-semibold tracking-tight text-[var(--color-on-surface)] sm:text-5xl">Uitgebreid zoeken</h1>
                <p class="mt-6 text-lg font-medium text-pretty text-[var(--color-on-surface-variant)] sm:text-xl/8">
                    Gebruik filters op datum, organisatie, categorie of thema om precies te vinden wat je zoekt. Verfijn je zoekopdracht voor de beste resultaten.
                </p>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Hero Section with Search - Only on Homepage -->
    @if(request()->routeIs('home'))
    <x-hero-split-search 
        badge="Open source Woo-voorziening"
        badgeText="Volledig operationeel"
        title="OpenPublicaties: Open Source Woo-Voorziening"
        :description="'Vind en bekijk alle actief openbaar gemaakte overheidsdocumenten. Eenvoudig, betrouwbaar en volledig transparant.'"
        :documentCount="$documentCount"
    />
    @endif
    
    <!-- Quick Actions Section - Bento Grid - Only on Homepage -->
    @if(request()->routeIs('home'))
    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
            <h2 class="text-base/7 font-semibold text-[var(--color-primary)]">Snel aan de slag</h2>
            <p class="mt-2 max-w-lg text-4xl font-semibold tracking-tight text-pretty text-[var(--color-on-surface)] sm:text-4xl">
                Alles wat je nodig hebt
            </p>
            <div class="mt-10 grid grid-cols-1 gap-4 sm:mt-16 lg:grid-cols-6 lg:grid-rows-2">
                <!-- Thema's - 4 columns -->
                <a href="{{ route('themas.index') }}" class="flex p-px lg:col-span-4">
                    <div class="w-full overflow-hidden rounded-lg bg-[var(--color-surface)] border border-[var(--color-outline-variant)] max-lg:rounded-t-2xl lg:rounded-tl-2xl">
                        <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?q=80&w=2070&auto=format&fit=crop" alt="Thema's" class="w-full h-80 object-cover object-left" />
                        <div class="p-10">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface-variant)]">Thema's</h3>
                            <p class="mt-2 text-lg font-medium tracking-tight text-[var(--color-on-surface)]">Zoek op onderwerp</p>
                            <p class="mt-2 max-w-lg text-sm text-[var(--color-on-surface-variant)]">
                                Zoek documenten op onderwerp zoals ruimtelijke ordening, onderwijs of zorg. Verken alle thema's en vind gerelateerde documenten.
                            </p>
                        </div>
                    </div>
                </a>
                
                <!-- Dossiers - 2 columns -->
                <a href="{{ route('dossiers.index') }}" class="flex p-px lg:col-span-2">
                    <div class="w-full overflow-hidden rounded-lg bg-[var(--color-surface)] border border-[var(--color-outline-variant)] lg:rounded-tr-2xl">
                        <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2072&auto=format&fit=crop" alt="Dossiers" class="w-full h-80 object-cover" />
                        <div class="p-10">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface-variant)]">Dossiers</h3>
                            <p class="mt-2 text-lg font-medium tracking-tight text-[var(--color-on-surface)]">Verken complete dossiers</p>
                            <p class="mt-2 max-w-lg text-sm text-[var(--color-on-surface-variant)]">
                                Verken complete dossiers met alle bijbehorende documenten en verbanden.
                            </p>
                        </div>
                    </div>
                </a>
                
                <!-- Uitgebreid zoeken - 2 columns -->
                <a href="{{ route('zoeken') }}" class="flex p-px lg:col-span-2">
                    <div class="w-full overflow-hidden rounded-lg bg-[var(--color-surface)] border border-[var(--color-outline-variant)] lg:rounded-bl-2xl">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2070&auto=format&fit=crop" alt="Uitgebreid zoeken" class="w-full h-80 object-cover" />
                        <div class="p-10">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface-variant)]">Uitgebreid zoeken</h3>
                            <p class="mt-2 text-lg font-medium tracking-tight text-[var(--color-on-surface)]">Filters en precieze resultaten</p>
                            <p class="mt-2 max-w-lg text-sm text-[var(--color-on-surface-variant)]">
                                Gebruik filters op datum, organisatie of categorie voor precieze zoekresultaten.
                            </p>
                        </div>
                    </div>
                </a>
                
                <!-- Verwijzingen - 4 columns -->
                <a href="{{ route('verwijzingen') }}" class="flex p-px lg:col-span-4">
                    <div class="w-full overflow-hidden rounded-lg bg-[var(--color-surface)] border border-[var(--color-outline-variant)] max-lg:rounded-b-2xl lg:rounded-br-2xl">
                        <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=2084&auto=format&fit=crop" alt="Verwijzingen" class="w-full h-80 object-cover object-left" />
                        <div class="p-10">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface-variant)]">Verwijzingen</h3>
                            <p class="mt-2 text-lg font-medium tracking-tight text-[var(--color-on-surface)]">Gerelateerde links en bronnen</p>
                            <p class="mt-2 max-w-lg text-sm text-[var(--color-on-surface-variant)]">
                                Vind handige links naar gerelateerde websites en informatiebronnen voor verdere verkenning.
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Kennisbank Section - Blog Grid -->
    @if(request()->routeIs('home'))
    <div class="bg-[var(--color-surface)] py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="max-w-2xl">
                <h2 class="text-4xl font-semibold tracking-tight text-balance text-[var(--color-on-surface)] sm:text-4xl">Kennisbank</h2>
                <p class="mt-2 text-base text-[var(--color-on-surface-variant)]">Leer meer over open data, transparantie en hoe je het platform gebruikt</p>
            </div>
            <x-knowledge-base-grid />
        </div>
    </div>
    @endif

    <!-- Testimonials Section -->
    @if(request()->routeIs('home'))
    <x-testimonials-grid />
    @endif

    <!-- Newsletter Section - Stay Updated -->
    @if(request()->routeIs('home'))
    <div class="relative isolate overflow-hidden bg-[var(--color-surface)] pt-20 pb-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-2">
                <div class="max-w-xl lg:max-w-lg">
                    <h2 class="text-2xl font-semibold tracking-tight text-[var(--color-on-surface)]">
                        Blijf op de hoogte
                    </h2>
                    <p class="mt-4 text-base text-[var(--color-on-surface-variant)] leading-[var(--line-height-relaxed)]">
                        Schrijf je in voor updates en ontvang het laatste nieuws over nieuwe documenten, platformupdates en ontwikkelingen binnen open overheid.
                    </p>
                    <form action="#" method="POST" class="mt-6 flex flex-col sm:flex-row w-full gap-4">
                        @csrf
                        <div class="flex-1 min-w-0">
                            <x-input 
                                type="email"
                                name="email"
                                id="email-address"
                                placeholder="Vul je e-mailadres in"
                                autocomplete="email"
                                required
                                class="w-full"
                            />
                        </div>
                        <x-primary-button class="flex-shrink-0 rounded-md px-3.5 py-2.5 text-sm font-semibold whitespace-nowrap w-full sm:w-auto">
                            Inschrijven
                        </x-primary-button>
                    </form>
                </div>
                <dl class="grid grid-cols-1 gap-x-8 gap-y-10 sm:grid-cols-2 lg:pt-2">
                    <div class="flex flex-col items-start">
                        <div class="rounded-md bg-[var(--color-primary)]/10 p-2 border border-[var(--color-outline-variant)]">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 text-[var(--color-primary)]">
                                <path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <dt class="mt-4 text-base font-semibold text-[var(--color-on-surface)]">
                            Regelmatige updates
                        </dt>
                        <dd class="mt-2 text-sm/7 text-[var(--color-on-surface-variant)] leading-[var(--line-height-normal)]">
                            Ontvang wekelijks een overzicht van nieuwe documenten en belangrijke ontwikkelingen binnen het platform.
                        </dd>
                    </div>
                    <div class="flex flex-col items-start">
                        <div class="rounded-md bg-[var(--color-primary)]/10 p-2 border border-[var(--color-outline-variant)]">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 text-[var(--color-primary)]">
                                <path d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <dt class="mt-4 text-base font-semibold text-[var(--color-on-surface)]">
                            Geen spam
                        </dt>
                        <dd class="mt-2 text-sm/7 text-[var(--color-on-surface-variant)] leading-[var(--line-height-normal)]">
                            Wij respecteren je privacy. Alleen relevante updates en nieuws over het platform. Je kunt je altijd uitschrijven.
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        <div aria-hidden="true" class="absolute top-0 left-1/2 -z-10 -translate-x-1/2 blur-3xl xl:-top-6">
            <div class="aspect-[1155/678] w-[288.75px]"></div>
        </div>
    </div>
    @endif
@endsection
