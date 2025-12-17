<?php

namespace App\Livewire\Admin\Concerns;

use App\Models\Admin\AdminMenuItem;

trait ManagesMenuExpansion
{
    public function toggleExpand(int $itemId): void
    {
        $this->expanded[$itemId] = ! ($this->expanded[$itemId] ?? false);
    }

    public function expandAll(): void
    {
        $this->expanded = AdminMenuItem::where('admin_menu_id', $this->menu->id)
            ->pluck('id')
            ->mapWithKeys(fn ($id) => [$id => true])
            ->toArray();
    }

    public function collapseAll(): void
    {
        $this->expanded = AdminMenuItem::where('admin_menu_id', $this->menu->id)
            ->pluck('id')
            ->mapWithKeys(fn ($id) => [$id => false])
            ->toArray();
    }

    protected function defaultExpandedState(): array
    {
        return AdminMenuItem::query()
            ->where('admin_menu_id', $this->menu->id)
            ->pluck('id')
            ->mapWithKeys(fn ($id) => [$id => false])
            ->toArray();
    }
}
