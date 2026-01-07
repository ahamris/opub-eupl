@extends('layouts.app')

@section('title', 'Contact - Open Overheid')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'Contact', 'href' => null, 'current' => true],
    ];
@endphp

@section('content')
    <!-- Premium Page Header Section -->
    <div class="relative bg-gradient-to-b from-slate-50 to-white overflow-hidden">
        <!-- Subtle grid pattern -->
        <div class="absolute inset-0 -z-10">
            <svg class="absolute inset-0 h-full w-full stroke-slate-200/50" fill="none">
                <defs>
                    <pattern id="contact-header-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M.5 40V.5H40" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#contact-header-grid)" />
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
                <p class="text-sm font-medium uppercase">Neem contact op</p>
                <h1 class="mt-2 font-semibold">Contactgegevens</h1>
                <p class="mt-4 text-base text-[var(--color-on-surface-variant)] leading-relaxed">
                    Neem gerust contact met ons op via onderstaande gegevens of gebruik het contactformulier.
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

    <!-- Split with Pattern Section -->
    <div class="relative isolate bg-[var(--color-surface)]">
        <div class="mx-auto grid max-w-7xl grid-cols-1 lg:grid-cols-2">
            <!-- Left side: Contact Info with pattern background -->
            <div class="relative px-6 pt-24 pb-20 sm:pt-32 lg:static lg:px-8 lg:py-48">
                <div class="mx-auto max-w-xl lg:mx-0 lg:max-w-lg">
                    <!-- Pattern background -->
                    <div class="absolute inset-y-0 left-0 -z-10 w-full overflow-hidden bg-slate-100 ring-1 ring-slate-900/10 lg:w-1/2 dark:bg-zinc-900 dark:ring-white/10">
                        <svg aria-hidden="true" class="absolute inset-0 size-full mask-[radial-gradient(100%_100%_at_top_right,white,transparent)] stroke-slate-200 dark:stroke-white/10">
                            <defs>
                                <pattern id="contact-pattern" width="200" height="200" x="100%" y="-1" patternUnits="userSpaceOnUse">
                                    <path d="M130 200V.5M.5 .5H200" fill="none" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" stroke-width="0" class="fill-white dark:fill-zinc-900" />
                            <svg x="100%" y="-1" class="overflow-visible fill-slate-50 dark:fill-zinc-800/20">
                                <path d="M-470.5 0h201v201h-201Z" stroke-width="0" />
                            </svg>
                            <rect width="100%" height="100%" fill="url(#contact-pattern)" stroke-width="0" />
                        </svg>
                        <!-- Gradient blur decoration -->
                        <div aria-hidden="true" class="absolute top-[calc(100%-13rem)] -left-56 hidden transform-gpu blur-3xl lg:top-[calc(50%-7rem)] lg:left-[max(-14rem,calc(100%-59rem))] lg:block">
                            <div style="clip-path: polygon(74.1% 56.1%, 100% 38.6%, 97.5% 73.3%, 85.5% 100%, 80.7% 98.2%, 72.5% 67.7%, 60.2% 37.8%, 52.4% 32.2%, 47.5% 41.9%, 45.2% 65.8%, 27.5% 23.5%, 0.1% 35.4%, 17.9% 0.1%, 27.6% 23.5%, 76.1% 2.6%, 74.1% 56.1%)" class="aspect-[1155/678] w-[72.1875rem] bg-gradient-to-br from-[var(--color-primary-dark)] to-[var(--color-purple)] opacity-20 dark:opacity-30"></div>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <dl class="mt-10 space-y-4 text-base/7 text-[var(--color-on-surface-variant)]">
                        <div class="flex gap-x-4">
                            <dt class="flex-none">
                                <span class="sr-only">Adres</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="h-7 w-6 text-slate-400 dark:text-zinc-500">
                                    <path d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </dt>
                            <dd>
                                @if(get_setting('contact_address'))
                                    {!! nl2br(e(get_setting('contact_address'))) !!}
                                @else
                                    Open Overheid Platform<br/>Den Haag, Nederland
                                @endif
                            </dd>
                        </div>
                        @if(get_setting('contact_phone'))
                        <div class="flex gap-x-4">
                            <dt class="flex-none">
                                <span class="sr-only">Telefoon</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="h-7 w-6 text-slate-400 dark:text-zinc-500">
                                    <path d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </dt>
                            <dd><a href="tel:{{ get_setting('contact_phone') }}" class="hover:text-[var(--color-on-surface)]">{{ get_setting('contact_phone') }}</a></dd>
                        </div>
                        @endif
                        @if(get_setting('contact_email'))
                        <div class="flex gap-x-4">
                            <dt class="flex-none">
                                <span class="sr-only">E-mail</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="h-7 w-6 text-slate-400 dark:text-zinc-500">
                                    <path d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </dt>
                            <dd><a href="mailto:{{ get_setting('contact_email') }}" class="hover:text-[var(--color-on-surface)]">{{ get_setting('contact_email') }}</a></dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
            
            <!-- Right side: Contact Form -->
            <form action="{{ route('contact.store') }}" method="POST" class="px-6 pt-20 pb-24 sm:pb-32 lg:px-8 lg:py-48">
                @csrf
                
                <div class="mx-auto max-w-xl lg:mr-0 lg:max-w-lg">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800">
                            <div class="flex">
                                <i class="fas fa-check-circle text-green-500 dark:text-green-400 mr-3 mt-0.5"></i>
                                <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="mb-6 rounded-lg bg-red-50 dark:bg-red-900/30 p-4 border border-red-200 dark:border-red-800">
                            <div class="flex">
                                <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 mr-3 mt-0.5"></i>
                                <div class="text-sm text-red-800 dark:text-red-200">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                        <!-- Organisation Name (Optional) -->
                        <div class="sm:col-span-2">
                            <div class="flex justify-between text-sm mb-2">
                                <label for="organisation" class="block font-semibold text-[var(--color-on-surface)]">Organisatienaam</label>
                                <p class="text-[var(--color-on-surface-variant)]">Optioneel</p>
                            </div>
                            <x-input 
                                type="text" 
                                name="organisation" 
                                id="organisation"
                                autocomplete="organization"
                                placeholder="Naam van uw organisatie"
                            />
                        </div>
                        
                        <!-- Name and Surname -->
                        <div class="sm:col-span-2">
                            <label for="full-name" class="block text-sm font-semibold text-[var(--color-on-surface)] mb-2">Volledige naam</label>
                            <x-input 
                                type="text" 
                                name="full-name" 
                                id="full-name"
                                autocomplete="name"
                                placeholder="Uw voor- en achternaam"
                                required
                            />
                        </div>
                        
                        <!-- Email Address -->
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
                        
                        <!-- Telephone Number (Optional) -->
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
                        
                        <!-- Subject Dropdown -->
                        <div class="sm:col-span-2">
                            <label for="subject" class="block text-sm font-semibold text-[var(--color-on-surface)] mb-2">Waar kunnen wij u mee helpen?</label>
                            <select 
                                id="subject" 
                                name="subject"
                                required
                                class="block w-full rounded-md bg-[var(--color-surface)] px-3.5 py-2.5 text-sm text-[var(--color-on-surface)] border border-[var(--color-outline-variant)] focus:outline-none focus:border-[var(--color-primary-dark)] focus:ring-1 focus:ring-[var(--color-primary-dark)] transition-colors duration-200"
                            >
                                <option value="" disabled selected>Selecteer een onderwerp</option>
                                <option value="algemeen">Algemene vraag</option>
                                <option value="technisch">Technische ondersteuning</option>
                                <option value="samenwerking">Samenwerking / Partnerschappen</option>
                                <option value="data">Data & API toegang</option>
                                <option value="feedback">Feedback & Suggesties</option>
                                <option value="media">Media & Pers</option>
                                <option value="anders">Anders</option>
                            </select>
                        </div>
                        
                        <!-- Message (2x height) -->
                        <div class="sm:col-span-2">
                            <label for="message" class="block text-sm font-semibold text-[var(--color-on-surface)] mb-2">Bericht</label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="8"
                                required
                                class="block w-full rounded-md bg-[var(--color-surface)] px-3.5 py-2 text-sm text-[var(--color-on-surface)] border border-[var(--color-outline-variant)] placeholder:text-[var(--color-on-surface-variant)] focus:outline-none focus:border-[var(--color-primary-dark)] focus:ring-1 focus:ring-[var(--color-primary-dark)] transition-colors duration-200"
                                placeholder="Beschrijf uw vraag of opmerking..."
                            ></textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button 
                            type="submit" 
                            class="inline-flex items-center justify-center px-4 py-2 bg-[var(--color-primary-dark)] text-[var(--color-on-primary)] border border-[var(--color-primary-dark)] rounded-md font-semibold text-sm hover:bg-[var(--color-primary)] focus:outline-none transition-colors duration-200">
                            Verstuur bericht
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
