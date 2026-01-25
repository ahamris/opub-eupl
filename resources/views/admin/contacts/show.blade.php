<x-layouts.admin title="Contact Details">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">{{ $contact->display_name }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Contact details and ticket history</p>
            </div>
            <div class="flex items-center gap-3" x-data="{ showDeleteModal: false }">
                @php
                    $menuItems = [
                        [
                            'label' => 'Edit Contact',
                            'icon' => 'edit',
                            'href' => route('admin.contacts.edit', $contact),
                        ],
                        [
                            'label' => 'View Submissions',
                            'icon' => 'envelope',
                            'href' => route('admin.contact-submissions.index', ['contact' => $contact->id]),
                        ],
                    ];
                    
                    $menuItems[] = [
                        'label' => 'Delete Contact',
                        'icon' => 'trash',
                        'type' => 'button',
                        'action' => 'showDeleteModal = true',
                        'color' => 'red',
                    ];
                @endphp
                
                <x-ui.dropdown :menuItems="$menuItems">
                    <x-slot:trigger>
                        <x-button variant="primary" icon="cog" icon-position="left" type="button">
                            Actions
                        </x-button>
                    </x-slot:trigger>
                </x-ui.dropdown>
                <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.contacts.index') }}">Back to Contacts</x-button>
                
                <x-ui.modal alpine-show="showDeleteModal" size="sm">
                    <x-slot:title>Delete Contact</x-slot:title>
                    <p class="text-zinc-600 dark:text-zinc-400">Are you sure you want to delete <strong>{{ $contact->display_name }}</strong>? This will also delete all associated submissions. This action cannot be undone.</p>
                    <x-slot:footer>
                        <x-button variant="secondary" x-on:click="showDeleteModal = false">Cancel</x-button>
                        <x-button 
                            variant="primary"
                            color="red"
                            x-on:click="axios.post('{{ route('admin.contacts.destroy', $contact) }}', { _method: 'DELETE' }).then(() => { toastManager.show('success', 'Contact deleted successfully.'); showDeleteModal = false; setTimeout(() => window.location.href = '{{ route('admin.contacts.index') }}', 500); }).catch(() => { toastManager.show('error', 'An error occurred while deleting the contact.'); showDeleteModal = false; });"
                        >Delete</x-button>
                    </x-slot:footer>
                </x-ui.modal>
            </div>
        </div>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Contact Details (2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Contact Information Card -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-user text-[var(--color-accent)]"></i>
                            Contact Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Email</label>
                                <p class="text-zinc-900 dark:text-white mt-1">{{ $contact->email }}</p>
                            </div>
                            
                            @if($contact->full_name)
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Full Name</label>
                                <p class="text-zinc-900 dark:text-white mt-1">{{ $contact->full_name }}</p>
                            </div>
                            @endif
                            
                            @if($contact->organisation)
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Organisation</label>
                                <p class="text-zinc-900 dark:text-white mt-1">{{ $contact->organisation }}</p>
                            </div>
                            @endif
                            
                            @if($contact->phone)
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Phone</label>
                                <p class="text-zinc-900 dark:text-white mt-1">{{ $contact->phone }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status & Priority Card -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-toggle-on text-[var(--color-accent)]"></i>
                            Status & Priority
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Status</label>
                                <p class="mt-1">
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
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Priority</label>
                                <p class="mt-1">
                                    <x-ui.badge :color="match($contact->priority) {
                                        'low' => 'gray',
                                        'normal' => 'blue',
                                        'high' => 'yellow',
                                        'urgent' => 'red',
                                        default => 'blue'
                                    }">
                                        {{ $contact->priority_label }}
                                    </x-ui.badge>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Card -->
                @if($contact->notes)
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-sticky-note text-[var(--color-accent)]"></i>
                            Notes
                        </h3>
                        <p class="text-zinc-900 dark:text-white whitespace-pre-wrap">{{ $contact->notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Recent Submissions Card -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                                <i class="fas fa-envelope text-[var(--color-accent)]"></i>
                                Recent Submissions
                            </h3>
                            <a href="{{ route('admin.contact-submissions.index', ['contact' => $contact->id]) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                View All
                            </a>
                        </div>
                        
                        @if($contact->submissions->count() > 0)
                            <div class="space-y-3">
                                @foreach($contact->submissions as $submission)
                                    <div class="p-4 bg-zinc-50 dark:bg-zinc-900/50 rounded-lg border border-zinc-200 dark:border-zinc-700">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $submission->subject_label }}</span>
                                                    @if(!$submission->is_read)
                                                        <x-ui.badge color="blue" size="sm">Unread</x-ui.badge>
                                                    @endif
                                                    @if($submission->is_archived)
                                                        <x-ui.badge color="gray" size="sm">Archived</x-ui.badge>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2">{{ Str::limit($submission->message, 150) }}</p>
                                                <p class="text-xs text-zinc-500 dark:text-zinc-500 mt-2">{{ $submission->created_at->format('M d, Y \a\t h:i A') }}</p>
                                            </div>
                                            <a href="{{ route('admin.contact-submissions.show', $submission) }}" class="ml-4 text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300">
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-zinc-600 dark:text-zinc-400 text-sm">No submissions yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Statistics & Actions (1/3) -->
            <div class="space-y-6">
                <!-- Statistics Card -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-bar text-[var(--color-accent)]"></i>
                            Statistics
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Submissions</label>
                                <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ $contact->total_submissions_count }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Unread Submissions</label>
                                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">{{ $contact->unread_submissions_count }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Active Submissions</label>
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $contact->active_submissions_count }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dates Card -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-clock text-[var(--color-accent)]"></i>
                            Dates
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Created At</label>
                                <p class="text-zinc-900 dark:text-white mt-1">{{ $contact->created_at->format('F d, Y \a\t h:i A') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Last Updated</label>
                                <p class="text-zinc-900 dark:text-white mt-1">{{ $contact->updated_at->format('F d, Y \a\t h:i A') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Last Contacted</label>
                                <p class="text-zinc-900 dark:text-white mt-1">
                                    @if($contact->last_contacted_at)
                                        {{ $contact->last_contacted_at->format('F d, Y \a\t h:i A') }}
                                        <span class="text-xs text-zinc-500 dark:text-zinc-400 block mt-1">({{ $contact->last_contacted_at->diffForHumans() }})</span>
                                    @else
                                        Never
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
