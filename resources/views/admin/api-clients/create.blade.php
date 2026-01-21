<x-layouts.admin title="Create API Client">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create API Client</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Generate an API key and optionally restrict browser origins</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.api-clients.index') }}">Back</x-button>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <form action="{{ route('admin.api-clients.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <x-input
                        label="Name"
                        name="name"
                        type="text"
                        placeholder="e.g. My Mobile App"
                        icon="tag"
                        value="{{ old('name') }}"
                        required
                    />
                </div>

                <div>
                    <x-ui.textarea
                        label="Allowed Domains (optional)"
                        name="allowed_domains_raw"
                        placeholder="One per line, e.g.:
app.example.com
*.example.com

Use * to allow any browser origin."
                        rows="7"
                        value="{{ old('allowed_domains_raw') }}"
                        hint="If an API call contains an Origin/Referer header, it must match one of these domains. Leave empty to allow server-to-server calls only."
                    />
                </div>

                <div class="flex items-center gap-3">
                    <input
                        id="is_active"
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ old('is_active', '1') ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-zinc-300 text-indigo-600"
                    >
                    <label for="is_active" class="text-sm text-zinc-700 dark:text-zinc-300">Active</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <x-button variant="secondary" type="button" href="{{ route('admin.api-clients.index') }}">Cancel</x-button>
                    <x-button variant="primary" type="submit" icon="key" icon-position="left">Create & Generate Key</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>

