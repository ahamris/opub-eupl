<x-layouts.admin title="Inputs - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Inputs -->
            <div>
                @php
                    $code1 = '<x-input label="Name" name="name" placeholder="Your name" />
<x-input label="Email" type="email" name="email" placeholder="you@example.com" />
<x-input label="Password" type="password" name="password" placeholder="••••••••" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Inputs</h3>
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
                    <x-input label="Name" name="name" placeholder="Your name" />
                    <x-input label="Email" type="email" name="email" placeholder="you@example.com" />
                    <x-input label="Password" type="password" name="password" placeholder="••••••••" />
                </div>
            </div>

            <!-- Sizes -->
            <div>
                @php
                    $code2 = '<x-input label="Small" size="sm" name="small" placeholder="Small input" />
<x-input label="Default" name="default" placeholder="Default input" />
<x-input label="Large" size="lg" name="large" placeholder="Large input" />';
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
                    <x-input label="Small" size="sm" name="small" placeholder="Small input" />
                    <x-input label="Default" name="default" placeholder="Default input" />
                    <x-input label="Large" size="lg" name="large" placeholder="Large input" />
                </div>
            </div>

            <!-- States -->
            <div>
                @php
                    $code3 = '<x-input label="With error" name="err" error errorMessage="This field is required" placeholder="Error state" />
<x-input label="Disabled" name="dis" disabled placeholder="Disabled input" />
<x-input label="Readonly" name="ro" readonly value="Readonly value" />
<x-input label="Required" name="req" required placeholder="Required input" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">States</h3>
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
                    <x-input label="With error" name="err" error errorMessage="This field is required" placeholder="Error state" />
                    <x-input label="Disabled" name="dis" disabled placeholder="Disabled input" />
                    <x-input label="Readonly" name="ro" readonly value="Readonly value" />
                    <x-input label="Required" name="req" required placeholder="Required input" />
                </div>
            </div>

            <!-- Icons -->
            <div>
                @php
                    $code4 = '<x-input label="With Icon (left)" name="iconl" icon="user" placeholder="Username" />
<x-input label="With Icon (right)" name="iconr" icon="envelope" iconPosition="right" placeholder="Email" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Icons</h3>
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
                    <x-input label="With Icon (left)" name="iconl" icon="user" placeholder="Username" />
                    <x-input label="With Icon (right)" name="iconr" icon="envelope" iconPosition="right" placeholder="Email" />
                </div>
            </div>

            <!-- Types -->
            <div>
                @php
                    $code5 = '<x-input label="Number" type="number" name="num" placeholder="0" />
<x-input label="Search" type="search" name="search" placeholder="Search..." />
<x-input label="URL" type="url" name="url" placeholder="https://" />
<x-input label="Phone" type="tel" name="tel" placeholder="(555) 555-5555" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Types</h3>
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
                    <x-input label="Number" type="number" name="num" placeholder="0" />
                    <x-input label="Search" type="search" name="search" placeholder="Search..." />
                    <x-input label="URL" type="url" name="url" placeholder="https://" />
                    <x-input label="Phone" type="tel" name="tel" placeholder="(555) 555-5555" />
                </div>
            </div>

            <!-- Labels & Hints -->
            <div>
                @php
                    $code6 = '<x-input name="nolabel" placeholder="No label" />
<x-input label="With hint" name="hint" hint="Helpful hint text" placeholder="Hover or read hint" />
<x-input label="Custom id" id="custom-id" name="cid" placeholder="With custom id" />
<x-input label="With value" name="val" value="Preset value" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Labels & Hints</h3>
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
                <div class="space-y-4">
                    <x-input name="nolabel" placeholder="No label" />
                    <x-input label="With hint" name="hint" hint="Helpful hint text" placeholder="Hover or read hint" />
                    <x-input label="Custom id" id="custom-id" name="cid" placeholder="With custom id" />
                    <x-input label="With value" name="val" value="Preset value" />
                </div>
            </div>

            <!-- Textarea -->
            <div>
                @php
                    $code7 = '<x-textarea label="Message" name="msg" placeholder="Your message" />
<x-textarea label="Small" size="sm" name="tsm" placeholder="Small textarea" />
<x-textarea label="Large" size="lg" name="tlg" placeholder="Large textarea" />
<x-textarea label="With rows" name="rows" rows="5" placeholder="5 rows" />
<x-textarea label="With error" name="terr" error errorMessage="This field is required" placeholder="Error state" />
<x-textarea label="Disabled" name="tdis" disabled placeholder="Disabled textarea" />
<x-textarea label="Readonly" name="tro" readonly value="Readonly value" />
<x-textarea label="With value" name="tval" value="Preset text content" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Textarea</h3>
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
                    <x-textarea label="Message" name="msg" placeholder="Your message" />
                    <x-textarea label="Small" size="sm" name="tsm" placeholder="Small textarea" />
                    <x-textarea label="Large" size="lg" name="tlg" placeholder="Large textarea" />
                    <x-textarea label="With rows" name="rows" rows="5" placeholder="5 rows" />
                    <x-textarea label="With error" name="terr" error errorMessage="This field is required" placeholder="Error state" />
                    <x-textarea label="Disabled" name="tdis" disabled placeholder="Disabled textarea" />
                    <x-textarea label="Readonly" name="tro" readonly value="Readonly value" />
                    <x-textarea label="With value" name="tval" value="Preset text content" />
                </div>
            </div>

            <!-- Color Picker -->
            <div>
                @php
                    $code8 = '<x-colorpicker label="Color" name="color" value="#3B82F6" />
