@once
    @push('styles')
        <style>
            /* Color picker focus styles */
            .color-picker-input:focus {
                @apply outline-none ring-1;
                border-color: var(--color-accent);
                --tw-ring-color: var(--color-accent);
            }

            .dark .color-picker-input:focus {
                border-color: var(--color-accent-content);
                --tw-ring-color: var(--color-accent-content);
            }
        </style>
    @endpush
@endonce

<div class="space-y-1.5" x-data="{
    color: '{{ $value }}',
    showPicker: false,
    hue: 0,
    saturation: 100,
    lightness: 50,
    alpha: 1,
    dragging: false,
    
    init() {
        // Ensure color starts with #
        if (this.color && !this.color.startsWith('#')) {
            this.color = this.normalizeHex(this.color) || '#000000';
        }
        // Only update if we have a valid 6-character hex
        if (this.color && this.color.replace(/^#/, '').length === 6) {
            this.updateFromHex(this.color);
        }
    },
    
    normalizeHex(hex) {
        if (!hex) return null;
        
        // Remove # if exists
        let cleanHex = hex.replace(/^#/, '').toUpperCase();
        
        // Filter non-hex characters
        cleanHex = cleanHex.replace(/[^0-9A-F]/g, '');
        
        // Limit to 6 characters
        cleanHex = cleanHex.substring(0, 6);
        
        // If empty, return null
        if (cleanHex.length === 0) return null;
        
        return '#' + cleanHex;
    },
    
    updateFromHex(hex) {
        if (!hex) return;
        
        // Normalize hex (add # if missing, filter invalid chars, limit to 6)
        const normalized = this.normalizeHex(hex);
        if (!normalized) return;
        
        // Only process if we have exactly 6 characters
        const hexValue = normalized.replace(/^#/, '');
        if (hexValue.length !== 6) return;
        
        // Update color if different
        if (this.color !== normalized) {
            this.color = normalized;
        }
        
        const r = parseInt(normalized.slice(1, 3), 16);
        const g = parseInt(normalized.slice(3, 5), 16);
        const b = parseInt(normalized.slice(5, 7), 16);
        
        const hsl = this.rgbToHsl(r, g, b);
        this.hue = hsl.h;
        this.saturation = hsl.s;
        this.lightness = hsl.l;
    },
    
    handleInput(event) {
        let value = event.target.value;
        
        // Remove any existing # for processing
        value = value.replace(/^#/, '').toUpperCase();
        
        // Limit to 6 hex characters and filter non-hex characters
        value = value.replace(/[^0-9A-F]/g, '').substring(0, 6);
        
        // If value is empty, set to default
        if (value.length === 0) {
            this.color = '#000000';
            event.target.value = '#000000';
        } else {
            // Always add # prefix
            this.color = '#' + value;
            event.target.value = '#' + value;
        }
        
        // Only update color picker if we have exactly 6 characters
        if (value.length === 6) {
            this.updateFromHex(this.color);
        }
    },
    
    rgbToHsl(r, g, b) {
        r /= 255;
        g /= 255;
        b /= 255;
        
        const max = Math.max(r, g, b);
        const min = Math.min(r, g, b);
        let h, s, l = (max + min) / 2;
        
        if (max === min) {
            h = s = 0;
        } else {
            const d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            
            switch (max) {
                case r: h = ((g - b) / d + (g < b ? 6 : 0)) / 6; break;
                case g: h = ((b - r) / d + 2) / 6; break;
                case b: h = ((r - g) / d + 4) / 6; break;
            }
        }
        
        return {
            h: Math.round(h * 360),
            s: Math.round(s * 100),
            l: Math.round(l * 100)
        };
    },
    
    hslToRgb(h, s, l) {
        s /= 100;
        l /= 100;
        
        const c = (1 - Math.abs(2 * l - 1)) * s;
        const x = c * (1 - Math.abs((h / 60) % 2 - 1));
        const m = l - c / 2;
        let r = 0, g = 0, b = 0;
        
        if (0 <= h && h < 60) {
            r = c; g = x; b = 0;
        } else if (60 <= h && h < 120) {
            r = x; g = c; b = 0;
        } else if (120 <= h && h < 180) {
            r = 0; g = c; b = x;
        } else if (180 <= h && h < 240) {
            r = 0; g = x; b = c;
        } else if (240 <= h && h < 300) {
            r = x; g = 0; b = c;
        } else if (300 <= h && h < 360) {
            r = c; g = 0; b = x;
        }
        
        r = Math.round((r + m) * 255);
        g = Math.round((g + m) * 255);
        b = Math.round((b + m) * 255);
        
        return { r, g, b };
    },
    
    updateColor() {
        const rgb = this.hslToRgb(this.hue, this.saturation, this.lightness);
        this.color = '#' + [rgb.r, rgb.g, rgb.b].map(x => {
            const hex = x.toString(16);
            return hex.length === 1 ? '0' + hex : hex;
        }).join('').toUpperCase();
        
        this.$dispatch('color-changed', this.color);
    },
    
    setColor(hex) {
        this.color = hex;
        this.updateFromHex(hex);
        this.$dispatch('color-changed', hex);
    }
}" x-on:click.outside="showPicker = false">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-zinc-900 dark:text-zinc-100 {{ $error ? 'text-red-600 dark:text-red-400' : '' }}">
            {{ $label }}
            @if($required)
                <span class="text-red-600 dark:text-red-400">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <div class="flex items-center gap-2">
            @if($showInput)
                <div class="relative max-w-xs">
                    <input 
                        type="text"
                        name="{{ $name }}" 
                        id="{{ $inputId }}"
                        x-model="color"
                        value="{{ $value }}"
                        placeholder="{{ $placeholder }}"
                        @if($required) required @endif
                        @if($disabled) disabled @endif
                        @if($readonly) readonly @endif
                        class="color-picker-input h-10 {{ $classes }}"
                        style="height: 2.5rem; box-sizing: border-box;"
                        x-on:input="handleInput($event)"
                        x-on:blur="updateFromHex(color)"
                        {{ $attributes->except(['class']) }}
                    >
                </div>
            @endif
            
            <button
                type="button"
                @click="showPicker = !showPicker"
                @if($disabled) disabled @endif
                class="flex-shrink-0 w-10 h-10 rounded-md border-2 border-zinc-300 dark:border-zinc-700 shadow-sm hover:border-zinc-400 dark:hover:border-zinc-600 transition-colors focus:outline-none"
                :style="'background-color: ' + color"
                :class="{ 'cursor-not-allowed opacity-50': {{ $disabled ? 'true' : 'false' }} }"
            >
                <span class="sr-only">Open color picker</span>
            </button>
        </div>

        <!-- Color Picker Dropdown -->
        <div
            x-show="showPicker"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            x-cloak
            class="absolute z-50 mt-2 p-4 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-lg shadow-lg w-64"
            style="display: none;"
        >
            <!-- Saturation/Lightness Picker -->
            <div class="mb-4">
                <div
                    class="w-full h-40 rounded-md cursor-crosshair relative overflow-hidden border border-zinc-300 dark:border-zinc-700"
                    :style="'background: linear-gradient(to bottom, transparent, hsl(' + hue + ', 100%, 50%)), linear-gradient(to right, white, hsl(' + hue + ', 100%, 50%))'"
                    x-on:mousedown="dragging = true"
                    x-on:mouseup="dragging = false"
                    x-on:mouseleave="dragging = false"
                    x-on:mousemove="if (dragging) {
                        const rect = $event.currentTarget.getBoundingClientRect();
                        const x = Math.max(0, Math.min(1, ($event.clientX - rect.left) / rect.width));
                        const y = Math.max(0, Math.min(1, ($event.clientY - rect.top) / rect.height));
                        saturation = Math.round(x * 100);
                        lightness = Math.round((1 - y) * 100);
                        updateColor();
                    }"
                    x-on:click="
                        const rect = $event.currentTarget.getBoundingClientRect();
                        const x = Math.max(0, Math.min(1, ($event.clientX - rect.left) / rect.width));
                        const y = Math.max(0, Math.min(1, ($event.clientY - rect.top) / rect.height));
                        saturation = Math.round(x * 100);
                        lightness = Math.round((1 - y) * 100);
                        updateColor();
                    "
                >
                    <div
                        class="absolute w-3 h-3 border-2 border-white dark:border-zinc-900 rounded-full shadow-lg pointer-events-none"
                        :style="'left: ' + (saturation / 100 * 100) + '%; top: ' + ((100 - lightness) / 100 * 100) + '%'"
                    ></div>
                </div>
            </div>

            <!-- Hue Slider -->
            <div class="mb-4">
                <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1">Hue</label>
                <div
                    class="w-full h-4 rounded-md cursor-pointer relative"
                    style="background: linear-gradient(to right, #ff0000, #ffff00, #00ff00, #00ffff, #0000ff, #ff00ff, #ff0000)"
                    x-on:mousedown="dragging = true"
                    x-on:mouseup="dragging = false"
                    x-on:mouseleave="dragging = false"
                    x-on:mousemove="if (dragging) {
                        const rect = $event.currentTarget.getBoundingClientRect();
                        const x = Math.max(0, Math.min(1, ($event.clientX - rect.left) / rect.width));
                        hue = Math.round(x * 360);
                        updateColor();
                    }"
                    x-on:click="
                        const rect = $event.currentTarget.getBoundingClientRect();
                        const x = Math.max(0, Math.min(1, ($event.clientX - rect.left) / rect.width));
                        hue = Math.round(x * 360);
                        updateColor();
                    "
                >
                    <div
                        class="absolute w-3 h-3 border-2 border-white dark:border-zinc-900 rounded-full shadow-lg pointer-events-none top-1/2 -translate-y-1/2"
                        :style="'left: ' + (hue / 360 * 100) + '%'"
                    ></div>
                </div>
            </div>

            <!-- Preset Colors -->
            @if($showPresets)
                <div>
                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-2">Preset Colors</label>
                    <div class="grid grid-cols-8 gap-1.5">
                        @foreach($presetColors as $presetColor)
                            <button
                                type="button"
                                @click="setColor('{{ $presetColor }}')"
                                class="w-8 h-8 rounded transition-colors focus:outline-none"
                                style="background-color: {{ $presetColor }}"
                                :class="{ 'ring-1 ring-[var(--color-accent)] dark:ring-[var(--color-accent-content)] ring-offset-1': color === '{{ $presetColor }}' }"
                            >
                                <span class="sr-only">{{ $presetColor }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($hint && !$error)
        <div class="text-xs leading-4 tracking-[0.4px] text-zinc-600 dark:text-zinc-400 mt-1.5">{{ $hint }}</div>
    @endif

    @if($error && $errorMessage)
        <div class="text-xs leading-4 tracking-[0.4px] text-red-600 dark:text-red-400 flex items-center gap-1 mt-1.5">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errorMessage }}
        </div>
    @endif
</div>
