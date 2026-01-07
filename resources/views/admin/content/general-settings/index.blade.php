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
                    activeTab: 'general',
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
                        x-on:click="activeTab = 'general'"
                        x-on:focus="activeTab = 'general'"
                        type="button"
                        id="general-tab"
                        role="tab"
                        aria-controls="general-tab-pane"
                        x-bind:aria-selected="activeTab === 'general' ? 'true' : 'false'"
                        x-bind:tabindex="activeTab === 'general' ? '0' : '-1'"
                        x-bind:class="{
                            'text-zinc-950 dark:text-zinc-50 border-zinc-200/75 dark:border-zinc-700/75 bg-white dark:bg-zinc-900': activeTab === 'general',
                            'text-zinc-500 border-transparent hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-zinc-50': activeTab !== 'general',
                        }"
                        class="z-10 -mb-px flex items-center gap-2 rounded-t-lg border-x border-t px-5 py-3 font-medium outline-none focus:outline-none"
                    >
                        <i class="fas fa-globe"></i>General
                    </button>
                    <button
                        x-on:click="activeTab = 'seo'"
                        x-on:focus="activeTab = 'seo'"
                        type="button"
                        id="seo-tab"
                        role="tab"
                        aria-controls="seo-tab-pane"
                        x-bind:aria-selected="activeTab === 'seo' ? 'true' : 'false'"
                        x-bind:tabindex="activeTab === 'seo' ? '0' : '-1'"
                        x-bind:class="{
                            'text-zinc-950 dark:text-zinc-50 border-zinc-200/75 dark:border-zinc-700/75 bg-white dark:bg-zinc-900': activeTab === 'seo',
                            'text-zinc-500 border-transparent hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-zinc-50': activeTab !== 'seo',
                        }"
                        class="z-10 -mb-px flex items-center gap-2 rounded-t-lg border-x border-t px-5 py-3 font-medium outline-none focus:outline-none"
                    >
                        <i class="fas fa-search"></i>SEO & Social
                    </button>
                    <button
                        x-on:click="activeTab = 'email'"
                        x-on:focus="activeTab = 'email'"
                        type="button"
                        id="email-tab"
                        role="tab"
                        aria-controls="email-tab-pane"
                        x-bind:aria-selected="activeTab === 'email' ? 'true' : 'false'"
                        x-bind:tabindex="activeTab === 'email' ? '0' : '-1'"
                        x-bind:class="{
                            'text-zinc-950 dark:text-zinc-50 border-zinc-200/75 dark:border-zinc-700/75 bg-white dark:bg-zinc-900': activeTab === 'email',
                            'text-zinc-500 border-transparent hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-zinc-50': activeTab !== 'email',
                        }"
                        class="z-10 -mb-px flex items-center gap-2 rounded-t-lg border-x border-t px-5 py-3 font-medium outline-none focus:outline-none"
                    >
                        <i class="fas fa-envelope"></i>Email
                    </button>
                    <button
                        x-on:click="activeTab = 'system'"
                        x-on:focus="activeTab = 'system'"
                        type="button"
                        id="system-tab"
                        role="tab"
                        aria-controls="system-tab-pane"
                        x-bind:aria-selected="activeTab === 'system' ? 'true' : 'false'"
                        x-bind:tabindex="activeTab === 'system' ? '0' : '-1'"
                        x-bind:class="{
                            'text-zinc-950 dark:text-zinc-50 border-zinc-200/75 dark:border-zinc-700/75 bg-white dark:bg-zinc-900': activeTab === 'system',
                            'text-zinc-500 border-transparent hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-zinc-50': activeTab !== 'system',
                        }"
                        class="z-10 -mb-px flex items-center gap-2 rounded-t-lg border-x border-t px-5 py-3 font-medium outline-none focus:outline-none"
                    >
                        <i class="fas fa-cog"></i>System
                    </button>
                </div>
                <!-- END Nav Tabs -->

                <!-- Tab Content -->
                <div
                    class="rounded-b-lg rounded-tr-lg border border-zinc-200/75 bg-white p-5 dark:border-zinc-700/75 dark:bg-zinc-900 rtl:rounded-tl-lg rtl:rounded-tr-none"
                >
                    <!-- General Tab -->
                    <div
                        x-show="activeTab === 'general'"
                        id="general-tab-pane"
                        role="tabpanel"
                        aria-labelledby="general-tab"
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
                                <i class="fas fa-address-card mr-2 text-zinc-500"></i>Contact Information
                            </h2>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">These contact details will be displayed on the contact page and in the footer.</p>
                            
                            <x-input 
                                label="Contact Email" 
                                name="contact_email" 
                                type="email" 
                                placeholder="e.g. contact@example.com"
                                value="{{ old('contact_email', get_setting('contact_email', '')) }}"
                            />

                            <x-input 
                                label="Contact Phone" 
                                name="contact_phone" 
                                type="tel" 
                                placeholder="e.g. +31 (0) 123 456 789"
                                value="{{ old('contact_phone', get_setting('contact_phone', '')) }}"
                            />

                            <x-ui.textarea 
                                label="Contact Address" 
                                name="contact_address" 
                                placeholder="e.g. Open Overheid Platform&#10;Den Haag, Nederland"
                                rows="3"
                                value="{{ old('contact_address', get_setting('contact_address', '')) }}"
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

                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                                <i class="fas fa-window-minimize mr-2 text-zinc-500"></i>Footer Settings
                            </h2>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Customize the footer content displayed on all pages.</p>
                            
                            <x-input 
                                label="Copyright Text" 
                                name="footer_copyright" 
                                type="text" 
                                placeholder="e.g. © {{ date('Y') }} Your Company Name. All rights reserved."
                                value="{{ old('footer_copyright', get_setting('footer_copyright', '© ' . date('Y') . ' Open Overheid. Alle rechten voorbehouden.')) }}"
                            />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Use {{ date('Y') }} for current year. Leave empty to use default.</p>

                            <x-ui.textarea 
                                label="Footer Description / Mission" 
                                name="footer_description" 
                                placeholder="Brief description or mission statement for the footer"
                                rows="4"
                                value="{{ old('footer_description', get_setting('footer_description', 'Open.overheid.nl bundelt actief openbaar gemaakte overheidsdocumenten op één centrale plek, zodat burgers en professionals deze eenvoudig kunnen vinden en raadplegen.')) }}"
                            />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">This will be displayed in the footer mission section. Leave empty to use default.</p>

                            <x-ui.textarea 
                                label="Footer Secondary Description" 
                                name="footer_secondary_description" 
                                placeholder="Additional footer text (optional)"
                                rows="2"
                                value="{{ old('footer_secondary_description', get_setting('footer_secondary_description', 'Wij werken op basis van de Wet open overheid (Woo) om transparantie en toegankelijkheid te bevorderen.')) }}"
                            />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Optional secondary description text. Leave empty to use default.</p>

                            <x-input 
                                label="Footer Bottom Text" 
                                name="footer_bottom_text" 
                                type="text" 
                                placeholder="e.g. Digitaliseringspartner voor slimme ICT-oplossingen"
                                value="{{ old('footer_bottom_text', get_setting('footer_bottom_text', 'Digitaliseringspartner voor slimme ICT-oplossingen')) }}"
                            />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Text displayed in the bottom right of the footer. Leave empty to hide.</p>
                        </div>
                    </div>

                    <!-- SEO & Social Tab -->
                    <div
                        x-cloak
                        x-show="activeTab === 'seo'"
                        id="seo-tab-pane"
                        role="tabpanel"
                        aria-labelledby="seo-tab"
                        tabindex="0"
                        class="space-y-6"
                    >
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
                                <i class="fas fa-share-nodes mr-2 text-zinc-500"></i>Open Graph (Social Sharing)
                            </h2>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Default Open Graph settings for social media sharing. These will be used when specific pages don't have their own Open Graph settings.</p>
                            
                            <x-input 
                                label="Open Graph Title" 
                                name="og_title" 
                                type="text" 
                                placeholder="e.g. My Website - Open Government"
                                value="{{ old('og_title', get_setting('og_title', '')) }}"
                            />

                            <x-ui.textarea 
                                label="Open Graph Description" 
                                name="og_description" 
                                placeholder="Brief description for social media sharing (recommended: 150-160 characters)"
                                rows="3"
                                value="{{ old('og_description', get_setting('og_description', '')) }}"
                            />

                            <x-ui.file-upload 
                                name="og_image"
                                label="Open Graph Image"
                                :value="get_setting('og_image', '')"
                                accept="image/png, image/jpeg, image/jpg, image/gif, image/webp"
                                helper-text="Recommended: 1200x630px - PNG, JPG, GIF, WebP - Max 2MB"
                            />
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                                <i class="fas fa-chart-line mr-2 text-zinc-500"></i>Analytics & Tracking
                            </h2>
                            
                            <x-input 
                                label="Google Analytics ID" 
                                name="google_analytics_id" 
                                type="text" 
                                placeholder="e.g. G-XXXXXXXXXX or UA-XXXXXXXXX-X"
                                value="{{ old('google_analytics_id', get_setting('google_analytics_id', '')) }}"
                            />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Enter your Google Analytics Measurement ID (GA4: G-XXXXXXXXXX) or Universal Analytics ID (UA-XXXXXXXXX-X). Leave empty to disable tracking.</p>
                        </div>

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

                    <!-- Email Tab -->
                    <div
                        x-cloak
                        x-show="activeTab === 'email'"
                        id="email-tab-pane"
                        role="tabpanel"
                        aria-labelledby="email-tab"
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

                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                                <i class="fas fa-envelope mr-2 text-zinc-500"></i>Contact Form Settings
                            </h2>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Configure email notifications for contact form submissions. Form submissions are already saved in the admin panel under Contact Messages.</p>
                            
                            <x-input 
                                label="Notification Email" 
                                name="contact_notification_email" 
                                type="email" 
                                placeholder="e.g. admin@example.com"
                                value="{{ old('contact_notification_email', get_setting('contact_notification_email', '')) }}"
                            />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Email address to receive notifications when a contact form is submitted. Leave empty to disable notifications.</p>

                            <x-ui.checkbox 
                                label="Enable Auto-Reply" 
                                name="contact_auto_reply_enabled" 
                                value="1"
                                :checked="old('contact_auto_reply_enabled', get_setting('contact_auto_reply_enabled', '0') == '1')"
                            />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Send an automatic confirmation email to users after they submit the contact form</p>
                        </div>
                    </div>

                    <!-- System Tab -->
                    <div
                        x-cloak
                        x-show="activeTab === 'system'"
                        id="system-tab-pane"
                        role="tabpanel"
                        aria-labelledby="system-tab"
                        tabindex="0"
                        class="space-y-6"
                    >
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                                <i class="fas fa-tools mr-2 text-zinc-500"></i>Maintenance Mode
                            </h2>
                            
                            <x-ui.checkbox 
                                label="Enable Maintenance Mode" 
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
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">
                                <i class="fas fa-globe mr-2 text-zinc-500"></i>Localization
                            </h2>
                            
                            <x-input 
                                label="Timezone" 
                                name="timezone" 
                                type="text" 
                                placeholder="e.g. Europe/Amsterdam"
                                value="{{ old('timezone', get_setting('timezone', config('app.timezone', 'UTC'))) }}"
                            />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Default timezone for the application</p>

                            <x-input 
                                label="Locale" 
                                name="locale" 
                                type="text" 
                                placeholder="e.g. en, nl, tr"
                                value="{{ old('locale', get_setting('locale', config('app.locale', 'en'))) }}"
                            />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Default locale/language for the application</p>
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
