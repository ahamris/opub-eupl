<x-layouts.admin title="API Client">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">{{ $client->name }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">API key details and allowed domains</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.api-clients.index') }}">Back</x-button>
                <x-button variant="secondary" icon="edit" icon-position="left" href="{{ route('admin.api-clients.edit', $client) }}">Edit</x-button>
            </div>
        </div>

        @if($plainApiKey)
            <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center shrink-0">
                        <i class="fas fa-key text-amber-700 dark:text-amber-300"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-amber-900 dark:text-amber-200">New API key (copy now)</div>
                        <div class="text-sm text-amber-800 dark:text-amber-300 mt-1">
                            This key is shown only once. Store it securely in your other application.
                        </div>
                        <div class="mt-3 p-3 bg-white/70 dark:bg-zinc-900/40 border border-amber-200 dark:border-amber-900 rounded-md font-mono text-sm break-all">
                            {{ $plainApiKey }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Details -->
            <div class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-circle-info text-zinc-500 dark:text-zinc-400"></i>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Details</h2>
                    </div>
                    <x-ui.badge :color="$client->is_active ? 'green' : 'gray'">
                        {{ $client->is_active ? 'Active' : 'Disabled' }}
                    </x-ui.badge>
                </div>
                <div class="p-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Client ID</div>
                            <div class="text-sm font-medium text-zinc-900 dark:text-white">#{{ $client->id }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Key Prefix</div>
                            <div class="text-sm font-mono text-zinc-900 dark:text-white">{{ $client->key_prefix ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Created</div>
                            <div class="text-sm text-zinc-900 dark:text-white">{{ $client->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Last Used</div>
                            <div class="text-sm text-zinc-900 dark:text-white">
                                @if($client->last_used_at)
                                    {{ $client->last_used_at->format('Y-m-d H:i') }}
                                    <span class="text-xs text-zinc-500 dark:text-zinc-400">({{ $client->last_used_at->diffForHumans() }})</span>
                                @else
                                    Never
                                @endif
                            </div>
                        </div>
                    </div>

                    @php
                        $domains = $client->allowed_domains ? $client->allowed_domains->toArray() : [];
                    @endphp

                    <div class="pt-2 border-t border-zinc-200 dark:border-zinc-700">
                        <div class="text-xs text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Allowed Domains</div>
                        @if(empty($domains))
                            <div class="text-sm text-zinc-600 dark:text-zinc-400">None. Browser-origin calls (with `Origin`/`Referer`) will be blocked; server-to-server calls are allowed.</div>
                        @elseif(in_array('*', $domains, true))
                            <div class="text-sm text-zinc-900 dark:text-white font-medium">Any (*)</div>
                        @else
                            <div class="flex flex-wrap gap-2">
                                @foreach($domains as $d)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-100 border border-zinc-200 dark:border-zinc-600">
                                        {{ $d }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-bolt text-zinc-500 dark:text-zinc-400"></i>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Actions</h2>
                    </div>
                </div>
                <div class="p-4 space-y-3">
                    <div x-data="{ showRegenerateModal: false }">
                        <x-button variant="secondary" icon="rotate" icon-position="left" type="button" x-on:click="showRegenerateModal = true">
                            Regenerate API Key
                        </x-button>
                        <x-ui.modal alpine-show="showRegenerateModal" size="sm">
                            <x-slot:title>Regenerate API Key</x-slot:title>
                            <p class="text-zinc-600 dark:text-zinc-400">This will revoke the current key immediately. Your external apps must be updated with the new key.</p>
                            <x-slot:footer>
                                <x-button variant="secondary" x-on:click="showRegenerateModal = false">Cancel</x-button>
                                <form action="{{ route('admin.api-clients.regenerate', $client) }}" method="POST" class="inline">
                                    @csrf
                                    <x-button variant="primary" type="submit" x-on:click="showRegenerateModal = false">Regenerate</x-button>
                                </form>
                            </x-slot:footer>
                        </x-ui.modal>
                    </div>

                    <div x-data="{ showDeleteModal: false }">
                        <x-button variant="error" icon="trash" icon-position="left" type="button" x-on:click="showDeleteModal = true">
                            Delete Client
                        </x-button>
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
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

