<x-layouts.admin title="Cookie Settings">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Cookie Settings</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage GDPR cookie banner and preferences</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.content.cookie-settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Banner Settings -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <i class="fas fa-cookie-bite mr-2 text-zinc-500"></i>Banner Settings
                </h2>
                
                <x-ui.checkbox 
                    label="Enable Cookie Banner" 
                    name="cookie_banner_enabled" 
                    value="1"
                    :checked="old('cookie_banner_enabled', get_setting('cookie_banner_enabled', '1') == '1')"
                />
            </div>

            <!-- Intro Text Settings -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <i class="fas fa-info-circle mr-2 text-zinc-500"></i>Intro Text
                </h2>
                
                <x-input 
                    label="Intro Title" 
                    name="cookie_intro_title" 
                    type="text" 
                    placeholder="e.g. We use cookies"
                    value="{{ old('cookie_intro_title', get_setting('cookie_intro_title', 'We use cookies')) }}"
                />

                <x-ui.textarea 
                    label="Intro Summary" 
                    name="cookie_intro_summary" 
                    placeholder="Brief explanation about cookies"
                    rows="4"
                    value="{{ old('cookie_intro_summary', get_setting('cookie_intro_summary', 'In addition to functional cookies we also place analytics and marketing cookies to understand usage, show relevant content and offer support. Only essential cookies are enabled by default.')) }}"
                />
            </div>

            <!-- Preferences Text Settings -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <i class="fas fa-cog mr-2 text-zinc-500"></i>Preferences Text
                </h2>
                
                <x-input 
                    label="Preferences Title" 
                    name="cookie_preferences_title" 
                    type="text" 
                    placeholder="e.g. Manage cookie preferences"
                    value="{{ old('cookie_preferences_title', get_setting('cookie_preferences_title', 'Manage cookie preferences')) }}"
                />

                <x-ui.textarea 
                    label="Preferences Summary" 
                    name="cookie_preferences_summary" 
                    placeholder="Explanation about managing preferences"
                    rows="4"
                    value="{{ old('cookie_preferences_summary', get_setting('cookie_preferences_summary', 'Configure your cookie preferences below. Need more information? Read our policy.')) }}"
                />
            </div>

            <!-- Settings URL -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <i class="fas fa-link mr-2 text-zinc-500"></i>Cookie Policy Link
                </h2>
                
                <x-input 
                    label="Settings Link Label" 
                    name="cookie_settings_label" 
                    type="text" 
                    placeholder="e.g. Cookie policy"
                    value="{{ old('cookie_settings_label', get_setting('cookie_settings_label', 'Cookie policy')) }}"
                />

                <div class="space-y-3">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Link Type</label>
                    <div class="flex gap-4">
                        <label class="flex items-center">
                            <input 
                                type="radio" 
                                name="cookie_settings_page_type" 
                                value="custom" 
                                class="mr-2"
                                {{ old('cookie_settings_page_type', get_setting('cookie_settings_page_type', 'custom')) == 'custom' ? 'checked' : '' }}
                                onchange="document.getElementById('custom-url-group').style.display = 'block'; document.getElementById('static-page-group').style.display = 'none';"
                            >
                            <span class="text-sm text-zinc-700 dark:text-zinc-300">Custom URL</span>
                        </label>
                        <label class="flex items-center">
                            <input 
                                type="radio" 
                                name="cookie_settings_page_type" 
                                value="static" 
                                class="mr-2"
                                {{ old('cookie_settings_page_type', get_setting('cookie_settings_page_type', 'custom')) == 'static' ? 'checked' : '' }}
                                onchange="document.getElementById('custom-url-group').style.display = 'none'; document.getElementById('static-page-group').style.display = 'block';"
                            >
                            <span class="text-sm text-zinc-700 dark:text-zinc-300">Static Page</span>
                        </label>
                    </div>
                </div>

                <div id="custom-url-group" style="display: {{ old('cookie_settings_page_type', get_setting('cookie_settings_page_type', 'custom')) == 'custom' ? 'block' : 'none' }};">
                    <x-input 
                        label="Custom URL" 
                        name="cookie_settings_url" 
                        type="text" 
                        placeholder="https://example.com/cookie-policy"
                        value="{{ old('cookie_settings_url', get_setting('cookie_settings_url', '')) }}"
                    />
                </div>

                <div id="static-page-group" style="display: {{ old('cookie_settings_page_type', get_setting('cookie_settings_page_type', 'custom')) == 'static' ? 'block' : 'none' }};">
                    <x-ui.select-menu 
                        label="Select Static Page" 
                        name="cookie_settings_page_id"
                        :options="$staticPages->map(fn($page) => ['value' => (string)$page->id, 'label' => $page->title])->toArray()"
                        placeholder="Select a page..."
                        :value="old('cookie_settings_page_id', get_setting('cookie_settings_page_id', ''))"
                    />
                </div>
            </div>

            <!-- Cookie Categories -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-6">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <i class="fas fa-tags mr-2 text-zinc-500"></i>Cookie Categories
                </h2>

                <!-- Functional Cookies -->
                <div class="space-y-3 border-b border-zinc-200 dark:border-zinc-700 pb-4">
                    <h3 class="text-base font-medium text-zinc-900 dark:text-white">Functional Cookies</h3>
                    <x-input 
                        label="Label" 
                        name="cookie_category_functional_label" 
                        type="text" 
                        placeholder="e.g. Functional cookies"
                        value="{{ old('cookie_category_functional_label', get_setting('cookie_category_functional_label', 'Functional cookies')) }}"
                    />
                    <x-ui.textarea 
                        label="Description" 
                        name="cookie_category_functional_description" 
                        placeholder="Description for functional cookies"
                        rows="2"
                        value="{{ old('cookie_category_functional_description', get_setting('cookie_category_functional_description', 'Required for core functionality of the website.')) }}"
                    />
                </div>

                <!-- Analytics Cookies -->
                <div class="space-y-3 border-b border-zinc-200 dark:border-zinc-700 pb-4">
                    <h3 class="text-base font-medium text-zinc-900 dark:text-white">Analytics Cookies</h3>
                    <x-input 
                        label="Label" 
                        name="cookie_category_analytics_label" 
                        type="text" 
                        placeholder="e.g. Analytics cookies"
                        value="{{ old('cookie_category_analytics_label', get_setting('cookie_category_analytics_label', 'Analytics cookies')) }}"
                    />
                    <x-ui.textarea 
                        label="Description" 
                        name="cookie_category_analytics_description" 
                        placeholder="Description for analytics cookies"
                        rows="2"
                        value="{{ old('cookie_category_analytics_description', get_setting('cookie_category_analytics_description', 'Help us measure usage and improve the experience.')) }}"
                    />
                </div>

                <!-- Marketing Cookies -->
                <div class="space-y-3">
                    <h3 class="text-base font-medium text-zinc-900 dark:text-white">Marketing Cookies</h3>
                    <x-input 
                        label="Label" 
                        name="cookie_category_marketing_label" 
                        type="text" 
                        placeholder="e.g. Marketing cookies"
                        value="{{ old('cookie_category_marketing_label', get_setting('cookie_category_marketing_label', 'Marketing cookies')) }}"
                    />
                    <x-ui.textarea 
                        label="Description" 
                        name="cookie_category_marketing_description" 
                        placeholder="Description for marketing cookies"
                        rows="2"
                        value="{{ old('cookie_category_marketing_description', get_setting('cookie_category_marketing_description', 'Enable personalised content and external integrations.')) }}"
                    />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <x-button type="submit" variant="primary" icon="save" icon-position="left">
                    Save Settings
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.admin>
