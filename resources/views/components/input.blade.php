@props([
    'type' => 'text',
    'name' => '',
    'id' => null,
    'label' => null,
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'autocomplete' => null,
    'leadingIcon' => null,
    'trailingIcon' => null,
    'error' => null,
])

@php
    $id = $id ?? $name;
    $inputId = $id ?: uniqid('input-');
    $hasIcon = $leadingIcon || $trailingIcon;
@endphp

<div>
    @if($label)
    <label for="{{ $inputId }}" class="block text-sm/6 font-medium text-gray-900 dark:text-white">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    @endif
    
    <div class="@if($label) mt-2 @endif @if($hasIcon) relative @endif">
        @if($leadingIcon)
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 z-10">
            <i class="{{ $leadingIcon }} text-[var(--color-on-surface-variant)] dark:text-[var(--color-on-surface-variant)] text-sm" aria-hidden="true"></i>
        </div>
        @endif
        
        <input 
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $inputId }}"
            @if($value) value="{{ $value }}" @endif
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($required) required @endif
            @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            @if($error) aria-invalid="true" aria-describedby="{{ $inputId }}-error" @endif
            {{ $attributes->merge([
                'class' => 'block w-full rounded-md bg-[var(--color-surface)] px-3 py-2 text-base text-[var(--color-on-surface)] border border-[var(--color-outline-variant)] placeholder:text-[var(--color-on-surface-variant)] focus:outline-none focus:border-[var(--color-primary)] sm:text-sm/6 dark:bg-white/5 dark:text-[var(--color-on-surface)] dark:border-white/10 dark:placeholder:text-[var(--color-on-surface-variant)] dark:focus:border-[var(--color-primary)]' . 
                ($leadingIcon ? ' pl-10' : '') . 
                ($trailingIcon ? ' pr-10' : '') .
                ($error ? ' border-red-300 focus:border-red-500' : '')
            ])->except(['label', 'leadingIcon', 'trailingIcon', 'error', 'type', 'name', 'id', 'value', 'placeholder', 'required', 'autocomplete']) }}
        />
        
        @if($trailingIcon)
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 z-10">
            <i class="{{ $trailingIcon }} text-[var(--color-on-surface-variant)] dark:text-[var(--color-on-surface-variant)] text-sm" aria-hidden="true"></i>
        </div>
        @endif
    </div>
    
    @if($error)
    <p id="{{ $inputId }}-error" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>

