<x-layouts.admin title="Badges - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Badges -->
            @php
                $code1 = '<x-badge variant="primary">Primary</x-badge>
<x-badge variant="secondary">Secondary</x-badge>
<x-badge variant="success">Success</x-badge>
<x-badge variant="warning">Warning</x-badge>
<x-badge variant="error">Error</x-badge>
<x-badge variant="sky">Sky</x-badge>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Badges</h3>
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
                <div class="flex items-center gap-2 flex-wrap">
                    <x-badge variant="primary">Primary</x-badge>
                    <x-badge variant="secondary">Secondary</x-badge>
                    <x-badge variant="success">Success</x-badge>
                    <x-badge variant="warning">Warning</x-badge>
                    <x-badge variant="error">Error</x-badge>
                    <x-badge variant="sky">Sky</x-badge>
                </div>
            </div>

            <!-- Badge Sizes -->
            @php
                $code2 = '<x-badge variant="primary" size="sm">Small</x-badge>
<x-badge variant="primary">Default</x-badge>
<x-badge variant="primary" size="lg">Large</x-badge>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Badge Sizes</h3>
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
                <div class="flex items-center gap-2 flex-wrap">
                    <x-badge variant="primary" size="sm">Small</x-badge>
                    <x-badge variant="primary">Default</x-badge>
                    <x-badge variant="primary" size="lg">Large</x-badge>
                </div>
            </div>

            <!-- Dot Badges -->
            @php
                $code3 = '<x-badge variant="primary" dot>Primary</x-badge>
<x-badge variant="secondary" dot>Secondary</x-badge>
<x-badge variant="success" dot>Success</x-badge>
<x-badge variant="warning" dot>Warning</x-badge>
<x-badge variant="error" dot>Error</x-badge>
<x-badge variant="sky" dot>Sky</x-badge>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Dot Badges</h3>
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
                <div class="flex items-center gap-2 flex-wrap">
                    <x-badge variant="primary" dot>Primary</x-badge>
                    <x-badge variant="secondary" dot>Secondary</x-badge>
                    <x-badge variant="success" dot>Success</x-badge>
                    <x-badge variant="warning" dot>Warning</x-badge>
                    <x-badge variant="error" dot>Error</x-badge>
                    <x-badge variant="sky" dot>Sky</x-badge>
                </div>
            </div>

            <!-- Badges with Icons -->
            @php
                $code4 = '<x-badge variant="primary" icon="check">Verified</x-badge>
<x-badge variant="success" icon="star">Featured</x-badge>
<x-badge variant="warning" icon="bell">Notification</x-badge>
<x-badge variant="error" icon="exclamation-triangle">Alert</x-badge>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Badges with Icons</h3>
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
                <div class="flex items-center gap-2 flex-wrap">
                    <x-badge variant="primary" icon="check">Verified</x-badge>
                    <x-badge variant="success" icon="star">Featured</x-badge>
                    <x-badge variant="warning" icon="bell">Notification</x-badge>
                    <x-badge variant="error" icon="exclamation-triangle">Alert</x-badge>
                </div>
            </div>

            <!-- Icon Only Badges -->
            @php
                $code5 = '<x-badge variant="primary" icon="check"></x-badge>
<x-badge variant="success" icon="star"></x-badge>
<x-badge variant="warning" icon="bell"></x-badge>
<x-badge variant="error" icon="exclamation-triangle"></x-badge>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Icon Only Badges</h3>
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
                <div class="flex items-center gap-2 flex-wrap">
                    <x-badge variant="primary" icon="check"></x-badge>
                    <x-badge variant="success" icon="star"></x-badge>
                    <x-badge variant="warning" icon="bell"></x-badge>
                    <x-badge variant="error" icon="exclamation-triangle"></x-badge>
                </div>
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div>
            <div>
                <h3 class="text-xl font-semibold mb-4">Documentation</h3>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm p-6 space-y-6">
                <div>
                    <h4 class="text-lg font-semibold mb-3">Basic Usage</h4>
                    <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-badge variant="primary"&gt;Badge Text&lt;/x-badge&gt;</code></pre>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-3">Badge Props</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">variant</code>
                            <span class="text-gray-600 dark:text-gray-400"> - string</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">primary, secondary, success, warning, error, sky</p>
                        </li>
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code>
                            <span class="text-gray-600 dark:text-gray-400"> - string</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">sm, lg (default normal)</p>
                        </li>
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">dot</code>
                            <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Display a small colored dot before the text</p>
                        </li>
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">icon</code>
                            <span class="text-gray-600 dark:text-gray-400"> - string</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Font Awesome icon name (e.g., "check" becomes "fa-solid fa-check")</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>


