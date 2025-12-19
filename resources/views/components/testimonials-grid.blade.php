@props([
    'testimonials' => [],
])

@php
    // Placeholder testimonials - later kunnen deze uit een database komen
    $defaultTestimonials = [
        [
            'quote' => 'Dit platform maakt het zoeken naar overheidsdocumenten eindelijk eenvoudig. De zoekfunctie is snel en de filters helpen me precies te vinden wat ik nodig heb.',
            'author' => 'Sarah van der Berg',
            'role' => 'Onderzoeker',
            'organization' => 'Universiteit van Amsterdam',
            'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        ],
        [
            'quote' => 'Als journalist gebruik ik dit platform dagelijks. De transparantie en toegankelijkheid van documenten is ongeëvenaard.',
            'author' => 'Mark Jansen',
            'role' => 'Journalist',
            'organization' => 'NRC Handelsblad',
            'avatar' => 'https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        ],
        [
            'quote' => 'Fantastisch platform voor het vinden van overheidsinformatie. De interface is intuïtief en de documenten zijn goed georganiseerd.',
            'author' => 'Lisa de Vries',
            'role' => 'Beleidsmedewerker',
            'organization' => 'Gemeente Rotterdam',
            'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        ],
        [
            'quote' => 'De Woo-voorziening is een game-changer. Eindelijk kunnen we eenvoudig toegang krijgen tot alle openbare documenten.',
            'author' => 'Tom Bakker',
            'role' => 'Advocaat',
            'organization' => 'Bakker & Partners',
            'avatar' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        ],
        [
            'quote' => 'Als burger waardeer ik de transparantie enorm. Dit platform maakt de overheid toegankelijker voor iedereen.',
            'author' => 'Emma Smit',
            'role' => 'Burger',
            'organization' => null,
            'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        ],
    ];

    $testimonials = !empty($testimonials) ? $testimonials : $defaultTestimonials;
@endphp

<div class="bg-gradient-to-b from-white to-slate-50 py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <!-- Section divider -->
        <div class="w-full h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent mb-12"></div>
        
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-10">
            <div>
                <p class="text-sm font-medium uppercase">Testimonials</p>
                <h2 class="mt-2 font-semibold sm:text-4xl">
                    Wat gebruikers vinden
                </h2>
            </div>
            <p class="max-w-sm text-sm lg:text-right">
                Ontdek hoe anderen het platform gebruiken voor hun onderzoek en werk.
            </p>
        </div>
        
        <div class="mx-auto flow-root max-w-2xl lg:mx-0 lg:max-w-none">
            <div class="-mt-8 sm:-mx-4 sm:columns-2 sm:text-[0] lg:columns-3">
                @foreach($testimonials as $testimonial)
                <div class="pt-8 sm:inline-block sm:w-full sm:px-4">
                    <figure class="rounded-md bg-white p-8 ring-1 ring-slate-200/60">
                        <!-- Quote icon -->
                        <svg class="h-6 w-6 text-[var(--color-primary)]/20 mb-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z" />
                        </svg>
                        <blockquote class="text-[var(--color-on-surface)] leading-relaxed">
                            <p>{{ $testimonial['quote'] }}</p>
                        </blockquote>
                        <figcaption class="mt-6 flex items-center gap-x-4 pt-6 border-t border-slate-100">
                            <img
                                src="{{ $testimonial['avatar'] }}"
                                alt="{{ $testimonial['author'] }}"
                                class="h-12 w-12 rounded-full object-cover ring-2 ring-white"
                                loading="lazy"
                            />
                            <div>
                                <div class="font-semibold text-[var(--color-on-surface)]">
                                    {{ $testimonial['author'] }}
                                </div>
                                <div class="text-sm text-[var(--color-on-surface-variant)]">
                                    {{ $testimonial['role'] }}{{ $testimonial['organization'] ? ' · ' . $testimonial['organization'] : '' }}
                                </div>
                            </div>
                        </figcaption>
                    </figure>
                </div>
                @endforeach
                
                <!-- Show More Card -->
                <div class="pt-8 sm:inline-block sm:w-full sm:px-4">
                    <figure class="rounded-md bg-white p-8 ring-1 ring-slate-200/60 h-full flex flex-col items-center justify-center min-h-[280px] group hover:ring-[var(--color-primary)]/30 transition-all duration-200 cursor-pointer">
                        <div class="flex flex-col items-center justify-center text-center space-y-4">
                            <div class="w-12 h-12 rounded-full bg-[var(--color-primary)]/10 flex items-center justify-center group-hover:bg-[var(--color-primary)]/20 transition-colors duration-200">
                                <svg class="w-6 h-6 text-[var(--color-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-lg font-semibold text-[var(--color-on-surface)] group-hover:text-[var(--color-primary)] transition-colors duration-200">
                                    Meer tonen
                                </p>
                                <p class="text-sm text-[var(--color-on-surface-variant)] mt-1">
                                    Bekijk alle testimonials
                                </p>
                            </div>
                        </div>
                    </figure>
                </div>
            </div>
        </div>
    </div>
</div>