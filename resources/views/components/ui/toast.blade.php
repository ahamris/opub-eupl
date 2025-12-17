@props([
    'variant' => 'info',
    'title' => null,
    'message' => null,
    'icon' => null,
    'duration' => 5000,
    'dismissible' => true,
    'id' => null,
    'classes' => null,
    'iconClasses' => null,
    'iconName' => null,
])

<div
    id="{{ $id }}"
    x-data="{ show: true }"
    x-show="show"
    x-cloak
    x-transition:enter="ease-out duration-300 transform"
    x-transition:enter-start="opacity-0 translate-x-full"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="ease-in duration-200 transform"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-full"
    @if($duration > 0)
        x-init="setTimeout(() => { show = false; setTimeout(() => $el.remove(), 200); }, {{ $duration }})"
    @endif
    class="{{ $classes }}"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
>
    @if($iconName)
        <div class="shrink-0 mt-0.5">
            <i class="fa-solid fa-{{ $iconName }} {{ $iconClasses }} text-lg"></i>
        </div>
    @endif

    <div class="flex-1 min-w-0">
        @if($title)
            <div class="text-sm font-semibold mb-1">
                {{ $title }}
            </div>
        @endif
        
        @if($message)
            <div class="text-sm leading-5">
                {{ $message }}
            </div>
        @else
            <div class="text-sm leading-5">
                {{ $slot }}
            </div>
        @endif
    </div>

    @if($dismissible)
        <button
            type="button"
            @click="show = false; setTimeout(() => $el.closest('[id]').remove(), 200)"
            class="shrink-0 text-current/60 hover:text-current transition-colors ml-2"
            aria-label="Close toast"
        >
            <i class="fa-solid fa-times text-sm"></i>
        </button>
    @endif
</div>

