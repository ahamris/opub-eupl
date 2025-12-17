<x-layouts.admin title="Divider - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Divider -->
            @php
                $code1 = '<x-divider />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Divider</h3>
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
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Content above</p>
                        <x-divider />
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Content below</p>
                    </div>
                </div>
            </div>

            <!-- Divider Variants -->
            @php
                $code2 = '<x-divider variant="solid" />
<x-divider variant="dashed" />
<x-divider variant="dotted" />';
            @endphp
            <div>
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
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Solid (default)</p>
                        <x-divider variant="solid" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Dashed</p>
                        <x-divider variant="dashed" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Dotted</p>
                        <x-divider variant="dotted" />
                    </div>
                </div>
            </div>

            <!-- Divider Sizes -->
            @php
                $code3 = '<x-divider size="sm" />
<x-divider size="md" />
<x-divider size="lg" />
<x-divider size="xl" />';
            @endphp
            <div>
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
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Small (default)</p>
                        <x-divider size="sm" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Medium</p>
                        <x-divider size="md" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Large</p>
                        <x-divider size="lg" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Extra Large</p>
                        <x-divider size="xl" />
                    </div>
                </div>
            </div>

            <!-- Divider with Text -->
            @php
                $code4 = '<x-divider text="OR" />
<x-divider text="Continue with" />
<x-divider text="Separator" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Text</h3>
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
                    <x-divider text="OR" />
                    <x-divider text="Continue with" />
                    <x-divider text="Separator" />
                </div>
            </div>

            <!-- Colored Dividers -->
            @php
                $code5 = '<x-divider color="accent" />
<x-divider color="blue" />
<x-divider color="red" />
<x-divider color="green" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Colors</h3>
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
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Accent Color</p>
                        <x-divider color="accent" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Blue</p>
                        <x-divider color="blue" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Red</p>
                        <x-divider color="red" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Green</p>
                        <x-divider color="green" />
                    </div>
                </div>
            </div>

            <!-- Vertical Divider -->
            @php
                $code6 = '<div class="flex items-center gap-4">
    <div>Left Content</div>
    <x-divider orientation="vertical" />
    <div>Right Content</div>
</div>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Vertical Divider</h3>
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
                <div class="flex items-center gap-4 h-20">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Left Content</div>
                    <x-divider orientation="vertical" />
                    <div class="text-sm text-gray-600 dark:text-gray-400">Right Content</div>
                </div>
            </div>

            <!-- Custom Width -->
            @php
                $code7 = '<x-divider width="50%" />
<x-divider width="200px" />
<x-divider width="75%" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Custom Width</h3>
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
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Width: 50%</p>
                        <x-divider width="50%" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Width: 200px</p>
                        <x-divider width="200px" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Width: 75%</p>
                        <x-divider width="75%" />
                    </div>
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
                    <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-divider /&gt;</code></pre>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Props</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">orientation</code> - string (default: <code class="text-xs">'horizontal'</code>, alternative: <code class="text-xs">'vertical'</code>)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">variant</code> - string (default: <code class="text-xs">'solid'</code>, alternatives: <code class="text-xs">'dashed'</code>, <code class="text-xs">'dotted'</code>)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">color</code> - string|null (default: <code class="text-xs">null</code> - zinc, alternatives: <code class="text-xs">'accent'</code> or any Tailwind color)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">text</code> - string|null (optional text to display in the middle of the divider)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code> - string (default: <code class="text-xs">'sm'</code>, alternatives: <code class="text-xs">'md'</code>, <code class="text-xs">'lg'</code>, <code class="text-xs">'xl'</code>)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">width</code> - string|null (custom width, e.g., <code class="text-xs">'50%'</code>, <code class="text-xs">'200px'</code>)</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Examples</h4>
                    <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;!-- Basic --&gt;
&lt;x-divider /&gt;

&lt;!-- Variants --&gt;
&lt;x-divider variant="solid" /&gt;
&lt;x-divider variant="dashed" /&gt;
&lt;x-divider variant="dotted" /&gt;

&lt;!-- Sizes --&gt;
&lt;x-divider size="sm" /&gt;
&lt;x-divider size="md" /&gt;
&lt;x-divider size="lg" /&gt;
&lt;x-divider size="xl" /&gt;

&lt;!-- With Text --&gt;
&lt;x-divider text="OR" /&gt;
&lt;x-divider text="Continue with" /&gt;

&lt;!-- Colors --&gt;
&lt;x-divider color="accent" /&gt;
&lt;x-divider color="blue" /&gt;
&lt;x-divider color="red" /&gt;

&lt;!-- Vertical --&gt;
&lt;div class="flex items-center gap-4"&gt;
    &lt;div&gt;Left&lt;/div&gt;
    &lt;x-divider orientation="vertical" /&gt;
    &lt;div&gt;Right&lt;/div&gt;
&lt;/div&gt;

&lt;!-- Custom Width --&gt;
&lt;x-divider width="50%" /&gt;
&lt;x-divider width="200px" /&gt;
&lt;x-divider width="75%" /&gt;</code></pre>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Use Cases</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li>Separating content sections</li>
                        <li>Dividing form fields</li>
                        <li>Creating visual breaks in lists</li>
                        <li>Vertical dividers in horizontal layouts</li>
                        <li>Text dividers for "OR" or "Continue with" scenarios</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>

