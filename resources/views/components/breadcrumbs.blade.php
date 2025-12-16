@props([
    /**
     * Breadcrumb items.
     * Each item: ['label' => string, 'href' => string|null, 'current' => bool|null]
     * First item is treated as "home" icon.
     */
    'items' => [],
])

@if(!empty($items) && count($items) > 0)
    <nav class="flex" aria-label="Breadcrumb">
        <ol role="list" class="flex items-center space-x-4">
            @php
                $home = $items[0];
                $rest = array_slice($items, 1);
                $homeHref = $home['href'] ?? route('home');
                $homeLabel = $home['label'] ?? 'Home';
            @endphp

            {{-- Home item with icon --}}
            <li>
                <div>
                    <a href="{{ $homeHref }}" class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-300">
                        <i class="fas fa-house text-sm shrink-0" aria-hidden="true"></i>
                        <span class="sr-only">{{ $homeLabel }}</span>
                    </a>
                </div>
            </li>

            {{-- Remaining items --}}
            @foreach($rest as $item)
                @php
                    $isCurrent = $item['current'] ?? false;
                    $href = $item['href'] ?? null;
                    $label = $item['label'] ?? '';
                @endphp
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-angle-right text-xs shrink-0 text-gray-400 dark:text-gray-500" aria-hidden="true"></i>

                        @if($href && ! $isCurrent)
                            <a href="{{ $href }}"
                               class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                {{ $label }}
                            </a>
                        @else
                            <span @if($isCurrent) aria-current="page" @endif
                                  class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ $label }}
                            </span>
                        @endif
                    </div>
                </li>
            @endforeach
        </ol>
    </nav>
@endif


