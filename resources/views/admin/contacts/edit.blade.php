<x-layouts.admin title="Edit Contact">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Contact</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Update contact information</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.contacts.show', $contact) }}">Back</x-button>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <form action="{{ route('admin.contacts.update', $contact) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input
                            label="Email"
                            name="email"
                            type="email"
                            placeholder="contact@example.com"
                            icon="envelope"
                            value="{{ old('email', $contact->email) }}"
                            required
                        />
                    </div>

                    <div>
                        <x-input
                            label="Full Name"
                            name="full_name"
                            type="text"
                            placeholder="John Doe"
                            icon="user"
                            value="{{ old('full_name', $contact->full_name) }}"
                        />
                    </div>

                    <div>
                        <x-input
                            label="Organisation"
                            name="organisation"
                            type="text"
                            placeholder="Company Name"
                            icon="building"
                            value="{{ old('organisation', $contact->organisation) }}"
                        />
                    </div>

                    <div>
                        <x-input
                            label="Phone"
                            name="phone"
                            type="tel"
                            placeholder="+31 6 12345678"
                            icon="phone"
                            value="{{ old('phone', $contact->phone) }}"
                        />
                    </div>

                    <div>
                        <x-ui.select
                            label="Status"
                            name="status"
                            icon="toggle-on"
                            value="{{ old('status', $contact->status) }}"
                        >
                            <option value="new">New</option>
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </x-ui.select>
                    </div>

                    <div>
                        <x-ui.select
                            label="Priority"
                            name="priority"
                            icon="exclamation-triangle"
                            value="{{ old('priority', $contact->priority) }}"
                        >
                            <option value="low">Low</option>
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </x-ui.select>
                    </div>
                </div>

                <div>
                    <x-ui.textarea
                        label="Notes"
                        name="notes"
                        placeholder="Internal notes about this contact..."
                        rows="5"
                        value="{{ old('notes', $contact->notes) }}"
                        hint="Internal notes visible only to admins"
                    />
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <x-button variant="secondary" type="button" href="{{ route('admin.contacts.show', $contact) }}">Cancel</x-button>
                    <x-button variant="primary" type="submit" icon="save" icon-position="left">Save Changes</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
