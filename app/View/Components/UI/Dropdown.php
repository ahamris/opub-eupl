<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Dropdown extends Component
{
    public string $dropdownId;

    public string $baseClasses;

    public array $selectedLabels;

    public bool $hasSelection;

    public function __construct(
        public string $label = '',
        public string $name = '',
        public ?string $id = null,
        public array $options = [], // ['value' => 'label'] format
        public array|string $selected = [], // Array of selected values or single value
        public string $placeholder = 'Placeholder',
        public string $hint = '',
        public bool $error = false,
        public string $errorMessage = '',
        public bool $required = false,
        public bool $disabled = false,
        public bool $multiple = false, // Multi-select (with checkboxes)
        public bool $radio = false, // Single-select with radio buttons
        public string $size = 'default', // sm, default, lg
    ) {
        $this->dropdownId = $id ?? ($name ?: 'dropdown-'.uniqid());

        // Normalize selected to array
        $selectedArray = is_array($selected) ? $selected : [$selected];
        $selectedArray = array_filter($selectedArray, fn ($value) => $value !== '' && $value !== null);

        // Size classes
        $sizeClasses = [
            'sm' => 'px-3 py-1.5 text-sm',
            'default' => 'px-4 py-2.5 text-sm',
            'lg' => 'px-5 py-3 text-base',
        ];
        $dropdownSizeClasses = $sizeClasses[$size] ?? $sizeClasses['default'];

        // Base dropdown classes
        $baseClasses = "block w-full {$dropdownSizeClasses} border rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition-all duration-200 cursor-pointer flex items-center justify-between";

        // Error state
        if ($error) {
            $baseClasses .= ' bg-red-50 dark:bg-red-900/20 border-red-500 dark:border-red-400 text-red-600 dark:text-red-400';
        } else {
            $baseClasses .= ' border-gray-300 dark:border-gray-700 focus:bg-indigo-50/50 dark:focus:bg-gray-800 focus:border-indigo-500 dark:focus:border-indigo-400 focus:outline-none';
        }

        // Disabled state - override other states
        if ($disabled) {
            $baseClasses = "block w-full {$dropdownSizeClasses} border rounded-lg bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-500 dark:text-gray-500 transition-all duration-200 cursor-not-allowed opacity-50 flex items-center justify-between";
        }

        $this->baseClasses = $baseClasses;

        // Get selected labels for display
        $selectedLabels = [];
        foreach ($selectedArray as $value) {
            if (isset($options[$value])) {
                $selectedLabels[] = $options[$value];
            }
        }

        $this->selectedLabels = $selectedLabels;
        $this->hasSelection = count($selectedLabels) > 0;
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.dropdown');
    }
}
