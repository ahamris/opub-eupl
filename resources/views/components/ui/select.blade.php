@once
    @push('styles')
        <style>
            /* Select focus styles using CSS variables */
            select.block.w-full.border.rounded-md:focus {
                @apply outline-none ring-1;
                border-color: var(--color-accent);
                --tw-ring-color: var(--color-accent);
            }

            .dark select.block.w-full.border.rounded-md:focus {
                border-color: var(--color-accent-content);
                --tw-ring-color: var(--color-accent-content);
            }

            /* Select arrow icon */
            .select-wrapper::after {
                content: '';
                position: absolute;
                right: 1rem;
                top: 50%;
                transform: translateY(-50%);
                width: 0;
                height: 0;
                border-left: 5px solid transparent;
                border-right: 5px solid transparent;
                border-top: 6px solid rgb(113 113 122); /* zinc-500 */
                pointer-events: none;
            }

            .dark .select-wrapper::after {
                border-top-color: rgb(161 161 170); /* zinc-400 */
            }

            .select-wrapper:has(select:disabled)::after {
                border-top-color: rgb(161 161 170); /* zinc-400 */
            }
        </style>
    @endpush
@endonce

<div>
    @if($label)
        <label for="{{ $selectId }}" class="{{ $error ? 'text-red-600 dark:text-red-400' : '' }}">
            {{ $label }}
            @if($required)
                <span class="text-red-600 dark:text-red-400">*</span>
            @endif
        </label>
    @endif

    <div class="select-wrapper relative {{ $label ? 'mt-2' : '' }}">
        <select
                name="{{ $name }}"
                id="{{ $selectId }}"
                @if($required) required @endif
                @if($disabled) disabled @endif
                class="{{ $classes }} pr-10"
                {{ $attributes->except(['class']) }}
        >
            @if($placeholder)
                <option value="" disabled {{ $value === '' ? 'selected' : '' }}>
                    {{ $placeholder }}
                </option>
            @endif

            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ $value == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach

            {{ $slot }}
        </select>
    </div>

    @if($hint && !$error && !$errorMessage)
        <div class="text-xs leading-4 tracking-[0.4px] text-gray-600 dark:text-gray-400 mt-1.5">{{ $hint }}</div>
    @endif

    @if($error && $errorMessage)
        <div class="text-xs leading-4 tracking-[0.4px] text-red-600 dark:text-red-400 flex items-center gap-1 mt-1.5">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errorMessage }}
        </div>
    @elseif($autoError && $name)
        @error($name)
            <div class="text-xs leading-4 tracking-[0.4px] text-red-600 dark:text-red-400 flex items-center gap-1 mt-1.5">
                <i class="fas fa-exclamation-circle"></i>
                {{ $message }}
            </div>
        @enderror
    @endif
</div>