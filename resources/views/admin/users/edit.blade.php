<x-layouts.admin title="Edit User">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit User</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Update user information</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.users.index') }}">Back to Users</x-button>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Name Field -->
                <div>
                    <x-input 
                        label="First Name" 
                        name="name" 
                        type="text" 
                        placeholder="Enter first name"
                        icon="user"
                        value="{{ old('name', $user->name) }}"
                        required
                    />
                </div>

                <!-- Last Name Field -->
                <div>
                    <x-input 
                        label="Last Name" 
                        name="last_name" 
                        type="text" 
                        placeholder="Enter last name"
                        icon="user"
                        value="{{ old('last_name', $user->last_name) }}"
                    />
                </div>

                <!-- Email Field -->
                <div>
                    <x-input 
                        label="Email" 
                        name="email" 
                        type="email" 
                        placeholder="user@example.com"
                        icon="envelope"
                        value="{{ old('email', $user->email) }}"
                        required
                    />
                </div>

                <!-- Password Field -->
                <div>
                    <x-input 
                        label="Password" 
                        name="password" 
                        type="password" 
                        placeholder="Leave blank to keep current password"
                        icon="lock"
                    />
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Leave blank if you don't want to change the password</p>
                </div>

                <!-- Password Confirmation Field -->
                <div>
                    <x-input 
                        label="Confirm Password" 
                        name="password_confirmation" 
                        type="password" 
                        placeholder="Confirm new password"
                        icon="lock"
                    />
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <x-button variant="secondary" type="button" href="{{ route('admin.users.index') }}">Cancel</x-button>
                    <x-button variant="primary" type="submit" icon="save" icon-position="left">Update User</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>

