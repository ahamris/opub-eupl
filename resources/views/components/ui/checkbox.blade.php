<div class="flex items-center">
    <label class="{{ $labelClasses }}">
        <input
            type="checkbox"
            name="{{ $name }}"
            value="{{ $value ?? '1' }}"
            id="{{ $checkboxId }}"
            class="{{ $inputClasses }}"
            @if($checked) checked @endif
            @if($disabled) disabled @endif
            @if($required) required @endif
            {{ $attributes->except(['class']) }}
        >
        @if($label)
            <span class="text-sm leading-5 tracking-[0.25px] text-gray-900 dark:text-gray-200">{{ $label }}</span>
        @endif
    </label>
</div>
