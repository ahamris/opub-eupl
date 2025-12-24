<x-layouts.admin title="Static Pages">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Static Pages</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage all static pages in your system</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.static-page.create') }}">Add New Page</x-button>
            </div>
        </div>

        <!-- Static Pages Table -->
        <livewire:admin.table
            resource="static_pages"
            :columns="[
                'id',
                'title',
                'slug',
                ['key' => 'is_active', 'type' => 'toggle'],
                ['key' => 'sort_order', 'label' => 'Order'],
                ['key' => 'created_at', 'format' => 'date'],
            ]"
            route-prefix="admin.content.static-page"
            search-placeholder="Search pages..."
            :paginate="15"
        />
    </div>
</x-layouts.admin>
