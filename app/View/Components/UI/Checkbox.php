<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Checkbox extends Component
{
    public string $checkboxId;

    public string $inputClasses;

    public string $labelClasses;

    public function __construct(
        public string $label = '',
        public string $name = '',
        public string|int|null $value = '1',
        public bool $checked = false,
        public bool $disabled = false,
        public ?string $color = 'primary', // primary, secondary, success, warning, error, sky
        public ?string $id = null,
        public bool $required = false
    ) {
        // Normalize value to string
        if ($this->value === null) {
            $this->value = '1';
        } else {
            $this->value = (string) $this->value;
        }
        
        // Ensure color has a default
        if ($this->color === null) {
            $this->color = 'primary';
        }
        $this->checkboxId = $id ?? ($name ?: 'checkbox-'.uniqid());

        $inputClasses = ['w-3.5 h-3.5 rounded border-gray-400 dark:border-gray-600 cursor-pointer transition-all duration-200 focus:outline-none focus:ring-0'];

        // Color classes
        if ($color === 'primary') {
            $inputClasses[] = 'text-[var(--color-accent)] accent-[var(--color-accent)] dark:text-[var(--color-accent-content)] dark:accent-[var(--color-accent-content)]';
        } elseif ($color === 'secondary') {
            $inputClasses[] = 'text-gray-700 dark:text-gray-300 accent-gray-700 dark:accent-gray-300';
        } elseif ($color === 'success') {
            $inputClasses[] = 'text-green-600 dark:text-green-400 accent-green-600 dark:accent-green-400';
        } elseif ($color === 'warning') {
            $inputClasses[] = 'text-yellow-600 dark:text-yellow-400 accent-yellow-600 dark:accent-yellow-400';
        } elseif ($color === 'error') {
            $inputClasses[] = 'text-red-600 dark:text-red-400 accent-red-600 dark:accent-red-400';
        } elseif ($color === 'sky') {
            $inputClasses[] = 'text-sky-600 dark:text-sky-400 accent-sky-600 dark:accent-sky-400';
        }

        if ($disabled) {
            $inputClasses[] = 'cursor-not-allowed opacity-50';
        }

        $this->inputClasses = implode(' ', $inputClasses);

        $labelClasses = ['flex items-center gap-2 cursor-pointer'];
        if ($disabled) {
            $labelClasses[] = 'cursor-not-allowed opacity-50';
        }
        $this->labelClasses = implode(' ', $labelClasses);
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.checkbox');
    }
}