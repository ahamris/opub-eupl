<x-layouts.admin title="Contact Messages">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Contact Messages</h1>
                <p class="text-zinc-600 dark:text-zinc-400">View and manage all contact form submissions</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="outline-primary" icon="download" icon-position="left" x-data x-on:click="toastManager.show('info', 'Exporting messages...')">Export</x-button>
            </div>
        </div>

        <!-- Livewire Table Component -->
        <livewire:admin.contact-submissions-table />
    </div>
</x-layouts.admin>
