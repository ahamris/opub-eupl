<x-layouts.admin title="Avatar - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Avatar -->
            @php
                $code1 = '<x-avatar name="John Doe" />
<x-avatar name="Jane Smith" />
<x-avatar name="Bob" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Avatar</h3>
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
                <div class="flex items-center gap-4">
                    <x-avatar name="John Doe" />
                    <x-avatar name="Jane Smith" />
                    <x-avatar name="Bob" />
                </div>
            </div>

            <!-- Avatar Sizes -->
            @php
                $code2 = '<x-avatar name="John Doe" size="sm" />
<x-avatar name="John Doe" size="md" />
<x-avatar name="John Doe" size="lg" />
<x-avatar name="John Doe" size="xl" />';
            @endphp
            <div>
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
                <div class="flex items-center gap-4">
                    <x-avatar name="John Doe" size="sm" />
                    <x-avatar name="John Doe" size="md" />
                    <x-avatar name="John Doe" size="lg" />
                    <x-avatar name="John Doe" size="xl" />
                </div>
            </div>

            <!-- Avatar with Image -->
            @php
                $code3 = '<x-avatar src="https://i.pravatar.cc/150?img=1" name="John Doe" />
<x-avatar src="https://i.pravatar.cc/150?img=2" name="Jane Smith" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Image</h3>
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
                <div class="flex items-center gap-4">
                    <x-avatar src="https://i.pravatar.cc/150?img=1" name="John Doe" />
                    <x-avatar src="https://i.pravatar.cc/150?img=2" name="Jane Smith" />
                </div>
            </div>

            <!-- Avatar Shapes -->
            @php
                $code4 = '<x-avatar name="John Doe" shape="circle" />
<x-avatar name="John Doe" shape="square" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Shapes</h3>
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
                <div class="flex items-center gap-4">
                    <x-avatar name="John Doe" shape="circle" />
                    <x-avatar name="John Doe" shape="square" />
                </div>
            </div>

            <!-- Avatar with Status -->
            @php
                $code5 = '<x-avatar name="John Doe" status="online" />
<x-avatar name="Jane Smith" status="offline" />
<x-avatar name="Bob" status="away" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Status</h3>
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
                <div class="flex items-center gap-4">
                    <x-avatar name="John Doe" status="online" />
                    <x-avatar name="Jane Smith" status="offline" />
                    <x-avatar name="Bob" status="away" />
                </div>
            </div>

            <!-- Avatar with Icon -->
            @php
                $code6 = '<x-avatar icon="user" />
<x-avatar icon="envelope" />
<x-avatar icon="bell" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Icon</h3>
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
                <div class="flex items-center gap-4">
                    <x-avatar icon="user" />
                    <x-avatar icon="envelope" />
                    <x-avatar icon="bell" />
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
                    <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-avatar name="John Doe" /&gt;</code></pre>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Props</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">src</code> - string|null (image URL)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">alt</code> - string|null (alt text for image)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code> - string|null (name for generating initials)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code> - string (default: <code class="text-xs">'md'</code>, alternatives: <code class="text-xs">'sm'</code>, <code class="text-xs">'lg'</code>, <code class="text-xs">'xl'</code>)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">shape</code> - string (default: <code class="text-xs">'circle'</code>, alternative: <code class="text-xs">'square'</code>)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">status</code> - string|null (default: <code class="text-xs">null</code>, alternatives: <code class="text-xs">'online'</code>, <code class="text-xs">'offline'</code>, <code class="text-xs">'away'</code>)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">icon</code> - string|null (fallback icon name, e.g., <code class="text-xs">'user'</code>, <code class="text-xs">'envelope'</code>)</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Examples</h4>
                    <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;!-- Basic with name --&gt;
&lt;x-avatar name="John Doe" /&gt;

&lt;!-- With image --&gt;
&lt;x-avatar src="https://example.com/avatar.jpg" name="John Doe" /&gt;

&lt;!-- Sizes --&gt;
&lt;x-avatar name="John Doe" size="sm" /&gt;
&lt;x-avatar name="John Doe" size="md" /&gt;
&lt;x-avatar name="John Doe" size="lg" /&gt;
&lt;x-avatar name="John Doe" size="xl" /&gt;

&lt;!-- Shapes --&gt;
&lt;x-avatar name="John Doe" shape="circle" /&gt;
&lt;x-avatar name="John Doe" shape="square" /&gt;

&lt;!-- With status --&gt;
&lt;x-avatar name="John Doe" status="online" /&gt;
&lt;x-avatar name="Jane Smith" status="offline" /&gt;
&lt;x-avatar name="Bob" status="away" /&gt;

&lt;!-- With icon --&gt;
&lt;x-avatar icon="user" /&gt;
&lt;x-avatar icon="envelope" /&gt;</code></pre>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Initials Generation</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        If a <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code> is provided, the component automatically generates initials:
                    </p>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside mt-2">
                        <li>Two words: "John Doe" → "JD"</li>
                        <li>Single word: "Bob" → "BO"</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Fallback Behavior</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        The component follows this priority for display:
                    </p>
                    <ol class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-decimal list-inside">
                        <li>Image (if <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">src</code> is provided and loads successfully)</li>
                        <li>Icon (if <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">icon</code> is provided)</li>
                        <li>Initials (if <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code> is provided)</li>
                        <li>Default user icon</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>

