<x-layouts.admin title="View Static Page">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">{{ $staticPage->title }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">View static page details</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.static-page.index') }}">Back to Pages</x-button>
                <x-button variant="primary" icon="pencil" icon-position="left" href="{{ route('admin.content.static-page.edit', $staticPage) }}">Edit Page</x-button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column (Content) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Page Info Card -->
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Page Details</h2>
                        @if($staticPage->is_active)
                            <x-badge variant="success">Active</x-badge>
                        @else
                            <x-badge variant="secondary">Inactive</x-badge>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">Title</label>
                            <p class="mt-1 text-zinc-900 dark:text-zinc-100">{{ $staticPage->title }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">Slug</label>
                            <p class="mt-1 text-zinc-900 dark:text-zinc-100">{{ $staticPage->slug }}</p>
                        </div>
                        @if($staticPage->subtitle)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">Subtitle</label>
                            <p class="mt-1 text-zinc-900 dark:text-zinc-100">{{ $staticPage->subtitle }}</p>
                        </div>
                        @endif
                        @if($staticPage->meta_description)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">Meta Description</label>
                            <p class="mt-1 text-zinc-900 dark:text-zinc-100">{{ $staticPage->meta_description }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Content Preview Card -->
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Content Preview</h2>
                    <div class="prose dark:prose-invert max-w-none">
                        {!! $staticPage->content !!}
                    </div>
                </div>
            </div>

            <!-- Right Column (Meta) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Buttons Card -->
                @if($staticPage->button_1_text || $staticPage->button_2_text)
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Buttons</h2>
                    
                    @if($staticPage->button_1_text)
                    <div class="p-3 border border-zinc-200 dark:border-zinc-700 rounded">
                        <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Button 1</p>
                        <p class="text-sm text-zinc-900 dark:text-zinc-100">{{ $staticPage->button_1_text }}</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ $staticPage->button_1_url }}</p>
                    </div>
                    @endif

                    @if($staticPage->button_2_text)
                    <div class="p-3 border border-zinc-200 dark:border-zinc-700 rounded">
                        <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Button 2</p>
                        <p class="text-sm text-zinc-900 dark:text-zinc-100">{{ $staticPage->button_2_text }}</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ $staticPage->button_2_url }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Meta Card -->
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Information</h2>
                    
                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">Sort Order</label>
                        <p class="mt-1 text-zinc-900 dark:text-zinc-100">{{ $staticPage->sort_order }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">Frontend URL</label>
                        <a href="{{ $staticPage->link_url }}" target="_blank" class="mt-1 text-indigo-600 hover:underline text-sm">
                            {{ $staticPage->link_url }}
                        </a>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">Created</label>
                        <p class="mt-1 text-zinc-900 dark:text-zinc-100">{{ $staticPage->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">Last Updated</label>
                        <p class="mt-1 text-zinc-900 dark:text-zinc-100">{{ $staticPage->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Actions</h2>
                    
                    <div class="space-y-3">
                        <x-button variant="primary" icon="pencil" icon-position="left" href="{{ route('admin.content.static-page.edit', $staticPage) }}" class="w-full justify-center">
                            Edit Page
                        </x-button>
                        
                        <form action="{{ route('admin.content.static-page.destroy', $staticPage) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page?')">
                            @csrf
                            @method('DELETE')
                            <x-button variant="error" icon="trash" icon-position="left" type="submit" class="w-full justify-center">
                                Delete Page
                            </x-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
