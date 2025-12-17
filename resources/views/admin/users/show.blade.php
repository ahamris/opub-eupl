<x-layouts.admin title="User Details">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">User Details</h1>
                <p class="text-zinc-600 dark:text-zinc-400">View and manage user information</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.users.index') }}">Back to Users</x-button>
                <x-button variant="primary" icon="edit" icon-position="left" href="{{ route('admin.users.edit', $user) }}">Edit User</x-button>
            </div>
        </div>

        <!-- User Information Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="p-6">
                <div class="flex items-start gap-6">
                    <!-- Avatar -->
                    <div class="w-20 h-20 rounded-full bg-[var(--color-accent)] flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-white text-2xl"></i>
                    </div>

                    <!-- User Info -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">{{ $user->name }}</h2>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
                                <i class="fas fa-envelope text-sm"></i>
                                <span>{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
                                <i class="fas fa-calendar text-sm"></i>
                                <span>Joined {{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                            @if($user->email_verified_at)
                                <div class="flex items-center gap-2">
                                    <x-badge variant="success" icon="check">Email Verified</x-badge>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <x-badge variant="warning" icon="exclamation-triangle">Email Not Verified</x-badge>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- User Information Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[var(--color-accent)]"></i>
                        User Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Name</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Email</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">User ID</label>
                            <p class="text-zinc-900 dark:text-white mt-1">#{{ $user->id }}</p>
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
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Created At</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $user->created_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Last Updated</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $user->updated_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Email Verified</label>
                            <p class="mt-1">
                                @if($user->email_verified_at)
                                    <x-badge variant="success" icon="check">Verified</x-badge>
                                @else
                                    <x-badge variant="warning" icon="times">Not Verified</x-badge>
                                @endif
                            </p>
                        </div>
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
                    <x-button variant="primary" icon="edit" icon-position="left" href="{{ route('admin.users.edit', $user) }}">Edit User</x-button>
                    <x-button 
                        variant="error" 
                        icon="trash" 
                        icon-position="left" 
                        type="button"
                        x-on:click="showDeleteModal = true"
                    >Delete User</x-button>
                    
                    <x-ui.modal alpine-show="showDeleteModal" size="sm">
                        <x-slot:title>Delete User</x-slot:title>
                        <p class="text-zinc-600 dark:text-zinc-400">Are you sure you want to delete <strong>{{ $user->name }}</strong>? This action cannot be undone.</p>
                        <x-slot:footer>
                            <x-button variant="secondary" x-on:click="showDeleteModal = false">Cancel</x-button>
                            <x-button 
                                variant="primary"
                                color="red"
                                x-on:click="axios.post('{{ route('admin.users.destroy', $user) }}', { _method: 'DELETE' }).then(() => { toastManager.show('success', 'User deleted successfully.'); showDeleteModal = false; setTimeout(() => window.location.href = '{{ route('admin.users.index') }}', 500); }).catch(() => { toastManager.show('error', 'An error occurred while deleting the user.'); showDeleteModal = false; });"
                            >Delete</x-button>
                        </x-slot:footer>
                    </x-ui.modal>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

