<x-layouts.admin title="Blog Details">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Blog Details</h1>
                <p class="text-zinc-600 dark:text-zinc-400">View and manage blog post information</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.blog.index') }}">Back to Blogs</x-button>
                <x-button variant="primary" icon="edit" icon-position="left" href="{{ route('admin.content.blog.edit', $blog) }}">Edit Blog</x-button>
            </div>
        </div>

        <!-- Blog Header Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="p-6">
                <div class="flex items-start gap-6">
                    <!-- Featured Image -->
                    @if($blog->image)
                        <div class="w-40 h-28 flex-shrink-0 rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700">
                            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-40 h-28 rounded-lg bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-image text-zinc-400 text-3xl"></i>
                        </div>
                    @endif

                    <!-- Blog Info -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">
                            {{ $blog->title }}
                        </h2>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
                                <i class="fas fa-folder text-sm"></i>
                                <span>{{ $blog->blog_category?->name ?? 'Uncategorized' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
                                <i class="fas fa-user text-sm"></i>
                                <span>{{ $blog->author?->name ?? 'Unknown Author' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
                                <i class="fas fa-calendar text-sm"></i>
                                <span>Published {{ $blog->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                @if($blog->is_active)
                                    <x-badge variant="success" icon="check">Active</x-badge>
                                @else
                                    <x-badge variant="warning" icon="times">Inactive</x-badge>
                                @endif
                                @if($blog->is_featured)
                                    <x-badge variant="primary" icon="star">Featured</x-badge>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Blog Content Card -->
            <div class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-align-left text-[var(--color-accent)]"></i>
                        Content
                    </h3>
                    
                    <!-- Short Description -->
                    <div class="mb-6">
                        <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Short Description</label>
                        <p class="text-zinc-900 dark:text-white mt-1">{{ $blog->short_body }}</p>
                    </div>
                    
                    <!-- Full Content -->
                    <div>
                        <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Full Content</label>
                        <div class="prose dark:prose-invert max-w-none mt-2 p-4 bg-zinc-50 dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700">
                            {!! $blog->long_body !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Blog Information Card -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-[var(--color-accent)]"></i>
                            Information
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Blog ID</label>
                                <p class="text-zinc-900 dark:text-white mt-1">#{{ $blog->id }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Slug</label>
                                <p class="text-zinc-900 dark:text-white mt-1 break-all">{{ $blog->slug }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Category</label>
                                <p class="text-zinc-900 dark:text-white mt-1">
                                    @if($blog->blog_category)
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="w-3 h-3 rounded-full" style="background-color: {{ $blog->blog_category->color ?? '#3B82F6' }}"></span>
                                            {{ $blog->blog_category->name }}
                                        </span>
                                    @else
                                        <span class="text-zinc-400">Uncategorized</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Author</label>
                                <p class="text-zinc-900 dark:text-white mt-1">{{ $blog->author?->name ?? 'Unknown' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dates Card -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-clock text-[var(--color-accent)]"></i>
                            Dates
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Created At</label>
                                <p class="text-zinc-900 dark:text-white mt-1">{{ $blog->created_at->format('F d, Y \a\t h:i A') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Last Updated</label>
                                <p class="text-zinc-900 dark:text-white mt-1">{{ $blog->updated_at->format('F d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Card -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-toggle-on text-[var(--color-accent)]"></i>
                            Status
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Visibility</label>
                                <p class="mt-1">
                                    @if($blog->is_active)
                                        <x-badge variant="success" icon="check">Active</x-badge>
                                    @else
                                        <x-badge variant="warning" icon="times">Inactive</x-badge>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Featured</label>
                                <p class="mt-1">
                                    @if($blog->is_featured)
                                        <x-badge variant="primary" icon="star">Featured</x-badge>
                                    @else
                                        <x-badge variant="secondary">Not Featured</x-badge>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-cog text-[var(--color-accent)]"></i>
                    Actions
                </h3>
                <div class="flex items-center gap-3" x-data="{ showDeleteModal: false }">
                    <x-button variant="primary" icon="edit" icon-position="left" href="{{ route('admin.content.blog.edit', $blog) }}">Edit Blog</x-button>
                    @if($blog->slug && Route::has('blog.show'))
                        <x-button variant="secondary" icon="external-link-alt" icon-position="left" href="{{ route('blog.show', $blog->slug) }}" target="_blank">View on Site</x-button>
                    @endif
                    <x-button 
                        variant="error" 
                        icon="trash" 
                        icon-position="left" 
                        type="button"
                        x-on:click="showDeleteModal = true"
                    >Delete Blog</x-button>
                    
                    <x-ui.modal alpine-show="showDeleteModal" size="sm">
                        <x-slot:title>Delete Blog</x-slot:title>
                        <p class="text-zinc-600 dark:text-zinc-400">Are you sure you want to delete <strong>{{ $blog->title }}</strong>? This action cannot be undone.</p>
                        <x-slot:footer>
                            <x-button variant="secondary" x-on:click="showDeleteModal = false">Cancel</x-button>
                            <x-button 
                                variant="primary"
                                color="red"
                                x-on:click="axios.post('{{ route('admin.content.blog.destroy', $blog) }}', { _method: 'DELETE' }).then(() => { toastManager.show('success', 'Blog deleted successfully.'); showDeleteModal = false; setTimeout(() => window.location.href = '{{ route('admin.content.blog.index') }}', 500); }).catch(() => { toastManager.show('error', 'An error occurred while deleting the blog.'); showDeleteModal = false; });"
                            >Delete</x-button>
                        </x-slot:footer>
                    </x-ui.modal>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
