<x-layouts.admin title="Data Entry Details">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Data Entry Details</h1>
                <p class="text-zinc-600 dark:text-zinc-400">View and manage data entry information</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.data-management.index') }}">Back to Data Management</x-button>
                <x-button variant="primary" icon="edit" icon-position="left" href="{{ route('admin.content.data-management.edit', $dataManagement) }}">Edit Entry</x-button>
            </div>
        </div>

        <!-- Entry Information Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="p-6">
                <div class="flex items-start gap-6">
                    <!-- Icon Preview -->
                    <div class="w-20 h-20 rounded-lg bg-[var(--color-accent)]/10 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-database text-[var(--color-accent)] text-2xl"></i>
                    </div>

                    <!-- Entry Info -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">
                            {{ $dataManagement->title }}
                        </h2>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <x-ui.badge color="blue">
                                    {{ ucfirst($dataManagement->type) }}
                                </x-ui.badge>
                                <x-ui.badge :color="$dataManagement->status === 'active' ? 'green' : ($dataManagement->status === 'archived' ? 'gray' : 'red')">
                                    {{ ucfirst($dataManagement->status) }}
                                </x-ui.badge>
                            </div>
                            <div class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
                                <i class="fas fa-calendar text-sm"></i>
                                <span>Updated {{ $dataManagement->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Entry Information Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[var(--color-accent)]"></i>
                        Entry Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Title</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $dataManagement->title }}</p>
                        </div>
                        @if($dataManagement->description)
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Description</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $dataManagement->description }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Type</label>
                            <div class="mt-1">
                                <x-ui.badge color="blue">
                                    {{ ucfirst($dataManagement->type) }}
                                </x-ui.badge>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Status</label>
                            <div class="mt-1">
                                <x-ui.badge :color="$dataManagement->status === 'active' ? 'green' : ($dataManagement->status === 'archived' ? 'gray' : 'red')">
                                    {{ ucfirst($dataManagement->status) }}
                                </x-ui.badge>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Priority</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $dataManagement->priority }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Entry ID</label>
                            <p class="text-zinc-900 dark:text-white mt-1">#{{ $dataManagement->id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-clock text-[var(--color-accent)]"></i>
                        Timestamps
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Created At</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $dataManagement->created_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Last Updated</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $dataManagement->updated_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                        @if($dataManagement->data)
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Additional Data</label>
                            <pre class="mt-1 text-xs bg-zinc-100 dark:bg-zinc-900 p-3 rounded overflow-auto max-h-40">{{ json_encode($dataManagement->data, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-cog text-[var(--color-accent)]"></i>
                    Actions
                </h3>
                <div class="flex items-center gap-3" x-data="{ showDeleteModal: false }">
                    <x-button variant="primary" icon="edit" icon-position="left" href="{{ route('admin.content.data-management.edit', $dataManagement) }}">Edit Entry</x-button>
                    <x-button 
                        variant="error" 
                        icon="trash" 
                        icon-position="left" 
                        type="button"
                        x-on:click="showDeleteModal = true"
                    >Delete Entry</x-button>
                    
                    <x-ui.modal alpine-show="showDeleteModal" size="sm">
                        <x-slot:title>Delete Data Entry</x-slot:title>
                        <p class="text-zinc-600 dark:text-zinc-400">Are you sure you want to delete <strong>{{ $dataManagement->title }}</strong>? This action cannot be undone.</p>
                        <x-slot:footer>
                            <x-button variant="secondary" x-on:click="showDeleteModal = false">Cancel</x-button>
                            <form action="{{ route('admin.content.data-management.destroy', $dataManagement) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-button 
                                    variant="primary"
                                    color="red"
                                    type="submit"
                                    x-on:click="showDeleteModal = false"
                                >Delete</x-button>
                            </form>
                        </x-slot:footer>
                    </x-ui.modal>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

