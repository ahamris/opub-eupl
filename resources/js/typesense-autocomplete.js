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
        getSources({ query, setContext }) {
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
                                icon: 'fa-search',
                            },
                        ];
                    },
                    getItemUrl({ item }) {
                        return `${searchRoute}?zoeken=${encodeURIComponent(item.query)}`;
                    },
                    templates: {
                        header() {
                            return `
                                <div class="ts-source-header">
                                    <span class="ts-source-header-title">
                                        <i class="fas fa-bolt text-amber-500"></i>
                                        Snelle acties
                                    </span>
                                </div>
                            `;
                        },
                        item({ item, html }) {
                            return html`
                                <a href="${searchRoute}?zoeken=${encodeURIComponent(item.query)}" class="ts-suggestion-item">
                                    <div class="ts-suggestion-icon">
                                        <i class="fas ${item.icon}"></i>
                                    </div>
                                    <div class="ts-suggestion-content">
                                        <span class="ts-suggestion-label">${item.label}</span>
                                    </div>
                                    <div class="ts-suggestion-arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </a>
                            `;
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
                        header({ items, html }) {
                            if (items.length === 0) {
                                return null;
                            }
                            return html`
                                <div class="ts-source-header">
                                    <span class="ts-source-header-title">
                                        <i class="fas fa-file-alt text-blue-500"></i>
                                        Documenten
                                    </span>
                                    <span class="ts-source-header-count">${items.length} gevonden</span>
                                </div>
                            `;
                        },
                        item({ item, html, components }) {
                            const title = item._highlightResult?.title?.value || item.title || 'Geen titel';
                            const description = item._highlightResult?.description?.value || item.description || '';
                            const truncatedDescription = description.length > 150 
                                ? description.substring(0, 150) + '...' 
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
                                    formattedDate = item.publication_date;
                                }
                            }

                            // Get category badge color
                            const categoryColors = {
                                'Woo-verzoeken en -besluiten': 'bg-blue-100 text-blue-700 border-blue-200',
                                'Wetten en algemeen verbindende voorschriften': 'bg-purple-100 text-purple-700 border-purple-200',
                                'Organisatiegegevens': 'bg-green-100 text-green-700 border-green-200',
                                'Convenanten': 'bg-orange-100 text-orange-700 border-orange-200',
                                'Jaarplannen en jaarverslagen': 'bg-pink-100 text-pink-700 border-pink-200',
                            };
                            const categoryClass = categoryColors[item.category] || 'bg-gray-100 text-gray-700 border-gray-200';

                            return html`
                                <a href="${documentRoute}/${item.id}" class="ts-document-item">
                                    <div class="ts-document-content">
                                        <div class="ts-document-header">
                                            <h4 class="ts-document-title" dangerouslySetInnerHTML=${{ __html: title }}></h4>
                                            ${item.category ? html`
                                                <span class="ts-document-category ${categoryClass}">
                                                    ${item.category}
                                                </span>
                                            ` : ''}
                                        </div>
                                        ${truncatedDescription ? html`
                                            <p class="ts-document-description" dangerouslySetInnerHTML=${{ __html: truncatedDescription }}></p>
                                        ` : ''}
                                        <div class="ts-document-meta">
                                            ${item.organisation ? html`
                                                <span class="ts-document-meta-item">
                                                    <i class="fas fa-building"></i>
                                                    ${item.organisation}
                                                </span>
                                            ` : ''}
                                            ${formattedDate ? html`
                                                <span class="ts-document-meta-item">
                                                    <i class="fas fa-calendar"></i>
                                                    ${formattedDate}
                                                </span>
                                            ` : ''}
                                            ${item.theme ? html`
                                                <span class="ts-document-meta-item">
                                                    <i class="fas fa-tag"></i>
                                                    ${item.theme}
                                                </span>
                                            ` : ''}
                                        </div>
                                    </div>
                                    <div class="ts-document-arrow">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </a>
                            `;
                        },
                        noResults({ html }) {
                            return html`
                                <div class="ts-no-results">
                                    <i class="fas fa-search text-gray-400 text-2xl mb-2"></i>
                                    <p class="ts-no-results-text">Geen documenten gevonden</p>
                                    <p class="ts-no-results-hint">Probeer andere zoektermen</p>
                                </div>
                            `;
                        },
                    },
                },
            ];
        },

        // Render the autocomplete panel
        render({ elements, render, html }, root) {
            const { suggestions, documents } = elements;

            render(
                html`
                    <div class="ts-autocomplete-layout">
                        ${suggestions}
                        ${documents}
                    </div>
                    <div class="ts-autocomplete-footer">
                        <span class="ts-footer-hint">
                            <kbd>↵</kbd> om te zoeken
                            <kbd>↑</kbd><kbd>↓</kbd> om te navigeren
                            <kbd>esc</kbd> om te sluiten
                        </span>
                        <span class="ts-footer-powered">
                            <i class="fas fa-bolt text-amber-500"></i>
                            Powered by Typesense
                        </span>
                    </div>
                `,
                root
            );
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

