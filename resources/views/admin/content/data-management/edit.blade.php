<x-layouts.admin title="Edit Data Entry">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Data Entry</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Update data entry information</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.data-management.index') }}">Back to Data Management</x-button>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <form action="{{ route('admin.content.data-management.update', $dataManagement) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Title Field -->
                <div>
                    <x-input 
                        label="Title" 
                        name="title" 
                        type="text" 
                        placeholder="Enter entry title"
                        icon="heading"
                        value="{{ old('title', $dataManagement->title) }}"
                        required
                    />
                </div>

                <!-- Description Field -->
                <div>
                    <x-ui.textarea 
                        label="Description" 
                        name="description" 
                        placeholder="Enter entry description"
                        rows="4"
                        value="{{ old('description', $dataManagement->description) }}"
                    />
                </div>

                <!-- Type Field -->
                <div>
                    <x-input 
                        label="Type" 
                        name="type" 
                        type="text" 
                        placeholder="e.g., general, category, item"
                        icon="tag"
                        value="{{ old('type', $dataManagement->type) }}"
                        required
                        list="type-suggestions"
                    />
                    <datalist id="type-suggestions">
                        <option value="general">General</option>
                        <option value="category">Category</option>
                        <option value="item">Item</option>
                        <option value="reference">Reference</option>
                    </datalist>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Category or type for this entry</p>
                </div>

                <!-- Status Field -->
                <div>
                    <x-ui.select 
                        label="Status" 
                        name="status"
                        :options="[
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'archived' => 'Archived'
                        ]"
                        value="{{ old('status', $dataManagement->status) }}"
                        required
                    />
                </div>

                <!-- Priority Field -->
                <div>
                    <x-input 
                        label="Priority" 
                        name="priority" 
                        type="number" 
                        placeholder="0"
                        icon="sort"
                        value="{{ old('priority', $dataManagement->priority) }}"
                        min="0"
                    />
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Higher numbers appear first (default: 0)</p>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <x-button variant="secondary" type="button" href="{{ route('admin.content.data-management.index') }}">Cancel</x-button>
                    <x-button variant="primary" type="submit" icon="save" icon-position="left">Update Entry</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>

