<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{
    public string $classes;

    public string $selectId = '';

    public function __construct(
        public string $label = '',
        public string $name = '',
        public ?string $id = null,
        public string $value = '',
        public string $placeholder = '',
        public string $hint = '',
        public bool $error = false,
        public string $errorMessage = '',
        public bool $required = false,
        public bool $disabled = false,
        public ?string $size = null, // sm, lg
        public array $options = [] // ['value' => 'label'] format
    ) {
        $classes = [];

        // Base select classes
        $baseClasses = 'block w-full border rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 transition-all duration-200 border-zinc-300 dark:border-zinc-700 focus:outline-none appearance-none cursor-pointer';
        $classes[] = $baseClasses;

        // Size classes
        if ($size === 'sm') {
            $classes[] = 'px-3 py-1.5 text-sm leading-5 tracking-[0.25px]';
        } elseif ($size === 'lg') {
            $classes[] = 'px-5 py-3 text-base leading-6 tracking-[0.5px]';
        } else {
            $classes[] = 'px-4 py-2.5 text-base leading-6 tracking-[0.5px]';
        }

        // State classes
        if ($error) {
            $classes[] = 'bg-red-50 dark:bg-red-900/20 border-red-500 dark:border-red-400 text-red-600 dark:text-red-400';
        } elseif ($disabled) {
            $classes[] = 'bg-zinc-100 dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700 text-zinc-500 dark:text-zinc-500 cursor-not-allowed';
        }

        $this->classes = implode(' ', $classes);
        $this->selectId = $id ?? ($name ?: 'select-'.uniqid());
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.select');
    }
}

