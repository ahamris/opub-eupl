@once
    @push('styles')
        <style>
            /* Input focus styles using CSS variables */
            .block.w-full.border.rounded-md:focus {
                @apply outline-none ring-1;
                border-color: var(--color-accent);
                --tw-ring-color: var(--color-accent);
            }

            .dark .block.w-full.border.rounded-md:focus {
                border-color: var(--color-accent-content);
                --tw-ring-color: var(--color-accent-content);
            }
        </style>
    @endpush
@endonce

<div>
    @if($label)
        <label for="{{ $inputId }}" class="{{ $error ? 'text-red-600 dark:text-red-400' : '' }}">
            {{ $label }}
            @if($required)
                <span class="text-red-600 dark:text-red-400">*</span>
            @endif
        </label>
    @endif

    <div class="relative {{ $label ? 'mt-2' : '' }}">
        @if($icon && $iconPosition === 'left')
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 pointer-events-none">
                <i class="fas fa-{{ $icon }}"></i>
            </div>
            <input
                    type="{{ $type }}"
                    name="{{ $name }}"
                    id="{{ $inputId }}"
                    value="{{ is_string($value) ? $value : '' }}"
                    placeholder="{{ $placeholder }}"
                    @if($required) required @endif
                    @if($disabled) disabled @endif
                    @if($readonly) readonly @endif
                    class="{{ $classes }}"
                    {{ $attributes->except(['class', 'value']) }}
            >
        @elseif($icon && $iconPosition === 'right')
            <input
                    type="{{ $type }}"
                    name="{{ $name }}"
                    id="{{ $inputId }}"
                    value="{{ is_string($value) ? $value : '' }}"
                    placeholder="{{ $placeholder }}"
                    @if($required) required @endif
                    @if($disabled) disabled @endif
                    @if($readonly) readonly @endif
                    class="{{ $classes }}"
                    {{ $attributes->except(['class', 'value']) }}
            >
            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 pointer-events-none">
                <i class="fas fa-{{ $icon }}"></i>
            </div>
        @else
            <input
                    type="{{ $type }}"
                    name="{{ $name }}"
                    id="{{ $inputId }}"
                    value="{{ is_string($value) ? $value : '' }}"
                    placeholder="{{ $placeholder }}"
                    @if($required) required @endif
                    @if($disabled) disabled @endif
                    @if($readonly) readonly @endif
                    class="{{ $classes }}"
                    {{ $attributes->except(['class', 'value']) }}
            >
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
