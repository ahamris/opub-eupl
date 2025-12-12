@extends('tsgui.layouts.tsgui')

@section('title', $collection['name'] . ' - Typesense GUI')

@section('page-title', $collection['name'])

@section('page-description', number_format($stats['num_documents'] ?? 0) . ' documents')

@section('content')
    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ route('tsgui.collection', ['collection' => $collection['name']]) }}" 
               class="{{ request()->routeIs('tsgui.collection') && !request()->routeIs('tsgui.search') ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                Overview
            </a>
            <a href="{{ route('tsgui.search', ['collection' => $collection['name'], 'q' => '*']) }}" 
               class="{{ request()->routeIs('tsgui.search') ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                Search
            </a>
            <a href="{{ route('tsgui.collection', ['collection' => $collection['name']]) }}#schema" 
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                Schema
            </a>
        </nav>
    </div>

    <div class="space-y-6">
        <!-- Overview Section -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="mb-6">
                <h2 class="text-base font-medium text-gray-900 mb-1">Collection Information</h2>
                <p class="text-sm text-gray-500">Basic information about this collection</p>
            </div>
            
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Collection Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $collection['name'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Documents</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($stats['num_documents'] ?? 0) }}</dd>
                </div>
                @if(isset($collection['created_at']) && $collection['created_at'] > 0)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::createFromTimestamp($collection['created_at'])->format('Y-m-d H:i:s') }}</dd>
                    </div>
                @endif
                @if(isset($collection['default_sorting_field']))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Default Sorting</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $collection['default_sorting_field'] }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <!-- Search Form -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="mb-4">
                <h2 class="text-base font-medium text-gray-900 mb-1">Search Documents</h2>
                <p class="text-sm text-gray-500">Search and filter documents in this collection</p>
            </div>
            <form action="{{ route('tsgui.search', ['collection' => $collection['name']]) }}" method="GET" class="space-y-4">
                <div>
                    <label for="q" class="block text-sm font-medium text-gray-700 mb-2">Search Query</label>
                    <input type="text" 
                           id="q" 
                           name="q" 
                           value="{{ request('q', '*') }}"
                           placeholder="Enter search query or * for all documents"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="per_page" class="block text-sm font-medium text-gray-700 mb-2">Results per page</label>
                        <select name="per_page" id="per_page" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 20) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">Sort by</label>
                        <select name="sort_by" id="sort_by" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Default</option>
                            @if(isset($collection['fields']))
                                @foreach($collection['fields'] as $field)
                                    @if(isset($field['sort']) && $field['sort'])
                                        <option value="{{ $field['name'] }}:asc" {{ request('sort_by') === $field['name'].':asc' ? 'selected' : '' }}>
                                            {{ $field['name'] }} (ascending)
                                        </option>
                                        <option value="{{ $field['name'] }}:desc" {{ request('sort_by') === $field['name'].':desc' ? 'selected' : '' }}>
                                            {{ $field['name'] }} (descending)
                                        </option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div>
                        <label for="filter_by" class="block text-sm font-medium text-gray-700 mb-2">Filter by</label>
                        <input type="text" 
                               id="filter_by" 
                               name="filter_by" 
                               value="{{ request('filter_by') }}"
                               placeholder="e.g., document_type:=article"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <p class="text-xs text-gray-500 mt-1">Typesense filter syntax</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 font-medium">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <a href="{{ route('tsgui.collection', ['collection' => $collection['name']]) }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Reset
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Schema Section -->
        <div id="schema" class="bg-white shadow rounded-lg p-6">
            <div class="mb-6">
                <h2 class="text-base font-medium text-gray-900 mb-1">Collection Schema</h2>
                <p class="text-sm text-gray-500">Field definitions and configuration for this collection</p>
            </div>
            
            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-500">Total Documents</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($stats['num_documents'] ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-500">Fields</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ count($collection['fields'] ?? []) }}</p>
                </div>
                @if(isset($collection['created_at']) && $collection['created_at'] > 0)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-500">Created</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::createFromTimestamp($collection['created_at'])->format('M d, Y') }}</p>
                    </div>
                @endif
            </div>
            
            @if(isset($collection['fields']) && count($collection['fields']) > 0)
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Index</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facet</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($collection['fields'] as $field)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $field['name'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $field['type'] ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if(isset($field['index']) && $field['index'])
                                            <span class="inline-flex items-center text-green-600">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-gray-400">
                                                <i class="fas fa-times-circle"></i>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if(isset($field['facet']) && $field['facet'])
                                            <span class="inline-flex items-center text-green-600">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-gray-400">
                                                <i class="fas fa-times-circle"></i>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if(isset($field['sort']) && $field['sort'])
                                            <span class="inline-flex items-center text-green-600">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-gray-400">
                                                <i class="fas fa-times-circle"></i>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-database text-gray-300 text-4xl mb-2"></i>
                    <p class="text-sm text-gray-500">No fields defined in this collection.</p>
                </div>
            @endif
        </div>
        
        <!-- Actions Section -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="mb-4">
                <h2 class="text-base font-medium text-gray-900 mb-1">Actions</h2>
                <p class="text-sm text-gray-500">Manage this collection</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('tsgui.search', ['collection' => $collection['name'], 'q' => '*']) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 shadow-sm">
                    <i class="fas fa-list"></i>
                    <span>View All Documents</span>
                </a>
                <button onclick="document.getElementById('add-document-modal').classList.remove('hidden')" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-md text-sm font-medium hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 shadow-sm">
                    <i class="fas fa-plus"></i>
                    <span>Add Document</span>
                </button>
                <form action="{{ route('tsgui.collection.destroy', ['collection' => $collection['name']]) }}" 
                      method="POST" 
                      class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this collection? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-red-300 rounded-md text-sm font-medium text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-sm">
                        <i class="fas fa-trash"></i>
                        <span>Delete Collection</span>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Add Document Modal -->
        <div id="add-document-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Add Document</h3>
                    <button onclick="document.getElementById('add-document-modal').classList.add('hidden')" 
                            class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="{{ route('tsgui.document.store', ['collection' => $collection['name']]) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="document-json" class="block text-sm font-medium text-gray-700 mb-2">
                            Document JSON
                        </label>
                        <textarea 
                            id="document-json" 
                            name="document" 
                            rows="15"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 font-mono text-sm"
                            placeholder='{
  "id": "unique-id",
  "title": "Document Title",
  "description": "Document description",
  ...
}'
                            required></textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Enter document data as JSON. The document will be validated before adding.
                        </p>
                    </div>
                    
                    <div class="flex items-center justify-end gap-4">
                        <button type="button" 
                                onclick="document.getElementById('add-document-modal').classList.add('hidden')"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <i class="fas fa-plus mr-2"></i>Add Document
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
