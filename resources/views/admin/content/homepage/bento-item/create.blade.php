<x-layouts.admin title="Create Bento Item">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create Bento Item</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Add a new item to the bento grid</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.homepage.bento-item.index') }}">Back</x-button>
        </div>

        <form action="{{ route('admin.content.homepage.bento-item.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

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
                            value="{{ old('title') }}"
                            required
                        />

                        <x-ui.textarea 
                            label="Description" 
                            name="description" 
                            placeholder="Brief description"
                            rows="2"
                            value="{{ old('description') }}"
                        />

                        <x-input 
                            label="URL" 
                            name="url" 
                            type="text" 
                            placeholder="/route or https://..."
                            value="{{ old('url') }}"
                        />

                        <x-ui.file-upload 
                            name="image"
                            label="Image"
                            accept="image/*"
                        />
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Or enter an external URL in the field above for external images</p>
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
                            :checked="old('is_active', true)"
                            hint="Show this item in the grid"
                        />

                        <x-ui.select 
                            label="Column Span" 
                            name="col_span" 
                            :options="['2' => 'Small (2 columns)', '4' => 'Large (4 columns)']"
                            value="{{ old('col_span', '2') }}"
                        />

                        <x-input 
                            label="Sort Order"
                            name="sort_order" 
                            type="number" 
                            placeholder="0"
                            value="{{ old('sort_order', 0) }}"
                        />
                    </div>

                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">Coming Soon Badge</h2>
                        
                        <x-ui.checkbox 
                            label="Mark as Coming Soon" 
                            name="is_coming_soon" 
                            value="1"
                            :checked="old('is_coming_soon', false)"
                            hint="Disable click and show badge"
                        />

                        <x-input 
                            label="Badge Text"
                            name="coming_soon_text" 
                            type="text" 
                            placeholder="Coming Soon"
                            value="{{ old('coming_soon_text', 'Coming Soon') }}"
                        />
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <x-button variant="secondary" type="button" href="{{ route('admin.content.homepage.bento-item.index') }}">Cancel</x-button>
                <x-button variant="primary" type="submit" icon="save" icon-position="left">Create Item</x-button>
            </div>
        </form>
    </div>
</x-layouts.admin>
