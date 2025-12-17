<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Divider extends Component
{
    public string $classes;

    public function __construct(
        public string $orientation = 'horizontal', // horizontal, vertical
        public string $variant = 'solid', // solid, dashed, dotted
        public ?string $color = null, // zinc (default), accent, or any color
        public ?string $text = null, // Optional text in the middle
        public string $size = 'sm', // sm, md, lg, xl
        public ?string $width = null // Custom width (e.g., '100px', '50%', 'full')
    ) {
        $classes = [];

        // Base classes
        if ($orientation === 'vertical') {
            $classes[] = 'inline-block self-stretch';
            if ($width) {
                // Custom width will be applied via style attribute
            } else {
                $classes[] = match ($size) {
                    'md' => 'w-0.5',
                    'lg' => 'w-[2px]',
                    'xl' => 'w-[3px]',
                    default => 'w-px',
                };
            }
        } else {
            if ($width) {
                // Custom width will be applied via style attribute
            } else {
                $classes[] = 'w-full';
            }
            $classes[] = match ($size) {
                'md' => 'h-0.5',
                'lg' => 'h-[2px]',
                'xl' => 'h-[3px]',
                default => 'h-px',
            };
        }

        // Border style
        $borderStyle = match ($variant) {
            'dashed' => 'border-dashed',
            'dotted' => 'border-dotted',
            default => 'border-solid',
        };
        $classes[] = $borderStyle;

        // Border width based on size
        if ($orientation === 'vertical') {
            $classes[] = match ($size) {
                'md' => 'border-r-2',
                'lg' => 'border-r-[3px]',
                'xl' => 'border-r-[4px]',
                default => 'border-r',
            };
            $classes[] = 'border-l-0';
        } else {
            $classes[] = match ($size) {
                'md' => 'border-t-2',
                'lg' => 'border-t-[3px]',
                'xl' => 'border-t-[4px]',
                default => 'border-t',
            };
            $classes[] = 'border-b-0';
        }

        // Color - using match for better Tailwind JIT support
        $colorClass = match ($color) {
            'accent' => 'border-[var(--color-accent)]',
            'red' => 'border-red-300 dark:border-red-700',
            'orange' => 'border-orange-300 dark:border-orange-700',
            'amber' => 'border-amber-300 dark:border-amber-700',
            'yellow' => 'border-yellow-300 dark:border-yellow-700',
            'lime' => 'border-lime-300 dark:border-lime-700',
            'green' => 'border-green-300 dark:border-green-700',
            'emerald' => 'border-emerald-300 dark:border-emerald-700',
            'teal' => 'border-teal-300 dark:border-teal-700',
            'cyan' => 'border-cyan-300 dark:border-cyan-700',
            'sky' => 'border-sky-300 dark:border-sky-700',
            'blue' => 'border-blue-300 dark:border-blue-700',
            'indigo' => 'border-indigo-300 dark:border-indigo-700',
            'violet' => 'border-violet-300 dark:border-violet-700',
            'purple' => 'border-purple-300 dark:border-purple-700',
            'fuchsia' => 'border-fuchsia-300 dark:border-fuchsia-700',
            'pink' => 'border-pink-300 dark:border-pink-700',
            'rose' => 'border-rose-300 dark:border-rose-700',
            'zinc' => 'border-zinc-300 dark:border-zinc-700',
            default => 'border-zinc-300 dark:border-zinc-700',
        };
        $classes[] = $colorClass;

        $this->classes = implode(' ', $classes);
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.divider');
    }
}
