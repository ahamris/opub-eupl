<span class="{{ $classes }} {{ $slot->isNotEmpty() ? '' : 'badge-icon-only' }}" {{ $attributes->except(['class']) }}>
    @if($icon)
        <i class="fa-solid fa-{{ $icon }} {{ $slot->isNotEmpty() ? 'mr-1' : '' }} {{ $iconClasses }}"></i>
    @endif

    @if($slot->isNotEmpty())
        {{ $slot }}
    @endif
</span>
