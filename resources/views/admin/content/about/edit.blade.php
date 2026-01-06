<x-layouts.admin title="About Us Settings">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">About Us Page</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage the content of your About Us page</p>
            </div>
        </div>

        @if(session('success'))
            <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
        @endif

        <form action="{{ route('admin.content.about.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Page Header Section -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <i class="fas fa-heading mr-2 text-zinc-500"></i>Page Header
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input 
                        label="Eyebrow Text" 
                        name="page_eyebrow" 
                        type="text" 
                        placeholder="e.g. Open source Woo-voorziening"
                        value="{{ old('page_eyebrow', $settings->page_eyebrow) }}"
                    />

                    <x-input 
                        label="Page Title" 
                        name="page_title" 
                        type="text" 
                        placeholder="Page title"
                        value="{{ old('page_title', $settings->page_title) }}"
                        required
                    />
                </div>

                <x-ui.textarea 
                    label="Page Description" 
                    name="page_description" 
                    placeholder="Brief description shown in header"
                    rows="3"
                    value="{{ old('page_description', $settings->page_description) }}"
                />
            </div>

            <!-- Introduction Section -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <i class="fas fa-paragraph mr-2 text-zinc-500"></i>Introduction
                </h2>
                
                <x-ui.textarea 
                    label="Introduction Content" 
                    name="intro_content" 
                    placeholder="Opening paragraph"
                    rows="4"
                    value="{{ old('intro_content', $settings->intro_content) }}"
                />
            </div>

            <!-- Section 1: Projectdoelstelling -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                        <i class="fas fa-bullseye mr-2 text-zinc-500"></i>Section 1: Projectdoelstelling
                    </h2>
                    <x-ui.checkbox 
                        label="Active" 
                        name="section1_is_active" 
                        value="1"
                        :checked="old('section1_is_active', $settings->section1_is_active)"
                    />
                </div>
                
                <x-input 
                    label="Section Title" 
                    name="section1_title" 
                    type="text" 
                    value="{{ old('section1_title', $settings->section1_title) }}"
                />

                <div>
                    <x-editor 
                        name="section1_content" 
                        :value="old('section1_content', $settings->section1_content)"
                        placeholder="Enter section content..."
                    />
                </div>
            </div>

            <!-- Section 2: Technische Realisatie -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                        <i class="fas fa-cogs mr-2 text-zinc-500"></i>Section 2: Technische Realisatie
                    </h2>
                    <x-ui.checkbox 
                        label="Active" 
                        name="section2_is_active" 
                        value="1"
                        :checked="old('section2_is_active', $settings->section2_is_active)"
                    />
                </div>
                
                <x-input 
                    label="Section Title" 
                    name="section2_title" 
                    type="text" 
                    value="{{ old('section2_title', $settings->section2_title) }}"
                />

                <x-ui.textarea 
                    label="Introduction Text" 
                    name="section2_intro" 
                    rows="2"
                    value="{{ old('section2_intro', $settings->section2_intro) }}"
                />

                <div class="space-y-3" x-data="{ features: {{ json_encode(old('section2_features', $settings->section2_features ?? [])) }} }">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Features</label>
                    <template x-for="(feature, index) in features" :key="index">
                        <div class="flex gap-3 items-start">
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3">
                                <input type="text" x-model="feature.title" :name="'section2_features['+index+'][title]'" placeholder="Feature title" class="block w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm">
                                <input type="text" x-model="feature.description" :name="'section2_features['+index+'][description]'" placeholder="Feature description" class="block w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm">
                            </div>
                            <button type="button" @click="features.splice(index, 1)" class="text-red-500 hover:text-red-700 p-2"><i class="fas fa-trash"></i></button>
                        </div>
                    </template>
                    <button type="button" @click="features.push({title: '', description: ''})" class="text-sm text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-plus mr-1"></i>Add Feature
                    </button>
                </div>

                <x-ui.textarea 
                    label="Outro Text" 
                    name="section2_outro" 
                    rows="2"
                    value="{{ old('section2_outro', $settings->section2_outro) }}"
                />
            </div>

            <!-- Section 3: Kernwaarden -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                        <i class="fas fa-star mr-2 text-zinc-500"></i>Section 3: Kernwaarden
                    </h2>
                    <x-ui.checkbox 
                        label="Active" 
                        name="section3_is_active" 
                        value="1"
                        :checked="old('section3_is_active', $settings->section3_is_active)"
                    />
                </div>
                
                <x-input 
                    label="Section Title" 
                    name="section3_title" 
                    type="text" 
                    value="{{ old('section3_title', $settings->section3_title) }}"
                />

                <div class="space-y-3" x-data="{ values: {{ json_encode(old('section3_values', $settings->section3_values ?? [])) }} }">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Values</label>
                    <template x-for="(value, index) in values" :key="index">
                        <div class="p-4 border border-zinc-200 dark:border-zinc-700 rounded-md space-y-3">
                            <div class="flex gap-3">
                                <input type="text" x-model="value.icon" :name="'section3_values['+index+'][icon]'" placeholder="Icon class (e.g. fas fa-cloud)" class="w-40 rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm">
                                <input type="text" x-model="value.title" :name="'section3_values['+index+'][title]'" placeholder="Value title" class="flex-1 rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm">
                                <button type="button" @click="values.splice(index, 1)" class="text-red-500 hover:text-red-700 p-2"><i class="fas fa-trash"></i></button>
                            </div>
                            <textarea x-model="value.description" :name="'section3_values['+index+'][description]'" placeholder="Description" rows="2" class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm"></textarea>
                        </div>
                    </template>
                    <button type="button" @click="values.push({icon: 'fas fa-star', title: '', description: ''})" class="text-sm text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-plus mr-1"></i>Add Value
                    </button>
                </div>
            </div>

            <!-- Section 4: Van proof-of-concept -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                        <i class="fas fa-rocket mr-2 text-zinc-500"></i>Section 4: Van proof-of-concept naar gezamenlijke voorziening
                    </h2>
                    <x-ui.checkbox 
                        label="Active" 
                        name="section4_is_active" 
                        value="1"
                        :checked="old('section4_is_active', $settings->section4_is_active)"
                    />
                </div>
                
                <x-input 
                    label="Section Title" 
                    name="section4_title" 
                    type="text" 
                    value="{{ old('section4_title', $settings->section4_title) }}"
                />

                <div>
                    <x-editor 
                        name="section4_content" 
                        :value="old('section4_content', $settings->section4_content)"
                        placeholder="Enter section content..."
                    />
                </div>
            </div>

            <!-- Section 5: Bijdrage aan Woo -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                        <i class="fas fa-balance-scale mr-2 text-zinc-500"></i>Section 5: Bijdrage aan de Wet open overheid (Woo)
                    </h2>
                    <x-ui.checkbox 
                        label="Active" 
                        name="section5_is_active" 
                        value="1"
                        :checked="old('section5_is_active', $settings->section5_is_active)"
                    />
                </div>
                
                <x-input 
                    label="Section Title" 
                    name="section5_title" 
                    type="text" 
                    value="{{ old('section5_title', $settings->section5_title) }}"
                />

                <x-ui.textarea 
                    label="Content" 
                    name="section5_content" 
                    rows="4"
                    value="{{ old('section5_content', $settings->section5_content) }}"
                />
            </div>

            <!-- Contact Section -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                        <i class="fas fa-envelope mr-2 text-zinc-500"></i>Contact CTA
                    </h2>
                    <x-ui.checkbox 
                        label="Active" 
                        name="contact_is_active" 
                        value="1"
                        :checked="old('contact_is_active', $settings->contact_is_active)"
                    />
                </div>
                
                <x-input 
                    label="Title" 
                    name="contact_title" 
                    type="text" 
                    value="{{ old('contact_title', $settings->contact_title) }}"
                />

                <x-ui.textarea 
                    label="Content" 
                    name="contact_content" 
                    rows="2"
                    value="{{ old('contact_content', $settings->contact_content) }}"
                />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input 
                        label="Link Text" 
                        name="contact_link_text" 
                        type="text" 
                        value="{{ old('contact_link_text', $settings->contact_link_text) }}"
                    />
                    <x-input 
                        label="Link URL" 
                        name="contact_link_url" 
                        type="text" 
                        value="{{ old('contact_link_url', $settings->contact_link_url) }}"
                    />
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <x-button variant="primary" type="submit" icon="save" icon-position="left">Save Settings</x-button>
            </div>
        </form>
    </div>
</x-layouts.admin>
