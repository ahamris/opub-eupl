<x-layouts.admin title="Setting Details">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Setting Details</h1>
                <p class="text-zinc-600 dark:text-zinc-400">View and manage setting information</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.setting.index', ['group' => $setting->group]) }}">Back to Settings</x-button>
                <x-button variant="primary" icon="edit" icon-position="left" href="{{ route('admin.content.setting.edit', $setting) }}">Edit Setting</x-button>
            </div>
        </div>

        <!-- Setting Information Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="p-6">
                <div class="flex items-start gap-6">
                    <!-- Icon Preview -->
                    <div class="w-20 h-20 rounded-lg bg-[var(--color-accent)]/10 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-cog text-[var(--color-accent)] text-2xl"></i>
                    </div>

                    <!-- Setting Info -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">
                            {{ $setting->_key }}
                        </h2>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
                                <x-ui.badge :color="$setting->group === 'general' ? 'blue' : ($setting->group === 'smtp' ? 'green' : 'gray')">
                                    {{ ucfirst($setting->group) }}
                                </x-ui.badge>
                            </div>
                            <div class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
                                <i class="fas fa-calendar text-sm"></i>
                                <span>Updated {{ $setting->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Setting Information Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[var(--color-accent)]"></i>
                        Setting Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Key</label>
                            <p class="text-zinc-900 dark:text-white mt-1 font-mono text-sm">{{ $setting->_key }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Value</label>
                            <p class="text-zinc-900 dark:text-white mt-1 break-words">{{ $setting->_value ?? '(empty)' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Group</label>
                            <div class="mt-1">
                                <x-ui.badge :color="$setting->group === 'general' ? 'blue' : ($setting->group === 'smtp' ? 'green' : 'gray')">
                                    {{ ucfirst($setting->group) }}
                                </x-ui.badge>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Setting ID</label>
                            <p class="text-zinc-900 dark:text-white mt-1">#{{ $setting->id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-clock text-[var(--color-accent)]"></i>
                        Timestamps
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Created At</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $setting->created_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Last Updated</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $setting->updated_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Value Length</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ strlen($setting->_value ?? '') }} characters</p>
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
                    <x-button variant="primary" icon="edit" icon-position="left" href="{{ route('admin.content.setting.edit', $setting) }}">Edit Setting</x-button>
                    <x-button 
                        variant="error" 
                        icon="trash" 
                        icon-position="left" 
                        type="button"
                        x-on:click="showDeleteModal = true"
                    >Delete Setting</x-button>
                    
                    <x-ui.modal alpine-show="showDeleteModal" size="sm">
                        <x-slot:title>Delete Setting</x-slot:title>
                        <p class="text-zinc-600 dark:text-zinc-400">Are you sure you want to delete <strong>{{ $setting->_key }}</strong>? This action cannot be undone.</p>
                        <x-slot:footer>
                            <x-button variant="secondary" x-on:click="showDeleteModal = false">Cancel</x-button>
                            <form action="{{ route('admin.content.setting.destroy', $setting) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-button 
                                    variant="primary"
                                    color="red"
                                    type="submit"
                                    x-on:click="showDeleteModal = false"
                                >Delete</x-button>
                            </form>
                        </x-slot:footer>
                    </x-ui.modal>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

