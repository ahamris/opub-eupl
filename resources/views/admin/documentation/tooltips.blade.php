<x-layouts.admin title="Tooltips - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Tooltip -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Basic Tooltip</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm">
                    <div class="p-4">
                        <x-ui.tooltip text="This is a tooltip" position="top">
                            <x-button>Hover me</x-button>
                        </x-ui.tooltip>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
                        <pre class="m-0 text-sm overflow-x-auto"><code class="language-xml">&lt;x-ui.tooltip text="This is a tooltip" position="top"&gt;
    &lt;x-button&gt;Hover me&lt;/x-button&gt;
&lt;/x-ui.tooltip&gt;</code></pre>
                    </div>
                </div>
            </div>

            <!-- Positions -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Positions</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm">
                    <div class="p-4 flex flex-wrap gap-3">
                        <x-ui.tooltip text="Top tooltip" position="top">
                            <x-button size="sm">Top</x-button>
                        </x-ui.tooltip>
                        <x-ui.tooltip text="Bottom tooltip" position="bottom">
                            <x-button size="sm">Bottom</x-button>
                        </x-ui.tooltip>
                        <x-ui.tooltip text="Left tooltip" position="left">
                            <x-button size="sm">Left</x-button>
                        </x-ui.tooltip>
                        <x-ui.tooltip text="Right tooltip" position="right">
                            <x-button size="sm">Right</x-button>
                        </x-ui.tooltip>
                    </div>
                </div>
            </div>

            <!-- Triggers -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Triggers</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm">
                    <div class="p-4 space-y-3">
                        <x-ui.tooltip text="Hover to show (default)" trigger="hover">
                            <x-button>Hover Trigger</x-button>
                        </x-ui.tooltip>
                        <x-ui.tooltip text="Click to show" trigger="click">
                            <x-button>Click Trigger</x-button>
                        </x-ui.tooltip>
                    </div>
                </div>
            </div>

            <!-- With Icon -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">With Icon</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm">
                    <div class="p-4 flex items-center justify-center gap-4 flex-wrap">
                        <x-ui.tooltip text="Diamond icon tooltip" position="top">
                            <i class="fa-solid fa-gem text-lg text-zinc-600 dark:text-zinc-400"></i>
                        </x-ui.tooltip>
                        <x-ui.tooltip text="Heart icon tooltip" position="top">
                            <i class="fa-solid fa-heart text-lg text-zinc-600 dark:text-zinc-400"></i>
                        </x-ui.tooltip>
                        <x-ui.tooltip text="Star icon tooltip" position="top">
                            <i class="fa-solid fa-star text-lg text-zinc-600 dark:text-zinc-400"></i>
                        </x-ui.tooltip>
                        <x-ui.tooltip text="Bell icon tooltip" position="top">
                            <i class="fa-solid fa-bell text-lg text-zinc-600 dark:text-zinc-400"></i>
                        </x-ui.tooltip>
                        <x-ui.tooltip text="Settings icon tooltip" position="top">
                            <i class="fa-solid fa-cog text-lg text-zinc-600 dark:text-zinc-400"></i>
                        </x-ui.tooltip>
                    </div>
                </div>
            </div>

            <!-- Long Text -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Long Text</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm">
                    <div class="p-4">
                        <x-ui.tooltip text="This is a longer tooltip text that wraps to multiple lines when needed." max-width="200px" position="top">
                            <x-button>Long Tooltip</x-button>
                        </x-ui.tooltip>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div class="space-y-8">
            <div>
                <h3 class="text-lg font-semibold mb-3">Overview</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    Tooltip component provides contextual information on hover or click.
                </p>
            </div>

            <!-- Usage -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Usage</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Wrap any element with tooltip:</p>
                <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto mb-3"><code class="language-xml">&lt;x-ui.tooltip text="Tooltip text" position="top"&gt;
    &lt;x-button&gt;Hover me&lt;/x-button&gt;
&lt;/x-ui.tooltip&gt;</code></pre>
            </div>

            <!-- Positions -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Positions</h3>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">top</code> - Default, tooltip appears above</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">bottom</code> - Tooltip appears below</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">left</code> - Tooltip appears on the left</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">right</code> - Tooltip appears on the right</li>
                </ul>
            </div>

            <!-- Triggers -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Triggers</h3>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">hover</code> - Default, show on mouse hover</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">click</code> - Show on click, hide on outside click</li>
                </ul>
            </div>

            <!-- Props -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Props</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">text</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string|null</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Tooltip text content</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">position</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string (default: top)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Tooltip position: top, bottom, left, right</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">trigger</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string (default: hover)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Trigger event: hover or click</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">delay</code>
                        <span class="text-gray-600 dark:text-gray-400"> - int (default: 200)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Show delay in milliseconds</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">maxWidth</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string|null</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Maximum width for tooltip (e.g., '200px')</p>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Features</h3>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                    <li>Hover and click triggers</li>
                    <li>Multiple position options</li>
                    <li>Smooth animations</li>
                    <li>Arrow indicator</li>
                    <li>Dark mode support</li>
                    <li>Configurable delay</li>
                    <li>Long text support with max-width</li>
                    <li>Accessibility (ARIA attributes)</li>
                </ul>
            </div>
        </div>
    </div>

</x-layouts.admin>

@push('scripts')

@endpush

