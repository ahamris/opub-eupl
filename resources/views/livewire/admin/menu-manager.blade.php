<div class="space-y-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <div class="lg:w-2/5 space-y-4">
            <div class="card">
                <div class="card-header flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <h2 class="card-title">Menu Tree</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 m-0">Drag and drop items to reorder them or create sub-items.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-button variant="secondary" size="sm" icon="compress" wire:click="collapseAll">
                            Collapse All
                        </x-button>
                        <x-button variant="secondary" size="sm" icon="expand" wire:click="expandAll">
                            Expand All
                        </x-button>
                        <x-ui.dropdown>
                            <x-slot:trigger>
                                <x-button variant="primary" size="sm" icon="plus">
                                    Add New Item
                                </x-button>
                            </x-slot:trigger>
                            <x-slot:content>
                                <div class="py-1">
                                    <button type="button" class="w-full text-left px-4 py-2 text-sm text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700" wire:click="openAddModal('section')">
                                        <i class="fa-solid fa-heading mr-2"></i> Section / Header
                                    </button>
                                    <button type="button" class="w-full text-left px-4 py-2 text-sm text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700" wire:click="openAddModal('link')">
                                        <i class="fa-solid fa-link mr-2"></i> Menu Item
                                    </button>
                                </div>
                            </x-slot:content>
                        </x-ui.dropdown>
                    </div>
                </div>
                <div class="card-body">
                    @if($this->menuTree->isEmpty())
                        <p class="text-sm text-gray-600 dark:text-gray-400">No menu items yet. Create a new item.</p>
                    @else
                        <ul class="space-y-2 list-none" data-menu-sortable data-parent-id="">
                            @foreach($this->menuTree as $item)
                                @include('livewire.admin.partials.menu-tree-item', [
                                    'item' => $item,
                                    'depth' => 0,
                                    'expanded' => $expanded[$item->id] ?? true,
                                    'expandedItems' => $expanded
                                ])
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

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
        </div>

        {{-- Documentation Panel --}}
        <div class="lg:w-3/5">
            <div class="card lg:sticky lg:top-8 lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto">
                <div class="card-header">
                    <h2 class="card-title">How to Use</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 m-0">Quick guide to managing your admin menu</p>
                </div>
                <div class="card-body space-y-6">
                    {{-- Adding Items --}}
                    <section>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-plus-circle text-indigo-500 text-sm"></i>
                            Adding Items
                        </h3>
                        <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                            <p>Click <strong>"Add New Item"</strong> and choose:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs text-gray-600 dark:text-gray-400 ml-2">
                                <li><strong>Section/Header:</strong> Non-clickable header (only label required)</li>
                                <li><strong>Menu Item:</strong> Clickable link (label + route/URL required)</li>
                            </ul>
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-md p-2 border border-gray-200 dark:border-gray-700 mt-2">
                                <p class="text-xs text-gray-600 dark:text-gray-400"><strong>Optional:</strong> Icon, badge, parent item, active pattern</p>
                            </div>
                        </div>
                    </section>

                    {{-- Reordering --}}
                    <section>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-arrows-up-down-left-right text-indigo-500 text-sm"></i>
                            Reordering
                        </h3>
                        <p class="text-sm text-gray-700 dark:text-gray-300">Drag items by the grip icon (<i class="fa-solid fa-grip-vertical text-gray-400 text-xs"></i>) to reorder or create sub-menus. Changes save automatically.</p>
                    </section>

                    {{-- Sub-menus --}}
                    <section>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-sitemap text-indigo-500 text-sm"></i>
                            Sub-menus
                        </h3>
                        <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                            <p>Two ways to create sub-menus:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs text-gray-600 dark:text-gray-400 ml-2">
                                <li>Select a <strong>Parent Item</strong> when creating/editing</li>
                                <li>Drag an item into another item's drop zone</li>
                            </ul>
                        </div>
                    </section>

                    {{-- Badges --}}
                    <section>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-tag text-indigo-500 text-sm"></i>
                            Badges
                        </h3>
                        <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                            <p><strong>Static:</strong> Fixed text (e.g., "New", "12")</p>
                            <p><strong>Dynamic:</strong> Database query result</p>
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-md p-2 border border-gray-200 dark:border-gray-700 mt-2">
                                <p class="text-xs font-medium mb-1">Dynamic Badge:</p>
                                <ol class="list-decimal list-inside space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                    <li>Select a model (e.g., User, Order)</li>
                                    <li>Enter query: <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">count()</code> or <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">where('status', 'pending')->count()</code></li>
                                </ol>
                            </div>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <x-badge variant="primary">Primary</x-badge>
                                <x-badge variant="secondary">Secondary</x-badge>
                                <x-badge variant="success">Success</x-badge>
                                <x-badge variant="warning">Warning</x-badge>
                                <x-badge variant="error">Error</x-badge>
                                <x-badge variant="sky">Sky</x-badge>
                            </div>
                        </div>
                    </section>

                    {{-- Routes & URLs --}}
                    <section>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-route text-indigo-500 text-sm"></i>
                            Routes & URLs
                        </h3>
                        <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                            <p><strong>Route:</strong> Select from dropdown (e.g., <code class="text-xs bg-gray-200 dark:bg-gray-700 px-1 rounded">admin.products.index</code>)</p>
                            <p><strong>External URL:</strong> Enter full URL (e.g., <code class="text-xs bg-gray-200 dark:bg-gray-700 px-1 rounded">https://example.com</code>)</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Note: External URL appears only when no route is selected.</p>
                        </div>
                    </section>

                    {{-- Icons --}}
                    <section>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-icons text-indigo-500 text-sm"></i>
                            Icons
                        </h3>
                        <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                            <p>Enter Font Awesome icon name only (without "fa-solid"):</p>
                            <div class="flex items-center gap-2 flex-wrap text-xs">
                                <span class="flex items-center gap-1"><i class="fa-solid fa-box text-gray-600 dark:text-gray-400"></i> <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">box</code></span>
                                <span class="flex items-center gap-1"><i class="fa-solid fa-shopping-cart text-gray-600 dark:text-gray-400"></i> <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">shopping-cart</code></span>
                                <span class="flex items-center gap-1"><i class="fa-solid fa-chart-line text-gray-600 dark:text-gray-400"></i> <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">chart-line</code></span>
                            </div>
                        </div>
                    </section>

                    {{-- Active State --}}
                    <section>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-toggle-on text-indigo-500 text-sm"></i>
                            Active State
                        </h3>
                        <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                            <p><strong>Active Pattern:</strong> Custom route pattern (default: route name + <code class="text-xs bg-gray-200 dark:bg-gray-700 px-1 rounded">.*</code>)</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Example: <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">admin.orders*</code> matches all routes starting with "admin.orders"</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400"><strong>Active checkbox:</strong> Show/hide item in sidebar</p>
                        </div>
                    </section>

                    {{-- Quick Tips --}}
                    <section>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-lightbulb text-indigo-500 text-sm"></i>
                            Quick Tips
                        </h3>
                        <ul class="list-disc list-inside space-y-1 text-xs text-gray-600 dark:text-gray-400 ml-2">
                            <li>Use sections to group related items</li>
                            <li>Keep menu depth to 2-3 levels maximum</li>
                            <li>Test dynamic badge queries in Tinker first</li>
                            <li>Use appropriate badge colors for context</li>
                            <li>Drag items to reorder or create sub-menus</li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>

        {{-- Modal --}}
        <div class="fixed inset-0 z-50" x-data="{ show: @entangle('showModal').live }" x-show="show" x-cloak x-transition style="display: none;">
            {{-- Backdrop --}}
            <div class="fixed inset-0 z-10 bg-gray-500/75 dark:bg-gray-900/75 transition-opacity" @click="$wire.resetForm()"></div>

            {{-- Modal Panel --}}
            <div class="fixed inset-0 z-20 flex items-center justify-center p-4 pointer-events-none">
                <div class="relative w-full max-w-2xl bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6 pointer-events-auto max-h-[90vh] overflow-y-auto" @click.stop>
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $editingItemId ? 'Edit ' . ($selectedItemType === 'section' ? 'Section' : 'Menu Item') : 'New ' . ($selectedItemType === 'section' ? 'Section' : 'Menu Item') }}
                            </h3>
                            <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" wire:click="resetForm">
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        </div>

                        <form wire:submit.prevent="save" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($selectedItemType === 'section')
                                    {{-- Section Form --}}
                                    <div class="md:col-span-2">
                                        <x-input 
                                            label="Label" 
                                            name="form.label" 
                                            wire:model.lazy="form.label" 
                                            placeholder="e.g. Settings" 
                                            required
                                        />
                                        @error('form.label') <small class="text-error">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="md:col-span-2 flex items-center gap-3">
                                        <x-checkbox label="Active" wire:model="form.is_active" />
                                    </div>
                                @else
                                    {{-- Menu Item Form --}}
                                    <div>
                                        <x-select 
                                            label="Parent Item" 
                                            name="form.parent_id" 
                                            wire:model="form.parent_id"
                                            placeholder="(No parent)"
                                            :options="collect($this->parentOptions)->mapWithKeys(function($parent) {
                                                return [$parent->id => $parent->label . ($parent->item_type === 'section' ? ' (Section)' : '')];
                                            })->toArray()"
                                        />
                                        @error('form.parent_id') <small class="text-error">{{ $message }}</small> @enderror
                                    </div>

                                    <div>
                                        <x-input 
                                            label="Label" 
                                            name="form.label" 
                                            wire:model.lazy="form.label" 
                                            placeholder="e.g. Products" 
                                            required
                                        />
                                        @error('form.label') <small class="text-error">{{ $message }}</small> @enderror
                                    </div>

                                    <div>
                                        <x-input 
                                            label="Icon (Font Awesome)" 
                                            name="form.icon" 
                                            wire:model.lazy="form.icon" 
                                            placeholder="e.g. box"
                                            hint="Icon name only (fa-solid is assumed)"
                                        />
                                        @error('form.icon') <small class="text-error">{{ $message }}</small> @enderror
                                    </div>

                                    <div>
                                        <x-select 
                                            label="Route" 
                                            name="form.route_name" 
                                            wire:model.live="form.route_name"
                                            placeholder="(Not selected)"
                                            :options="collect($availableRoutes)->mapWithKeys(function($route) {
                                                return [$route['name'] => $route['name'] . ' (' . $route['uri'] . ')'];
                                            })->toArray()"
                                        />
                                        @error('form.route_name') <small class="text-error">{{ $message }}</small> @enderror
                                    </div>

                                    @if(empty($form['route_name']))
                                        <div class="md:col-span-2">
                                            <x-input 
                                                label="External URL" 
                                                name="form.url" 
                                                wire:model.lazy="form.url" 
                                                placeholder="https://..."
                                            />
                                            @error('form.url') <small class="text-error">{{ $message }}</small> @enderror
                                        </div>
                                    @endif

                                    {{-- Badge Section --}}
                                    <div class="md:col-span-2">
                                        <x-select 
                                            label="Badge Type" 
                                            name="form.badge_type" 
                                            wire:model.live="form.badge_type"
                                            placeholder="No Badge"
                                            :options="['' => 'No Badge', 'static' => 'Static Text', 'dynamic' => 'Dynamic (Database Query)']"
                                        />
                                        @error('form.badge_type') <small class="text-error">{{ $message }}</small> @enderror
                                    </div>

                                    @if($form['badge_type'] === 'static')
                                        <div>
                                            <x-input 
                                                label="Badge Text" 
                                                name="form.badge_text" 
                                                wire:model.lazy="form.badge_text" 
                                                placeholder="e.g. 12"
                                            />
                                            @error('form.badge_text') <small class="text-error">{{ $message }}</small> @enderror
                                        </div>
                                    @endif

                                    @if($form['badge_type'] === 'dynamic')
                                        <div class="md:col-span-2">
                                            <x-select 
                                                label="Model" 
                                                name="form.badge_query.model" 
                                                wire:model.live="form.badge_query.model"
                                                placeholder="Select Model"
                                                :options="collect($availableModels)->mapWithKeys(function($model) {
                                                    return [$model['class'] => $model['name']];
                                                })->toArray()"
                                            />
                                            @error('form.badge_query.model') <small class="text-error">{{ $message }}</small> @enderror
                                        </div>

                                        @if(!empty($form['badge_query']['model']))
                                            @php
                                                $modelName = class_basename($form['badge_query']['model']);
                                                $prefixText = $modelName . '::';
                                                $prefixLength = strlen($prefixText);
                                            @endphp
                                            <div class="md:col-span-2">
                                                <label for="badge_query_query">Query</label>
                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-mono text-base leading-6 pointer-events-none whitespace-nowrap">
                                                        {{ $prefixText }}
                                                    </span>
                                                    <input 
                                                        id="badge_query_query" 
                                                        type="text" 
                                                        class="input font-mono"
                                                        style="padding-left: calc(1rem + {{ $prefixLength }}ch + 0.5rem);"
                                                        wire:model.live="form.badge_query.query" 
                                                        placeholder="count()"
                                                    >
                                                </div>
                                                <details class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    <summary class="cursor-pointer hover:text-gray-700 dark:hover:text-gray-300">Common query examples</summary>
                                                    <div class="mt-2 space-y-1 pl-2 border-l-2 border-gray-300 dark:border-gray-600">
                                                        <div><code class="text-xs">count()</code> - Total count</div>
                                                        <div><code class="text-xs">where('status', 'pending')->count()</code> - Count with condition</div>
                                                        <div><code class="text-xs">whereNotNull('email_verified_at')->count()</code> - Count non-null</div>
                                                        <div><code class="text-xs">whereNull('deleted_at')->count()</code> - Count null</div>
                                                        <div><code class="text-xs">where('created_at', '>', now()->subDays(7))->count()</code> - Count last 7 days</div>
                                                        <div><code class="text-xs">distinct('email')->count('email')</code> - Distinct count</div>
                                                        <div><code class="text-xs">exists()</code> - Check if exists (returns 1/0)</div>
                                                    </div>
                                                </details>
                                                @error('form.badge_query.query') <small class="text-error">{{ $message }}</small> @enderror
                                            </div>
                                        @endif
                                    @endif

                                    @if(in_array($form['badge_type'], ['static', 'dynamic']))
                                        <div>
                                            <x-select 
                                                label="Badge Color" 
                                                name="form.badge_color" 
                                                wire:model.live="form.badge_color"
                                                :options="['primary' => 'Primary', 'secondary' => 'Secondary', 'success' => 'Success', 'warning' => 'Warning', 'error' => 'Error', 'sky' => 'Sky']"
                                            />
                                            @error('form.badge_color') <small class="text-error">{{ $message }}</small> @enderror
                                        </div>
                                    @endif

                                    <div>
                                        <x-input 
                                            label="Active Route Pattern" 
                                            name="form.active_pattern" 
                                            wire:model.lazy="form.active_pattern" 
                                            placeholder="admin.orders*"
                                            hint="Default: selected route + .*"
                                        />
                                        @error('form.active_pattern') <small class="text-error">{{ $message }}</small> @enderror
                                    </div>

                                    <div>
                                        <x-select 
                                            label="Target" 
                                            name="form.target" 
                                            wire:model="form.target"
                                            :options="['' => 'Standard', '_blank' => 'New tab', '_self' => 'Same tab']"
                                        />
                                        @error('form.target') <small class="text-error">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="md:col-span-2 flex items-center gap-3">
                                        <x-checkbox label="Active" wire:model="form.is_active" />
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <x-button variant="secondary" wire:click="resetForm">
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
    </div>

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
                        // Reordering sırasında sortable'ları yeniden initialize etme
                        if (isReordering) {
                            return;
                        }
                        
                        document.querySelectorAll('[data-menu-sortable]').forEach(container => {
                            if (container.__sortable) {
                                container.__sortable.destroy();
                            }

                            container.__sortable = new Sortable(container, {
                                group: 'admin-menu',
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
                                    if (! component) {
                                        return;
                                    }

                                    isReordering = true;
                                    component.call('reorderFromFrontend', parentId, orderedIds).then(() => {
                                        isReordering = false;
                                        // Reordering tamamlandıktan sonra sortable'ları yeniden initialize et
                                        setTimeout(() => {
                                            initializeSortables();
                                        }, 100);
                                    }).catch(() => {
                                        isReordering = false;
                                    });
                                },
                            });
                        });
                    };

                    initializeSortables();

                    Livewire.hook('morph.updated', ({ component }) => {
                        // Reordering sırasında morph.updated'i ignore et
                        if (isReordering) {
                            return;
                        }
                        
                        const componentEl = document.querySelector(`[wire\\:id="${component.id}"]`);
                        if (componentEl && componentEl.querySelector('[data-menu-sortable]')) {
                            setTimeout(() => {
                                initializeSortables();
                            }, 100);
                        }
                    });

                    Livewire.on('menu-tree-mounted', () => {
                        setTimeout(() => {
                            initializeSortables();
                        }, 100);
                    });
                });
            </script>
        @endonce
    @endpush
</div>
