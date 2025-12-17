@props([
    'title' => null,
    'icon' => null,
    'href' => null,
    'route' => null,
    'active' => false,
    'badge' => null,
    'badgeColor' => 'primary',
    'expanded' => false
])

@php
    use App\Models\Admin\AdminThemeSetting;
    
    $url = $href ?? ($route ? route($route) : '#');
    $hasChildren = $slot->isNotEmpty();
    if ($hasChildren) {
        $baseSlug = \Illuminate\Support\Str::slug($title ?? 'submenu');
        $submenuId = 'nav-submenu-' . ($baseSlug !== '' ? $baseSlug : uniqid());
    } else {
        $submenuId = null;
    }
    
    $linkClasses = [
        'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200',
        'focus:outline-none'
    ];

    // Active state - tema rengine göre text rengi belirle
    $settings = AdminThemeSetting::getSettings();
    $accentColor = $settings->accent_color;
    $baseColor = $settings->base_color;
    
    if($active) {
        
        $linkClasses[] = 'bg-[var(--color-accent)] dark:bg-[var(--color-accent)]';

        if ($accentColor === 'base') {

            $darkTextColor = match($baseColor) {
                'slate' => 'text-white dark:text-slate-50',
                'gray' => 'text-white dark:text-gray-50',
                'zinc' => 'text-white dark:text-zinc-50',
                'neutral' => 'text-white dark:text-neutral-50',
                'stone' => 'text-white dark:text-stone-50',
                default => 'text-white dark:text-zinc-50',
            };
            $linkClasses[] = $darkTextColor;
        } else {
            $textColorClass = match($accentColor) {
                'slate' => 'text-white dark:text-slate-800',
                'gray' => 'text-white dark:text-gray-800',
                'zinc' => 'text-zinc-50 dark:text-zinc-800',
                'neutral' => 'text-white dark:text-neutral-800',
                'stone' => 'text-white dark:text-stone-800',
                'red', 'green', 'emerald', 'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose' => 'text-white',
                'orange' => 'text-orange-50',
                'amber' => 'text-amber-50',
                'yellow' => 'text-yellow-950',
                'lime' => 'text-lime-950',
                default => 'text-white dark:text-zinc-800',
            };
            $linkClasses[] = $textColorClass;
        }
    } else {
        $linkClasses[] = 'hover:bg-zinc-200 dark:hover:bg-zinc-800 text-zinc-900 dark:text-zinc-100 hover:text-zinc-900 dark:hover:text-white';
    }
@endphp

<li class="relative list-none">
    @if($hasChildren)
        <div x-data="{ open: {{ $expanded ? 'true' : 'false' }} }">
            <!-- Parent Menu Item (acts as a toggle button) -->
            <button
                type="button"
                @click="open = !open"
                class="{{ implode(' ', $linkClasses) }} w-full text-left h-9"
                :aria-expanded="open.toString()"
                @if($submenuId) aria-controls="{{ $submenuId }}" @endif
            >
                @if($icon)
                    <i class="fa-solid fa-{{ $icon }} mr-3 text-lg w-5 text-center text-current"></i>
                @endif
                
                <span class="flex-1">{{ $title }}</span>

                @if($badge)
                    <span class="mr-2">
                        <x-badge :variant="$badgeColor" size="sm">{{ $badge }}</x-badge>
                    </span>
                @endif
                
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 text-current" 
                   :class="{ 'rotate-180': open }"></i>
            </button>

            <!-- Submenu -->
            <div 
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="mt-1 space-y-1"
                style="{{ $expanded ? '' : 'display: none;' }}"
                @if($submenuId) id="{{ $submenuId }}" @endif
            >
                <ul class="space-y-1 list-none">
                    {{ $slot }}
                </ul>
            </div>
        </div>
    @else
        {{-- Simple Menu Item --}}
        <a 
            href="{{ $url }}"
            class="{{ implode(' ', $linkClasses) }}"
        >
            @if($icon)
                <i class="fa-solid fa-{{ $icon }} mr-3 text-lg w-5 text-center text-current"></i>
            @endif
            
            <span class="flex-1">{{ $title }}</span>
            
            @if($badge)
                <x-badge :variant="$badgeColor" size="sm">{{ $badge }}</x-badge>
            @endif
        </a>
    @endif
</li>
