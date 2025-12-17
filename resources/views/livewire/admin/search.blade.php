<div
        x-data="{
        isOpen: $wire.entangle('isOpen'),
        query: $wire.entangle('query')
    }"
        x-on:keydown.escape.window="if(isOpen) { isOpen = false; $wire.close(); }"
        x-on:keydown.ctrl.k.window.prevent="isOpen = !isOpen; if(isOpen) { $wire.open(); $nextTick(() => { setTimeout(() => { $refs.searchInput?.focus(); $refs.searchInput?.select(); }, 250); }); } else { $wire.close(); }"
        x-on:keydown.cmd.k.window.prevent="isOpen = !isOpen; if(isOpen) { $wire.open(); $nextTick(() => { setTimeout(() => { $refs.searchInput?.focus(); $refs.searchInput?.select(); }, 250); }); } else { $wire.close(); }"
        x-on:open-search.window="isOpen = true; $wire.open(); $nextTick(() => { setTimeout(() => { $refs.searchInput?.focus(); $refs.searchInput?.select(); }, 250); });"
        x-show="isOpen"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
>
    {{-- Backdrop --}}
    <div
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm"
            @click="isOpen = false; $wire.close()"
    ></div>

    {{-- Modal --}}
    <div
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="flex min-h-full items-center justify-center p-4"
            @click.away="isOpen = false; $wire.close()"
    >
        <div
                class="relative w-full max-w-2xl transform overflow-hidden rounded-xl bg-white dark:bg-zinc-800 shadow-2xl ring-1 ring-zinc-200 dark:ring-zinc-700 transition-all"
                @click.stop
        >
            {{-- Search Input --}}
            <div class="border-b border-zinc-200 dark:border-zinc-700 px-5 py-2.5">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-base text-zinc-400 dark:text-zinc-500"></i>
                    </div>
                    <input
                            type="text"
                            wire:model.live.debounce.300ms="query"
                            x-ref="searchInput"
                            x-on:click.stop
                            class="block w-full pl-8 pr-20 py-2.5 border-0 bg-transparent text-base text-zinc-900 dark:text-zinc-100 placeholder:text-zinc-400 dark:placeholder:text-zinc-500 focus:outline-none focus:ring-0 font-normal"
                            placeholder="Search menus, pages..."
                            autocomplete="off"
                            x-on:keydown.escape.window="isOpen = false; $wire.close()"
                            x-on:keydown.arrow-down.prevent="$focus.focusable().next()?.focus()"
                            x-on:keydown.arrow-up.prevent="$focus.focusable().previous()?.focus()"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center gap-2 pr-5">
                        @if(!empty($query))
                            <button
                                    type="button"
                                    wire:click="$set('query', '')"
                                    x-on:click.stop
                                    class="inline-flex items-center justify-center w-5 h-5 text-zinc-400 dark:text-zinc-500 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors rounded"
                                    aria-label="Clear search"
                            >
                                <i class="fa-solid fa-times text-xs"></i>
                            </button>
                        @endif
                        <kbd class="hidden md:inline-flex items-center px-2 py-1 text-[10px] font-medium text-zinc-500 dark:text-zinc-400 bg-zinc-100 dark:bg-zinc-700/50 border border-zinc-200 dark:border-zinc-600 rounded shadow-sm">ESC</kbd>
                    </div>
                </div>
            </div>

            {{-- Results --}}
            <div class="max-h-[28rem] min-h-[20rem] overflow-y-auto relative">
                {{-- Content --}}
                <div class="h-full">
                    @if(empty($query))
                        {{-- Empty State --}}
                        <div class="h-full flex items-center justify-center px-6 py-10">
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-zinc-100 dark:bg-zinc-700/50 mb-4">
                                    <i class="fa-solid fa-search text-2xl text-zinc-400 dark:text-zinc-500"></i>
                                </div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-5">Start typing to search across your admin panel</p>
                                <div class="flex items-center justify-center gap-2 text-sm text-zinc-500 dark:text-zinc-400 mb-3">
                                    <span>Press</span>
                                    <kbd class="inline-flex items-center px-2 py-1 text-xs font-medium text-zinc-600 dark:text-zinc-300 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 rounded shadow-sm">Ctrl+K</kbd>
                                    <span>or</span>
                                    <kbd class="inline-flex items-center px-2 py-1 text-xs font-medium text-zinc-600 dark:text-zinc-300 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 rounded shadow-sm">Cmd+K</kbd>
                                    <span>to open</span>
                                </div>
                                <div class="flex items-center justify-center gap-2 text-sm text-zinc-500 dark:text-zinc-400">
                                    <span>Press</span>
                                    <kbd class="inline-flex items-center px-2 py-1 text-xs font-medium text-zinc-600 dark:text-zinc-300 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 rounded shadow-sm">ESC</kbd>
                                    <span>to close</span>
                                </div>
                            </div>
                        </div>
                    @elseif(empty($this->results))
                        {{-- No Results --}}
                        <div class="h-full flex items-center justify-center px-6 py-10">
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-zinc-100 dark:bg-zinc-700/50 mb-4">
                                    <i class="fa-solid fa-search-minus text-2xl text-zinc-400 dark:text-zinc-500"></i>
                                </div>
                                <p class="text-base font-medium text-zinc-900 dark:text-zinc-100 mb-2">No results found</p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Try searching for something else</p>
                            </div>
                        </div>
                    @else
                        {{-- Results List --}}
                        <div class="py-1">
                            @foreach($this->results as $group)
                                <div class="mb-2">
                                    <div class="px-5 py-2 text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        {{ $group['title'] }}
                                    </div>
                                    <div class="space-y-0">
                                        @foreach($group['items'] as $item)
                                            @php
                                                $href = '#';
                                                if (isset($item['route'])) {
                                                    try {
                                                        $href = route($item['route']);
                                                    } catch (\Illuminate\Routing\Exceptions\UrlGenerationException $e) {
                                                        $href = $item['url'] ?? '#';
                                                    }
                                                } else {
                                                    $href = $item['url'] ?? '#';
                                                }
                                            @endphp
                                            <a
                                                    href="{{ $href }}"
                                                    wire:key="result-{{ $group['type'] }}-{{ $loop->index }}"
                                                    class="flex items-center px-5 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors cursor-pointer group focus:outline-none focus:bg-zinc-50 dark:focus:bg-zinc-700/50"
                                                    @click="isOpen = false; $wire.close()"
                                            >
                                                @if(isset($item['icon']))
                                                    <div class="flex-shrink-0 w-5 h-5 mr-3 flex items-center justify-center">
                                                        <i class="fa-solid fa-{{ $item['icon'] }} text-base text-zinc-500 dark:text-zinc-400 group-hover:text-[var(--color-accent)] dark:group-hover:text-[var(--color-accent-content)] transition-colors"></i>
                                                    </div>
                                                @else
                                                    <div class="flex-shrink-0 w-4 h-4 mr-3 flex items-center justify-center">
                                                        <i class="fa-solid fa-circle text-[8px] text-zinc-400 dark:text-zinc-500"></i>
                                                    </div>
                                                @endif
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-normal text-zinc-900 dark:text-zinc-100 group-hover:text-[var(--color-accent)] dark:group-hover:text-[var(--color-accent-content)] transition-colors leading-5">
                                                        {{ $item['title'] }}
                                                    </div>
                                                </div>
                                                <i class="fa-solid fa-chevron-right text-[10px] text-zinc-400 dark:text-zinc-500 group-hover:text-[var(--color-accent)] dark:group-hover:text-[var(--color-accent-content)] transition-colors ml-3 flex-shrink-0"></i>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>