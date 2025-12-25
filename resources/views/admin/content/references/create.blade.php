<x-layouts.admin title="Create Reference">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create Reference</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Add a new external link</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.reference.index') }}">Back</x-button>
        </div>

        <form action="{{ route('admin.content.reference.store') }}" method="POST" class="space-y-6">
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
                            placeholder="Reference title"
                            value="{{ old('title') }}"
                            required
                        />

                        <x-ui.textarea 
                            label="Description" 
                            name="description" 
                            placeholder="Brief description"
                            rows="3"
                            value="{{ old('description') }}"
                        />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input 
                                label="Link URL" 
                                name="link_url" 
                                type="text" 
                                placeholder="https://example.com"
                                value="{{ old('link_url') }}"
                            />
                            <x-input 
                                label="Link Text" 
                                name="link_text" 
                                type="text" 
                                placeholder="e.g. example.com"
                                value="{{ old('link_text') }}"
                            />
                        </div>
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
                            hint="Show this reference on the page"
                        />

                        <x-input 
                            label="Icon Class" 
                            name="icon" 
                            type="text" 
                            placeholder="fas fa-link"
                            value="{{ old('icon', 'fas fa-link') }}"
                            required
                        />
                        <p class="text-xs text-zinc-500">Use FontAwesome classes (e.g. fas fa-gavel)</p>

                        <x-input 
                            label="Sort Order"
                            name="sort_order" 
                            type="number" 
                            placeholder="0"
                            value="{{ old('sort_order', 0) }}"
                        />
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <x-button variant="secondary" type="button" href="{{ route('admin.content.reference.index') }}">Cancel</x-button>
                <x-button variant="primary" type="submit" icon="save" icon-position="left">Create Reference</x-button>
            </div>
        </form>
    </div>
</x-layouts.admin>
