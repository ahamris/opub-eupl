<x-layouts.admin title="Create Testimonial">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create Testimonial</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Add a new customer testimonial</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.homepage.testimonial.index') }}">Back</x-button>
        </div>

        <form action="{{ route('admin.content.homepage.testimonial.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">Testimonial</h2>
                        
                        <x-ui.textarea 
                            label="Quote" 
                            name="quote" 
                            placeholder="What did the customer say?"
                            rows="4"
                            value="{{ old('quote') }}"
                            required
                        />

                        <div class="grid grid-cols-2 gap-4">
                            <x-input 
                                label="Author Name" 
                                name="author" 
                                type="text" 
                                placeholder="Full name"
                                value="{{ old('author') }}"
                                required
                            />
                            <x-input 
                                label="Role/Title" 
                                name="role" 
                                type="text" 
                                placeholder="e.g. Journalist"
                                value="{{ old('role') }}"
                            />
                        </div>

                        <x-input 
                            label="Organization" 
                            name="organization" 
                            type="text" 
                            placeholder="Company or organization name"
                            value="{{ old('organization') }}"
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
                            :checked="old('is_active', true)"
                            hint="Show this testimonial"
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
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">Avatar</h2>
                        
                        <x-ui.file-upload 
                            name="avatar"
                            label="Profile Photo"
                            accept="image/*"
                        />
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Recommended: 256×256px square image</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <x-button variant="secondary" type="button" href="{{ route('admin.content.homepage.testimonial.index') }}">Cancel</x-button>
                <x-button variant="primary" type="submit" icon="save" icon-position="left">Create Testimonial</x-button>
            </div>
        </form>
    </div>
</x-layouts.admin>
