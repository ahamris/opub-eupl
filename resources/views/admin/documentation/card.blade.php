<x-layouts.admin title="Card - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Stat Card -->
            <div>
                @php
                    $code1 = '<x-ui.card 
    icon="users" 
    icon-color="primary" 
    title="Total Users" 
    value="12,345" 
    action-text="View details"
    action-url="#"
/>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Stat Card</h3>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-ui.card 
                        icon="users" 
                        icon-color="primary" 
                        title="Total Users" 
                        value="12,345" 
                        action-text="View details"
                        action-url="#"
                    />
                    <x-ui.card 
                        icon="chart-line" 
                        icon-color="success" 
                        title="Revenue" 
                        value="₺125,450" 
                        action-text="View report"
                        action-url="#"
                    />
                </div>
            </div>

            <!-- Variants -->
            <div>
                @php
                    $code2 = '<x-ui.card variant="default" icon="box" icon-color="primary" title="Default" value="100" />
<x-ui.card variant="outlined" icon="box" icon-color="success" title="Outlined" value="100" />
<x-ui.card variant="filled" icon="box" icon-color="warning" title="Filled" value="100" />
<x-ui.card variant="elevated" icon="box" icon-color="error" title="Elevated" value="100" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Variants</h3>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-ui.card variant="default" icon="box" icon-color="primary" title="Default" value="100" />
                    <x-ui.card variant="outlined" icon="box" icon-color="success" title="Outlined" value="100" />
                    <x-ui.card variant="filled" icon="box" icon-color="warning" title="Filled" value="100" />
                    <x-ui.card variant="elevated" icon="box" icon-color="error" title="Elevated" value="100" />
                </div>
            </div>

            <!-- Sizes -->
            <div>
                @php
                    $code3 = '<x-ui.card size="sm" icon="star" icon-color="primary" title="Small" value="50" />
<x-ui.card icon="star" icon-color="primary" title="Default" value="100" />
<x-ui.card size="lg" icon="star" icon-color="primary" title="Large" value="200" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Sizes</h3>
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-ui.card size="sm" icon="star" icon-color="primary" title="Small" value="50" />
                    <x-ui.card icon="star" icon-color="primary" title="Default" value="100" />
                    <x-ui.card size="lg" icon="star" icon-color="primary" title="Large" value="200" />
                </div>
            </div>

            <!-- Icon Colors -->
            <div>
                @php
                    $code4 = '<x-ui.card icon="check" icon-color="primary" title="Primary" value="100" />
<x-ui.card icon="check" icon-color="success" title="Success" value="100" />
<x-ui.card icon="check" icon-color="warning" title="Warning" value="100" />
<x-ui.card icon="check" icon-color="error" title="Error" value="100" />
<x-ui.card icon="check" icon-color="sky" title="Sky" value="100" />
<x-ui.card icon="check" icon-color="secondary" title="Secondary" value="100" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Icon Colors</h3>
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <x-ui.card icon="check" icon-color="primary" title="Primary" value="100" />
                    <x-ui.card icon="check" icon-color="success" title="Success" value="100" />
                    <x-ui.card icon="check" icon-color="warning" title="Warning" value="100" />
                    <x-ui.card icon="check" icon-color="error" title="Error" value="100" />
                    <x-ui.card icon="check" icon-color="sky" title="Sky" value="100" />
                    <x-ui.card icon="check" icon-color="secondary" title="Secondary" value="100" />
                </div>
            </div>

            <!-- Slot-based Card -->
            <div>
                @php
                    $code5 = '<x-ui.card>
    <x-slot:header>
        <h3 class="card-title">Card Title</h3>
    </x-slot:header>
    <x-slot:body>
        <p>This is the card body content. You can put any content here.</p>
    </x-slot:body>
    <x-slot:footer>
        <button class="px-4 py-2 bg-blue-500 text-white rounded">Action</button>
    </x-slot:footer>
</x-ui.card>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Slot-based Card</h3>
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
                <x-ui.card>
                    <x-slot:header>
                        <h3 class="card-title">Card Title</h3>
                    </x-slot:header>
                    <x-slot:body>
                        <p class="text-sm text-gray-600 dark:text-gray-400">This is the card body content. You can put any content here.</p>
                    </x-slot:body>
                    <x-slot:footer>
                        <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">Action</button>
                    </x-slot:footer>
                </x-ui.card>
            </div>

            <!-- Clickable Card -->
            <div>
                @php
                    $code6 = '<x-ui.card 
    clickable 
    click-url="#"
    icon="arrow-right" 
    icon-color="primary" 
    title="Clickable Card" 
    value="Click me"
