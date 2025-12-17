<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Toggle extends Component
{
    public string $toggleId;

    public string $inputClasses;

    public string $labelClasses;

    public function __construct(
        public string $label = '',
        public string $name = '',
        public string $value = '1',
        public bool $checked = false,
        public bool $disabled = false,
        public ?string $size = null, // sm, lg
        public ?string $id = null,
        public bool $required = false
    ) {
        $this->toggleId = $id ?? ($name ?: 'toggle-'.uniqid());

        // Base input classes - Tailwind only
        $inputClasses = [
            'appearance-none',
            'relative',
            'cursor-pointer',
            'focus:outline-none',
            'focus:ring-0',
            'toggle-switch',
        ];

        // Size-specific classes
        if ($size === 'sm') {
            $inputClasses[] = 'w-8';
            $inputClasses[] = 'h-5';
            $inputClasses[] = 'toggle-switch-sm';
        } elseif ($size === 'lg') {
            $inputClasses[] = 'w-12';
            $inputClasses[] = 'h-7';
            $inputClasses[] = 'toggle-switch-lg';
        } else {
            // Default size
            $inputClasses[] = 'w-10';
            $inputClasses[] = 'h-6';
        }

        if ($disabled) {
            $inputClasses[] = 'cursor-not-allowed';
            $inputClasses[] = 'opacity-50';
        }

        $this->inputClasses = implode(' ', $inputClasses);

        $labelClasses = ['flex', 'items-center', 'gap-3', 'cursor-pointer'];
        if ($disabled) {
            $labelClasses[] = 'cursor-not-allowed';
            $labelClasses[] = 'opacity-50';
        }
        $this->labelClasses = implode(' ', $labelClasses);
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.toggle');
    }
}

