/**
 * Typesense Autocomplete Implementation
 * Based on: https://github.com/typesense/typesense-autocomplete-demo
 * 
 * Uses @algolia/autocomplete-js with Laravel API proxy (to avoid mixed content issues)
 */

import { autocomplete } from '@algolia/autocomplete-js';

// Store the autocomplete instance globally for cleanup
let autocompleteInstance = null;

/**
 * Custom search client that uses Laravel API endpoints
 * This avoids mixed content issues (HTTP Typesense on HTTPS site)
 */
function createLaravelSearchClient(liveSearchUrl, autocompleteUrl) {
    return {
        async search(queries) {
            const query = queries[0]?.query || '';
            if (!query || query.length < 2) {
                return { results: [{ hits: [], nbHits: 0 }] };
            }
            
            try {
                const response = await fetch(`${liveSearchUrl}?q=${encodeURIComponent(query)}&limit=6`);
                if (!response.ok) throw new Error('Search failed');
                const data = await response.json();
                
                return {
                    results: [{
                        hits: (data.hits || []).map(hit => ({
                            ...hit,
                            objectID: hit.id,
                            _highlightResult: {
                                title: { value: hit.title || '' },
                                description: { value: hit.description || '' },
                            }
                        })),
                        nbHits: data.found || 0,
                        processingTimeMS: data.search_time_ms || 0,
                    }]
                };
            } catch (error) {
                console.error('Search error:', error);
                return { results: [{ hits: [], nbHits: 0 }] };
            }
        }
    };
}

/**
 * Initialize Typesense Autocomplete
 * @param {Object} config - Configuration object
 * @param {string} config.container - CSS selector for the container element
 * @param {string} config.liveSearchUrl - URL for live search API
 * @param {string} config.autocompleteUrl - URL for autocomplete suggestions API
 * @param {string} config.searchRoute - URL to redirect for full search
 * @param {string} config.documentRoute - Base URL for document details
 */
