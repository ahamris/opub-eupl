@props(['documents', 'collection', 'query' => '*'])

@if(empty($documents))
    <div class="bg-white border border-gray-200 rounded-lg p-12 text-center">
        <i class="fas fa-search text-gray-300 text-4xl mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No documents found</h3>
        <p class="text-gray-500">Try adjusting your search query or filters.</p>
    </div>
@else
    <div class="space-y-4">
        @foreach($documents as $hit)
            @php
                $document = $hit['document'] ?? $hit;
            @endphp
            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        @if(isset($document['title']))
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                <a href="{{ route('tsgui.document', ['collection' => $collection['name'], 'id' => $document['id'] ?? '']) }}" 
                                   class="text-purple-600 hover:text-purple-700">
                                    {{ $document['title'] }}
                                </a>
                            </h3>
                        @endif
                        
                        @if(isset($document['description']))
                            <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                                {{ Str::limit($document['description'], 200) }}
                            </p>
                        @endif
                        
                        <div class="flex flex-wrap gap-2 text-xs text-gray-500">
                            @if(isset($document['document_type']))
                                <span class="px-2 py-1 bg-gray-100 rounded">{{ $document['document_type'] }}</span>
                            @endif
                            @if(isset($document['theme']))
                                <span class="px-2 py-1 bg-gray-100 rounded">{{ $document['theme'] }}</span>
                            @endif
                            @if(isset($document['organisation']))
                                <span class="px-2 py-1 bg-gray-100 rounded">{{ $document['organisation'] }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="ml-4">
                        <a href="{{ route('tsgui.document', ['collection' => $collection['name'], 'id' => $document['id'] ?? '']) }}" 
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
@endif
