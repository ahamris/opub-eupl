<x-layouts.admin title="Edit Blog Category">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Blog Category</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Update blog category information</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.blog-category.index') }}">Back to Categories</x-button>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <form action="{{ route('admin.content.blog-category.update', $blogCategory) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Name Field -->
                <div>
                    <x-input 
                        label="Name" 
                        name="name" 
                        type="text" 
                        placeholder="Enter category name"
                        icon="tag"
                        value="{{ old('name', $blogCategory->name) }}"
                        required
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug Field -->
                <div>
                    <x-input 
                        label="Slug" 
                        name="slug" 
                        type="text" 
                        placeholder="Leave empty to auto-generate from name"
                        icon="link"
                        value="{{ old('slug', $blogCategory->slug) }}"
                    />
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Leave blank to auto-generate from name</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Field -->
                <div>
                    <x-ui.textarea 
                        label="Description" 
                        name="description" 
                        placeholder="Enter category description"
                        rows="3"
                        value="{{ old('description', $blogCategory->description) }}"
                    />
                    @error('description')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color Field -->
                <div>
                    <x-ui.color-picker 
                        label="Color" 
                        name="color" 
                        value="{{ old('color', $blogCategory->color ?? '#3B82F6') }}"
                        :show-presets="true"
                    />
                    @error('color')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <x-button variant="secondary" type="button" href="{{ route('admin.content.blog-category.index') }}">Cancel</x-button>
                    <x-button variant="primary" type="submit" icon="save" icon-position="left">Update Category</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
