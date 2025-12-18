<x-layouts.admin title="Blogs">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Blogs</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage all blog posts in your system</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.blog.create') }}">Add New Blog</x-button>
            </div>
        </div>

        <!-- Blogs Table -->
        <livewire:admin.table
            resource="blogs"
            :columns="[
                'id',
                'title',
                ['key' => 'blog_category.name', 'label' => 'Category'],
                ['key' => 'author.name', 'label' => 'Author'],
                ['key' => 'is_active', 'type' => 'toggle'],
                ['key' => 'is_featured', 'type' => 'toggle'],
                ['key' => 'created_at', 'format' => 'date'],
            ]"
            route-prefix="admin.content.blog"
            search-placeholder="Search blogs..."
            :paginate="15"
        />
    </div>
</x-layouts.admin>
