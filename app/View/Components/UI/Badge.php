<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Badge extends Component
{
    public string $classes;

    public string $iconClasses;

    public function __construct(
        public ?string $size = null, // sm, lg
        public ?string $variant = 'secondary', // primary, secondary, success, warning, error, sky
        public ?string $icon = null
    ) {
        // Ensure variant has a default
        if ($this->variant === null) {
            $this->variant = 'secondary';
        }
        $classes = [];

        $baseClasses = 'inline-flex items-center font-medium rounded-full self-start';
        $classes[] = $baseClasses;

        // Size classes
        if ($size === 'sm') {
            $classes[] = 'px-2 py-0.5 text-[11px] leading-4 tracking-[0.5px]';
        } elseif ($size === 'lg') {
            $classes[] = 'px-3 py-1 text-xs leading-4 tracking-[0.5px]';
        } else {
            $classes[] = 'px-2.5 py-1 text-[11px] leading-4 tracking-[0.5px]';
        }

        if ($variant === 'primary') {
            $classes[] = 'bg-[color-mix(in_oklab,var(--color-accent)_15%,transparent)] dark:bg-[color-mix(in_oklab,var(--color-accent)_50%,transparent)] text-[var(--color-accent)] dark:text-[var(--color-accent-content)]';
            $this->iconClasses = 'text-[var(--color-accent)] dark:text-[var(--color-accent-content)]';
        } elseif ($variant === 'primary-dark') {
            $classes[] = 'bg-[var(--color-primary-dark)] text-[var(--color-on-primary)]';
            $this->iconClasses = 'text-[var(--color-on-primary)]';
        } elseif ($variant === 'primary-light') {
            $classes[] = 'bg-[var(--color-primary-light)] text-[var(--color-on-primary-light)]';
            $this->iconClasses = 'text-[var(--color-on-primary-light)]';
        } elseif ($variant === 'secondary') {
            $classes[] = 'bg-[var(--color-primary-dark)] text-[var(--color-on-primary)]';
            $this->iconClasses = 'text-[var(--color-on-primary)]';
        } elseif ($variant === 'success') {
            $classes[] = 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300';
            $this->iconClasses = 'text-green-700 dark:text-green-300';
        } elseif ($variant === 'warning') {
            $classes[] = 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300';
            $this->iconClasses = 'text-yellow-700 dark:text-yellow-300';
        } elseif ($variant === 'error') {
            $classes[] = 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300';
            $this->iconClasses = 'text-red-700 dark:text-red-300';
        } elseif ($variant === 'sky') {
            $classes[] = 'bg-sky-100 dark:bg-sky-900/50 text-sky-700 dark:text-sky-300';
            $this->iconClasses = 'text-sky-700 dark:text-sky-300';
        } else {
            $this->iconClasses = '';
        }

        $this->classes = implode(' ', $classes);
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.badge');
    }
}
