<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public string $classes;

    public string $iconClasses;

    public function __construct(
        public ?string $variant = 'primary', // primary, success, warning, error, info
        public ?string $icon = null,
        public ?string $title = null, // Can also use title slot
        public ?string $message = null, // Can also use message slot or default slot
    ) {
        $classes = ['flex items-start gap-3 p-4 rounded-lg border transition-all duration-200'];

        // Variant classes - tam CSS class isimleriyle
        if ($variant === 'primary') {
            $classes[] = 'bg-[color-mix(in_oklab,var(--color-accent)_10%,transparent)] dark:bg-[color-mix(in_oklab,var(--color-accent)_20%,transparent)] border-[color-mix(in_oklab,var(--color-accent)_20%,transparent)] dark:border-[color-mix(in_oklab,var(--color-accent)_80%,transparent)] text-[var(--color-accent)] dark:text-[var(--color-accent-content)]';
            $this->iconClasses = 'text-[var(--color-accent)] dark:text-[var(--color-accent-content)]';
        } elseif ($variant === 'success') {
            $classes[] = 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-700 dark:text-green-300';
            $this->iconClasses = 'text-green-700 dark:text-green-300';
        } elseif ($variant === 'warning') {
            $classes[] = 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800 text-yellow-700 dark:text-yellow-300';
            $this->iconClasses = 'text-yellow-700 dark:text-yellow-300';
        } elseif ($variant === 'error') {
            $classes[] = 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-700 dark:text-red-300';
            $this->iconClasses = 'text-red-700 dark:text-red-300';
        } elseif ($variant === 'info') {
            $classes[] = 'bg-sky-50 dark:bg-sky-900/20 border-sky-200 dark:border-sky-800 text-sky-700 dark:text-sky-300';
            $this->iconClasses = 'text-sky-700 dark:text-sky-300';
        } else {
            $this->iconClasses = '';
        }

        $this->classes = implode(' ', $classes);
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.alert');
    }
}
