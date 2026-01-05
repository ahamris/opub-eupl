@props([
    'title',
    'description' => null,
    'actionLabel' => null,
    'actionHref' => null,
    'actionIcon' => null,
    'actionIconPosition' => 'left',
    'actionVariant' => 'primary',
    'backHref' => null,
    'backLabel' => null,
])

<div class="flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">{{ $title }}</h1>
        @if($description)
            <p class="text-zinc-600 dark:text-zinc-400">{{ $description }}</p>
        @endif
    </div>
    
    <div class="flex items-center gap-3">
        @if($backHref)
            <x-button 
                variant="secondary" 
                icon="arrow-left" 
                icon-position="left" 
                href="{{ $backHref }}"
            >
                {{ $backLabel ?? 'Back' }}
            </x-button>
        @endif
        
        @if($actionLabel && $actionHref)
            <x-button 
                variant="{{ $actionVariant }}" 
                icon="{{ $actionIcon }}" 
                icon-position="{{ $actionIconPosition }}" 
                href="{{ $actionHref }}"
            >
                {{ $actionLabel }}
            </x-button>
        @elseif(isset($actionSlot))
            {{ $actionSlot }}
        @endif
    </div>
</div>
