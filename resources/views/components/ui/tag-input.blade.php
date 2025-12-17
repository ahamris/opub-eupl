@php
    $hasWireModel = $attributes->whereStartsWith('wire:model')->isNotEmpty();
    $wireModelValue = $hasWireModel ? $attributes->wire('model')->value() : null;
@endphp

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $inputId }}" class="{{ $error ? 'text-red-600 dark:text-red-400' : '' }}">
            {{ $label }}
            @if($required)
                <span class="text-red-600 dark:text-red-400">*</span>
            @endif
        </label>
    @endif

    <div 
        x-data="{
            tags: @js($tags),
            inputValue: '',
            filteredOptions: @js($options ?? []),
            showDropdown: false,
            selectedIndex: -1,
            
            @if($hasWireModel)
                init() {
                    // Sync tags to Livewire
                    this.$watch('tags', (value) => {
                        $wire.set('{{ $wireModelValue }}', value);
                    });
                    
                    // Sync from Livewire to Alpine
                    this.$watch('$wire.{{ $wireModelValue }}', (value) => {
                        if (Array.isArray(value) && JSON.stringify(value) !== JSON.stringify(this.tags)) {
                            this.tags = value;
                        }
                    });
                },
            @endif
            
            addTag(tag) {
                const trimmedTag = typeof tag === 'string' ? tag.trim() : (tag.label || tag.value || tag);
                if (trimmedTag && !this.tags.includes(trimmedTag)) {
                    this.tags.push(trimmedTag);
                    this.inputValue = '';
                    @if($options)
                    this.filteredOptions = @js($options ?? []).filter(option => {
                        const label = option.label || option.value || option;
                        return !this.tags.includes(label);
                    });
                    // Keep dropdown open if there are still options available
                    this.showDropdown = this.filteredOptions.length > 0;
                    @else
                    this.showDropdown = false;
                    @endif
                }
            },
            
            removeTag(index) {
                this.tags.splice(index, 1);
                @if($options)
                // Update filtered options when tag is removed
                this.filteredOptions = @js($options ?? []).filter(option => {
                    const label = option.label || option.value || option;
                    return !this.tags.includes(label);
                });
                @endif
            },
            
            handleInput(event) {
                const value = event.target.value;
                this.inputValue = value;
                
                @if($options)
                // Filter dropdown options
                if (value) {
                    this.filteredOptions = @js($options ?? []).filter(option => {
                        const label = option.label || option.value || option;
                        return label.toLowerCase().includes(value.toLowerCase()) && 
                               !this.tags.includes(label);
                    });
                    this.showDropdown = this.filteredOptions.length > 0;
                } else {
                    this.filteredOptions = @js($options ?? []).filter(option => {
                        const label = option.label || option.value || option;
                        return !this.tags.includes(label);
                    });
                    this.showDropdown = this.filteredOptions.length > 0;
                }
                @endif
            },
            
            handleKeydown(event) {
                if (event.key === 'Enter' || event.key === ',') {
                    event.preventDefault();
                    if (this.selectedIndex >= 0 && this.filteredOptions.length > 0) {
                        // Select from dropdown
                        this.addTag(this.filteredOptions[this.selectedIndex]);
                        this.selectedIndex = -1;
                    } else if (this.inputValue.trim()) {
                        // Add typed tag
                        this.addTag(this.inputValue);
                    }
                } else if (event.key === 'Backspace' && !this.inputValue && this.tags.length > 0) {
                    // Remove last tag on backspace
                    this.removeTag(this.tags.length - 1);
                } else if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    if (this.showDropdown && this.filteredOptions.length > 0) {
                        this.selectedIndex = Math.min(this.selectedIndex + 1, this.filteredOptions.length - 1);
                    }
                } else if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    this.selectedIndex = Math.max(this.selectedIndex - 1, -1);
                } else if (event.key === 'Escape') {
                    this.showDropdown = false;
                    this.selectedIndex = -1;
                }
            },
            
            selectOption(option) {
                this.addTag(option);
            }
        }"
        class="relative"
        @click.away="showDropdown = false"
    >
        <div class="flex flex-wrap gap-2 {{ $classes }} min-h-[42px] items-center py-2">
            <template x-for="(tag, index) in tags" :key="index">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-[color-mix(in_oklab,var(--color-accent)_10%,transparent)] dark:bg-[color-mix(in_oklab,var(--color-accent)_10%,transparent)] text-[var(--color-accent)] dark:text-[var(--color-accent-content)] border border-[color-mix(in_oklab,var(--color-accent)_70%,transparent)] dark:border-[color-mix(in_oklab,var(--color-accent)_70%,transparent)] rounded-md text-xs font-medium transition-all duration-200">
                    <span x-text="tag" class="leading-5"></span>
                    <button 
                        type="button"
                        @click="removeTag(index)"
                        class="ml-0.5 -mr-0.5 flex items-center justify-center w-4 h-4 rounded-md hover:opacity-70 text-[var(--color-accent)] dark:text-[var(--color-accent-content)] transition-opacity duration-150 cursor-pointer"
                        @if($disabled) disabled @endif
                        title="Remove tag"
                    >
                        <i class="fa-solid fa-xmark text-xs leading-none"></i>
                    </button>
                </span>
            </template>
            
            <input
                type="text"
                name="{{ $name }}"
                id="{{ $inputId }}"
                x-model="inputValue"
                @input="handleInput"
                @keydown="handleKeydown"
                @focus="showDropdown = filteredOptions.length > 0"
                placeholder="{{ $placeholder }}"
                @if($required) required @endif
                @if($disabled) disabled @endif
                class="flex-1 min-w-[120px] border-0 bg-transparent focus:outline-none focus:ring-0 p-0"
                {{ $attributes->whereDoesntStartWith('wire:model') }}
            />
        </div>

        @if($options)
        <!-- Dropdown -->
        <div 
            x-show="showDropdown && filteredOptions.length > 0"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-md shadow-lg max-h-60 overflow-y-auto"
        >
            <template x-for="(option, index) in filteredOptions" :key="index">
                <button
                    type="button"
                    @click="selectOption(option)"
                    :class="selectedIndex === index ? 'bg-zinc-100 dark:bg-zinc-700' : 'hover:bg-zinc-50 dark:hover:bg-zinc-700/50'"
                    class="w-full px-4 py-2 text-left text-sm text-zinc-900 dark:text-zinc-100 transition-colors"
                >
                    <span x-text="option.label || option.value || option"></span>
                </button>
            </template>
        </div>
        @endif
    </div>

    @if($hint && !$error)
        <div class="text-xs leading-4 tracking-[0.4px] text-gray-600 dark:text-gray-400 mt-1.5">{{ $hint }}</div>
    @endif

    @if($error && $errorMessage)
        <div class="text-xs leading-4 tracking-[0.4px] text-red-600 dark:text-red-400 flex items-center gap-1 mt-1.5">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errorMessage }}
        </div>
    @endif
</div>

