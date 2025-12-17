@props([
    'text' => null,
    'position' => 'top',
    'trigger' => 'hover',
    'delay' => 200,
    'maxWidth' => null,
    'tooltipId' => null,
])

@php
    $tooltipId = $tooltipId ?? 'tooltip-'.uniqid();
    $tooltipContent = $text ?? null;
    
    // Position classes
    $positionClasses = [
        'top' => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
        'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
        'left' => 'right-full top-1/2 -translate-y-1/2 mr-2',
        'right' => 'left-full top-1/2 -translate-y-1/2 ml-2',
    ];
    
    $arrowClasses = [
        'top' => 'top-full left-1/2 -translate-x-1/2 border-t-zinc-900 dark:border-t-zinc-100 border-x-transparent border-y-transparent',
        'bottom' => 'bottom-full left-1/2 -translate-x-1/2 border-b-zinc-900 dark:border-b-zinc-100 border-x-transparent border-y-transparent',
        'left' => 'left-full top-1/2 -translate-y-1/2 border-l-zinc-900 dark:border-l-zinc-100 border-x-transparent border-y-transparent',
        'right' => 'right-full top-1/2 -translate-y-1/2 border-r-zinc-900 dark:border-r-zinc-100 border-x-transparent border-y-transparent',
    ];
    
    $positionClass = $positionClasses[$position] ?? $positionClasses['top'];
    $arrowClass = $arrowClasses[$position] ?? $arrowClasses['top'];
    
    $isClickTrigger = $trigger === 'click';
    $delayMs = $delay;
@endphp

<div 
    class="relative inline-flex self-start"
    x-data="{ 
        show: false,
        timeout: null,
        hideTimeout: null,
        trigger: @js($isClickTrigger),
        delay: @js($delayMs),
        showTooltip() {
            clearTimeout(this.timeout);
            clearTimeout(this.hideTimeout);
            this.timeout = setTimeout(() => {
                this.show = true;
            }, this.delay);
        },
        hideTooltip() {
            clearTimeout(this.timeout);
            clearTimeout(this.hideTimeout);
            this.hideTimeout = setTimeout(() => {
                this.show = false;
            }, 100);
        }
    }"
    @if($isClickTrigger)
        @click="showTooltip()"
        @click.outside="hideTooltip()"
    @else
        @mouseenter="showTooltip()"
        @mouseleave="hideTooltip()"
    @endif
    {{ $attributes->except(['class']) }}
>
    {{-- Trigger Element --}}
    <div class="cursor-pointer">
        {{ $slot }}
    </div>

    {{-- Tooltip --}}
    @if($tooltipContent)
        <div
            x-show="show"
            x-cloak
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 {{ $positionClass }} px-3 py-2 text-xs font-medium text-white dark:text-zinc-900 bg-zinc-900 dark:bg-zinc-100 rounded-md shadow-lg whitespace-nowrap pointer-events-none"
            @if($maxWidth)
                style="max-width: {{ $maxWidth }}; white-space: normal;"
            @endif
            role="tooltip"
            id="{{ $tooltipId }}"
        >
            {{ $tooltipContent }}
            
            {{-- Arrow --}}
            <div
                class="absolute {{ $arrowClass }} border-4"
            ></div>
        </div>
    @endif
</div>

