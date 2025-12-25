@extends('layouts.app')

@section('title', 'Zoek overheidsdocumenten - Open Overheid' . (isset($documentCount) ? ' (' . number_format($documentCount, 0, ',', '.') . ' documenten)' : ''))

@php
    // No breadcrumbs on homepage
    $breadcrumbs = [];
@endphp

@section('content')
    <!-- Hero Section with Search - Only on Homepage -->
    @if(request()->routeIs('home') && (!isset($homepageSettings) || $homepageSettings->hero_is_active))
    <x-hero-split-search 
        :badge="$homepageSettings->hero_badge ?? 'Open source Woo-voorziening'"
        :badgeText="$homepageSettings->hero_badge_text ?? 'Volledig operationeel'"
        :title="$homepageSettings->hero_title ?? 'OpenPublicaties: Open Source Woo-Voorziening'"
        :description="$homepageSettings->hero_description ?? 'Vind en bekijk alle actief openbaar gemaakte overheidsdocumenten. Eenvoudig, betrouwbaar en volledig transparant.'"
        :documentCount="$documentCount"
    />
    
    <!-- CTA Section - AI Assistant (from database or fallback) -->
    @php
        $aiCtaPanel = isset($ctaPanels) ? $ctaPanels->where('slug', 'ai-assistant')->first() : null;
    @endphp
    <x-cta-dark-panel 
        :title="$aiCtaPanel->title ?? 'Stel vragen aan onze AI-assistent'"
        :description="$aiCtaPanel->description ?? 'Vraag in gewone taal naar overheidsdocumenten en ontvang direct antwoord. Onze AI doorzoekt honderden duizenden documenten en vindt precies wat u zoekt.'"
        :primaryButtonText="$aiCtaPanel->primary_button_text ?? 'Probeer nu gratis'"
        :primaryButtonUrl="$aiCtaPanel->primary_button_url ?? route('chat')"
        :secondaryButtonText="$aiCtaPanel->secondary_button_text ?? 'Hoe werkt het?'"
        :secondaryButtonUrl="$aiCtaPanel->secondary_button_url ?? route('over')"
        :screenshotUrl="$aiCtaPanel && $aiCtaPanel->screenshot ? asset('storage/' . $aiCtaPanel->screenshot) : asset('images/ss.png')"
        :screenshotAlt="$aiCtaPanel->screenshot_alt ?? 'AI-gestuurde documentzoekmachine'"
        :variant="$aiCtaPanel->variant ?? 'purple'"
    />
    @endif
    
    <!-- Header Section - on Zoeken Page -->
    @if(request()->routeIs('zoeken'))
    <x-page-header 
        eyebrow="Zoek in overheidsdocumenten"
        title="Uitgebreid zoeken"
        description="Gebruik filters op datum, organisatie, categorie of thema om precies te vinden wat je zoekt."
        :breadcrumbs="$zoekenBreadcrumbs"
    />
    @endif
    
    <!-- Quick Actions Section - Bento Grid - Only on Homepage -->
    @if(request()->routeIs('home') && (!isset($homepageSettings) || $homepageSettings->bento_is_active))
    <div class="bg-gray-50 py-16 sm:py-20">
        <div class="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
            <!-- Section Header -->
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-10">
                <div>
                    <p class="text-sm font-medium uppercase">{{ $homepageSettings->bento_eyebrow ?? 'Snel aan de slag' }}</p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">
                        {{ $homepageSettings->bento_title ?? 'Alles wat je nodig hebt' }}
                    </h2>
                </div>
                <p class="max-w-sm text-sm lg:text-right">
                    {{ $homepageSettings->bento_description ?? 'Verken de verschillende manieren om overheidsdocumenten te vinden en te raadplegen.' }}
                </p>
            </div>

            <!-- Bento Grid - Dynamic from database or fallback -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-6 lg:grid-rows-2">
                @if(isset($bentoItems) && $bentoItems->count() > 0)
                    @foreach($bentoItems as $item)
                    @if($item->is_coming_soon)
                    <div class="lg:col-span-{{ $item->col_span }} opacity-60 cursor-not-allowed">
                        <div class="relative h-full overflow-hidden rounded-md bg-white shadow-xs">
                            <div class="overflow-hidden">
                                <img src="{{ $item->image_url ?? 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2072&auto=format&fit=crop' }}"
                                     alt="{{ $item->title }}"
                                     class="h-64 sm:h-80 w-full object-cover grayscale" />
                            </div>
                            <div class="p-6">
                                <h3 class="font-semibold text-gray-400 flex items-center gap-2">
                                    {{ $item->title }}
                                    <span class="inline-flex items-center rounded-full bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-700">{{ $item->coming_soon_text ?? 'Coming Soon' }}</span>
                                </h3>
                                <p class="mt-1.5 text-sm leading-relaxed text-gray-400">
                                    {{ $item->description }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @else
                    <a href="{{ $item->url ?? '#' }}" class="group lg:col-span-{{ $item->col_span }}">
                        <div class="relative h-full overflow-hidden rounded-md bg-white shadow-xs group">
                            <div class="overflow-hidden">
                                <img src="{{ $item->image_url ?? 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?q=80&w=2070&auto=format&fit=crop' }}"
                                     alt="{{ $item->title }}"
                                     class="h-64 sm:h-80 w-full object-cover object-center" />
                            </div>
                            <div class="p-6 {{ $item->col_span == 4 ? 'lg:p-8' : '' }}">
                                <h3 class="font-semibold group-hover:text-primary transition-colors duration-200">
                                    {{ $item->title }}
                                </h3>
                                <p class="mt-1.5 text-sm leading-relaxed">
                                    {{ $item->description }}
                                </p>
                            </div>
                        </div>
                    </a>
                    @endif
                    @endforeach
                @else
                {{-- Fallback static bento items --}}
                <a href="{{ route('themas.index') }}" class="group lg:col-span-4">
                    <div class="relative h-full overflow-hidden rounded-md bg-white shadow-xs group">
                        <div class="overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?q=80&w=2070&auto=format&fit=crop"
                                 alt="Thema's"
                                 class="h-64 sm:h-80 w-full object-cover object-center" />
                        </div>
                        <div class="p-6 lg:p-8">
                            <h3 class="font-semibold group-hover:text-primary transition-colors duration-200">
                                Thema's
                            </h3>
                            <p class="mt-1.5 text-sm leading-relaxed">
                                Zoek documenten op onderwerp zoals ruimtelijke ordening, onderwijs of zorg.
                            </p>
                        </div>
                    </div>
                </a>

                <div class="lg:col-span-2 opacity-60 cursor-not-allowed">
                    <div class="relative h-full overflow-hidden rounded-md bg-white shadow-xs">
                        <div class="overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2072&auto=format&fit=crop"
                                 alt="Dossiers"
                                 class="h-64 sm:h-80 w-full object-cover grayscale" />
                        </div>
                        <div class="p-6">
                            <h3 class="font-semibold text-gray-400 flex items-center gap-2">
                                Dossiers
                                <span class="inline-flex items-center rounded-full bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-700">Coming Soon</span>
                            </h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-gray-400">
                                Verken complete dossiers met alle bijbehorende documenten.
                            </p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('zoeken') }}" class="group lg:col-span-2">
                    <div class="relative h-full overflow-hidden rounded-md bg-white shadow-xs">
                        <div class="overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2070&auto=format&fit=crop"
                                 alt="Uitgebreid zoeken"
                                 class="h-64 sm:h-80 w-full object-cover" />
                        </div>
                        <div class="p-6">
                            <h3 class="font-semibold">
                                Uitgebreid zoeken
                            </h3>
                            <p class="mt-1.5 text-sm leading-relaxed">
                                Filters op datum, organisatie of categorie.
                            </p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('verwijzingen') }}" class="group lg:col-span-4">
                    <div class="relative h-full overflow-hidden rounded-md bg-white shadow-xs">
                        <div class="overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=2084&auto=format&fit=crop"
                                 alt="Verwijzingen"
                                 class="h-64 sm:h-80 w-full object-cover object-center" />
                        </div>
                        <div class="p-6 lg:p-8">
                            <h3 class="font-semibold">
                                Verwijzingen
                            </h3>
                            <p class="mt-1.5 text-sm leading-relaxed">
                                Vind handige links naar gerelateerde websites en informatiebronnen.
                            </p>
                        </div>
                    </div>
                </a>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Kennisbank Section -->
    @if(request()->routeIs('home') && (!isset($homepageSettings) || $homepageSettings->kennisbank_is_active))
    <div class="bg-white py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <!-- Section divider -->
            <div class="w-full h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent mb-12"></div>
            
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-10">
                <div>
                    <p class="text-sm font-medium uppercase">{{ $homepageSettings->kennisbank_eyebrow ?? 'Leren & ontdekken' }}</p>
                    <h2 class="mt-2 font-semibold sm:text-4xl">
                        {{ $homepageSettings->kennisbank_title ?? 'Kennisbank' }}
                    </h2>
                </div>
                <p class="max-w-sm text-sm lg:text-right">
                    {{ $homepageSettings->kennisbank_description ?? 'Leer meer over open data, transparantie en hoe je het platform effectief gebruikt.' }}
                </p>
            </div>
            <x-knowledge-base-grid />
        </div>
    </div>
    @endif

    <!-- Testimonials Section -->
    @if(request()->routeIs('home') && (!isset($homepageSettings) || $homepageSettings->testimonials_is_active))
    @php
        $testimonialsArray = isset($testimonials) && $testimonials->count() > 0 
            ? $testimonials->map(fn($t) => [
                'quote' => $t->quote,
                'author' => $t->author,
                'role' => $t->role,
                'organization' => $t->organization,
                'rating' => $t->rating,
            ])->toArray() 
            : [];
    @endphp
    <x-testimonials-grid :testimonials="$testimonialsArray" />
    @endif

    <!-- Newsletter Section -->
    @if(request()->routeIs('home') && (!isset($homepageSettings) || $homepageSettings->newsletter_is_active))
    <div class="relative isolate bg-gradient-to-b from-slate-50 to-white py-16 sm:py-20">
        <!-- Section divider at top -->
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
        
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-12 gap-y-12 lg:max-w-none lg:grid-cols-2 lg:items-center">
                <div class="max-w-xl">
                    <p class="text-sm font-medium uppercase">{{ $homepageSettings->newsletter_eyebrow ?? 'Nieuwsbrief' }}</p>
                    <h2 class="mt-2 font-semibold">
                        {{ $homepageSettings->newsletter_title ?? 'Blijf op de hoogte' }}
                    </h2>
                    <p class="mt-4 text-sm ">
                        {{ $homepageSettings->newsletter_description ?? 'Schrijf je in voor updates en ontvang het laatste nieuws over nieuwe documenten en platformupdates.' }}
                    </p>
                    <form action="#" method="POST" class="mt-6 flex flex-col sm:flex-row w-full gap-3">
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
                        <x-primary-button class="flex-shrink-0 rounded-md px-5 py-2.5 text-sm font-semibold whitespace-nowrap w-full sm:w-auto">
                            {{ $homepageSettings->newsletter_button_text ?? 'Inschrijven' }}
                        </x-primary-button>
                    </form>
                </div>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="relative rounded-md bg-white p-6 ring-1 ring-slate-200/60">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-[var(--color-primary-dark)]/10 mb-4">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-[var(--color-primary)]">
                                <path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-[var(--color-on-surface)]">
                            {{ $homepageSettings->newsletter_feature_1_title ?? 'Regelmatige updates' }}
                        </h3>
                        <p class="mt-2 text-sm text-[var(--color-on-surface-variant)] leading-relaxed">
                            {{ $homepageSettings->newsletter_feature_1_description ?? 'Wekelijks overzicht van nieuwe documenten en ontwikkelingen.' }}
                        </p>
                    </div>
                    <div class="relative rounded-md bg-white p-6 ring-1 ring-slate-200/60">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-purple/10 mb-4">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-purple">
                                <path d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-[var(--color-on-surface)]">
                            {{ $homepageSettings->newsletter_feature_2_title ?? 'Geen spam' }}
                        </h3>
                        <p class="mt-2 text-sm text-[var(--color-on-surface-variant)] leading-relaxed">
                            {{ $homepageSettings->newsletter_feature_2_description ?? 'Alleen relevante updates. Je kunt je altijd uitschrijven.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA Section - Primary variant (bottom, from database or fallback) -->
    @php
        $bottomCtaPanel = isset($ctaPanels) ? $ctaPanels->where('slug', 'bottom-cta')->first() : null;
    @endphp
    <x-cta-dark-panel 
        :title="$bottomCtaPanel->title ?? 'Begin vandaag nog met transparante publicaties'"
        :description="$bottomCtaPanel->description ?? 'Ontdek hoe OpenPublicaties uw organisatie kan helpen bij het voldoen aan de Wet open overheid. Eenvoudig, betrouwbaar en volledig open source.'"
        :primaryButtonText="$bottomCtaPanel->primary_button_text ?? 'Neem contact op'"
        :primaryButtonUrl="$bottomCtaPanel->primary_button_url ?? route('contact')"
        :secondaryButtonText="$bottomCtaPanel->secondary_button_text ?? 'Meer informatie'"
        :secondaryButtonUrl="$bottomCtaPanel->secondary_button_url ?? route('over')"
        :screenshotUrl="$bottomCtaPanel && $bottomCtaPanel->screenshot ? asset('storage/' . $bottomCtaPanel->screenshot) : asset('images/ss.png')"
        :screenshotAlt="$bottomCtaPanel->screenshot_alt ?? 'OpenPublicaties platform'"
        :variant="$bottomCtaPanel->variant ?? 'primary'"
    />
    @endif
@endsection
