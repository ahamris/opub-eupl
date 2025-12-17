<x-layouts.admin title="DatePicker - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic DatePicker -->
            <div>
                @php
                    $code1 = '<x-datepicker name="date" label="Date" />
<x-datepicker name="date2" label="Date with Value" value="2024-12-25" />
<x-datepicker name="date3" label="Date with Placeholder" placeholder="Select a date" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic DatePicker</h3>
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
                    <x-datepicker name="date" label="Date" />
                    <x-datepicker name="date2" label="Date with Value" value="2024-12-25" />
                    <x-datepicker name="date3" label="Date with Placeholder" placeholder="Select a date" />
                </div>
            </div>

            <!-- Types -->
            <div>
                @php
                    $code2 = '<x-datepicker name="date" type="date" label="Date" />
<x-datepicker name="datetime" type="datetime" label="Date & Time" />
<x-datepicker name="range" type="range" label="Date Range" />
<x-datepicker name="multiple" type="multiple" label="Multiple Dates" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Types</h3>
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
                    <x-datepicker name="date" type="date" label="Date" />
                    <x-datepicker name="datetime" type="datetime" label="Date & Time" />
                    <x-datepicker name="range" type="range" label="Date Range" />
                    <x-datepicker name="multiple" type="multiple" label="Multiple Dates" />
                </div>
            </div>

            <!-- Sizes -->
            <div>
                @php
                    $code3 = '<x-datepicker name="small" size="sm" label="Small" />
<x-datepicker name="default" label="Default" />
<x-datepicker name="large" size="lg" label="Large" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Sizes</h3>
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
                    <x-datepicker name="small" size="sm" label="Small" />
                    <x-datepicker name="default" label="Default" />
                    <x-datepicker name="large" size="lg" label="Large" />
                </div>
            </div>

            <!-- With Icons -->
            <div>
                @php
                    $code4 = '<x-datepicker name="icon-left" icon="calendar" label="Icon Left" />
<x-datepicker name="icon-right" icon="calendar" icon-position="right" label="Icon Right" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Icons</h3>
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
                    <x-datepicker name="icon-left" icon="calendar" label="Icon Left" />
                    <x-datepicker name="icon-right" icon="calendar" icon-position="right" label="Icon Right" />
                </div>
            </div>

            <!-- Min/Max Date -->
            <div>
                @php
                    $code5 = '<x-datepicker name="minmax" label="Date Range" min-date="2024-01-01" max-date="2024-12-31" />
<x-datepicker name="future" label="Future Only" min-date="today" />
<x-datepicker name="past" label="Past Only" max-date="today" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Min/Max Date</h3>
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
                    <x-datepicker name="minmax" label="Date Range" min-date="2024-01-01" max-date="2024-12-31" />
                    <x-datepicker name="future" label="Future Only" min-date="today" />
                    <x-datepicker name="past" label="Past Only" max-date="today" />
                </div>
            </div>

            <!-- Time Options -->
            <div>
                @php
                    $code6 = '<x-datepicker name="time24" type="datetime" time24hr label="24 Hour Format" />
<x-datepicker name="time12" type="datetime" time-format="h:i K" label="12 Hour Format" />
<x-datepicker name="seconds" type="datetime" enable-seconds label="With Seconds" />
<x-datepicker name="no-cal" no-calendar enable-time label="Time Only" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Time Options</h3>
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
                    <x-datepicker name="time24" type="datetime" time24hr label="24 Hour Format" />
                    <x-datepicker name="time12" type="datetime" time-format="h:i K" label="12 Hour Format" />
                    <x-datepicker name="seconds" type="datetime" enable-seconds label="With Seconds" />
                    <x-datepicker name="no-cal" no-calendar enable-time label="Time Only" />
                </div>
            </div>

            <!-- Time Increments -->
            <div>
                @php
                    $code7 = '<x-datepicker name="hour-inc" type="datetime" hour-increment="2" label="Hour Increment (2)" />
