<x-layouts.admin title="Buttons - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Tables -->
        <div class="space-y-8">
            <!-- Solid Variants -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Solid Variants</h3>
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">BUTTON</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">CODE</th>
                                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider w-24">COPY</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <x-button variant="primary">Primary</x-button>
                                        </td>
                                        <td class="px-4 py-2">
                                            <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="primary"&gt;Primary&lt;/x-button&gt;</code></pre>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <button 
                                                type="button"
                                                class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                                data-code="&lt;x-button variant=&quot;primary&quot;&gt;Primary&lt;/x-button&gt;"
                                                x-data="{ copied: false }"
                                                x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                            >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <x-button variant="secondary">Secondary</x-button>
                                        </td>
                                        <td class="px-4 py-2">
                                            <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="secondary"&gt;Secondary&lt;/x-button&gt;</code></pre>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <button 
                                                type="button"
                                                class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                                data-code="&lt;x-button variant=&quot;secondary&quot;&gt;Secondary&lt;/x-button&gt;"
                                                x-data="{ copied: false }"
                                                x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                            >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <x-button variant="success">Success</x-button>
                                        </td>
                                        <td class="px-4 py-2">
                                            <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="success"&gt;Success&lt;/x-button&gt;</code></pre>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <button 
                                                type="button"
                                                class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                                data-code="&lt;x-button variant=&quot;success&quot;&gt;Success&lt;/x-button&gt;"
                                                x-data="{ copied: false }"
                                                x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                            >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <x-button variant="warning">Warning</x-button>
                                        </td>
                                        <td class="px-4 py-2">
                                            <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="warning"&gt;Warning&lt;/x-button&gt;</code></pre>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <button 
                                                type="button"
                                                class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                                data-code="&lt;x-button variant=&quot;warning&quot;&gt;Warning&lt;/x-button&gt;"
                                                x-data="{ copied: false }"
                                                x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                            >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <x-button variant="error">Error</x-button>
                                        </td>
                                        <td class="px-4 py-2">
                                            <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="error"&gt;Error&lt;/x-button&gt;</code></pre>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <button 
                                                type="button"
                                                class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                                data-code="&lt;x-button variant=&quot;error&quot;&gt;Error&lt;/x-button&gt;"
                                                x-data="{ copied: false }"
                                                x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                            >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <x-button variant="sky">Sky</x-button>
                                        </td>
                                        <td class="px-4 py-2">
                                            <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="sky"&gt;Sky&lt;/x-button&gt;</code></pre>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <button 
                                                type="button"
                                                class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                                data-code="&lt;x-button variant=&quot;sky&quot;&gt;Sky&lt;/x-button&gt;"
                                                x-data="{ copied: false }"
                                                x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                            >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>

            <!-- Outline Variants -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Outline Variants</h3>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">BUTTON</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">CODE</th>
                                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider w-24">COPY</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="outline-primary">Outline Primary</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="outline-primary"&gt;Outline Primary&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;outline-primary&quot;&gt;Outline Primary&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="outline-secondary">Outline Secondary</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="outline-secondary"&gt;Outline Secondary&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;outline-secondary&quot;&gt;Outline Secondary&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="outline-success">Outline Success</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="outline-success"&gt;Outline Success&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;outline-success&quot;&gt;Outline Success&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="outline-warning">Outline Warning</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="outline-warning"&gt;Outline Warning&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;outline-warning&quot;&gt;Outline Warning&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="outline-error">Outline Error</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="outline-error"&gt;Outline Error&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;outline-error&quot;&gt;Outline Error&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="outline-sky">Outline Sky</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="outline-sky"&gt;Outline Sky&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;outline-sky&quot;&gt;Outline Sky&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sizes -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Sizes</h3>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">BUTTON</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">CODE</th>
                                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider w-24">COPY</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button size="sm" variant="primary">Small</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button size="sm" variant="primary"&gt;Small&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button size=&quot;sm&quot; variant=&quot;primary&quot;&gt;Small&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="primary">Default</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="primary"&gt;Default&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;primary&quot;&gt;Default&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button size="lg" variant="primary">Large</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button size="lg" variant="primary"&gt;Large&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button size=&quot;lg&quot; variant=&quot;primary&quot;&gt;Large&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- States -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">States</h3>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">BUTTON</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">CODE</th>
                                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider w-24">COPY</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="primary">Normal</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="primary"&gt;Normal&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;primary&quot;&gt;Normal&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="primary" disabled>Disabled</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="primary" disabled&gt;Disabled&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;primary&quot; disabled&gt;Disabled&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="primary" loading>Loading</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="primary" loading&gt;Loading&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;primary&quot; loading&gt;Loading&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Icons - Left -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Icons - Left</h3>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">BUTTON</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">CODE</th>
                                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider w-24">COPY</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="primary" icon="save">Save</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="primary" icon="save"&gt;Save&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;primary&quot; icon=&quot;save&quot;&gt;Save&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="error" icon="trash">Delete</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="error" icon="trash"&gt;Delete&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;error&quot; icon=&quot;trash&quot;&gt;Delete&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="success" icon="check">Confirm</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="success" icon="check"&gt;Confirm&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;success&quot; icon=&quot;check&quot;&gt;Confirm&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Icons - Right -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Icons - Right</h3>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">BUTTON</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">CODE</th>
                                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider w-24">COPY</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="primary" icon="arrow-right" iconPosition="right">Next</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="primary" icon="arrow-right" iconPosition="right"&gt;Next&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;primary&quot; icon=&quot;arrow-right&quot; iconPosition=&quot;right&quot;&gt;Next&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="success" icon="download" iconPosition="right">Download</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="success" icon="download" iconPosition="right"&gt;Download&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;success&quot; icon=&quot;download&quot; iconPosition=&quot;right&quot;&gt;Download&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button variant="sky" icon="external-link" iconPosition="right">Open</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button variant="sky" icon="external-link" iconPosition="right"&gt;Open&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button variant=&quot;sky&quot; icon=&quot;external-link&quot; iconPosition=&quot;right&quot;&gt;Open&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Link Buttons -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Link Buttons</h3>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">BUTTON</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">CODE</th>
                                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider w-24">COPY</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button href="/dashboard" variant="primary">Go to Dashboard</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button href="/dashboard" variant="primary"&gt;Go to Dashboard&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button href=&quot;/dashboard&quot; variant=&quot;primary&quot;&gt;Go to Dashboard&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button href="https://example.com" target="_blank" variant="primary">External Link</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button href="https://example.com" target="_blank" variant="primary"&gt;External Link&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button href=&quot;https://example.com&quot; target=&quot;_blank&quot; variant=&quot;primary&quot;&gt;External Link&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button href="/profile" variant="primary" icon="user">Profile</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button href="/profile" variant="primary" icon="user"&gt;Profile&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button href=&quot;/profile&quot; variant=&quot;primary&quot; icon=&quot;user&quot;&gt;Profile&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <x-button href="#" variant="primary" disabled>Disabled Link</x-button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <pre class="m-0 text-sm"><code class="language-xml">&lt;x-button href="#" variant="primary" disabled&gt;Disabled Link&lt;/x-button&gt;</code></pre>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button 
                                            type="button"
                                            class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                            data-code="&lt;x-button href=&quot;#&quot; variant=&quot;primary&quot; disabled&gt;Disabled Link&lt;/x-button&gt;"
                                            x-data="{ copied: false }"
                                            x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                                        >
                                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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


                    <!-- Basic Usage -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Basic Usage</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">The Button component can be used as both a button and a link. When `href` is provided, it renders as an anchor tag.</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-button variant="primary"&gt;Click Me&lt;/x-button&gt;</code></pre>
                    </div>

                    <!-- Variants -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Variants</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Available variants:</p>
                        <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">primary</code></li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">secondary</code></li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">success</code></li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">warning</code></li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">error</code></li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">sky</code></li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">outline-*</code> (outline-primary, outline-secondary, etc.)</li>
                        </ul>
                    </div>

                    <!-- Sizes -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Sizes</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Available sizes:</p>
                        <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">sm</code> - Small button</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">default</code> - Default size (no size prop)</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">lg</code> - Large button</li>
                        </ul>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto mt-3"><code class="language-xml">&lt;x-button size="sm" variant="primary"&gt;Small&lt;/x-button&gt;</code></pre>
                    </div>

                    <!-- Icons -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Icons</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Add FontAwesome icons to buttons:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto mb-3"><code class="language-xml">&lt;x-button variant="primary" icon="save"&gt;Save&lt;/x-button&gt;</code></pre>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Icon position:</p>
                        <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">iconPosition="left"</code> - Default, icon on the left</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">iconPosition="right"</code> - Icon on the right</li>
                        </ul>
                    </div>

                    <!-- States -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3">States</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Button states:</p>
                        <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code> - Disables the button</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">loading</code> - Shows loading spinner and disables the button</li>
                        </ul>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto mt-3"><code class="language-xml">&lt;x-button variant="primary" loading&gt;Loading...&lt;/x-button&gt;</code></pre>
                    </div>

                    <!-- Link Support -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Link Support</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">When `href` is provided, the button renders as an anchor tag:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto mb-3"><code class="language-xml">&lt;x-button href="/dashboard" variant="primary"&gt;Go to Dashboard&lt;/x-button&gt;</code></pre>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">External links:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-button href="https://example.com" target="_blank" variant="primary"&gt;External Link&lt;/x-button&gt;</code></pre>
                    </div>

                    <!-- Button Types -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Button Types</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">When used as a button (not a link), specify the type:</p>
                        <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">button</code> - Default</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">submit</code> - For form submission</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">reset</code> - For form reset</li>
                        </ul>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto mt-3"><code class="language-xml">&lt;x-button type="submit" variant="primary"&gt;Submit&lt;/x-button&gt;</code></pre>
                    </div>

                    <!-- Props -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3">All Props</h3>
                        <div class="space-y-2 text-sm">
                            <div>
                                <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code>
                                <span class="text-gray-600 dark:text-gray-400"> - string|null</span>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Button size: <code class="text-xs">sm</code>, <code class="text-xs">lg</code>, or <code class="text-xs">null</code> for default</p>
                            </div>
                            <div>
                                <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">variant</code>
                                <span class="text-gray-600 dark:text-gray-400"> - string (default: secondary)</span>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Button variant: primary, secondary, success, warning, error, sky, or outline-* variants</p>
                            </div>
                            <div>
                                <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code>
                                <span class="text-gray-600 dark:text-gray-400"> - bool (default: false)</span>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Whether the button is disabled</p>
                            </div>
                            <div>
                                <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">type</code>
                                <span class="text-gray-600 dark:text-gray-400"> - string (default: button)</span>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Button type: button, submit, or reset</p>
                            </div>
                            <div>
                                <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">icon</code>
                                <span class="text-gray-600 dark:text-gray-400"> - string|null</span>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">FontAwesome icon name (without fa- prefix)</p>
                            </div>
                            <div>
                                <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">iconPosition</code>
                                <span class="text-gray-600 dark:text-gray-400"> - string (default: left)</span>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Icon position: left or right</p>
                            </div>
                            <div>
                                <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">loading</code>
                                <span class="text-gray-600 dark:text-gray-400"> - bool (default: false)</span>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Show loading spinner</p>
                            </div>
                            <div>
                                <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">href</code>
                                <span class="text-gray-600 dark:text-gray-400"> - string|null</span>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">If provided, renders as a link instead of button</p>
                            </div>
                            <div>
                                <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">target</code>
                                <span class="text-gray-600 dark:text-gray-400"> - string|null</span>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Link target (e.g., _blank)</p>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

</x-layouts.admin>

@push('scripts')

@endpush
