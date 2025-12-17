<x-layouts.admin title="Form Wizard - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Form Wizard -->
            <div>
                @php
                    $code1 = '<livewire:admin.form-wizard :steps="$steps">
    {{-- Step content will be rendered based on currentStep --}}
    {{-- Use renderStepContent() method in your component --}}
</livewire:admin.form-wizard>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Form Wizard</h3>
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
                <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <livewire:admin.form-wizard-example />
                </div>
            </div>

            <!-- Steps Configuration -->
            <div>
                @php
                    $code2 = '$steps = [
    [
        \'title\' => \'Personal Information\',
        \'description\' => \'Enter your personal details\',
        \'rules\' => [
            \'name\' => \'required|string|max:255\',
            \'email\' => \'required|email\',
        ],
        \'messages\' => [
            \'name.required\' => \'Name is required\',
        ],
    ],
    [
        \'title\' => \'Address\',
        \'rules\' => [
            \'address\' => \'required|string\',
        ],
    ],
];';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Steps Configuration</h3>
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
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <pre class="text-xs overflow-x-auto"><code class="language-php">{{ $code2 }}</code></pre>
                </div>
            </div>

            <!-- Event Handling -->
            <div>
                @php
                    $code3 = '@script
<script>
    Livewire.on(\'form-wizard-submitted\', () => {
        // Handle form submission
        console.log(\'Form submitted!\');
    });
</script>
@endscript';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Event Handling</h3>
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
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <pre class="text-xs overflow-x-auto"><code class="language-html">{{ $code3 }}</code></pre>
                </div>
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div class="space-y-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Form Wizard Component</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 m-0">Multi-step form wizard component built with Livewire for complex form workflows.</p>
                </div>
                <div class="card-body space-y-6">
                    <!-- Basic Usage -->
                    <section>
                        <h4 class="text-lg font-semibold mb-3">Basic Usage</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">The Form Wizard component is a Livewire component that helps you create multi-step forms with validation, progress tracking, and step navigation.</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-html">&lt;livewire:admin.form-wizard :steps="$steps"&gt;
    {{-- Step content --}}
&lt;/livewire:admin.form-wizard&gt;</code></pre>
                    </section>

                    <!-- Steps Configuration -->
                    <section>
                        <h4 class="text-lg font-semibold mb-3">Steps Configuration</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Each step in the wizard is configured with:</p>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2 list-disc list-inside mb-3">
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">title</code> - Step title displayed in navigation</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">description</code> - Optional step description</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">rules</code> - Laravel validation rules for the step</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">messages</code> - Custom validation error messages</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">icon</code> - Optional FontAwesome icon name</li>
                        </ul>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-php">$steps = [
    [
        'title' => 'Step 1',
        'description' => 'Step description',
        'rules' => [
            'field' => 'required|string',
        ],
        'messages' => [
            'field.required' => 'Field is required',
        ],
    ],
];</code></pre>
                    </section>

                    <!-- Component Properties -->
                    <section>
                        <h4 class="text-lg font-semibold mb-3">Component Properties</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">The FormWizard Livewire component exposes these properties:</p>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">$currentStep</code> - Current active step (1-based)</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">$totalSteps</code> - Total number of steps</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">$steps</code> - Array of step configurations</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">$errors</code> - Validation errors array</li>
                        </ul>
                    </section>

                    <!-- Methods -->
                    <section>
                        <h4 class="text-lg font-semibold mb-3">Available Methods</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">next()</code> - Move to next step (validates current step first)</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">previous()</code> - Move to previous step</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">goToStep($step)</code> - Jump to specific step</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">submit()</code> - Submit the form (validates all steps)</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">getProgressPercentage()</code> - Get completion percentage</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">isStepCompleted($step)</code> - Check if step is completed</li>
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">isStepAccessible($step)</code> - Check if step can be accessed</li>
                        </ul>
                    </section>

                    <!-- Events -->
                    <section>
                        <h4 class="text-lg font-semibold mb-3">Events</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">The component dispatches the following events:</p>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                            <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">form-wizard-submitted</code> - Dispatched when all steps are validated and form is submitted</li>
                        </ul>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto mt-3"><code class="language-javascript">Livewire.on('form-wizard-submitted', () => {
    // Handle form submission
});</code></pre>
                    </section>

                    <!-- Features -->
                    <section>
                        <h4 class="text-lg font-semibold mb-3">Features</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2 list-disc list-inside">
                            <li><strong>Multi-step navigation</strong> - Navigate between steps with validation</li>
                            <li><strong>Progress tracking</strong> - Visual progress bar and percentage</li>
                            <li><strong>Step validation</strong> - Each step validates before proceeding</li>
                            <li><strong>Step completion tracking</strong> - Visual indicators for completed steps</li>
                            <li><strong>Accessible navigation</strong> - Only allow navigation to completed or next step</li>
                            <li><strong>Error display</strong> - Shows validation errors for current step</li>
                            <li><strong>Responsive design</strong> - Works on all screen sizes</li>
                            <li><strong>Theme integration</strong> - Uses CSS variables for accent colors</li>
                        </ul>
                    </section>

                    <!-- Usage Example -->
                    <section>
                        <h4 class="text-lg font-semibold mb-3">Complete Example</h4>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-php">// In your Livewire component
public array $steps = [];

public function mount(): void
{
    $this->steps = [
        [
            'title' => 'Personal Info',
            'rules' => ['name' => 'required'],
        ],
        [
            'title' => 'Address',
            'rules' => ['address' => 'required'],
        ],
    ];
}</code></pre>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto mt-3"><code class="language-html">&lt;livewire:admin.form-wizard :steps="$steps"&gt;
    {{-- Step content will be rendered based on currentStep --}}
    {{-- Use renderStepContent() method in your component --}}
&lt;/livewire:admin.form-wizard&gt;</code></pre>
                    </section>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>

