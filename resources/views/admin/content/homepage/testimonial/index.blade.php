<x-layouts.admin title="Testimonials">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Testimonials</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage customer testimonials on the homepage</p>
            </div>
            <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.homepage.testimonial.create') }}">Add Testimonial</x-button>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <!-- Testimonials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse(\App\Models\Testimonial::ordered()->get() as $testimonial)
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 {{ !$testimonial->is_active ? 'opacity-50' : '' }}">
                <div class="flex items-start gap-4 mb-4">
                    @if($testimonial->avatar_url)
                    <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->author }}" class="w-12 h-12 rounded-full object-cover">
                    @else
                    <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                        <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-lg">{{ substr($testimonial->author, 0, 1) }}</span>
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-zinc-900 dark:text-white truncate">{{ $testimonial->author }}</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 truncate">{{ $testimonial->role_display }}</p>
                    </div>
                </div>
                
                <blockquote class="text-sm text-zinc-600 dark:text-zinc-300 italic mb-4 line-clamp-3">
                    "{{ $testimonial->quote }}"
                </blockquote>
                
                <div class="flex items-center justify-between pt-4 border-t border-zinc-100 dark:border-zinc-800">
                    <form action="{{ route('admin.content.homepage.testimonial.toggle-active', $testimonial) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs {{ $testimonial->is_active ? 'text-green-600' : 'text-gray-400' }}">
                            <i class="fas {{ $testimonial->is_active ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>
                            {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </form>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.content.homepage.testimonial.edit', $testimonial) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.content.homepage.testimonial.destroy', $testimonial) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full p-12 text-center text-zinc-500 dark:text-zinc-400 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md">
                <i class="fas fa-quote-left text-4xl mb-4 text-zinc-300 dark:text-zinc-600"></i>
                <p>No testimonials yet</p>
                <a href="{{ route('admin.content.homepage.testimonial.create') }}" class="mt-2 inline-block text-indigo-600 hover:underline">Add your first testimonial</a>
            </div>
            @endforelse
        </div>
    </div>
</x-layouts.admin>
