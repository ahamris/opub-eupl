@props([
    'badge' => null,
    'badgeText' => null,
    'title',
    'description' => null,
    'documentCount' => 0,
])

<div class="relative isolate bg-surface" style="z-index: 1; position: relative;">
    <svg aria-hidden="true" class="absolute inset-0 -z-10 size-full mask-[radial-gradient(100%_100%_at_top_right,white,transparent)] stroke-outline-variant">
        <defs>
            <pattern id="hero-pattern-{{ uniqid() }}" width="200" height="200" x="50%" y="-1" patternUnits="userSpaceOnUse">
                <path d="M.5 200V.5H200" fill="none" />
            </pattern>
        </defs>
        <svg x="50%" y="-1" class="overflow-visible fill-surface-variant">
            <path d="M-200 0h201v201h-201Z M600 0h201v201h-201Z M-400 600h201v201h-201Z M200 800h201v201h-201Z" stroke-width="0" />
        </svg>
        <rect width="100%" height="100%" fill="url(#hero-pattern-{{ uniqid() }})" stroke-width="0" />
    </svg>
    <div aria-hidden="true" class="absolute top-10 left-[calc(50%-4rem)] -z-10 transform-gpu blur-3xl sm:left-[calc(50%-18rem)] lg:top-[calc(50%-30rem)] lg:left-48 xl:left-[calc(50%-24rem)]">
        <div style="clip-path: polygon(73.6% 51.7%, 91.7% 11.8%, 100% 46.4%, 97.4% 82.2%, 92.5% 84.9%, 75.7% 64%, 55.3% 47.5%, 46.5% 49.4%, 45% 62.9%, 50.3% 87.2%, 21.3% 64.1%, 0.1% 100%, 5.4% 51.1%, 21.4% 63.9%, 58.9% 0.2%, 73.6% 51.7%)" 
             class="aspect-1108/632 w-277 bg-gradient-to-r from-primary/30 to-primary/20 opacity-20"></div>
    </div>
    <div class="mx-auto max-w-7xl px-6 pt-8 pb-8 sm:pb-12 lg:px-8 lg:pt-12 lg:pb-16" style="position: relative; z-index: 1;">
        <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-none" style="position: relative;">
            @if($badge || $badgeText)
            <div class="mb-6">
                <div class="inline-flex space-x-6">
                    @if($badge)
                    <span class="rounded-full bg-primary-container px-3 py-1 text-sm/6 font-semibold text-on-primary-container ring-1 ring-primary/20 ring-inset">{{ $badge }}</span>
                    @endif
                    @if($badgeText)
                    <span class="inline-flex items-center space-x-2 text-sm/6 font-medium text-on-surface-variant">
                        <span>{{ $badgeText }}</span>
                    </span>
                    @endif
                </div>
            </div>
            @endif
            
            <h1 class="text-4xl font-semibold tracking-tight text-pretty text-on-surface sm:text-5xl lg:text-6xl">
                {{ $title }}
            </h1>
            
            @if($description)
            <p class="mt-4 text-base font-medium text-pretty text-on-surface-variant sm:text-lg/7">
                {{ $description }}
            </p>
            @endif
            
            <!-- Live Search Section -->
            <div class="mt-8 relative search-container" x-data="liveSearch()" style="z-index: 99999 !important; position: relative !important; isolation: isolate !important;" @click.outside="showResults = false">
                <div class="relative" style="position: relative;">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i class="fas fa-search text-on-surface-variant/60" aria-hidden="true"></i>
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
                        class="w-full pl-12 pr-4 py-4 rounded-xl 
                               border-2 border-outline bg-surface
                               text-body-large text-on-surface
                               focus:border-primary focus:outline-2 focus:outline-primary focus:outline-offset-2
                               transition-colors duration-200
                               min-h-[56px]
                               shadow-lg
                               placeholder:text-on-surface-variant"
                        placeholder="Zoek in {{ number_format($documentCount, 0, ',', '.') }} documenten..."
                        autocomplete="off"
                        aria-label="Zoek documenten"
                        :aria-expanded="showResults ? 'true' : 'false'"
                        aria-haspopup="listbox"
                        aria-autocomplete="list"
                    >
                    <div x-show="loading" class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                        <svg class="animate-spin h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Search Results Dropdown -->
                <div 
                    x-show="showResults && (query.length >= 2 || loading)"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="absolute w-full mt-2 bg-surface rounded-xl shadow-2xl border border-outline-variant max-h-[600px] overflow-y-auto search-dropdown"
                    role="listbox"
                    @click.stop
                    style="z-index: 99999 !important; position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; margin-top: 0.5rem !important;"
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
                    <!-- Autocomplete Suggestions (Prioritized First) -->
                    <template x-if="suggestions.length > 0">
                        <div>
                            <div class="px-4 py-2 border-b border-outline-variant bg-surface-variant">
                                <p class="text-xs font-medium text-on-surface-variant uppercase tracking-wide">Suggesties</p>
                            </div>
                            <ul class="py-1" role="listbox">
                                <template x-for="(suggestion, index) in suggestions" :key="index">
                                    <li 
                                        @click="selectSuggestion(suggestion)"
                                        @mouseenter="selectedIndex = index"
                                        :class="selectedIndex === index ? 'bg-primary-container' : 'hover:bg-surface-variant'"
                                        class="px-4 py-2.5 cursor-pointer transition-colors duration-150"
                                        role="option"
                                        :aria-selected="selectedIndex === index"
                                    >
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-lightbulb text-primary shrink-0" aria-hidden="true"></i>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-medium text-on-surface" x-html="suggestion.highlight || suggestion.query"></div>
                                            </div>
                                            <i class="fas fa-chevron-right text-on-surface-variant/60 shrink-0 text-xs" aria-hidden="true"></i>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                    
                    <!-- Search Results -->
                    <template x-if="results.length > 0">
                        <div>
                            <div class="px-4 py-2 border-b border-outline-variant bg-surface-variant">
                                <p class="text-xs font-medium text-on-surface-variant">
                                    <span x-text="found"></span> resultaten gevonden
                                    <template x-if="searchTime > 0">
                                        <span class="text-on-surface-variant/60"> in <span x-text="searchTime"></span>ms</span>
                                    </template>
                                </p>
                            </div>
                            <ul class="py-2" role="listbox">
                                <template x-for="(result, index) in results" :key="result.id">
                                    <li 
                                        @click="goToDetail(result.id)"
                                        @mouseenter="selectedIndex = suggestions.length + index"
                                        :class="selectedIndex === (suggestions.length + index) ? 'bg-primary-container' : 'hover:bg-surface-variant'"
                                        class="px-4 py-3 cursor-pointer transition-colors duration-150"
                                        role="option"
                                        :aria-selected="selectedIndex === (suggestions.length + index)"
                                    >
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-2 mb-1">
                                                    <h3 class="text-base font-semibold text-on-surface truncate flex-1" x-text="result.title"></h3>
                                                    <template x-if="result.formatted_category || result.category">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary shrink-0">
                                                            <span x-text="result.formatted_category || result.category"></span>
                                                        </span>
                                                    </template>
                                                </div>
                                                <p class="mt-1 text-sm text-on-surface-variant line-clamp-2" x-text="result.description"></p>
                                                <div class="mt-2 flex items-center gap-4 text-xs text-on-surface-variant/80">
                                                    <template x-if="result.organisation">
                                                        <span class="inline-flex items-center gap-1">
                                                            <i class="fas fa-building" aria-hidden="true"></i>
                                                            <span x-text="result.organisation"></span>
                                                        </span>
                                                    </template>
                                                    <template x-if="result.publication_date">
                                                        <span class="inline-flex items-center gap-1">
                                                            <i class="fas fa-calendar" aria-hidden="true"></i>
                                                            <span x-text="formatDate(result.publication_date)"></span>
                                                        </span>
                                                    </template>
                                                </div>
                                            </div>
                                            <i class="fas fa-chevron-right text-on-surface-variant/60 shrink-0 mt-1" aria-hidden="true"></i>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                    
                    <!-- Footer with "View all results" -->
                    <template x-if="(suggestions.length > 0 || results.length > 0) && query.length >= 2">
                        <div class="px-4 py-3 border-t border-outline-variant bg-surface-variant">
                            <a 
                                :href="'{{ route('zoeken') }}?zoeken=' + encodeURIComponent(query)"
                                class="text-sm font-medium text-primary hover:underline inline-flex items-center gap-2"
                            >
                                Bekijk alle resultaten
                                <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                            </a>
                        </div>
                    </template>
                    
                    <!-- No Results -->
                    <template x-if="suggestions.length === 0 && results.length === 0 && query.length >= 2 && !loading">
                        <div class="px-4 py-8 text-center">
                            <p class="text-sm text-on-surface-variant">Geen resultaten gevonden voor "<span x-text="query"></span>"</p>
                            <a 
                                :href="'{{ route('zoeken') }}?zoeken=' + encodeURIComponent(query)"
                                class="mt-4 text-sm font-medium text-primary hover:underline inline-flex items-center gap-2"
                            >
                                Toch zoeken
                                <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
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
