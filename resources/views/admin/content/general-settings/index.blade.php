<x-layouts.admin title="General Settings">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">General Settings</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage site-wide settings and configurations</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.content.general-settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Tabs -->
            <div
                x-data="{
                    activeTab: 'site',
                }"
                x-cloak
                class="flex flex-col"
            >
                <!-- Nav Tabs -->
                <div
                    x-on:keydown.right.prevent.stop="$focus.wrap().next()"
                    x-on:keydown.left.prevent.stop="$focus.wrap().previous()"
                    x-on:keydown.home.prevent.stop="$focus.first()"
                    x-on:keydown.end.prevent.stop="$focus.last()"
                    x-cloak
                    class="flex items-center text-sm dark:border-zinc-700"
                >
                    <button
                        x-on:click="activeTab = 'site'"
                        x-on:focus="activeTab = 'site'"
                        type="button"
                        id="site-tab"
                        role="tab"
                        aria-controls="site-tab-pane"
                        x-bind:aria-selected="activeTab === 'site' ? 'true' : 'false'"
                        x-bind:tabindex="activeTab === 'site' ? '0' : '-1'"
                        x-bind:class="{
                            'text-zinc-950 dark:text-zinc-50 border-zinc-200/75 dark:border-zinc-700/75 bg-white dark:bg-zinc-900': activeTab === 'site',
                            'text-zinc-500 border-transparent hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-zinc-50': activeTab !== 'site',
                        }"
                        class="z-10 -mb-px flex items-center gap-2 rounded-t-lg border-x border-t px-5 py-3 font-medium outline-none focus:outline-none"
                    >
                        <i class="fas fa-globe"></i>Site Information
                    </button>
                    <button
                        x-on:click="activeTab = 'smtp'"
                        x-on:focus="activeTab = 'smtp'"
                        type="button"
                        id="smtp-tab"
                        role="tab"
                        aria-controls="smtp-tab-pane"
                        x-bind:aria-selected="activeTab === 'smtp' ? 'true' : 'false'"
                        x-bind:tabindex="activeTab === 'smtp' ? '0' : '-1'"
                        x-bind:class="{
                            'text-zinc-950 dark:text-zinc-50 border-zinc-200/75 dark:border-zinc-700/75 bg-white dark:bg-zinc-900': activeTab === 'smtp',
                            'text-zinc-500 border-transparent hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-zinc-50': activeTab !== 'smtp',
                        }"
                        class="z-10 -mb-px flex items-center gap-2 rounded-t-lg border-x border-t px-5 py-3 font-medium outline-none focus:outline-none"
                    >
                        <i class="fas fa-envelope"></i>SMTP Settings
                    </button>
                    <button
                        x-on:click="activeTab = 'social'"
                        x-on:focus="activeTab = 'social'"
                        type="button"
                        id="social-tab"
                        role="tab"
                        aria-controls="social-tab-pane"
                        x-bind:aria-selected="activeTab === 'social' ? 'true' : 'false'"
                        x-bind:tabindex="activeTab === 'social' ? '0' : '-1'"
                        x-bind:class="{
                            'text-zinc-950 dark:text-zinc-50 border-zinc-200/75 dark:border-zinc-700/75 bg-white dark:bg-zinc-900': activeTab === 'social',
                            'text-zinc-500 border-transparent hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-zinc-50': activeTab !== 'social',
                        }"
                        class="z-10 -mb-px flex items-center gap-2 rounded-t-lg border-x border-t px-5 py-3 font-medium outline-none focus:outline-none"
                    >
                        <i class="fas fa-share-alt"></i>Social Media
                    </button>
                    <button
                        x-on:click="activeTab = 'other'"
                        x-on:focus="activeTab = 'other'"
                        type="button"
                        id="other-tab"
                        role="tab"
                        aria-controls="other-tab-pane"
                        x-bind:aria-selected="activeTab === 'other' ? 'true' : 'false'"
                        x-bind:tabindex="activeTab === 'other' ? '0' : '-1'"
                        x-bind:class="{
                            'text-zinc-950 dark:text-zinc-50 border-zinc-200/75 dark:border-zinc-700/75 bg-white dark:bg-zinc-900': activeTab === 'other',
                            'text-zinc-500 border-transparent hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-zinc-50': activeTab !== 'other',
                        }"
                        class="z-10 -mb-px flex items-center gap-2 rounded-t-lg border-x border-t px-5 py-3 font-medium outline-none focus:outline-none"
                    >
                        <i class="fas fa-cog"></i>Other Settings
                    </button>
                </div>
                <!-- END Nav Tabs -->

                <!-- Tab Content -->
                <div
                    class="rounded-b-lg rounded-tr-lg border border-zinc-200/75 bg-white p-5 dark:border-zinc-700/75 dark:bg-zinc-900 rtl:rounded-tl-lg rtl:rounded-tr-none"
                >
                    <!-- Site Information Tab -->
                    <div
                        x-show="activeTab === 'site'"
                        id="site-tab-pane"
                        role="tabpanel"
                        aria-labelledby="site-tab"
                        tabindex="0"
                        class="space-y-6"
                    >
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                                <i class="fas fa-info-circle mr-2 text-zinc-500"></i>Basic Information
                            </h2>
                            
                            <x-input 
                                label="Site Title" 
                                name="site_title" 
                                type="text" 
                                placeholder="e.g. My Website"
                                value="{{ old('site_title', get_setting('site_title', '')) }}"
                            />

                            <x-ui.textarea 
                                label="Site Description" 
                                name="site_description" 
                                placeholder="Brief description of your website"
                                rows="3"
                                value="{{ old('site_description', get_setting('site_description', '')) }}"
                            />

                            <x-input 
                                label="Site URL" 
                                name="site_url" 
                                type="url" 
                                placeholder="https://example.com"
                                value="{{ old('site_url', get_setting('site_url', '')) }}"
                            />
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                                <i class="fas fa-search mr-2 text-zinc-500"></i>SEO Settings
                            </h2>
                            
                            <x-input 
                                label="Meta Keywords" 
                                name="meta_keywords" 
                                type="text" 
                                placeholder="keyword1, keyword2, keyword3"
                                value="{{ old('meta_keywords', get_setting('meta_keywords', '')) }}"
                            />

                            <x-ui.textarea 
                                label="Meta Description" 
                                name="meta_description" 
                                placeholder="SEO meta description (recommended: 150-160 characters)"
                                rows="3"
                                value="{{ old('meta_description', get_setting('meta_description', '')) }}"
                            />
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                                <i class="fas fa-image mr-2 text-zinc-500"></i>Branding
                            </h2>
                            
                            <x-ui.file-upload 
                                name="site_logo"
                                label="Site Logo"
                                :value="get_setting('site_logo', '')"
                                accept="image/png, image/jpeg, image/jpg, image/gif, image/svg+xml, image/webp"
                                helper-text="PNG, JPG, GIF, SVG, WebP - Max 2MB"
                            />

                            <x-ui.file-upload 
                                name="site_favicon"
                                label="Site Favicon"
                                :value="get_setting('site_favicon', '')"
                                accept="image/x-icon, image/png, image/jpeg, image/gif, image/svg+xml"
                                helper-text="ICO, PNG, JPG, GIF, SVG - Max 1MB"
                            />
                        </div>
                    </div>

                    <!-- SMTP Tab -->
                    <div
                        x-cloak
                        x-show="activeTab === 'smtp'"
                        id="smtp-tab-pane"
                        role="tabpanel"
                        aria-labelledby="smtp-tab"
                        tabindex="0"
                        class="space-y-6"
                    >
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                                <i class="fas fa-server mr-2 text-zinc-500"></i>SMTP Server Configuration
                            </h2>
                            
                            <x-input 
                                label="SMTP Host" 
                                name="smtp_host" 
                                type="text" 
                                placeholder="e.g. smtp.gmail.com"
                                value="{{ old('smtp_host', get_setting('smtp_host', '')) }}"
                            />

                            <x-input 
                                label="SMTP Port" 
                                name="smtp_port" 
                                type="number" 
                                placeholder="e.g. 587"
                                value="{{ old('smtp_port', get_setting('smtp_port', '')) }}"
                            />

                            <x-ui.select 
                                label="SMTP Encryption" 
                                name="smtp_encryption"
                                :options="[
                                    '' => 'None',
                                    'tls' => 'TLS',
                                    'ssl' => 'SSL'
                                ]"
                                value="{{ old('smtp_encryption', get_setting('smtp_encryption', '')) }}"
                            />
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                                <i class="fas fa-user mr-2 text-zinc-500"></i>SMTP Authentication
                            </h2>
                            
                            <x-input 
                                label="SMTP Username" 
                                name="smtp_username" 
                                type="text" 
                                placeholder="e.g. your-email@gmail.com"
                                value="{{ old('smtp_username', get_setting('smtp_username', '')) }}"
                            />

                            <x-input 
                                label="SMTP Password" 
                                name="smtp_password" 
                                type="password" 
                                placeholder="Leave empty to keep current password"
                                value=""
                            />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Leave empty if you don't want to change the password</p>
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                                <i class="fas fa-paper-plane mr-2 text-zinc-500"></i>Email From Settings
                            </h2>
                            
                            <x-input 
                                label="From Email Address" 
                                name="smtp_from_address" 
                                type="email" 
                                placeholder="e.g. noreply@example.com"
                                value="{{ old('smtp_from_address', get_setting('smtp_from_address', '')) }}"
                            />

                            <x-input 
                                label="From Name" 
                                name="smtp_from_name" 
                                type="text" 
                                placeholder="e.g. My Website"
                                value="{{ old('smtp_from_name', get_setting('smtp_from_name', '')) }}"
                            />
                        </div>
                    </div>

                    <!-- Social Media Tab -->
                    <div
                        x-cloak
                        x-show="activeTab === 'social'"
                        id="social-tab-pane"
                        role="tabpanel"
                        aria-labelledby="social-tab"
                        tabindex="0"
                        class="space-y-6"
                    >
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                                <i class="fas fa-share-alt mr-2 text-zinc-500"></i>Social Media Links
                            </h2>
                            
                            <x-input 
                                label="Facebook URL" 
                                name="facebook_url" 
                                type="url" 
                                placeholder="https://facebook.com/yourpage"
                                icon="facebook"
                                value="{{ old('facebook_url', get_setting('facebook_url', '')) }}"
                            />

                            <x-input 
                                label="Twitter URL" 
                                name="twitter_url" 
                                type="url" 
                                placeholder="https://twitter.com/yourhandle"
                                icon="twitter"
                                value="{{ old('twitter_url', get_setting('twitter_url', '')) }}"
                            />

                            <x-input 
                                label="LinkedIn URL" 
                                name="linkedin_url" 
                                type="url" 
                                placeholder="https://linkedin.com/company/yourcompany"
                                icon="linkedin"
                                value="{{ old('linkedin_url', get_setting('linkedin_url', '')) }}"
                            />

                            <x-input 
                                label="Instagram URL" 
                                name="instagram_url" 
                                type="url" 
                                placeholder="https://instagram.com/yourhandle"
                                icon="instagram"
                                value="{{ old('instagram_url', get_setting('instagram_url', '')) }}"
                            />

                            <x-input 
                                label="YouTube URL" 
                                name="youtube_url" 
                                type="url" 
                                placeholder="https://youtube.com/@yourchannel"
                                icon="youtube"
                                value="{{ old('youtube_url', get_setting('youtube_url', '')) }}"
                            />

                            <x-input 
                                label="GitHub URL" 
                                name="github_url" 
                                type="url" 
                                placeholder="https://github.com/yourusername"
                                icon="github"
                                value="{{ old('github_url', get_setting('github_url', '')) }}"
                            />
                        </div>
                    </div>

                    <!-- Other Settings Tab -->
                    <div
                        x-cloak
                        x-show="activeTab === 'other'"
                        id="other-tab-pane"
                        role="tabpanel"
                        aria-labelledby="other-tab"
                        tabindex="0"
                        class="space-y-6"
                    >
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                                <i class="fas fa-cog mr-2 text-zinc-500"></i>Additional Settings
                            </h2>
                            
                            <x-ui.checkbox 
                                label="Maintenance Mode" 
                                name="maintenance_mode" 
                                value="1"
                                :checked="old('maintenance_mode', get_setting('maintenance_mode', '0') == '1')"
                            />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-4">Enable maintenance mode to temporarily disable public access to the site</p>

                            <x-ui.textarea 
                                label="Maintenance Message" 
                                name="maintenance_message" 
                                placeholder="e.g. We're currently performing scheduled maintenance to improve your experience. We'll be back shortly."
                                rows="4"
                                value="{{ old('maintenance_message', get_setting('maintenance_message', 'We\'re currently performing scheduled maintenance to improve your experience. We\'ll be back shortly.')) }}"
                            />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">This message will be displayed to users when maintenance mode is active</p>

                            <x-input 
                                label="Timezone" 
                                name="timezone" 
                                type="text" 
                                placeholder="e.g. Europe/Amsterdam"
                                value="{{ old('timezone', get_setting('timezone', config('app.timezone', 'UTC'))) }}"
                            />

                            <x-input 
                                label="Locale" 
                                name="locale" 
                                type="text" 
                                placeholder="e.g. en, nl, tr"
                                value="{{ old('locale', get_setting('locale', config('app.locale', 'en'))) }}"
                            />
                        </div>
                    </div>
                </div>
                <!-- END Tab Content -->
            </div>
            <!-- END Tabs -->

            <!-- Submit Button -->
            <div class="flex justify-end">
                <x-button type="submit" variant="primary" icon="save" icon-position="left">
                    Save Settings
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.admin>
