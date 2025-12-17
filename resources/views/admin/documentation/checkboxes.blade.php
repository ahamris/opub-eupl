<x-layouts.admin title="Checkboxes - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Checkboxes -->
            @php
                $code1 = '<x-checkbox label="I agree to the terms and conditions" name="checkbox1" value="test" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Checkbox</h3>
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
                <x-checkbox label="I agree to the terms and conditions" name="checkbox1" value="test" />
            </div>

            <!-- Checked Checkbox -->
            @php
                $code2 = '<x-checkbox label="Remember me" name="remember" value="1" checked />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Checked Checkbox</h3>
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
                <x-checkbox label="Remember me" name="remember" value="1" checked />
            </div>

            <!-- Disabled Checkbox -->
            @php
                $code3 = '<x-checkbox label="This is disabled" name="disabled1" value="1" disabled />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Disabled Checkbox</h3>
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
                <x-checkbox label="This is disabled" name="disabled1" value="1" disabled />
            </div>

            <!-- Checked and Disabled -->
            @php
                $code4 = '<x-checkbox label="Checked and disabled" name="disabled2" value="1" checked disabled />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Checked and Disabled</h3>
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
                <x-checkbox label="Checked and disabled" name="disabled2" value="1" checked disabled />
            </div>

            <!-- Color Variants -->
            @php
                $code5 = '<x-checkbox label="Primary" name="color1" value="1" color="primary" checked />
<x-checkbox label="Secondary" name="color2" value="1" color="secondary" checked />
<x-checkbox label="Success" name="color3" value="1" color="success" checked />
<x-checkbox label="Warning" name="color4" value="1" color="warning" checked />
<x-checkbox label="Error" name="color5" value="1" color="error" checked />
<x-checkbox label="Sky" name="color6" value="1" color="sky" checked />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Color Variants</h3>
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
                <div class="space-y-2">
                    <x-checkbox label="Primary" name="color1" value="1" color="primary" checked />
                    <x-checkbox label="Secondary" name="color2" value="1" color="secondary" checked />
                    <x-checkbox label="Success" name="color3" value="1" color="success" checked />
                    <x-checkbox label="Warning" name="color4" value="1" color="warning" checked />
                    <x-checkbox label="Error" name="color5" value="1" color="error" checked />
                    <x-checkbox label="Sky" name="color6" value="1" color="sky" checked />
                </div>
            </div>

            <!-- Required Checkbox -->
            @php
                $code6 = '<x-checkbox label="I accept the terms (required)" name="required1" value="1" required />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Required Checkbox</h3>
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
                <x-checkbox label="I accept the terms (required)" name="required1" value="1" required />
            </div>

            <!-- Toggle Section -->
            <div class="pt-8 border-t border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold mb-6">Toggle Switches</h2>
            </div>

            <!-- Basic Toggle -->
            @php
                $code7 = '<x-toggle label="Enable notifications" name="toggle1" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Toggle</h3>
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
                <x-toggle label="Enable notifications" name="toggle1" />
            </div>

            <!-- Checked Toggle -->
            @php
                $code8 = '<x-toggle label="Dark mode" name="toggle2" checked />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Checked Toggle</h3>
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
                <x-toggle label="Dark mode" name="toggle2" checked />
            </div>

            <!-- Toggle Sizes -->
            @php
                $code9 = '<x-toggle label="Small toggle" name="toggle3" size="sm" />
<x-toggle label="Normal toggle" name="toggle4" />
<x-toggle label="Large toggle" name="toggle5" size="lg" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Toggle Sizes</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code9 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="space-y-2">
                    <x-toggle label="Small toggle" name="toggle3" size="sm" />
                    <x-toggle label="Normal toggle" name="toggle4" />
                    <x-toggle label="Large toggle" name="toggle5" size="lg" />
                </div>
            </div>

            <!-- Disabled Toggle -->
            @php
                $code10 = '<x-toggle label="Disabled toggle" name="toggle6" disabled />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Disabled Toggle</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code10 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <x-toggle label="Disabled toggle" name="toggle6" disabled />
            </div>

            <!-- Radio Section -->
            <div class="pt-8 border-t border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold mb-6">Radio Buttons</h2>
            </div>

            <!-- Basic Radio Group -->
            @php
                $code11 = '<x-radio name="gender" value="male" label="Male" />
<x-radio name="gender" value="female" label="Female" />
<x-radio name="gender" value="other" label="Other" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Radio Group</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code11 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="space-y-2">
                    <x-radio name="gender" value="male" label="Male" />
                    <x-radio name="gender" value="female" label="Female" />
                    <x-radio name="gender" value="other" label="Other" />
                </div>
            </div>

            <!-- Checked Radio -->
            @php
                $code12 = '<x-radio name="theme" value="light" label="Light Theme" />
<x-radio name="theme" value="dark" label="Dark Theme" checked />
<x-radio name="theme" value="auto" label="Auto" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Checked Radio</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code12 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="space-y-2">
                    <x-radio name="theme" value="light" label="Light Theme" />
                    <x-radio name="theme" value="dark" label="Dark Theme" checked />
                    <x-radio name="theme" value="auto" label="Auto" />
                </div>
            </div>

            <!-- Radio Color Variants -->
            @php
                $code13 = '<x-radio name="status" value="active" label="Active" color="success" checked />
