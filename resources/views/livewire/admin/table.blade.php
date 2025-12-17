<div>
    {{-- Search and Filters --}}
    <div class="mb-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex-1 w-full sm:max-w-md">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-zinc-400"></i>
                </div>
                @if($isExternal)
                    {{-- Controller Mode: Standard Form Submission --}}
                    <form action="{{ request()->url() }}" method="GET">
                        <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="{{ $searchPlaceholder }}"
                                class="block w-full pl-10 pr-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-md leading-5 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 placeholder-zinc-500 focus:outline-none focus:placeholder-zinc-400 focus:ring-1 focus:ring-[var(--color-accent)] focus:border-[var(--color-accent)] text-sm"
                        />
                        @foreach(request()->except(['search', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                    </form>
                @else
                    {{-- Livewire Mode: Real-time Search --}}
                    <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="{{ $searchPlaceholder }}"
                            class="block w-full pl-10 pr-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-md leading-5 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 placeholder-zinc-500 focus:outline-none focus:placeholder-zinc-400 focus:ring-1 focus:ring-[var(--color-accent)] focus:border-[var(--color-accent)] text-sm"
                    />
                @endif
            </div>
        </div>

        {{-- Bulk Actions --}}
        <div x-data="{ showBulkDeleteModal: false }">
            @if($showBulkDelete && count($selected) > 0)
                <div class="flex items-center gap-2">
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">
                        {{ count($selected) }} selected
                    </span>
                    <x-button
                            variant="error"
                            size="sm"
                            x-on:click="showBulkDeleteModal = true"
                    >
                        Delete Selected
                    </x-button>
                </div>
            @endif

            <x-ui.modal alpine-show="showBulkDeleteModal" size="sm">
                <x-slot:title>Delete Selected Items</x-slot:title>
                <p class="text-zinc-600 dark:text-zinc-400">
                    Are you sure you want to delete <strong>{{ count($selected) }}</strong> selected {{ count($selected) === 1 ? 'item' : 'items' }}? This action cannot be undone.
                </p>
                <x-slot:footer>
                    <x-button variant="secondary" x-on:click="showBulkDeleteModal = false">Cancel</x-button>
                    <x-button
                            variant="primary"
                            color="red"
                            type="button"
                            wire:click="deleteSelected"
                            x-on:click="showBulkDeleteModal = false"
                    >
                        Delete
                    </x-button>
                </x-slot:footer>
            </x-ui.modal>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session()->has('message'))
        <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md text-sm text-green-800 dark:text-green-200">
            {{ session('message') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-300 dark:divide-zinc-700">
                <thead class="bg-zinc-100 dark:bg-zinc-800">
                <tr>
                    @if($showCheckbox)
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider w-12">
                            <x-ui.checkbox
                                    name="selectAll"
                                    value="1"
                                    label=""
                                    color="primary"
                                    wire:model.live="selectAll"
                            />
                        </th>
                    @endif

                    @foreach($columns as $index => $column)
                        @php
                            // Columns are now always normalized to array format with 'key'
                            $field = $column['key'] ?? $index;
                            $label = $column['label'] ?? ucfirst(str_replace('_', ' ', $field));
                            $sortable = $this->isColumnSortable($field);
                        @endphp
                        <th
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider {{ $sortable ? 'cursor-pointer hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors' : '' }}"
                                @if($sortable)
                                    @if($isExternal)
                                        {{-- Controller Mode: Link Sorting --}}
                                        onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => $field, 'direction' => request('sort') === $field && request('direction') === 'asc' ? 'desc' : 'asc']) }}'"
                                @else
                                    {{-- Livewire Mode: Method Sorting --}}
                                    wire:click="sortBy('{{ $field }}')"
                                @endif
                                @endif
                        >
                            <div class="flex items-center gap-2">
                                <span>{{ $label }}</span>
                                @if($sortable)
                                    @if($isExternal)
                                        @if(request('sort') === $field)
                                            <i class="fa-solid fa-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }} text-xs"></i>
                                        @endif
                                    @else
                                        @if($sortField === $field)
                                            <i class="fa-solid fa-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-xs"></i>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </th>
                    @endforeach

                    @if($showActions)
                        <th class="px-6 py-3 text-right text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider w-24">Actions</th>
                    @endif
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-300 dark:divide-zinc-700">
                @if($this->items->count() > 0)
                    @foreach($this->items as $item)
                        <tr wire:key="row-{{ $item->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors {{ in_array($item->id, $selected) ? 'bg-zinc-100 dark:bg-zinc-700' : '' }}" x-data="{ showDeleteModal{{ $item->id }}: false }">
                            @if($showCheckbox)
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <x-ui.checkbox
                                            name="selected"
                                            value="{{ $item->id }}"
                                            label=""
                                            color="primary"
                                            wire:model.live="selected"
                                    />
                                </td>
                            @endif

                            @foreach($columns as $index => $column)
                                @php
                                    // Columns are now always normalized to array format with 'key'
                                    $field = $column['key'] ?? $index;
                                    $columnType = $column['type'] ?? null;
                                    // Support relationship fields like 'user.name'
                                    $value = data_get($item, $field);
                                    // Format date fields
                                    if ($value instanceof \Carbon\Carbon) {
                                        $value = $value->format('Y-m-d');
                                    } elseif (is_array($column) && isset($column['format']) && $column['format'] === 'date' && $value) {
                                        $value = \Carbon\Carbon::parse($value)->format('Y-m-d');
                                    }
                                @endphp
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                    @if($columnType === 'toggle')
                                        <x-toggle
                                                name="is_active_{{ $item->id }}"
                                                :checked="(bool) $value"
                                                label=""
                                                wire:change="toggleField({{ $item->id }}, '{{ addslashes($field) }}')"
                                        />
                                    @elseif($columnType === 'file-name')
                                        @php
                                            $icon = '';
                                            $iconClass = 'text-zinc-500 dark:text-zinc-400';

                                            if(str_starts_with($item->mime_type ?? '', 'image/')) {
                                                $icon = 'fa-image';
                                                $iconClass = 'text-sky-600 dark:text-sky-400';
                                            } elseif(str_starts_with($item->mime_type ?? '', 'video/')) {
                                                $icon = 'fa-video';
                                                $iconClass = 'text-sky-600 dark:text-sky-400';
                                            } elseif(str_starts_with($item->mime_type ?? '', 'audio/')) {
                                                $icon = 'fa-music';
                                                $iconClass = 'text-sky-600 dark:text-sky-400';
                                            } elseif(($item->mime_type ?? '') === 'application/pdf') {
                                                $icon = 'fa-file-pdf';
                                                $iconClass = 'text-red-600 dark:text-red-400';
                                            } elseif(in_array($item->extension ?? [], ['doc', 'docx'])) {
                                                $icon = 'fa-file-word';
                                                $iconClass = 'text-blue-600 dark:text-blue-400';
                                            } elseif(in_array($item->extension ?? [], ['xls', 'xlsx'])) {
                                                $icon = 'fa-file-excel';
                                                $iconClass = 'text-green-600 dark:text-green-400';
                                            } else {
                                                $icon = 'fa-file';
                                            }

                                            $originalName = $item->original_name ?? $value ?? '';
                                            $fileName = $item->file_name ?? '';
                                        @endphp
                                        <div class="flex items-center gap-3">
                                            <i class="fa-solid {{ $icon }} {{ $iconClass }}"></i>
                                            <div>
                                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $originalName }}</div>
                                                @if($fileName && $fileName !== $originalName)
                                                    <div class="text-xs text-zinc-600 dark:text-zinc-400">{{ $fileName }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif($columnType === 'file-size')
                                        @php
                                            $fileSize = $item->file_size ?? $value ?? 0;
                                        @endphp
                                        {{ $fileSize ? number_format($fileSize / 1024, 2) . ' KB' : 'N/A' }}
                                    @elseif($columnType === 'file-type')
                                        {{ $item->mime_type ?? $value ?? 'N/A' }}
                                    @elseif($columnType === 'color')
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full border border-gray-300 dark:border-white/10" style="background-color: {{ $value ?? '#3B82F6' }}"></div>
                                            <span class="text-xs font-mono text-zinc-600 dark:text-zinc-400">{{ $value ?? 'N/A' }}</span>
                                        </div>
                                    @elseif($columnType === 'text' && isset($column['limit']))
                                        {{ Str::limit($value ?? '', $column['limit']) }}
                                    @elseif($columnType === 'custom')
                                        @if(isset($column['render']) && is_callable($column['render']))
                                            {!! $column['render']($item) !!}
                                        @elseif(isset($column['view']))
                                            @include($column['view'], ['item' => $item])
                                        @endif
                                    @else
                                        {{ $value }}
                                    @endif
                                </td>
                            @endforeach

                            @if($showActions)
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($customActionsView)
                                            @include($customActionsView, ['item' => $item, 'file' => $item])
                                        @else
                                            @if(in_array('view', $actions))
                                                @php
                                                    $viewRoute = $this->view($item->id);
                                                    $isFile = isset($item->file_path) && $item->file_path;
                                                @endphp
                                                @if($viewRoute)
                                                    <a href="{{ $viewRoute }}" @if($isFile) target="_blank" @endif>
                                                        <x-button variant="sky" size="sm" icon="eye" title="View"></x-button>
                                                    </a>
                                                @else
                                                    <x-button variant="sky" size="sm" icon="eye" title="View" disabled></x-button>
                                                @endif
                                            @endif

                                            @if(in_array('download', $actions))
                                                @php $downloadUrl = $this->download($item->id); @endphp
                                                @if($downloadUrl)
                                                    <a href="{{ $downloadUrl }}" download>
                                                        <x-button variant="sky" size="sm" icon="download" title="Download"></x-button>
                                                    </a>
                                                @else
                                                    <x-button variant="sky" size="sm" icon="download" title="Download" disabled></x-button>
                                                @endif
                                            @endif

                                            @if(in_array('edit', $actions))
                                                @php $editRoute = $this->edit($item->id); @endphp
                                                @if($editRoute)
                                                    <a href="{{ $editRoute }}">
                                                        <x-button variant="warning" size="sm" icon="edit" title="Edit"></x-button>
                                                    </a>
                                                @else
                                                    <x-button variant="warning" size="sm" icon="edit" title="Edit" disabled></x-button>
                                                @endif
                                            @endif

                                            @if(in_array('delete', $actions))
                                                @php
                                                    $modelName = class_basename($this->modelClass);
                                                @endphp
                                                <x-button
                                                        variant="error"
                                                        size="sm"
                                                        icon="trash"
                                                        title="Delete"
                                                        type="button"
                                                        x-on:click="showDeleteModal{{ $item->id }} = true"
                                                ></x-button>

                                                <x-ui.modal modal-id="delete-modal-{{ $item->id }}" alpine-show="showDeleteModal{{ $item->id }}" size="sm">
                                                    <x-slot:title>Delete {{ $modelName }}</x-slot:title>
                                                    <div class="space-y-4">
                                                        <p class="text-zinc-600 dark:text-zinc-400">
                                                            Are you sure you want to delete this item? This action cannot be undone.
                                                        </p>

                                                        <div class="bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 space-y-2">
                                                            @foreach($columns as $index => $column)
                                                                @php
                                                                    $field = is_array($column) ? ($column['key'] ?? $index) : $index;
                                                                    $label = is_array($column) ? ($column['label'] ?? ucfirst(str_replace('_', ' ', $field))) : ucfirst(str_replace('_', ' ', $field));
                                                                    $value = data_get($item, $field);

                                                                    // Skip if value is null or empty, or if it's a toggle/action column
                                                                    $columnType = is_array($column) && isset($column['type']) ? $column['type'] : null;
                                                                    if ($columnType === 'toggle' || $columnType === 'custom' || $value === null || $value === '') {
                                                                        continue;
                                                                    }

                                                                    // Format date fields
                                                                    if ($value instanceof \Carbon\Carbon) {
                                                                        $value = $value->format('d.m.Y H:i');
                                                                    } elseif (is_array($column) && isset($column['format']) && $column['format'] === 'date' && $value) {
                                                                        $value = \Carbon\Carbon::parse($value)->format('d.m.Y');
                                                                    }

                                                                    // Format file-size
                                                                    if ($columnType === 'file-size' && $value) {
                                                                        $value = number_format($value / 1024, 2) . ' KB';
                                                                    }
                                                                @endphp
                                                                <div class="text-sm">
                                                                    <span class="text-zinc-500 dark:text-zinc-400 font-medium">{{ $label }}:</span>
                                                                    <span class="text-zinc-700 dark:text-zinc-300 ml-1">{{ $value }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <x-slot:footer>
                                                        <x-button variant="secondary" x-on:click="showDeleteModal{{ $item->id }} = false">Cancel</x-button>
                                                        <x-button
                                                                variant="primary"
                                                                color="red"
                                                                type="button"
                                                                wire:click="delete({{ $item->id }})"
                                                                x-on:click="showDeleteModal{{ $item->id }} = false"
                                                        >Delete</x-button>
                                                    </x-slot:footer>
                                                </x-ui.modal>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ count($columns) + ($showCheckbox ? 1 : 0) + ($showActions ? 1 : 0) }}" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-zinc-600 dark:text-zinc-400">
                                <i class="fa-solid fa-inbox text-4xl mb-3 opacity-50"></i>
                                <p class="text-sm">No items found</p>
                            </div>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($this->items->hasPages())
            <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                {{ $this->items->links() }}
            </div>
        @endif
    </div>
</div>