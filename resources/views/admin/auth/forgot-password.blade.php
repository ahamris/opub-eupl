<x-layouts.auth title="Forgot Password">
    <div class="flex min-h-full">
        <!-- Left: Forgot Password Form -->
        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div>
                    <h2 class="mt-8 text-2xl/9 font-bold tracking-tight text-gray-900 dark:text-white">Forgot your password?</h2>
                    <p class="mt-2 text-sm/6 text-gray-500 dark:text-gray-400">
                        No worries. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                    </p>
                </div>

                <div class="mt-10">
                    <div>
                        <form action="{{ route('admin.password.email') }}" method="POST" class="space-y-6">
                            @csrf

                            @if (session('status'))
                                <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4">
                                    <p class="text-sm text-green-800 dark:text-green-200">{{ session('status') }}</p>
                                </div>
                            @endif

                            <div>
                                <label for="email" class="block text-sm/6 font-medium text-gray-900 dark:text-gray-100">Email address</label>
                                <div class="mt-2">
                                    <input 
                                        id="email" 
                                        type="email" 
                                        name="email" 
                                        value="{{ old('email') }}"
                                        required 
                                        autocomplete="email" 
                                        autofocus
                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('email') outline-red-600 dark:outline-red-500 @enderror" 
                                    />
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit" class="flex w-full justify-center rounded-md bg-[var(--color-accent)] px-3 py-1.5 text-sm/6 font-semibold text-[var(--color-accent-foreground)] shadow-xs hover:opacity-90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)] dark:bg-[var(--color-accent-content)] dark:shadow-none dark:hover:opacity-90 dark:focus-visible:outline-[var(--color-accent)] transition-opacity">Email Password Reset Link</button>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('admin.login') }}" class="text-sm/6 font-semibold text-[var(--color-accent)] hover:opacity-80 dark:text-[var(--color-accent-content)] dark:hover:opacity-80 transition-opacity">Back to login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Image -->
        <div class="relative hidden w-0 flex-1 lg:block">
            <img src="https://images.unsplash.com/photo-1496917756835-20cb06e75b4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1908&q=80" alt="" class="absolute inset-0 size-full object-cover" />
        </div>
    </div>
</x-layouts.auth>

