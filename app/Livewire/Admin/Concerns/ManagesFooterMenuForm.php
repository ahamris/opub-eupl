<?php

namespace App\Livewire\Admin\Concerns;

trait ManagesFooterMenuForm
{
    public function getDefaultForm(): array
    {
        return [
            'parent_id' => null,
            'item_type' => 'link',
            'label' => '',
            'route_name' => '',
            'url' => '',
            'use_external_url' => false,
            'icon' => '',
            'description' => '',
            'badge_text' => '',
            'badge_color' => '',
            'is_disabled' => false,
            'is_hidden' => false,
            'target' => '',
            'position' => 0,
            'is_active' => true,
            'active_pattern' => '',
        ];
    }

    public function prepareFormData(array $data): array
    {
        $options = [];

        if (!empty($data['active_pattern'])) {
            $options['active_pattern'] = $data['active_pattern'];
        }

        // If using external URL, clear route_name; otherwise clear url
        $useExternalUrl = !empty($data['use_external_url']);
        $routeName = $useExternalUrl ? null : ($data['route_name'] ?: null);
        $url = $useExternalUrl ? ($data['url'] ?: null) : null;

        // Handle parent_id - convert empty string to null, otherwise cast to int
        $parentId = $data['parent_id'] ?? null;
        if ($parentId === '' || $parentId === null || $parentId === 0) {
            $parentId = null;
        } else {
            $parentId = (int) $parentId;
        }

        return [
            'parent_id' => $parentId,
            'item_type' => $data['item_type'],
            'label' => $data['label'],
            'slug' => \Illuminate\Support\Str::slug($data['label']),
            'route_name' => $routeName,
            'url' => $url,
            'icon' => $data['icon'] ?: null,
            'description' => $data['description'] ?: null,
            'badge_text' => $data['badge_text'] ?: null,
            'badge_color' => $data['badge_color'] ?: null,
            'is_disabled' => (bool) ($data['is_disabled'] ?? false),
            'is_hidden' => (bool) ($data['is_hidden'] ?? false),
            'target' => $data['target'] ?: null,
            'position' => (int) ($data['position'] ?? 0),
            'is_active' => (bool) ($data['is_active'] ?? true),
            'options' => !empty($options) ? $options : null,
        ];
    }

    protected function loadRouteOptions(): array
    {
        $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())
            ->filter(function ($route) {
                $name = $route->getName();
                // Only frontend routes (not admin, not api, not livewire)
                return $name
                    && !str_starts_with($name, 'admin.')
                    && !str_starts_with($name, 'api.')
                    && !str_starts_with($name, 'livewire.')
                    && !str_starts_with($name, 'ignition.')
                    && !str_starts_with($name, 'sanctum.')
                    && !str_starts_with($name, 'filament.')
                    && in_array('GET', $route->methods());
            })
            ->map(function ($route) {
                return [
                    'name' => $route->getName(),
                    'uri' => $route->uri(),
                ];
            })
            ->sortBy('name')
            ->values()
            ->toArray();

        return $routes;
    }
}

