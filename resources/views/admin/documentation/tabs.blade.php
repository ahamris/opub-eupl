<x-layouts.admin title="Tabs - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Tabs -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Basic Tabs</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4" style="width: 375px; height: 550px; margin: 0 auto; box-shadow: 0 8px 17px 2px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
                        <x-ui.tabs>
                            <x-slot:tabs>
                                <x-ui.tab-item label="Profile" tab-index="profile" :active="true" :is-button="true" />
                                <x-ui.tab-item label="Settings" tab-index="settings" :is-button="true" />
                                <x-ui.tab-item label="About Us" tab-index="about" :is-button="true" />
                                <x-ui.tab-item label="FAQ" tab-index="faq" :is-button="true" />
                            </x-slot:tabs>
                            
                            <x-ui.tab-item label="Profile" tab-index="profile" :active="true">
                                <div class="space-y-6">
                                    <div class="flex gap-8 p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                        <div class="w-24 h-24 rounded-full bg-black/15 flex-shrink-0"></div>
                                        <div class="flex-1 space-y-4">
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                            <div class="h-5 bg-black/15 rounded w-3/4"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-8 p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                        <div class="flex-1 space-y-4">
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-8 p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                        <div class="flex-1 space-y-4">
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                        </div>
                                    </div>
                                </div>
                            </x-ui.tab-item>
                            <x-ui.tab-item label="Settings" tab-index="settings">
                                <div class="space-y-6">
                                    <div class="flex gap-8 p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                        <div class="flex-1 space-y-4">
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                        </div>
                                        <div class="flex-1 space-y-4">
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-8 p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                        <div class="flex-1 space-y-4">
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between gap-4 p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                        <div class="w-20 h-20 rounded bg-black/15 flex-shrink-0"></div>
                                        <div class="w-20 h-20 rounded bg-black/15 flex-shrink-0"></div>
                                        <div class="w-20 h-20 rounded bg-black/15 flex-shrink-0"></div>
                                    </div>
                                </div>
                            </x-ui.tab-item>
                            <x-ui.tab-item label="About Us" tab-index="about">
                                <div class="space-y-6">
                                    <div class="flex gap-8 p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                        <div class="flex-1 space-y-4">
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-8 p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                        <div class="flex-1 space-y-4">
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                        </div>
                                        <div class="w-20 h-20 rounded bg-black/15 flex-shrink-0"></div>
                                    </div>
                                    <div class="flex gap-8 p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                        <div class="w-20 h-20 rounded bg-black/15 flex-shrink-0"></div>
                                        <div class="flex-1 space-y-4">
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                        </div>
                                    </div>
                                </div>
                            </x-ui.tab-item>
                            <x-ui.tab-item label="FAQ" tab-index="faq">
                                <div class="space-y-6">
                                    <div class="flex gap-8 p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                        <div class="flex-1 space-y-4">
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                        </div>
                                        <div class="flex-1 space-y-4">
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-8 p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                        <div class="flex-1 space-y-4">
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-8 p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                        <div class="flex-1 space-y-4">
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                        </div>
                                        <div class="flex-1 space-y-4">
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                            <div class="h-5 bg-black/15 rounded w-full"></div>
                                        </div>
                                    </div>
                                </div>
                            </x-ui.tab-item>
                        </x-ui.tabs>
                    </div>
                </div>
            </div>

            <!-- With Icons and Badges -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">With Icons and Badges</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4" style="max-width: 600px;">
                        <x-ui.tabs>
                            <x-slot:tabs>
                                <x-ui.tab-item label="Home" icon="home" tab-index="home-icon" :active="true" :is-button="true" />
                                <x-ui.tab-item label="Messages" icon="envelope" badge="3" tab-index="messages-icon" :is-button="true" />
                                <x-ui.tab-item label="Settings" icon="gear" tab-index="settings-icon" :is-button="true" />
                            </x-slot:tabs>
                            <x-ui.tab-item label="Home" tab-index="home-icon" :active="true">
                                <div class="p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Home content with icon</p>
                                </div>
                            </x-ui.tab-item>
                            <x-ui.tab-item label="Messages" tab-index="messages-icon">
                                <div class="p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Messages content with badge</p>
                                </div>
                            </x-ui.tab-item>
                            <x-ui.tab-item label="Settings" tab-index="settings-icon">
                                <div class="p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Settings content</p>
                                </div>
                            </x-ui.tab-item>
                        </x-ui.tabs>
                    </div>
                </div>
            </div>

            <!-- Disabled Tab -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Disabled Tab</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4" style="max-width: 600px;">
                        <x-ui.tabs>
                            <x-slot:tabs>
                                <x-ui.tab-item label="Enabled" tab-index="enabled-tab" :active="true" :is-button="true" />
                                <x-ui.tab-item label="Disabled" tab-index="disabled-tab" :disabled="true" :is-button="true" />
                            </x-slot:tabs>
                            <x-ui.tab-item label="Enabled" tab-index="enabled-tab" :active="true">
                                <div class="p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Enabled tab content</p>
                                </div>
                            </x-ui.tab-item>
                            <x-ui.tab-item label="Disabled" tab-index="disabled-tab" :disabled="true">
                                <div class="p-4 bg-white dark:bg-zinc-800 rounded" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">This tab is disabled</p>
                                </div>
                            </x-ui.tab-item>
                        </x-ui.tabs>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div class="space-y-8">
            <div>
                <h3 class="text-lg font-semibold mb-3">Overview</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    Tabs component with animated decoration background. The active tab has a colored background that smoothly animates between tabs.
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Tab panels use scale and opacity transitions for smooth content switching.
                </p>
            </div>

            <!-- Usage -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Usage</h3>
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded text-sm overflow-x-auto">
                    <pre><code class="language-blade">@verbatim
