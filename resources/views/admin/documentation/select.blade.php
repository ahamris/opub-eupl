<x-layouts.admin title="Select - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Select -->
            <div>
                @php
                    $code1 = '<x-select 
    label="Select Option" 
    name="option" 
    :options="[\'option1\' => \'Option 1\', \'option2\' => \'Option 2\', \'option3\' => \'Option 3\']" 
    placeholder="Choose an option"
/>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Select</h3>
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
                    <x-select 
                        label="Select Option" 
                        name="option1" 
                        :options="['option1' => 'Option 1', 'option2' => 'Option 2', 'option3' => 'Option 3']" 
                        placeholder="Choose an option"
                    />
                </div>
            </div>

            <!-- With Selected Value -->
            <div>
                @php
                    $code2 = '<x-select 
    label="Country" 
    name="country" 
    :options="[\'us\' => \'United States\', \'uk\' => \'United Kingdom\', \'tr\' => \'Turkey\']" 
    value="tr"
/>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Selected Value</h3>
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
                    <x-select 
                        label="Country" 
                        name="country1" 
                        :options="['us' => 'United States', 'uk' => 'United Kingdom', 'tr' => 'Turkey', 'de' => 'Germany']" 
                        value="tr"
                    />
                </div>
            </div>

            <!-- Sizes -->
            <div>
                @php
                    $code3 = '<x-select label="Small" name="sm" size="sm" :options="[\'1\' => \'Option 1\']" />
<x-select label="Default" name="def" :options="[\'1\' => \'Option 1\']" />
<x-select label="Large" name="lg" size="lg" :options="[\'1\' => \'Option 1\']" />';
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
                <div class="space-y-4">
                    <x-select label="Small" name="sm1" size="sm" :options="['1' => 'Option 1', '2' => 'Option 2']" placeholder="Small select" />
                    <x-select label="Default" name="def1" :options="['1' => 'Option 1', '2' => 'Option 2']" placeholder="Default select" />
                    <x-select label="Large" name="lg1" size="lg" :options="['1' => 'Option 1', '2' => 'Option 2']" placeholder="Large select" />
                </div>
            </div>

            <!-- States -->
            <div>
                @php
                    $code4 = '<x-select label="With Hint" name="hint" hint="Select an option from the list" :options="[\'1\' => \'Option 1\']" />
<x-select label="With Error" name="error" error errorMessage="Please select an option" :options="[\'1\' => \'Option 1\']" />
<x-select label="Disabled" name="disabled" disabled :options="[\'1\' => \'Option 1\']" />
<x-select label="Required" name="required" required :options="[\'1\' => \'Option 1\']" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">States</h3>
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
                    <x-select label="With Hint" name="hint1" hint="Select an option from the list" :options="['1' => 'Option 1', '2' => 'Option 2']" placeholder="Choose..." />
                    <x-select label="With Error" name="error1" error errorMessage="Please select an option" :options="['1' => 'Option 1', '2' => 'Option 2']" placeholder="Choose..." />
                    <x-select label="Disabled" name="disabled1" disabled :options="['1' => 'Option 1', '2' => 'Option 2']" placeholder="Disabled" />
                    <x-select label="Required" name="required1" required :options="['1' => 'Option 1', '2' => 'Option 2']" placeholder="Required field" />
                </div>
            </div>

            <!-- With Slot Options -->
            <div>
                @php
                    $code5 = '<x-select label="Custom Options" name="custom" placeholder="Choose...">
    <option value="1">Option 1</option>
    <option value="2">Option 2</option>
    <optgroup label="Group 1">
        <option value="3">Option 3</option>
        <option value="4">Option 4</option>
    </optgroup>
</x-select>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Slot Options</h3>
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
                    <x-select label="Custom Options" name="custom1" placeholder="Choose...">
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <optgroup label="Group 1">
                            <option value="3">Option 3</option>
                            <option value="4">Option 4</option>
                        </optgroup>
                    </x-select>
                </div>
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div class="space-y-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm p-6">
                <h2 class="text-2xl font-bold mb-4">Select Component</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    A native HTML select element wrapper with enhanced styling, validation states, and support for both array-based and slot-based options.
                </p>

                <h3 class="text-xl font-semibold mb-3">Props</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">label</code> - string - Label text above the select</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code> - string - Input name attribute</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">id</code> - string|null - Custom ID (auto-generated if not provided)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">value</code> - string - Selected value</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">placeholder</code> - string - Placeholder text for the select</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">options</code> - array - Options array in ['value' => 'label'] format</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">hint</code> - string - Helper text</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">error</code> - bool - Error state</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">errorMessage</code> - string - Error message</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">required</code> - bool - Required field</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code> - bool - Disabled state</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code> - string|null - 'sm' | 'lg' | null (default: null)</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Slots</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">default</code> - Custom option elements (can use &lt;optgroup&gt; for grouping)</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Sizes</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><strong>sm</strong> - Small padding and text size</li>
                    <li><strong>default</strong> - Standard padding and text size</li>
                    <li><strong>lg</strong> - Large padding and text size</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Features</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li>• Native HTML select element</li>
                    <li>• Array-based options support</li>
                    <li>• Slot-based custom options</li>
                    <li>• Optgroup support via slots</li>
                    <li>• Size options (sm, default, lg)</li>
                    <li>• Error and validation states</li>
                    <li>• Disabled state support</li>
                    <li>• Custom arrow styling</li>
                    <li>• Dark mode support</li>
                    <li>• Responsive design</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Common Use Cases</h3>
                <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                    <div>
                        <p class="font-medium mb-1">Basic Select with Array:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-select 
    label="Country" 
    name="country" 
    :options="['us' => 'United States', 'uk' => 'United Kingdom']" 
/&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">With Selected Value:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-select 
    label="Country" 
    name="country" 
    :options="['us' => 'United States', 'uk' => 'United Kingdom']" 
    value="us"
/&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Custom Options with Optgroup:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-select label="Category" name="category"&gt;
    &lt;optgroup label="Fruits"&gt;
        &lt;option value="apple"&gt;Apple&lt;/option&gt;
        &lt;option value="banana"&gt;Banana&lt;/option&gt;
    &lt;/optgroup&gt;
    &lt;optgroup label="Vegetables"&gt;
        &lt;option value="carrot"&gt;Carrot&lt;/option&gt;
        &lt;option value="potato"&gt;Potato&lt;/option&gt;
    &lt;/optgroup&gt;
&lt;/x-select&gt;</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>

