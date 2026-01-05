<x-layouts.admin title="Blog Category Details">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Blog Category Details</h1>
                <p class="text-zinc-600 dark:text-zinc-400">View and manage blog category information</p>
            </div>
            <div class="flex items-center gap-3" x-data="{ showDeleteModal: false }">
                <x-ui.dropdown
                    :menuItems="[
                        [
                            'label' => 'Edit Category',
                            'icon' => 'edit',
                            'href' => route('admin.content.blog-category.edit', $blogCategory),
                        ],
                        [
                            'label' => 'Delete Category',
                            'icon' => 'trash',
                            'type' => 'button',
                            'action' => 'showDeleteModal = true',
                            'color' => 'red',
                        ],
                    ]"
                >
                    <x-slot:trigger>
                        <x-button variant="primary" icon="cog" icon-position="left" type="button">
                            Actions
                        </x-button>
                    </x-slot:trigger>
                </x-ui.dropdown>
                <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.blog-category.index') }}">Back to Categories</x-button>
                
                <x-ui.modal alpine-show="showDeleteModal" size="sm">
                    <x-slot:title>Delete Blog Category</x-slot:title>
                    <p class="text-zinc-600 dark:text-zinc-400">Are you sure you want to delete <strong>{{ $blogCategory->name }}</strong>? This action cannot be undone.</p>
                    <x-slot:footer>
                        <x-button variant="secondary" x-on:click="showDeleteModal = false">Cancel</x-button>
                        <x-button 
                            variant="primary"
                            color="red"
                            x-on:click="axios.post('{{ route('admin.content.blog-category.destroy', $blogCategory) }}', { _method: 'DELETE' }).then(() => { toastManager.show('success', 'Blog category deleted successfully.'); showDeleteModal = false; setTimeout(() => window.location.href = '{{ route('admin.content.blog-category.index') }}', 500); }).catch(() => { toastManager.show('error', 'An error occurred while deleting the blog category.'); showDeleteModal = false; });"
                        >Delete</x-button>
                    </x-slot:footer>
                </x-ui.modal>
            </div>
        </div>

        <!-- Category Information Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="p-6">
                <div class="flex items-start gap-6">
                    <!-- Color Preview -->
                    <div class="w-20 h-20 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: {{ $blogCategory->color ?? '#3B82F6' }}">
                        <i class="fas fa-tag text-white text-2xl"></i>
                    </div>

                    <!-- Category Info -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">
                            {{ $blogCategory->name }}
                        </h2>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
                                <i class="fas fa-link text-sm"></i>
                                <span>{{ $blogCategory->slug }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
                                <i class="fas fa-calendar text-sm"></i>
                                <span>Created {{ $blogCategory->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Category Information Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[var(--color-accent)]"></i>
                        Category Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Name</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $blogCategory->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Slug</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $blogCategory->slug }}</p>
                        </div>
                        @if($blogCategory->description)
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Description</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $blogCategory->description }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Color</label>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="w-6 h-6 rounded" style="background-color: {{ $blogCategory->color ?? '#3B82F6' }}"></span>
                                <span class="text-zinc-900 dark:text-white">{{ $blogCategory->color ?? '#3B82F6' }}</span>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Category ID</label>
                            <p class="text-zinc-900 dark:text-white mt-1">#{{ $blogCategory->id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-clock text-[var(--color-accent)]"></i>
                        Statistics & Dates
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Blog Posts</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $blogCategory->blogs()->count() }} posts</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Created At</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $blogCategory->created_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Last Updated</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $blogCategory->updated_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
