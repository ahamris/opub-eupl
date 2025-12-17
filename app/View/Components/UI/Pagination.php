<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;

class Pagination extends Component
{
    public function __construct(
        public LengthAwarePaginator $paginator,
        public string $variant = 'default', // default, compact
        public ?string $wireModel = null,
        public bool $showInfo = true,
        public int $onEachSide = 2,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.ui.pagination');
    }
}
