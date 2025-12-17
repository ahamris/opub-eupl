<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    public string $modalId;

    public string $maxWidthClass;

    public function __construct(
        public ?string $id = null,
        public ?string $size = 'default', // sm, default, lg, xl, 2xl, fullscreen
        public bool $closeable = true,
        public bool $closeOnBackdropClick = true,
        public bool $closeOnEscape = true,
        public ?string $wireModel = null,
        public ?string $alpineShow = null,
        public bool $blur = false,
        public bool $darker = false,
    ) {
        $this->modalId = $id ?? 'modal-'.uniqid();

        $maxWidthClasses = [
            'sm' => 'max-w-md',
            'default' => 'max-w-2xl',
            'lg' => 'max-w-4xl',
            'xl' => 'max-w-6xl',
            '2xl' => 'max-w-7xl',
            'fullscreen' => 'max-w-full h-screen m-0 rounded-none',
        ];

        $this->maxWidthClass = $maxWidthClasses[$size] ?? $maxWidthClasses['default'];
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.modal');
    }
}

