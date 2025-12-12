@props(['collection'])

<div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                <a href="{{ route('tsgui.collection', ['collection' => $collection['name']]) }}" 
                   class="text-purple-600 hover:text-purple-700">
                    {{ $collection['name'] }}
                </a>
            </h3>
            <p class="text-sm text-gray-500">
                {{ number_format($collection['num_documents'] ?? 0) }} documents
            </p>
        </div>
        <div class="flex items-center gap-2">
            <form action="{{ route('tsgui.collection.destroy', ['collection' => $collection['name']]) }}" 
                  method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this collection? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="text-red-600 hover:text-red-700 p-1"
                        title="Delete collection">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
    
    @if(isset($collection['created_at']) && $collection['created_at'] > 0)
        <p class="text-xs text-gray-400 mb-4">
            Created {{ \Carbon\Carbon::createFromTimestamp($collection['created_at'])->diffForHumans() }}
        </p>
    @endif
    
    <div>
        <a href="{{ route('tsgui.collection', ['collection' => $collection['name']]) }}" 
           class="inline-flex items-center gap-2 text-sm text-purple-600 hover:text-purple-700 font-medium">
            <span>View Collection</span>
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>
