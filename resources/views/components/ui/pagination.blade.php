@props([
    'paginator',
    'variant' => 'default',
    'wireModel' => null,
    'showInfo' => true,
    'onEachSide' => 2,
])

@php
    $hasWireModel = !empty($wireModel);
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $total = $paginator->total();
    $perPage = $paginator->perPage();
    $from = $paginator->firstItem() ?? 0;
    $to = $paginator->lastItem() ?? 0;
@endphp

@if($paginator->hasPages())
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-4 py-3 border-t border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
        @if($showInfo)
            <div class="text-sm text-zinc-700 dark:text-zinc-400">
                Showing <span class="font-medium">{{ $from }}</span> to <span class="font-medium">{{ $to }}</span> of <span class="font-medium">{{ $total }}</span> results
            </div>
        @else
            <div></div>
        @endif

        <div class="flex items-center gap-2">
            {{-- Previous Button --}}
            @if($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm text-zinc-400 dark:text-zinc-600 cursor-not-allowed rounded-md border border-zinc-200 dark:border-zinc-700">
                    <i class="fa-solid fa-chevron-left"></i>
                </span>
            @else
                @if($hasWireModel)
                    <button 
                        type="button"
                        wire:click="{{ $wireModel }}.previousPage('{{ $paginator->getPageName() }}')"
                        class="px-3 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 rounded-md border border-zinc-200 dark:border-zinc-700 transition-colors"
                    >
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                @else
                    <a 
                        href="{{ $paginator->previousPageUrl() }}"
                        class="px-3 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 rounded-md border border-zinc-200 dark:border-zinc-700 transition-colors"
                    >
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                @endif
            @endif

            {{-- Page Numbers --}}
            @if($variant === 'compact')
                <span class="px-3 py-2 text-sm text-zinc-700 dark:text-zinc-300">
                    Page {{ $currentPage }} of {{ $lastPage }}
                </span>
            @else
                {{-- First Page --}}
                @if($currentPage > $onEachSide + 1)
                    @if($hasWireModel)
                        <button 
                            type="button"
                            wire:click="{{ $wireModel }}.gotoPage(1, '{{ $paginator->getPageName() }}')"
                            class="px-3 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 rounded-md border border-zinc-200 dark:border-zinc-700 transition-colors"
                        >
                            1
                        </button>
                    @else
                        <a 
                            href="{{ $paginator->url(1) }}"
                            class="px-3 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 rounded-md border border-zinc-200 dark:border-zinc-700 transition-colors"
                        >
                            1
                        </a>
                    @endif
                    @if($currentPage > $onEachSide + 2)
                        <span class="px-3 py-2 text-sm text-zinc-400 dark:text-zinc-600">...</span>
                    @endif
                @endif

                {{-- Page Range --}}
                @for($page = max(1, $currentPage - $onEachSide); $page <= min($lastPage, $currentPage + $onEachSide); $page++)
                    @if($hasWireModel)
                        <button 
                            type="button"
                            wire:click="{{ $wireModel }}.gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                            class="px-3 py-2 text-sm rounded-md border transition-colors {{ $page === $currentPage ? 'bg-[var(--color-accent)] text-[var(--color-accent-foreground)] border-[var(--color-accent)]' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 border-zinc-200 dark:border-zinc-700' }}"
                        >
                            {{ $page }}
                        </button>
                    @else
                        <a 
                            href="{{ $paginator->url($page) }}"
                            class="px-3 py-2 text-sm rounded-md border transition-colors {{ $page === $currentPage ? 'bg-[var(--color-accent)] text-[var(--color-accent-foreground)] border-[var(--color-accent)]' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 border-zinc-200 dark:border-zinc-700' }}"
                        >
                            {{ $page }}
                        </a>
                    @endif
                @endfor

                {{-- Last Page --}}
                @if($currentPage < $lastPage - $onEachSide)
                    @if($currentPage < $lastPage - $onEachSide - 1)
                        <span class="px-3 py-2 text-sm text-zinc-400 dark:text-zinc-600">...</span>
                    @endif
                    @if($hasWireModel)
                        <button 
                            type="button"
                            wire:click="{{ $wireModel }}.gotoPage({{ $lastPage }}, '{{ $paginator->getPageName() }}')"
                            class="px-3 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 rounded-md border border-zinc-200 dark:border-zinc-700 transition-colors"
                        >
                            {{ $lastPage }}
                        </button>
                    @else
                        <a 
                            href="{{ $paginator->url($lastPage) }}"
                            class="px-3 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 rounded-md border border-zinc-200 dark:border-zinc-700 transition-colors"
                        >
                            {{ $lastPage }}
                        </a>
                    @endif
                @endif
            @endif

            {{-- Next Button --}}
            @if($paginator->hasMorePages())
                @if($hasWireModel)
                    <button 
                        type="button"
                        wire:click="{{ $wireModel }}.nextPage('{{ $paginator->getPageName() }}')"
                        class="px-3 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 rounded-md border border-zinc-200 dark:border-zinc-700 transition-colors"
                    >
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                @else
                    <a 
                        href="{{ $paginator->nextPageUrl() }}"
                        class="px-3 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 rounded-md border border-zinc-200 dark:border-zinc-700 transition-colors"
                    >
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                @endif
            @else
                <span class="px-3 py-2 text-sm text-zinc-400 dark:text-zinc-600 cursor-not-allowed rounded-md border border-zinc-200 dark:border-zinc-700">
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
            @endif
        </div>
    </div>
@endif

