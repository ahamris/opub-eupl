@props([
    'title' => null,
    'icon' => null,
    'href' => null,
    'route' => null,
    'active' => false,
    'badge' => null,
    'badgeColor' => 'primary'
])

@php
    use App\Models\Admin\AdminThemeSetting;
    
    $url = $href ?? ($route ? route($route) : '#');
    
    $linkClasses = [
        'flex items-center px-3 py-2 text-sm font-medium transition-colors duration-200 ml-4 rounded-md focus:outline-none',
        'relative before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-6 before:bg-[var(--color-accent)] dark:before:bg-[color-mix(in_oklab,var(--color-accent)_60%,transparent)] before:rounded-full before:opacity-0 before:transition-opacity'
    ];

    if($active) {
        $linkClasses[] = 'bg-[color-mix(in_oklab,var(--color-accent)_5%,transparent)] dark:bg-[color-mix(in_oklab,var(--color-accent)_5%,transparent)]';
        $linkClasses[] = 'before:opacity-100';
        
        // Active state - tema rengine göre text rengi belirle
        $settings = AdminThemeSetting::getSettings();
        $accentColor = $settings->accent_color;
        $baseColor = $settings->base_color;
        
        if ($accentColor === 'base') {
            // Base color seçildiğinde:
            // Light mode: hafif koyu arka plan, text koyu olmalı
            // Dark mode: hafif açık arka plan, text açık olmalı
            $darkTextColor = match($baseColor) {
                'slate' => 'text-slate-900 dark:text-slate-100',
                'gray' => 'text-gray-900 dark:text-gray-100',
                'zinc' => 'text-zinc-900 dark:text-zinc-100',
                'neutral' => 'text-neutral-900 dark:text-neutral-100',
                'stone' => 'text-stone-900 dark:text-stone-100',
                default => 'text-zinc-900 dark:text-zinc-100',
            };
            $linkClasses[] = $darkTextColor;
        } else {
            // Diğer accent color'lar için
            $textColorClass = match($accentColor) {
                'slate' => 'text-slate-700 dark:text-slate-300',
                'gray' => 'text-gray-700 dark:text-gray-300',
                'zinc' => 'text-zinc-700 dark:text-zinc-300',
                'neutral' => 'text-neutral-700 dark:text-neutral-300',
                'stone' => 'text-stone-700 dark:text-stone-300',
                'red', 'green', 'emerald', 'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose' => 'text-[var(--color-accent)] dark:text-[var(--color-accent-content)]',
                'orange' => 'text-orange-700 dark:text-orange-300',
                'amber' => 'text-amber-700 dark:text-amber-300',
                'yellow' => 'text-yellow-700 dark:text-yellow-300',
                'lime' => 'text-lime-700 dark:text-lime-300',
                default => 'text-zinc-700 dark:text-zinc-300',
            };
            $linkClasses[] = $textColorClass;
        }
    } else {
        $linkClasses[] = 'hover:bg-zinc-100 dark:hover:bg-zinc-800/50 text-zinc-800 dark:text-white hover:text-zinc-900 dark:hover:text-white hover:before:opacity-30';
    }
@endphp

<li class="relative list-none">
    <a 
        href="{{ $url }}"
        class="{{ implode(' ', $linkClasses) }}"
    >
        @if($icon)
            <i class="fa-solid fa-{{ $icon }} mr-3 text-sm w-4 text-center text-current"></i>
        @endif
        
        <span class="flex-1">{{ $title }}</span>
        
        @if($badge)
            <x-badge :variant="$badgeColor" size="sm">{{ $badge }}</x-badge>
        @endif
    </a>
</li>
