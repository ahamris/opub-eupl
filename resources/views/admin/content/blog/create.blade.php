<x-layouts.admin title="Create Blog">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create Blog</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Add a new blog post to your system</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.blog.index') }}">Back to Blogs</x-button>
        </div>

        <form action="{{ route('admin.content.blog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column (Content) -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-6">
                        <!-- Title Field -->
                        <div>
                            <x-input 
                                label="Title" 
                                name="title" 
                                type="text" 
                                placeholder="Enter blog title"
                                icon="heading"
                                value="{{ old('title') }}"
                                required
                            />
                            @error('title')
                                <p class="mt-1 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug Field -->
                        <div>
                            <x-input 
                                label="Slug" 
                                name="slug" 
                                type="text" 
                                placeholder="Leave empty to auto-generate from title"
                                icon="link"
                                value="{{ old('slug') }}"
                            />
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Leave blank to auto-generate from title</p>
                            @error('slug')
                                <p class="mt-1 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Short Body Field -->
                        <div>
                            <x-ui.textarea 
                                label="Short Description" 
                                name="short_body" 
                                placeholder="Enter a brief description for the blog preview"
                                rows="3"
                                value="{{ old('short_body') }}"
                                required
                            />
                            @error('short_body')
                                <p class="mt-1 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Long Body Field (Quill Editor) -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-2">
                                Content <span class="text-red-600 dark:text-red-400">*</span>
                            </label>
                            <x-quill 
                                name="long_body" 
                                :value="old('long_body')" 
                                placeholder="Write your blog content here..."
                                class="h-[300px] fixed-height"
                            />
                            @error('long_body')
                                <p class="mt-1 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column (Settings) -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-6">
                        <!-- Category Field -->
                        <div>
                            <x-ui.select 
                                label="Category" 
                                name="blog_category_id" 
                                placeholder="Select a category"
                                :options="$blogCategories->pluck('name', 'id')->toArray()"
                                value="{{ old('blog_category_id') }}"
                                required
                            />
                            @error('blog_category_id')
                                <p class="mt-1 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Author Field -->
                        <div>
                            <x-ui.select 
                                label="Author" 
                                name="author_id" 
                                placeholder="Select an author"
                                :options="$authors->pluck('name', 'id')->toArray()"
                                value="{{ old('author_id') }}"
                                required
                            />
                            @error('author_id')
                                <p class="mt-1 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image Upload Field -->
                        <div>
                            <x-ui.file-upload 
                                name="image"
                                label="Cover Picture"
                            />
                            @error('image')
                                <p class="mt-1 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Toggle Fields -->
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-zinc-900 dark:text-zinc-100">Visibility</label>
                            
                            <div>
                                <x-ui.checkbox 
                                    label="Active" 
                                    name="is_active" 
                                    value="1"
                                    :checked="old('is_active', true)"
                                    hint="Enable this blog post to be visible on the website"
                                />
                                @error('is_active')
                                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-ui.checkbox 
                                    label="Featured" 
                                    name="is_featured" 
                                    value="1"
                                    :checked="old('is_featured', false)"
                                    hint="Feature this blog post on the homepage carousel"
                                />
                                @error('is_featured')
                                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <x-button variant="secondary" type="button" href="{{ route('admin.content.blog.index') }}">Cancel</x-button>
                <x-button variant="primary" type="submit" icon="save" icon-position="left">Create Blog</x-button>
            </div>
        </form>
    </div>
</x-layouts.admin>
