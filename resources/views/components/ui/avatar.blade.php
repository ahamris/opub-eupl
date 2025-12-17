<div class="{{ $classes }}" {{ $attributes }}>
    @if($src)
        <img 
            src="{{ $src }}" 
            alt="{{ $alt ?? $name ?? 'Avatar' }}"
            class="w-full h-full object-cover {{ $shape === 'square' ? 'rounded-md' : 'rounded-full' }}"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
        />
    @endif
    
    <div 
        class="w-full h-full {{ $shape === 'square' ? 'rounded-md' : 'rounded-full' }} flex items-center justify-center {{ $src ? 'hidden' : '' }} bg-[var(--color-accent)]"
    >
        @if($icon)
            <i class="fa-solid fa-{{ $icon }}"></i>
        @elseif($initials)
            <span>{{ $initials }}</span>
        @else
            <i class="fa-solid fa-user"></i>
        @endif
    </div>
    
    @if($status)
        <span class="absolute bottom-0 right-0 {{ $shape === 'square' ? 'rounded-sm' : 'rounded-full' }} border-2 border-white dark:border-zinc-800 
            {{ match($size) {
                'sm' => 'w-2.5 h-2.5',
                'lg' => 'w-3.5 h-3.5',
                'xl' => 'w-4 h-4',
                default => 'w-3 h-3',
            } }}
            {{ match($status) {
                'online' => 'bg-green-500',
                'offline' => 'bg-zinc-400',
                'away' => 'bg-yellow-500',
                default => 'bg-zinc-400',
            } }}"
        ></span>
    @endif
</div>

