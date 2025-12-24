@props([
    'label' => null,
    'placeholder' => 'Please select...',
    'options' => [], // ['value' => 'label'] or [['value' => '', 'label' => '']] format
    'size' => 'full',
    'required' => false,
    'error' => null,
    'hint' => null,
])

@php
    $uuid = Str::uuid();
    $wireModel = $attributes->wire('model');
    
    // Format options for Alpine: [{id, label, value}]
    $formattedOptions = collect($options)->map(function($label, $value) {
        return [
            'id' => $value,
            'label' => $label,
            'value' => $value,
        ];
    })->values()->toArray();
@endphp

<div class="w-full" {{ $attributes->whereDoesntStartWith('wire:model') }}>
    <div
        x-data="{
            open: false,
            @if($wireModel->value())
            value: @entangle($wireModel),
            @else
            value: null,
            @endif
            options: @js($formattedOptions),
            placeholderText: '{{ $placeholder }}',
            closeOnSelection: true,
            
            selectedOption: null,
            keyboardTimeout: false,

            init() {
                this.$watch('value', () => this.updateSelected());
                this.updateSelected();
            },

            updateSelected() {
                this.selectedOption = this.options.find(opt => String(opt.value) === String(this.value)) || null;
            },

            openMenu() {
                this.open = true;
                this.$nextTick(() => {
                    let selectedEl = this.$refs.list.querySelector('[data-selected=\'true\']');
                    if (selectedEl) {
                        this.$focus.focus(selectedEl);
                    } else {
                        this.$focus.within(this.$refs.list).first();
                    }
                });
            },

            closeMenu() {
                this.open = false;
                this.$nextTick(() => { this.$focus.focus(this.$refs.button); });
            },

            setSelected(option) {
                this.value = option.value;
                if (this.closeOnSelection) this.closeMenu();
            },

            isSelected(id) {
                return String(id) === String(this.value);
            },
            
            keyboardNavigation(e) {
                clearTimeout(this.keyboardTimeout);
                this.keyboardTimeout = setTimeout(() => { 
                    if (e.key.toUpperCase().match(/^[A-Z]$/)) {
                        let elements = this.$refs.list.querySelectorAll('li[data-label^=\'' + e.key.toUpperCase() + '\']');
                        let focusedEl, focusedIndex;

                        elements.forEach((el, index) => {
                            if (document.activeElement === el) {
                                focusedEl = el;
                                focusedIndex = index;
                            }
                        });

                        if (focusedEl) {
                            if ((elements.length - 1) === focusedIndex) {
                                this.$focus.focus(elements[0]);  
                            } else {
                                this.$focus.focus(elements[focusedIndex + 1]);
                            }
                        } else {
                            this.$focus.focus(elements[0]);
                        }
                    }
                }, 50);
            }
        }"
        class="relative"
        :class="{
            'w-48': '{{ $size }}' === 'xs',
            'w-56': '{{ $size }}' === 'sm',
            'w-64': '{{ $size }}' === 'md',
            'w-72': '{{ $size }}' === 'lg',
            'w-full': '{{ $size }}' === 'full'
        }"
    >
        <!-- Label -->
        @if($label)
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" x-on:click="openMenu()">
                {{ $label }}
                @if($required) <span class="text-red-500">*</span> @endif
            </label>
        @endif

        <!-- Select Menu Toggle -->
        <button
            x-ref="button"
            x-on:click="openMenu()"
            x-on:keydown.down.prevent.stop="openMenu()"
            x-on:keydown.up.prevent.stop="openMenu()"
            :aria-expanded="open"
            type="button"
            class="group flex w-full items-center justify-between gap-2 rounded-lg border border-zinc-200 bg-white px-3 py-2 text-start text-sm/6 focus:border-zinc-500 focus:ring-3 focus:ring-zinc-500/50 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 dark:focus:border-zinc-500"
            aria-haspopup="listbox"
        >
            <span
                x-text="selectedOption ? selectedOption.label : placeholderText"
                :class="{ 'text-zinc-500 dark:text-zinc-400': !selectedOption }"
                class="grow truncate"
            ></span>
            <svg class="size-5 flex-none opacity-40 transition group-hover:opacity-60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
            </svg>
        </button>

        <!-- Select Menu Container -->
        <ul
            x-cloak
            x-ref="list"
            x-show="open"
            x-trap="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 -translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-3"
            x-on:click.outside="closeMenu()"
            x-on:keydown="keyboardNavigation($event)"
            x-on:keydown.esc.prevent.stop="closeMenu()"
            x-on:keydown.up.prevent.stop="$focus.previous()"
            x-on:keydown.down.prevent.stop="$focus.next()"
            x-on:keydown.home.prevent.stop="$focus.first()"
            x-on:keydown.end.prevent.stop="$focus.last()"
            class="absolute inset-x-0 z-50 mt-2 max-h-60 origin-top overflow-y-auto rounded-lg bg-white py-2 shadow-xl ring-1 ring-black/5 focus:outline-none dark:bg-zinc-800 dark:ring-zinc-700"
            role="listbox"
            tabindex="0"
        >
            <template x-for="option in options" :key="option.id">
                <li
                    x-on:click="setSelected(option)"
                    x-on:keydown.enter.prevent.stop="setSelected(option)"
                    x-on:keydown.space.prevent.stop="setSelected(option)"
                    :class="{
                        'font-semibold text-zinc-950 bg-zinc-50 dark:text-white dark:bg-zinc-700/75': isSelected(option.id),
                        'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-50 focus:text-zinc-950 focus:bg-zinc-50 dark:text-zinc-300 dark:hover:text-white dark:hover:bg-zinc-700/50 dark:focus:bg-zinc-700/50': !isSelected(option.id),
                    }"
                    :data-selected="isSelected(option.id)"
                    :data-label="option.label.toUpperCase()"
                    class="group flex cursor-pointer items-center justify-between gap-2 px-3 text-sm focus:outline-none"
                    role="option"
                    tabindex="-1"
                >
                    <div x-text="option.label" class="grow truncate py-2"></div>
                    <svg x-show="isSelected(option.id)" class="size-5 flex-none text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                </li>
            </template>
        </ul>

        @if($hint)
            <p class="mt-1.5 text-xs text-zinc-500 dark:text-zinc-400">{{ $hint }}</p>
        @endif
        
        @php
            $errorName = $wireModel->value();
        @endphp
        @error($errorName)
            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>
</div>
