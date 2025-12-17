<x-layouts.admin title="Color Picker - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Color Picker -->
            <div>
                @php
                    $code1 = '<x-colorpicker label="Color" name="color" value="#3B82F6" />
<x-colorpicker label="With hint" name="color2" value="#6366F1" hint="Select a color for your theme" />
<x-colorpicker label="With placeholder" name="color3" placeholder="#000000" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Color Picker</h3>
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
                    <x-colorpicker label="Color" name="color" value="#3B82F6" />
                    <x-colorpicker label="With hint" name="color2" value="#6366F1" hint="Select a color for your theme" />
                    <x-colorpicker label="With placeholder" name="color3" placeholder="#000000" />
                </div>
            </div>

            <!-- Sizes -->
            <div>
                @php
                    $code2 = '<x-colorpicker label="Small" size="sm" name="color_sm" value="#10B981" />
<x-colorpicker label="Default" name="color_default" value="#3B82F6" />
<x-colorpicker label="Large" size="lg" name="color_lg" value="#F59E0B" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Sizes</h3>
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
                    <x-colorpicker label="Small" size="sm" name="color_sm" value="#10B981" />
                    <x-colorpicker label="Default" name="color_default" value="#3B82F6" />
                    <x-colorpicker label="Large" size="lg" name="color_lg" value="#F59E0B" />
                </div>
            </div>

            <!-- Display Options -->
            <div>
                @php
                    $code3 = '<x-colorpicker label="Without presets" name="color_no_presets" :show-presets="false" value="#EF4444" />
<x-colorpicker label="Without input" name="color_no_input" :show-input="false" value="#8B5CF6" />
<x-colorpicker label="Input only" name="color_input_only" :show-presets="false" :show-input="true" value="#10B981" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Display Options</h3>
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
                    <x-colorpicker label="Without presets" name="color_no_presets" :show-presets="false" value="#EF4444" />
                    <x-colorpicker label="Without input" name="color_no_input" :show-input="false" value="#8B5CF6" />
                    <x-colorpicker label="Input only" name="color_input_only" :show-presets="false" :show-input="true" value="#10B981" />
                </div>
            </div>

            <!-- Custom Presets -->
            <div>
                @php
                    $code4 = '<x-colorpicker label="Custom presets" name="color_custom" :presets="[\'#FF0000\', \'#00FF00\', \'#0000FF\', \'#FFFF00\', \'#FF00FF\']" value="#FF0000" />
<x-colorpicker label="Brand colors" name="color_brand" :presets="[\'#3B82F6\', \'#10B981\', \'#F59E0B\', \'#EF4444\', \'#8B5CF6\']" value="#3B82F6" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Custom Presets</h3>
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
                    <x-colorpicker label="Custom presets" name="color_custom" :presets="['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF']" value="#FF0000" />
                    <x-colorpicker label="Brand colors" name="color_brand" :presets="['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6']" value="#3B82F6" />
                </div>
            </div>

            <!-- States -->
            <div>
                @php
                    $code5 = '<x-colorpicker label="With error" name="color_error" error errorMessage="Please select a color" value="#000000" />
