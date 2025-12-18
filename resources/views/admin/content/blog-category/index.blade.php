<x-layouts.admin title="Blog Categories">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Blog Categories</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage all blog categories in your system</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.blog-category.create') }}">Add New Category</x-button>
            </div>
        </div>

        <!-- Blog Categories Table -->
        <livewire:admin.table
            resource="blog_categories"
            :columns="[
                'id',
                'name',
                'slug',
                ['key' => 'color', 'type' => 'color'],
                ['key' => 'created_at', 'format' => 'date'],
            ]"
            route-prefix="admin.content.blog-category"
            search-placeholder="Search blog categories..."
            :paginate="15"
        />
    </div>
</x-layouts.admin>
