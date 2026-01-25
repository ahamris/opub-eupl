@props([
    'articles' => [],
])

@php
    // Try to fetch blogs from database first
    $dbBlogs = \App\Models\Blog::getCachedCarouselBlogs(5);
    
    if ($dbBlogs && $dbBlogs->count() > 0) {
        $articles = $dbBlogs->map(fn($blog) => [
            'title' => $blog->title,
            'description' => $blog->short_body,
            'category' => $blog->blog_category?->name ?? 'Algemeen',
            'date' => $blog->created_at,
            'image' => $blog->getImageUrl() ?? 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?q=80&w=2070&auto=format&fit=crop',
            'url' => $blog->link_url,
            'author' => $blog->author?->name ?? 'Open Overheid Team',
            'author_avatar' => $blog->author?->profile_photo_url ?? 'https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        ])->toArray();
    } elseif (empty($articles)) {
        // Fallback to static defaults if no DB blogs
        $articles = [
            [
                'title' => 'Wat is de Wet open overheid (Woo)?',
                'description' => 'Leer alles over de Woo en hoe deze wet transparantie bevordert in de Nederlandse overheid. Ontdek wat de Woo betekent voor burgers en organisaties.',
                'category' => 'Basis',
                'date' => now()->subDays(5),
                'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?q=80&w=2070&auto=format&fit=crop',
                'url' => '#',
            ],
            [
                'title' => 'Hoe zoek je effectief in overheidsdocumenten?',
                'description' => 'Tips en trucs om snel de juiste documenten te vinden met geavanceerde zoekfuncties en filters.',
                'category' => 'Gebruik',
                'date' => now()->subDays(12),
                'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2072&auto=format&fit=crop',
                'url' => '#',
            ],
            [
                'title' => 'Open data en transparantie in Nederland',
                'description' => 'Ontdek hoe open data bijdraagt aan een transparantere overheid en betere democratie.',
                'category' => 'Achtergrond',
                'date' => now()->subDays(20),
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2070&auto=format&fit=crop',
                'url' => '#',
            ],
            [
                'title' => 'Woo-verzoeken indienen: een praktische gids',
                'description' => 'Stap-voor-stap instructies voor het indienen van een Woo-verzoek en wat je kunt verwachten.',
                'category' => 'Gebruik',
                'date' => now()->subDays(30),
                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=2070&auto=format&fit=crop',
                'url' => '#',
            ],
            [
                'title' => 'Privacy en openbaarheid: de balans',
                'description' => 'Hoe de Woo omgaat met privacy en welke informatie wel en niet openbaar gemaakt kan worden.',
                'category' => 'Achtergrond',
                'date' => now()->subDays(45),
                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=2070&auto=format&fit=crop',
                'url' => '#',
            ],
        ];
    }
@endphp

<div class="mx-auto mt-16 relative" x-data="{
    currentIndex: 0,
    itemsPerView: 3,
    totalItems: {{ count($articles) + 1 }},
    touchStartX: 0,
    touchStartY: 0,
    touchEndX: 0,
    touchEndY: 0,
    isDragging: false,
    dragOffset: 0,
    get maxIndex() { 
        return Math.max(0, this.totalItems - this.itemsPerView);
    },
    updateItemsPerView() {
        if (window.innerWidth >= 1024) this.itemsPerView = 3;
        else if (window.innerWidth >= 640) this.itemsPerView = 2;
        else this.itemsPerView = 1;
        if (this.currentIndex > this.maxIndex) this.currentIndex = this.maxIndex;
    },
    prev() {
        if (this.currentIndex > 0) this.currentIndex--;
    },
    next() {
        if (this.currentIndex < this.maxIndex) this.currentIndex++;
    },
    handleTouchStart(e) {
        this.touchStartX = e.touches[0].clientX;
        this.touchStartY = e.touches[0].clientY;
        this.isDragging = true;
    },
    handleTouchMove(e) {
        if (!this.isDragging) return;
        this.dragOffset = e.touches[0].clientX - this.touchStartX;
    },
    handleTouchEnd(e) {
        if (!this.isDragging) return;
        this.touchEndX = e.changedTouches[0].clientX;
        this.touchEndY = e.changedTouches[0].clientY;
        this.isDragging = false;
        
        const deltaX = this.touchEndX - this.touchStartX;
        const deltaY = this.touchEndY - this.touchStartY;
        const minSwipeDistance = 50;
        
        // Only swipe if horizontal movement is greater than vertical
        if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > minSwipeDistance) {
            if (deltaX > 0) {
                this.prev();
            } else {
                this.next();
            }
        }
        
        this.dragOffset = 0;
    }
}" x-init="
    updateItemsPerView();
    window.addEventListener('resize', () => updateItemsPerView());
