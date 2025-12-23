<x-layouts.admin title="Create Setting">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create Setting</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Add a new setting to your system</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.setting.index') }}">Back to Settings</x-button>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <form action="{{ route('admin.content.setting.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Key Field -->
                <div>
                    <x-input 
                        label="Key" 
                        name="_key" 
                        type="text" 
                        placeholder="e.g., site_title, smtp_host"
                        icon="key"
                        value="{{ old('_key') }}"
                        required
                    />
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Unique identifier for this setting (lowercase, use underscores)</p>
                    @error('_key')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Value Field -->
                <div>
                    <x-ui.textarea 
                        label="Value" 
                        name="_value" 
                        placeholder="Enter setting value"
                        rows="4"
                        value="{{ old('_value') }}"
                    />
                    @error('_value')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Group Field -->
                <div>
                    <x-input 
                        label="Group" 
                        name="group" 
                        type="text" 
                        placeholder="e.g., general, smtp, app"
                        icon="folder"
                        value="{{ old('group', 'general') }}"
                        required
                        list="group-suggestions"
                    />
                    <datalist id="group-suggestions">
                        <option value="general">General</option>
                        <option value="smtp">SMTP</option>
                        <option value="app">Application</option>
                        <option value="social">Social Media</option>
                    </datalist>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Group settings together for better organization (you can type a custom group name)</p>
                    @error('group')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <x-button variant="secondary" type="button" href="{{ route('admin.content.setting.index') }}">Cancel</x-button>
                    <x-button variant="primary" type="submit" icon="save" icon-position="left">Create Setting</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>

