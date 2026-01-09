<x-layouts.admin title="Dashboard">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Dashboard</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Welcome! You can track your system's overall status here.</p>
            </div>
        </div>

        <!-- Statistics Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Users Card -->
            <x-ui.card 
                icon="users" 
                icon-color="primary"
                title="Total Users"
                :value="number_format($totalUsers)"
                action-text="Manage users"
                :action-url="route('admin.users.index')"
            />

            <!-- Active Blogs Card -->
            <x-ui.card 
                icon="newspaper" 
                icon-color="success"
                title="Active Blog Posts"
                :value="number_format($activeBlogs)"
                action-text="Manage blogs"
                :action-url="route('admin.content.blog.index')"
            />

            <!-- Unread Messages Card -->
            <x-ui.card 
                icon="envelope" 
                icon-color="{{ $unreadMessages > 0 ? 'warning' : 'secondary' }}"
                title="Unread Messages"
                :value="number_format($unreadMessages)"
                action-text="View messages"
                :action-url="route('admin.contact-submissions.index')"
            />

            <!-- Active Subscriptions Card -->
            <x-ui.card 
                icon="bell" 
                icon-color="sky"
                title="Active Subscriptions"
                :value="number_format($activeSubscriptions)"
                action-text="Manage subscriptions"
                :action-url="route('admin.search-subscriptions.index')"
            />
        </div>

        <!-- Secondary Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <!-- Total Documents -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-950/30 flex items-center justify-center">
                        <i class="fas fa-file-alt text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Total Documents</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ number_format($totalDocuments, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-50 dark:bg-green-950/30 flex items-center justify-center">
                        <i class="fas fa-user-check text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Active Users</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $activeUsers }}</p>
                    </div>
                </div>
            </div>

            <!-- Users This Month -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-sky-50 dark:bg-sky-950/30 flex items-center justify-center">
                        <i class="fas fa-user-plus text-sky-600 dark:text-sky-400"></i>
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Registered This Month</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $usersThisMonth }}</p>
                    </div>
                </div>
            </div>

            <!-- Featured Blogs -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-yellow-50 dark:bg-yellow-950/30 flex items-center justify-center">
                        <i class="fas fa-star text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Featured Blogs</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $featuredBlogs }}</p>
                    </div>
                </div>
            </div>

            <!-- Verified Subscriptions -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 flex items-center justify-center">
                        <i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400"></i>
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Verified Subscriptions</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $verifiedSubscriptions }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Two Column Layout: Recent Messages + Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Unread Messages -->
            <div class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-envelope text-zinc-500 dark:text-zinc-400"></i>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Recent Unread Messages</h2>
                        @if($unreadMessages > 0)
                            <span class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 text-xs font-medium px-2 py-0.5 rounded-full">
                                {{ $unreadMessages }} new
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('admin.contact-submissions.index') }}">
                        <x-button variant="outline-primary" size="sm" icon="arrow-right">View All</x-button>
                    </a>
                </div>
                <div class="p-4">
                    @if($recentUnreadMessages->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentUnreadMessages as $message)
                                <a href="{{ route('admin.contact-submissions.show', $message) }}" 
                                   class="block p-3 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-medium text-zinc-900 dark:text-white truncate">{{ $message->full_name }}</span>
                                                <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $message->organisation ?? '' }}</span>
                                            </div>
                                            <p class="text-sm text-zinc-600 dark:text-zinc-400 truncate">{{ $message->subject_label }}</p>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-500 mt-1">{{ $message->email }}</p>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $message->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-4xl text-zinc-300 dark:text-zinc-600 mb-3"></i>
                            <p class="text-zinc-500 dark:text-zinc-400">No unread messages</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-bolt text-zinc-500 dark:text-zinc-400"></i>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Quick Actions</h2>
                    </div>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('admin.users.create') }}" 
                       class="flex items-center gap-3 p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/30 flex items-center justify-center">
                            <i class="fas fa-user-plus text-indigo-600 dark:text-indigo-400 text-sm"></i>
                        </div>
                        <span class="text-sm text-zinc-700 dark:text-zinc-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Add New User</span>
                    </a>
                    <a href="{{ route('admin.content.blog.create') }}" 
                       class="flex items-center gap-3 p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-green-50 dark:bg-green-950/30 flex items-center justify-center">
                            <i class="fas fa-pen text-green-600 dark:text-green-400 text-sm"></i>
                        </div>
                        <span class="text-sm text-zinc-700 dark:text-zinc-300 group-hover:text-green-600 dark:group-hover:text-green-400">New Blog Post</span>
                    </a>
                    <a href="{{ route('admin.content.static-page.index') }}" 
                       class="flex items-center gap-3 p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-sky-50 dark:bg-sky-950/30 flex items-center justify-center">
                            <i class="fas fa-file text-sky-600 dark:text-sky-400 text-sm"></i>
                        </div>
                        <span class="text-sm text-zinc-700 dark:text-zinc-300 group-hover:text-sky-600 dark:group-hover:text-sky-400">Static Pages</span>
                    </a>
                    <a href="{{ route('admin.content.general-settings.index') }}" 
                       class="flex items-center gap-3 p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center">
                            <i class="fas fa-cog text-zinc-600 dark:text-zinc-400 text-sm"></i>
                        </div>
                        <span class="text-sm text-zinc-700 dark:text-zinc-300 group-hover:text-zinc-900 dark:group-hover:text-white">General Settings</span>
                    </a>
                    <a href="{{ route('admin.settings.theme') }}" 
                       class="flex items-center gap-3 p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-950/30 flex items-center justify-center">
                            <i class="fas fa-palette text-purple-600 dark:text-purple-400 text-sm"></i>
                        </div>
                        <span class="text-sm text-zinc-700 dark:text-zinc-300 group-hover:text-purple-600 dark:group-hover:text-purple-400">Theme Settings</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-users text-zinc-500 dark:text-zinc-400"></i>
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Users</h2>
                </div>
                <a href="{{ route('admin.users.create') }}">
                    <x-button variant="primary" size="sm" icon="plus">Add</x-button>
                </a>
            </div>
            <div class="p-4">
                <livewire:admin.table 
                    resource="users"
                    :columns="[
                        ['key' => 'id', 'label' => 'ID', 'sortable' => true],
                        ['key' => 'name', 'label' => 'Name', 'sortable' => true],
                        ['key' => 'email', 'label' => 'Email', 'sortable' => true],
                        ['key' => 'is_active', 'label' => 'Active', 'sortable' => true, 'type' => 'toggle'],
                        ['key' => 'created_at', 'label' => 'Created At', 'sortable' => true, 'format' => 'date'],
                    ]"
                    :search-fields="['name', 'email']"
                    :sortable-fields="['id', 'name', 'email', 'is_active', 'created_at']"
                    :show-actions="true"
                    :actions="['view', 'edit', 'delete']"
                    route-prefix="admin.users"
                    search-placeholder="Search users..."
                    :paginate="5"
                    :show-checkbox="false"
                    :show-bulk-delete="false"
                />
            </div>
        </div>
    </div>
</x-layouts.admin>
