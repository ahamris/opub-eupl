<?php

namespace App\Livewire\Admin\Concerns;

use App\Models\HeaderMenuItem;
use Illuminate\Support\Facades\Cache;

trait ManagesHeaderMenuReordering
{
    public function reorderFromFrontend(?int $parentId, array $orderedIds): void
    {
        $orderedIds = array_values(array_filter(array_map('intval', $orderedIds)));

        if (empty($orderedIds)) {
            return;
        }

        $items = HeaderMenuItem::query()
            ->whereIn('id', $orderedIds)
            ->get()
            ->keyBy('id');

        $affectedParents = [];

        foreach ($orderedIds as $position => $id) {
            if (!$items->has($id)) {
                continue;
            }

            $item = $items[$id];

            if ($item->parent_id !== $parentId) {
                $affectedParents[(string) ($item->parent_id ?? 'root')] = $item->parent_id;
            }

            $item->parent_id = $parentId;
            $item->position = $position;
            $item->save();
        }

        $this->reindexSiblings($parentId);

        foreach ($affectedParents as $oldParent) {
            $this->reindexSiblings($oldParent);
        }

        if (!is_null($parentId)) {
            $this->expanded[$parentId] = true;
        }

        HeaderMenuItem::clearCache();

        $this->dispatch('notify', type: 'success', message: 'Menu order updated.');
    }

    protected function reindexSiblings(?int $parentId): void
    {
        $query = HeaderMenuItem::query();

        if (is_null($parentId)) {
            $query->whereNull('parent_id');
        } else {
            $query->where('parent_id', $parentId);
        }

        $siblings = $query->orderBy('position')
            ->orderBy('id')
            ->get();

        foreach ($siblings as $index => $sibling) {
            if ((int) $sibling->position !== $index) {
                $sibling->update(['position' => $index]);
            }
        }
    }
}
