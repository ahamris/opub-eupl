{{-- Dropdown component with slot support (trigger, content) or standard select --}}
@php
    $hasCustomSlots = isset($trigger) && isset($content);
    $hasMenuItems = !empty($menuItems);
    $selected = is_array($selected) ? $selected : [$selected];
@endphp

@if($hasCustomSlots || $hasMenuItems)
    <!-- Slot mode: trigger/content ile genel amaçlı açılır menü -->
    <div 
        class="relative inline-block" 
        x-data="{ open: false }" 
        x-cloak 
        x-on:keydown.esc.prevent.stop="open = false"
        @click.outside="open = false"
    >
        <div @click="open = !open" class="inline-block">
            {{ $trigger ?? '' }}
        </div>
        <div
                x-show="open"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 -translate-y-3"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-3"
                role="menu"
                class="absolute end-0 z-50 mt-2 w-64 origin-top-right rounded-lg shadow-xl dark:shadow-zinc-950 overflow-hidden"
                style="display: none;"
        >
            <div class="bg-white dark:bg-zinc-800 ring-1 ring-black/5 dark:ring-zinc-700/75">
                <div class="px-2.5 py-2">
                    @if($hasMenuItems)
                        @foreach($menuItems as $item)
                            @php
                                $itemType = $item['type'] ?? ($item['href'] ? 'link' : (($item['action'] ?? false) ? 'button' : 'link'));
                                $itemColor = $item['color'] ?? ($itemType === 'button' && isset($item['danger']) && $item['danger'] ? 'red' : 'zinc');
                                $itemClasses = "group flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm font-medium transition-colors ";
                                
                                if ($itemColor === 'red') {
                                    $itemClasses .= "text-red-600 hover:bg-zinc-100/75 hover:text-red-700 dark:text-red-400 dark:hover:bg-zinc-700/50 dark:hover:text-red-300";
                                } else {
                                    $itemClasses .= "text-zinc-600 hover:bg-zinc-100/75 hover:text-zinc-950 dark:text-zinc-400 dark:hover:bg-zinc-700/50 dark:hover:text-zinc-50";
                                }
                                
                                $icon = $item['icon'] ?? null;
                                $label = $item['label'] ?? '';
                                $href = $item['href'] ?? null;
                                $action = $item['action'] ?? null;
                                $target = $item['target'] ?? null;
                            @endphp
                            
                            @if($itemType === 'link' && !empty($href))
                                <a 
                                    href="{{ $href }}" 
                                    @if(!empty($target)) target="{{ $target }}" rel="noopener noreferrer" @endif
                                    role="menuitem"
                                    class="{{ $itemClasses }}"
                                >
                                    @if(!empty($icon))
                                        <span class="flex items-center justify-center w-5 h-5 flex-shrink-0">
                                            <i class="fas fa-{{ $icon }} opacity-40 group-hover:opacity-80 transition-opacity"></i>
                                        </span>
                                    @endif
                                    <span>{{ $label }}</span>
                                </a>
                            @elseif($itemType === 'button' && !empty($action))
                                <button 
                                    type="button"
                                    x-on:click="{{ $action }}; open = false"
                                    role="menuitem"
                                    class="{{ $itemClasses }} w-full text-left"
                                >
                                    @if(!empty($icon))
                                        <span class="flex items-center justify-center w-5 h-5 flex-shrink-0">
                                            <i class="fas fa-{{ $icon }} opacity-40 group-hover:opacity-80 transition-opacity"></i>
                                        </span>
                                    @endif
                                    <span>{{ $label }}</span>
                                </button>
                            @endif
                        @endforeach
                    @else
                        {{ $content ?? '' }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@else
    @php
        // Normalize selected - handle null
        $normalizedSelected = $selected ?? [];
        $selectedArray = is_array($normalizedSelected) ? $normalizedSelected : [$normalizedSelected];
        $selectedArray = array_filter($selectedArray, fn ($value) => $value !== '' && $value !== null);
        $selectedJson = json_encode(array_values($selectedArray));
        
        // Ensure options is always an array
        $normalizedOptions = is_array($options) ? $options : [];
    @endphp
    <div x-data="{
    open: false,
    selectedValues: {{ $selectedJson }},
    options: @js($normalizedOptions),
    multiple: {{ $multiple ? 'true' : 'false' }},
    getSelectedLabels() {
        if (this.multiple) {
            return this.selectedValues.map(val => this.options[val] || '').filter(Boolean);
        } else {
            return this.selectedValues.length > 0 ? [this.options[this.selectedValues[0]] || ''] : [];
        }
    },
    hasSelection() {
        return this.selectedValues.length > 0;
    },
    toggleValue(value) {
        if (this.multiple) {
            const index = this.selectedValues.indexOf(value);
            if (index > -1) {
                this.selectedValues.splice(index, 1);
            } else {
                this.selectedValues.push(value);
            }
        } else {
            this.selectedValues = [value];
            this.open = false;
        }
    },
    removeValue(value) {
        const index = this.selectedValues.indexOf(value);
        if (index > -1) {
            this.selectedValues.splice(index, 1);
        }
    }
}" x-cloak>
        @if($label)
            <label for="{{ $dropdownId }}" class="{{ $error ? 'text-red-600 dark:text-red-400' : '' }}">
                {{ $label }}
                @if($required)
                    <span class="text-red-600 dark:text-red-400">*</span>
                @endif
            </label>
        @endif

        <!-- Hidden inputs for form submission -->
        @if($multiple)
            <template x-for="value in selectedValues" :key="value">
                <input type="hidden" name="{{ $name }}[]" :value="value" />
            </template>
        @else
            <input type="hidden" name="{{ $name }}" :value="selectedValues[0] || ''" />
        @endif

        <div class="relative {{ $label ? 'mt-2' : '' }}" @click.outside="open = false">
            <!-- Dropdown Trigger -->
            <button
                    type="button"
                    @click="{{ $disabled ? '' : 'open = !open' }}"
                    @if($disabled) disabled @endif
                    :aria-expanded="open"
                    aria-haspopup="listbox"
                    aria-controls="{{ $dropdownId }}-menu"
                    id="{{ $dropdownId }}-trigger"
                    class="{{ $baseClasses }}"
            >
                <div class="flex flex-wrap items-center gap-1 flex-1 min-h-[1.25rem]">
                    @if($multiple)
                        <!-- Multi-select: Show chips -->
                        <template x-for="(value, index) in selectedValues" :key="value">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium {{ $disabled ? 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' : 'bg-indigo-500 dark:bg-indigo-600 text-white' }}">
                            <span x-text="options[value] || ''"></span>
                            @if(!$disabled)
                                <i class="fas fa-times cursor-pointer text-xs hover:text-indigo-200" x-on:click.stop="removeValue(value); open = false"></i>
                            @endif
                        </span>
                        </template>
                        <template x-if="!hasSelection()">
                            <span class="text-gray-500 dark:text-gray-400">{{ $placeholder }}</span>
                        </template>
                    @else
                        <!-- Single-select: Show text -->
                        <template x-if="hasSelection()">
                            <span x-text="getSelectedLabels()[0] || '{{ $placeholder }}'"></span>
                        </template>
                        <template x-if="!hasSelection()">
                            <span class="text-gray-500 dark:text-gray-400">{{ $placeholder }}</span>
                        </template>
                    @endif
                </div>
                <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-400 transition-transform duration-200 ml-2" :class="{ 'rotate-180': open }"></i>
            </button>

            <!-- Dropdown Menu -->
            <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    id="{{ $dropdownId }}-menu"
                    role="listbox"
                    aria-labelledby="{{ $dropdownId }}-trigger"
                    class="absolute z-[9999] w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                    style="display: none;"
            >
                @if($multiple)
                    <!-- Multi-select with checkboxes -->
                    @foreach($options as $value => $optionLabel)
                        <label class="flex items-center gap-3 px-4 py-2.5 {{ $disabled ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer' }}" role="option">
                            <input
                                    type="checkbox"
                                    value="{{ $value }}"
                                    :checked="selectedValues.includes('{{ $value }}')"
                                    @change="toggleValue('{{ $value }}')"
                                    @if($disabled) disabled @endif
                                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-indigo-500 dark:text-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                            >
                            <span class="flex-1 text-sm text-gray-900 dark:text-gray-100">{{ $optionLabel }}</span>
                            <i class="fas fa-check text-indigo-500 dark:text-indigo-400" x-show="selectedValues.includes('{{ $value }}')"></i>
                        </label>
                    @endforeach
                @elseif($radio)
                    <!-- Single-select with radio buttons -->
                    @foreach($options as $value => $optionLabel)
                        <label class="flex items-center gap-3 px-4 py-2.5 {{ $disabled ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer' }}" role="option">
                            <input
                                    type="radio"
                                    name="{{ $name }}"
                                    value="{{ $value }}"
                                    :checked="selectedValues[0] === '{{ $value }}'"
                                    @change="toggleValue('{{ $value }}')"
                                    @if($disabled) disabled @endif
                                    class="w-4 h-4 text-indigo-500 dark:text-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                            >
                            <span class="flex-1 text-sm text-gray-900 dark:text-gray-100">{{ $optionLabel }}</span>
                        </label>
                    @endforeach
                @else
                    <!-- Single-select standard -->
                    @foreach($options as $value => $optionLabel)
                        <label class="flex items-center justify-between gap-3 px-4 py-2.5 {{ $disabled ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer' }}"
                               @click="{{ $disabled ? '' : 'toggleValue(\''.$value.'\')' }}"
                               role="option"
                               :aria-selected="selectedValues[0] === '{{ $value }}'">
                            <span class="flex-1 text-sm text-gray-900 dark:text-gray-100">{{ $optionLabel }}</span>
                            <i class="fas fa-check text-indigo-500 dark:text-indigo-400" x-show="selectedValues[0] === '{{ $value }}'"></i>
                        </label>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="flex justify-between items-center">
            @if($hint && !$error)
                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $hint }}</div>
            @else
                <div></div>
            @endif
        </div>

        @if($error && $errorMessage)
            <div class="text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errorMessage }}
            </div>
        @endif
    </div>
@endif