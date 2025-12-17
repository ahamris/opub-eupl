@php
    // Component class'ından gelen property'ler render() metodunda geçirilir
    $visibleCrumbs = $visibleCrumbs ?? [];
    $hiddenCrumbs = $hiddenCrumbs ?? [];
    $shouldTruncate = $shouldTruncate ?? false;
    $separator = $separator ?? '›';
    
    // Eğer visibleCrumbs boşsa, en azından Dashboard'u göster
    if (empty($visibleCrumbs)) {
        $dashboardUrl = url('/');
        $visibleCrumbs = [['label' => 'Dashboard', 'url' => $dashboardUrl]];
    }
    
    $lastIndex = count($visibleCrumbs) > 0 ? count($visibleCrumbs) - 1 : 0;
    $homeUrl = $visibleCrumbs[0]['url'] ?? url('/');
    
    // Separator rendering
    $separatorHtml = '';
    // Check if separator is a FontAwesome icon name (contains dash and common icon patterns)
    if (str_contains($separator, '-') && !in_array($separator, ['›', '→', '>', '/', '|'])) {
        // FontAwesome icon (e.g., 'chevron-right', 'angle-right', 'arrow-right')
        $separatorHtml = '<i class="fa-solid fa-'.$separator.'"></i>';
    } else {
        // Text separator (e.g., '›', '→', '/', '|')
        $separatorHtml = htmlspecialchars($separator);
    }
@endphp

<nav aria-label="Breadcrumb" class="flex items-center gap-1 md:gap-2 text-sm min-w-0 overflow-hidden">
    <!-- Home Icon -->
    <a href="{{ $homeUrl }}" class="text-zinc-700 hover:text-[var(--color-accent)] dark:text-zinc-400 dark:hover:text-[var(--color-accent-content)] transition-colors flex-shrink-0" title="Dashboard">
        <i class="fa-solid fa-house"></i>
    </a>
    
    @if(count($visibleCrumbs) > 1)
        <span class="text-zinc-500 dark:text-zinc-500 flex-shrink-0">{!! $separatorHtml !!}</span>
    @endif

    @if($shouldTruncate && !empty($hiddenCrumbs))
        <!-- Truncation dropdown -->
        <div class="relative inline-block" x-data="{ open: false }" @click.outside="open = false">
            <button 
                type="button"
                @click="open = !open"
                class="text-zinc-500 hover:text-[var(--color-accent)] dark:text-zinc-400 dark:hover:text-[var(--color-accent-content)] transition-colors px-1"
                aria-label="Show more breadcrumbs"
                aria-expanded="false"
                x-bind:aria-expanded="open"
                x-ref="button"
            >
                <span>...</span>
            </button>
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                style="display: none;"
                class="z-[9999] w-56 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-lg overflow-hidden"
                x-effect="
                    if (open) {
                        const rect = $refs.button.getBoundingClientRect();
                        $el.style.position = 'fixed';
                        $el.style.left = rect.left + 'px';
                        $el.style.top = (rect.bottom + 4) + 'px';
                    }
                "
            >
                <div class="py-1">
                    @foreach($hiddenCrumbs as $crumb)
                        <a 
                            href="{{ $crumb['url'] ?? '#' }}" 
                            class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-700 transition-colors"
                        >
                            {{ $crumb['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        
        <span class="text-zinc-500 dark:text-zinc-500 flex-shrink-0">{!! $separatorHtml !!}</span>
        
        <!-- Last 2 items (skip Dashboard which is at index 0) -->
        @foreach($visibleCrumbs as $i => $crumb)
            @continue($i === 0) {{-- Skip first (Dashboard) --}}
            @if($i > 1)
                <span class="text-zinc-500 dark:text-zinc-500 flex-shrink-0">{!! $separatorHtml !!}</span>
            @endif
            @php
                $isLast = $i === count($visibleCrumbs) - 1;
            @endphp
            @if(!empty($crumb['url']) && !$isLast)
                <a href="{{ $crumb['url'] }}" class="text-zinc-700 hover:text-[var(--color-accent)] dark:text-zinc-400 dark:hover:text-[var(--color-accent-content)] transition-colors truncate min-w-0">
                    <span class="truncate block">{{ $crumb['label'] }}</span>
                </a>
            @else
                <span class="font-medium text-zinc-900 dark:text-zinc-200 truncate min-w-0 block">{{ $crumb['label'] }}</span>
            @endif
        @endforeach
    @else
        <!-- Normal display without truncation -->
        @if(count($visibleCrumbs) > 1)
        @foreach($visibleCrumbs as $i => $crumb)
            @continue($i === 0) {{-- Skip first (Dashboard) --}}
            @if($i > 1)
                    <span class="text-zinc-500 dark:text-zinc-500 flex-shrink-0">{!! $separatorHtml !!}</span>
            @endif
            @if(!empty($crumb['url']) && $i !== $lastIndex)
                    <a href="{{ $crumb['url'] }}" class="text-zinc-700 hover:text-[var(--color-accent)] dark:text-zinc-400 dark:hover:text-[var(--color-accent-content)] transition-colors truncate min-w-0">
                        <span class="truncate block">{{ $crumb['label'] }}</span>
                </a>
            @else
                    <span class="font-medium text-zinc-900 dark:text-zinc-200 truncate min-w-0 block">{{ $crumb['label'] }}</span>
            @endif
        @endforeach
        @endif
    @endif
</nav>