export function initTypesenseAutocomplete(config) {
    const {
        container,
        liveSearchUrl = '/api/live-search',
        autocompleteUrl = '/api/autocomplete',
        searchRoute = '/zoeken',
        documentRoute = '/open-overheid/documents',
        placeholder = 'Zoek in alle documenten...',
    } = config;

    // Destroy existing instance if any
    if (autocompleteInstance) {
        autocompleteInstance.destroy();
    }

    // Check if container exists
    const containerEl = document.querySelector(container);
    if (!containerEl) {
        console.warn(`Autocomplete container not found: ${container}`);
        return null;
    }

    // Create Laravel-backed search client
    const searchClient = createLaravelSearchClient(liveSearchUrl, autocompleteUrl);

    // Initialize autocomplete
    autocompleteInstance = autocomplete({
        container: container,
        placeholder: placeholder,
        openOnFocus: true,
        autoFocus: false,
        defaultActiveItemId: 0,
        
        // Custom class names for styling
        classNames: {
            form: 'ts-autocomplete-form',
            input: 'ts-autocomplete-input',
            inputWrapper: 'ts-autocomplete-input-wrapper',
            inputWrapperPrefix: 'ts-autocomplete-input-prefix',
            inputWrapperSuffix: 'ts-autocomplete-input-suffix',
            panel: 'ts-autocomplete-panel',
            list: 'ts-autocomplete-list',
            item: 'ts-autocomplete-item',
            sourceHeader: 'ts-autocomplete-source-header',
            sourceFooter: 'ts-autocomplete-source-footer',
            panelLayout: 'ts-autocomplete-panel-layout',
            clearButton: 'ts-autocomplete-clear-button',
            submitButton: 'ts-autocomplete-submit-button',
            loadingIndicator: 'ts-autocomplete-loading',
        },

        // Handle form submission (Enter key or submit button)
        onSubmit({ state }) {
            if (state.query) {
                window.location.href = `${searchRoute}?zoeken=${encodeURIComponent(state.query)}`;
            }
        },

        // Handle item selection
        onStateChange({ state, prevState }) {
            // Track analytics or other state changes if needed
        },

        // Define sources - this is where the magic happens!
        getSources({ query }) {
            if (!query || query.length < 2) {
                return [];
            }

            return [
                // Source 1: Quick Actions / Suggestions
                {
                    sourceId: 'suggestions',
                    getItems() {
                        return [
                            {
                                type: 'action',
                                label: `Zoeken naar "${query}"`,
                                query: query,
                            },
                        ];
                    },
                    getItemUrl({ item }) {
                        return `${searchRoute}?zoeken=${encodeURIComponent(item.query)}`;
                    },
                    templates: {
                        item({ item, createElement }) {
                            // Use createElement (Preact's h function) for proper rendering
                            return createElement('a', {
                                href: `${searchRoute}?zoeken=${encodeURIComponent(item.query)}`,
                                className: 'ts-suggestion-item',
                            }, [
                                createElement('div', { className: 'ts-suggestion-icon' }, 
                                    createElement('i', { className: 'fas fa-search' })
                                ),
                                createElement('div', { className: 'ts-suggestion-content' },
                                    createElement('span', { className: 'ts-suggestion-label' }, item.label)
                                ),
                                createElement('div', { className: 'ts-suggestion-arrow' },
                                    createElement('i', { className: 'fas fa-arrow-right' })
                                ),
                            ]);
                        },
                    },
                },

                // Source 2: Document Results from Laravel API (proxied Typesense)
                {
                    sourceId: 'documents',
                    async getItems() {
                        const results = await searchClient.search([{ query }]);
                        return results.results[0]?.hits || [];
                    },
                    getItemUrl({ item }) {
                        return `${documentRoute}/${item.id}`;
                    },
                    templates: {
                        header({ items, createElement }) {
                            if (items.length === 0) return null;
                            return createElement('div', { className: 'ts-source-header' }, [
                                createElement('span', { className: 'ts-source-header-title' }, [
                                    createElement('i', { className: 'fas fa-file-alt text-blue-500' }),
                                    ' DOCUMENTEN'
                                ]),
                                createElement('span', { className: 'ts-source-header-count' }, `${items.length} gevonden`)
                            ]);
                        },
                        item({ item, createElement }) {
                            const title = item.title || 'Geen titel';
                            const description = item.description || '';
                            const truncatedDesc = description.length > 100 
                                ? description.substring(0, 100) + '...' 
                                : description;
                            
                            // Format date
                            let formattedDate = '';
                            if (item.publication_date) {
                                try {
                                    const date = new Date(item.publication_date);
                                    formattedDate = date.toLocaleDateString('nl-NL', {
                                        year: 'numeric',
                                        month: 'short',
                                        day: 'numeric'
                                    });
                                } catch (e) {
                                    formattedDate = '';
                                }
                            }

                            const metaItems = [];
                            if (item.organisation) {
                                metaItems.push(createElement('span', { className: 'ts-document-meta-item' }, [
                                    createElement('i', { className: 'fas fa-building' }),
                                    ` ${item.organisation}`
                                ]));
                            }
                            if (formattedDate) {
                                metaItems.push(createElement('span', { className: 'ts-document-meta-item' }, [
                                    createElement('i', { className: 'fas fa-calendar' }),
                                    ` ${formattedDate}`
                                ]));
                            }

                            return createElement('a', {
                                href: `${documentRoute}/${item.id}`,
                                className: 'ts-document-item',
                            }, [
                                createElement('div', { className: 'ts-document-content' }, [
                                    createElement('div', { className: 'ts-document-header' }, [
                                        createElement('h4', { className: 'ts-document-title' }, title),
                                        item.category ? createElement('span', { className: 'ts-document-category' }, item.category) : null,
                                    ].filter(Boolean)),
                                    truncatedDesc ? createElement('p', { className: 'ts-document-description' }, truncatedDesc) : null,
                                    metaItems.length > 0 ? createElement('div', { className: 'ts-document-meta' }, metaItems) : null,
                                ].filter(Boolean)),
                                createElement('div', { className: 'ts-document-arrow' },
                                    createElement('i', { className: 'fas fa-chevron-right' })
                                ),
                            ]);
                        },
                        noResults({ createElement }) {
                            return createElement('div', { className: 'ts-no-results' }, [
                                createElement('i', { className: 'fas fa-search text-gray-400 text-2xl mb-2' }),
                                createElement('p', { className: 'ts-no-results-text' }, 'Geen documenten gevonden'),
                                createElement('p', { className: 'ts-no-results-hint' }, 'Probeer andere zoektermen'),
                            ]);
                        },
                    },
                },
            ];
        },
    });

    return autocompleteInstance;
}

/**
 * Destroy the autocomplete instance
 */
export function destroyAutocomplete() {
    if (autocompleteInstance) {
        autocompleteInstance.destroy();
        autocompleteInstance = null;
    }
}

/**
 * Update the autocomplete query programmatically
 * @param {string} query - The new query
 */
export function setAutocompleteQuery(query) {
    if (autocompleteInstance) {
        autocompleteInstance.setQuery(query);
        autocompleteInstance.refresh();
    }
}

// Export for use in global scope
window.initTypesenseAutocomplete = initTypesenseAutocomplete;
window.destroyAutocomplete = destroyAutocomplete;
window.setAutocompleteQuery = setAutocompleteQuery;

