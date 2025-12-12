@php
if (!function_exists('highlightText')) {
    function highlightText($text, $query) {
        if (!$query || $query === '*') {
            return htmlspecialchars($text ?? '');
        }
        $escapedQuery = preg_quote($query, '/');
        $pattern = '/(' . $escapedQuery . ')/i';
        return preg_replace($pattern, '<mark class="bg-yellow-200">$1</mark>', htmlspecialchars($text ?? ''));
    }
}
@endphp

@extends('tsgui.layouts.tsgui')

@section('title', 'Search - ' . $collection['name'] . ' - Typesense GUI')

@section('page-title', 'Search Results')

@section('page-description', number_format($results['found'] ?? 0) . ' results found in ' . ($results['search_time_ms'] ?? 0) . 'ms')

@section('content')
    <div class="space-y-6">
        <!-- Search Form -->
        <div class="bg-white shadow rounded-lg p-4">
            <form action="{{ route('tsgui.search', ['collection' => $collection['name']]) }}" method="GET" class="flex gap-2">
                <input type="text" 
                       name="q" 
                       value="{{ $query }}"
                       placeholder="Search here..."
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                <input type="hidden" name="per_page" value="{{ $perPage }}">
                <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                <input type="hidden" name="filter_by" value="{{ $filterBy }}">
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Facets Sidebar -->
            <div class="lg:col-span-1">
                @if(isset($results['facet_counts']) && count($results['facet_counts']) > 0)
                    <div class="bg-white shadow rounded-lg p-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Filters</h3>
                            @if($filterBy)
                                <button onclick="clearAllFilters()" class="text-xs text-purple-600 hover:text-purple-700">
                                    Clear all
                                </button>
                            @endif
                        </div>
                        
                        @foreach($results['facet_counts'] as $facet)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $facet['field_name'] }}
                                </label>
                                <div class="space-y-2 max-h-64 overflow-y-auto">
                                    @foreach($facet['counts'] as $count)
                                        <label class="flex items-center gap-2 text-sm">
                                            <input type="checkbox" 
                                                   class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                                                   data-field="{{ $facet['field_name'] }}"
                                                   data-value="{{ $count['value'] ?? '' }}"
                                                   onchange="updateFilter('{{ $facet['field_name'] }}', '{{ addslashes($count['value'] ?? '') }}', this.checked)">
                                            <span class="flex-1">{{ $count['value'] ?: '(empty)' }}</span>
                                            <span class="text-gray-500">{{ $count['count'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Results -->
            <div class="lg:col-span-3">
                @if(isset($results['hits']) && count($results['hits']) > 0)
                    <div class="space-y-4">
                        @foreach($results['hits'] as $hit)
                            <div class="bg-white shadow rounded-lg p-4 hover:shadow-lg transition-shadow">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1">
                                        @if(isset($hit['document']['title']))
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                                <a href="{{ route('tsgui.document', ['collection' => $collection['name'], 'id' => $hit['document']['id'] ?? '']) }}" 
                                                   class="text-purple-600 hover:text-purple-700">
                                                    {!! highlightText($hit['document']['title'], $query) !!}
                                                </a>
                                            </h3>
                                        @endif
                                        
                                        @if(isset($hit['document']['description']))
                                            <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                                                {!! highlightText($hit['document']['description'], $query) !!}
                                            </p>
                                        @endif
                                        
                                        <div class="flex flex-wrap gap-2 text-xs text-gray-500">
                                            @if(isset($hit['document']['document_type']))
                                                <span class="px-2 py-1 bg-gray-100 rounded">{{ $hit['document']['document_type'] }}</span>
                                            @endif
                                            @if(isset($hit['document']['theme']))
                                                <span class="px-2 py-1 bg-gray-100 rounded">{{ $hit['document']['theme'] }}</span>
                                            @endif
                                            @if(isset($hit['document']['organisation']))
                                                <span class="px-2 py-1 bg-gray-100 rounded">{{ $hit['document']['organisation'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="ml-4">
                                        <a href="{{ route('tsgui.document', ['collection' => $collection['name'], 'id' => $hit['document']['id'] ?? '']) }}" 
                                           class="text-purple-600 hover:text-purple-700 text-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                                
                                @if(isset($hit['highlights']))
                                    <div class="mt-2 text-xs text-gray-500">
                                        @foreach($hit['highlights'] as $field => $highlights)
                                            @foreach($highlights as $highlight)
                                                <p class="mb-1">...{!! $highlight !!}...</p>
                                            @endforeach
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    @if(isset($results['found']) && $results['found'] > $perPage)
                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Showing {{ (($page - 1) * $perPage) + 1 }} to {{ min($page * $perPage, $results['found']) }} of {{ number_format($results['found']) }} results
                            </div>
                            <div class="flex gap-2">
                                @if($page > 1)
                                    <a href="{{ route('tsgui.search', array_merge(['collection' => $collection['name']], request()->except('page'), ['page' => $page - 1])) }}" 
                                       class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                @endif
                                
                                @for($i = max(1, $page - 2); $i <= min(ceil($results['found'] / $perPage), $page + 2); $i++)
                                    <a href="{{ route('tsgui.search', array_merge(['collection' => $collection['name']], request()->except('page'), ['page' => $i])) }}" 
                                       class="px-4 py-2 border border-gray-300 rounded-md {{ $i === $page ? 'bg-purple-600 text-white border-purple-600' : 'hover:bg-gray-50' }}">
                                        {{ $i }}
                                    </a>
                                @endfor
                                
                                @if($page < ceil($results['found'] / $perPage))
                                    <a href="{{ route('tsgui.search', array_merge(['collection' => $collection['name']], request()->except('page'), ['page' => $page + 1])) }}" 
                                       class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                @else
                    <div class="bg-white shadow rounded-lg p-12 text-center">
                        <i class="fas fa-search text-gray-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No results found</h3>
                        <p class="text-gray-500">Try adjusting your search query or filters.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        // Store active filters
        let activeFilters = {};
        
        // Initialize active filters from URL
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const filterBy = urlParams.get('filter_by');
            if (filterBy) {
                // Parse existing filters
                const filters = filterBy.split(' && ');
                filters.forEach(filter => {
                    const match = filter.match(/(\w+):=([^&]+)/);
                    if (match) {
                        const field = match[1];
                        const value = match[2];
                        if (!activeFilters[field]) {
                            activeFilters[field] = [];
                        }
                        activeFilters[field].push(value);
                    }
                });
            }
            updateFilterCheckboxes();
        });
        
        function updateFilter(field, value, checked) {
            if (!activeFilters[field]) {
                activeFilters[field] = [];
            }
            
            if (checked) {
                if (!activeFilters[field].includes(value)) {
                    activeFilters[field].push(value);
                }
            } else {
                activeFilters[field] = activeFilters[field].filter(v => v !== value);
                if (activeFilters[field].length === 0) {
                    delete activeFilters[field];
                }
            }
            
            updateFilterCheckboxes();
            applyFilters();
        }
        
        function updateFilterCheckboxes() {
            // Update checkbox states based on activeFilters
            Object.keys(activeFilters).forEach(field => {
                activeFilters[field].forEach(value => {
                    const checkbox = document.querySelector(`input[data-field="${field}"][data-value="${value}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            });
        }
        
        function applyFilters() {
            const url = new URL(window.location.href);
            
            // Build filter_by string
            const filterParts = [];
            Object.keys(activeFilters).forEach(field => {
                activeFilters[field].forEach(value => {
                    // Escape value if needed
                    const escapedValue = value.replace(/"/g, '\\"');
                    filterParts.push(`${field}:="${escapedValue}"`);
                });
            });
            
            if (filterParts.length > 0) {
                url.searchParams.set('filter_by', filterParts.join(' && '));
            } else {
                url.searchParams.delete('filter_by');
            }
            
            // Reset to page 1 when filters change
            url.searchParams.set('page', '1');
            
            window.location.href = url.toString();
        }
        
        function clearAllFilters() {
            activeFilters = {};
            const url = new URL(window.location.href);
            url.searchParams.delete('filter_by');
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }
        
        function highlightText(text, query) {
            if (!query || query === '*') {
                return text;
            }
            const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            return text.replace(regex, '<mark class="bg-yellow-200">$1</mark>');
        }
    </script>
    @endpush
@endsection
