@once
    @push('styles')
        <style>
            /* Toggle Switch - Using Tailwind spacing/color values */
            /* w-10=2.5rem, h-6=1.5rem, w-8=2rem, h-5=1.25rem, w-12=3rem, h-7=1.75rem */
            /* w-4=1rem, w-3=0.75rem, w-5=1.25rem */
            /* left-1=0.25rem, left-0.5=0.125rem, left-1.5=0.375rem */
            /* translate-x-4=1rem, translate-x-3=0.75rem, translate-x-5=1.25rem */
            /* bg-zinc-300=rgb(212 212 216), border-zinc-400=rgb(161 161 170) */
            /* dark:bg-zinc-700=rgb(63 63 70), dark:border-zinc-600=rgb(82 82 91) */
            
            .toggle-switch::before {
                content: '';
                display: block;
                width: 2.5rem;
                height: 1.5rem;
                border-radius: 9999px;
                background-color: rgb(212 212 216);
                border: 1px solid rgb(161 161 170);
                transition: all 200ms;
            }
            
            .dark .toggle-switch::before {
                background-color: rgb(63 63 70);
                border-color: rgb(82 82 91);
            }
            
            .toggle-switch::after {
                content: '';
                position: absolute;
                left: 0.25rem;
                top: 50%;
                transform: translateY(-50%);
                width: 1rem;
                height: 1rem;
                border-radius: 9999px;
                background-color: white;
                transition: transform 200ms;
                box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            }
            
            .toggle-switch:checked::before {
                background-color: var(--color-accent);
                border-color: var(--color-accent);
            }
            
            .dark .toggle-switch:checked::before {
                background-color: var(--color-accent-content);
                border-color: var(--color-accent-content);
            }
            
            .toggle-switch:checked::after {
                transform: translateX(1rem) translateY(-50%);
            }
            
            /* Small size */
            .toggle-switch-sm::before {
                width: 2rem;
                height: 1.25rem;
            }
            
            .toggle-switch-sm::after {
                left: 0.125rem;
                top: 50%;
                transform: translateY(-50%);
                width: 0.75rem;
                height: 0.75rem;
            }
            
            .toggle-switch-sm:checked::after {
                transform: translateX(0.75rem) translateY(-50%);
            }
            
            /* Large size */
            .toggle-switch-lg::before {
                width: 3rem;
                height: 1.75rem;
            }
            
            .toggle-switch-lg::after {
                left: 0.375rem;
                top: 50%;
                transform: translateY(-50%);
                width: 1.25rem;
                height: 1.25rem;
            }
            
            .toggle-switch-lg:checked::after {
                transform: translateX(1.25rem) translateY(-50%);
            }
        </style>
    @endpush
@endonce

<div class="flex items-center">
    <label class="{{ $labelClasses }}">
        <input
            type="checkbox"
            name="{{ $name }}"
            value="{{ $value }}"
            id="{{ $toggleId }}"
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

