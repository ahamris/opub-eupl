<x-layouts.admin title="Edit Bento Item">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Bento Item</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Update bento grid item</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.homepage.bento-item.index') }}">Back</x-button>
        </div>

        <form action="{{ route('admin.content.homepage.bento-item.update', $bentoItem) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">Content</h2>
                        
                        <x-input 
                            label="Title" 
                            name="title" 
                            type="text" 
                            placeholder="Item title"
                            value="{{ old('title', $bentoItem->title) }}"
                            required
                        />

                        <x-ui.textarea 
                            label="Description" 
                            name="description" 
                            placeholder="Brief description"
                            rows="2"
                            value="{{ old('description', $bentoItem->description) }}"
                        />

                        <x-input 
                            label="URL" 
                            name="url" 
                            type="text" 
                            placeholder="/route or https://..."
                            value="{{ old('url', $bentoItem->url) }}"
                        />

                        @if($bentoItem->image_url)
                        <div class="mb-4">
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">Current Image:</p>
                            <div class="relative inline-block">
                                <img src="{{ $bentoItem->image_url }}" alt="Current image" class="h-32 rounded-md border border-zinc-200 dark:border-zinc-700">
                                <label class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center cursor-pointer hover:bg-red-600">
                                    <input type="checkbox" name="remove_image" value="1" class="sr-only">
                                    <i class="fas fa-times text-xs"></i>
                                </label>
                            </div>
                        </div>
                        @endif

                        <x-ui.file-upload 
                            name="image"
                            label="Upload New Image"
                            accept="image/*"
                        />
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">Settings</h2>
                        
                        <x-ui.checkbox 
                            label="Active" 
                            name="is_active" 
                            value="1"
                            :checked="old('is_active', $bentoItem->is_active)"
                            hint="Show this item in the grid"
                        />

                        <x-ui.select 
                            label="Column Span" 
                            name="col_span" 
                            :options="['2' => 'Small (2 columns)', '4' => 'Large (4 columns)']"
                            value="{{ old('col_span', $bentoItem->col_span) }}"
                        />

                        <x-input 
                            label="Sort Order"
                            name="sort_order" 
                            type="number" 
                            placeholder="0"
                            value="{{ old('sort_order', $bentoItem->sort_order) }}"
                        />
                    </div>

                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">Coming Soon Badge</h2>
                        
                        <x-ui.checkbox 
                            label="Mark as Coming Soon" 
                            name="is_coming_soon" 
                            value="1"
                            :checked="old('is_coming_soon', $bentoItem->is_coming_soon)"
                            hint="Disable click and show badge"
                        />

                        <x-input 
                            label="Badge Text"
                            name="coming_soon_text" 
                            type="text" 
                            placeholder="Coming Soon"
                            value="{{ old('coming_soon_text', $bentoItem->coming_soon_text) }}"
                        />
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <x-button variant="secondary" type="button" href="{{ route('admin.content.homepage.bento-item.index') }}">Cancel</x-button>
                <x-button variant="primary" type="submit" icon="save" icon-position="left">Update Item</x-button>
            </div>
        </form>
    </div>
</x-layouts.admin>
