@props([
    'name',
    'label' => null,
    'value' => '#000000',
    'id' => null,
])

@php
    $id = $id ?? $name ?? 'color-picker-' . uniqid();
@endphp

<div class="w-full">
    <div
        x-data="{
            // Options
            defaultColor: '{{ $value }}',

            // Helpers
            color: null,
            textInput: null,
            message: null,

            // Initialization
            init() {
                // Convert initial color and set both inputs
                const convertedColor = this.colorToHex(this.defaultColor);

                this.color = convertedColor;
                this.textInput = convertedColor;

                // Watch for changes in the text input and update the color
                this.$watch('textInput', value => {
                    if (this.isValidColor(value)) {
                        const hexColor = this.colorToHex(value);
                        this.color = hexColor;
                        this.message = null;
                    } else {
                        this.message = 'Invalid color!';
                    }
                });

                // Watch for changes in the color input and update the text input
                this.$watch('color', value => {
                    this.textInput = value;
                });
            },

            // Check if the color is valid
            isValidColor(color) {
                const temp = document.createElement('div');
                temp.style.color = color;
                return temp.style.color !== '';
            },

            // Convert color to hex
            colorToHex(color) {
                if (!color) return '#000000';

                color = color.toLowerCase().replace(/\s/g, '');

                // Handle RGB format
                let rgbMatch = color.match(/^rgb\((\d+),(\d+),(\d+)\)$/);
                if (rgbMatch) {
                    const [_, r, g, b] = rgbMatch;
                    return '#' + [r, g, b].map(x => {
                        const hex = parseInt(x).toString(16);
                        return hex.length === 1 ? '0' + hex : hex;
                    }).join('');
                }

                // Handle RGBA format
                let rgbaMatch = color.match(/^rgba\((\d+),(\d+),(\d+),([\d.]+)\)$/);
                if (rgbaMatch) {
                    const [_, r, g, b] = rgbaMatch;
                    return '#' + [r, g, b].map(x => {
                        const hex = parseInt(x).toString(16);
                        return hex.length === 1 ? '0' + hex : hex;
                    }).join('');
                }

                // Handle HSL format
                let hslMatch = color.match(/^hsl\((\d+),(\d+)%,(\d+)%\)$/);
                if (hslMatch) {
                    const [_, h, s, l] = hslMatch.map(Number);
                    return this.hslToHex(h, s, l);
                }

                // Handle HSLA format
                let hslaMatch = color.match(/^hsla\((\d+),(\d+)%,(\d+)%,([\d.]+)\)$/);
                if (hslaMatch) {
                    const [_, h, s, l] = hslaMatch.map(Number);
                    return this.hslToHex(h, s, l);
                }

                // If it's already a hex color or another valid color format, return as is
                return color;
            },

            // Convert HSL to hex
            hslToHex(h, s, l) {
                l /= 100;
                const a = s * Math.min(l, 1 - l) / 100;
                const f = n => {
                    const k = (n + h / 30) % 12;
                    const color = l - a * Math.max(Math.min(k - 3, 9 - k, 1), -1);
                    return Math.round(255 * color).toString(16).padStart(2, '0');
                };
                return `#${f(0)}${f(8)}${f(4)}`;
            }
        }"
        class="rounded-xl border border-zinc-200 p-6 dark:border-zinc-700/50"
    >
        <!-- Input -->
        <div class="space-y-1">
            @if($label)
                <label
                    for="{{ $id }}"
                    class="block text-sm font-medium text-zinc-900 dark:text-zinc-100"
                >
                    {{ $label }}
                </label>
            @endif
            <div class="relative">
                <div
                    class="absolute inset-y-0 start-0 flex w-12 items-center justify-center"
                >
                    <div
                        class="relative size-5 cursor-pointer rounded-full transition-all duration-150 hover:opacity-80 active:opacity-100"
                        x-bind:style="{ backgroundColor: color }"
                    >
                        <input
                            type="color"
                            x-model="color"
                            class="absolute inset-0 size-5 cursor-pointer opacity-0"
                        />
                    </div>
                </div>
                <input
                    type="text"
                    id="{{ $id }}"
                    name="{{ $name }}"
                    x-model="textInput"
                    class="block w-full rounded-lg border border-zinc-200 py-2 pe-3 ps-11 text-sm/6 placeholder-zinc-500 focus:border-zinc-500 focus:ring-3 focus:ring-zinc-500/50 dark:border-zinc-600 dark:bg-transparent dark:placeholder-zinc-400 dark:focus:border-zinc-500"
                    required
                />
            </div>
        </div>
        <!-- END Input -->

        <!-- Message -->
        <p
            x-text="message || 'Taking advantage of browser\'s default color picker.'"
            class="mt-1.5 text-sm font-medium text-zinc-500 dark:text-zinc-400"
        >
            Taking advantage of browser's default color picker.
        </p>
        <!-- END Message -->
    </div>
</div>
