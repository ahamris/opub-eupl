<x-layouts.admin title="Modals - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Modal -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Basic Modal</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4">
                        <div x-data="{ showModal: false }">
                            <x-button @click="showModal = true">
                                Open Modal
                            </x-button>
                            <x-ui.modal alpine-show="showModal">
                                <x-slot:header>Modal Title</x-slot:header>
                                <x-slot:body>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        This is a basic modal with header, body, and footer slots.
                                    </p>
                                </x-slot:body>
                                <x-slot:footer>
                                    <x-button variant="secondary" @click="showModal = false">Cancel</x-button>
                                    <x-button variant="primary" @click="showModal = false">Save</x-button>
                                </x-slot:footer>
                            </x-ui.modal>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
                        <pre class="m-0 text-sm overflow-x-auto"><code class="language-xml">&lt;x-ui.modal alpine-show="showModal"&gt;
    &lt;x-slot:header&gt;Modal Title&lt;/x-slot:header&gt;
    &lt;x-slot:body&gt;Content&lt;/x-slot:body&gt;
    &lt;x-slot:footer&gt;Footer&lt;/x-slot:footer&gt;
&lt;/x-ui.modal&gt;</code></pre>
                    </div>
                </div>
            </div>

            <!-- Sizes -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Sizes</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4 space-y-3">
                        <div x-data="{ showSm: false }">
                            <x-button @click="showSm = true" size="sm">
                                Small (sm)
                            </x-button>
                            <x-ui.modal alpine-show="showSm" size="sm">
                                <x-slot:header>Small Modal</x-slot:header>
                                <x-slot:body>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">This is a small modal.</p>
                                </x-slot:body>
                            </x-ui.modal>
                        </div>

                        <div x-data="{ showDefault: false }">
                            <x-button @click="showDefault = true">
                                Default
                            </x-button>
                            <x-ui.modal alpine-show="showDefault" size="default">
                                <x-slot:header>Default Modal</x-slot:header>
                                <x-slot:body>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">This is the default size modal.</p>
                                </x-slot:body>
                            </x-ui.modal>
                        </div>

                        <div x-data="{ showLg: false }">
                            <x-button @click="showLg = true">
                                Large (lg)
                            </x-button>
                            <x-ui.modal alpine-show="showLg" size="lg">
                                <x-slot:header>Large Modal</x-slot:header>
                                <x-slot:body>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">This is a large modal.</p>
                                </x-slot:body>
                            </x-ui.modal>
                        </div>

                        <div x-data="{ showXl: false }">
                            <x-button @click="showXl = true">
                                Extra Large (xl)
                            </x-button>
                            <x-ui.modal alpine-show="showXl" size="xl">
                                <x-slot:header>Extra Large Modal</x-slot:header>
                                <x-slot:body>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">This is an extra large modal.</p>
                                </x-slot:body>
                            </x-ui.modal>
                        </div>

                        <div x-data="{ show2xl: false }">
                            <x-button @click="show2xl = true">
                                2XL
                            </x-button>
                            <x-ui.modal alpine-show="show2xl" size="2xl">
                                <x-slot:header>2XL Modal</x-slot:header>
                                <x-slot:body>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">This is a 2XL modal.</p>
                                </x-slot:body>
                            </x-ui.modal>
                        </div>

                        <div x-data="{ showFullscreen: false }">
                            <x-button @click="showFullscreen = true" variant="warning">
                                Fullscreen
                            </x-button>
                            <x-ui.modal alpine-show="showFullscreen" size="fullscreen">
                                <x-slot:header>Fullscreen Modal</x-slot:header>
                                <x-slot:body>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">This is a fullscreen modal.</p>
                                </x-slot:body>
                            </x-ui.modal>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Livewire Integration -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Livewire Integration</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            Use <code class="text-xs bg-gray-100 dark:bg-gray-900 px-1 py-0.5 rounded">wire-model</code> for Livewire integration:
                        </p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">// In Livewire component:
public $showModal = false;

// In Blade:
&lt;x-button wire:click="$set('showModal', true)"&gt;Open&lt;/x-button&gt;
&lt;x-ui.modal wire-model="showModal"&gt;
    &lt;x-slot:header&gt;Title&lt;/x-slot:header&gt;
    &lt;x-slot:body&gt;Content&lt;/x-slot:body&gt;
&lt;/x-ui.modal&gt;</code></pre>
                    </div>
                </div>
            </div>

            <!-- Without Header/Footer -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Without Header/Footer</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4">
                        <div x-data="{ showSimple: false }">
                            <x-button @click="showSimple = true">
                                Open Simple Modal
                            </x-button>
                            <x-ui.modal alpine-show="showSimple">
                                <div class="p-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        This modal uses the default slot without header or footer.
                                    </p>
                                </div>
                            </x-ui.modal>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
                        <pre class="m-0 text-sm overflow-x-auto"><code class="language-xml">&lt;x-ui.modal alpine-show="showSimple"&gt;
    &lt;div class="p-4"&gt;Content&lt;/div&gt;
