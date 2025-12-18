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

        <!-- Form Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <form action="{{ route('admin.content.blog.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title Field -->
                    <div class="md:col-span-2">
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
                    <div class="md:col-span-2">
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
                <div x-data="{ editorContent: @js(old('long_body', '')) }">
                    <label class="block text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-2">
                        Content <span class="text-red-600 dark:text-red-400">*</span>
                    </label>
                    <div
                        x-data="{
                            editor: null,
                            init() {
                                if (!window.Quill) {
                                    setTimeout(() => this.init(), 100);
                                    return;
                                }
                                
                                this.editor = new window.Quill(this.$refs.editor, {
                                    theme: 'snow',
                                    placeholder: 'Write your blog content here...',
                                });
                                
                                if (this.$root.editorContent) {
                                    this.editor.root.innerHTML = this.$root.editorContent;
                                }
                                
                                this.editor.on('text-change', () => {
                                    this.$root.editorContent = this.editor.root.innerHTML;
                                });
                            }
                        }"
                        class="quill-wrapper"
                        style="min-height: 400px;"
                    >
                        <div x-ref="editor" class="bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-b-md"></div>
                    </div>
                    <input type="hidden" name="long_body" x-bind:value="editorContent">
                    @error('long_body')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload Field -->
                <div x-data="{ 
                    preview: null,
                    handleFileSelect(event) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (e) => this.preview = e.target.result;
                            reader.readAsDataURL(file);
                        }
                    }
                }">
                    <label class="block text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-2">
                        Featured Image
                    </label>
                    <div class="flex items-start gap-4">
                        <div class="flex-1">
                            <input 
                                type="file" 
                                name="image" 
                                accept="image/*"
                                x-on:change="handleFileSelect($event)"
                                class="block w-full text-sm text-zinc-500 dark:text-zinc-400
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-[var(--color-accent)] file:text-white
                                    hover:file:bg-[var(--color-accent-content)]
                                    cursor-pointer"
                            />
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">PNG, JPG, GIF up to 2MB</p>
                        </div>
                        <div x-show="preview" x-cloak class="flex-shrink-0">
                            <img :src="preview" class="w-32 h-20 object-cover rounded-lg border border-zinc-200 dark:border-zinc-700">
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Toggle Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <x-button variant="secondary" type="button" href="{{ route('admin.content.blog.index') }}">Cancel</x-button>
                    <x-button variant="primary" type="submit" icon="save" icon-position="left">Create Blog</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
