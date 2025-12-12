@extends('tsgui.layouts.tsgui')

@section('title', 'Document - ' . $collection['name'] . ' - Typesense GUI')

@section('page-title', 'Document Details')

@section('page-description', 'ID: ' . ($document['id'] ?? 'N/A'))

@section('content')
    <div class="space-y-6">
        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('tsgui.collection', ['collection' => $collection['name']]) }}" 
               class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Collection</span>
            </a>
            
            <div class="flex items-center gap-2">
                <button onclick="document.getElementById('edit-document-modal').classList.remove('hidden')" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-edit mr-2"></i>Edit Document
                </button>
                <form action="{{ route('tsgui.document.destroy', ['collection' => $collection['name'], 'id' => $document['id'] ?? '']) }}" 
                      method="POST" 
                      class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this document? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <i class="fas fa-trash mr-2"></i>Delete Document
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Document View Toggle -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Document Data</h2>
                <div class="flex items-center gap-2">
                    <button id="toggle-view" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <i class="fas fa-code mr-2"></i>Toggle JSON View
                    </button>
                </div>
            </div>
            
            <!-- Formatted View -->
            <div id="formatted-view" class="space-y-4">
                @foreach($document as $key => $value)
                    <div class="border-b border-gray-100 pb-4 last:border-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $key }}</label>
                        <div class="text-sm text-gray-900">
                            @if(is_array($value))
                                <pre class="bg-gray-50 p-3 rounded-md overflow-x-auto text-xs">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            @elseif(is_bool($value))
                                <span class="px-2 py-1 bg-{{ $value ? 'green' : 'red' }}-100 text-{{ $value ? 'green' : 'red' }}-800 rounded">{{ $value ? 'true' : 'false' }}</span>
                            @elseif(is_numeric($value))
                                <span class="font-mono">{{ number_format($value) }}</span>
                            @elseif(filter_var($value, FILTER_VALIDATE_URL))
                                <a href="{{ $value }}" target="_blank" class="text-purple-600 hover:text-purple-700 break-all">
                                    {{ $value }} <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                            @else
                                <p class="whitespace-pre-wrap">{{ $value }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- JSON View -->
            <div id="json-view" class="hidden">
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-md overflow-x-auto text-sm font-mono"><code id="json-content">{{ json_encode($document, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</code></pre>
                <button onclick="copyToClipboard()" class="mt-2 px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="fas fa-copy mr-2"></i>Copy JSON
                </button>
            </div>
        </div>
        
        <!-- Document Metadata -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Metadata</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Document ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $document['id'] ?? 'N/A' }}</dd>
                </div>
                @if(isset($document['external_id']))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">External ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $document['external_id'] }}</dd>
                    </div>
                @endif
                @if(isset($document['publication_date']) && $document['publication_date'] > 0)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Publication Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ \Carbon\Carbon::createFromTimestamp($document['publication_date'])->format('Y-m-d H:i:s') }}
                        </dd>
                    </div>
                @endif
                @if(isset($document['synced_at']) && $document['synced_at'] > 0)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Synced At</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ \Carbon\Carbon::createFromTimestamp($document['synced_at'])->format('Y-m-d H:i:s') }}
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
        
        <!-- Edit Document Modal -->
        <div id="edit-document-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Document</h3>
                    <button onclick="document.getElementById('edit-document-modal').classList.add('hidden')" 
                            class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="{{ route('tsgui.document.store', ['collection' => $collection['name']]) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="edit-document-json" class="block text-sm font-medium text-gray-700 mb-2">
                            Document JSON
                        </label>
                        <textarea 
                            id="edit-document-json" 
                            name="document" 
                            rows="15"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 font-mono text-sm"
                            required>{{ json_encode($document, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Edit document data as JSON. Changes will be saved to Typesense.
                        </p>
                    </div>
                    
                    <div class="flex items-center justify-end gap-4">
                        <button type="button" 
                                onclick="document.getElementById('edit-document-modal').classList.add('hidden')"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        let isJsonView = false;
        
        document.getElementById('toggle-view').addEventListener('click', function() {
            isJsonView = !isJsonView;
            const formattedView = document.getElementById('formatted-view');
            const jsonView = document.getElementById('json-view');
            
            if (isJsonView) {
                formattedView.classList.add('hidden');
                jsonView.classList.remove('hidden');
                this.innerHTML = '<i class="fas fa-list mr-2"></i>Toggle Formatted View';
            } else {
                formattedView.classList.remove('hidden');
                jsonView.classList.add('hidden');
                this.innerHTML = '<i class="fas fa-code mr-2"></i>Toggle JSON View';
            }
        });
        
        function copyToClipboard() {
            const jsonContent = document.getElementById('json-content').textContent;
            navigator.clipboard.writeText(jsonContent).then(function() {
                alert('JSON copied to clipboard!');
            }, function(err) {
                console.error('Failed to copy: ', err);
            });
        }
    </script>
    @endpush
@endsection
