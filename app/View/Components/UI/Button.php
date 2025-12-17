<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    public string $classes;

    public string $tag = 'button';

    public function __construct(
        public ?string $size = null, // sm, lg
        public ?string $variant = null, // primary, default/outline, filled, danger, ghost, subtle, secondary, success, warning, error, sky, outline-primary, outline-secondary, outline-success, outline-warning, outline-error, outline-sky
        public ?string $color = null, // zinc, red, orange, amber, yellow, lime, green, emerald, teal, cyan, sky, blue, indigo, violet, purple, fuchsia, pink, rose, slate, gray, neutral, stone, base
        public bool $disabled = false,
        public ?string $type = 'button',
        public ?string $icon = null,
        public string $iconPosition = 'left', // left, right
        public bool $loading = false,
        public ?string $href = null,
        public ?string $target = null,
    ) {
        $classes = [];

        $baseClasses = 'inline-flex items-center justify-center text-sm leading-5 tracking-[0.1px] font-medium rounded-md transition-all duration-200 focus:outline-none cursor-pointer self-start';
        $classes[] = $baseClasses;

        if ($size === 'sm') {
            $classes[] = 'px-2 py-1.5 text-xs leading-4 tracking-[0.5px]';
        } elseif ($size === 'lg') {
            $classes[] = 'px-4 py-2.5 text-sm leading-5 tracking-[0.1px]';
        } else {
            $classes[] = 'px-3 py-2';
        }

        if ($variant === null || $variant === 'default' || $variant === 'outline') {
            $classes[] = 'bg-white hover:bg-zinc-50 dark:bg-zinc-700 dark:hover:bg-zinc-600/75';
            $classes[] = 'text-zinc-800 dark:text-white';
            $classes[] = 'border border-zinc-200 hover:border-zinc-200 border-b-zinc-300/80 dark:border-zinc-600 dark:hover:border-zinc-600';
            $classes[] = 'shadow-xs';
        } elseif ($variant === 'primary') {
            if ($color === null) {
                $classes[] = 'bg-[var(--color-accent)] hover:bg-[color-mix(in_oklab,_var(--color-accent),_transparent_10%)]';
                $classes[] = 'text-[var(--color-accent-foreground)]';
                $classes[] = 'border border-black/10 dark:border-0';
                $classes[] = 'shadow-[inset_0px_1px_--theme(--color-white/.2)]';
            } else {
                $primaryClasses = $this->getPrimaryVariantClasses($color);
                $classes = array_merge($classes, $primaryClasses);
            }
        } elseif ($variant === 'filled') {
            $classes[] = 'bg-zinc-800/5 hover:bg-zinc-800/10 dark:bg-white/10 dark:hover:bg-white/20';
            $classes[] = 'text-zinc-800 dark:text-white';
        } elseif ($variant === 'danger') {
            $classes[] = 'bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-500';
            $classes[] = 'text-white';
            $classes[] = 'shadow-[inset_0px_1px_var(--color-red-500),inset_0px_2px_--theme(--color-white/.15)] dark:shadow-none';
        } elseif ($variant === 'ghost') {
            $classes[] = 'bg-transparent hover:bg-zinc-800/5 dark:hover:bg-white/15';
            $classes[] = 'text-zinc-800 dark:text-white';
        } elseif ($variant === 'subtle') {
            $classes[] = 'bg-transparent hover:bg-zinc-800/5 dark:hover:bg-white/15';
            $classes[] = 'text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-white';
        } elseif ($variant === 'outline-primary') {
            $classes[] = 'bg-transparent border transition-all duration-200';
            if ($color === null) {
                $classes[] = 'text-[var(--color-accent)] dark:text-[var(--color-accent-content)]';
                $classes[] = 'border-[var(--color-accent)] dark:border-[var(--color-accent-content)]';
                $classes[] = 'hover:bg-[var(--color-accent)] dark:hover:bg-[var(--color-accent-content)]';
                $classes[] = 'hover:text-[var(--color-accent-foreground)]';
            } else {
                $outlineClasses = $this->getOutlinePrimaryVariantClasses($color);
                $classes = array_merge($classes, $outlineClasses);
            }
        } elseif ($variant === 'secondary') {
            $classes[] = 'bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 text-zinc-900 dark:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-all duration-200';
        } elseif ($variant === 'success') {
            $classes[] = 'bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-900/50 hover:border-green-300 dark:hover:border-green-600 transition-all duration-200';
        } elseif ($variant === 'warning') {
            $classes[] = 'bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 text-yellow-700 dark:text-yellow-300 hover:bg-yellow-100 dark:hover:bg-yellow-900/50 hover:border-yellow-300 dark:hover:border-yellow-600 transition-all duration-200';
        } elseif ($variant === 'error') {
            $classes[] = 'bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/50 hover:border-red-300 dark:hover:border-red-600 transition-all duration-200';
        } elseif ($variant === 'sky') {
            $classes[] = 'bg-sky-50 dark:bg-sky-900/30 border border-sky-200 dark:border-sky-700 text-sky-700 dark:text-sky-300 hover:bg-sky-100 dark:hover:bg-sky-900/50 hover:border-sky-300 dark:hover:border-sky-600 transition-all duration-200';
        } elseif ($variant === 'outline-secondary') {
            $classes[] = 'bg-transparent text-gray-700 dark:text-gray-300 border border-gray-500 dark:border-gray-500 hover:bg-gray-700 dark:hover:bg-gray-600 hover:text-white dark:hover:text-white transition-all duration-200';
        } elseif ($variant === 'outline-success') {
            $classes[] = 'bg-transparent text-green-600 dark:text-green-400 border border-green-600 dark:border-green-400 hover:bg-green-600 dark:hover:bg-green-500 hover:text-white dark:hover:text-white transition-all duration-200';
        } elseif ($variant === 'outline-warning') {
            $classes[] = 'bg-transparent text-yellow-600 dark:text-yellow-400 border border-yellow-600 dark:border-yellow-400 hover:bg-yellow-600 dark:hover:bg-yellow-500 hover:text-white dark:hover:text-white transition-all duration-200';
        } elseif ($variant === 'outline-error') {
            $classes[] = 'bg-transparent text-red-600 dark:text-red-400 border border-red-600 dark:border-red-400 hover:bg-red-600 dark:hover:bg-red-500 hover:text-white dark:hover:text-white transition-all duration-200';
        } elseif ($variant === 'outline-sky') {
            $classes[] = 'bg-transparent text-sky-600 dark:text-sky-400 border border-sky-600 dark:border-sky-400 hover:bg-sky-600 dark:hover:bg-sky-500 hover:text-white dark:hover:text-white transition-all duration-200';
        }

        // Disabled state
        if ($disabled || $loading) {
            if ($href) {
                // For links, use pointer-events-none and opacity
                $classes[] = 'pointer-events-none opacity-50';
            } else {
                // For buttons, use disabled classes
                $classes[] = 'opacity-50 cursor-not-allowed';
                // Remove hover effects by adding no-hover classes
                $classes[] = 'hover:opacity-50';
            }
        }

        $this->classes = implode(' ', $classes);
        $this->tag = $href ? 'a' : 'button';
    }

    /**
     * Get primary variant classes for each color
     */
    private function getPrimaryVariantClasses(string $color): array
    {
        return match ($color) {
            'base' => [
                'bg-zinc-800 dark:bg-white',
                'hover:bg-[color-mix(in_oklab,_var(--color-zinc-800),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-white),_transparent_10%)]',
                'text-white dark:text-zinc-800',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'slate' => [
                'bg-slate-800 dark:bg-white',
                'hover:bg-[color-mix(in_oklab,_var(--color-slate-800),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-white),_transparent_10%)]',
                'text-white dark:text-slate-800',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'gray' => [
                'bg-gray-800 dark:bg-white',
                'hover:bg-[color-mix(in_oklab,_var(--color-gray-800),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-white),_transparent_10%)]',
                'text-white dark:text-gray-800',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'zinc' => [
                'bg-zinc-800 dark:bg-zinc-50',
                'hover:bg-[color-mix(in_oklab,_var(--color-zinc-800),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-white),_transparent_10%)]',
                'text-zinc-50 dark:text-zinc-800',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'neutral' => [
                'bg-neutral-800 dark:bg-white',
                'hover:bg-[color-mix(in_oklab,_var(--color-neutral-800),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-white),_transparent_10%)]',
                'text-white dark:text-neutral-800',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'stone' => [
                'bg-stone-800 dark:bg-white',
                'hover:bg-[color-mix(in_oklab,_var(--color-stone-800),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-white),_transparent_10%)]',
                'text-white dark:text-stone-800',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'red' => [
                'bg-red-500 dark:bg-red-500',
                'hover:bg-[color-mix(in_oklab,_var(--color-red-500),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-red-500),_transparent_10%)]',
                'text-white',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'orange' => [
                'bg-orange-500 dark:bg-orange-400',
                'hover:bg-[color-mix(in_oklab,_var(--color-orange-500),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-orange-400),_transparent_10%)]',
                'text-white dark:text-orange-950',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'amber' => [
                'bg-amber-400 dark:bg-amber-400',
                'hover:bg-[color-mix(in_oklab,_var(--color-amber-400),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-amber-400),_transparent_10%)]',
                'text-amber-950',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'yellow' => [
                'bg-yellow-400 dark:bg-yellow-400',
                'hover:bg-[color-mix(in_oklab,_var(--color-yellow-400),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-yellow-400),_transparent_10%)]',
                'text-yellow-950',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'lime' => [
                'bg-lime-400 dark:bg-lime-400',
                'hover:bg-[color-mix(in_oklab,_var(--color-lime-400),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-lime-400),_transparent_10%)]',
                'text-lime-900 dark:text-lime-950',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'green' => [
                'bg-green-600 dark:bg-green-600',
                'hover:bg-[color-mix(in_oklab,_var(--color-green-600),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-green-600),_transparent_10%)]',
                'text-white',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'emerald' => [
                'bg-emerald-600 dark:bg-emerald-600',
                'hover:bg-[color-mix(in_oklab,_var(--color-emerald-600),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-emerald-600),_transparent_10%)]',
                'text-white',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'teal' => [
                'bg-teal-600 dark:bg-teal-600',
                'hover:bg-[color-mix(in_oklab,_var(--color-teal-600),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-teal-600),_transparent_10%)]',
                'text-white',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'cyan' => [
                'bg-cyan-600 dark:bg-cyan-600',
                'hover:bg-[color-mix(in_oklab,_var(--color-cyan-600),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-cyan-600),_transparent_10%)]',
                'text-white',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'sky' => [
                'bg-sky-600 dark:bg-sky-600',
                'hover:bg-[color-mix(in_oklab,_var(--color-sky-600),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-sky-600),_transparent_10%)]',
                'text-white',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'blue' => [
                'bg-blue-500 dark:bg-blue-500',
                'hover:bg-[color-mix(in_oklab,_var(--color-blue-500),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-blue-500),_transparent_10%)]',
                'text-white',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'indigo' => [
                'bg-indigo-500 dark:bg-indigo-500',
                'hover:bg-[color-mix(in_oklab,_var(--color-indigo-500),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-indigo-500),_transparent_10%)]',
                'text-white',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'violet' => [
                'bg-violet-500 dark:bg-violet-500',
                'hover:bg-[color-mix(in_oklab,_var(--color-violet-500),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-violet-500),_transparent_10%)]',
                'text-white',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'purple' => [
                'bg-purple-500 dark:bg-purple-500',
                'hover:bg-[color-mix(in_oklab,_var(--color-purple-500),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-purple-500),_transparent_10%)]',
                'text-white',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'fuchsia' => [
                'bg-fuchsia-600 dark:bg-fuchsia-600',
                'hover:bg-[color-mix(in_oklab,_var(--color-fuchsia-600),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-fuchsia-600),_transparent_10%)]',
                'text-white',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'pink' => [
                'bg-pink-600 dark:bg-pink-600',
                'hover:bg-[color-mix(in_oklab,_var(--color-pink-600),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-pink-600),_transparent_10%)]',
                'text-white',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            'rose' => [
                'bg-rose-500 dark:bg-rose-500',
                'hover:bg-[color-mix(in_oklab,_var(--color-rose-500),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-rose-500),_transparent_10%)]',
                'text-white',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
            default => [
                'bg-zinc-800 dark:bg-white',
                'hover:bg-[color-mix(in_oklab,_var(--color-zinc-800),_transparent_10%)] dark:hover:bg-[color-mix(in_oklab,_var(--color-white),_transparent_10%)]',
                'text-white dark:text-zinc-800',
                'border border-black/10 dark:border-0',
                'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
            ],
        };
    }

    /**
     * Get outline-primary variant classes for each color - tam olarak tanımlı, JIT uyumlu
     */
    private function getOutlinePrimaryVariantClasses(string $color): array
    {
        return match ($color) {
            'base' => [
                'text-zinc-800 dark:text-white',
                'border-zinc-800 dark:border-white',
                'hover:bg-zinc-800 dark:hover:bg-white',
                'hover:text-white dark:hover:text-zinc-800',
            ],
            'slate' => [
                'text-slate-800 dark:text-white',
                'border-slate-800 dark:border-white',
                'hover:bg-slate-800 dark:hover:bg-white',
                'hover:text-white dark:hover:text-slate-800',
            ],
            'gray' => [
                'text-gray-800 dark:text-white',
                'border-gray-800 dark:border-white',
                'hover:bg-gray-800 dark:hover:bg-white',
                'hover:text-white dark:hover:text-gray-800',
            ],
            'zinc' => [
                'text-zinc-800 dark:text-white',
                'border-zinc-800 dark:border-white',
                'hover:bg-zinc-800 dark:hover:bg-white',
                'hover:text-white dark:hover:text-zinc-800',
            ],
            'neutral' => [
                'text-neutral-800 dark:text-white',
                'border-neutral-800 dark:border-white',
                'hover:bg-neutral-800 dark:hover:bg-white',
                'hover:text-white dark:hover:text-neutral-800',
            ],
            'stone' => [
                'text-stone-800 dark:text-white',
                'border-stone-800 dark:border-white',
                'hover:bg-stone-800 dark:hover:bg-white',
                'hover:text-white dark:hover:text-stone-800',
            ],
            'red' => [
                'text-red-500 dark:text-red-500',
                'border-red-500 dark:border-red-400',
                'hover:bg-red-500 dark:hover:bg-red-400',
                'hover:text-white',
            ],
            'orange' => [
                'text-orange-500 dark:text-orange-400',
                'border-orange-500 dark:border-orange-400',
                'hover:bg-orange-500 dark:hover:bg-orange-400',
                'hover:text-white dark:hover:text-orange-950',
            ],
            'amber' => [
                'text-amber-400 dark:text-amber-400',
                'border-amber-400 dark:border-amber-400',
                'hover:bg-amber-400 dark:hover:bg-amber-400',
                'hover:text-amber-950',
            ],
            'yellow' => [
                'text-yellow-400 dark:text-yellow-400',
                'border-yellow-400 dark:border-yellow-400',
                'hover:bg-yellow-400 dark:hover:bg-yellow-400',
                'hover:text-yellow-950',
            ],
            'lime' => [
                'text-lime-400 dark:text-lime-400',
                'border-lime-400 dark:border-lime-400',
                'hover:bg-lime-400 dark:hover:bg-lime-400',
                'hover:text-lime-900 dark:hover:text-lime-950',
            ],
            'green' => [
                'text-green-600 dark:text-green-400',
                'border-green-600 dark:border-green-400',
                'hover:bg-green-600 dark:hover:bg-green-400',
                'hover:text-white',
            ],
            'emerald' => [
                'text-emerald-600 dark:text-emerald-400',
                'border-emerald-600 dark:border-emerald-400',
                'hover:bg-emerald-600 dark:hover:bg-emerald-400',
                'hover:text-white',
            ],
            'teal' => [
                'text-teal-600 dark:text-teal-400',
                'border-teal-600 dark:border-teal-400',
                'hover:bg-teal-600 dark:hover:bg-teal-400',
                'hover:text-white',
            ],
            'cyan' => [
                'text-cyan-600 dark:text-cyan-400',
                'border-cyan-600 dark:border-cyan-400',
                'hover:bg-cyan-600 dark:hover:bg-cyan-400',
                'hover:text-white',
            ],
            'sky' => [
                'text-sky-600 dark:text-sky-400',
                'border-sky-600 dark:border-sky-400',
                'hover:bg-sky-600 dark:hover:bg-sky-400',
                'hover:text-white',
            ],
            'blue' => [
                'text-blue-500 dark:text-blue-400',
                'border-blue-500 dark:border-blue-400',
                'hover:bg-blue-500 dark:hover:bg-blue-400',
                'hover:text-white',
            ],
            'indigo' => [
                'text-indigo-500 dark:text-indigo-300',
                'border-indigo-500 dark:border-indigo-300',
                'hover:bg-indigo-500 dark:hover:bg-indigo-300',
                'hover:text-white',
            ],
            'violet' => [
                'text-violet-500 dark:text-violet-400',
                'border-violet-500 dark:border-violet-400',
                'hover:bg-violet-500 dark:hover:bg-violet-400',
                'hover:text-white',
            ],
            'purple' => [
                'text-purple-500 dark:text-purple-300',
                'border-purple-500 dark:border-purple-300',
                'hover:bg-purple-500 dark:hover:bg-purple-300',
                'hover:text-white',
            ],
            'fuchsia' => [
                'text-fuchsia-600 dark:text-fuchsia-400',
                'border-fuchsia-600 dark:border-fuchsia-400',
                'hover:bg-fuchsia-600 dark:hover:bg-fuchsia-400',
                'hover:text-white',
            ],
            'pink' => [
                'text-pink-600 dark:text-pink-400',
                'border-pink-600 dark:border-pink-400',
                'hover:bg-pink-600 dark:hover:bg-pink-400',
                'hover:text-white',
            ],
            'rose' => [
                'text-rose-500 dark:text-rose-400',
                'border-rose-500 dark:border-rose-400',
                'hover:bg-rose-500 dark:hover:bg-rose-400',
                'hover:text-white',
            ],
            default => [
                'text-zinc-800 dark:text-white',
                'border-zinc-800 dark:border-white',
                'hover:bg-zinc-800 dark:hover:bg-white',
                'hover:text-white dark:hover:text-zinc-800',
            ],
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.button');
    }
}