<x-layouts.admin title="Create Static Page">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create Static Page</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Add a new static page to your website</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.static-page.index') }}">Back to Pages</x-button>
        </div>

        <form action="{{ route('admin.content.static-page.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column (Content) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Main Content Card -->
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-6">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">Page Content</h2>
                        
                        <!-- Title Field -->
                        <div>
                            <x-input 
                                label="Title" 
                                name="title" 
                                type="text" 
                                placeholder="Enter page title"
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
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">URL path: /pagina/<span class="font-mono">your-slug</span></p>
                            @error('slug')
                                <p class="mt-1 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subtitle Field -->
                        <div>
                            <x-input 
                                label="Subtitle" 
                                name="subtitle" 
                                type="text" 
                                placeholder="Small uppercase text above title (e.g. 'Open Source Platform')"
                                value="{{ old('subtitle') }}"
                            />
                            @error('subtitle')
                                <p class="mt-1 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Short Description Field -->
                        <div>
                            <x-ui.textarea 
                                label="Short Description" 
                                name="short_description" 
                                placeholder="Brief description shown in the page header section"
                                rows="3"
                                value="{{ old('short_description') }}"
                            />
                            @error('short_description')
                                <p class="mt-1 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CTA Buttons (Compact) -->
                        <div class="pt-2">
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fas fa-mouse-pointer text-zinc-400 text-sm"></i>
                                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Call-to-Action Buttons</span>
                                <span class="text-xs text-zinc-400">(optional)</span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Button 1 -->
                                <div class="p-3 bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-lg space-y-2">
                                    <div class="flex items-center gap-1.5 mb-2">
                                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold">1</span>
                                        <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">Primary</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <x-ui.input name="button_1_text" placeholder="Text" size="sm" value="{{ old('button_1_text') }}" />
                                        <x-ui.input name="button_1_url" placeholder="URL" size="sm" value="{{ old('button_1_url') }}" />
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <x-ui.select name="button_1_style" size="sm" :options="['primary' => 'Filled', 'secondary' => 'Secondary', 'outline' => 'Outline']" value="{{ old('button_1_style', 'primary') }}" />
                                        <x-ui.input name="button_1_icon" placeholder="Icon (arrow-right)" size="sm" value="{{ old('button_1_icon') }}" />
                                    </div>
                                    <label class="flex items-center gap-1.5 cursor-pointer">
                                        <input type="checkbox" name="button_1_new_tab" value="1" {{ old('button_1_new_tab') ? 'checked' : '' }} class="w-3 h-3 rounded border-zinc-300 text-indigo-600">
                                        <span class="text-xs text-zinc-500">New tab</span>
                                    </label>
                                </div>

                                <!-- Button 2 -->
                                <div class="p-3 bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-lg space-y-2">
                                    <div class="flex items-center gap-1.5 mb-2">
                                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-zinc-500 text-white text-[10px] font-bold">2</span>
                                        <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">Secondary</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <x-ui.input name="button_2_text" placeholder="Text" size="sm" value="{{ old('button_2_text') }}" />
                                        <x-ui.input name="button_2_url" placeholder="URL" size="sm" value="{{ old('button_2_url') }}" />
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <x-ui.select name="button_2_style" size="sm" :options="['primary' => 'Filled', 'secondary' => 'Secondary', 'outline' => 'Outline']" value="{{ old('button_2_style', 'secondary') }}" />
                                        <x-ui.input name="button_2_icon" placeholder="Icon (external-link)" size="sm" value="{{ old('button_2_icon') }}" />
                                    </div>
                                    <label class="flex items-center gap-1.5 cursor-pointer">
                                        <input type="checkbox" name="button_2_new_tab" value="1" {{ old('button_2_new_tab') ? 'checked' : '' }} class="w-3 h-3 rounded border-zinc-300 text-indigo-600">
                                        <span class="text-xs text-zinc-500">New tab</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Content Field (Quill Editor) -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-2">
                                Content
                            </label>
                            <x-quill 
                                name="content" 
                                :value="old('content')" 
                                placeholder="Write your page content here..."
                                class="h-[400px] fixed-height"
                            />
                            @error('content')
                                <p class="mt-1 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column (SEO & Settings) -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Publish Settings Card -->
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                            <i class="fas fa-cog mr-2 text-zinc-500"></i>Publish Settings
                        </h2>
                        
                        <x-ui.checkbox 
                            label="Active" 
                            name="is_active" 
                            value="1"
                            :checked="old('is_active', true)"
                            hint="Enable this page to be visible on the website"
                        />

                        <x-input 
                            label="Sort Order"
                            name="sort_order" 
                            type="number" 
                            placeholder="0"
                            value="{{ old('sort_order', 0) }}"
                        />
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 -mt-2">Lower numbers appear first in listings</p>
                    </div>

                    <!-- SEO Settings Card -->
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                            <i class="fas fa-search mr-2 text-zinc-500"></i>SEO Settings
                        </h2>
                        
                        <x-ui.textarea 
                            label="Meta Description" 
                            name="meta_description" 
                            placeholder="Brief description for search engines (150-160 chars)"
                            rows="3"
                            value="{{ old('meta_description') }}"
                        />
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 -mt-2">Recommended: 150-160 characters</p>

                        <x-ui.textarea 
                            label="Meta Keywords" 
                            name="meta_keywords" 
                            placeholder="keyword1, keyword2, keyword3"
                            rows="2"
                            value="{{ old('meta_keywords') }}"
                        />
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 -mt-2">Comma-separated keywords</p>

                        <x-ui.select 
                            label="Robots Meta" 
                            name="meta_robots" 
                            :options="[
                                'index, follow' => 'Index, Follow (Default)',
                                'noindex, follow' => 'No Index, Follow',
                                'index, nofollow' => 'Index, No Follow',
                                'noindex, nofollow' => 'No Index, No Follow',
                            ]"
                            value="{{ old('meta_robots', 'index, follow') }}"
                        />

                        <x-input 
                            label="Canonical URL"
                            name="canonical_url" 
                            type="url" 
                            placeholder="https://example.com/original-page"
                            value="{{ old('canonical_url') }}"
                        />
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 -mt-2">Leave empty if this is the canonical version</p>
                    </div>

                    <!-- Open Graph Card -->
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                            <i class="fas fa-share-nodes mr-2 text-zinc-500"></i>Social Sharing (Open Graph)
                        </h2>
                        
                        <x-input 
                            label="OG Title"
                            name="og_title" 
                            type="text" 
                            placeholder="Leave empty to use page title"
                            value="{{ old('og_title') }}"
                        />

                        <x-ui.textarea 
                            label="OG Description" 
                            name="og_description" 
                            placeholder="Leave empty to use meta description"
                            rows="2"
                            value="{{ old('og_description') }}"
                        />

                        <div>
                            <x-ui.file-upload 
                                name="og_image"
                                label="OG Image"
                                accept="image/*"
                            />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Recommended: 1200×630px (JPG, PNG, WebP)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <x-button variant="secondary" type="button" href="{{ route('admin.content.static-page.index') }}">Cancel</x-button>
                <x-button variant="primary" type="submit" icon="save" icon-position="left">Create Page</x-button>
            </div>
        </form>
    </div>
</x-layouts.admin>
