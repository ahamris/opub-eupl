@props([
    'articles' => [],
])

@php
    // Placeholder artikelen - later kunnen deze uit een database komen
    $defaultArticles = [
        [
            'title' => 'Wat is de Wet open overheid (Woo)?',
            'description' => 'Leer alles over de Woo en hoe deze wet transparantie bevordert in de Nederlandse overheid. Ontdek wat de Woo betekent voor burgers en organisaties.',
            'category' => 'Basis',
            'date' => now()->subDays(5),
            'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?q=80&w=2070&auto=format&fit=crop',
        ],
        [
            'title' => 'Hoe zoek je effectief in overheidsdocumenten?',
            'description' => 'Tips en trucs om snel de juiste documenten te vinden met geavanceerde zoekfuncties en filters.',
            'category' => 'Gebruik',
            'date' => now()->subDays(12),
            'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2072&auto=format&fit=crop',
        ],
        [
            'title' => 'Open data en transparantie in Nederland',
            'description' => 'Ontdek hoe open data bijdraagt aan een transparantere overheid en betere democratie.',
            'category' => 'Achtergrond',
            'date' => now()->subDays(20),
            'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2070&auto=format&fit=crop',
        ],
    ];
    
    $articles = !empty($articles) ? $articles : $defaultArticles;
@endphp

<div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
    @foreach($articles as $article)
    <article class="flex flex-col items-start justify-between group">
        <div class="relative w-full">
            <img 
                src="{{ $article['image'] }}" 
                alt="{{ $article['title'] }}" 
                class="aspect-video w-full rounded-2xl bg-outline-variant/30 object-cover sm:aspect-2/1 lg:aspect-3/2 transition-transform duration-300 group-hover:scale-105"
                loading="lazy"
            />
            <div class="absolute inset-0 rounded-2xl ring-1 ring-inset ring-outline-variant/20"></div>
        </div>
        <div class="flex max-w-xl grow flex-col justify-between mt-8">
            <div class="flex items-center gap-x-4 text-label-small mb-4">
                <time datetime="{{ $article['date']->format('Y-m-d') }}" class="text-on-surface-variant/70">
                    {{ $article['date']->format('d M Y') }}
                </time>
                <a href="#" class="relative z-10 rounded bg-primary-light/50 px-3 py-1.5 font-medium text-primary hover:bg-primary-light transition-colors duration-200">
                    {{ $article['category'] }}
                </a>
            </div>
            <div class="group/article relative grow">
                <h3 class="mt-3 text-headline-small font-semibold text-on-surface group-hover/article:text-primary transition-colors duration-200">
                    <a href="#">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        {{ $article['title'] }}
                    </a>
                </h3>
                <p class="mt-5 line-clamp-3 text-body-medium text-on-surface-variant/80 leading-relaxed">
                    {{ $article['description'] }}
                </p>
            </div>
            <div class="relative mt-8 flex items-center gap-x-4">
                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                    <i class="fas fa-user text-primary text-sm" aria-hidden="true"></i>
                </div>
                <div class="text-body-small">
                    <p class="font-semibold text-on-surface">
                        <a href="#" class="hover:text-primary transition-colors duration-200">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            Open Overheid Team
                        </a>
                    </p>
                    <p class="text-on-surface-variant/70">Redactie</p>
                </div>
            </div>
        </div>
    </article>
    @endforeach
</div>
