<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public string $classes;

    public string $inputId = '';

    public string $value = '';

    public function __construct(
        public string $label = '',
        public string $type = 'text',
        public string $name = '',
        public ?string $id = null,
        string|int|float|array|null $value = null,
        public string $placeholder = '',
        public string $hint = '',
        public bool $error = false,
        public string $errorMessage = '',
        public bool $required = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public ?string $icon = null,
        public string $iconPosition = 'left', // left, right
        public ?string $size = null, // sm, lg
        public ?string $variant = null, // slug, etc.
        public bool $autoError = true, // Automatically detect errors from Laravel validation
    ) {
        // Auto-detect error if autoError is enabled and name is provided
        if ($this->autoError && $this->name && !$this->error && empty($this->errorMessage)) {
            $errors = session()->get('errors');
            if ($errors && $errors->has($this->name)) {
                $this->error = true;
                $this->errorMessage = $errors->first($this->name);
            }
        }
        // Normalize value to string
        if ($value === null) {
            $this->value = '';
        } elseif (is_array($value)) {
            // If array, convert to JSON or take first element
            $this->value = '';
        } else {
            $this->value = (string) $value;
        }
        $classes = [];

        // Base input classes
        $baseClasses = 'block w-full border rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 placeholder:text-zinc-500 dark:placeholder:text-zinc-400 transition-all duration-200 border-zinc-300 dark:border-zinc-700 focus:outline-none';
        $classes[] = $baseClasses;

        // Size classes
        if ($variant === 'slug') {
            // Slug variant uses same size as title input
            $classes[] = 'px-3 py-1.5 text-base sm:text-sm/6';
        } elseif ($size === 'sm') {
            $classes[] = 'px-3 py-1.5 text-sm leading-5 tracking-[0.25px]';
        } elseif ($size === 'lg') {
            $classes[] = 'px-5 py-3 text-base leading-6 tracking-[0.5px]';
        } else {
            $classes[] = 'px-4 py-2.5 text-base leading-6 tracking-[0.5px]';
        }

        // State classes
        if ($error) {
            $classes[] = 'bg-red-50 dark:bg-red-900/20 border-red-500 dark:border-red-400 text-red-600 dark:text-red-400 placeholder:text-red-400 dark:placeholder:text-red-500';
        } elseif ($disabled) {
            $classes[] = 'bg-zinc-100 dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700 text-zinc-500 dark:text-zinc-500 cursor-not-allowed';
        } elseif ($readonly) {
            $classes[] = 'bg-zinc-50 dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700';
        }

        // Icon paddings
        if (!empty($icon) && $iconPosition === 'left') {
            $classes[] = 'pl-11';
        } elseif (!empty($icon) && $iconPosition === 'right') {
            $classes[] = 'pr-11';
        }

        // Variant classes
        if ($variant === 'slug') {
            $classes[] = 'font-mono bg-gray-50 dark:bg-white/5 border-gray-300 dark:border-white/10';
        }

        $this->classes = implode(' ', $classes);
        $this->inputId = $id ?? ($name ?: 'input-'.uniqid('', true));
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.input');
    }
}