&lt;/x-ui.modal&gt;</code></pre>
                    </div>
                </div>
            </div>

            <!-- Non-closeable -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Non-closeable Modal</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4">
                        <div x-data="{ showLocked: false }">
                            <x-button @click="showLocked = true">
                                Open Locked Modal
                            </x-button>
                            <x-ui.modal alpine-show="showLocked" :closeable="false" :close-on-backdrop-click="false" :close-on-escape="false">
                                <x-slot:header>Locked Modal</x-slot:header>
                                <x-slot:body>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        This modal cannot be closed by clicking backdrop, ESC key, or close button.
                                    </p>
                                </x-slot:body>
                                <x-slot:footer>
                                    <x-button variant="primary" @click="showLocked = false">Close via Button</x-button>
                                </x-slot:footer>
                            </x-ui.modal>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Backdrop Effects -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Backdrop Effects</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4 space-y-3">
                        <div x-data="{ showDefault: false }">
                            <x-button @click="showDefault = true" variant="secondary">
                                Default Modal (No Effects)
                            </x-button>
                            <x-ui.modal alpine-show="showDefault">
                                <x-slot:header>Default Modal</x-slot:header>
                                <x-slot:body>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        This is the default modal without blur or darker backdrop effects.
                                    </p>
                                </x-slot:body>
                            </x-ui.modal>
                        </div>

                        <div x-data="{ showBlur: false }">
                            <x-button @click="showBlur = true">
                                Modal with Blur
                            </x-button>
                            <x-ui.modal alpine-show="showBlur" blur>
                                <x-slot:header>Blur Effect</x-slot:header>
                                <x-slot:body>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        This modal has a blur effect on the backdrop.
                                    </p>
                                </x-slot:body>
                            </x-ui.modal>
                        </div>

                        <div x-data="{ showDark: false }">
                            <x-button @click="showDark = true">
                                Modal with Darker Backdrop
                            </x-button>
                            <x-ui.modal alpine-show="showDark" darker>
                                <x-slot:header>Darker Backdrop</x-slot:header>
                                <x-slot:body>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        This modal has a darker backdrop (darker).
                                    </p>
                                </x-slot:body>
                            </x-ui.modal>
                        </div>

                        <div x-data="{ showBoth: false }">
                            <x-button @click="showBoth = true" variant="warning">
                                Modal with Both Effects
                            </x-button>
                            <x-ui.modal alpine-show="showBoth" blur darker>
                                <x-slot:header>Blur + Darker Backdrop</x-slot:header>
                                <x-slot:body>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        This modal has both blur effect and darker backdrop.
                                    </p>
                                </x-slot:body>
                            </x-ui.modal>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div class="space-y-8">
            <div>
                <h3 class="text-lg font-semibold mb-3">Overview</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    Modal component provides a flexible dialog overlay for displaying content, forms, or confirmations.
                </p>
            </div>

            <!-- Sizes -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Sizes</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Available sizes:</p>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">sm</code> - max-w-md</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">default</code> - max-w-2xl</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">lg</code> - max-w-4xl</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">xl</code> - max-w-6xl</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">2xl</code> - max-w-7xl</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">fullscreen</code> - Full screen modal</li>
                </ul>
            </div>

            <!-- Slots -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Slots</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Available slots:</p>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">header</code> - Modal header (optional)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">body</code> - Modal body content (optional)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">footer</code> - Modal footer (optional)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">default</code> - Default slot (used when body is not provided)</li>
                </ul>
            </div>

            <!-- Props -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Props</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string (default: default)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Modal size: sm, default, lg, xl, 2xl, fullscreen</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">closeable</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool (default: true)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Show close button in header</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">closeOnBackdropClick</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool (default: true)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Close modal when clicking backdrop</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">closeOnEscape</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool (default: true)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Close modal when pressing ESC key</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">wire-model</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string|null</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Livewire property name for modal state</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">alpine-show</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string|null</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Alpine.js variable name for modal state</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">blur</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool (default: false)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Add backdrop blur effect to the modal overlay</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">darker</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool (default: false)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Make the backdrop darker (increased opacity)</p>
                    </div>
                </div>
            </div>

            <!-- Usage Examples -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Usage Examples</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Alpine.js (default):</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-button x-data="{ open: false }" @click="open = true"&gt;Open&lt;/x-button&gt;
&lt;x-ui.modal alpine-show="open"&gt;
    &lt;x-slot:header&gt;Title&lt;/x-slot:header&gt;
    &lt;x-slot:body&gt;Content&lt;/x-slot:body&gt;
&lt;/x-ui.modal&gt;</code></pre>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Livewire:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-button wire:click="$set('showModal', true)"&gt;Open&lt;/x-button&gt;
&lt;x-ui.modal wire-model="showModal"&gt;
    &lt;x-slot:header&gt;Title&lt;/x-slot:header&gt;
    &lt;x-slot:body&gt;Content&lt;/x-slot:body&gt;
&lt;/x-ui.modal&gt;</code></pre>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">With blur effect:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-ui.modal alpine-show="showModal" blur&gt;
    &lt;x-slot:header&gt;Title&lt;/x-slot:header&gt;
    &lt;x-slot:body&gt;Content&lt;/x-slot:body&gt;
&lt;/x-ui.modal&gt;</code></pre>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">With darker backdrop:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-ui.modal alpine-show="showModal" darker&gt;
    &lt;x-slot:header&gt;Title&lt;/x-slot:header&gt;
    &lt;x-slot:body&gt;Content&lt;/x-slot:body&gt;
&lt;/x-ui.modal&gt;</code></pre>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">With both blur and darker backdrop:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-ui.modal alpine-show="showModal" blur darker&gt;
    &lt;x-slot:header&gt;Title&lt;/x-slot:header&gt;
    &lt;x-slot:body&gt;Content&lt;/x-slot:body&gt;
&lt;/x-ui.modal&gt;</code></pre>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Features</h3>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                    <li>Alpine.js integration (default)</li>
                    <li>Livewire wire:model support</li>
                    <li>Smooth animations (fade + scale)</li>
                    <li>Backdrop blur effect</li>
                    <li>ESC key support</li>
                    <li>Click outside to close</li>
                    <li>Dark mode support</li>
                    <li>Accessibility (ARIA attributes)</li>
                    <li>Multiple size options</li>
                    <li>Flexible slot system</li>
                </ul>
            </div>
        </div>
    </div>

</x-layouts.admin>

@push('scripts')

@endpush

