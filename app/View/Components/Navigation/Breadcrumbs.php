<?php

namespace App\View\Components\Navigation;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    public array $crumbs;

    public string $separator;

    public bool $shouldTruncate;

    public array $visibleCrumbs;

    public array $hiddenCrumbs;

    public function __construct(
        public ?array $items = null,
        ?string $separator = null, // FontAwesome icon name (e.g., 'chevron-right', 'angle-right') or text (default: '›')
        public int $maxItems = 5, // Maximum items to show before truncation
    ) {
        $this->separator = $separator ?? '›';
        $this->generateCrumbs();
        $this->processTruncation();
    }

    protected function generateCrumbs(): void
    {
        $generated = [];

        if (! $this->items) {
            // Get current request
            $request = request();

            // Try multiple methods to get URL segments
            $segments = $request->segments();

            // If segments() is empty, try parsing path() manually
            if (empty($segments)) {
                $path = trim($request->path(), '/');
                $segments = $path ? array_filter(explode('/', $path)) : [];
            }

            // If still empty, try parsing from full URL
            if (empty($segments) && $request->url()) {
                $url = parse_url($request->url());
                if (isset($url['path'])) {
                    $path = trim($url['path'], '/');
                    $segments = $path ? array_filter(explode('/', $path)) : [];
                }
            }

            $dashboardUrl = Route::has('admin.home')
                ? route('admin.home')
                : (Route::has('dashboard') ? route('dashboard') : url('/'));
            $generated[] = ['label' => 'Dashboard', 'url' => $dashboardUrl];

            if (! empty($segments)) {
                // Eğer ilk segment 'admin' ise, onu atla (zaten prefix olarak var)
                $filteredSegments = $segments;
                if (isset($segments[0]) && $segments[0] === 'admin') {
                    $filteredSegments = array_slice($segments, 1);
                }

                // Base URL'i dashboard URL'inden al
                $baseUrl = $dashboardUrl;

                if (! empty($filteredSegments)) {
                    foreach ($filteredSegments as $index => $segment) {
                        if (empty($segment)) {
                            continue;
                        }
                        $baseUrl .= '/'.$segment;
                        $label = str($segment)
                            ->replace(['-', '_'], ' ')
                            ->title();
                        $generated[] = [
                            'label' => (string) $label,
                            'url' => $index === count($filteredSegments) - 1 ? null : $baseUrl,
                        ];
                    }
                }
            }

            $generated = collect($generated)
                ->unique(fn ($i) => ($i['label'].'|'.($i['url'] ?? '')))
                ->values()
                ->all();
        }

        $this->crumbs = $this->items ?: $generated;
    }

    protected function processTruncation(): void
    {
        // Eğer crumbs boşsa, en azından Dashboard'u ekle
        if (empty($this->crumbs)) {
            $dashboardUrl = Route::has('admin.home')
                ? route('admin.home')
                : (Route::has('dashboard') ? route('dashboard') : url('/'));
            $this->crumbs = [['label' => 'Dashboard', 'url' => $dashboardUrl]];
        }

        $totalItems = count($this->crumbs);

        // Truncate if we have more items than maxItems
        // Example: 6 items, maxItems=3
        // Show: Dashboard (first) + Level 4, Current Page (last 2)
        // Hide: Level 1, Level 2, Level 3 (middle items)
        if ($totalItems > $this->maxItems) {
            $this->shouldTruncate = true;

            // First item (always Dashboard)
            $firstItem = array_slice($this->crumbs, 0, 1);

            // Last 2 items (always show)
            $lastTwoItems = array_slice($this->crumbs, -2);

            // Hidden items: everything between first and last 2
            // If totalItems = 6, we show 1 (first) + 2 (last) = 3 visible
            // Hidden = items from index 1 to (totalItems - 3)
            $hiddenStartIndex = 1;
            $hiddenCount = $totalItems - count($firstItem) - count($lastTwoItems);
            $hiddenItems = array_slice($this->crumbs, $hiddenStartIndex, $hiddenCount);

            // Visible: first item + last 2 items
            $this->visibleCrumbs = array_merge($firstItem, $lastTwoItems);
            $this->hiddenCrumbs = $hiddenItems;
        } else {
            $this->shouldTruncate = false;
            $this->visibleCrumbs = $this->crumbs;
            $this->hiddenCrumbs = [];
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.navigation.breadcrumbs', [
            'separator' => $this->separator,
            'visibleCrumbs' => $this->visibleCrumbs,
            'hiddenCrumbs' => $this->hiddenCrumbs,
            'shouldTruncate' => $this->shouldTruncate,
        ]);
    }
}
