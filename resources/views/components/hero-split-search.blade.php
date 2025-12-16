@props([
    'badge' => null,
    'badgeText' => null,
    'title',
    'description' => null,
    'documentCount' => 0,
])

<div class="relative isolate pt-14 bg-white">
    <!-- Subtle background pattern (slightly more visible but still light) -->
    <svg aria-hidden="true" class="absolute inset-0 -z-10 size-full mask-[radial-gradient(100%_100%_at_top_right,white,transparent)] stroke-gray-200 dark:stroke-white/10">
        <defs>
            <pattern id="hero-pattern" width="200" height="200" x="50%" y="-1" patternUnits="userSpaceOnUse">
                <path d="M100 200V.5M.5 .5H200" fill="none" />
            </pattern>
        </defs>
        <svg x="50%" y="-1" class="overflow-visible fill-gray-50 dark:fill-gray-800/50">
            <path d="M-100.5 0h201v201h-201Z M699.5 0h201v201h-201Z M499.5 400h201v201h-201Z M-300.5 600h201v201h-201Z" stroke-width="0" />
        </svg>
        <rect width="100%" height="100%" fill="url(#hero-pattern)" stroke-width="0" />
    </svg>

    <div class="mx-auto max-w-7xl px-6 pt-40 pb-40 lg:flex lg:items-center lg:gap-x-10 lg:px-8">
        <!-- Left side: Text content -->
        <div class="max-w-2xl lg:flex-auto">
            @if($badge || $badgeText)
            <div class="flex">
                <div class="relative flex items-center gap-x-4 rounded-full bg-white px-4 py-1 text-[var(--font-size-body-small)] text-[var(--color-on-surface-variant)] ring-1 ring-gray-900/10 hover:ring-gray-900/20 transition-all duration-200">
                    @if($badge)
                    <span class="font-semibold text-[var(--color-primary)]">{{ $badge }}</span>
                    @endif
                    @if($badge && $badgeText)
                    <span aria-hidden="true" class="h-4 w-px bg-gray-900/10"></span>
                    @endif
                    @if($badgeText)
                    <a href="#" class="flex items-center gap-x-1 hover:text-[var(--color-primary)] transition-colors duration-200">
                        <span aria-hidden="true" class="absolute inset-0"></span>
                        {{ $badgeText }}
                        <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="-mr-2 size-5 text-gray-400">
                            <path d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
            @endif
            
            <h1 class="mt-10 text-[var(--font-size-display-large)] font-bold tracking-[-0.02em] text-pretty text-[var(--color-on-surface)] leading-[var(--line-height-tight)]">
                {{ $title }}
            </h1>
            
            @if($description)
            <p class="mt-6 text-[var(--font-size-body-large)] font-normal text-pretty text-[var(--color-on-surface-variant)] leading-[var(--line-height-relaxed)]">{{ $description }}</p>
            @endif
            
            <div class="mt-6 text-[var(--font-size-body-medium)] font-medium text-[var(--color-on-surface-variant)]">
                <span x-data="liveDocumentCounter({{ $documentCount }})">
                    <span class="inline-flex items-center gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-[var(--color-primary)] animate-pulse" aria-hidden="true"></span>
                        <span class="font-semibold text-[var(--color-on-surface)]" x-text="formatNumber(count)"></span>
                        <span>documenten beschikbaar</span>
                    </span>
                </span>
            </div>
        </div>
        
        <!-- Right side: Search functionality -->
        <div class="mt-16 sm:mt-24 lg:mt-0 lg:shrink-0 lg:grow">
            <div class="relative z-9999 isolate" x-data="liveSearch()" @click.outside="showResults = false">
                <div class="mx-auto rounded-md bg-white p-6 shadow-xl ring-1 ring-gray-900/10 dark:bg-gray-900 dark:ring-white/10">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Slim zoeken met</p>
                        <a href="{{ route('chat') }}" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-primary)] px-3.5 py-1.5 text-xs font-semibold text-[var(--color-on-primary)] shadow-xs hover:bg-[var(--color-primary-dark)] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)]">
                            <i class="fas fa-comments text-[0.9rem]" aria-hidden="true"></i>
                            <span>Chat met AI</span>
                        </a>
                    </div>
                    <div class="relative mt-2">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 z-10">
                            <i class="fas fa-search text-[var(--color-on-surface-variant)] dark:text-[var(--color-on-surface-variant)] text-sm" aria-hidden="true"></i>
                        </div>
                        <input 
                            type="text" 
                            x-model="query"
                            @input="handleInput()"
                            @focus="handleFocus()"
                            @keydown.escape="showResults = false; query = ''"
                            @keydown.arrow-down.prevent="navigateResults(1)"
                            @keydown.arrow-up.prevent="navigateResults(-1)"
                            @keydown.enter.prevent="selectResult()"
                            class="block w-full rounded-md bg-white px-3 py-2 pl-10 pr-10 text-base text-[var(--color-on-surface)] outline-1 -outline-offset-1 outline-[var(--color-outline-variant)] placeholder:text-[var(--color-on-surface-variant)] focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-primary)] sm:text-sm/6 dark:bg-white/5 dark:text-[var(--color-on-surface)] dark:outline-white/10 dark:placeholder:text-[var(--color-on-surface-variant)] dark:focus:outline-[var(--color-primary)]"
                            placeholder="Zoek in alle documenten"
                            autocomplete="off"
                            aria-label="Zoek documenten"
                            :aria-expanded="showResults ? 'true' : 'false'"
                            aria-haspopup="listbox"
                            aria-autocomplete="list"
                        >
                        <div x-show="loading" class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 z-10">
                            <i class="fas fa-circle-notch animate-spin text-[var(--color-primary)] dark:text-[var(--color-primary)] text-sm" aria-hidden="true"></i>
                        </div>
                    </div>
                
                <!-- Dropdown: matches input width, minimal & clean -->
                <div
                    x-show="showResults && (query.length >= 2 || loading)"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute inset-x-0 top-full left-0 right-0 mt-2 bg-white rounded-md shadow-lg ring-1 ring-gray-900/5 border border-gray-100 max-h-80 overflow-y-auto z-[99999]"
                    role="listbox"
                    @click.stop
                >
                    <!-- Autocomplete Suggestions (Prioritized First) - Apple Level -->
                    <template x-if="suggestions.length > 0">
                        <div>
                            <div class="px-4 py-2 border-b border-gray-100 bg-gray-50/60">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Suggesties</p>
                            </div>
                            <ul class="py-1" role="listbox">
                                <template x-for="(suggestion, index) in suggestions" :key="index">
                                    <li 
                                        @click="selectSuggestion(suggestion)"
                                        @mouseenter="selectedIndex = index"
                                        :class="selectedIndex === index ? 'bg-gray-50' : 'hover:bg-gray-50'"
                                        class="px-4 py-2 cursor-pointer transition-colors duration-100"
                                        role="option"
                                        :aria-selected="selectedIndex === index"
                                    >
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-bolt text-xs text-[var(--color-primary)] flex-shrink-0" aria-hidden="true"></i>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-medium text-gray-900 leading-snug" x-html="suggestion.highlight || suggestion.query"></div>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                    
                    <!-- Search Results - Apple Level Design -->
                    <template x-if="results.length > 0">
                        <div>
                            <div class="px-4 py-2 border-b border-gray-100 bg-gray-50/60">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    <span x-text="found"></span> resultaten gevonden
                                    <template x-if="searchTime > 0">
                                        <span class="text-gray-400"> in <span x-text="searchTime"></span>ms</span>
                                    </template>
                                </p>
                            </div>
                            <ul class="py-1" role="listbox">
                                <template x-for="(result, index) in results" :key="result.id">
                                    <li 
                                        @click="goToDetail(result.id)"
                                        @mouseenter="selectedIndex = suggestions.length + index"
                                        :class="selectedIndex === (suggestions.length + index) ? 'bg-gray-50' : 'hover:bg-gray-50'"
                                        class="px-4 py-3 cursor-pointer transition-colors duration-100 border-b border-gray-100 last:border-b-0"
                                        role="option"
                                        :aria-selected="selectedIndex === (suggestions.length + index)"
                                    >
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-3 mb-1.5">
                                                    <h3 class="text-sm font-medium text-gray-900 truncate flex-1 leading-snug" x-text="result.title"></h3>
                                                    <template x-if="result.formatted_category || result.category">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-[var(--color-primary-light)] text-[var(--color-primary)] shrink-0">
                                                            <span x-text="result.formatted_category || result.category"></span>
                                                        </span>
                                                    </template>
                                                </div>
                                                <p class="mt-1 text-xs text-gray-600 line-clamp-2 leading-snug" x-text="result.description"></p>
                                                <div class="mt-2 flex items-center gap-4 text-[11px] text-gray-500">
                                                    <template x-if="result.organisation">
                                                        <span class="inline-flex items-center gap-1.5">
                                                            <i class="fas fa-building text-[10px]" aria-hidden="true"></i>
                                                            <span class="tracking-tight" x-text="result.organisation"></span>
                                                        </span>
                                                    </template>
                                                    <template x-if="result.publication_date">
                                                        <span class="inline-flex items-center gap-1.5">
                                                            <i class="fas fa-calendar text-[10px]" aria-hidden="true"></i>
                                                            <span class="tracking-tight" x-text="formatDate(result.publication_date)"></span>
                                                        </span>
                                                    </template>
                                                </div>
                                            </div>
                                            <i class="fas fa-chevron-right text-gray-300 shrink-0 mt-1 text-xs" aria-hidden="true"></i>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                    
                    <!-- Footer with "View all results" -->
                    <template x-if="(suggestions.length > 0 || results.length > 0) && query.length >= 2">
                        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/80">
                            <a 
                                :href="'{{ route('zoeken') }}?zoeken=' + encodeURIComponent(query)"
                                class="text-sm font-semibold text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] transition-colors duration-150 inline-flex items-center gap-2 group"
                            >
                                Bekijk alle resultaten
                                <i class="fas fa-arrow-right text-xs transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true"></i>
                            </a>
                        </div>
                    </template>
                    
                    <!-- No Results -->
                    <template x-if="suggestions.length === 0 && results.length === 0 && query.length >= 2 && !loading">
                        <div class="px-4 py-6 text-center text-sm">
                            <p class="text-gray-600 mb-3">Geen resultaten gevonden voor "<span class="font-medium text-gray-900" x-text="query"></span>"</p>
                            <a 
                                :href="'{{ route('zoeken') }}?zoeken=' + encodeURIComponent(query)"
                                class="text-sm font-semibold text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] transition-colors duration-150 inline-flex items-center gap-2 group"
                            >
                                Toch zoeken
                                <i class="fas fa-arrow-right text-xs transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true"></i>
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
// Live Document Counter - Updates periodically to show real-time document count
function liveDocumentCounter(initialCount) {
    return {
        count: initialCount,
        interval: null,
        
        init() {
            // Update every 30 seconds to show live count
            this.interval = setInterval(() => {
                this.updateCount();
            }, 30000);
            
            // Initial update after 5 seconds
            setTimeout(() => {
                this.updateCount();
            }, 5000);
        },
        
        async updateCount() {
            try {
                const response = await fetch('{{ route("api.live-search") }}?q=&limit=1');
                if (response.ok) {
                    const data = await response.json();
                    if (data.total_found !== undefined) {
                        // Smooth transition to new count
                        const targetCount = data.total_found;
                        this.animateCount(this.count, targetCount);
                        
                        // Update page title if it exists
                        const titleElement = document.querySelector('title');
                        if (titleElement && titleElement.__x) {
                            titleElement.__x.$data.documentCount = targetCount;
                        }
                    }
                }
            } catch (error) {
                console.error('Failed to update document count:', error);
            }
        },
        
        animateCount(from, to) {
            const duration = 1000;
            const startTime = Date.now();
            const difference = to - from;
            
            const animate = () => {
                const elapsed = Date.now() - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing function for smooth animation
                const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                this.count = Math.round(from + (difference * easeOutQuart));
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    this.count = to;
                }
            };
            
            animate();
        },
        
        formatNumber(num) {
            return new Intl.NumberFormat('nl-NL').format(num);
        },
        
        destroy() {
            if (this.interval) {
                clearInterval(this.interval);
            }
        }
    }
}

