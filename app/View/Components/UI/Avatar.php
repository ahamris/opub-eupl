<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Avatar extends Component
{
    public string $classes;

    public string $initials;

    public function __construct(
        public ?string $src = null, // Image URL
        public ?string $alt = null, // Alt text
        public ?string $name = null, // Name for initials
        public string $size = 'md', // sm, md, lg, xl
        public string $shape = 'circle', // circle, square
        public ?string $status = null, // online, offline, away
        public ?string $icon = null // Fallback icon
    ) {
        $classes = [];

        // Base classes
        $classes[] = 'inline-flex items-center justify-center font-medium text-white relative';

        // Shape
        if ($shape === 'square') {
            $classes[] = 'rounded-md';
        } else {
            $classes[] = 'rounded-full';
        }

        // Size
        $classes[] = match ($size) {
            'sm' => 'w-8 h-8 text-xs',
            'lg' => 'w-12 h-12 text-base',
            'xl' => 'w-16 h-16 text-lg',
            default => 'w-10 h-10 text-sm',
        };

        $this->classes = implode(' ', $classes);

        // Generate initials from name
        $this->initials = $this->generateInitials($name);
    }

    protected function generateInitials(?string $name): string
    {
        if (! $name) {
            return '';
        }

        $words = explode(' ', trim($name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1).substr($words[1], 0, 1));
        }

        return strtoupper(substr($name, 0, 2));
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.avatar');
    }
}
