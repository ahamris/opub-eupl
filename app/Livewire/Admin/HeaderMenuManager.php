<?php

namespace App\Livewire\Admin;

use App\Models\HeaderMenuItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class HeaderMenuManager extends Component
{
    // State
    public bool $showModal = false;
    public ?int $editingItemId = null;
    public ?int $confirmingDeleteId = null;
    public array $expanded = [];
    
    // Form data - all values are strings for consistent HTML form handling
    public string $formLabel = '';
    public string $formItemType = 'link';
    public ?string $formParentId = null;
    public string $formIcon = '';
    public string $formRouteName = '';
    public string $formUrl = '';
    public bool $formUseExternalUrl = false;
    public string $formDescription = '';
    public string $formBadgeText = '';
    public string $formBadgeColor = '';
    public string $formTarget = '';
    public string $formActivePattern = '';
    public bool $formIsActive = true;
    public bool $formIsDisabled = false;
    public bool $formIsHidden = false;
    
    // Cached data
    public array $availableRoutes = [];

    protected $rules = [
        'formLabel' => 'required|string|max:255',
        'formItemType' => 'required|in:link,dropdown',
        'formParentId' => 'nullable',
        'formIcon' => 'nullable|string|max:100',
        'formRouteName' => 'nullable|string|max:255',
        'formUrl' => 'nullable|url|max:500',
        'formUseExternalUrl' => 'boolean',
        'formDescription' => 'nullable|string|max:500',
        'formBadgeText' => 'nullable|string|max:50',
        'formBadgeColor' => 'nullable|string|max:50',
        'formTarget' => 'nullable|in:,_blank,_self',
        'formActivePattern' => 'nullable|string|max:255',
        'formIsActive' => 'boolean',
        'formIsDisabled' => 'boolean',
        'formIsHidden' => 'boolean',
    ];

    public function mount(): void
    {
        $this->availableRoutes = $this->loadRouteOptions();
        $this->expanded = $this->getDefaultExpandedState();
    }

    public function render()
    {
        return view('livewire.admin.header-menu-manager', [
            'menuTree' => $this->getMenuTree(),
            'dropdownMenus' => $this->getDropdownMenus(),
        ]);
    }

    // ==================== COMPUTED DATA ====================

    public function getMenuTree(): Collection
    {
        return HeaderMenuItem::query()
            ->with(['childrenRecursive' => fn($q) => $q->ordered()])
            ->whereNull('parent_id')
            ->ordered()
            ->get();
    }

    public function getDropdownMenus(): Collection
    {
        $query = HeaderMenuItem::query()
            ->where('item_type', 'dropdown')
            ->whereNull('parent_id')
            ->orderBy('label');
        
        // Exclude current item when editing
        if ($this->editingItemId) {
            $query->where('id', '!=', $this->editingItemId);
        }
        
        return $query->get(['id', 'label']);
    }

    protected function getDefaultExpandedState(): array
    {
        return HeaderMenuItem::whereNull('parent_id')
            ->pluck('id')
            ->mapWithKeys(fn($id) => [$id => true])
            ->toArray();
    }

    protected function loadRouteOptions(): array
    {
        return collect(\Illuminate\Support\Facades\Route::getRoutes())
            ->filter(function ($route) {
                $name = $route->getName();
                return $name
                    && !str_starts_with($name, 'admin.')
                    && !str_starts_with($name, 'api.')
                    && !str_starts_with($name, 'livewire.')
                    && !str_starts_with($name, 'ignition.')
                    && !str_starts_with($name, 'sanctum.')
                    && !str_starts_with($name, 'filament.')
                    && in_array('GET', $route->methods());
            })
            ->map(fn($route) => $route->getName())
            ->sort()
            ->values()
            ->toArray();
    }

    // ==================== MODAL ACTIONS ====================

    public function openAddModal(string $itemType = 'link'): void
    {
        $this->resetForm();
        $this->formItemType = $itemType;
        $this->showModal = true;
    }

    public function openAddSubItemModal(int $parentId): void
    {
        $this->resetForm();
        $this->formItemType = 'link';
        $this->formParentId = (string) $parentId;
        $this->showModal = true;
    }

    public function edit(int $itemId): void
    {
        $item = HeaderMenuItem::findOrFail($itemId);
        $options = $item->options ?? [];

        $this->editingItemId = $item->id;
        $this->formLabel = $item->label ?? '';
        $this->formItemType = $item->item_type ?? 'link';
        $this->formParentId = $item->parent_id ? (string) $item->parent_id : null;
        $this->formIcon = $item->icon ?? '';
        $this->formRouteName = $item->route_name ?? '';
        $this->formUrl = $item->url ?? '';
        $this->formUseExternalUrl = !empty($item->url) && empty($item->route_name);
        $this->formDescription = $item->description ?? '';
        $this->formBadgeText = $item->badge_text ?? '';
        $this->formBadgeColor = $item->badge_color ?? '';
        $this->formTarget = $item->target ?? '';
        $this->formActivePattern = $options['active_pattern'] ?? '';
        $this->formIsActive = (bool) $item->is_active;
        $this->formIsDisabled = (bool) $item->is_disabled;
        $this->formIsHidden = (bool) $item->is_hidden;
        
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        // Prepare data
        $parentId = $this->formParentId && $this->formParentId !== '' 
            ? (int) $this->formParentId 
            : null;

        $routeName = null;
        $url = null;
        
        if ($this->formItemType !== 'dropdown') {
            if ($this->formUseExternalUrl) {
                $url = $this->formUrl ?: null;
            } else {
                $routeName = $this->formRouteName ?: null;
            }
        }

        $options = [];
        if ($this->formActivePattern) {
            $options['active_pattern'] = $this->formActivePattern;
        }

        $data = [
            'parent_id' => $parentId,
            'item_type' => $this->formItemType,
            'label' => $this->formLabel,
            'slug' => Str::slug($this->formLabel),
            'icon' => $this->formIcon ?: null,
            'route_name' => $routeName,
            'url' => $url,
            'description' => $this->formDescription ?: null,
            'badge_text' => $this->formBadgeText ?: null,
            'badge_color' => $this->formBadgeColor ?: null,
            'target' => $this->formTarget ?: null,
            'is_active' => $this->formIsActive,
            'is_disabled' => $this->formIsDisabled,
            'is_hidden' => $this->formIsHidden,
            'options' => !empty($options) ? $options : null,
        ];

        if ($this->editingItemId) {
            $item = HeaderMenuItem::findOrFail($this->editingItemId);
            $item->update($data);
            $message = 'Menu item updated.';
        } else {
            // Set position for new items
            $data['position'] = $this->getNextPosition($parentId);
            $item = HeaderMenuItem::create($data);
            $message = 'Menu item created.';
        }

        // Expand parent if exists
        if ($item->parent_id) {
            $this->expanded[$item->parent_id] = true;
        }

        $this->closeModal();
        HeaderMenuItem::clearCache();
        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->editingItemId = null;
        $this->formLabel = '';
        $this->formItemType = 'link';
        $this->formParentId = null;
        $this->formIcon = '';
        $this->formRouteName = '';
        $this->formUrl = '';
        $this->formUseExternalUrl = false;
        $this->formDescription = '';
        $this->formBadgeText = '';
        $this->formBadgeColor = '';
        $this->formTarget = '';
        $this->formActivePattern = '';
        $this->formIsActive = true;
        $this->formIsDisabled = false;
        $this->formIsHidden = false;
    }

    protected function getNextPosition(?int $parentId): int
    {
        $query = HeaderMenuItem::query();
        
        if ($parentId) {
            $query->where('parent_id', $parentId);
        } else {
            $query->whereNull('parent_id');
        }
        
        return ($query->max('position') ?? -1) + 1;
    }

    // ==================== DELETE ====================

    public function confirmDelete(int $itemId): void
    {
        $this->confirmingDeleteId = $itemId;
    }

    public function delete(): void
    {
        if (!$this->confirmingDeleteId) {
            return;
        }

        $item = HeaderMenuItem::findOrFail($this->confirmingDeleteId);
        $item->delete();

        $this->confirmingDeleteId = null;
        unset($this->expanded[$item->id]);
        HeaderMenuItem::clearCache();
        
        $this->dispatch('notify', type: 'success', message: 'Menu item deleted.');
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    // ==================== EXPAND/COLLAPSE ====================

    public function toggleExpand(int $itemId): void
    {
        $this->expanded[$itemId] = !($this->expanded[$itemId] ?? false);
    }

    // ==================== REORDERING ====================

    public function reorderFromFrontend(?int $parentId, array $orderedIds): void
    {
        foreach ($orderedIds as $position => $id) {
            HeaderMenuItem::where('id', $id)->update([
                'parent_id' => $parentId,
                'position' => $position,
            ]);
        }

        HeaderMenuItem::clearCache();
        $this->dispatch('notify', type: 'success', message: 'Order updated.');
    }
}
