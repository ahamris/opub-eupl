<x-layouts.admin title="Edit Testimonial">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Testimonial</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Update customer testimonial</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.homepage.testimonial.index') }}">Back</x-button>
        </div>

        <form action="{{ route('admin.content.homepage.testimonial.update', $testimonial) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

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
                            value="{{ old('quote', $testimonial->quote) }}"
                            required
                        />

                        <div class="grid grid-cols-2 gap-4">
                            <x-input 
                                label="Author Name" 
                                name="author" 
                                type="text" 
                                placeholder="Full name"
                                value="{{ old('author', $testimonial->author) }}"
                                required
                            />
                            <x-input 
                                label="Role/Title" 
                                name="role" 
                                type="text" 
                                placeholder="e.g. Journalist"
                                value="{{ old('role', $testimonial->role) }}"
                            />
                        </div>

                        <x-input 
                            label="Organization" 
                            name="organization" 
                            type="text" 
                            placeholder="Company or organization name"
                            value="{{ old('organization', $testimonial->organization) }}"
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
                            :checked="old('is_active', $testimonial->is_active)"
                            hint="Show this testimonial"
                        />

                        <x-input 
                            label="Sort Order"
                            name="sort_order" 
                            type="number" 
                            placeholder="0"
                            value="{{ old('sort_order', $testimonial->sort_order) }}"
                        />
                    </div>

                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">Avatar</h2>
                        
                        @if($testimonial->avatar_url)
                        <div class="mb-4">
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">Current Avatar:</p>
                            <div class="relative inline-block">
                                <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->author }}" class="w-20 h-20 rounded-full object-cover border border-zinc-200 dark:border-zinc-700">
                                <label class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center cursor-pointer hover:bg-red-600">
                                    <input type="checkbox" name="remove_avatar" value="1" class="sr-only">
                                    <i class="fas fa-times text-xs"></i>
                                </label>
                            </div>
                        </div>
                        @endif

                        <x-ui.file-upload 
                            name="avatar"
                            label="Upload New Avatar"
                            accept="image/*"
                        />
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Recommended: 256×256px square image</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <x-button variant="secondary" type="button" href="{{ route('admin.content.homepage.testimonial.index') }}">Cancel</x-button>
                <x-button variant="primary" type="submit" icon="save" icon-position="left">Update Testimonial</x-button>
            </div>
        </form>
    </div>
</x-layouts.admin>
