<x-layouts.admin title="Dropdown - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Dropdown -->
            <div>
                @php
                    $code1 = '<x-ui.dropdown 
    label="Select Option" 
    name="option" 
    :options="[\'option1\' => \'Option 1\', \'option2\' => \'Option 2\', \'option3\' => \'Option 3\']" 
    placeholder="Choose an option"
/>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Dropdown</h3>
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
                    <x-ui.dropdown 
                        label="Select Option" 
                        name="option1" 
                        :options="['option1' => 'Option 1', 'option2' => 'Option 2', 'option3' => 'Option 3']" 
                        placeholder="Choose an option"
                    />
                </div>
            </div>

            <!-- Multi-select Dropdown -->
            <div>
                @php
                    $code2 = '<x-ui.dropdown 
    label="Select Multiple" 
    name="multi" 
    :options="[\'red\' => \'Red\', \'green\' => \'Green\', \'blue\' => \'Blue\']" 
    :multiple="true"
    :selected="[\'red\', \'green\']"
    placeholder="Choose options"
/>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Multi-select Dropdown</h3>
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
                    <x-ui.dropdown 
                        label="Select Multiple" 
                        name="multi1" 
                        :options="['red' => 'Red', 'green' => 'Green', 'blue' => 'Blue', 'yellow' => 'Yellow']" 
                        :multiple="true"
                        :selected="['red', 'green']"
                        placeholder="Choose options"
                    />
                </div>
            </div>

            <!-- Radio Mode Dropdown -->
            <div>
                @php
                    $code3 = '<x-ui.dropdown 
    label="Select One" 
    name="radio" 
    :options="[\'small\' => \'Small\', \'medium\' => \'Medium\', \'large\' => \'Large\']" 
    :radio="true"
    selected="medium"
    placeholder="Choose size"
/>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Radio Mode Dropdown</h3>
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
                    <x-ui.dropdown 
                        label="Select One" 
                        name="radio1" 
                        :options="['small' => 'Small', 'medium' => 'Medium', 'large' => 'Large']" 
                        :radio="true"
                        selected="medium"
                        placeholder="Choose size"
                    />
                </div>
            </div>

            <!-- Sizes -->
            <div>
                @php
                    $code4 = '<x-ui.dropdown label="Small" name="sm" size="sm" :options="[\'1\' => \'Option 1\']" />
<x-ui.dropdown label="Default" name="def" :options="[\'1\' => \'Option 1\']" />
<x-ui.dropdown label="Large" name="lg" size="lg" :options="[\'1\' => \'Option 1\']" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Sizes</h3>
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
                    <x-ui.dropdown label="Small" name="sm1" size="sm" :options="['1' => 'Option 1', '2' => 'Option 2']" placeholder="Small dropdown" />
                    <x-ui.dropdown label="Default" name="def1" :options="['1' => 'Option 1', '2' => 'Option 2']" placeholder="Default dropdown" />
                    <x-ui.dropdown label="Large" name="lg1" size="lg" :options="['1' => 'Option 1', '2' => 'Option 2']" placeholder="Large dropdown" />
                </div>
            </div>

            <!-- States -->
            <div>
                @php
                    $code5 = '<x-ui.dropdown label="With Hint" name="hint" hint="Select an option from the list" :options="[\'1\' => \'Option 1\']" />
