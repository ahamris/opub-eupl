<?php

namespace App\Livewire\Admin\Concerns;

trait ManagesMenuForm
{
    protected function getDefaultForm(): array
    {
        return [
            'parent_id' => null,
            'item_type' => 'link',
            'label' => '',
            'route_name' => '',
            'url' => '',
            'icon' => '',
            'badge_type' => null,
            'badge_text' => '',
            'badge_color' => '',
            'badge_query' => [
                'model' => '',
                'query' => '',
            ],
            'active_pattern' => '',
            'target' => '',
            'position' => 0,
            'is_active' => true,
        ];
    }

    protected function prepareFormData(array $data): array
    {
        // Prepare options JSON
        $options = [];
        if ($data['badge_type'] === 'dynamic' && ! empty($data['badge_query']['model']) && ! empty($data['badge_query']['query'])) {
            $options['badge_query'] = [
                'model' => $data['badge_query']['model'],
                'query' => trim($data['badge_query']['query']),
            ];
            $badgeText = null;
        } else {
            $badgeText = $data['badge_text'] ?: null;
        }

        return [
            'admin_menu_id' => $this->menu->id,
            'parent_id' => $data['parent_id'],
            'item_type' => $data['item_type'],
            'label' => $data['label'],
            'slug' => $data['slug'] ?? null,
            'route_name' => $data['route_name'] ?: null,
            'url' => $data['url'] ?: null,
            'icon' => $data['icon'] ?: null,
            'badge_text' => $badgeText,
            'badge_color' => $data['badge_color'] ?: null,
            'active_pattern' => $data['active_pattern'] ?: null,
            'target' => $data['target'] ?: null,
            'position' => $data['position'] ?? 0,
            'is_active' => $data['is_active'],
            'options' => ! empty($options) ? $options : null,
        ];
    }
}
