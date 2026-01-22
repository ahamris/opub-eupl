<x-layouts.admin title="Edit API Client">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit API Client</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Update allowed domains and status</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.api-clients.show', $client) }}">Back</x-button>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <form action="{{ route('admin.api-clients.update', $client) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input
                        label="Name"
                        name="name"
                        type="text"
                        icon="tag"
                        value="{{ old('name', $client->name) }}"
                        required
                    />
                </div>

                <div>
                    <x-ui.textarea
                        label="Allowed Domains (optional)"
                        name="allowed_domains_raw"
                        rows="7"
                        value="{{ old('allowed_domains_raw', $allowedDomainsRaw) }}"
                        hint="One per line. If an API call contains Origin/Referer it must match one of these domains. Use * to allow any browser origin."
                    />
                </div>

                <div class="flex items-center gap-3">
                    <input
                        id="is_active"
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ (old('is_active', $client->is_active ? '1' : '')) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-zinc-300 text-indigo-600"
                    >
                    <label for="is_active" class="text-sm text-zinc-700 dark:text-zinc-300">Active</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <x-button variant="secondary" type="button" href="{{ route('admin.api-clients.show', $client) }}">Cancel</x-button>
                    <x-button variant="primary" type="submit" icon="save" icon-position="left">Save Changes</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>