<x-datepicker name="min-inc" type="datetime" minute-increment="15" label="Minute Increment (15)" />
<x-datepicker name="default-time" type="datetime" default-hour="9" default-minute="30" label="Default Time" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Time Increments</h3>
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
                    <x-datepicker name="hour-inc" type="datetime" hour-increment="2" label="Hour Increment (2)" />
                    <x-datepicker name="min-inc" type="datetime" minute-increment="15" label="Minute Increment (15)" />
                    <x-datepicker name="default-time" type="datetime" default-hour="9" default-minute="30" label="Default Time" />
                </div>
            </div>

            <!-- Time Range -->
            <div>
                @php
                    $code8 = '<x-datepicker name="time-range" type="datetime" min-time="09:00" max-time="18:00" label="Business Hours (9 AM - 6 PM)" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Time Range</h3>
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
                    <x-datepicker name="time-range" type="datetime" min-time="09:00" max-time="18:00" label="Business Hours (9 AM - 6 PM)" />
                </div>
            </div>

            <!-- Disable/Enable Dates -->
            <div>
                @php
                    $code9 = '<x-datepicker name="disabled" label="Disabled Dates" :disable="[\'2024-12-25\', \'2024-12-31\']" />
<x-datepicker name="weekends" label="Weekends Disabled" :options="[\'disable\' => [function(date) { return date.getDay() === 0 || date.getDay() === 6; }]]" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Disable/Enable Dates</h3>
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
                <div class="space-y-4">
                    <x-datepicker name="disabled" label="Disabled Dates" :disable="['2024-12-25', '2024-12-31']" />
                </div>
            </div>

            <!-- Custom Format -->
            <div>
                @php
                    $code10 = '<x-datepicker name="format1" format="d/m/Y" label="DD/MM/YYYY" />
<x-datepicker name="format2" format="F j, Y" label="Month Day, Year" />
<x-datepicker name="format3" format="Y-m-d H:i" type="datetime" label="ISO Format" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Custom Format</h3>
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
                <div class="space-y-4">
                    <x-datepicker name="format1" format="d/m/Y" label="DD/MM/YYYY" />
                    <x-datepicker name="format2" format="F j, Y" label="Month Day, Year" />
                    <x-datepicker name="format3" format="Y-m-d H:i" type="datetime" label="ISO Format" />
                </div>
            </div>

            <!-- Alt Input -->
            <div>
                @php
                    $code11 = '<x-datepicker name="alt" alt-input alt-format="F j, Y" label="Alt Input (Readable Format)" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Alt Input</h3>
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
                <div class="space-y-4">
                    <x-datepicker name="alt" alt-input alt-format="F j, Y" label="Alt Input (Readable Format)" />
                </div>
            </div>

            <!-- Inline Calendar -->
            <div>
                @php
                    $code12 = '<x-datepicker name="inline" inline label="Inline Calendar" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Inline Calendar</h3>
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
                <div class="space-y-4">
                    <x-datepicker name="inline" inline label="Inline Calendar" />
                </div>
            </div>

            <!-- Week Numbers -->
            <div>
                @php
                    $code13 = '<x-datepicker name="week" week-numbers label="With Week Numbers" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Week Numbers</h3>
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
                <div class="space-y-4">
                    <x-datepicker name="week" week-numbers label="With Week Numbers" />
                </div>
            </div>

            <!-- Themes -->
            <div>
                @php
                    $code14 = '<x-datepicker name="theme-demo" :theme="$selectedTheme" label="Theme Demo" />

<!-- Theme Buttons -->
<div class="flex flex-wrap gap-2 mb-4">
    <button onclick="applyTheme(\'light\')">Light</button>
    <button onclick="applyTheme(\'dark\')">Dark</button>
    <button onclick="applyTheme(\'material_blue\')">Material Blue</button>
