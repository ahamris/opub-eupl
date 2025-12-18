@extends('layouts.app')

@section('title', 'Over OpenPublicaties - Open Source Woo-Voorziening')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'Over OpenPublicaties', 'href' => null, 'current' => true],
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
                <p class="text-sm font-medium uppercase">Open source Woo-voorziening</p>
                <h1 class="mt-2 font-semibold">Over OpenPublicaties</h1>
                <p class="mt-4 text-base text-[var(--color-on-surface-variant)] leading-relaxed">
                    Een volledig open source, lichtgewicht en state-of-the-art Woo-voorziening die actieve openbaarmaking eenvoudig, betrouwbaar en duurzaam ondersteunt.
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
                <!-- Introductie -->
                <div>
                    <p class="text-base/7 text-[var(--color-on-surface-variant)]">
                        Open.overheid.nl bundelt actief openbaar gemaakte documenten op één centrale plek. Met OpenPublicaties (opub.nl) 
                        hebben wij een volledig open source Woo-voorziening ontwikkeld die hierop aansluit: een moderne, lichte 
                        referentie-implementatie die laat zien hoe actieve openbaarmaking sneller, transparanter en beter beheersbaar kan 
                        worden ingericht – in nauwe samenhang met de Woo-index en de landelijke voorzieningen.
                    </p>
                </div>
                
                <!-- Projectdoelstelling -->
                        <div>
                            <h2 class="text-[var(--font-size-headline-medium)] font-bold tracking-tight !text-[var(--color-primary-dark)] mb-4">
                                Projectdoelstelling
                            </h2>
                            <p class="text-base/7 text-[var(--color-on-surface)]-variant">
                                Het doel van het project <em>OpenPublicaties</em> is het realiseren van een volledig open source, 
                                lichtgewicht en state-of-the-art Woo-voorziening die actief openbaar maken eenvoudig, betrouwbaar en 
                                duurzaam ondersteunt. De voorziening fungeert als een <strong>blauwdruk</strong> voor bestuursorganen en als 
                                <strong>innovatieve referentie-implementatie</strong> voor het Ministerie van BZK, waarmee wordt aangetoond dat moderne 
                                technieken het Woo-proces aanzienlijk kunnen versnellen en vereenvoudigen.
                            </p>
                            <p class="text-base/7 text-[var(--color-on-surface)]-variant mt-4">
                                Het project levert een werkende, schaalbare, modulair uitbreidbare voorziening waarmee documenten automatisch 
                                worden geharvest, gemetadateerd, geïndexeerd en actief openbaar gemaakt, in nauwe aansluiting op de Woo-index 
                                en open.overheid.nl.
                            </p>
                        </div>

                        <!-- Technische Realisatie -->
                        <div>
                            <h2 class="text-[var(--font-size-headline-medium)] font-bold tracking-tight !text-[var(--color-primary-dark)] mb-4">
                                Technische Realisatie
                            </h2>
                            <p class="text-base/7 text-[var(--color-on-surface)]-variant mb-4">
                                OpenPublicaties is door ons gebouwd als een werkende, actieve proof-of-concept Woo-voorziening. Geen 
                                cosmetische schil, maar een end-to-end keten op basis van state-of-the-art open source tooling:
                            </p>
                            <ul role="list" class="mt-4 space-y-3 text-base/7 text-[var(--color-on-surface)]-variant">
                                <li class="flex gap-x-3">
                                    <i class="fas fa-check-circle text-[var(--color-primary)] mt-1 flex-none" aria-hidden="true"></i>
                                    <span><strong class="font-semibold text-[var(--color-on-surface)]">Laravel 12</strong> voor de applicatielaag</span>
                                </li>
                                <li class="flex gap-x-3">
                                    <i class="fas fa-check-circle text-[var(--color-primary)] mt-1 flex-none" aria-hidden="true"></i>
                                    <span><strong class="font-semibold text-[var(--color-on-surface)]">Go</strong> voor het actief harvesten van bronnen zoals open.overheid.nl en zoek.openraadsinformatie.nl</span>
                                </li>
                                <li class="flex gap-x-3">
                                    <i class="fas fa-check-circle text-[var(--color-primary)] mt-1 flex-none" aria-hidden="true"></i>
                                    <span><strong class="font-semibold text-[var(--color-on-surface)]">PostgreSQL 12</strong> en <strong class="font-semibold text-[var(--color-on-surface)]">Typesense</strong> voor opslag en razendsnelle zoekfunctionaliteit</span>
                                </li>
                                <li class="flex gap-x-3">
                                    <i class="fas fa-check-circle text-[var(--color-primary)] mt-1 flex-none" aria-hidden="true"></i>
                                    <span><strong class="font-semibold text-[var(--color-on-surface)]">Ollama AI (local)</strong> voor slimme vind- en duidfuncties</span>
                                </li>
                                <li class="flex gap-x-3">
                                    <i class="fas fa-check-circle text-[var(--color-primary)] mt-1 flex-none" aria-hidden="true"></i>
                                    <span><strong class="font-semibold text-[var(--color-on-surface)]">Tailwind CSS 4</strong> voor een moderne, toegankelijke front-end</span>
                                </li>
                            </ul>
                            <p class="text-base/7 text-[var(--color-on-surface)]-variant mt-4">
                                Met uitsluitend de broodnodige packages blijft de oplossing licht, beheersbaar en goed uitlegbaar.
                            </p>
                        </div>
                        
                        <!-- Kernwaarden -->
                        <div>
                            <h2 class="text-[var(--font-size-headline-medium)] font-bold tracking-tight !text-[var(--color-primary-dark)] mb-4">
                                Kernwaarden
                            </h2>
                            <ul role="list" class="mt-4 space-y-6 text-base/7 text-[var(--color-on-surface)]-variant">
                                <li class="flex gap-x-3">
                                    <i class="fas fa-cloud-upload-alt text-[var(--color-primary)] mt-1 size-5 flex-none" aria-hidden="true"></i>
                                    <span>
                                        <strong class="font-semibold text-[var(--color-on-surface)]">Moderne, transparante architectuur.</strong>
                                        De volledige keten is opgebouwd uit open source componenten met een heldere scheiding tussen 
                                        opslag, indexing, AI-ondersteuning en presentatie. Dat maakt de oplossing niet alleen snel en 
                                        schaalbaar, maar ook eenvoudig te auditen, te beheren en zo nodig door andere leveranciers 
                                        over te nemen of voort te zetten.
                                    </span>
                                </li>
                                <li class="flex gap-x-3">
                                    <i class="fas fa-lock text-[var(--color-primary)] mt-1 size-5 flex-none" aria-hidden="true"></i>
                                    <span>
                                        <strong class="font-semibold text-[var(--color-on-surface)]">Actieve openbaarmaking als uitgangspunt.</strong>
                                        De inrichting volgt de logica van de <em>Wet open overheid</em>: zaakcontext en MDTO-metadata 
                                        vormen het vertrekpunt. De oplossing ondersteunt het actief publiceren van Woo-categorieën, 
                                        sluit aan bij de Woo-index en kan documenten en dossiers voorbereiden voor aanlevering aan 
                                        landelijke voorzieningen, zonder bestaande portalen te vervangen.
                                    </span>
                                </li>
                                <li class="flex gap-x-3">
                                    <i class="fas fa-users text-[var(--color-primary)] mt-1 size-5 flex-none" aria-hidden="true"></i>
                                    <span>
                                        <strong class="font-semibold text-[var(--color-on-surface)]">Samen leren, samen versnellen.</strong>
                                        OpenPublicaties is bewust als open source referentie-implementatie gebouwd. Bestuursorganen kunnen 
                                        de blauwdruk hergebruiken, uitbreiden en samen met ons – of met andere partijen – doorontwikkelen. 
                                        Daarmee ontstaat ruimte om te experimenteren, zonder de verantwoordelijkheid voor de landelijke 
                                        voorzieningen of bestaande leveranciersrelaties te doorkruisen.
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <!-- Van proof-of-concept naar gezamenlijke voorziening -->
                        <div>
                            <h2 class="text-[var(--font-size-headline-medium)] font-bold tracking-tight !text-[var(--color-primary-dark)] mb-4">
                                Van proof-of-concept naar gezamenlijke voorziening
                            </h2>
                            <p class="text-base/7 text-[var(--color-on-surface)]-variant mb-4">
                                Met deze oplossing reiken wij een concreet alternatief aan: geen papieren architectuur, maar een werkende 
                                voorziening die vandaag al draait en in de praktijk wordt beproefd. CodeLabs B.V. ondersteunt bestuursorganen 
                                graag bij het verkennen van deze aanpak – van een eerste pilot tot een structurele, multi-tenant inrichting 
                                naast of in aanvulling op bestaande Woo-voorzieningen.
                            </p>
                            <p class="text-base/7 text-[var(--color-on-surface)]-variant">
                                We nodigen het ministerie van BZK en andere belanghebbenden van harte uit om samen in gesprek te gaan: 
                                niet om met de vinger te wijzen, maar om vanuit een gedeelde verantwoordelijkheid voor de rechtsstaat en 
                                de <em>Wet open overheid</em> te laten zien dat het daadwerkelijk eenvoudiger, sneller en toekomstbestendig 
                                kan. OpenPublicaties is onze uitgestoken hand – een transparante blauwdruk die we graag samen verder inkleuren.
                            </p>
                        </div>
                        
                        <!-- Bijdrage aan de Wet open overheid -->
                        <div>
                            <h2 class="text-[var(--font-size-headline-medium)] font-bold tracking-tight !text-[var(--color-primary-dark)] mb-4">
                                Bijdrage aan de Wet open overheid (Woo)
                            </h2>
                            <p class="text-base/7 text-[var(--color-on-surface)]-variant">
                                OpenPublicaties versterkt de doelen van de Woo door actieve openbaarmaking te vereenvoudigen, toegankelijkheid 
                                en vindbaarheid te verbeteren, de transparantie van digitale overheidsinformatie te vergroten, en bestuursorganen 
                                meer grip te geven op hun informatiehuishouding.
                            </p>
                        </div>

                        <!-- Vraag en ondersteuning -->
                        <div class="flex items-start gap-4 pt-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-md bg-[var(--color-primary)]/10 flex items-center justify-center shrink-0">
                                    <i class="fas fa-question-circle text-lg text-[var(--color-primary)]" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-[var(--font-size-headline-medium)] font-bold tracking-tight !text-[var(--color-primary-dark)] mb-4">
                                    Vraag en ondersteuning
                                </h2>
                                <p class="text-base/7 text-[var(--color-on-surface)]-variant mb-4">
                                    Heeft u vragen of suggesties over de website?
                                </p>
                                <a href="#" class="inline-flex items-center gap-2 text-[var(--color-primary)] font-medium text-base hover:text-[var(--color-primary-dark)] focus:outline-none transition-colors duration-200">
                                    Link naar contact
                                    <i class="fas fa-chevron-right text-sm" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
        </div>
    </main>
@endsection

