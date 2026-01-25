<x-layouts.admin title="Contacts">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Contacts</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage contacts and their ticket submissions</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.contacts.create') }}">New Contact</x-button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-4">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <form method="GET" action="{{ route('admin.contacts.index') }}" class="flex gap-2">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search by email, name, or organisation..."
                            class="flex-1 px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                        <x-button variant="secondary" type="submit" icon="search">Search</x-button>
                        @if($search)
                            <x-button variant="secondary" href="{{ route('admin.contacts.index', ['filter' => $filter]) }}">Clear</x-button>
                        @endif
                    </form>
                </div>
                
                <!-- Filter Tabs -->
                <div class="flex items-center gap-2 overflow-x-auto">
                    <a href="{{ route('admin.contacts.index', ['filter' => 'all', 'search' => $search]) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors {{ $filter === 'all' ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-600' }}">
                        All ({{ $counts['all'] }})
                    </a>
                    <a href="{{ route('admin.contacts.index', ['filter' => 'with_unread', 'search' => $search]) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors {{ $filter === 'with_unread' ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-600' }}">
                        Unread ({{ $counts['with_unread'] }})
                    </a>
                    <a href="{{ route('admin.contacts.index', ['filter' => 'active', 'search' => $search]) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors {{ $filter === 'active' ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-600' }}">
                        Active ({{ $counts['active'] }})
                    </a>
                    <a href="{{ route('admin.contacts.index', ['filter' => 'new', 'search' => $search]) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors {{ $filter === 'new' ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-600' }}">
                        New ({{ $counts['new'] }})
                    </a>
                    <a href="{{ route('admin.contacts.index', ['filter' => 'resolved', 'search' => $search]) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors {{ $filter === 'resolved' ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-600' }}">
                        Resolved ({{ $counts['resolved'] }})
                    </a>
                    <a href="{{ route('admin.contacts.index', ['filter' => 'closed', 'search' => $search]) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors {{ $filter === 'closed' ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-600' }}">
                        Closed ({{ $counts['closed'] }})
                    </a>
                </div>
            </div>
        </div>

        @if($contacts->count() > 0)
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Submissions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Last Contacted</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($contacts as $contact)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-900/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center shrink-0">
                                                <i class="fas fa-user text-indigo-600 dark:text-indigo-400"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-zinc-900 dark:text-white">
                                                    <a class="hover:underline" href="{{ route('admin.contacts.show', $contact) }}">
                                                        {{ $contact->display_name }}
                                                    </a>
                                                </div>
                                                <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $contact->email }}</div>
                                                @if($contact->organisation)
                                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $contact->organisation }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-ui.badge :color="match($contact->status) {
                                            'new' => 'blue',
                                            'active' => 'green',
                                            'pending' => 'yellow',
                                            'resolved' => 'gray',
                                            'closed' => 'gray',
                                            default => 'gray'
                                        }">
                                            {{ $contact->status_label }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-ui.badge :color="match($contact->priority) {
                                            'low' => 'gray',
                                            'normal' => 'blue',
                                            'high' => 'yellow',
                                            'urgent' => 'red',
                                            default => 'blue'
                                        }">
                                            {{ $contact->priority_label }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-zinc-900 dark:text-white">
                                            {{ $contact->total_submissions_count }} total
                                        </div>
                                        @if($contact->unread_count > 0)
                                            <div class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">
                                                {{ $contact->unread_count }} unread
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                        @if($contact->last_contacted_at)
                                            {{ $contact->last_contacted_at->diffForHumans() }}
                                        @else
                                            Never
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <x-button
                                                variant="secondary"
                                                size="sm"
                                                icon="eye"
                                                href="{{ route('admin.contacts.show', $contact) }}"
                                                title="View"
                                            />
                                            <x-button
                                                variant="secondary"
                                                size="sm"
                                                icon="edit"
                                                href="{{ route('admin.contacts.edit', $contact) }}"
                                                title="Edit"
                                            />
                                            <x-button
                                                variant="error"
                                                size="sm"
                                                icon="trash"
                                                type="button"
                                                x-data="{ showDeleteModal: false }"
                                                x-on:click="showDeleteModal = true"
                                                title="Delete"
                                            />

                                            <x-ui.modal alpine-show="showDeleteModal" size="sm">
                                                <x-slot:title>Delete Contact</x-slot:title>
                                                <p class="text-zinc-600 dark:text-zinc-400">Delete <strong>{{ $contact->display_name }}</strong>? This will also delete all associated submissions.</p>
                                                <x-slot:footer>
                                                    <x-button variant="secondary" x-on:click="showDeleteModal = false">Cancel</x-button>
                                                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <x-button variant="primary" color="red" type="submit" x-on:click="showDeleteModal = false">Delete</x-button>
                                                    </form>
                                                </x-slot:footer>
                                            </x-ui.modal>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($contacts->hasPages())
                    <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
                        {{ $contacts->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-12 text-center">
                <i class="fas fa-users text-4xl text-zinc-400 dark:text-zinc-600 mb-4"></i>
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">No Contacts Found</h3>
                <p class="text-zinc-600 dark:text-zinc-400 mb-6">
                    @if($search || $filter !== 'all')
                        No contacts match your search criteria.
                    @else
                        Create a contact to start managing tickets.
                    @endif
                </p>
                @if(!$search && $filter === 'all')
                    <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.contacts.create') }}">Create Contact</x-button>
                @endif
            </div>
        @endif
    </div>
</x-layouts.admin>
