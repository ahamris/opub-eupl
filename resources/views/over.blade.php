@extends('layouts.app')

@section('title', 'Over OpenPublicaties - Open Source Woo-Voorziening')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'Over OpenPublicaties', 'href' => null, 'current' => true],
    ];
@endphp

@section('content')
    <x-page-header 
        eyebrow="Open source Woo-voorziening"
        title="Over OpenPublicaties"
        description="Een volledig open source, lichtgewicht en state-of-the-art Woo-voorziening die actieve openbaarmaking eenvoudig, betrouwbaar en duurzaam ondersteunt."
        :breadcrumbs="$breadcrumbs"
    />
    
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
                                <div class="w-12 h-12 rounded-md bg-[var(--color-primary-light)] flex items-center justify-center">
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

