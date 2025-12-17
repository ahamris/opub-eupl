<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\LoadsMenuOptions;
use App\Livewire\Admin\Concerns\ManagesMenuExpansion;
use App\Livewire\Admin\Concerns\ManagesMenuForm;
use App\Livewire\Admin\Concerns\ManagesMenuReordering;
use App\Livewire\Admin\Concerns\ValidatesMenuForm;
use App\Models\Admin\AdminMenu;
use App\Models\Admin\AdminMenuItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class MenuManager extends Component
{
    use LoadsMenuOptions;
    use ManagesMenuExpansion;
    use ManagesMenuForm;
    use ManagesMenuReordering;
    use ValidatesMenuForm;

    public AdminMenu $menu;

    public ?int $editingItemId = null;

    public ?int $confirmingDeleteId = null;

    public bool $showModal = false;

    public ?string $selectedItemType = null;

    public array $form = [];

    public array $availableRoutes = [];

    public array $availableModels = [];

    public array $expanded = [];

    public function mount(): void
    {
        $this->menu = AdminMenu::firstOrCreate(
            ['slug' => 'admin-main'],
            [
                'name' => 'Admin Sidebar',
                'description' => 'Admin panel menu',
                'position' => 0,
                'is_active' => true,
            ]
        );

        $this->availableRoutes = $this->loadRouteOptions();
        $this->availableModels = $this->loadModelOptions();
        $this->expanded = $this->defaultExpandedState();
        $this->form = $this->getDefaultForm();

        $this->dispatch('menu-tree-mounted');
    }

    public function getMenuTreeProperty(): Collection
    {
        return AdminMenuItem::query()
            ->with(['childrenRecursive' => fn ($query) => $query->ordered()])
            ->where('admin_menu_id', $this->menu->id)
            ->whereNull('parent_id')
            ->ordered()
            ->get();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.menu-manager');
    }

    public function save(): void
    {
        $data = $this->validateForm();
        $attributes = $this->prepareFormData($data);

        if ($this->editingItemId) {
            $item = AdminMenuItem::where('admin_menu_id', $this->menu->id)->findOrFail($this->editingItemId);
            $previousParent = $item->parent_id;
            $item->update($attributes);
            $message = 'Menu item updated.';

            if ($item->parent_id) {
                $this->expanded[$item->parent_id] = true;
            }

            if ($previousParent && $previousParent !== $item->parent_id) {
                $this->expanded[$previousParent] = $this->expanded[$previousParent] ?? false;
            }
        } else {
            $item = AdminMenuItem::create($attributes);
            $message = 'Menu item created.';
            $this->expanded[$item->id] = true;
            if ($item->parent_id) {
                $this->expanded[$item->parent_id] = true;
            }
        }

        $this->showModal = false;
        $this->selectedItemType = null;
        $this->editingItemId = null;
        $this->form = $this->getDefaultForm();
        Cache::forget('admin-menu:sidebar');

        $this->dispatch('notify', type: 'success', message: $message);
        $this->dispatch('refresh-sidebar');
        $this->dispatch('$refresh');
    }

    public function edit(int $itemId): void
    {
        $item = AdminMenuItem::where('admin_menu_id', $this->menu->id)->findOrFail($itemId);

        $options = $item->options ?? [];
        $badgeQuery = $options['badge_query'] ?? null;
        $badgeType = $badgeQuery ? 'dynamic' : ($item->badge_text ? 'static' : null);

        $this->editingItemId = $item->id;
        $this->selectedItemType = $item->item_type;
        $this->showModal = true;
        $this->form = [
            'parent_id' => $item->parent_id,
            'item_type' => $item->item_type,
            'label' => $item->label,
            'route_name' => $item->route_name ?? '',
            'url' => $item->url ?? '',
            'icon' => $item->icon ?? '',
            'badge_type' => $badgeType,
            'badge_text' => $item->badge_text ?? '',
            'badge_color' => $item->badge_color ?? '',
            'badge_query' => $badgeQuery ? [
                'model' => $badgeQuery['model'] ?? '',
                'query' => $badgeQuery['query'] ?? '',
            ] : [
                'model' => '',
                'query' => '',
            ],
            'active_pattern' => $item->active_pattern ?? '',
            'target' => $item->target ?? '',
            'position' => $item->position ?? 0,
            'is_active' => $item->is_active,
        ];
    }

    public function confirmDelete(int $itemId): void
    {
        $this->confirmingDeleteId = $itemId;
    }

    public function delete(): void
    {
        if (! $this->confirmingDeleteId) {
            return;
        }

        $item = AdminMenuItem::where('admin_menu_id', $this->menu->id)->findOrFail($this->confirmingDeleteId);
        $item->delete();

        $this->confirmingDeleteId = null;
        unset($this->expanded[$item->id]);
        Cache::forget('admin-menu:sidebar');
        $this->dispatch('notify', type: 'success', message: 'Menu item deleted.');
        $this->dispatch('refresh-sidebar');
        $this->dispatch('$refresh');
    }

    public function resetForm(): void
    {
        $this->editingItemId = null;
        $this->confirmingDeleteId = null;
        $this->showModal = false;
        $this->selectedItemType = null;
        Cache::forget('admin-menu:sidebar');
        $this->form = $this->getDefaultForm();
    }

    public function openAddModal(string $itemType): void
    {
        $this->selectedItemType = $itemType;
        $this->form['item_type'] = $itemType;
        $this->form['position'] = $this->getNextPosition();

        if ($itemType === 'section') {
            $this->form['parent_id'] = null;
            $this->form['route_name'] = '';
            $this->form['url'] = '';
            $this->form['icon'] = '';
        }

        $this->showModal = true;
    }

    protected function getNextPosition(): int
    {
        $maxPosition = AdminMenuItem::where('admin_menu_id', $this->menu->id)
            ->whereNull('parent_id')
            ->max('position');

        return ($maxPosition ?? -1) + 1;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function updatedFormItemType(): void
    {
        if ($this->form['item_type'] === 'section') {
            $this->form['route_name'] = '';
            $this->form['url'] = '';
            $this->form['icon'] = '';
        }
    }

    public function updatedFormRouteName(): void
    {
        if (! empty($this->form['route_name'])) {
            $this->form['url'] = '';
        }
    }

    public function getParentOptionsProperty(): Collection
    {
        return AdminMenuItem::query()
            ->where('admin_menu_id', $this->menu->id)
            ->orderBy('label')
            ->get(['id', 'label', 'item_type']);
    }
}
