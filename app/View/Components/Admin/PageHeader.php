<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PageHeader extends Component
{
    public function __construct(
        public string $title,
        public ?string $description = null,
        public ?string $actionLabel = null,
        public ?string $actionHref = null,
        public ?string $actionIcon = null,
        public string $actionIconPosition = 'left',
        public string $actionVariant = 'primary',
        public ?string $backHref = null,
        public ?string $backLabel = null,
    ) {
    }

    public function render(): View|Closure|string
    {
        return view('components.admin.page-header');
    }
}
