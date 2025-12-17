@once
    @push('styles')
        <style>
            /* Alert icon */
            .alert-icon {
                @apply shrink-0 mt-0.5;
            }

            /* Alert content */
            .alert-content {
                @apply flex-1 text-sm leading-5 tracking-[0.25px];
            }

            /* Alert title */
            .alert-title {
                @apply text-base leading-6 tracking-[0.15px] font-medium mb-1;
            }

            /* Alert message */
            .alert-message {
                @apply text-sm leading-5 tracking-[0.25px];
            }

            /* Alert content lists */
            .alert-content ul {
                @apply mb-0 mt-1.5 text-sm leading-5 tracking-[0.25px];
            }

            .alert-content li {
                @apply text-sm leading-5 tracking-[0.25px] mb-0.5;
            }
        </style>
    @endpush
@endonce

<div class="{{ $classes }}" {{ $attributes->except(['class']) }}>
    @if($icon)
        <div class="alert-icon">
            <i class="fa-solid fa-{{ $icon }} {{ $iconClasses }}"></i>
        </div>
    @endif

    <div class="alert-content">
        @if(isset($titleSlot))
            <div class="alert-title">{{ $titleSlot }}</div>
        @elseif($title)
            <div class="alert-title">{{ $title }}</div>
        @endif

        @if(isset($messageSlot))
            <div class="alert-message">{{ $messageSlot }}</div>
        @elseif($message)
            <div class="alert-message">{{ $message }}</div>
        @else
            <div class="alert-message">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>

