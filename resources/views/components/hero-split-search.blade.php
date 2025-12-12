@props([
    'badge' => null,
    'badgeText' => null,
    'title',
    'description' => null,
    'documentCount' => 0,
])

<div class="relative isolate pt-14 bg-gradient-to-br from-[var(--color-primary-light)] via-white to-[var(--color-primary-light)]/30">
    <!-- Subtle background pattern -->
    <svg aria-hidden="true" class="absolute inset-0 -z-10 size-full mask-[radial-gradient(100%_100%_at_top_right,white,transparent)] stroke-[var(--color-primary)]/10">
        <defs>
            <pattern id="hero-pattern" width="200" height="200" x="50%" y="-1" patternUnits="userSpaceOnUse">
                <path d="M100 200V.5M.5 .5H200" fill="none" />
            </pattern>
        </defs>
        <svg x="50%" y="-1" class="overflow-visible fill-[var(--color-primary-light)]/20">
            <path d="M-100.5 0h201v201h-201Z M699.5 0h201v201h-201Z M499.5 400h201v201h-201Z M-300.5 600h201v201h-201Z" stroke-width="0" />
        </svg>
        <rect width="100%" height="100%" fill="url(#hero-pattern)" stroke-width="0" />
    </svg>
    
    <!-- Decorative gradient overlay -->
    <div aria-hidden="true" class="absolute top-0 right-0 -z-10 w-1/3 h-full bg-gradient-to-l from-[var(--color-primary)]/5 to-transparent"></div>
    
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
            <div class="relative search-container" x-data="liveSearch()" style="z-index: 99999 !important; position: relative !important; isolation: isolate !important;" @click.outside="showResults = false">
                <div class="rounded-2xl bg-white/95 backdrop-blur-sm p-6 shadow-xl ring-1 ring-[var(--color-primary)]/10 border border-[var(--color-primary)]/5">
                    <div class="mb-4 flex items-center justify-between">
                        <p class="text-[var(--font-size-body-medium)] font-medium text-[var(--color-on-surface)]">Slim zoeken met</p>
                        <a href="{{ route('chat') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[var(--color-primary)] text-white text-[var(--font-size-body-small)] font-medium hover:bg-[var(--color-primary-dark)] transition-colors duration-200">
                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-4">
                                <path d="M3.5 2.75a.75.75 0 0 0-1.5 0v14.5a.75.75 0 0 0 1.5 0v-4.392l1.657-.348a6.449 6.449 0 0 1 4.271.572 7.948 7.948 0 0 0 5.965.44l2.087-.694a.75.75 0 0 0 .42-.941l-.8-2.385a.75.75 0 0 0-.42-.499l-2.087-.694a7.948 7.948 0 0 0-5.965.44 6.449 6.449 0 0 1-4.271.572L3.5 7.25v-4.5Z" />
                            </svg>
                            <span>Chat met AI</span>
                        </a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none z-10">
                            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-5 text-[var(--color-on-surface-variant)]">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19 19-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
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
                            class="w-full pl-12 pr-12 py-4 rounded-lg
                                   border-0 bg-white
                                   text-[var(--font-size-body-large)] text-[var(--color-on-surface)] font-normal
                                   focus:outline-2 focus:outline-offset-2 focus:outline-[var(--color-primary)]
                                   transition-all duration-200 ease-out
                                   shadow-sm
                                   placeholder:text-[var(--color-on-surface-variant)]
                                   ring-1 ring-inset ring-gray-900/10"
                            placeholder="Vul je zoekterm in"
                            autocomplete="off"
                            aria-label="Zoek documenten"
                            :aria-expanded="showResults ? 'true' : 'false'"
                            aria-haspopup="listbox"
                            aria-autocomplete="list"
                        >
                        <div x-show="loading" class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                            <svg class="animate-spin h-5 w-5 text-[var(--color-primary)]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                
                <!-- Apple/Google Level: Premium dropdown - Fully contained within hero -->
                <div 
                    x-show="showResults && (query.length >= 2 || loading)"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform translate-y-[-8px]"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform translate-y-[-8px]"
                    class="absolute w-full mt-3 bg-white rounded-md shadow-2xl ring-1 ring-gray-900/10 max-h-[500px] overflow-y-auto search-dropdown"
                    role="listbox"
                    @click.stop
                    style="z-index: 99999 !important; position: absolute !important; top: calc(100% + 0.75rem) !important; left: 0 !important; right: 0 !important; margin-bottom: 2rem !important;"
                    x-ref="dropdown"
                    x-init="$watch('showResults', value => {
                        if (value && $refs.dropdown) {
                            const rect = $refs.dropdown.getBoundingClientRect();
                            const containerRect = $el.getBoundingClientRect();
                            if (rect.bottom > window.innerHeight) {
                                $refs.dropdown.style.maxHeight = (window.innerHeight - containerRect.bottom - 20) + 'px';
                            }
                        }
                    })"
                >
                    <!-- Autocomplete Suggestions (Prioritized First) - Apple Level -->
                    <template x-if="suggestions.length > 0">
                        <div>
                            <div class="px-5 py-3 border-b border-gray-900/10 bg-gray-50">
                                <p class="text-[var(--font-size-label-small)] font-semibold text-gray-600 uppercase tracking-wider">Suggesties</p>
                            </div>
                            <ul class="py-2" role="listbox">
                                <template x-for="(suggestion, index) in suggestions" :key="index">
                                    <li 
                                        @click="selectSuggestion(suggestion)"
                                        @mouseenter="selectedIndex = index"
                                        :class="selectedIndex === index ? 'bg-[var(--color-primary-light)]' : 'hover:bg-gray-50'"
                                        class="px-5 py-3 cursor-pointer transition-all duration-150 ease-out active:scale-[0.98]"
                                        role="option"
                                        :aria-selected="selectedIndex === index"
                                    >
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0 w-8 h-8 rounded bg-[var(--color-primary-light)] flex items-center justify-center">
                                                <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-4 text-[var(--color-primary)]">
                                                    <path d="M10.43 2.3a.75.75 0 0 0-.86 0l-3 1.85a.75.75 0 0 0-.39.58l-.87 3.38A.75.75 0 0 1 4.57 8.5l-.5-.87a.75.75 0 0 0-1.14-.1L1.72 8.9a.75.75 0 0 1-1.1-.64l.35-2.5a.75.75 0 0 0-.43-.75L.84 4.83a.75.75 0 0 1 .28-1.35l2.5-.73a.75.75 0 0 0 .58-.39L5.98.85a.75.75 0 0 1 1.04 0l1.85 3 3.38-.87a.75.75 0 0 1 .75.43l.73 2.5a.75.75 0 0 1-1.35.28l-1.38-1.38a.75.75 0 0 0-1.1.1l.87.5a.75.75 0 1 1-.87 1.26l-3.38.87a.75.75 0 0 1-.39-.58L6.3 4.43a.75.75 0 0 1 .3-.58l2.83-2.55Zm1.14 4.4a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0V7.81l-5.22 5.22a.75.75 0 0 1-1.06-1.06l5.22-5.22h-2.69a.75.75 0 0 1-.75-.75Z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[var(--font-size-body-medium)] font-medium text-[var(--color-on-surface)] leading-[var(--line-height-normal)]" x-html="suggestion.highlight || suggestion.query"></div>
                                            </div>
                                            <i class="fas fa-chevron-right text-gray-400 shrink-0 text-[var(--font-size-label-small)] transition-transform duration-200 group-hover:translate-x-0.5" aria-hidden="true"></i>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                    
                    <!-- Search Results - Apple Level Design -->
                    <template x-if="results.length > 0">
                        <div>
                            <div class="px-5 py-3 border-b border-gray-900/10 bg-gray-50">
                                <p class="text-[var(--font-size-body-medium)] font-semibold text-gray-600">
                                    <span x-text="found"></span> resultaten gevonden
                                    <template x-if="searchTime > 0">
                                        <span class="text-gray-500"> in <span x-text="searchTime"></span>ms</span>
                                    </template>
                                </p>
                            </div>
                            <ul class="py-2" role="listbox">
                                <template x-for="(result, index) in results" :key="result.id">
                                    <li 
                                        @click="goToDetail(result.id)"
                                        @mouseenter="selectedIndex = suggestions.length + index"
                                        :class="selectedIndex === (suggestions.length + index) ? 'bg-[var(--color-primary-light)]' : 'hover:bg-gray-50'"
                        class="px-5 py-4 cursor-pointer transition-all duration-150 ease-out active:scale-[0.98] border-b border-gray-900/10 last:border-b-0"
                                        role="option"
                                        :aria-selected="selectedIndex === (suggestions.length + index)"
                                    >
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-3 mb-2">
                                                    <h3 class="text-[var(--font-size-body-medium)] font-semibold text-gray-900 truncate flex-1 leading-[var(--line-height-normal)]" x-text="result.title"></h3>
                                                    <template x-if="result.formatted_category || result.category">
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded text-[var(--font-size-label-small)] font-semibold bg-[var(--color-primary-light)] text-[var(--color-primary)] shrink-0">
                                                            <span x-text="result.formatted_category || result.category"></span>
                                                        </span>
                                                    </template>
                                                </div>
                                                <p class="mt-1.5 text-[var(--font-size-body-small)] text-gray-600 line-clamp-2 leading-[var(--line-height-normal)]" x-text="result.description"></p>
                                                <div class="mt-3 flex items-center gap-5 text-[var(--font-size-label-small)] text-gray-500">
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
                                            <i class="fas fa-chevron-right text-gray-400 shrink-0 mt-1 text-[var(--font-size-label-small)] transition-transform duration-200 group-hover:translate-x-0.5" aria-hidden="true"></i>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                    
                    <!-- Footer with "View all results" -->
                    <template x-if="(suggestions.length > 0 || results.length > 0) && query.length >= 2">
                        <div class="px-5 py-4 border-t border-gray-900/10 bg-gray-50">
                            <a 
                                :href="'{{ route('zoeken') }}?zoeken=' + encodeURIComponent(query)"
                                class="text-[var(--font-size-body-medium)] font-semibold text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] transition-colors duration-200 inline-flex items-center gap-2 group"
                            >
                                Bekijk alle resultaten
                                <i class="fas fa-arrow-right text-[var(--font-size-label-small)] transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true"></i>
                            </a>
                        </div>
                    </template>
                    
                    <!-- No Results -->
                    <template x-if="suggestions.length === 0 && results.length === 0 && query.length >= 2 && !loading">
                        <div class="px-5 py-12 text-center">
                            <p class="text-[var(--font-size-body-medium)] text-gray-600 mb-4">Geen resultaten gevonden voor "<span class="font-medium text-gray-900" x-text="query"></span>"</p>
                            <a 
                                :href="'{{ route('zoeken') }}?zoeken=' + encodeURIComponent(query)"
                                class="text-[var(--font-size-body-medium)] font-semibold text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] transition-colors duration-200 inline-flex items-center gap-2 group"
                            >
                                Toch zoeken
                                <i class="fas fa-arrow-right text-[var(--font-size-label-small)] transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true"></i>
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .search-container {
        z-index: 99999 !important;
        isolation: isolate;
    }

    .search-dropdown {
        z-index: 99999 !important;
        position: absolute !important;
        top: 100%;
        left: 0;
        right: 0;
    }
</style>
@endpush

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
                // Otherwise, search for the suggestion query
                window.location.href = `{{ route('zoeken') }}?zoeken=${encodeURIComponent(suggestion.query)}`;
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
