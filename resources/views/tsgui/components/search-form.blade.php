@props(['collection', 'query' => '*', 'perPage' => 20, 'sortBy' => '', 'filterBy' => ''])

<div class="bg-white border border-gray-200 rounded-lg p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Search Documents</h2>
    <form action="{{ route('tsgui.search', ['collection' => $collection['name']]) }}" method="GET" class="space-y-4">
        <div>
            <label for="q" class="block text-sm font-medium text-gray-700 mb-2">Search Query</label>
            <input type="text" 
                   id="q" 
                   name="q" 
                   value="{{ $query }}"
                   placeholder="Enter search query or * for all documents"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="per_page" class="block text-sm font-medium text-gray-700 mb-2">Results per page</label>
                <select name="per_page" id="per_page" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
            
            <div>
                <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">Sort by</label>
                <select name="sort_by" id="sort_by" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">Default</option>
                    @if(isset($collection['fields']))
                        @foreach($collection['fields'] as $field)
                            @if(isset($field['sort']) && $field['sort'])
                                <option value="{{ $field['name'] }}:asc" {{ $sortBy === $field['name'].':asc' ? 'selected' : '' }}>
                                    {{ $field['name'] }} (ascending)
                                </option>
                                <option value="{{ $field['name'] }}:desc" {{ $sortBy === $field['name'].':desc' ? 'selected' : '' }}>
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
                       value="{{ $filterBy }}"
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
