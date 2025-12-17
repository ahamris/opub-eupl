<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Accordion extends Component
{
    public function __construct(
        public bool $exclusive = false,
        public bool $reverse = false,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.ui.accordion');
    }
}
