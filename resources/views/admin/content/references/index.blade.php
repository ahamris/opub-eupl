<x-layouts.admin title="References">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">References</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage external links on the Verwijzingen page</p>
            </div>
            <x-button 
                variant="primary" 
                icon="plus" 
                icon-position="left" 
                type="button"
                x-on:click="$dispatch('open-drawer', { id: 'drawer-create-reference' })"
            >
                Add Reference
            </x-button>
        </div>

        @if(session('success'))
            <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
        @endif

        <!-- References Table -->
        <livewire:admin.table
            :resource="\App\Models\Reference::class"
            :columns="[
                ['key' => 'icon', 'label' => 'Icon', 'type' => 'custom', 'view' => 'livewire.admin.table-columns.reference-icon', 'sortable' => false],
                ['key' => 'title', 'label' => 'Title', 'type' => 'custom', 'view' => 'livewire.admin.table-columns.reference-title', 'sortable' => true],
                ['key' => 'link_url', 'label' => 'Link', 'type' => 'custom', 'view' => 'livewire.admin.table-columns.reference-link', 'sortable' => false],
                ['key' => 'sort_order', 'label' => 'Order', 'sortable' => true],
                ['key' => 'is_active', 'type' => 'toggle', 'label' => 'Status'],
                ['key' => 'created_at', 'format' => 'date', 'label' => 'Created'],
            ]"
            route-prefix="admin.content.reference"
            search-placeholder="Search references..."
            :paginate="15"
            sort-field="sort_order"
            sort-direction="asc"
        />

        <!-- Drawers for References (only for current page items) -->
        @php
            // Get current page items - limit to prevent performance issues
            // In production, consider using Livewire to load drawer content dynamically
            $references = \App\Models\Reference::orderBy('sort_order', 'asc')->limit(50)->get();
        @endphp
        @foreach($references as $reference)
            <x-ui.drawer 
                id="drawer-view-{{ $reference->id }}"
                title="View Reference"
                width="max-w-2xl"
            >
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Icon</label>
                            <p class="mt-1 text-sm text-zinc-900 dark:text-white">
                                <i class="{{ $reference->icon }}"></i>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Title</label>
                            <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $reference->title }}</p>
                        </div>
                        @if($reference->description)
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Description</label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $reference->description }}</p>
                            </div>
                        @endif
                        @if($reference->link_url)
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Link URL</label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-white">
                                    <a href="{{ $reference->link_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
                                        {{ $reference->link_url }}
                                    </a>
                                </p>
                            </div>
                        @endif
                        @if($reference->link_text)
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Link Text</label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $reference->link_text }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Sort Order</label>
                            <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $reference->sort_order }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Status</label>
                            <p class="mt-1">
                                @if($reference->is_active)
                                    <x-badge variant="success">Active</x-badge>
                                @else
                                    <x-badge variant="warning">Inactive</x-badge>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Created At</label>
                            <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $reference->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </div>
            </x-ui.drawer>

            <x-ui.drawer 
                id="drawer-edit-{{ $reference->id }}"
                title="Edit Reference"
                width="max-w-2xl"
            >
                <form 
                    action="{{ route('admin.content.reference.update', $reference) }}" 
                    method="POST" 
                    class="space-y-6"
                    x-on:submit="setTimeout(() => { $dispatch('close-drawer', { id: 'drawer-edit-{{ $reference->id }}' }); }, 100)"
                >
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <x-input 
                            label="Title" 
                            name="title" 
                            type="text" 
                            placeholder="Reference title"
                            value="{{ old('title', $reference->title) }}"
                            required
                        />

                        <x-ui.textarea 
                            label="Description" 
                            name="description" 
                            placeholder="Brief description"
                            rows="3"
                            value="{{ old('description', $reference->description) }}"
                        />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input 
                                label="Link URL" 
                                name="link_url" 
                                type="text" 
                                placeholder="https://example.com"
                                value="{{ old('link_url', $reference->link_url) }}"
                            />
                            <x-input 
                                label="Link Text" 
                                name="link_text" 
                                type="text" 
                                placeholder="e.g. example.com"
                                value="{{ old('link_text', $reference->link_text) }}"
                            />
                        </div>

                        <x-ui.checkbox 
                            label="Active" 
                            name="is_active" 
                            value="1"
                            :checked="old('is_active', $reference->is_active)"
                            hint="Show this reference on the page"
                        />

                        <x-input 
                            label="Icon Class" 
                            name="icon" 
                            type="text" 
                            placeholder="fas fa-link"
                            value="{{ old('icon', $reference->icon) }}"
                            required
                        />
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Use FontAwesome classes (e.g. fas fa-gavel)</p>

                        <x-input 
                            label="Sort Order"
                            name="sort_order" 
                            type="number" 
                            placeholder="0"
                            value="{{ old('sort_order', $reference->sort_order) }}"
                        />
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <x-button 
                            variant="secondary" 
                            type="button" 
                            x-on:click="$dispatch('close-drawer', { id: 'drawer-edit-{{ $reference->id }}' })"
                        >
                            Cancel
                        </x-button>
                        <x-button variant="primary" type="submit" icon="save" icon-position="left">
                            Update Reference
                        </x-button>
                    </div>
                </form>
            </x-ui.drawer>
        @endforeach

        <!-- Create Drawer -->
        <x-ui.drawer 
            id="drawer-create-reference"
            title="Create Reference"
            width="max-w-2xl"
        >
            <form 
                action="{{ route('admin.content.reference.store') }}" 
                method="POST" 
                class="space-y-6"
                x-on:submit="setTimeout(() => { $dispatch('close-drawer', { id: 'drawer-create-reference' }); }, 100)"
            >
                @csrf

                <div class="space-y-4">
                    <x-input 
                        label="Title" 
                        name="title" 
                        type="text" 
                        placeholder="Reference title"
                        value="{{ old('title') }}"
                        required
                    />

                    <x-ui.textarea 
                        label="Description" 
                        name="description" 
                        placeholder="Brief description"
                        rows="3"
                        value="{{ old('description') }}"
                    />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input 
                            label="Link URL" 
                            name="link_url" 
                            type="text" 
                            placeholder="https://example.com"
                            value="{{ old('link_url') }}"
                        />
                        <x-input 
                            label="Link Text" 
                            name="link_text" 
                            type="text" 
                            placeholder="e.g. example.com"
                            value="{{ old('link_text') }}"
                        />
                    </div>

                    <x-ui.checkbox 
                        label="Active" 
                        name="is_active" 
                        value="1"
                        :checked="old('is_active', true)"
                        hint="Show this reference on the page"
                    />

                    <x-input 
                        label="Icon Class" 
                        name="icon" 
                        type="text" 
                        placeholder="fas fa-link"
                        value="{{ old('icon', 'fas fa-link') }}"
                        required
                    />
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Use FontAwesome classes (e.g. fas fa-gavel)</p>

                    <x-input 
                        label="Sort Order"
                        name="sort_order" 
                        type="number" 
                        placeholder="0"
                        value="{{ old('sort_order', 0) }}"
                    />
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <x-button 
                        variant="secondary" 
                        type="button" 
                        x-on:click="$dispatch('close-drawer', { id: 'drawer-create-reference' })"
                    >
                        Cancel
                    </x-button>
                    <x-button variant="primary" type="submit" icon="save" icon-position="left">
                        Create Reference
                    </x-button>
                </div>
            </form>
        </x-ui.drawer>
    </div>
</x-layouts.admin>
