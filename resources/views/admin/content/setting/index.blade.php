<x-layouts.admin title="Settings">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Settings</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage all application settings</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.setting.create') }}">Add New Setting</x-button>
            </div>
        </div>

        <!-- Group Filter -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-4">
            <form method="GET" action="{{ route('admin.content.setting.index') }}" class="flex items-center gap-3">
                <div class="flex-1">
                    <x-ui.select 
                        label="Filter by Group" 
                        name="group"
                        :options="[
                            '' => 'All Groups',
                            ...$groups->mapWithKeys(fn($group) => [$group => ucfirst($group)])->toArray()
                        ]"
                        value="{{ $selectedGroup }}"
                    />
                </div>
                <div class="flex items-end gap-2">
                    <x-button variant="secondary" type="submit" icon="filter" icon-position="left">Filter</x-button>
                    @if($selectedGroup)
                        <x-button variant="secondary" type="button" href="{{ route('admin.content.setting.index') }}" icon="times">Clear</x-button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Settings Table -->
        @if($settings->count() > 0)
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Key</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Value</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Group</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Updated</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($settings as $setting)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-900/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-white">
                                        #{{ $setting->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-zinc-900 dark:text-white">
                                            {{ $setting->_key }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-zinc-600 dark:text-zinc-400 max-w-md truncate" title="{{ $setting->_value }}">
                                            {{ Str::limit($setting->_value ?? '(empty)', 50) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-ui.badge :color="$setting->group === 'general' ? 'blue' : ($setting->group === 'smtp' ? 'green' : 'gray')">
                                            {{ ucfirst($setting->group) }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ $setting->updated_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <x-button 
                                                variant="secondary" 
                                                size="sm" 
                                                icon="eye" 
                                                href="{{ route('admin.content.setting.show', $setting) }}"
                                                title="View"
                                            />
                                            <x-button 
                                                variant="secondary" 
                                                size="sm" 
                                                icon="edit" 
                                                href="{{ route('admin.content.setting.edit', $setting) }}"
                                                title="Edit"
                                            />
                                            <x-button 
                                                variant="error" 
                                                size="sm" 
                                                icon="trash" 
                                                type="button"
                                                x-data="{ showDeleteModal: false }"
                                                x-on:click="showDeleteModal = true"
                                                title="Delete"
                                            />
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
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-12 text-center">
                <i class="fas fa-cog text-4xl text-zinc-400 dark:text-zinc-600 mb-4"></i>
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">No Settings Found</h3>
                <p class="text-zinc-600 dark:text-zinc-400 mb-6">
                    @if($selectedGroup)
                        No settings found in the "{{ ucfirst($selectedGroup) }}" group.
                    @else
                        Get started by creating your first setting.
                    @endif
                </p>
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.setting.create') }}">Create Setting</x-button>
            </div>
        @endif
    </div>
</x-layouts.admin>

