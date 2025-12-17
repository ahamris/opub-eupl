<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Toast extends Component
{
    public string $classes;
    public string $iconClasses;
    public string $iconName;

    public function __construct(
        public string $variant = 'info', // success, error, warning, info
        public ?string $title = null,
        public ?string $message = null,
        public ?string $icon = null,
        public int $duration = 5000, // Auto-dismiss duration in milliseconds (0 = no auto-dismiss)
        public bool $dismissible = true,
        public ?string $id = null,
    ) {
        $this->id = $id ?? 'toast-'.uniqid('', true);
        
        // Base classes
        $baseClasses = 'flex items-start gap-3 p-4 rounded-lg border shadow-lg transition-all duration-300 max-w-sm w-full pointer-events-auto';
        
        // Variant classes
        $variantClasses = match($variant) {
            'success' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-700 dark:text-green-300',
            'error' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-700 dark:text-red-300',
            'warning' => 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800 text-yellow-700 dark:text-yellow-300',
            'info' => 'bg-sky-50 dark:bg-sky-900/20 border-sky-200 dark:border-sky-800 text-sky-700 dark:text-sky-300',
            default => 'bg-sky-50 dark:bg-sky-900/20 border-sky-200 dark:border-sky-800 text-sky-700 dark:text-sky-300',
        };
        
        $this->classes = $baseClasses . ' ' . $variantClasses;
        
        // Icon classes
        $this->iconClasses = match($variant) {
            'success' => 'text-green-700 dark:text-green-300',
            'error' => 'text-red-700 dark:text-red-300',
            'warning' => 'text-yellow-700 dark:text-yellow-300',
            'info' => 'text-sky-700 dark:text-sky-300',
            default => 'text-sky-700 dark:text-sky-300',
        };
        
        // Default icons
        $this->iconName = $icon ?? match($variant) {
            'success' => 'check-circle',
            'error' => 'exclamation-circle',
            'warning' => 'triangle-exclamation',
            'info' => 'circle-info',
            default => 'circle-info',
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.toast');
    }
}

