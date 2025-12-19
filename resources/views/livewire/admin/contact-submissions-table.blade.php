<div>
    {{-- Filter Tabs --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        {{-- Vertical Tabs --}}
        <div class="flex sm:flex-col gap-1 sm:w-44 flex-shrink-0">
            <button
                wire:click="setFilter('unread')"
                class="flex items-center justify-between gap-2 px-4 py-3 text-sm font-medium rounded-lg border {{ $filter === 'unread' ? 'bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white border-zinc-200 dark:border-zinc-700' : 'border-transparent text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
            >
                <span class="flex items-center gap-2">
                    <i class="fas fa-envelope text-blue-500"></i>
                    Unread
                </span>
                @if($this->counts['unread'] > 0)
                    <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300">
                        {{ $this->counts['unread'] }}
                    </span>
                @endif
            </button>
            
            <button
                wire:click="setFilter('read')"
                class="flex items-center justify-between gap-2 px-4 py-3 text-sm font-medium rounded-lg border {{ $filter === 'read' ? 'bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white border-zinc-200 dark:border-zinc-700' : 'border-transparent text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
            >
                <span class="flex items-center gap-2">
                    <i class="fas fa-envelope-open text-green-500"></i>
                    Read
                </span>
                @if($this->counts['read'] > 0)
                    <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300">
                        {{ $this->counts['read'] }}
                    </span>
                @endif
            </button>
            
            <button
                wire:click="setFilter('archived')"
                class="flex items-center justify-between gap-2 px-4 py-3 text-sm font-medium rounded-lg border {{ $filter === 'archived' ? 'bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white border-zinc-200 dark:border-zinc-700' : 'border-transparent text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
            >
                <span class="flex items-center gap-2">
                    <i class="fas fa-archive text-yellow-500"></i>
                    Archived
                </span>
                @if($this->counts['archived'] > 0)
                    <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300">
                        {{ $this->counts['archived'] }}
                    </span>
                @endif
            </button>
        </div>

        {{-- Main Content --}}
        <div class="flex-1">
            {{-- Search and Bulk Actions --}}
            <div class="mb-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex-1 w-full sm:max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-search text-zinc-400"></i>
                        </div>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search messages..."
                            class="block w-full pl-10 pr-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-md leading-5 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 placeholder-zinc-500 focus:outline-none focus:ring-1 focus:ring-[var(--color-accent)] focus:border-[var(--color-accent)] text-sm"
                        />
                    </div>
                </div>

                {{-- Bulk Actions --}}
                <div x-data="{ showDeleteModal: false }">
                    @if(count($selected) > 0)
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm text-zinc-600 dark:text-zinc-400">
                                {{ count($selected) }} selected
                            </span>
                            
                            @if($filter !== 'read')
                                <x-button variant="success" size="sm" icon="envelope-open" wire:click="markSelectedAsRead">
                                    Mark Read
                                </x-button>
                            @endif
                            
                            @if($filter !== 'unread')
                                <x-button variant="primary" size="sm" icon="envelope" wire:click="markSelectedAsUnread">
                                    Mark Unread
                                </x-button>
                            @endif
                            
                            @if($filter !== 'archived')
                                <x-button variant="warning" size="sm" icon="archive" wire:click="archiveSelected">
                                    Archive
                                </x-button>
                            @else
                                <x-button variant="secondary" size="sm" icon="undo" wire:click="unarchiveSelected">
                                    Restore
                                </x-button>
                            @endif
                            
                            <x-button variant="error" size="sm" icon="trash" x-on:click="showDeleteModal = true">
                                Delete
                            </x-button>
                        </div>
                    @endif

                    <x-ui.modal alpine-show="showDeleteModal" size="sm">
                        <x-slot:title>Delete Selected Messages</x-slot:title>
                        <p class="text-zinc-600 dark:text-zinc-400">
                            Are you sure you want to permanently delete <strong>{{ count($selected) }}</strong> selected message(s)? This action cannot be undone.
                        </p>
                        <x-slot:footer>
                            <x-button variant="secondary" x-on:click="showDeleteModal = false">Cancel</x-button>
                            <x-button
                                variant="primary"
                                color="red"
                                type="button"
                                wire:click="deleteSelected"
                                x-on:click="showDeleteModal = false"
                            >Delete</x-button>
                        </x-slot:footer>
                    </x-ui.modal>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider w-12">
                                    <x-ui.checkbox
                                        name="selectAll"
                                        value="1"
                                        label=""
                                        color="primary"
                                        wire:model.live="selectAll"
                                    />
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider w-8">
                                    
                                </th>
                                @foreach($columns as $column)
                                    @php
                                        $field = $column['key'];
                                        $label = $column['label'];
                                        $sortable = $column['sortable'] ?? false;
                                    @endphp
                                    <th 
                                        class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider {{ $sortable ? 'cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-700' : '' }}"
                                        @if($sortable) wire:click="sortBy('{{ $field }}')" @endif
                                    >
                                        <div class="flex items-center gap-1">
                                            {{ $label }}
                                            @if($sortable && $sortField === $field)
                                                <i class="fas fa-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-[10px]"></i>
                                            @endif
                                        </div>
                                    </th>
                                @endforeach
                                <th class="px-4 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider w-32">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse($this->items as $item)
                                <tr 
                                    wire:key="row-{{ $item->id }}" 
                                    class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors {{ in_array($item->id, $selected) ? 'bg-blue-50 dark:bg-blue-900/20' : '' }} {{ !$item->is_read ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}"
                                    x-data="{ showDeleteModal: false }"
                                >
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <x-ui.checkbox
                                            name="selected"
                                            value="{{ $item->id }}"
                                            label=""
                                            color="primary"
                                            wire:model.live="selected"
                                        />
                                    </td>
                                    <td class="px-2 py-3 whitespace-nowrap">
                                        @if(!$item->is_read)
                                            <span class="inline-block w-2 h-2 bg-blue-500 rounded-full" title="Unread"></span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                        {{ $item->id }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <a href="{{ route('admin.contact-submissions.show', $item) }}" class="text-sm font-medium text-zinc-900 dark:text-zinc-100 hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ $item->full_name }}
                                        </a>
                                        @if($item->organisation)
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $item->organisation }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-400">
                                        {{ $item->email }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-100 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-300">
                                            {{ $item->subject_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($item->is_archived)
                                            <x-badge variant="warning" size="sm">Archived</x-badge>
                                        @elseif($item->is_read)
                                            <x-badge variant="success" size="sm">Read</x-badge>
                                        @else
                                            <x-badge variant="primary" size="sm">Unread</x-badge>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ $item->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('admin.contact-submissions.show', $item) }}">
                                                <x-button variant="sky" size="sm" icon="eye" title="View"></x-button>
                                            </a>
                                            <x-button 
                                                variant="{{ $item->is_read ? 'secondary' : 'success' }}" 
                                                size="sm" 
                                                icon="{{ $item->is_read ? 'envelope' : 'envelope-open' }}" 
                                                title="{{ $item->is_read ? 'Mark Unread' : 'Mark Read' }}"
                                                wire:click="toggleRead({{ $item->id }})"
                                            ></x-button>
                                            <x-button
                                                variant="error"
                                                size="sm"
                                                icon="trash"
                                                title="Delete"
                                                x-on:click="showDeleteModal = true"
                                            ></x-button>
                                            
                                            <x-ui.modal alpine-show="showDeleteModal" size="sm">
                                                <x-slot:title>Delete Message</x-slot:title>
                                                <p class="text-zinc-600 dark:text-zinc-400">
                                                    Are you sure you want to delete the message from <strong>{{ $item->full_name }}</strong>? This action cannot be undone.
                                                </p>
                                                <x-slot:footer>
                                                    <x-button variant="secondary" x-on:click="showDeleteModal = false">Cancel</x-button>
                                                    <x-button
                                                        variant="primary"
                                                        color="red"
                                                        wire:click="delete({{ $item->id }})"
                                                        x-on:click="showDeleteModal = false"
                                                    >Delete</x-button>
                                                </x-slot:footer>
                                            </x-ui.modal>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-zinc-500 dark:text-zinc-400">
                                            <i class="fas fa-inbox text-4xl mb-3 opacity-50"></i>
                                            <p class="text-sm">
                                                @if($filter === 'unread')
                                                    All messages have been read
                                                @elseif($filter === 'read')
                                                    No read messages
                                                @elseif($filter === 'archived')
                                                    No archived messages
                                                @else
                                                    No messages found
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
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
    </div>
</div>
