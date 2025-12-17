<x-layouts.admin title="Toasts - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Variants -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Variants</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4 space-y-3">
                        <x-button onclick="toastSuccess('Item saved successfully!')">
                            Success Toast
                        </x-button>
                        <x-button onclick="toastError('An error occurred. Please try again.')" variant="error">
                            Error Toast
                        </x-button>
                        <x-button onclick="toastWarning('Please check your input.')" variant="warning">
                            Warning Toast
                        </x-button>
                        <x-button onclick="toastInfo('Here is some information.')" variant="sky">
                            Info Toast
                        </x-button>
                    </div>
                </div>
            </div>

            <!-- With Title -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">With Title</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4 space-y-3">
                        <x-button onclick="showToast('success', 'Your changes have been saved.', { title: 'Saved Successfully' })">
                            Success with Title
                        </x-button>
                        <x-button onclick="showToast('error', 'Unable to process your request.', { title: 'Error' })" variant="error">
                            Error with Title
                        </x-button>
                    </div>
                </div>
            </div>

            <!-- Positions -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Positions</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4 space-y-2">
                        <!-- Top Row -->
                        <div class="grid grid-cols-3 gap-2">
                            <x-button size="sm" onclick="showToast('info', 'Top Left', { position: 'top-left' })">
                                Top Left
                            </x-button>
                            <x-button size="sm" onclick="showToast('info', 'Top Center', { position: 'top-center' })">
                                Top Center
                            </x-button>
                            <x-button size="sm" onclick="showToast('info', 'Top Right', { position: 'top-right' })">
                                Top Right
                            </x-button>
                        </div>
                        <!-- Bottom Row -->
                        <div class="grid grid-cols-3 gap-2">
                            <x-button size="sm" onclick="showToast('info', 'Bottom Left', { position: 'bottom-left' })">
                                Bottom Left
                            </x-button>
                            <x-button size="sm" onclick="showToast('info', 'Bottom Center', { position: 'bottom-center' })">
                                Bottom Center
                            </x-button>
                            <x-button size="sm" onclick="showToast('info', 'Bottom Right', { position: 'bottom-right' })">
                                Bottom Right
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Duration Options -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Duration Options</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4 space-y-3">
                        <x-button onclick="showToast('info', 'This toast auto-dismisses in 2 seconds', { duration: 2000 })">
                            2 Seconds
                        </x-button>
                        <x-button onclick="showToast('info', 'This toast auto-dismisses in 5 seconds (default)', { duration: 5000 })">
                            5 Seconds (Default)
                        </x-button>
                        <x-button onclick="showToast('info', 'This toast will not auto-dismiss', { duration: 0 })">
                            No Auto-dismiss
                        </x-button>
                    </div>
                </div>
            </div>

            <!-- Custom Icon -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Custom Icon</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4 space-y-3">
                        <x-button onclick="showToast('success', 'File uploaded successfully', { icon: 'upload' })">
                            Custom Icon
                        </x-button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div class="space-y-8">
            <div>
                <h3 class="text-lg font-semibold mb-3">Overview</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    Toast notifications provide non-intrusive feedback messages that appear temporarily and automatically dismiss.
                </p>
            </div>

            <!-- Usage -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Usage</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Use JavaScript functions to show toasts:</p>
                <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto mb-3"><code class="language-javascript">// Simple usage
toastSuccess('Item saved successfully!');
toastError('An error occurred.');
toastWarning('Please check your input.');
toastInfo('Here is some information.');

// With options
showToast('success', 'Message here', {
    title: 'Title',
    duration: 5000,
    dismissible: true,
    position: 'top-right',
    icon: 'custom-icon'
});</code></pre>
            </div>

            <!-- Variants -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Variants</h3>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">success</code> - Green, for successful operations</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">error</code> - Red, for errors</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">warning</code> - Yellow, for warnings</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">info</code> - Blue/Sky, for informational messages</li>
                </ul>
            </div>

            <!-- Positions -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Positions</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Available positions:</p>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">top-right</code> - Default position</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">top-left</code></li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">top-center</code></li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">bottom-right</code></li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">bottom-left</code></li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">bottom-center</code></li>
                </ul>
            </div>

            <!-- Options -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Options</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">title</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string|null</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Optional title displayed above message</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">duration</code>
                        <span class="text-gray-600 dark:text-gray-400"> - number (default: 5000)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Auto-dismiss duration in milliseconds (0 = no auto-dismiss)</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">dismissible</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool (default: true)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Show dismiss button</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">position</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string (default: top-right)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Toast position</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">icon</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string|null</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Custom FontAwesome icon name (overrides default icon)</p>
                    </div>
                </div>
            </div>

            <!-- Functions -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Functions</h3>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">showToast(variant, message, options)</code> - General toast function</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">toastSuccess(message, options)</code> - Success toast shorthand</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">toastError(message, options)</code> - Error toast shorthand</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">toastWarning(message, options)</code> - Warning toast shorthand</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">toastInfo(message, options)</code> - Info toast shorthand</li>
                </ul>
            </div>

            <!-- Features -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Features</h3>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                    <li>Auto-dismiss with configurable duration</li>
                    <li>Manual dismiss button</li>
                    <li>Multiple position options</li>
                    <li>Stack multiple toasts</li>
                    <li>Smooth animations</li>
                    <li>Dark mode support</li>
                    <li>Accessibility (ARIA attributes)</li>
                    <li>Custom icons support</li>
                    <li>Title and message support</li>
                </ul>
            </div>
        </div>
    </div>

</x-layouts.admin>

@push('scripts')

@endpush