<x-radio name="status" value="pending" label="Pending" color="warning" />
<x-radio name="status" value="inactive" label="Inactive" color="error" />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Radio Color Variants</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code13 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="space-y-2">
                    <x-radio name="status" value="active" label="Active" color="success" checked />
                    <x-radio name="status" value="pending" label="Pending" color="warning" />
                    <x-radio name="status" value="inactive" label="Inactive" color="error" />
                </div>
            </div>

            <!-- Disabled Radio -->
            @php
                $code14 = '<x-radio name="option" value="available" label="Available" checked />
<x-radio name="option" value="locked" label="Locked" disabled />';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Disabled Radio</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code14 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="space-y-2">
                    <x-radio name="option" value="available" label="Available" checked />
                    <x-radio name="option" value="locked" label="Locked" disabled />
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
                    <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-checkbox label="Label text" name="checkbox1" value="test" /&gt;</code></pre>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-3">Checkbox Props</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">label</code>
                            <span class="text-gray-600 dark:text-gray-400"> - string</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Text displayed next to the checkbox</p>
                        </li>
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code>
                            <span class="text-gray-600 dark:text-gray-400"> - string</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Input name attribute</p>
                        </li>
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">value</code>
                            <span class="text-gray-600 dark:text-gray-400"> - string</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Input value attribute</p>
                        </li>
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">checked</code>
                            <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Checkbox starts in checked state</p>
                        </li>
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code>
                            <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Disable the checkbox</p>
                        </li>
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">color</code>
                            <span class="text-gray-600 dark:text-gray-400"> - string</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Checkbox color variant: <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">primary</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">secondary</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">success</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">warning</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">error</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">sky</code>. Default: <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">primary</code></p>
                        </li>
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">required</code>
                            <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Make the checkbox required</p>
                        </li>
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">id</code>
                            <span class="text-gray-600 dark:text-gray-400"> - string</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Input ID attribute (auto-generated if not provided)</p>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-3">Examples</h4>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Basic checkbox:</p>
                            <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-checkbox label="Label" name="checkbox1" value="test" /&gt;</code></pre>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Checked:</p>
                            <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-checkbox label="Label" name="checkbox1" value="test" checked /&gt;</code></pre>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Disabled:</p>
                            <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-checkbox label="Label" name="checkbox1" value="test" disabled /&gt;</code></pre>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Color variant:</p>
                            <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-checkbox label="Label" name="checkbox1" value="test" color="success" /&gt;</code></pre>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Required:</p>
                            <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-checkbox label="Label" name="checkbox1" value="test" required /&gt;</code></pre>
                        </div>
                    </div>
                </div>

                <!-- Toggle Documentation -->
                <div class="pt-8 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-lg font-semibold mb-3">Toggle Component</h4>
                    <div class="space-y-4">
                        <div>
                            <h5 class="text-md font-semibold mb-2">Basic Usage</h5>
                            <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-toggle label="Label text" name="toggle1" /&gt;</code></pre>
                        </div>
                        <div>
                            <h5 class="text-md font-semibold mb-2">Toggle Props</h5>
                            <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                                <li>
                                    <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">label</code>
                                    <span class="text-gray-600 dark:text-gray-400"> - string</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Text displayed next to the toggle</p>
                                </li>
                                <li>
                                    <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code>
                                    <span class="text-gray-600 dark:text-gray-400"> - string</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Input name attribute</p>
                                </li>
                                <li>
                                    <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">value</code>
                                    <span class="text-gray-600 dark:text-gray-400"> - string</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Input value attribute (default: "1")</p>
                                </li>
                                <li>
                                    <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">checked</code>
                                    <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Toggle starts in checked state</p>
                                </li>
                                <li>
                                    <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code>
                                    <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Disable the toggle</p>
                                </li>
                                <li>
                                    <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code>
                                    <span class="text-gray-600 dark:text-gray-400"> - string</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Toggle size: <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">sm</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">lg</code>. Default: normal</p>
                                </li>
                                <li>
                                    <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">required</code>
                                    <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Make the toggle required</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Radio Documentation -->
                <div class="pt-8 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-lg font-semibold mb-3">Radio Component</h4>
                    <div class="space-y-4">
                        <div>
                            <h5 class="text-md font-semibold mb-2">Basic Usage</h5>
                            <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-radio name="gender" value="male" label="Male" /&gt;
&lt;x-radio name="gender" value="female" label="Female" /&gt;</code></pre>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">Note: Radio buttons with the same <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code> form a group where only one can be selected.</p>
                        </div>
                        <div>
                            <h5 class="text-md font-semibold mb-2">Radio Props</h5>
                            <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                                <li>
                                    <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">label</code>
                                    <span class="text-gray-600 dark:text-gray-400"> - string</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Text displayed next to the radio button</p>
                                </li>
                                <li>
                                    <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code>
                                    <span class="text-gray-600 dark:text-gray-400"> - string</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Input name attribute (must be same for radio group)</p>
                                </li>
                                <li>
                                    <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">value</code>
                                    <span class="text-gray-600 dark:text-gray-400"> - string</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Input value attribute (required, must be unique within group)</p>
                                </li>
                                <li>
                                    <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">checked</code>
                                    <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Radio starts in checked state</p>
                                </li>
                                <li>
                                    <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code>
                                    <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Disable the radio button</p>
                                </li>
                                <li>
                                    <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">color</code>
                                    <span class="text-gray-600 dark:text-gray-400"> - string</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Radio color variant: <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">primary</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">secondary</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">success</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">warning</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">error</code>, <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">sky</code>. Default: <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">primary</code></p>
                                </li>
                                <li>
                                    <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">required</code>
                                    <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Make the radio button required</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>

