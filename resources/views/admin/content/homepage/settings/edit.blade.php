<x-layouts.admin title="Homepage Settings">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Homepage Settings</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage your homepage content sections</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.content.homepage.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Hero Section -->
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-3">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                            <i class="fas fa-star mr-2 text-zinc-500"></i>Hero Section
                        </h2>
                        <x-ui.checkbox 
                            label="Active" 
                            name="hero_is_active" 
                            value="1"
                            :checked="old('hero_is_active', $settings->hero_is_active ?? true)"
                        />
                    </div>
                    
                    <x-input 
                        label="Badge Text" 
                        name="hero_badge" 
                        type="text" 
                        placeholder="e.g. Open source Woo-voorziening"
                        value="{{ old('hero_badge', $settings->hero_badge) }}"
                    />

                    <x-input 
                        label="Badge Action Text" 
                        name="hero_badge_text" 
                        type="text" 
                        placeholder="e.g. Volledig operationeel"
                        value="{{ old('hero_badge_text', $settings->hero_badge_text) }}"
                    />

                    <x-input 
                        label="Badge URL" 
                        name="hero_badge_url" 
                        type="text" 
                        placeholder="Optional link for badge"
                        value="{{ old('hero_badge_url', $settings->hero_badge_url) }}"
                    />

                    <x-input 
                        label="Title" 
                        name="hero_title" 
                        type="text" 
                        placeholder="Main hero title"
                        value="{{ old('hero_title', $settings->hero_title) }}"
                        required
                    />

                    <x-ui.textarea 
                        label="Description" 
                        name="hero_description" 
                        placeholder="Hero description text"
                        rows="3"
                        value="{{ old('hero_description', $settings->hero_description) }}"
                    />
                </div>

                <!-- Newsletter Section -->
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-3">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                            <i class="fas fa-envelope mr-2 text-zinc-500"></i>Newsletter Section
                        </h2>
                        <x-ui.checkbox 
                            label="Active" 
                            name="newsletter_is_active" 
                            value="1"
                            :checked="old('newsletter_is_active', $settings->newsletter_is_active ?? true)"
                        />
                    </div>
                    
                    <x-input 
                        label="Eyebrow Text" 
                        name="newsletter_eyebrow" 
                        type="text" 
                        placeholder="e.g. Nieuwsbrief"
                        value="{{ old('newsletter_eyebrow', $settings->newsletter_eyebrow) }}"
                    />

                    <x-input 
                        label="Title" 
                        name="newsletter_title" 
                        type="text" 
                        placeholder="e.g. Blijf op de hoogte"
                        value="{{ old('newsletter_title', $settings->newsletter_title) }}"
                    />

                    <x-ui.textarea 
                        label="Description" 
                        name="newsletter_description" 
                        placeholder="Newsletter section description"
                        rows="2"
                        value="{{ old('newsletter_description', $settings->newsletter_description) }}"
                    />

                    <x-input 
                        label="Button Text" 
                        name="newsletter_button_text" 
                        type="text" 
                        placeholder="e.g. Inschrijven"
                        value="{{ old('newsletter_button_text', $settings->newsletter_button_text) }}"
                    />

                    <div class="grid grid-cols-2 gap-4">
                        <x-input 
                            label="Feature 1 Title" 
                            name="newsletter_feature_1_title" 
                            type="text" 
                            placeholder="e.g. Regelmatige updates"
                            value="{{ old('newsletter_feature_1_title', $settings->newsletter_feature_1_title) }}"
                        />
                        <x-input 
                            label="Feature 2 Title" 
                            name="newsletter_feature_2_title" 
                            type="text" 
                            placeholder="e.g. Geen spam"
                            value="{{ old('newsletter_feature_2_title', $settings->newsletter_feature_2_title) }}"
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <x-ui.textarea 
                            label="Feature 1 Description" 
                            name="newsletter_feature_1_description" 
                            rows="2"
                            value="{{ old('newsletter_feature_1_description', $settings->newsletter_feature_1_description) }}"
                        />
                        <x-ui.textarea 
                            label="Feature 2 Description" 
                            name="newsletter_feature_2_description" 
                            rows="2"
                            value="{{ old('newsletter_feature_2_description', $settings->newsletter_feature_2_description) }}"
                        />
                    </div>
                </div>

                <!-- Bento Grid Section -->
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-3">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                            <i class="fas fa-th-large mr-2 text-zinc-500"></i>Bento Grid Section
                        </h2>
                        <x-ui.checkbox 
                            label="Active" 
                            name="bento_is_active" 
                            value="1"
                            :checked="old('bento_is_active', $settings->bento_is_active ?? true)"
                        />
                    </div>
                    
                    <x-input 
                        label="Eyebrow Text" 
                        name="bento_eyebrow" 
                        type="text" 
                        placeholder="e.g. Snel aan de slag"
                        value="{{ old('bento_eyebrow', $settings->bento_eyebrow) }}"
                    />

                    <x-input 
                        label="Title" 
                        name="bento_title" 
                        type="text" 
                        placeholder="e.g. Alles wat je nodig hebt"
                        value="{{ old('bento_title', $settings->bento_title) }}"
                    />

                    <x-ui.textarea 
                        label="Description" 
                        name="bento_description" 
                        placeholder="Bento section description"
                        rows="2"
                        value="{{ old('bento_description', $settings->bento_description) }}"
                    />

                    <div class="mt-4 p-3 bg-zinc-50 dark:bg-zinc-800 rounded-md">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">
                            <i class="fas fa-info-circle mr-1"></i>
                            Manage individual bento items from 
                            <a href="{{ route('admin.content.homepage.bento-item.index') }}" class="text-indigo-600 hover:underline">Bento Items</a>
                        </p>
                    </div>
                </div>

                <!-- Kennisbank Section -->
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-3">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                            <i class="fas fa-book mr-2 text-zinc-500"></i>Kennisbank Section
                        </h2>
                        <x-ui.checkbox 
                            label="Active" 
                            name="kennisbank_is_active" 
                            value="1"
                            :checked="old('kennisbank_is_active', $settings->kennisbank_is_active ?? true)"
                        />
                    </div>
                    
                    <x-input 
                        label="Eyebrow Text" 
                        name="kennisbank_eyebrow" 
                        type="text" 
                        placeholder="e.g. Leren & ontdekken"
                        value="{{ old('kennisbank_eyebrow', $settings->kennisbank_eyebrow) }}"
                    />

                    <x-input 
                        label="Title" 
                        name="kennisbank_title" 
                        type="text" 
                        placeholder="e.g. Kennisbank"
                        value="{{ old('kennisbank_title', $settings->kennisbank_title) }}"
                    />

                    <x-ui.textarea 
                        label="Description" 
                        name="kennisbank_description" 
                        placeholder="Kennisbank section description"
                        rows="2"
                        value="{{ old('kennisbank_description', $settings->kennisbank_description) }}"
                    />
                </div>

                <!-- Testimonials Section -->
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4 lg:col-span-2">
                    <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-3">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                            <i class="fas fa-quote-left mr-2 text-zinc-500"></i>Testimonials Section
                        </h2>
                        <x-ui.checkbox 
                            label="Active" 
                            name="testimonials_is_active" 
                            value="1"
                            :checked="old('testimonials_is_active', $settings->testimonials_is_active ?? true)"
                        />
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <x-input 
                            label="Eyebrow Text" 
                            name="testimonials_eyebrow" 
                            type="text" 
                            placeholder="e.g. Testimonials"
                            value="{{ old('testimonials_eyebrow', $settings->testimonials_eyebrow) }}"
                        />

                        <x-input 
                            label="Title" 
                            name="testimonials_title" 
                            type="text" 
                            placeholder="e.g. Wat gebruikers vinden"
                            value="{{ old('testimonials_title', $settings->testimonials_title) }}"
                        />

                        <x-ui.textarea 
                            label="Description" 
                            name="testimonials_description" 
                            placeholder="Testimonials section description"
                            rows="1"
                            value="{{ old('testimonials_description', $settings->testimonials_description) }}"
                        />
                    </div>

                    <div class="mt-4 p-3 bg-zinc-50 dark:bg-zinc-800 rounded-md">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">
                            <i class="fas fa-info-circle mr-1"></i>
                            Manage individual testimonials from 
                            <a href="{{ route('admin.content.homepage.testimonial.index') }}" class="text-indigo-600 hover:underline">Testimonials</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <x-button variant="primary" type="submit" icon="save" icon-position="left">Save Settings</x-button>
            </div>
        </form>
    </div>
</x-layouts.admin>
