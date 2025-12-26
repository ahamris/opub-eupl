<x-layouts.admin title="Subscription Details">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Subscription Details</h1>
                <p class="text-zinc-600 dark:text-zinc-400">View and manage subscription information</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.search-subscriptions.index') }}">Back to Subscriptions</x-button>
            </div>
        </div>

        <!-- Subscription Information Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="p-6">
                <div class="flex items-start gap-6">
                    <!-- Icon -->
                    <div class="w-20 h-20 rounded-full bg-[var(--color-accent)] flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-bell text-white text-2xl"></i>
                    </div>

                    <!-- Subscription Info -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">
                            {{ $searchSubscription->email }}
                        </h2>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
                                <i class="fas fa-clock text-sm"></i>
                                <span>{{ $searchSubscription->frequency_label }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
                                <i class="fas fa-calendar text-sm"></i>
                                <span>Created {{ $searchSubscription->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($searchSubscription->is_active)
                                    <x-badge variant="success" icon="check">Active</x-badge>
                                @else
                                    <x-badge variant="warning" icon="times">Inactive</x-badge>
                                @endif
                                @if($searchSubscription->isVerified())
                                    <x-badge variant="success" icon="check-circle">Verified</x-badge>
                                @else
                                    <x-badge variant="warning" icon="exclamation-triangle">Not Verified</x-badge>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Subscription Information Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[var(--color-accent)]"></i>
                        Subscription Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Email</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $searchSubscription->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Frequency</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $searchSubscription->frequency_label }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Search Query</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $searchSubscription->search_query ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Subscription ID</label>
                            <p class="text-zinc-900 dark:text-white mt-1">#{{ $searchSubscription->id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters & Status Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-filter text-[var(--color-accent)]"></i>
                        Filters & Status
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Filters</label>
                            @if($searchSubscription->filters && count($searchSubscription->filters) > 0)
                                <div class="mt-2 space-y-1">
                                    @foreach($searchSubscription->filters as $key => $value)
                                        <div class="text-sm">
                                            <span class="text-zinc-600 dark:text-zinc-400">{{ $key }}:</span>
                                            <span class="text-zinc-900 dark:text-white ml-1">
                                                @if(is_array($value))
                                                    {{ implode(', ', $value) }}
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-zinc-900 dark:text-white mt-1">No filters applied</p>
                            @endif
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Status</label>
                            <p class="mt-1">
                                @if($searchSubscription->is_active)
                                    <x-badge variant="success" icon="check">Active</x-badge>
                                @else
                                    <x-badge variant="warning" icon="times">Inactive</x-badge>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Verified</label>
                            <p class="mt-1">
                                @if($searchSubscription->isVerified())
                                    <x-badge variant="success" icon="check-circle">Verified</x-badge>
                                @else
                                    <x-badge variant="warning" icon="exclamation-triangle">Not Verified</x-badge>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Information Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-clock text-[var(--color-accent)]"></i>
                    Account Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Created At</label>
                        <p class="text-zinc-900 dark:text-white mt-1">{{ $searchSubscription->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Last Updated</label>
                        <p class="text-zinc-900 dark:text-white mt-1">{{ $searchSubscription->updated_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    @if($searchSubscription->verified_at)
                    <div>
                        <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Verified At</label>
                        <p class="text-zinc-900 dark:text-white mt-1">{{ $searchSubscription->verified_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    @endif
                    @if($searchSubscription->last_sent_at)
                    <div>
                        <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Last Sent At</label>
                        <p class="text-zinc-900 dark:text-white mt-1">{{ $searchSubscription->last_sent_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    @endif
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
                    <form method="POST" action="{{ route('admin.search-subscriptions.toggle-active', $searchSubscription) }}" class="inline">
                        @csrf
                        <x-button 
                            variant="{{ $searchSubscription->is_active ? 'warning' : 'success' }}" 
                            icon="{{ $searchSubscription->is_active ? 'pause' : 'play' }}" 
                            icon-position="left" 
                            type="submit"
                        >
                            {{ $searchSubscription->is_active ? 'Deactivate' : 'Activate' }}
                        </x-button>
                    </form>
                    <x-button 
                        variant="error" 
                        icon="trash" 
                        icon-position="left" 
                        type="button"
                        x-on:click="showDeleteModal = true"
                    >Delete Subscription</x-button>
                    
                    <x-ui.modal alpine-show="showDeleteModal" size="sm">
                        <x-slot:title>Delete Subscription</x-slot:title>
                        <p class="text-zinc-600 dark:text-zinc-400">Are you sure you want to delete this subscription for <strong>{{ $searchSubscription->email }}</strong>? This action cannot be undone.</p>
                        <x-slot:footer>
                            <x-button variant="secondary" x-on:click="showDeleteModal = false">Cancel</x-button>
                            <form method="POST" action="{{ route('admin.search-subscriptions.destroy', $searchSubscription) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-button variant="primary" color="red" type="submit">Delete</x-button>
                            </form>
                        </x-slot:footer>
                    </x-ui.modal>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
