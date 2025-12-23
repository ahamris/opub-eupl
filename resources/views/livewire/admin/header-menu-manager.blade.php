<div class="space-y-8">
    <div class="card">
        <div class="card-header flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h2 class="card-title">Header Menu</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 m-0">Drag items to reorder. Changes save automatically.</p>
            </div>
            <div class="flex items-center gap-2">
                <x-ui.dropdown>
                    <x-slot:trigger>
                        <x-button variant="primary" size="sm" icon="plus">
                            Add New Item
                        </x-button>
                    </x-slot:trigger>
                    <x-slot:content>
                        <div class="py-1">
                            <button type="button" class="w-full text-left px-4 py-2 text-sm text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700" wire:click="openAddModal('dropdown')">
                                <i class="fa-solid fa-chevron-down mr-2"></i> Dropdown Menu
                            </button>
                            <button type="button" class="w-full text-left px-4 py-2 text-sm text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700" wire:click="openAddModal('link')">
                                <i class="fa-solid fa-link mr-2"></i> Link Item
                            </button>
                        </div>
                    </x-slot:content>
                </x-ui.dropdown>
            </div>
        </div>
        <div class="card-body">
            @if($menuTree->isEmpty())
                <p class="text-sm text-gray-600 dark:text-gray-400">No menu items yet. Create a new item.</p>
            @else
                <div class="flex flex-wrap gap-4" data-header-menu-sortable data-parent-id="">
                    @foreach($menuTree as $item)
                        @php
                            $isDropdown = $item->item_type === 'dropdown';
                            $children = $item->childrenRecursive ?? collect();
                            $hasChildren = $children->isNotEmpty();
                            $isExpanded = $expanded[$item->id] ?? true;
                        @endphp
                        
                        <div 
                            class="flex flex-col min-w-[220px]"
                            wire:key="menu-item-{{ $item->id }}"
                            data-item-id="{{ $item->id }}"
                        >
                            {{-- Parent Item --}}
                            <div class="border rounded-md bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 shadow-sm px-3 py-2.5">
                                <div class="flex items-center gap-2">
                                    <x-button 
                                        variant="secondary" 
                                        size="sm" 
                                        icon="grip-vertical" 
                                        data-sort-handle
                                        title="Drag to reorder"
                                        class="shrink-0 cursor-move"
                                    ></x-button>

                                    @if($hasChildren)
                                        <x-button 
                                            variant="secondary" 
                                            size="sm" 
                                            icon="{{ $isExpanded ? 'chevron-down' : 'chevron-right' }}"
                                            wire:click="toggleExpand({{ $item->id }})"
                                            class="shrink-0"
                                        ></x-button>
                                    @endif

                                    <div class="flex-1 flex items-center gap-2">
                                        @if($item->icon)
                                            <i class="fa-solid fa-{{ $item->icon }} text-gray-500 dark:text-gray-400"></i>
                                        @endif
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $item->label }}</span>
                                        @if($isDropdown)
                                            <x-badge variant="sky" size="xs">Dropdown</x-badge>
                                        @endif
                                        @if(!$item->is_active)
                                            <x-badge variant="secondary" size="xs">Inactive</x-badge>
                                        @endif
                                        @if($item->badge_text)
                                            <x-badge variant="{{ $item->badge_color ?? 'primary' }}" size="xs">{{ $item->badge_text }}</x-badge>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-1 shrink-0">
                                        @if($isDropdown)
                                            <x-button variant="success" size="sm" icon="plus" wire:click="openAddSubItemModal({{ $item->id }})" title="Add sub-item"></x-button>
                                        @endif
                                        <x-button variant="secondary" size="sm" icon="pencil" wire:click="edit({{ $item->id }})" title="Edit"></x-button>
                                        <x-button variant="error" size="sm" icon="trash" wire:click="confirmDelete({{ $item->id }})" title="Delete"></x-button>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Children --}}
                            @if($hasChildren && $isExpanded)
                                <div class="mt-3 relative">
                                    <div class="absolute left-0 top-0 bottom-0 w-px bg-indigo-200 dark:bg-indigo-700 ml-2"></div>
                                    <ul class="space-y-2.5 list-none pl-6 relative" data-header-menu-sortable data-parent-id="{{ $item->id }}">
                                        @foreach($children as $child)
                                            <li
                                                class="border rounded-md bg-sky-50 dark:bg-sky-800/50 border-sky-200 dark:border-sky-700 shadow px-3 py-2.5"
                                                wire:key="menu-child-{{ $child->id }}"
                                                data-item-id="{{ $child->id }}"
                                            >
                                                <div class="flex items-center gap-2">
                                                    <x-button 
                                                        variant="secondary" 
                                                        size="sm" 
                                                        icon="grip-vertical" 
                                                        data-sort-handle
                                                        title="Drag"
                                                        class="shrink-0 cursor-move"
                                                    ></x-button>

                                                    <div class="flex-1 flex items-center gap-2">
                                                        @if($child->icon)
                                                            <i class="fa-solid fa-{{ $child->icon }} text-gray-500 dark:text-gray-400"></i>
                                                        @endif
                                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $child->label }}</span>
                                                        @if(!$child->is_active)
                                                            <x-badge variant="secondary" size="xs">Inactive</x-badge>
                                                        @endif
                                                        @if($child->badge_text)
                                                            <x-badge variant="{{ $child->badge_color ?? 'primary' }}" size="xs">{{ $child->badge_text }}</x-badge>
                                                        @endif
                                                    </div>

                                                    <div class="flex items-center gap-1 shrink-0">
                                                        <x-button variant="secondary" size="sm" icon="pencil" wire:click="edit({{ $child->id }})" title="Edit"></x-button>
                                                        <x-button variant="error" size="sm" icon="trash" wire:click="confirmDelete({{ $child->id }})" title="Delete"></x-button>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Confirmation --}}
    @if($confirmingDeleteId)
        <x-alert variant="error" icon="triangle-exclamation" title="Confirm Deletion">
            <p class="text-sm m-0 text-red-600/75 dark:text-red-400/80">This item and all its sub-items will be permanently deleted.</p>
            <div class="flex items-center gap-3 mt-4">
                <x-button variant="error" icon="trash" wire:click="delete">
                    Confirm Delete
                </x-button>
                <x-button variant="secondary" wire:click="cancelDelete">
                    Cancel
                </x-button>
            </div>
        </x-alert>
    @endif

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50">
        <div class="fixed inset-0 z-10 bg-gray-500/75 dark:bg-gray-900/75" wire:click="closeModal"></div>

        <div class="fixed inset-0 z-20 flex items-center justify-center p-4 pointer-events-none">
            <div class="relative w-full max-w-2xl bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6 pointer-events-auto max-h-[90vh] overflow-y-auto" wire:click.stop>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        @if($editingItemId)
                            Edit Menu Item
                        @elseif($formItemType === 'dropdown')
                            New Dropdown Menu
                        @elseif($formParentId)
                            New Sub-Item
                        @else
                            New Menu Item
                        @endif
                    </h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" wire:click="closeModal">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                </div>

                <form wire:submit="save" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Label --}}
                        <div>
                            <x-input 
                                label="Label" 
                                wire:model="formLabel" 
                                placeholder="e.g. Products" 
                                required
                            />
                            @error('formLabel') <small class="text-error">{{ $message }}</small> @enderror
                        </div>

                        {{-- Item Type - Only for top-level items --}}
                        @if(!$formParentId)
                        <div>
                            <x-ui.select-menu 
                                label="Item Type"
                                wire:model.live="formItemType"
                                :options="['link' => 'Link', 'dropdown' => 'Dropdown Menu']"
                            />
                        </div>
                        @endif

                        {{-- Parent Menu - For link items --}}
                        @if($formItemType === 'link')
                        <div>
                            <x-ui.select-menu 
                                label="Parent Menu"
                                wire:model="formParentId"
                                placeholder="None (top-level)"
                                :options="collect($dropdownMenus)->mapWithKeys(fn($d) => [(string) $d->id => $d->label])->prepend('None (top-level)', '')->toArray()"
                            />
                        </div>
                        @endif

                        {{-- Icon --}}
                        <div>
                            <x-input 
                                label="Icon (Font Awesome)"
                                wire:model="formIcon"
                                placeholder="e.g. folder-open"
                                hint="Icon name only"
                            />
                        </div>

                        {{-- Route vs External URL - For link items --}}
                        @if($formItemType !== 'dropdown')
                        <div class="md:col-span-2 space-y-3">
                            <div class="flex items-center gap-3">
                                <x-checkbox 
                                    label="Use External URL instead of route" 
                                    wire:model.live="formUseExternalUrl" 
                                />
                            </div>
                            
                            @if(!$formUseExternalUrl)
                                <div>
                                    <x-ui.select-menu 
                                        label="Route"
                                        wire:model="formRouteName"
                                        placeholder="Select a route..."
                                        :options="collect($availableRoutes)->mapWithKeys(fn($r) => [$r => $r])->toArray()"
                                    />
                                </div>
                            @else
                                <div>
                                    <x-input 
                                        label="External URL"
                                        wire:model="formUrl"
                                        placeholder="https://example.com"
                                    />
                                    @error('formUrl') <small class="text-error">{{ $message }}</small> @enderror
                                </div>
                            @endif
                        </div>
                        @endif

                        {{-- Description --}}
                        <div class="md:col-span-2">
                            <x-input 
                                label="Description"
                                wire:model="formDescription"
                                placeholder="Short description"
                                hint="Shows below label in flyout menus"
                            />
                        </div>

                        {{-- Badge --}}
                        <div>
                            <x-input 
                                label="Badge Text"
                                wire:model="formBadgeText"
                                placeholder="e.g. Coming Soon"
                            />
                        </div>

                        <div>
                            <x-ui.select-menu 
                                label="Badge Color"
                                wire:model="formBadgeColor"
                                placeholder="(Default)"
                                :options="['purple' => 'Purple', 'primary' => 'Blue', 'secondary' => 'Gray', 'success' => 'Green', 'warning' => 'Yellow', 'error' => 'Red']"
                            />
                        </div>

                        {{-- Target --}}
                        @if($formItemType !== 'dropdown')
                        <div>
                            <x-ui.select-menu 
                                label="Open In"
                                wire:model="formTarget"
                                :options="['' => 'Same Window', '_blank' => 'New Tab']"
                            />
                        </div>
                        @endif

                        {{-- Active Pattern --}}
                        <div>
                            <x-input 
                                label="Active Pattern"
                                wire:model="formActivePattern"
                                placeholder="e.g. dossiers.*"
                                hint="Custom active state pattern"
                            />
                        </div>

                        {{-- Checkboxes --}}
                        <div class="md:col-span-2 flex items-center gap-6 flex-wrap">
                            <x-checkbox label="Active" wire:model="formIsActive" />
                            <x-checkbox label="Disabled" wire:model="formIsDisabled" />
                            <x-checkbox label="Hidden" wire:model="formIsHidden" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-button variant="secondary" type="button" wire:click="closeModal">
                            Cancel
                        </x-button>
                        <x-button variant="primary" type="submit" icon="floppy-disk">
                            {{ $editingItemId ? 'Update' : 'Save' }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
        @once
            @assets
                <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
            @endassets
            <script>
                document.addEventListener('livewire:init', () => {
                    let isReordering = false;
                    
                    const getComponent = (el) => {
                        const componentEl = el.closest('[wire\\:id]');
                        return componentEl ? Livewire.find(componentEl.getAttribute('wire:id')) : null;
                    };

                    const initializeSortables = () => {
                        if (isReordering) return;
                        
                        document.querySelectorAll('[data-header-menu-sortable]').forEach(container => {
                            if (container.__sortable) container.__sortable.destroy();

                            container.__sortable = new Sortable(container, {
                                group: 'header-menu',
                                handle: '[data-sort-handle]',
                                animation: 150,
                                fallbackTolerance: 5,
                                onEnd: (evt) => {
                                    const parentId = evt.to.dataset.parentId !== '' ? Number(evt.to.dataset.parentId) : null;
                                    const orderedIds = Array.from(evt.to.children)
                                        .map(child => child.dataset.itemId)
                                        .filter(Boolean)
                                        .map(Number);

                                    const component = getComponent(container);
                                    if (!component) return;

                                    isReordering = true;
                                    component.call('reorderFromFrontend', parentId, orderedIds).then(() => {
                                        isReordering = false;
                                        setTimeout(initializeSortables, 100);
                                    }).catch(() => {
                                        isReordering = false;
                                    });
                                },
                            });
                        });
                    };

                    initializeSortables();

                    Livewire.hook('morph.updated', ({ component }) => {
                        if (isReordering) return;
                        const componentEl = document.querySelector(`[wire\\:id="${component.id}"]`);
                        if (componentEl && componentEl.querySelector('[data-header-menu-sortable]')) {
                            setTimeout(initializeSortables, 100);
                        }
                    });
                });
            </script>
        @endonce
    @endpush
</div>
