<x-layouts.admin title="Quill Editor - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Quill -->
            <div>
                @php
                    $code1 = '<x-quill />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Quill</h3>
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
                    <x-quill />
                </div>
            </div>

            <!-- With Placeholder -->
            <div>
                @php
                    $code2 = '<x-quill placeholder="Start typing your content..." />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Placeholder</h3>
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
                    <x-quill placeholder="Start typing your content..." />
                </div>
            </div>

            <!-- Bubble Theme -->
            <div>
                @php
                    $code3 = '<x-quill theme="bubble" placeholder="Bubble theme" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Bubble Theme</h3>
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
                    <x-quill theme="bubble" placeholder="Bubble theme" />
                </div>
            </div>

            <!-- All Formats -->
            <div>
                @php
                    $allFormats = [
                        'background', 'bold', 'color', 'font', 'code', 'italic', 'link', 'size', 'strike', 'script', 'underline',
                        'blockquote', 'header', 'indent', 'list', 'align', 'direction', 'code-block',
                        'formula', 'image', 'video'
                    ];
                    $allToolbar = [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        ['link', 'image', 'video', 'formula'],
                        [['header' => 1], ['header' => 2], ['header' => 3], ['header' => 4], ['header' => 5], ['header' => 6]],
                        [['list' => 'ordered'], ['list' => 'bullet'], ['list' => 'check']],
                        [['script' => 'sub'], ['script' => 'super']],
                        [['indent' => '-1'], ['indent' => '+1']],
                        [['direction' => 'rtl']],
                        [['size' => ['small', false, 'large', 'huge']]],
                        [['header' => [1, 2, 3, 4, 5, 6, false]]],
                        [['color' => []], ['background' => []]],
                        [['font' => []]],
                        [['align' => []]],
                        ['clean']
                    ];
                    $code4 = '<x-quill 
    :formats="[\'background\', \'bold\', \'color\', \'font\', \'code\', \'italic\', \'link\', \'size\', \'strike\', \'script\', \'underline\', \'blockquote\', \'header\', \'indent\', \'list\', \'align\', \'direction\', \'code-block\', \'formula\', \'image\', \'video\']" 
    :toolbar="[[\'bold\', \'italic\', \'underline\', \'strike\'], [\'blockquote\', \'code-block\'], [\'link\', \'image\', \'video\', \'formula\'], [[\'header\' => 1], [\'header\' => 2], [\'header\' => 3], [\'header\' => 4], [\'header\' => 5], [\'header\' => 6]], [[\'list\' => \'ordered\'], [\'list\' => \'bullet\'], [\'list\' => \'check\']], [[\'script\' => \'sub\'], [\'script\' => \'super\']], [[\'indent\' => \'-1\'], [\'indent\' => \'+1\']], [[\'direction\' => \'rtl\']], [[\'size\' => [\'small\', false, \'large\', \'huge\']]], [[\'header\' => [1, 2, 3, 4, 5, 6, false]]], [[\'color\' => []], [\'background\' => []]], [[\'font\' => []]], [[\'align\' => []]], [\'clean\']]"
    placeholder="All formats enabled" 
/>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">All Formats</h3>
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
                    <x-quill :formats="$allFormats" :toolbar="$allToolbar" placeholder="All formats enabled" />
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
                    <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-quill /&gt;</code></pre>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Props</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">value</code> - string (initial HTML content)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">placeholder</code> - string</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">theme</code> - string (default: <code class="text-xs">'snow'</code>, alternative: <code class="text-xs">'bubble'</code>)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">height</code> - string (default: <code class="text-xs">'200px'</code>)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">formats</code> - array|null (format names to enable, null = all formats enabled)</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Livewire Integration</h4>
                    <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-quill wire:model="content" /&gt;</code></pre>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                        The component uses <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">$wire</code> and <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">.live</code> modifier for real-time synchronization with Livewire component properties.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Features</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li>Rich text formatting (bold, italic, underline, strikethrough)</li>
                        <li>Headings (H1-H6)</li>
                        <li>Lists (ordered, unordered)</li>
                        <li>Links and images</li>
                        <li>Text alignment</li>
                        <li>Code blocks</li>
                        <li>Blockquotes</li>
                        <li>Undo/Redo support</li>
                        <li>Dark mode support</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Examples</h4>
                    <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;!-- Basic --&gt;
&lt;x-quill /&gt;

&lt;!-- With placeholder --&gt;
&lt;x-quill placeholder="Start typing your content..." /&gt;

&lt;!-- With initial value --&gt;
&lt;x-quill value="&lt;p&gt;Initial content&lt;/p&gt;" /&gt;

&lt;!-- Custom height --&gt;
&lt;x-quill height="400px" /&gt;

&lt;!-- Bubble theme --&gt;
&lt;x-quill theme="bubble" /&gt;

&lt;!-- Livewire --&gt;
&lt;x-quill wire:model="content" /&gt;

&lt;!-- Custom formats --&gt;
&lt;x-quill :formats="['bold', 'italic', 'underline', 'link']" /&gt;</code></pre>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Available Formats</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        Inline formats:
                    </p>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside mb-3">
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">background</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">bold</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">color</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">font</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">code</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">italic</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">link</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">strike</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">script</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">underline</code></li>
                    </ul>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        Block formats:
                    </p>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside mb-3">
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">blockquote</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">header</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">indent</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">list</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">align</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">direction</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">code-block</code></li>
                    </ul>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        Embeds:
                    </p>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">formula</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">image</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">video</code></li>
                    </ul>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                        See <a href="https://quilljs.com/docs/formats" target="_blank" class="text-blue-600 dark:text-blue-400 underline">Quill Formats Documentation</a> for more details.
                    </p>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>

