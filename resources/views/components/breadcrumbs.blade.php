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
                    <a href="{{ $homeHref }}" class="flex items-center text-[var(--color-primary-dark)] hover:text-[var(--color-primary)]">
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
                        <i class="fas fa-angle-right text-[10px] leading-none shrink-0 text-[var(--color-on-surface-variant)]" aria-hidden="true"></i>

                        @if($href && ! $isCurrent)
                            <a href="{{ $href }}"
                               class="ml-4 text-sm font-medium leading-none text-[var(--color-primary-dark)] hover:text-[var(--color-primary)] flex items-center">
                                {{ $label }}
                            </a>
                        @else
                            <span @if($isCurrent) aria-current="page" @endif
                            class="ml-4 text-sm font-medium leading-none text-[var(--color-on-surface-variant)]">
                {{ $label }}
            </span>
                        @endif
                    </div>
                </li>
            @endforeach
        </ol>
    </nav>
@endif


