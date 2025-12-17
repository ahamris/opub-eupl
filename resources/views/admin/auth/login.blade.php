<x-layouts.auth title="Admin Login">
    <div class="flex min-h-full">
        <!-- Left: Login Form -->
        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div>
                    <h2 class="mt-8 text-2xl/9 font-bold tracking-tight text-gray-900 dark:text-white">Sign in to your account</h2>
                    <p class="mt-2 text-sm/6 text-gray-500 dark:text-gray-400">
                        Admin panel access
                    </p>
                </div>

                <div class="mt-10">
                    <div>
                        <form action="{{ route('admin.login.store') }}" method="POST" class="space-y-6">
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
                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('email') outline-red-600 dark:outline-red-500 @enderror" 
                                    />
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="password" class="block text-sm/6 font-medium text-gray-900 dark:text-gray-100">Password</label>
                                <div class="mt-2">
                                    <input 
                                        id="password" 
                                        type="password" 
                                        name="password" 
                                        required 
                                        autocomplete="current-password" 
                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('password') outline-red-600 dark:outline-red-500 @enderror" 
                                    />
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex gap-3">
                                    <div class="flex h-6 shrink-0 items-center">
                                        <div class="group grid size-4 grid-cols-1">
                                            <input 
                                                id="remember" 
                                                type="checkbox" 
                                                name="remember" 
                                                class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-[var(--color-accent)] checked:bg-[var(--color-accent)] indeterminate:border-[var(--color-accent)] indeterminate:bg-[var(--color-accent)] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)] disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 dark:border-white/10 dark:bg-white/5 dark:checked:border-[var(--color-accent)] dark:checked:bg-[var(--color-accent)] dark:indeterminate:border-[var(--color-accent)] dark:indeterminate:bg-[var(--color-accent)] dark:focus-visible:outline-[var(--color-accent)] forced-colors:appearance-auto" 
                                            />
                                            <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25">
                                                <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-checked:opacity-100" />
                                                <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-indeterminate:opacity-100" />
                                            </svg>
                                        </div>
                                    </div>
                                    <label for="remember" class="block text-sm/6 text-gray-900 dark:text-gray-300">Remember me</label>
                                </div>

                                <div class="text-sm/6">
                                    <a href="{{ route('admin.password.request') }}" class="font-semibold text-[var(--color-accent)] hover:opacity-80 dark:text-[var(--color-accent-content)] dark:hover:opacity-80 transition-opacity">Forgot password?</a>
                                </div>
                            </div>

                            <div>
                                <button type="submit" class="flex w-full justify-center rounded-md bg-[var(--color-accent)] px-3 py-1.5 text-sm/6 font-semibold text-[var(--color-accent-foreground)] shadow-xs hover:opacity-90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)] dark:bg-[var(--color-accent-content)] dark:shadow-none dark:hover:opacity-90 dark:focus-visible:outline-[var(--color-accent)] transition-opacity">Sign in</button>
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

