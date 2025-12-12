@extends('tsgui.layouts.tsgui')

@section('title', 'Collections - Typesense GUI')

@section('page-title', 'Collections')

@section('page-description', 'Manage your Typesense collections')

@section('content')
    @if(isset($error))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md mb-6 shadow-sm">
            <p class="font-medium">Error connecting to Typesense</p>
            <p class="text-sm mt-1">{{ $error }}</p>
        </div>
    @endif

    <!-- Page Header with Action -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900">Collections Overview</h2>
                <p class="mt-1 text-sm text-gray-500">Manage and view your Typesense collections</p>
            </div>
            <button onclick="document.getElementById('create-collection-modal').classList.remove('hidden')" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 shadow-sm">
                <i class="fas fa-plus"></i>
                <span>Create Collection</span>
            </button>
        </div>
    </div>

    @if(empty($collections))
        <div class="bg-white shadow rounded-lg">
            <div class="text-center py-12 px-4">
                <i class="fas fa-database text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No collections found</h3>
                <p class="text-gray-500 mb-6">There are no collections in your Typesense instance.</p>
                <button onclick="document.getElementById('create-collection-modal').classList.remove('hidden')" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700">
                    <i class="fas fa-plus"></i>
                    <span>Create Your First Collection</span>
                </button>
            </div>
        </div>
    @else
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-medium text-gray-900">All Collections</h3>
            </div>
            <ul class="divide-y divide-gray-200">
                @foreach($collections as $collection)
                    <li class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class="fas fa-database text-purple-600"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('tsgui.collection', ['collection' => $collection['name']]) }}" 
                                           class="text-base font-medium text-gray-900 hover:text-purple-600">
                                            {{ $collection['name'] }}
                                        </a>
                                        <div class="mt-1 flex items-center gap-4 text-sm text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-file-alt text-xs"></i>
                                                {{ number_format($collection['num_documents'] ?? 0) }} documents
                                            </span>
                                            @if(isset($collection['created_at']) && $collection['created_at'] > 0)
                                                <span class="flex items-center gap-1">
                                                    <i class="fas fa-clock text-xs"></i>
                                                    {{ \Carbon\Carbon::createFromTimestamp($collection['created_at'])->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 ml-4">
                                <a href="{{ route('tsgui.collection', ['collection' => $collection['name']]) }}" 
                                   class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                    <i class="fas fa-eye"></i>
                                    <span>View</span>
                                </a>
                                <form action="{{ route('tsgui.collection.destroy', ['collection' => $collection['name']]) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this collection? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-red-700 bg-white border border-red-300 rounded-md hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                            title="Delete collection">
                                        <i class="fas fa-trash"></i>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <!-- Create Collection Modal -->
    <div id="create-collection-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Create Collection</h3>
                <button onclick="document.getElementById('create-collection-modal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('tsgui.collection.create') }}" method="POST" class="space-y-4" id="create-collection-form">
                @csrf
                <div>
                    <label for="collection-name" class="block text-sm font-medium text-gray-700 mb-2">
                        Collection Name
                    </label>
                    <input type="text" 
                           id="collection-name" 
                           name="name" 
                           required
                           pattern="[a-z0-9_]+"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="my_collection_name">
                    <p class="text-xs text-gray-500 mt-1">
                        Use lowercase letters, numbers, and underscores only.
                    </p>
                </div>
                
                <div>
                    <label for="collection-schema" class="block text-sm font-medium text-gray-700 mb-2">
                        Collection Schema (JSON)
                    </label>
                    <textarea 
                        id="collection-schema" 
                        name="schema" 
                        rows="20"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 font-mono text-sm"
                        required>{{ json_encode([
    'name' => '',
    'fields' => [
        ['name' => 'id', 'type' => 'string'],
        ['name' => 'title', 'type' => 'string', 'index' => true],
    ],
    'default_sorting_field' => 'id'
], JSON_PRETTY_PRINT) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        Define the collection schema as JSON. Include 'name', 'fields' array, and optional 'default_sorting_field'.
                    </p>
                </div>
                
                <div class="flex items-center justify-end gap-4">
                    <button type="button" 
                            onclick="document.getElementById('create-collection-modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <i class="fas fa-plus mr-2"></i>Create Collection
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.getElementById('create-collection-form')?.addEventListener('submit', function(e) {
            const name = document.getElementById('collection-name').value;
            const schemaText = document.getElementById('collection-schema').value;
            
            try {
                const schema = JSON.parse(schemaText);
                // Update schema name with form name
                schema.name = name;
                
                // Update the form data
                document.getElementById('collection-schema').value = JSON.stringify(schema, null, 2);
            } catch (err) {
                e.preventDefault();
                alert('Invalid JSON schema. Please check your syntax.');
            }
        });
    </script>
    @endpush
@endsection
