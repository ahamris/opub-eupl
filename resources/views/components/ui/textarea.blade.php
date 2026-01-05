@once
    @push('styles')
        <style>
            /* Textarea focus styles using CSS variables */
            .block.w-full.border.rounded-md[class*="resize-y"]:focus,
            .block.w-full.border.rounded-md[class*="resize-none"]:focus {
                @apply outline-none ring-1;
                border-color: var(--color-accent);
                --tw-ring-color: var(--color-accent);
            }

            .dark .block.w-full.border.rounded-md[class*="resize-y"]:focus,
            .dark .block.w-full.border.rounded-md[class*="resize-none"]:focus {
                border-color: var(--color-accent-content);
                --tw-ring-color: var(--color-accent-content);
            }
        </style>
    @endpush
@endonce

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $textareaId }}" class="{{ $error ? 'text-red-600 dark:text-red-400' : '' }}">
            {{ $label }}
            @if($required)
                <span class="text-red-600 dark:text-red-400">*</span>
            @endif
        </label>
    @endif

    <textarea 
        name="{{ $name }}" 
        id="{{ $textareaId }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($rows) rows="{{ $rows }}" @endif
        @if($cols) cols="{{ $cols }}" @endif
        class="{{ $classes }}"
        {{ $attributes->except(['class']) }}
    >{{ $value }}</textarea>

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
