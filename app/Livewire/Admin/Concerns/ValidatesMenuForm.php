<?php

namespace App\Livewire\Admin\Concerns;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait ValidatesMenuForm
{
    protected function validateForm(): array
    {
        $data = $this->validate([
            'form.parent_id' => 'nullable|exists:admin_menu_items,id',
            'form.item_type' => 'required|in:link,section',
            'form.label' => 'required|string|max:255',
            'form.route_name' => 'nullable|string|max:255',
            'form.url' => 'nullable|string|max:2048',
            'form.icon' => 'nullable|string|max:50',
            'form.badge_type' => 'nullable|in:static,dynamic',
            'form.badge_text' => 'nullable|string|max:50',
            'form.badge_color' => 'nullable|string|max:50',
            'form.badge_query.model' => 'nullable|string|max:255',
            'form.badge_query.query' => 'nullable|string|max:500',
            'form.active_pattern' => 'nullable|string|max:255',
            'form.target' => 'nullable|string|max:20',
            'form.position' => 'nullable|integer|min:0',
            'form.is_active' => 'boolean',
        ])['form'];

        if ($data['item_type'] === 'link' && empty($data['route_name']) && empty($data['url'])) {
            throw ValidationException::withMessages([
                'form.route_name' => 'At least one of route or URL field is required for link type items.',
            ]);
        }

        if ($this->editingItemId && (int) $data['parent_id'] === $this->editingItemId) {
            throw ValidationException::withMessages([
                'form.parent_id' => 'An item cannot be a child of itself.',
            ]);
        }

        $data['slug'] = Str::slug($data['label']);

        return $data;
    }
}
