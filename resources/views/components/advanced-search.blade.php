@props([
    'badge' => null,
    'badgeText' => null,
    'title',
    'description' => null,
    'documentCount' => 0,
])

<div class="relative isolate overflow-hidden bg-surface">
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
    <div class="mx-auto max-w-7xl px-6 pt-8 pb-16 sm:pb-20 lg:px-8 lg:pt-12 lg:pb-24">
        <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-none">
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
            
            <!-- Advanced Search with Autocomplete -->
            <div class="mt-8 relative z-[99999] isolate" id="advanced-search-container">
                <!-- Autocomplete input will be injected here -->
                <div id="autocomplete" class="relative"></div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@algolia/autocomplete-theme-classic@1.11.1/dist/theme.min.css" />
<style>
    .aa-Autocomplete {
        position: relative;
        z-index: 99999 !important;
    }
    
    .aa-InputWrapper {
        position: relative;
    }
    
    .aa-Input {
        width: 100%;
        padding: 1rem 3rem 1rem 3rem;
        font-size: 1rem;
        line-height: 1.5;
        border: 2px solid var(--color-outline);
        background: var(--color-surface);
        color: var(--color-on-surface);
        border-radius: 0.75rem;
        min-height: 56px;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        transition: all 200ms;
    }
    
    .aa-Input:focus {
        outline: 2px solid var(--color-primary);
        outline-offset: 2px;
        border-color: var(--color-primary);
    }
    
    .aa-Input::placeholder {
        color: var(--color-on-surface-variant);
    }
    
    .aa-InputWrapper::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23666'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'%3E%3C/path%3E%3C/svg%3E");
        background-size: contain;
        pointer-events: none;
        z-index: 10;
    }
    
    .aa-Panel {
        background: var(--color-surface) !important;
        border: 1px solid var(--color-outline-variant) !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important;
        margin-top: 0.5rem !important;
        max-height: 24rem !important;
        overflow-y: auto !important;
        z-index: 99999 !important;
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        right: 0 !important;
    }
    
    .aa-Item {
        padding: 0.75rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid var(--color-outline-variant);
    }
    
    .aa-Item:hover,
    .aa-Item[aria-selected="true"] {
        background: var(--color-primary-container);
    }
    
    .aa-ItemMark {
        background: var(--color-primary);
        color: white;
        padding: 0.125rem 0.25rem;
        border-radius: 0.25rem;
    }
    
    .aa-Source {
        border: none;
    }
    
    .aa-SourceHeader {
        padding: 0.75rem 1rem;
        background: var(--color-surface-variant);
        border-bottom: 1px solid var(--color-outline-variant);
        font-weight: 500;
        font-size: 0.875rem;
        color: var(--color-on-surface-variant);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@algolia/autocomplete-js@1.11.1/dist/umd/index.production.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const { autocomplete } = window['@algolia/autocomplete-js'];
    
    if (!autocomplete) {
        console.error('Autocomplete.js not loaded');
        return;
    }

    const searchEndpoint = '{{ route("api.live-search") }}';
    const autocompleteEndpoint = '{{ route("api.autocomplete") }}';

    autocomplete({
    container: '#autocomplete',
    placeholder: 'Zoek in {{ number_format($documentCount, 0, ",", ".") }} documenten...',
    openOnFocus: true,
    detachedMediaQuery: '',
    getSources({ query, state }) {
        // Show suggestions even with 1 character for better UX
        if (!query || query.length < 1) {
            return [];
        }
        
        return [
            {
                sourceId: 'suggestions',
                getItems() {
                    return fetch(`${autocompleteEndpoint}?q=${encodeURIComponent(query)}&limit=5`)
                        .then(response => response.json())
                        .then(data => data.suggestions || [])
                        .catch(() => []);
                },
                getItemUrl({ item }) {
                    return `/open-overheid/documents/${item.id || ''}`;
                },
                onSelect({ item, setIsOpen }) {
                    if (item.id) {
                        window.location.href = `/open-overheid/documents/${item.id}`;
                    } else if (item.query) {
                        window.location.href = `{{ route('zoeken') }}?zoeken=${encodeURIComponent(item.query)}`;
                    }
                    setIsOpen(false);
                },
                templates: {
                    header() {
                        return '<div class="aa-SourceHeader">Suggesties</div>';
                    },
                    item({ item, html }) {
                        return html`
                            <div class="aa-Item">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-search text-on-surface-variant/60 mt-0.5"></i>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-base font-semibold text-on-surface" innerHTML="${item.highlight || item.query}"></div>
                                        ${item.type === 'document' ? html`<div class="text-xs text-on-surface-variant/80 mt-1">Document</div>` : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                    },
                },
            },
            {
                sourceId: 'results',
                getItems() {
                    return fetch(`${searchEndpoint}?q=${encodeURIComponent(query)}&limit=5`)
                        .then(response => response.json())
                        .then(data => (data.hits || []).map(hit => ({
                            ...hit,
                            query: hit.title,
                            type: 'result',
                        })))
                        .catch(() => []);
                },
                getItemUrl({ item }) {
                    return `/open-overheid/documents/${item.id || ''}`;
                },
                onSelect({ item, setIsOpen }) {
                    if (item.id) {
                        window.location.href = `/open-overheid/documents/${item.id}`;
                    }
                    setIsOpen(false);
                },
                templates: {
                    header({ items }) {
                        if (items.length === 0) return '';
                        return `<div class="aa-SourceHeader">${items.length} resultaten gevonden</div>`;
                    },
                    item({ item, html }) {
                        return html`
                            <div class="aa-Item">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-semibold text-on-surface truncate">${item.title || 'Geen titel'}</h3>
                                        ${item.description ? html`<p class="mt-1 text-sm text-on-surface-variant line-clamp-2">${item.description}</p>` : ''}
                                        <div class="mt-2 flex items-center gap-4 text-xs text-on-surface-variant/80">
                                            ${item.organisation ? html`<span class="inline-flex items-center gap-1"><i class="fas fa-building"></i>${item.organisation}</span>` : ''}
                                            ${item.publication_date ? (() => {
                                                const date = new Date(item.publication_date);
                                                const formatted = date.toLocaleDateString('nl-NL', { year: 'numeric', month: 'long', day: 'numeric' });
                                                return html`<span class="inline-flex items-center gap-1"><i class="fas fa-calendar"></i>${formatted}</span>`;
                                            })() : ''}
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-on-surface-variant/60 flex-shrink-0 mt-1"></i>
                                </div>
                            </div>
                        `;
                    },
                    footer({ state }) {
                        if (!state.query || state.query.length < 2) return '';
                        return html`
                            <div class="px-4 py-3 border-t border-outline-variant bg-surface-variant">
                                <a href="{{ route('zoeken') }}?zoeken=${encodeURIComponent(state.query)}" 
                                   class="text-sm font-medium text-primary hover:underline inline-flex items-center gap-2">
                                    Bekijk alle resultaten
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        `;
                    },
                    noResults({ state }) {
                        if (!state.query || state.query.length < 2) return '';
                        return html`
                            <div class="px-4 py-8 text-center">
                                <p class="text-sm text-on-surface-variant">
                                    Geen resultaten gevonden voor "${state.query}"
                                </p>
                                <a href="{{ route('zoeken') }}?zoeken=${encodeURIComponent(state.query)}" 
                                   class="mt-4 text-sm font-medium text-primary hover:underline inline-flex items-center gap-2">
                                    Toch zoeken
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        `;
                    },
                },
            },
        ];
    },
    // Debounce for better performance
    getSourcesDebounceMs: 200,
    // Enable keyboard navigation
    navigator: {
        navigate({ itemUrl }) {
            if (itemUrl) {
                window.location.assign(itemUrl);
            }
        },
    },
    });
});
</script>
@endpush

