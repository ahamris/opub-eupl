<x-layouts.admin title="Search Subscriptions">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Search Subscriptions</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage all search subscriptions and email notifications</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="outline-primary" icon="download" icon-position="left" x-data x-on:click="toastManager.show('info', 'Exporting subscriptions...')">Export</x-button>
            </div>
        </div>

        <!-- Subscriptions Table -->
        <livewire:admin.table
            :resource="\App\Models\SearchSubscription::class"
            :columns="[
                'id',
                'email',
                ['key' => 'frequency', 'label' => 'Frequency'],
                ['key' => 'search_query', 'label' => 'Search Query'],
                ['key' => 'formatted_filters', 'label' => 'Filters', 'sortable' => false],
                ['key' => 'is_active', 'type' => 'toggle', 'label' => 'Active'],
                ['key' => 'verified_at', 'format' => 'date', 'label' => 'Verified'],
                ['key' => 'created_at', 'format' => 'date', 'label' => 'Created'],
            ]"
            route-prefix="admin.search-subscriptions"
            search-placeholder="Search subscriptions by email..."
            :paginate="15"
        />
    </div>
</x-layouts.admin>
