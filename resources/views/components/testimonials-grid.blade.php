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
        [
            'quote' => 'Professioneel, snel en betrouwbaar. Dit is precies wat we nodig hadden voor ons onderzoek naar overheidsbeleid.',
            'author' => 'David Mulder',
            'role' => 'Onderzoeker',
            'organization' => 'Clingendael Instituut',
            'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        ],
        [
            'quote' => 'De API en zoekfunctionaliteit zijn uitstekend. Perfect voor onze integratie met andere systemen.',
            'author' => 'Robert van Dijk',
            'role' => 'Developer',
            'organization' => 'Tech Solutions BV',
            'avatar' => 'https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        ],
        [
            'quote' => 'Geweldige tool voor het monitoren van overheidsbesluiten. Helpt ons om beter geïnformeerd te blijven.',
            'author' => 'Sophie de Boer',
            'role' => 'Communicatieadviseur',
            'organization' => 'NGO Transparantie',
            'avatar' => 'https://images.unsplash.com/photo-1463453091185-61582044d556?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        ],
        [
            'quote' => 'Uitstekende ervaring met dit platform. Het maakt het vinden van overheidsdocumenten veel eenvoudiger.',
            'author' => 'Jan Pietersen',
            'role' => 'Onderzoeker',
            'organization' => 'Universiteit Utrecht',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        ],
    ];

    $testimonials = !empty($testimonials) ? $testimonials : $defaultTestimonials;
@endphp

<div class="bg-[var(--color-surface)] py-24 sm:py-32 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="max-w-2xl">
            <h2 class="text-base/7 font-semibold text-[var(--color-primary)] dark:text-[var(--color-primary-light)]">Testimonials</h2>
            <p class="mt-2 text-4xl font-semibold tracking-tight text-balance text-[var(--color-on-surface-variant)]900 sm:text-5xl dark:text-white">
                Wat gebruikers van ons platform vinden
            </p>
        </div>
        <div class="mx-auto mt-16 flow-root max-w-2xl sm:mt-20 lg:mx-0 lg:max-w-none">
            <div class="-mt-8 sm:-mx-4 sm:columns-2 sm:text-[0] lg:columns-3">
                @foreach($testimonials as $testimonial)
                <div class="pt-8 sm:inline-block sm:w-full sm:px-4">
                    <figure class="rounded-md bg-gray-50 p-8 text-sm/6 dark:bg-[var(--color-surface)]/2.5">
                        <blockquote class="text-[var(--color-on-surface-variant)]900 dark:text-[var(--color-on-surface-variant)]100">
                            <p>"{{ $testimonial['quote'] }}"</p>
                        </blockquote>
                        <figcaption class="mt-6 flex items-center gap-x-4">
                            <img
                                src="{{ $testimonial['avatar'] }}"
                                alt="{{ $testimonial['author'] }}"
                                class="size-10 rounded-full bg-gray-50 dark:bg-gray-800"
                                loading="lazy"
                            />
                            <div>
                                <div class="font-semibold text-[var(--color-on-surface-variant)]900 dark:text-white">
                                    {{ $testimonial['author'] }}
                                </div>
                                <div class="text-[var(--color-on-surface-variant)]600 dark:text-[var(--color-on-surface-variant)]400">
                                    {{ $testimonial['role'] }}{{ $testimonial['organization'] ? ' bij ' . $testimonial['organization'] : '' }}
                                </div>
                            </div>
                        </figcaption>
                    </figure>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>