<x-layouts.admin title="Tag Input - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Tag Input -->
            @php
                $code1 = '<x-tag-input name="tags" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Tag Input</h3>
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
                    <x-tag-input name="tags" />
                </div>
            </div>

            <!-- With Initial Tags -->
            @php
                $code2 = '<x-tag-input name="tags" :tags="[\'Tag 1\', \'Tag 2\', \'Tag 3\']" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Initial Tags</h3>
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
                    <x-tag-input name="tags2" :tags="['Tag 1', 'Tag 2', 'Tag 3']" />
                </div>
            </div>

            <!-- With Dropdown Options -->
            @php
                $code3 = '<x-tag-input 
    name="tags" 
    :options="[
        [\'value\' => \'1\', \'label\' => \'JavaScript\'],
        [\'value\' => \'2\', \'label\' => \'PHP\'],
        [\'value\' => \'3\', \'label\' => \'Python\'],
        [\'value\' => \'4\', \'label\' => \'Laravel\'],
        [\'value\' => \'5\', \'label\' => \'Vue.js\'],
    ]"
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Dropdown Options</h3>
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
                    <x-tag-input 
                        name="tags3" 
                        :options="[
                            ['value' => '1', 'label' => 'JavaScript'],
                            ['value' => '2', 'label' => 'PHP'],
                            ['value' => '3', 'label' => 'Python'],
                            ['value' => '4', 'label' => 'Laravel'],
                            ['value' => '5', 'label' => 'Vue.js'],
                        ]"
                    />
                </div>
            </div>

            <!-- With Label and Hint -->
            @php
                $code4 = '<x-tag-input 
    name="tags" 
    label="Tags"
    hint="Type and press comma or enter to add tags"
/>';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Label and Hint</h3>
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
                    <x-tag-input 
                        name="tags4" 
                        label="Tags"
                        hint="Type and press comma or enter to add tags"
                    />
                </div>
            </div>

            <!-- Sizes -->
            @php
                $code5 = '<x-tag-input name="tags" size="sm" />
<x-tag-input name="tags" size="md" />
<x-tag-input name="tags" size="lg" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Sizes</h3>
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
                    <x-tag-input name="tags5" size="sm" />
                    <x-tag-input name="tags6" size="md" />
                    <x-tag-input name="tags7" size="lg" />
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
                    <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-tag-input name="tags" /&gt;</code></pre>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Props</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">label</code> - string (label text)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code> - string (input name)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">id</code> - string|null (input id, auto-generated if not provided)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">tags</code> - array (default: <code class="text-xs">[]</code>, initial tags array)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">placeholder</code> - string (default: <code class="text-xs">'Type and press comma or enter...'</code>)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">options</code> - array|null (dropdown options: <code class="text-xs">[['value' => '1', 'label' => 'Option 1'], ...]</code>)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">hint</code> - string (hint text)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">error</code> - bool (error state)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">errorMessage</code> - string (error message)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">required</code> - bool (required field)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code> - bool (disabled state)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code> - string|null (default: <code class="text-xs">null</code>, alternatives: <code class="text-xs">'sm'</code>, <code class="text-xs">'lg'</code>)</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Livewire Integration</h4>
                    <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-tag-input wire:model="tags" /&gt;</code></pre>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                        The component uses <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">wire:model</code> for real-time synchronization with Livewire component properties. Tags are automatically synced as an array.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Examples</h4>
                    <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;!-- Basic --&gt;
&lt;x-tag-input name="tags" /&gt;

&lt;!-- With initial tags --&gt;
&lt;x-tag-input name="tags" :tags="['Tag 1', 'Tag 2']" /&gt;

&lt;!-- With dropdown options --&gt;
&lt;x-tag-input 
    name="tags" 
    :options="[
        ['value' => '1', 'label' => 'Option 1'],
        ['value' => '2', 'label' => 'Option 2'],
    ]"
/&gt;

&lt;!-- With label and hint --&gt;
&lt;x-tag-input 
    name="tags" 
    label="Tags"
    hint="Add tags by typing and pressing comma"
/&gt;

&lt;!-- Livewire --&gt;
&lt;x-tag-input wire:model="tags" /&gt;

&lt;!-- Sizes --&gt;
&lt;x-tag-input name="tags" size="sm" /&gt;
&lt;x-tag-input name="tags" size="lg" /&gt;</code></pre>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Features</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li>Type and press comma or Enter to add tags</li>
                        <li>Click X button to remove tags</li>
                        <li>Press Backspace on empty input to remove last tag</li>
                        <li>Dropdown options for selecting from predefined list</li>
                        <li>Filter dropdown options as you type</li>
                        <li>Arrow keys to navigate dropdown</li>
                        <li>Livewire integration with <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">wire:model</code></li>
                        <li>Prevents duplicate tags</li>
                        <li>Dark mode support</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Keyboard Shortcuts</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li><kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-xs">Comma</kbd> or <kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-xs">Enter</kbd> - Add tag</li>
                        <li><kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-xs">Backspace</kbd> - Remove last tag (when input is empty)</li>
                        <li><kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-xs">Arrow Down</kbd> - Navigate dropdown down</li>
                        <li><kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-xs">Arrow Up</kbd> - Navigate dropdown up</li>
                        <li><kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-xs">Escape</kbd> - Close dropdown</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>

