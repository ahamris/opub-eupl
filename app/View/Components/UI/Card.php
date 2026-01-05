<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public string $classes;

    public array $colorClasses;

    public string $paddingClasses;

    public function __construct(
        public ?string $icon = null,
        public string $iconColor = 'primary', // primary, success, warning, error, sky, secondary
        public ?string $title = null,
        public ?string $value = null,
        public ?string $actionText = null,
        public ?string $actionUrl = null,
        public string $variant = 'default', // default, outlined, filled
        public ?string $size = null, // sm, md, lg
        public ?string $image = null,
        public ?string $imageAlt = null,
        public bool $clickable = false,
        public ?string $clickUrl = null,
        public bool $loading = false,
    ) {
        // Normalize nullable strings to empty strings for display
        $this->title = $this->title ?? '';
        $this->value = $this->value ?? '';
        $this->actionText = $this->actionText ?? 'View details';
        $this->actionUrl = $this->actionUrl ?? 'javascript:void(0)';
        // Size-based padding
        $this->paddingClasses = match ($size) {
            'sm' => 'p-3',
            'lg' => 'p-6',
            default => 'p-5',
        };

        // Card base classes (no shadow, no hover effects)
        $classes = 'bg-white dark:bg-zinc-800 rounded-lg '.$this->paddingClasses;

        // Variant classes (no shadows)
        $classes .= match ($variant) {
            'outlined' => ' border-2 border-zinc-200 dark:border-zinc-700',
            'filled' => ' bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700',
            default => ' border border-zinc-200 dark:border-zinc-700',
        };

        // Clickable card
        if ($clickable && $clickUrl) {
            $classes .= ' cursor-pointer';
        }

        $this->classes = $classes;

        // Color classes mapping
        $this->colorClasses = match ($iconColor) {
            'primary' => [
                'bg' => 'bg-indigo-50',
                'bgDark' => 'dark:bg-indigo-950/30',
                'text' => 'text-indigo-600',
                'textDark' => 'dark:text-indigo-400',
            ],
            'success' => [
                'bg' => 'bg-green-50',
                'bgDark' => 'dark:bg-green-950/30',
                'text' => 'text-green-600',
                'textDark' => 'dark:text-green-400',
            ],
            'warning' => [
                'bg' => 'bg-yellow-50',
                'bgDark' => 'dark:bg-yellow-950/30',
                'text' => 'text-yellow-600',
                'textDark' => 'dark:text-yellow-400',
            ],
            'error' => [
                'bg' => 'bg-red-50',
                'bgDark' => 'dark:bg-red-950/30',
                'text' => 'text-red-600',
                'textDark' => 'dark:text-red-400',
            ],
            'sky' => [
                'bg' => 'bg-sky-50',
                'bgDark' => 'dark:bg-sky-950/30',
                'text' => 'text-sky-600',
                'textDark' => 'dark:text-sky-400',
            ],
            'secondary' => [
                'bg' => 'bg-zinc-100',
                'bgDark' => 'dark:bg-zinc-800',
                'text' => 'text-zinc-600',
                'textDark' => 'dark:text-zinc-400',
            ],
            default => [
                'bg' => 'bg-indigo-50',
                'bgDark' => 'dark:bg-indigo-950/30',
                'text' => 'text-indigo-600',
                'textDark' => 'dark:text-indigo-400',
            ],
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.card');
    }
}
