<x-layouts.admin title="Blog Categories">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Blog Categories</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage all blog categories in your system</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button 
                    variant="primary" 
                    icon="plus" 
                    icon-position="left" 
                    type="button"
                    x-on:click="$dispatch('open-drawer', { id: 'drawer-create-blog-category' })"
                >
                    Add New Category
                </x-button>
            </div>
        </div>

        <!-- Blog Categories Table -->
        <livewire:admin.table
            resource="blog_categories"
            :columns="[
                'id',
                'name',
                'slug',
                ['key' => 'color', 'type' => 'color'],
                ['key' => 'created_at', 'format' => 'date'],
            ]"
            route-prefix="admin.content.blog-category"
            search-placeholder="Search blog categories..."
            :paginate="15"
        />

        <!-- Drawers for Blog Categories (only for current page items) -->
        @php
            // Get current page items from Livewire component
            // Note: This is a workaround - ideally we'd get items from the table component
            // For now, we'll use pagination to limit drawer creation
            $blogCategories = \App\Models\BlogCategory::orderBy('created_at', 'desc')->limit(50)->get();
        @endphp
        @foreach($blogCategories as $blogCategory)
            <!-- View Drawer -->
            <x-ui.drawer 
                id="drawer-view-{{ $blogCategory->id }}"
                title="View Blog Category"
                width="max-w-2xl"
            >
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Name</label>
                            <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $blogCategory->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Slug</label>
                            <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $blogCategory->slug }}</p>
                        </div>
                        @if($blogCategory->description)
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Description</label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $blogCategory->description }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Color</label>
                            <p class="mt-1 flex items-center gap-2">
                                <span class="inline-block w-6 h-6 rounded border border-zinc-300 dark:border-zinc-600" style="background-color: {{ $blogCategory->color ?? '#3B82F6' }}"></span>
                                <span class="text-sm text-zinc-900 dark:text-white">{{ $blogCategory->color ?? '#3B82F6' }}</span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Created At</label>
                            <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $blogCategory->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </div>
            </x-ui.drawer>

            <!-- Edit Drawer -->
            <x-ui.drawer 
                id="drawer-edit-{{ $blogCategory->id }}"
                title="Edit Blog Category"
                width="max-w-2xl"
            >
                <form 
                    action="{{ route('admin.content.blog-category.update', $blogCategory) }}" 
                    method="POST" 
                    class="space-y-6"
                    x-on:submit="setTimeout(() => { $dispatch('close-drawer', { id: 'drawer-edit-{{ $blogCategory->id }}' }); }, 100)"
                >
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
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
                        </div>

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
                        </div>

                        <div>
                            <x-ui.textarea 
                                label="Description" 
                                name="description" 
                                placeholder="Enter category description"
                                rows="3"
                                value="{{ old('description', $blogCategory->description) }}"
                            />
                        </div>

                        <div>
                            <x-ui.color-picker 
                                label="Color" 
                                name="color" 
                                value="{{ old('color', $blogCategory->color ?? '#3B82F6') }}"
                                :show-presets="true"
                            />
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <x-button 
                            variant="secondary" 
                            type="button" 
                            x-on:click="$dispatch('close-drawer', { id: 'drawer-edit-{{ $blogCategory->id }}' })"
                        >
                            Cancel
                        </x-button>
                        <x-button variant="primary" type="submit" icon="save" icon-position="left">
                            Update Category
                        </x-button>
                    </div>
                </form>
            </x-ui.drawer>
        @endforeach

        <!-- Create Drawer -->
        <x-ui.drawer 
            id="drawer-create-blog-category"
            title="Create Blog Category"
            width="max-w-2xl"
        >
            <form 
                action="{{ route('admin.content.blog-category.store') }}" 
                method="POST" 
                class="space-y-6"
                x-on:submit="setTimeout(() => { $dispatch('close-drawer', { id: 'drawer-create-blog-category' }); }, 100)"
            >
                @csrf

                <div class="space-y-4">
                    <div>
                        <x-input 
                            label="Name" 
                            name="name" 
                            type="text" 
                            placeholder="Enter category name"
                            icon="tag"
                            value="{{ old('name') }}"
                            required
                        />
                    </div>

                    <div>
                        <x-input 
                            label="Slug" 
                            name="slug" 
                            type="text" 
                            placeholder="Leave empty to auto-generate from name"
                            icon="link"
                            value="{{ old('slug') }}"
                        />
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Leave blank to auto-generate from name</p>
                    </div>

                    <div>
                        <x-ui.textarea 
                            label="Description" 
                            name="description" 
                            placeholder="Enter category description"
                            rows="3"
                            value="{{ old('description') }}"
                        />
                    </div>

                    <div>
                        <x-ui.color-picker 
                            label="Color" 
                            name="color" 
                            value="{{ old('color', '#3B82F6') }}"
                            :show-presets="true"
                        />
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <x-button 
                        variant="secondary" 
                        type="button" 
                        x-on:click="$dispatch('close-drawer', { id: 'drawer-create-blog-category' })"
                    >
                        Cancel
                    </x-button>
                    <x-button variant="primary" type="submit" icon="save" icon-position="left">
                        Create Category
                    </x-button>
                </div>
            </form>
        </x-ui.drawer>
    </div>
</x-layouts.admin>
