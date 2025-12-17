@extends('layouts.app')

@section('title', 'Contact - Open Overheid')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'Contact', 'href' => null, 'current' => true],
    ];
@endphp

@section('content')
    <x-page-header 
        eyebrow="Neem contact op"
        title="Contact"
        description="Heeft u vragen, suggesties of opmerkingen? Wij horen graag van u."
        :breadcrumbs="$breadcrumbs"
    />

    <!-- Split with Pattern Section -->
    <div class="relative isolate bg-[var(--color-surface)]">
        <div class="mx-auto grid max-w-7xl grid-cols-1 lg:grid-cols-2">
            <!-- Left side: Info with pattern background -->
            <div class="relative px-6 pt-24 pb-20 sm:pt-32 lg:static lg:px-8 lg:py-48">
                <div class="mx-auto max-w-xl lg:mx-0 lg:max-w-lg">
                    <!-- Pattern background -->
                    <div class="absolute inset-y-0 left-0 -z-10 w-full overflow-hidden bg-[var(--color-primary-light)]/30 ring-1 ring-[var(--color-outline-variant)] lg:w-1/2">
                        <svg aria-hidden="true" class="absolute inset-0 size-full mask-[radial-gradient(100%_100%_at_top_right,white,transparent)] stroke-[var(--color-outline-variant)]/20">
                            <defs>
                                <pattern id="contact-pattern" width="200" height="200" x="100%" y="-1" patternUnits="userSpaceOnUse">
                                    <path d="M130 200V.5M.5 .5H200" fill="none" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" stroke-width="0" class="fill-[var(--color-surface)]" />
                            <svg x="100%" y="-1" class="overflow-visible fill-[var(--color-primary-light)]/20">
                                <path d="M-470.5 0h201v201h-201Z" stroke-width="0" />
                            </svg>
                            <rect width="100%" height="100%" fill="url(#contact-pattern)" stroke-width="0" />
                        </svg>
                    </div>
                    
                    <h2 class="text-4xl font-semibold tracking-tight text-pretty text-[var(--color-on-surface)] sm:text-5xl">
                        Contactgegevens
                    </h2>
                    <p class="mt-6 text-lg text-[var(--color-on-surface-variant)]">
                        Neem gerust contact met ons op via onderstaande gegevens of gebruik het contactformulier.
                    </p>
                    
                    <!-- Contact Information -->
                    <dl class="mt-10 space-y-4 text-base text-[var(--color-on-surface-variant)]">
                        <div class="flex gap-x-4">
                            <dt class="flex-none">
                                <span class="sr-only">Adres</span>
                                <i class="fas fa-map-marker-alt h-6 w-6 text-[var(--color-primary)]" aria-hidden="true"></i>
                            </dt>
                            <dd>
                                Open Overheid Platform<br />
                                Den Haag, Nederland
                            </dd>
                        </div>
                        <div class="flex gap-x-4">
                            <dt class="flex-none">
                                <span class="sr-only">Telefoon</span>
                                <i class="fas fa-phone h-6 w-6 text-[var(--color-primary)]" aria-hidden="true"></i>
                            </dt>
                            <dd>
                                <a href="tel:+31123456789" class="hover:text-[var(--color-primary)] transition-colors duration-200">
                                    +31 (0) 123 456 789
                                </a>
                            </dd>
                        </div>
                        <div class="flex gap-x-4">
                            <dt class="flex-none">
                                <span class="sr-only">E-mail</span>
                                <i class="fas fa-envelope h-6 w-6 text-[var(--color-primary)]" aria-hidden="true"></i>
                            </dt>
                            <dd>
                                <a href="mailto:contact@openoverheid.nl" class="hover:text-[var(--color-primary)] transition-colors duration-200">
                                    contact@openoverheid.nl
                                </a>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Right side: Contact Form -->
            <form action="#" method="POST" class="px-6 pt-20 pb-24 sm:pb-32 lg:px-8 lg:py-48">
                <div class="mx-auto max-w-xl lg:mr-0 lg:max-w-lg">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                        <div>
                            <label for="first-name" class="block text-sm font-semibold text-[var(--color-on-surface)] mb-2">Voornaam</label>
                            <x-input 
                                type="text" 
                                name="first-name" 
                                id="first-name"
                                autocomplete="given-name"
                                placeholder="Uw voornaam"
                            />
                        </div>
                        <div>
                            <label for="last-name" class="block text-sm font-semibold text-[var(--color-on-surface)] mb-2">Achternaam</label>
                            <x-input 
                                type="text" 
                                name="last-name" 
                                id="last-name"
                                autocomplete="family-name"
                                placeholder="Uw achternaam"
                            />
                        </div>
                        <div class="sm:col-span-2">
                            <label for="email" class="block text-sm font-semibold text-[var(--color-on-surface)] mb-2">E-mailadres</label>
                            <x-input 
                                type="email" 
                                name="email" 
                                id="email"
                                autocomplete="email"
                                placeholder="uw.email@voorbeeld.nl"
                                required
                            />
                        </div>
                        <div class="sm:col-span-2">
                            <div class="flex justify-between text-sm mb-2">
                                <label for="phone" class="block font-semibold text-[var(--color-on-surface)]">Telefoonnummer</label>
                                <p id="phone-description" class="text-[var(--color-on-surface-variant)]">Optioneel</p>
                            </div>
                            <x-input 
                                type="tel" 
                                name="phone" 
                                id="phone"
                                autocomplete="tel"
                                aria-describedby="phone-description"
                                placeholder="06-12345678"
                            />
                        </div>
                        <div class="sm:col-span-2">
                            <label for="message" class="block text-sm font-semibold text-[var(--color-on-surface)] mb-2">Bericht</label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="4"
                                class="block w-full rounded-md bg-[var(--color-surface)] px-3.5 py-2 text-sm text-[var(--color-on-surface)] border border-[var(--color-outline-variant)] placeholder:text-[var(--color-on-surface-variant)] focus:outline-none focus:border-[var(--color-primary)] transition-colors duration-200"
                                placeholder="Uw bericht..."
                            ></textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button 
                            type="submit" 
                            class="inline-flex items-center justify-center px-4 py-2 bg-[var(--color-primary)] text-[var(--color-on-primary)] border border-[var(--color-primary)] rounded-md font-semibold text-sm hover:bg-[var(--color-primary-dark)] focus:outline-none transition-colors duration-200">
                            Verstuur bericht
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