<x-colorpicker label="With hint" name="color2" value="#6366F1" hint="Select a color for your theme" />
<x-colorpicker label="Small" size="sm" name="color3" value="#10B981" />
<x-colorpicker label="Large" size="lg" name="color4" value="#F59E0B" />
<x-colorpicker label="Without presets" name="color5" :show-presets="false" value="#EF4444" />
<x-colorpicker label="Without input" name="color6" :show-input="false" value="#8B5CF6" />
<x-colorpicker label="With error" name="color7" error errorMessage="Please select a color" value="#000000" />
<x-colorpicker label="Disabled" name="color8" disabled value="#6B7280" />
<x-colorpicker label="Custom presets" name="color9" :presets="[\'#FF0000\', \'#00FF00\', \'#0000FF\']" value="#FF0000" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Color Picker</h3>
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
                <div class="space-y-4">
                    <x-colorpicker label="Color" name="color" value="#3B82F6" />
                    <x-colorpicker label="With hint" name="color2" value="#6366F1" hint="Select a color for your theme" />
                    <x-colorpicker label="Small" size="sm" name="color3" value="#10B981" />
                    <x-colorpicker label="Large" size="lg" name="color4" value="#F59E0B" />
                    <x-colorpicker label="Without presets" name="color5" :show-presets="false" value="#EF4444" />
                    <x-colorpicker label="Without input" name="color6" :show-input="false" value="#8B5CF6" />
                    <x-colorpicker label="With error" name="color7" error errorMessage="Please select a color" value="#000000" />
                    <x-colorpicker label="Disabled" name="color8" disabled value="#6B7280" />
                    <x-colorpicker label="Custom presets" name="color9" :presets="['#FF0000', '#00FF00', '#0000FF']" value="#FF0000" />
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
<pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-input label="Name" name="name" placeholder="Your name" /&gt;</code></pre>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Props</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">label</code> - string</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">type</code> - string (text, email, password, ...)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code> - string</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">id</code> - string|null (defaults to name)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">value</code> - string</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">placeholder</code> - string</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code> - sm | lg | null</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">error</code> - bool + <code class="text-xs">errorMessage</code></li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">required</code> - bool</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code> - bool</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">readonly</code> - bool</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">icon</code> - string|null (FontAwesome)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">iconPosition</code> - left | right</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Sizes</h4>
<pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-input size="sm" ... /&gt;
&lt;x-input ... /&gt;
&lt;x-input size="lg" ... /&gt;</code></pre>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">States</h4>
<pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-input error errorMessage="Message" /&gt;
&lt;x-input disabled /&gt;
&lt;x-input readonly /&gt;
&lt;x-input required /&gt;</code></pre>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Icons</h4>
<pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-input icon="user" /&gt;
&lt;x-input icon="envelope" iconPosition="right" /&gt;</code></pre>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Textarea</h4>
<pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-textarea label="Message" name="msg" placeholder="Your message" /&gt;</code></pre>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-3 mb-3">Textarea Props:</p>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">label</code> - string</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code> - string</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">id</code> - string|null</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">value</code> - string</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">placeholder</code> - string</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code> - sm | lg | null</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">rows</code> - int|null</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">cols</code> - int|null</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">error</code> - bool + <code class="text-xs">errorMessage</code></li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">required</code> - bool</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code> - bool</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">readonly</code> - bool</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">hint</code> - string</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Color Picker</h4>
<pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-colorpicker label="Color" name="color" value="#3B82F6" /&gt;</code></pre>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-3 mb-3">Color Picker Props:</p>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">label</code> - string</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code> - string</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">id</code> - string|null</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">value</code> - string (hex color, default: #000000)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">placeholder</code> - string</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code> - sm | lg | null</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">showPresets</code> - bool (default: true)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">showInput</code> - bool (default: true)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">presets</code> - array|null (custom preset colors)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">format</code> - hex | rgb | hsl (default: hex)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">error</code> - bool + <code class="text-xs">errorMessage</code></li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">required</code> - bool</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code> - bool</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">readonly</code> - bool</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">hint</code> - string</li>
                    </ul>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">Features:</p>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li>HSL color picker with hue, saturation, and lightness controls</li>
                        <li>15 preset colors (customizable)</li>
                        <li>Hex color format support</li>
                        <li>Dark mode support</li>
                        <li>Interactive Alpine.js integration</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>
