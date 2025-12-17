<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ColorPicker extends Component
{
    public string $classes;

    public string $inputId = '';

    public array $presetColors = [];

    public function __construct(
        public string $label = '',
        public string $name = '',
        public ?string $id = null,
        public string $value = '#000000',
        public string $placeholder = '#000000',
        public string $hint = '',
        public bool $error = false,
        public string $errorMessage = '',
        public bool $required = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public ?string $size = null, // sm, lg
        public bool $showPresets = true,
        public bool $showInput = true,
        public ?array $presets = null, // Custom preset colors
        public string $format = 'hex', // hex, rgb, hsl
    ) {
        $classes = [];

        // Base input classes
        $baseClasses = 'block w-full border rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 placeholder:text-zinc-500 dark:placeholder:text-zinc-400 transition-all duration-200 border-zinc-300 dark:border-zinc-700 focus:outline-none';
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
            $classes[] = 'bg-red-50 dark:bg-red-900/20 border-red-500 dark:border-red-400 text-red-600 dark:text-red-400 placeholder:text-red-400 dark:placeholder:text-red-500';
        } elseif ($disabled) {
            $classes[] = 'bg-zinc-100 dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700 text-zinc-500 dark:text-zinc-500 cursor-not-allowed';
        } elseif ($readonly) {
            $classes[] = 'bg-zinc-50 dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700';
        }

        $this->classes = implode(' ', $classes);
        $this->inputId = $id ?? ($name ?: 'colorpicker-'.uniqid());

        // Default preset colors if not provided
        $this->presetColors = $presets ?? [
            '#000000', '#FFFFFF', '#FF0000', '#00FF00', '#0000FF',
            '#FFFF00', '#FF00FF', '#00FFFF', '#FFA500', '#800080',
            '#FFC0CB', '#A52A2A', '#808080', '#000080', '#008000',
        ];
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.color-picker');
    }
}
