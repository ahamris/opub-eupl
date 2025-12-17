<div
    x-data="{ 
        showResults: false
    }"
    @click.away="showResults = false"
    class="relative w-full"
>
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fa-solid fa-search text-zinc-400 dark:text-zinc-500"></i>
        </div>
        <input
            type="text"
            wire:model.live.debounce.300ms="query"
            x-on:focus="showResults = true"
            x-on:keydown.escape="showResults = false; $wire.set('query', '')"
            x-on:keydown.ctrl.k.prevent="showResults = !showResults"
            x-on:keydown.cmd.k.prevent="showResults = !showResults"
            class="flex items-center gap-2 w-full pl-10 pr-20 py-1.5 text-sm text-[var(--color-zinc-800)] dark:text-[var(--color-zinc-400)] bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 border border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600 rounded-md transition-colors focus:outline-none"
            placeholder="Search..."
            autocomplete="off"
        />
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center gap-2">
            @if(!empty($query))
                <button
                    type="button"
                    wire:click="$set('query', '')"
                    x-on:click.stop
                    class="inline-flex items-center justify-center w-4 h-4 text-zinc-400 dark:text-zinc-500 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors"
                    aria-label="Clear search"
                >
                    <i class="fa-solid fa-times text-xs"></i>
                </button>
            @endif
            <kbd class="hidden lg:inline-flex items-center px-2 py-1 text-xs font-semibold text-zinc-600 dark:text-zinc-300 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 rounded">⌘K</kbd>
        </div>
    </div>
    
    <!-- Dropdown Results -->
    <div 
        x-show="showResults && !empty($wire.query)"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 w-full mt-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg max-h-[28rem] overflow-y-auto"
    >
        <div>
            @if(empty($query))
                <div class="px-4 py-8 text-center">
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Start typing to search...</p>
                </div>
            @elseif(empty($this->results))
                <div class="px-4 py-8 text-center">
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">No results found</p>
                </div>
            @else
                <div class="py-1">
                    @foreach($this->results as $group)
                        <div class="mb-2">
                            <div class="px-4 py-2 text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
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
                                        class="flex items-center px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors cursor-pointer group focus:outline-none focus:bg-zinc-50 dark:focus:bg-zinc-700/50"
                                        x-on:click="showResults = false"
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

