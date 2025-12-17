<x-layouts.admin title="Alert - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Alerts -->
            <div>
                @php
                    $code1 = '<x-alert variant="primary" title="Primary Alert" message="This is a primary alert message." />
<x-alert variant="success" title="Success Alert" message="Operation completed successfully!" />
<x-alert variant="warning" title="Warning Alert" message="Please review your input." />
<x-alert variant="error" title="Error Alert" message="Something went wrong." />
<x-alert variant="info" title="Info Alert" message="Here is some information." />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Alerts</h3>
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
                <div class="space-y-4">
                    <x-alert variant="primary" title="Primary Alert" message="This is a primary alert message." />
                    <x-alert variant="success" title="Success Alert" message="Operation completed successfully!" />
                    <x-alert variant="warning" title="Warning Alert" message="Please review your input." />
                    <x-alert variant="error" title="Error Alert" message="Something went wrong." />
                    <x-alert variant="info" title="Info Alert" message="Here is some information." />
                </div>
            </div>

            <!-- Alerts with Icons -->
            <div>
                @php
                    $code2 = '<x-alert variant="success" icon="check-circle" title="Success" message="Your changes have been saved." />
<x-alert variant="warning" icon="exclamation-triangle" title="Warning" message="Your session will expire soon." />
<x-alert variant="error" icon="times-circle" title="Error" message="Failed to process your request." />
<x-alert variant="info" icon="info-circle" title="Info" message="New features are available." />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Alerts with Icons</h3>
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
                <div class="space-y-4">
                    <x-alert variant="success" icon="check-circle" title="Success" message="Your changes have been saved." />
                    <x-alert variant="warning" icon="exclamation-triangle" title="Warning" message="Your session will expire soon." />
                    <x-alert variant="error" icon="times-circle" title="Error" message="Failed to process your request." />
                    <x-alert variant="info" icon="info-circle" title="Info" message="New features are available." />
                </div>
            </div>

            <!-- Alerts without Title -->
            <div>
                @php
                    $code3 = '<x-alert variant="success" message="Operation completed successfully!" />
<x-alert variant="warning" message="Please review your input before submitting." />
<x-alert variant="error" message="An error occurred. Please try again." />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Alerts without Title</h3>
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
                    <x-alert variant="success" message="Operation completed successfully!" />
                    <x-alert variant="warning" message="Please review your input before submitting." />
                    <x-alert variant="error" message="An error occurred. Please try again." />
                </div>
            </div>

            <!-- Alerts with Slots -->
            <div>
                @php
                    $code4 = '<x-alert variant="info">
    <x-slot:title>Custom Title</x-slot:title>
    <x-slot:message>This alert uses slots for custom content.</x-slot:message>
</x-alert>

<x-alert variant="warning">
    <x-slot:title>Warning</x-slot:title>
    Custom message with <strong>HTML</strong> content.
</x-alert>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Alerts with Slots</h3>
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
                <div class="space-y-4">
                    <x-alert variant="info">
                        <x-slot:title>Custom Title</x-slot:title>
                        <x-slot:message>This alert uses slots for custom content.</x-slot:message>
                    </x-alert>
                    <x-alert variant="warning">
                        <x-slot:title>Warning</x-slot:title>
                        Custom message with <strong>HTML</strong> content.
                    </x-alert>
                </div>
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div class="space-y-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm p-6">
                <h2 class="text-2xl font-bold mb-4">Alert Component</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    A flexible alert component for displaying important messages to users. Supports multiple variants, icons, and custom content via slots.
                </p>

                <h3 class="text-xl font-semibold mb-3">Props</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">variant</code> - string|null - 'primary' | 'success' | 'warning' | 'error' | 'info' (default: 'primary')</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">icon</code> - string|null - FontAwesome icon name (e.g., 'check-circle', 'exclamation-triangle')</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">title</code> - string|null - Alert title text</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">message</code> - string|null - Alert message text</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Slots</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">title</code> - Custom title content (overrides title prop)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">message</code> - Custom message content (overrides message prop)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">default</code> - Default slot (used when no message prop or slot provided)</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Variants</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><strong>primary</strong> - Uses accent color from theme</li>
                    <li><strong>success</strong> - Green color scheme</li>
                    <li><strong>warning</strong> - Yellow color scheme</li>
                    <li><strong>error</strong> - Red color scheme</li>
                    <li><strong>info</strong> - Sky/blue color scheme</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Features</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li>• Multiple color variants</li>
                    <li>• Optional icon support</li>
                    <li>• Title and message props</li>
                    <li>• Slot-based content for flexibility</li>
                    <li>• HTML content support in slots</li>
                    <li>• Dark mode support</li>
                    <li>• Responsive design</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Common Use Cases</h3>
                <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                    <div>
                        <p class="font-medium mb-1">Basic Alert:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-alert variant="success" title="Success" message="Operation completed!" /&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">With Icon:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-alert variant="error" icon="times-circle" title="Error" message="Something went wrong." /&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Using Slots:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-alert variant="warning"&gt;
    &lt;x-slot:title&gt;Warning&lt;/x-slot:title&gt;
    Custom message with &lt;strong&gt;HTML&lt;/strong&gt;.
&lt;/x-alert&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Simple Message:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-alert variant="info" message="Here is some information." /&gt;</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>