function liveSearch() {
    return {
        query: '',
        suggestions: [],
        results: [],
        found: 0,
        searchTime: 0,
        loading: false,
        showResults: false,
        selectedIndex: -1,
        searchTimeout: null,
        
        handleInput() {
            if (this.query.length >= 2) {
                this.showResults = true;
            }
            
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            
            this.searchTimeout = setTimeout(() => {
                this.search();
            }, 200);
        },
        
        handleFocus() {
            if (this.query.length >= 2) {
                this.showResults = true;
                if (this.results.length === 0 && this.suggestions.length === 0 && !this.loading) {
                    this.search();
                }
            }
        },
        
        async search() {
            if (this.query.length < 2) {
                this.suggestions = [];
                this.results = [];
                this.found = 0;
                this.showResults = false;
                this.loading = false;
                return;
            }
            
            this.loading = true;
            this.selectedIndex = -1;
            this.showResults = true;
            
            try {
                // Fetch both autocomplete suggestions and search results in parallel
                const [suggestionsResponse, searchResponse] = await Promise.all([
                    fetch(`{{ route('api.autocomplete') }}?q=${encodeURIComponent(this.query)}&limit=3`),
                    fetch(`{{ route('api.live-search') }}?q=${encodeURIComponent(this.query)}&limit=5`)
                ]);
                
                // Process autocomplete suggestions first (prioritized)
                if (suggestionsResponse.ok) {
                    const suggestionsData = await suggestionsResponse.json();
                    this.suggestions = (suggestionsData.suggestions || []).map(suggestion => ({
                        ...suggestion,
                        isSuggestion: true
                    }));
                }
                
                // Process search results
                if (searchResponse.ok) {
                    const searchData = await searchResponse.json();
                    this.results = searchData.hits || [];
                    this.found = searchData.found || 0;
                    this.searchTime = searchData.search_time_ms || 0;
                }
                
                this.showResults = true;
            } catch (error) {
                console.error('Search error:', error);
                this.suggestions = [];
                this.results = [];
                this.found = 0;
                this.showResults = true;
            } finally {
                this.loading = false;
            }
        },
        
        get allItems() {
            // Prioritize suggestions first, then results
            return [...this.suggestions, ...this.results];
        },
        
        navigateResults(direction) {
            const items = this.allItems;
            if (items.length === 0) return;
            
            this.selectedIndex += direction;
            if (this.selectedIndex < 0) {
                this.selectedIndex = items.length - 1;
            } else if (this.selectedIndex >= items.length) {
                this.selectedIndex = 0;
            }
        },
        
        selectResult() {
            const items = this.allItems;
            if (this.selectedIndex >= 0 && items[this.selectedIndex]) {
                const item = items[this.selectedIndex];
                if (item.isSuggestion && item.query) {
                    // Autocomplete suggestion - navigate to search page
                    window.location.href = `{{ route('zoeken') }}?zoeken=${encodeURIComponent(item.query)}`;
                } else if (item.id) {
                    // Document result - navigate to document detail
                    this.goToDetail(item.id);
                }
            } else if (this.query.length >= 2) {
                window.location.href = `{{ route('zoeken') }}?zoeken=${encodeURIComponent(this.query)}`;
            }
        },
        
        selectSuggestion(suggestion) {
            if (suggestion.id) {
                // If suggestion has an ID, go directly to the document
                this.goToDetail(suggestion.id);
            } else if (suggestion.query) {
                // Update search input with suggestion query and trigger live search
                this.query = suggestion.query;
                // Trigger live search immediately
                this.search();
            }
        },
        
        goToDetail(id) {
            if (id) {
                window.location.href = `/open-overheid/documents/${id}`;
            }
        },
        
        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('nl-NL', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        },
    }
}
</script>
@endpush
