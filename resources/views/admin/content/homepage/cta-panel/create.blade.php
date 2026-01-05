<x-layouts.admin title="Create CTA Panel">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create CTA Panel</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Add a new call-to-action panel</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.homepage.cta-panel.index') }}">Back</x-button>
        </div>

        <form action="{{ route('admin.content.homepage.cta-panel.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                            placeholder="Panel title"
                            value="{{ old('title') }}"
                            required
                        />

                        <x-input 
                            label="Slug" 
                            name="slug" 
                            type="text" 
                            placeholder="Leave empty to auto-generate"
                            value="{{ old('slug') }}"
                        />

                        <x-ui.textarea 
                            label="Description" 
                            name="description" 
                            placeholder="Panel description text"
                            rows="3"
                            value="{{ old('description') }}"
                        />

                        <div class="grid grid-cols-2 gap-4">
                            <x-input 
                                label="Primary Button Text" 
                                name="primary_button_text" 
                                type="text" 
                                placeholder="e.g. Get Started"
                                value="{{ old('primary_button_text') }}"
                            />
                            <x-input 
                                label="Primary Button URL" 
                                name="primary_button_url" 
                                type="text" 
                                placeholder="/contact or https://..."
                                value="{{ old('primary_button_url') }}"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <x-input 
                                label="Secondary Button Text" 
                                name="secondary_button_text" 
                                type="text" 
                                placeholder="e.g. Learn More"
                                value="{{ old('secondary_button_text') }}"
                            />
                            <x-input 
                                label="Secondary Button URL" 
                                name="secondary_button_url" 
                                type="text" 
                                placeholder="/about or https://..."
                                value="{{ old('secondary_button_url') }}"
                            />
                        </div>
                    </div>

                    <!-- Screenshot -->
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">Screenshot</h2>
                        
                        <x-ui.file-upload 
                            name="screenshot"
                            label="Panel Screenshot"
                            accept="image/*"
                        />
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Recommended: 1824×1080px (PNG, JPG, WebP)</p>

                        <x-input 
                            label="Screenshot Alt Text" 
                            name="screenshot_alt" 
                            type="text" 
                            placeholder="Describe the screenshot"
                            value="{{ old('screenshot_alt') }}"
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
                            hint="Show this panel on the homepage"
                        />

                        <x-ui.select 
                            label="Variant" 
                            name="variant" 
                            :options="['purple' => 'Purple', 'primary' => 'Primary']"
                            value="{{ old('variant', 'purple') }}"
                        />

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
            <div class="flex items-center justify-end gap-3 pt-4">
                <x-button variant="secondary" type="button" href="{{ route('admin.content.homepage.cta-panel.index') }}">Cancel</x-button>
                <x-button variant="primary" type="submit" icon="save" icon-position="left">Create Panel</x-button>
            </div>
        </form>
    </div>
</x-layouts.admin>
