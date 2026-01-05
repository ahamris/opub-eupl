@props([
    'id',
    'title' => null,
    'width' => 'max-w-md',
    'withBackdrop' => true,
    'closeOnBackdropClick' => true,
])

<div 
    x-data="{ open: false }"
    x-on:open-drawer.window="if ($event.detail.id === '{{ $id }}') open = true"
    x-on:close-drawer.window="if ($event.detail.id === '{{ $id }}') open = false"
    x-on:keydown.esc.window="if (open) open = false"
    class="relative z-50"
    aria-labelledby="{{ $id }}-title"
    role="dialog"
    aria-modal="true"
>
    <!-- Backdrop -->
    @if($withBackdrop)
        <div 
            x-show="open"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @if($closeOnBackdropClick)
                @click="open = false"
            @endif
            class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/50"
            x-cloak
            style="display: none;"
        ></div>
    @endif

    <!-- Drawer Panel -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 z-50 flex {{ $width }}"
        x-cloak
        style="display: none;"
    >
        <div class="relative flex w-full flex-col overflow-y-auto bg-white shadow-xl dark:bg-zinc-800 dark:after:absolute dark:after:inset-y-0 dark:after:left-0 dark:after:w-px dark:after:bg-white/10">
            <!-- Header -->
            @if($title)
                <div class="px-4 py-6 sm:px-6 border-b border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-start justify-between">
                        <h2 id="{{ $id }}-title" class="text-base font-semibold text-zinc-900 dark:text-white">
                            {{ $title }}
                        </h2>
                        <div class="ml-3 flex h-7 items-center">
                            <button 
                                type="button"
                                x-on:click="open = false"
                                class="relative rounded-md text-zinc-400 hover:text-zinc-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:hover:text-white dark:focus-visible:outline-indigo-500"
                            >
                                <span class="absolute -inset-2.5"></span>
                                <span class="sr-only">Close panel</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-6" aria-hidden="true">
                                    <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Content -->
            <div class="relative flex-1 px-4 py-6 sm:px-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
