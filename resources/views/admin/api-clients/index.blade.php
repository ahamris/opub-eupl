<x-layouts.admin title="API Clients">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">API Clients</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage OPub API keys and allowed domains</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.api-clients.create') }}">New API Client</x-button>
            </div>
        </div>

        @if($clients->count() > 0)
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Key Prefix</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Allowed Domains</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Last Used</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($clients as $client)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-900/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-zinc-900 dark:text-white">
                                            <a class="hover:underline" href="{{ route('admin.api-clients.show', $client) }}">
                                                {{ $client->name }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">#{{ $client->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-ui.badge :color="$client->is_active ? 'green' : 'gray'">
                                            {{ $client->is_active ? 'Active' : 'Disabled' }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-mono text-xs text-zinc-700 dark:text-zinc-300">{{ $client->key_prefix ?? '—' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-400">
                                        @php
                                            $domains = $client->allowed_domains ? $client->allowed_domains->toArray() : [];
                                        @endphp
                                        @if(empty($domains))
                                            <span class="text-zinc-400">None (server-to-server only)</span>
                                        @elseif(in_array('*', $domains, true))
                                            <span class="text-zinc-600 dark:text-zinc-300">Any (*)</span>
                                        @else
                                            {{ count($domains) }} domain{{ count($domains) === 1 ? '' : 's' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                        @if($client->last_used_at)
                                            {{ $client->last_used_at->diffForHumans() }}
                                        @else
                                            Never
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <x-button
                                                variant="secondary"
                                                size="sm"
                                                icon="eye"
                                                href="{{ route('admin.api-clients.show', $client) }}"
                                                title="View"
                                            />
                                            <x-button
                                                variant="secondary"
                                                size="sm"
                                                icon="edit"
                                                href="{{ route('admin.api-clients.edit', $client) }}"
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
                                                <x-slot:title>Delete API Client</x-slot:title>
                                                <p class="text-zinc-600 dark:text-zinc-400">Delete <strong>{{ $client->name }}</strong>? This will revoke access immediately.</p>
                                                <x-slot:footer>
                                                    <x-button variant="secondary" x-on:click="showDeleteModal = false">Cancel</x-button>
                                                    <form action="{{ route('admin.api-clients.destroy', $client) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <x-button variant="primary" color="red" type="submit" x-on:click="showDeleteModal = false">Delete</x-button>
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
                <i class="fas fa-key text-4xl text-zinc-400 dark:text-zinc-600 mb-4"></i>
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">No API Clients</h3>
                <p class="text-zinc-600 dark:text-zinc-400 mb-6">Create an API client to allow external apps to use the OPub API.</p>
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.api-clients.create') }}">Create API Client</x-button>
            </div>
        @endif
    </div>
</x-layouts.admin>

