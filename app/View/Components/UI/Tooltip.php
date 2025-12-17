<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tooltip extends Component
{
    public string $tooltipId;

    public function __construct(
        public ?string $id = null,
        public ?string $text = null,
        public string $position = 'top', // top, bottom, left, right, auto
        public string $trigger = 'hover', // hover, click
        public int $delay = 200, // Delay in milliseconds
        public ?string $maxWidth = null,
    ) {
        $this->tooltipId = $id ?? 'tooltip-'.uniqid();
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.tooltip');
    }
}