&lt;x-ui.tabs&gt;
    &lt;x-slot:tabs&gt;
        &lt;x-ui.tab-item label="Profile" tab-index="profile" :active="true" :is-button="true" /&gt;
        &lt;x-ui.tab-item label="Settings" tab-index="settings" :is-button="true" /&gt;
    &lt;/x-slot:tabs&gt;
    
    &lt;x-ui.tab-item label="Profile" tab-index="profile" :active="true"&gt;
        &lt;div class="p-4 bg-white rounded shadow-md"&gt;
            Content for Profile
        &lt;/div&gt;
    &lt;/x-ui.tab-item&gt;
    
    &lt;x-ui.tab-item label="Settings" tab-index="settings"&gt;
        &lt;div class="p-4 bg-white rounded shadow-md"&gt;
            Content for Settings
        &lt;/div&gt;
    &lt;/x-ui.tab-item&gt;
&lt;/x-ui.tabs&gt;
@endverbatim</code></pre>
                </div>
            </div>

            <!-- Props - Tabs -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Tabs Props</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">alpine-model</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string|null</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Alpine.js variable for active tab state</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">wire-model</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string|null</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Livewire property for active tab state</p>
                    </div>
                </div>
            </div>

            <!-- Props - TabItem -->
            <div>
                <h3 class="text-lg font-semibold mb-3">TabItem Props</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">label</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string (required)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Tab label text</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">tab-index</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string (required)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Unique identifier for tab (must match between button and content)</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">active</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool (default: false)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Whether tab is active by default</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">icon</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string|null</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">FontAwesome icon name</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">badge</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string|null</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Badge text to display</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool (default: false)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Disable the tab</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">is-button</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool (default: false)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Set to true for tab buttons in the tabs slot. Set to false (or omit) for tab content panels in the main slot.</p>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Features</h3>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                    <li>Animated decoration background</li>
                    <li>Smooth scale and opacity transitions</li>
                    <li>Icon and badge support</li>
                    <li>Disabled state</li>
                    <li>Alpine.js integration</li>
                    <li>Livewire integration</li>
                    <li>Dark mode support</li>
                    <li>Accessibility (ARIA attributes)</li>
                </ul>
            </div>
        </div>
    </div>

</x-layouts.admin>
