<x-layouts.admin title="Breadcrumbs - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Breadcrumbs -->
            <div>
                @php
                    $code1 = '<x-navigation.breadcrumbs />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Breadcrumbs</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code1 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <x-navigation.breadcrumbs />
                </div>
            </div>

            <!-- Manual Breadcrumbs -->
            <div>
                @php
                    $code2 = '<x-navigation.breadcrumbs :items="[
    [\'label\' => \'Dashboard\', \'url\' => route(\'admin.home\')],
    [\'label\' => \'Users\', \'url\' => route(\'admin.users.index\')],
    [\'label\' => \'Edit User\', \'url\' => null],
]" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Manual Breadcrumbs</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code2 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <x-navigation.breadcrumbs :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.home')],
                        ['label' => 'Users', 'url' => route('admin.users.index')],
                        ['label' => 'Edit User', 'url' => null],
                    ]" />
                </div>
            </div>

            <!-- Custom Separator -->
            <div>
                @php
                    $code3 = '<x-navigation.breadcrumbs separator="chevron-right" />
<x-navigation.breadcrumbs separator="angle-right" />
<x-navigation.breadcrumbs separator="/" />
<x-navigation.breadcrumbs separator="→" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Custom Separator</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code3 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <x-navigation.breadcrumbs separator="chevron-right" :items="[
                            ['label' => 'Dashboard', 'url' => route('admin.home')],
                            ['label' => 'Settings', 'url' => route('admin.settings.menu')],
                            ['label' => 'Theme', 'url' => null],
                        ]" />
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <x-navigation.breadcrumbs separator="angle-right" :items="[
                            ['label' => 'Dashboard', 'url' => route('admin.home')],
                            ['label' => 'Settings', 'url' => route('admin.settings.menu')],
                            ['label' => 'Theme', 'url' => null],
                        ]" />
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <x-navigation.breadcrumbs separator="/" :items="[
                            ['label' => 'Dashboard', 'url' => route('admin.home')],
                            ['label' => 'Settings', 'url' => route('admin.settings.menu')],
                            ['label' => 'Theme', 'url' => null],
                        ]" />
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <x-navigation.breadcrumbs separator="→" :items="[
                            ['label' => 'Dashboard', 'url' => route('admin.home')],
                            ['label' => 'Settings', 'url' => route('admin.settings.menu')],
                            ['label' => 'Theme', 'url' => null],
                        ]" />
                    </div>
                </div>
            </div>

            <!-- Truncation -->
            <div>
                @php
                    $code4 = '<x-navigation.breadcrumbs :maxItems="3" :items="[
    [\'label\' => \'Dashboard\', \'url\' => route(\'admin.home\')],
    [\'label\' => \'Level 1\', \'url\' => \'#\'],
    [\'label\' => \'Level 2\', \'url\' => \'#\'],
    [\'label\' => \'Level 3\', \'url\' => \'#\'],
    [\'label\' => \'Level 4\', \'url\' => \'#\'],
    [\'label\' => \'Current Page\', \'url\' => null],
]" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Truncation</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code4 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <x-navigation.breadcrumbs :maxItems="3" :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.home')],
                        ['label' => 'Level 1', 'url' => '#'],
                        ['label' => 'Level 2', 'url' => '#'],
                        ['label' => 'Level 3', 'url' => '#'],
                        ['label' => 'Level 4', 'url' => '#'],
                        ['label' => 'Current Page', 'url' => null],
                    ]" />
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-3">When breadcrumbs exceed maxItems (default: 5), middle items are hidden in a dropdown. Click "..." to see hidden items.</p>
                </div>
            </div>

            <!-- Long Labels -->
            <div>
                @php
                    $code5 = '<x-navigation.breadcrumbs :items="[
    [\'label\' => \'Dashboard\', \'url\' => route(\'admin.home\')],
    [\'label\' => \'This is a very long breadcrumb label that will be truncated\', \'url\' => \'#\'],
    [\'label\' => \'Current\', \'url\' => null],
]" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Long Labels</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code5 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <x-navigation.breadcrumbs :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.home')],
                        ['label' => 'This is a very long breadcrumb label that will be truncated', 'url' => '#'],
                        ['label' => 'Current', 'url' => null],
                    ]" />
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-3">Long labels are automatically truncated with ellipsis to maintain layout.</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div class="space-y-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Breadcrumbs Component</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 m-0">Navigation breadcrumb component that automatically generates from URL segments or accepts manual items.</p>
                </div>
                <div class="card-body space-y-6">
                    <!-- Basic Usage -->
                    <section>
                        <h4 class="text-lg font-semibold mb-3">Basic Usage</h4>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-navigation.breadcrumbs /&gt;</code></pre>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">When used without props, the component automatically generates breadcrumbs from the current URL segments.</p>
                    </section>

                    <!-- Props -->
                    <section>
                        <h4 class="text-lg font-semibold mb-3">Props</h4>
                        <div class="space-y-3">
                            <div>
                                <h5 class="text-md font-semibold mb-2">items</h5>
                                <p class="text-xs text-gray-600 dark:text-gray-400">array|null - Manual breadcrumb items array. Each item should have:</p>
                                <ul class="text-xs text-gray-600 dark:text-gray-400 mt-2 ml-4 list-disc space-y-1">
                                    <li><code class="bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">label</code> - string (required) - Display text</li>
                                    <li><code class="bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">url</code> - string|null - Link URL (null for current page)</li>
                                </ul>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">Default: null (auto-generate from URL)</p>
                            </div>

                            <div>
                                <h5 class="text-md font-semibold mb-2">separator</h5>
                                <p class="text-xs text-gray-600 dark:text-gray-400">string|null - Separator between breadcrumb items. Can be:</p>
                                <ul class="text-xs text-gray-600 dark:text-gray-400 mt-2 ml-4 list-disc space-y-1">
                                    <li>FontAwesome icon name (e.g., <code class="bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">chevron-right</code>, <code class="bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">angle-right</code>)</li>
                                    <li>Text character (e.g., <code class="bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">›</code>, <code class="bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">/</code>, <code class="bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">→</code>)</li>
                                </ul>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">Default: <code class="bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">›</code></p>
                            </div>

                            <div>
                                <h5 class="text-md font-semibold mb-2">maxItems</h5>
                                <p class="text-xs text-gray-600 dark:text-gray-400">int - Maximum number of breadcrumb items to show before truncation. When exceeded, middle items are hidden in a dropdown.</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">Default: <code class="bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">5</code></p>
                            </div>
                        </div>
                    </section>

                    <!-- Features -->
                    <section>
                        <h4 class="text-lg font-semibold mb-3">Features</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2 list-disc list-inside">
                            <li><strong>Auto-generation</strong> - Automatically creates breadcrumbs from URL segments</li>
                            <li><strong>Manual override</strong> - Accept custom items array for full control</li>
                            <li><strong>Smart truncation</strong> - Hides middle items when exceeding maxItems</li>
                            <li><strong>Dropdown navigation</strong> - Click "..." to see hidden breadcrumb items</li>
                            <li><strong>Custom separators</strong> - Use FontAwesome icons or text characters</li>
                            <li><strong>Responsive</strong> - Adapts to mobile screens with proper truncation</li>
                            <li><strong>Accessibility</strong> - Includes ARIA labels for screen readers</li>
                            <li><strong>Theme integration</strong> - Uses CSS variables for accent colors</li>
                        </ul>
                    </section>

                    <!-- Examples -->
                    <section>
                        <h4 class="text-lg font-semibold mb-3">Examples</h4>
                        <div class="space-y-4">
                            <div>
                                <h5 class="text-md font-semibold mb-2">Auto-generated from URL</h5>
                                <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-navigation.breadcrumbs /&gt;</code></pre>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">For URL: <code class="bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">/admin/users/123/edit</code></p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Generates: Dashboard › Users › 123 › Edit</p>
                            </div>

                            <div>
                                <h5 class="text-md font-semibold mb-2">Manual items</h5>
                                <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-navigation.breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.home')],
    ['label' => 'Products', 'url' => route('admin.products.index')],
    ['label' => 'Edit Product', 'url' => null],
]" /&gt;</code></pre>
                            </div>

                            <div>
                                <h5 class="text-md font-semibold mb-2">Custom separator</h5>
                                <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-navigation.breadcrumbs separator="chevron-right" /&gt;</code></pre>
                            </div>

                            <div>
                                <h5 class="text-md font-semibold mb-2">Truncation control</h5>
                                <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-navigation.breadcrumbs :maxItems="3" /&gt;</code></pre>
                            </div>
                        </div>
                    </section>

                    <!-- Notes -->
                    <section>
                        <h4 class="text-lg font-semibold mb-3">Notes</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2 list-disc list-inside">
                            <li>The component automatically skips the "admin" segment if it's the first segment (since it's a route prefix)</li>
                            <li>Last item in the breadcrumb is always non-clickable (current page)</li>
                            <li>When truncation occurs, first item (Dashboard) and last 2 items are always visible</li>
                            <li>Long labels are automatically truncated with CSS ellipsis</li>
                            <li>Home icon links to Dashboard route or root URL</li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>