</div>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Themes</h3>
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
                <div 
                    x-data="{ selectedTheme: 'auto' }"
                    class="space-y-4"
                >
                    <div class="flex flex-wrap gap-2 mb-4">
                        <button 
                            type="button"
                            @click="selectedTheme = 'light'; applyThemeToDemo('light')"
                            class="px-3 py-1.5 text-sm rounded-md border transition-colors"
                            :class="selectedTheme === 'light' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                        >
                            Light
                        </button>
                        <button 
                            type="button"
                            @click="selectedTheme = 'dark'; applyThemeToDemo('dark')"
                            class="px-3 py-1.5 text-sm rounded-md border transition-colors"
                            :class="selectedTheme === 'dark' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                        >
                            Dark
                        </button>
                        <button 
                            type="button"
                            @click="selectedTheme = 'material_blue'; applyThemeToDemo('material_blue')"
                            class="px-3 py-1.5 text-sm rounded-md border transition-colors"
                            :class="selectedTheme === 'material_blue' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                        >
                            Material Blue
                        </button>
                        <button 
                            type="button"
                            @click="selectedTheme = 'material_green'; applyThemeToDemo('material_green')"
                            class="px-3 py-1.5 text-sm rounded-md border transition-colors"
                            :class="selectedTheme === 'material_green' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                        >
                            Material Green
                        </button>
                        <button 
                            type="button"
                            @click="selectedTheme = 'material_orange'; applyThemeToDemo('material_orange')"
                            class="px-3 py-1.5 text-sm rounded-md border transition-colors"
                            :class="selectedTheme === 'material_orange' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                        >
                            Material Orange
                        </button>
                        <button 
                            type="button"
                            @click="selectedTheme = 'material_red'; applyThemeToDemo('material_red')"
                            class="px-3 py-1.5 text-sm rounded-md border transition-colors"
                            :class="selectedTheme === 'material_red' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                        >
                            Material Red
                        </button>
                        <button 
                            type="button"
                            @click="selectedTheme = 'airbnb'; applyThemeToDemo('airbnb')"
                            class="px-3 py-1.5 text-sm rounded-md border transition-colors"
                            :class="selectedTheme === 'airbnb' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                        >
                            Airbnb
                        </button>
                        <button 
                            type="button"
                            @click="selectedTheme = 'confetti'; applyThemeToDemo('confetti')"
                            class="px-3 py-1.5 text-sm rounded-md border transition-colors"
                            :class="selectedTheme === 'confetti' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                        >
                            Confetti
                        </button>
                        <button 
                            type="button"
                            @click="selectedTheme = 'auto'; applyThemeToDemo('auto')"
                            class="px-3 py-1.5 text-sm rounded-md border transition-colors"
                            :class="selectedTheme === 'auto' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                        >
                            Auto
                        </button>
                    </div>
                    <x-datepicker 
                        name="theme-demo" 
                        theme="auto"
                        label="Theme Demo" 
                        id="theme-demo-picker"
                    />
                </div>
            </div>

            <!-- Localization -->
            <div>
                @php
                    $code15 = '<x-datepicker name="en" label="English (Default)" />
<x-datepicker name="nl" locale="nl" label="Dutch (Netherlands)" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Localization</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code15 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <x-datepicker name="en" label="English (Default)" />
                    <x-datepicker name="nl" locale="nl" label="Dutch (Netherlands)" />
                </div>
            </div>

            <!-- States -->
            <div>
                @php
                    $code16 = '<x-datepicker name="error" error error-message="This field is required" label="Error State" />
