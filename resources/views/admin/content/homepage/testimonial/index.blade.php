<x-layouts.admin title="Testimonials">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Testimonials</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage customer testimonials on the homepage</p>
            </div>
            <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.homepage.testimonial.create') }}">Add Testimonial</x-button>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <!-- Testimonials Table -->
        <livewire:admin.table
            resource="testimonials"
            :columns="[
                ['key' => 'author', 'label' => 'Author', 'type' => 'custom', 'view' => 'admin.content.homepage.testimonial.columns.author', 'sortable' => true],
                ['key' => 'quote', 'label' => 'Quote', 'type' => 'custom', 'view' => 'admin.content.homepage.testimonial.columns.quote', 'sortable' => false],
                ['key' => 'rating', 'label' => 'Rating', 'type' => 'custom', 'view' => 'admin.content.homepage.testimonial.columns.rating', 'sortable' => true],
                ['key' => 'sort_order', 'label' => 'Order', 'sortable' => true],
                ['key' => 'is_active', 'label' => 'Active', 'type' => 'toggle'],
            ]"
            route-prefix="admin.content.homepage.testimonial"
            search-placeholder="Search testimonials..."
            :paginate="15"
            :actions="['edit', 'delete']"
        />
    </div>
</x-layouts.admin>