/>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Clickable Card</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code6 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-ui.card 
                    clickable 
                    click-url="#"
                    icon="arrow-right" 
                    icon-color="primary" 
                    title="Clickable Card" 
                    value="Click me"
                />
            </div>

            <!-- Loading State -->
            <div>
                @php
                    $code7 = '<x-ui.card loading />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Loading State</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code7 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-ui.card loading />
            </div>

            <!-- Without Hover -->
            <div>
                @php
                    $code8 = '<x-ui.card :hover="false" icon="lock" icon-color="secondary" title="No Hover" value="Static" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Without Hover</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code8 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-ui.card :hover="false" icon="lock" icon-color="secondary" title="No Hover" value="Static" />
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div class="space-y-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm p-6">
                <h2 class="text-2xl font-bold mb-4">Card Component</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    A flexible card component that supports both stat card style (with icon, title, value) and slot-based usage (header, body, footer). Perfect for dashboards, data displays, and content containers.
                </p>

                <h3 class="text-xl font-semibold mb-3">Stat Card Props</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">icon</code> - string - FontAwesome icon name (default: 'check')</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">iconColor</code> - string - 'primary' | 'success' | 'warning' | 'error' | 'sky' | 'secondary' (default: 'primary')</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">title</code> - string - Title text above the value</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">value</code> - string - Main value to display</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">actionText</code> - string - Text for the action link (default: 'View details')</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">actionUrl</code> - string - URL for the action link (default: 'javascript:void(0)')</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">General Props</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">variant</code> - string - 'default' | 'outlined' | 'filled' | 'elevated' (default: 'default')</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code> - string|null - 'sm' | 'md' | 'lg' | null (default: null)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">hover</code> - bool - Enable hover shadow effect (default: true)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">image</code> - string|null - Image URL for card header</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">imageAlt</code> - string|null - Alt text for the image</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">clickable</code> - bool - Make entire card clickable (default: false)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">clickUrl</code> - string|null - URL when card is clickable</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">loading</code> - bool - Show loading skeleton (default: false)</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Slots</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">header</code> - Card header content</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">body</code> - Card body content</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">footer</code> - Card footer content</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">default</code> - Default slot (used when no body slot provided)</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Variants</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><strong>default</strong> - Standard card with border and shadow</li>
                    <li><strong>outlined</strong> - Card with thicker border, no shadow</li>
                    <li><strong>filled</strong> - Card with background color</li>
                    <li><strong>elevated</strong> - Card with stronger shadow</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Sizes</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><strong>sm</strong> - Small padding (p-3), smaller icon and text</li>
                    <li><strong>md</strong> - Medium padding (p-5), default sizes</li>
                    <li><strong>lg</strong> - Large padding (p-6), larger icon and text</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Features</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li>• Two usage modes: Stat card (props) or flexible slots</li>
                    <li>• Multiple variants for different visual styles</li>
                    <li>• Size options for different use cases</li>
                    <li>• Icon color themes matching your design system</li>
                    <li>• Image support for card headers</li>
                    <li>• Clickable card option</li>
                    <li>• Loading skeleton state</li>
                    <li>• Hover effects (optional)</li>
                    <li>• Dark mode support</li>
                    <li>• Responsive design</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Common Use Cases</h3>
                <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                    <div>
                        <p class="font-medium mb-1">Dashboard Stat Card:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-ui.card 
    icon="users" 
    icon-color="primary" 
    title="Total Users" 
    value="12,345" 
    action-text="View all"
    action-url="/users"
/&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Slot-based Card:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-ui.card&gt;
    &lt;x-slot:header&gt;
        &lt;h3 class="card-title"&gt;Card Title&lt;/h3&gt;
    &lt;/x-slot:header&gt;
    &lt;x-slot:body&gt;
        &lt;p&gt;Card content here&lt;/p&gt;
    &lt;/x-slot:body&gt;
    &lt;x-slot:footer&gt;
        &lt;button&gt;Action&lt;/button&gt;
    &lt;/x-slot:footer&gt;
&lt;/x-ui.card&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Clickable Card:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-ui.card 
    clickable 
    click-url="/details"
    icon="arrow-right" 
    icon-color="primary" 
    title="View Details" 
    value="Click me"
/&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Card with Image:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-ui.card 
    image="/path/to/image.jpg"
    image-alt="Card image"
&gt;
    &lt;x-slot:body&gt;
        &lt;p&gt;Content below image&lt;/p&gt;
    &lt;/x-slot:body&gt;
&lt;/x-ui.card&gt;</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>

