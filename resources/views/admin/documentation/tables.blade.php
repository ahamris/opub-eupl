<x-layouts.admin title="Tables - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Interactive Livewire Table -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Interactive Table</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Full-featured Livewire table component with search, pagination, sorting, bulk selection, and actions.
                </p>
                <livewire:admin.table
                    resource="users"
                    :columns="['name' => 'Name', 'email' => 'Email', 'created_at' => 'Created At']"
                    :search-fields="['name', 'email']"
                    :sortable-fields="['name', 'email', 'created_at']"
                    route-prefix="admin.users"
                    search-placeholder="Search users..."
                    :paginate="10"
                />
            </div>

            <!-- Empty State Example -->
            <div>
                <h3 class="mb-4 text-xl font-semibold">Empty State</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Table displays an empty state when no data is available.
                </p>
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-300 dark:divide-zinc-700">
                            <thead class="bg-zinc-100 dark:bg-zinc-800">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider w-12">
                                        <input
                                            type="checkbox"
                                            class="rounded border-zinc-300 dark:border-zinc-700 text-[var(--color-accent)] focus:ring-[var(--color-accent)]"
                                            disabled
                                        />
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">Created At</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider w-24">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-300 dark:divide-zinc-700">
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-zinc-600 dark:text-zinc-400">
                                            <i class="fa-solid fa-inbox text-4xl mb-3 opacity-50"></i>
                                            <p class="text-sm">No users found</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div class="space-y-8">
            <div>
                <h3 class="text-lg font-semibold mb-3">Overview</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    The Livewire Table component is a fully interactive table with built-in search, pagination, sorting, bulk selection, and row actions.
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    It automatically handles all user interactions and data management on the server side, providing a seamless user experience.
                </p>
            </div>

            <!-- Features -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Features</h3>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                    <li>Real-time search with debouncing</li>
                    <li>Server-side pagination</li>
                    <li>Sortable columns (click headers to sort)</li>
                    <li>Bulk selection with checkboxes</li>
                    <li>Bulk actions (delete selected)</li>
                    <li>Individual row actions (view, edit, delete)</li>
                    <li>Empty state with icon</li>
                    <li>Success messages via session flash</li>
                    <li>URL state persistence (search, sort, page)</li>
                    <li>Responsive design</li>
                    <li>Dark mode support</li>
                </ul>
                </div>
            </div>

            <!-- Component Structure -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Component Structure</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">app/Livewire/Admin/Table.php</code>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Livewire component class</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">resources/views/livewire/admin/table.blade.php</code>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Component view template</p>
                    </div>
                </div>
            </div>

            <!-- Props -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Component Props</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">model</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string (required)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Model class name (e.g., 'App\Models\User')</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">columns</code>
                        <span class="text-gray-600 dark:text-gray-400"> - array (required)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Column definitions: ['field' => 'Label'] or ['field' => ['label' => '...', 'sortable' => true]]</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">searchFields</code>
                        <span class="text-gray-600 dark:text-gray-400"> - array (optional)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Fields to search in (defaults to all column fields)</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">sortableFields</code>
                        <span class="text-gray-600 dark:text-gray-400"> - array (optional)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Fields that can be sorted (defaults to all column fields)</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">showActions</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool (default: true)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Show actions column</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">actions</code>
                        <span class="text-gray-600 dark:text-gray-400"> - array (default: ['view', 'edit', 'delete'])</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Available actions: 'view', 'edit', 'delete'</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">routePrefix</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string|null (optional)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Route prefix for actions (e.g., 'admin.users' for admin.users.show, admin.users.edit)</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">searchPlaceholder</code>
                        <span class="text-gray-600 dark:text-gray-400"> - string (default: 'Search...')</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Search input placeholder text</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">perPage</code>
                        <span class="text-gray-600 dark:text-gray-400"> - int (default: 10)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Items per page</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">showCheckbox</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool (default: true)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Show checkbox column for selection</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">showBulkDelete</code>
                        <span class="text-gray-600 dark:text-gray-400"> - bool (default: true)</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Show bulk delete button</p>
                    </div>
                </div>
            </div>

            <!-- Methods -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Component Methods</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">items()</code>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Computed property that returns paginated, filtered, and sorted items</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">sortBy($field)</code>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Toggle sort for a column</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">deleteSelected()</code>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Delete all selected items</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">delete($id)</code>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Delete a single item by ID</p>
                    </div>
                    <div>
                        <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">getRoute($action, $id)</code>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Generate route URL for an action (e.g., 'show', 'edit')</p>
                    </div>
                </div>
            </div>

            <!-- Customization -->
            <div>
                <h3 class="text-lg font-semibold mb-3">How to Use</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    The component is now fully configurable via props. Simply pass the model class, columns, and other options to use it with any model.
                </p>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-700 dark:text-gray-300 font-medium mb-1">1. Define your columns:</p>
                        <p class="text-gray-600 dark:text-gray-400 text-xs">Pass an array of field => label pairs for the columns you want to display.</p>
                    </div>
                    <div>
                        <p class="text-gray-700 dark:text-gray-300 font-medium mb-1">2. Configure search and sort:</p>
                        <p class="text-gray-600 dark:text-gray-400 text-xs">Specify which fields should be searchable and sortable.</p>
                    </div>
                    <div>
                        <p class="text-gray-700 dark:text-gray-300 font-medium mb-1">3. Set up routes:</p>
                        <p class="text-gray-600 dark:text-gray-400 text-xs">If you want view/edit buttons, provide a route prefix (e.g., 'admin.users' for admin.users.show/edit).</p>
                    </div>
                    <div>
                        <p class="text-gray-700 dark:text-gray-300 font-medium mb-1">4. Customize actions:</p>
                        <p class="text-gray-600 dark:text-gray-400 text-xs">Choose which actions to show: 'view', 'edit', 'delete'. Or disable actions entirely with <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">:show-actions="false"</code>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>

@push('scripts')

@endpush
