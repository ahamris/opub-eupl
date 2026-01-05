<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Drawer extends Component
{
    public function __construct(
        public string $id,
        public ?string $title = null,
        public ?string $width = 'max-w-md', // max-w-sm, max-w-md, max-w-lg, max-w-xl, max-w-2xl
        public bool $withBackdrop = true,
        public bool $closeOnBackdropClick = true,
    ) {
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.drawer');
    }
}
