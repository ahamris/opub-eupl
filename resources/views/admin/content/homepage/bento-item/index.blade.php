<x-layouts.admin title="Bento Items">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Bento Items</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage bento grid items on the homepage</p>
            </div>
            <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.homepage.bento-item.create') }}">Add Item</x-button>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <!-- Bento Items Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse(\App\Models\BentoItem::ordered()->get() as $item)
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md overflow-hidden {{ !$item->is_active ? 'opacity-50' : '' }}">
                @if($item->image_url)
                <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-40 object-cover">
                @else
                <div class="w-full h-40 bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                    <i class="fas fa-image text-4xl text-zinc-300 dark:text-zinc-600"></i>
                </div>
                @endif
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-zinc-900 dark:text-white">{{ $item->title }}</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-xs bg-zinc-100 dark:bg-zinc-800 px-2 py-1 rounded">{{ $item->col_span }}col</span>
                            @if($item->is_coming_soon)
                            <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded">{{ $item->coming_soon_text ?? 'Coming Soon' }}</span>
                            @endif
                        </div>
                    </div>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">{{ Str::limit($item->description, 80) }}</p>
                    <div class="flex items-center justify-between">
                        <form action="{{ route('admin.content.homepage.bento-item.toggle-active', $item) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs {{ $item->is_active ? 'text-green-600' : 'text-gray-400' }}">
                                <i class="fas {{ $item->is_active ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.content.homepage.bento-item.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.content.homepage.bento-item.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full p-12 text-center text-zinc-500 dark:text-zinc-400 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md">
                <i class="fas fa-th-large text-4xl mb-4 text-zinc-300 dark:text-zinc-600"></i>
                <p>No bento items yet</p>
                <a href="{{ route('admin.content.homepage.bento-item.create') }}" class="mt-2 inline-block text-indigo-600 hover:underline">Create your first item</a>
            </div>
            @endforelse
        </div>
    </div>
</x-layouts.admin>
