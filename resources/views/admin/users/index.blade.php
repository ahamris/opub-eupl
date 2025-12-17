<x-layouts.admin title="Users">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Users</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage all users in your system</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="outline-primary" icon="download" icon-position="left" x-data x-on:click="toastManager.show('info', 'Exporting users...')">Export</x-button>
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.users.create') }}">Add New User</x-button>
            </div>
        </div>

        <!-- Users Table -->
        <livewire:admin.table
            resource="users"
            :columns="[
                'id',
                'name',
                'last_name',
                'email',
                ['key' => 'is_active', 'type' => 'toggle'],
                ['key' => 'created_at', 'format' => 'date'],
            ]"
            route-prefix="admin.users"
            search-placeholder="Search users..."
            :paginate="15"
        />
    </div>
</x-layouts.admin>