<x-datepicker name="disabled" disabled label="Disabled" />
<x-datepicker name="readonly" readonly value="2024-12-25" label="Readonly" />
<x-datepicker name="required" required label="Required" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">States</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code16 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <x-datepicker name="error" error error-message="This field is required" label="Error State" />
                    <x-datepicker name="disabled" disabled label="Disabled" />
                    <x-datepicker name="readonly" readonly value="2024-12-25" label="Readonly" />
                    <x-datepicker name="required" required label="Required" />
                </div>
            </div>

            <!-- Advanced Options -->
            <div>
                @php
                    $code17 = '<x-datepicker name="advanced" label="Advanced Options" 
    :options="[
        \'position\' => \'above\',
        \'disableMobile\' => true,
        \'allowInput\' => false
    ]" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Advanced Options</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code17 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <x-datepicker name="advanced" label="Advanced Options" 
                        :options="[
                            'position' => 'above',
                            'disableMobile' => true,
                            'allowInput' => false
                        ]" />
                </div>
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div class="space-y-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm p-6">
                <h2 class="text-2xl font-bold mb-4">DatePicker Component</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    A powerful and flexible date/time picker component built on Flatpickr. Supports multiple date selection modes, themes, localization, and extensive customization options.
                </p>

                <h3 class="text-xl font-semibold mb-3">Basic Props</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code> - string (required) - Input name</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">label</code> - string - Label text</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">value</code> - string - Default value</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">placeholder</code> - string - Placeholder text</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">id</code> - string|null - Custom ID</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">hint</code> - string - Helper text</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Type & Format</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">type</code> - string - 'date' | 'datetime' | 'time' | 'range' | 'multiple'</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">format</code> - string - Custom date format (e.g., 'd/m/Y', 'F j, Y')</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">defaultDate</code> - string - Default selected date</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Date Constraints</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">minDate</code> - string - Minimum selectable date</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">maxDate</code> - string - Maximum selectable date</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disable</code> - array - Dates to disable</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">enable</code> - array - Only enable these dates</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Time Options</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">enableTime</code> - bool - Enable time selection</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">time24hr</code> - bool - 24-hour format</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">timeFormat</code> - string - Custom time format</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">enableSeconds</code> - bool - Show seconds</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">noCalendar</code> - bool - Time only, no calendar</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">hourIncrement</code> - int - Hour step (e.g., 2)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">minuteIncrement</code> - int - Minute step (e.g., 15)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">defaultHour</code> - int - Default hour (0-23)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">defaultMinute</code> - int - Default minute (0-59)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">minTime</code> - string - Minimum time (e.g., '09:00')</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">maxTime</code> - string - Maximum time (e.g., '18:00')</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Display Options</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">size</code> - string - 'sm' | 'lg' | null</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">icon</code> - string|null - FontAwesome icon name</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">iconPosition</code> - string - 'left' | 'right'</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">theme</code> - string|null - Theme name or 'auto'</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">locale</code> - string|null - Locale code (e.g., 'tr', 'de', 'fr')</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">altInput</code> - bool - Show alternative readable input</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">altFormat</code> - string - Format for alt input</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">inline</code> - bool - Inline calendar</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">static</code> - bool - Static calendar</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">weekNumbers</code> - bool - Show week numbers</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">position</code> - string - 'auto' | 'above' | 'below'</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Behavior Options</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">allowInput</code> - bool - Allow manual input (default: true)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">clickOpens</code> - bool - Open on click (default: true)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disableMobile</code> - bool - Disable native mobile picker</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">States</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">error</code> - bool - Error state</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">errorMessage</code> - string - Error message</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code> - bool - Disabled state</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">readonly</code> - bool - Readonly state</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">required</code> - bool - Required field</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Advanced</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">options</code> - array - Flatpickr advanced options</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Available Themes</h3>
                <ul class="space-y-1 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li>• <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">light</code> - Default light theme</li>
                    <li>• <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">dark</code> - Dark theme</li>
                    <li>• <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">material_blue</code> - Material Blue</li>
                    <li>• <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">material_green</code> - Material Green</li>
                    <li>• <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">material_orange</code> - Material Orange</li>
                    <li>• <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">material_red</code> - Material Red</li>
                    <li>• <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">airbnb</code> - Airbnb theme</li>
                    <li>• <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">confetti</code> - Confetti theme</li>
                    <li>• <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">auto</code> - Auto (dark/light based on system)</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Date Format Examples</h3>
                <ul class="space-y-1 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li>• <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">Y-m-d</code> - 2024-12-25</li>
                    <li>• <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">d/m/Y</code> - 25/12/2024</li>
                    <li>• <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">F j, Y</code> - December 25, 2024</li>
                    <li>• <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">Y-m-d H:i</code> - 2024-12-25 14:30</li>
                    <li>• <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">h:i K</code> - 02:30 PM</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Common Use Cases</h3>
                <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                    <div>
                        <p class="font-medium mb-1">Basic Date Selection:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-datepicker name="date" label="Select Date" /&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Date Range:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-datepicker name="range" type="range" label="Date Range" /&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">DateTime with Constraints:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-datepicker 
    name="datetime" 
    type="datetime" 
    min-date="today" 
    min-time="09:00" 
    max-time="18:00" 
    label="Business Hours" 
/&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Localized DatePicker:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-datepicker name="date" locale="tr" label="Tarih Seçin" /&gt;</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function applyThemeToDemo(theme) {
            // Load theme CSS for the demo picker
            if (window.loadFlatpickrTheme) {
                window.loadFlatpickrTheme(theme);
            }
            
            // Update the demo datepicker's theme
            const demoInput = document.getElementById('theme-demo-picker');
            if (demoInput) {
                // Update data-theme attribute
                demoInput.setAttribute('data-theme', theme);
                
                // Get the Flatpickr instance
                const instance = demoInput._flatpickrInstance || demoInput._flatpickr;
                if (instance && instance.calendarContainer) {
                    // Remove all theme classes
                    const availableThemes = ['dark', 'light', 'material_blue', 'material_green', 'material_orange', 'material_red', 'airbnb', 'confetti'];
                    availableThemes.forEach(function(themeName) {
                        instance.calendarContainer.classList.remove(themeName);
                    });
                    
                    // Add new theme class if not light
                    if (theme && theme !== 'light' && theme !== 'auto') {
                        instance.calendarContainer.classList.add(theme);
                    } else if (theme === 'auto') {
                        // Auto theme: dark or light based on system
                        const isDark = document.documentElement.classList.contains('dark');
                        if (isDark) {
                            instance.calendarContainer.classList.add('dark');
                        }
                    }
                }
            }
        }
    </script>

</x-layouts.admin>