">
    <!-- Carousel Container -->
    <div class="overflow-hidden touch-pan-y">
        <div 
            class="flex transition-transform duration-500 ease-in-out"
            :class="{ 'transition-none': isDragging }"
            :style="`transform: translateX(calc(-${currentIndex * (100 / itemsPerView)}% + ${dragOffset}px))`"
            @touchstart="handleTouchStart($event)"
            @touchmove="handleTouchMove($event)"
            @touchend="handleTouchEnd($event)"
        >
            @foreach($articles as $article)
            <div class="w-full flex-shrink-0 px-4 sm:w-1/2 lg:w-1/3">
                <article class="flex flex-col items-start justify-between h-full">
                    <div class="relative w-full">
                        <img 
                            src="{{ $article['image'] }}" 
                            alt="{{ $article['title'] }}" 
                            class="aspect-video w-full rounded-md bg-[var(--color-surface-variant)] object-cover sm:aspect-2/1 lg:aspect-3/2"
                            loading="lazy"
                        />
                    </div>
                    <div class="flex max-w-xl grow flex-col justify-between w-full">
                        <div class="mt-8 flex items-center gap-x-4 text-xs">
                            <time datetime="{{ $article['date']->format('Y-m-d') }}">
                                {{ $article['date']->format('M d, Y') }}
                            </time>
                            <span class="relative z-10 text-xs rounded-md bg-[var(--color-purple)] text-white px-1.5 py-1 font-medium">
                                {{ $article['category'] }}
                            </span>
                        </div>
                        <div class="relative grow">
                            <h3 class="mt-3 font-semibold">
                                <a href="{{ $article['url'] ?? '#' }}">
                                    <span class="absolute inset-0"></span>
                                    {{ $article['title'] }}
                                </a>
                            </h3>
                            <p class="mt-5 line-clamp-3 text-sm text-[var(--color-on-surface-variant)]">
                                {{ $article['description'] }}
                            </p>
                        </div>
                        <div class="relative mt-8 flex items-center gap-x-4 justify-self-end">
                            <img src="{{ $article['author_avatar'] ?? 'https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }}" alt="" class="size-10 rounded-full bg-[var(--color-surface-variant)]" />
                            <div class="text-sm">
                                <p class="font-semibold text-[var(--color-on-surface)]">
                                    {{ $article['author'] ?? 'Open Overheid Team' }}
                                </p>
                                <p>Redactie</p>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            @endforeach
            
            <!-- Show More Card -->
            <div class="w-full flex-shrink-0 px-4 sm:w-1/2 lg:w-1/3">
                <article class="flex flex-col items-center justify-center h-full min-h-[400px] rounded-md bg-gradient-to-br from-[var(--color-primary)]/10 via-[var(--color-primary)]/5 to-transparent p-8 group hover:from-[var(--color-primary)]/20 hover:via-[var(--color-primary)]/10 transition-all duration-200 cursor-pointer">
                    <div class="flex flex-col items-center justify-center text-center space-y-4">
                        <div class="w-16 h-16 rounded-full bg-[var(--color-primary)]/20 flex items-center justify-center group-hover:bg-[var(--color-primary)]/30 transition-colors duration-200">
                            <svg class="w-8 h-8 text-[var(--color-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xl font-semibold text-[var(--color-on-surface)] group-hover:text-[var(--color-primary)] transition-colors duration-200">
                                Meer artikelen
                            </p>
                            <p class="text-sm text-[var(--color-on-surface-variant)] mt-2">
                                Bekijk alle blog posts
                            </p>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
    
    <!-- Navigation Buttons -->
    <div class="flex items-center justify-center gap-4 mt-8">
        <button 
            @click="prev()"
            :disabled="currentIndex === 0"
            :class="currentIndex === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-[var(--color-primary)]/10'"
            class="p-2 rounded-full border border-slate-200/60 transition-colors duration-200"
        >
            <svg class="w-6 h-6 text-[var(--color-on-surface)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button 
            @click="next()"
            :disabled="currentIndex >= maxIndex"
            :class="currentIndex >= maxIndex ? 'opacity-50 cursor-not-allowed' : 'hover:bg-[var(--color-primary)]/10'"
            class="p-2 rounded-full border border-slate-200/60 transition-colors duration-200"
        >
            <svg class="w-6 h-6 text-[var(--color-on-surface)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</div>
