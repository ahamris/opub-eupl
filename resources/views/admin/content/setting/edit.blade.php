<x-layouts.admin title="Edit Setting">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Setting</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Update setting information</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.setting.index', ['group' => $setting->group]) }}">Back to Settings</x-button>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <form action="{{ route('admin.content.setting.update', $setting) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Key Field -->
                <div>
                    <x-input 
                        label="Key" 
                        name="_key" 
                        type="text" 
                        placeholder="e.g., site_title, smtp_host"
                        icon="key"
                        value="{{ old('_key', $setting->_key) }}"
                        required
                    />
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Unique identifier for this setting (lowercase, use underscores)</p>
                </div>

                <!-- Value Field -->
                <div>
                    <x-ui.textarea 
                        label="Value" 
                        name="_value" 
                        placeholder="Enter setting value"
                        rows="4"
                        value="{{ old('_value', $setting->_value) }}"
                    />
                </div>

                <!-- Group Field -->
                <div>
                    <x-input 
                        label="Group" 
                        name="group" 
                        type="text" 
                        placeholder="e.g., general, smtp, app"
                        icon="folder"
                        value="{{ old('group', $setting->group) }}"
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
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <x-button variant="secondary" type="button" href="{{ route('admin.content.setting.index', ['group' => $setting->group]) }}">Cancel</x-button>
                    <x-button variant="primary" type="submit" icon="save" icon-position="left">Update Setting</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>

