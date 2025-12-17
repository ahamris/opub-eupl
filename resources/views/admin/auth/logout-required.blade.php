<x-layouts.auth title="Logout Required">
    <div class="flex min-h-full">
        <!-- Left: Logout Required Form -->
        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div>
                    <h2 class="mt-8 text-2xl/9 font-bold tracking-tight text-gray-900 dark:text-white">Logout Required</h2>
                    <p class="mt-2 text-sm/6 text-gray-500 dark:text-gray-400">
                        You are currently logged in as <strong>{{ $user->email }}</strong>. Please logout first to access the admin panel.
                    </p>
                </div>

                <div class="mt-10">
                    <div>
                        @if (session('error'))
                            <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-4 mb-6">
                                <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                            </div>
                        @endif

                        <form action="{{ route('admin.logout') }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                            Current User
                                        </h3>
                                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                            <p><strong>Name:</strong> {{ $user->name }}{{ $user->last_name ? ' ' . $user->last_name : '' }}</p>
                                            <p><strong>Email:</strong> {{ $user->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <button type="submit" class="flex w-full justify-center rounded-md bg-[var(--color-accent)] px-3 py-1.5 text-sm/6 font-semibold text-[var(--color-accent-foreground)] shadow-xs hover:opacity-90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)] dark:bg-[var(--color-accent-content)] dark:shadow-none dark:hover:opacity-90 dark:focus-visible:outline-[var(--color-accent)] transition-opacity">
                                    Logout and Go to Admin Login
                                </button>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('home') }}" class="text-sm/6 font-semibold text-[var(--color-accent)] hover:opacity-80 dark:text-[var(--color-accent-content)] dark:hover:opacity-80 transition-opacity">Back to Homepage</a>
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

