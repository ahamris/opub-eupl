<?php

namespace App\Livewire\Admin;

use App\Models\Admin\AdminMenu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Search extends Component
{
    public string $query = '';

    public bool $isOpen = false;

    public bool $dropdownMode = false;

    public function mount(bool $dropdownMode = false): void
    {
        $this->dropdownMode = $dropdownMode;
    }

    public function open(): void
    {
        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->isOpen = false;
        $this->query = '';
    }

    #[Computed]
    public function results(): array
    {
        if (empty($this->query)) {
            return [];
        }

        $results = [];

        // Menü araması
        $menuResults = $this->searchMenus();
        if ($menuResults->isNotEmpty()) {
            $results[] = [
                'type' => 'menu',
                'title' => 'Menus',
                'items' => $menuResults->toArray(),
            ];
        }

        return $results;
    }

    protected function searchMenus(): Collection
    {
        try {
            $menu = AdminMenu::query()
                ->active()
                ->where('slug', 'admin-main')
                ->with(['items' => fn ($query) => $query->ordered()->with('childrenRecursive')])
                ->first();

            if (! $menu) {
                return collect();
            }

            $items = collect();

            $searchInItems = function ($menuItems, $parentLabel = '') use (&$searchInItems, &$items) {
                foreach ($menuItems as $item) {
                    if (! $item->is_active) {
                        continue;
                    }

                    $label = $item->label;
                    $fullLabel = $parentLabel ? "{$parentLabel} → {$label}" : $label;

                    if (stripos($label, $this->query) !== false ||
                        stripos($fullLabel, $this->query) !== false ||
                        ($item->route_name && stripos($item->route_name, $this->query) !== false)) {
                        $items->push([
                            'title' => $fullLabel,
                            'url' => $item->url,
                            'route' => $item->route_name,
                            'icon' => $item->icon,
                        ]);
                    }

                    if ($item->relationLoaded('childrenRecursive') && $item->childrenRecursive->isNotEmpty()) {
                        $searchInItems($item->childrenRecursive, $fullLabel);
                    }
                }
            };

            if ($menu->relationLoaded('items')) {
                $searchInItems($menu->items);
            }

            return $items->take(5);
        } catch (\Exception $e) {
            Log::error('Search component: Error searching menus', [
                'error' => $e->getMessage(),
                'query' => $this->query,
            ]);

            return collect();
        }
    }

    protected function searchRoutes(): Collection
    {
        try {
            $routes = collect(Route::getRoutes())
                ->filter(function ($route) {
                    $name = $route->getName();

                    if (! $name ||
                        ! str_starts_with($name, 'admin.') ||
                        $name === 'admin.home' ||
                        str_contains($name, '.files.') ||
                        str_contains($name, 'api.')) {
                        return false;
                    }

                    // Parametre gerektiren route'ları filtrele
                    $uri = $route->uri();
                    if (preg_match('/\{[^}]+\}/', $uri)) {
                        return false;
                    }

                    return true;
                })
                ->map(function ($route) {
                    try {
                        $uri = $route->uri();
                        $uri = str_replace(['{', '}'], '', $uri);

                        return [
                            'title' => $this->formatRouteName($route->getName()),
                            'url' => $uri,
                            'route' => $route->getName(),
                        ];
                    } catch (\Exception $e) {
                        Log::warning('Search component: Error processing route', [
                            'route' => $route->getName(),
                            'error' => $e->getMessage(),
                        ]);

                        return null;
                    }
                })
                ->filter(function ($item) {
                    if (! $item) {
                        return false;
                    }

                    return stripos($item['title'], $this->query) !== false ||
                        stripos($item['route'], $this->query) !== false ||
                        stripos($item['url'], $this->query) !== false;
                })
                ->values()
                ->take(5);

            return $routes;
        } catch (\Exception $e) {
            Log::error('Search component: Error searching routes', [
                'error' => $e->getMessage(),
                'query' => $this->query,
            ]);

            return collect();
        }
    }

    protected function formatRouteName(?string $name): string
    {
        if (! $name) {
            return '';
        }

        // admin.settings.menu -> Settings Menu
        $name = str_replace('admin.', '', $name);
        $parts = explode('.', $name);
        $formatted = array_map('ucfirst', $parts);

        return implode(' → ', $formatted);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        if ($this->dropdownMode) {
            return view('livewire.admin.search-dropdown');
        }

        return view('livewire.admin.search');
    }
}