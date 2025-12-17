@props([
    'size' => 'default',
    'closeable' => true,
    'closeOnBackdropClick' => true,
    'closeOnEscape' => true,
    'wireModel' => null,
    'alpineShow' => null,
    'modalId' => null,
    'maxWidthClass' => null,
    'blur' => false,
    'darker' => false,
])

@php
    $modalId = $modalId ?? 'modal-'.uniqid();
    $hasWireModel = !empty($wireModel);
    $hasAlpineShow = !empty($alpineShow);
    
    $fullscreenClass = $size === 'fullscreen' ? 'rounded-none' : 'rounded-lg';
    $paddingClass = $size === 'fullscreen' ? 'p-0' : 'p-6';
    $blurClass = $blur ? 'backdrop-blur-sm' : '';
    $darkerBgClass = $darker ? 'bg-black/70 dark:bg-black/85' : 'bg-black/10 dark:bg-black/20';
@endphp

@if($hasWireModel)
    {{-- Livewire Integration --}}
    <div
        wire:show="{{ $wireModel }}"
        x-cloak
        @if($closeOnEscape)
            @keydown.escape.window="$wire.set('{{ $wireModel }}', false)"
        @endif
        class="fixed inset-0 z-50 overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="{{ $modalId }}-title"
        x-data
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        {{-- Backdrop --}}
        <div
            wire:show="{{ $wireModel }}"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 {{ $darkerBgClass }} {{ $blurClass }}"
            @if($closeOnBackdropClick)
                @click="$wire.set('{{ $wireModel }}', false)"
            @endif
        ></div>

        {{-- Modal Panel --}}
        <div
            wire:show="{{ $wireModel }}"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none"
        >
            <div
                class="relative w-full {{ $maxWidthClass }} bg-white dark:bg-zinc-800 shadow-xl {{ $fullscreenClass }} {{ $paddingClass }} pointer-events-auto transform transition-all overflow-hidden"
                @click.stop
            >
                {{-- Header --}}
                @if(isset($header) || isset($title))
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div id="{{ $modalId }}-title" class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 break-words whitespace-normal text-left flex-1 min-w-0">
                            @if(isset($title))
                                {{ $title }}
                            @else
                                {{ $header }}
                            @endif
                        </div>
                        @if($closeable)
                            <button
                                type="button"
                                @click="$wire.set('{{ $wireModel }}', false)"
                                class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors flex-shrink-0"
                                aria-label="Close modal"
                            >
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        @endif
                    </div>
                @endif

                {{-- Body --}}
                @if(isset($body))
                    <div class="mb-6 text-left text-wrap break-words max-w-full">
                        {{ $body }}
                    </div>
                @else
                    <div class="mb-6 text-left text-wrap break-words max-w-full">
                        {{ $slot }}
                    </div>
                @endif

                {{-- Footer --}}
                @if(isset($footer))
                    <div class="flex items-center justify-end gap-3">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@else
    {{-- Alpine.js Integration --}}
    <div
        x-show="{{ $hasAlpineShow ? $alpineShow : 'false' }}"
        x-cloak
        @if($closeOnEscape)
            @if($hasAlpineShow)
                @keydown.escape.window="{{ $alpineShow }} = false"
            @endif
        @endif
        class="fixed inset-0 z-50 overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="{{ $modalId }}-title"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        {{-- Backdrop --}}
        <div
            x-show="{{ $hasAlpineShow ? $alpineShow : 'false' }}"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 {{ $darkerBgClass }} {{ $blurClass }}"
            @if($closeOnBackdropClick)
                @if($hasAlpineShow)
                    @click="{{ $alpineShow }} = false"
                @endif
            @endif
        ></div>

        {{-- Modal Panel --}}
        <div
            x-show="{{ $hasAlpineShow ? $alpineShow : 'false' }}"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none"
        >
            <div
                class="relative w-full {{ $maxWidthClass }} bg-white dark:bg-zinc-800 shadow-xl {{ $fullscreenClass }} {{ $paddingClass }} pointer-events-auto transform transition-all overflow-hidden"
                @click.stop
            >
                {{-- Header --}}
                @if(isset($header) || isset($title))
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div id="{{ $modalId }}-title" class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 break-words whitespace-normal text-left flex-1 min-w-0">
                            @if(isset($title))
                                {{ $title }}
                            @else
                                {{ $header }}
                            @endif
                        </div>
                        @if($closeable)
                            <button
                                type="button"
                                @if($hasAlpineShow)
                                    @click="{{ $alpineShow }} = false"
                                @else
                                    @click="$el.closest('[x-show]').style.display = 'none'"
                                @endif
                                class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors flex-shrink-0"
                                aria-label="Close modal"
                            >
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        @endif
                    </div>
                @endif

                {{-- Body --}}
                @if(isset($body))
                    <div class="mb-6 text-left text-wrap break-words max-w-full">
                        {{ $body }}
                    </div>
                @else
                    <div class="mb-6 text-left text-wrap break-words max-w-full">
                        {{ $slot }}
                    </div>
                @endif

                {{-- Footer --}}
                @if(isset($footer))
                    <div class="flex items-center justify-end gap-3">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