<x-ui.dropdown label="With Error" name="error" error errorMessage="Please select an option" :options="[\'1\' => \'Option 1\']" />
<x-ui.dropdown label="Disabled" name="disabled" disabled :options="[\'1\' => \'Option 1\']" />
<x-ui.dropdown label="Required" name="required" required :options="[\'1\' => \'Option 1\']" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">States</h3>
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
                    <x-ui.dropdown label="With Hint" name="hint1" hint="Select an option from the list" :options="['1' => 'Option 1', '2' => 'Option 2']" placeholder="Choose..." />
                    <x-ui.dropdown label="With Error" name="error1" error errorMessage="Please select an option" :options="['1' => 'Option 1', '2' => 'Option 2']" placeholder="Choose..." />
                    <x-ui.dropdown label="Disabled" name="disabled1" disabled :options="['1' => 'Option 1', '2' => 'Option 2']" placeholder="Disabled" />
                    <x-ui.dropdown label="Required" name="required1" required :options="['1' => 'Option 1', '2' => 'Option 2']" placeholder="Required field" />
                </div>
            </div>

            <!-- Custom Slots -->
            <div>
                @php
                    $code6 = '<x-ui.dropdown>
    <x-slot:trigger>
        <button class="px-4 py-2 bg-blue-500 text-white rounded">Custom Trigger</button>
    </x-slot:trigger>
    <x-slot:content>
        <div class="py-1">
            <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">Item 1</a>
            <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">Item 2</a>
        </div>
    </x-slot:content>
</x-ui.dropdown>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Custom Slots</h3>
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
                <x-ui.dropdown>
                    <x-slot:trigger>
                        <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">Custom Trigger</button>
                    </x-slot:trigger>
                    <x-slot:content>
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Item 1</a>
                            <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Item 2</a>
                            <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Item 3</a>
                        </div>
                    </x-slot:content>
                </x-ui.dropdown>
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div class="space-y-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm p-6">
                <h2 class="text-2xl font-bold mb-4">Dropdown Component</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    A versatile dropdown component that supports single-select, multi-select, radio mode, and custom slot-based usage. Built with Alpine.js for smooth interactions.
                </p>

                <h3 class="text-xl font-semibold mb-3">Props</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">label</code> - string - Label text above the dropdown</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code> - string - Input name attribute</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">id</code> - string|null - Custom ID (auto-generated if not provided)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">options</code> - array - Options array in ['value' => 'label'] format</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">selected</code> - array|string - Selected value(s)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">placeholder</code> - string - Placeholder text (default: 'Placeholder')</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">hint</code> - string - Helper text</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">error</code> - bool - Error state</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">errorMessage</code> - string - Error message</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">required</code> - bool - Required field</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code> - bool - Disabled state</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">multiple</code> - bool - Enable multi-select mode (default: false)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">radio</code> - bool - Use radio buttons for single-select (default: false)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code> - string - 'sm' | 'default' | 'lg' (default: 'default')</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Slots</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">trigger</code> - Custom trigger button/element</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">content</code> - Custom dropdown content</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Modes</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><strong>Standard</strong> - Single-select dropdown with click-to-select</li>
                    <li><strong>Multi-select</strong> - Multiple selection with checkboxes</li>
                    <li><strong>Radio</strong> - Single-select with radio buttons</li>
                    <li><strong>Custom Slots</strong> - Fully customizable trigger and content</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Features</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li>• Single and multi-select modes</li>
                    <li>• Radio button mode for single-select</li>
                    <li>• Custom trigger and content slots</li>
                    <li>• Size options (sm, default, lg)</li>
                    <li>• Error and validation states</li>
                    <li>• Disabled state support</li>
                    <li>• Selected values display</li>
                    <li>• Dark mode support</li>
                    <li>• Responsive design</li>
                    <li>• Click outside to close</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Common Use Cases</h3>
                <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                    <div>
                        <p class="font-medium mb-1">Basic Single-select:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-ui.dropdown 
    label="Category" 
    name="category" 
    :options="['tech' => 'Technology', 'design' => 'Design']" 
/&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Multi-select:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-ui.dropdown 
    label="Tags" 
    name="tags" 
    :multiple="true"
    :options="['php' => 'PHP', 'js' => 'JavaScript']" 
/&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Custom Dropdown:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-ui.dropdown&gt;
    &lt;x-slot:trigger&gt;
        &lt;button&gt;Menu&lt;/button&gt;
    &lt;/x-slot:trigger&gt;
    &lt;x-slot:content&gt;
        &lt;div class="py-1"&gt;
            &lt;a href="#"&gt;Item 1&lt;/a&gt;
        &lt;/div&gt;
    &lt;/x-slot:content&gt;
&lt;/x-ui.dropdown&gt;</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>

