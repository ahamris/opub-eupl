<x-layouts.admin title="Data Management - Typesense Sync Status">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Data Management</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Monitor Typesense sync status and operations</p>
            </div>
        </div>

        <!-- Livewire Component for Real-time Status -->
        <livewire:admin.typesense-sync-status />
    </div>
</x-layouts.admin>
