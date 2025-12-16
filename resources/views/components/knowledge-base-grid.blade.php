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
    <article class="flex flex-col items-start justify-between">
        <div class="relative w-full">
            <img 
                src="{{ $article['image'] }}" 
                alt="{{ $article['title'] }}" 
                class="aspect-video w-full rounded-md bg-gray-100 object-cover sm:aspect-2/1 lg:aspect-3/2 dark:bg-gray-800"
                loading="lazy"
            />
            <div class="absolute inset-0 rounded-md inset-ring inset-ring-gray-900/10 dark:inset-ring-white/10"></div>
        </div>
        <div class="flex max-w-xl grow flex-col justify-between">
            <div class="mt-8 flex items-center gap-x-4 text-xs">
                <time datetime="{{ $article['date']->format('Y-m-d') }}" class="text-gray-500 dark:text-gray-400">
                    {{ $article['date']->format('M d, Y') }}
                </time>
                <a href="#" class="relative z-10 rounded-md bg-gray-50 px-3 py-1.5 font-medium text-gray-600 dark:bg-gray-800/60 dark:text-gray-300">
                    {{ $article['category'] }}
                </a>
            </div>
            <div class="relative grow">
                <h3 class="mt-3 text-lg/6 font-semibold text-gray-900 dark:text-white">
                    <a href="#">
                        <span class="absolute inset-0"></span>
                        {{ $article['title'] }}
                    </a>
                </h3>
                <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600 dark:text-gray-400">
                    {{ $article['description'] }}
                </p>
            </div>
            <div class="relative mt-8 flex items-center gap-x-4 justify-self-end">
                <img src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-10 rounded-full bg-gray-100 dark:bg-gray-800" />
                <div class="text-sm/6">
                    <p class="font-semibold text-gray-900 dark:text-white">
                        <a href="#">
                            <span class="absolute inset-0"></span>
                            Open Overheid Team
                        </a>
                    </p>
                    <p class="text-gray-600 dark:text-gray-400">Redactie</p>
                </div>
            </div>
        </div>
    </article>
    @endforeach
</div>