<x-colorpicker label="Disabled" name="color_disabled" disabled value="#6B7280" />
<x-colorpicker label="Readonly" name="color_readonly" readonly value="#8B5CF6" />
<x-colorpicker label="Required" name="color_required" required value="#10B981" />';
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
                    <x-colorpicker label="With error" name="color_error" error errorMessage="Please select a color" value="#000000" />
                    <x-colorpicker label="Disabled" name="color_disabled" disabled value="#6B7280" />
                    <x-colorpicker label="Readonly" name="color_readonly" readonly value="#8B5CF6" />
                    <x-colorpicker label="Required" name="color_required" required value="#10B981" />
                </div>
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div class="space-y-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm p-6">
                <h2 class="text-2xl font-bold mb-4">Color Picker Component</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    A powerful color picker component with HSL color selection, preset colors, and hex input support. Built with Alpine.js for seamless interactivity.
                </p>

                <h3 class="text-xl font-semibold mb-3">Basic Props</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code> - string (required) - Input name attribute</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">label</code> - string - Label text above the input</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">value</code> - string - Default color value (hex format, e.g., '#3B82F6')</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">placeholder</code> - string - Placeholder text for the input</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">id</code> - string|null - Custom ID for the input (auto-generated if not provided)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">hint</code> - string - Helper text displayed below the input</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Display Options</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code> - string|null - Input size: 'sm' | 'lg' | null (default)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">showPresets</code> - bool - Show preset color palette (default: true)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">showInput</code> - bool - Show hex input field (default: true)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">presets</code> - array|null - Custom array of preset colors (hex format)</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Color Format</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">format</code> - string - Color format: 'hex' | 'rgb' | 'hsl' (default: 'hex')</li>
                    <li class="text-xs text-gray-600 dark:text-gray-400 mt-2">Note: Currently only hex format is fully supported. RGB and HSL formats are reserved for future implementation.</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">States</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">error</code> - bool - Show error state styling</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">errorMessage</code> - string - Error message to display</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code> - bool - Disable the color picker</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">readonly</code> - bool - Make the input readonly</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">required</code> - bool - Mark the field as required</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Features</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li>• HSL color picker with hue, saturation, and lightness controls</li>
                    <li>• Visual color swatch showing the selected color</li>
                    <li>• Hex input field with automatic '#' prefix</li>
                    <li>• Preset color palette for quick selection</li>
                    <li>• Custom preset colors support</li>
                    <li>• Real-time color preview</li>
                    <li>• Dark mode support</li>
                    <li>• Responsive design</li>
                    <li>• Accessible with proper ARIA labels</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Hex Input Behavior</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li>• Automatically adds '#' prefix if missing</li>
                    <li>• Accepts up to 6 hex characters (0-9, A-F)</li>
                    <li>• Filters out invalid characters automatically</li>
                    <li>• Converts input to uppercase</li>
                    <li>• Updates color picker when valid 6-character hex is entered</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Default Preset Colors</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    If no custom presets are provided, the following colors are used by default:
                </p>
                <div class="grid grid-cols-8 gap-2 mb-6">
                    <div class="w-8 h-8 rounded" style="background-color: #000000;" title="#000000"></div>
                    <div class="w-8 h-8 rounded" style="background-color: #FFFFFF; border: 1px solid #e5e7eb;" title="#FFFFFF"></div>
                    <div class="w-8 h-8 rounded" style="background-color: #FF0000;" title="#FF0000"></div>
                    <div class="w-8 h-8 rounded" style="background-color: #00FF00;" title="#00FF00"></div>
                    <div class="w-8 h-8 rounded" style="background-color: #0000FF;" title="#0000FF"></div>
                    <div class="w-8 h-8 rounded" style="background-color: #FFFF00;" title="#FFFF00"></div>
                    <div class="w-8 h-8 rounded" style="background-color: #FF00FF;" title="#FF00FF"></div>
                    <div class="w-8 h-8 rounded" style="background-color: #00FFFF;" title="#00FFFF"></div>
                    <div class="w-8 h-8 rounded" style="background-color: #FFA500;" title="#FFA500"></div>
                    <div class="w-8 h-8 rounded" style="background-color: #800080;" title="#800080"></div>
                    <div class="w-8 h-8 rounded" style="background-color: #FFC0CB;" title="#FFC0CB"></div>
                    <div class="w-8 h-8 rounded" style="background-color: #A52A2A;" title="#A52A2A"></div>
                    <div class="w-8 h-8 rounded" style="background-color: #808080;" title="#808080"></div>
                    <div class="w-8 h-8 rounded" style="background-color: #000080;" title="#000080"></div>
                    <div class="w-8 h-8 rounded" style="background-color: #008000;" title="#008000"></div>
                </div>

                <h3 class="text-xl font-semibold mb-3 mt-6">Common Use Cases</h3>
                <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                    <div>
                        <p class="font-medium mb-1">Basic Color Selection:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-colorpicker label="Theme Color" name="theme_color" value="#3B82F6" /&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">With Custom Presets:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-colorpicker 
    label="Brand Color" 
    name="brand_color" 
    :presets="['#FF0000', '#00FF00', '#0000FF']" 
    value="#FF0000" 
/&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Input Only (No Presets):</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-colorpicker 
    label="Custom Color" 
    name="custom_color" 
    :show-presets="false" 
    value="#8B5CF6" 
/&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Swatch Only (No Input):</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-colorpicker 
    label="Quick Color" 
    name="quick_color" 
    :show-input="false" 
    value="#10B981" 
/&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">With Validation:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-colorpicker 
    label="Required Color" 
    name="required_color" 
    required 
    error 
    errorMessage="Please select a color" 
    value="#000000" 
/&gt;</code></pre>
                    </div>
                </div>

                <h3 class="text-xl font-semibold mb-3 mt-6">Events</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    The component dispatches a <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">color-changed</code> event when the color is updated. You can listen to this event using Alpine.js:
                </p>
                <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto mb-6"><code>&lt;div x-data="{ selectedColor: '#3B82F6' }"&gt;
    &lt;x-colorpicker 
        name="color" 
        value="#3B82F6"
        x-on:color-changed="selectedColor = $event.detail"
    /&gt;
    &lt;p&gt;Selected: &lt;span x-text="selectedColor"&gt;&lt;/span&gt;&lt;/p&gt;
&lt;/div&gt;</code></pre>
            </div>
        </div>
    </div>

</x-layouts.admin>

