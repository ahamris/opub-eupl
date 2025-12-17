<header class="h-16 border-b bg-zinc-50 border-zinc-300 dark:bg-zinc-900 dark:border-zinc-700">
    <div class="flex h-full items-center gap-2 md:gap-4 px-4 md:px-8">
        <!-- Left: Mobile toggle + Breadcrumbs -->
        <div class="flex items-center gap-2 md:gap-4 min-w-0 flex-1 md:flex-initial overflow-hidden">
            <button
                @click="$store.sidebar.toggle()"
                class="inline-flex w-8 h-8 items-center justify-center text-zinc-700 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-zinc-100 focus:outline-none lg:hidden flex-shrink-0"
                aria-label="Toggle sidebar"
            >
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <div class="min-w-0 flex-1 md:flex-initial overflow-hidden">
            <x-navigation.breadcrumbs />
            </div>
        </div>

        <!-- Center: Search Input -->
        <div class="hidden md:flex items-center justify-center flex-1 max-w-xl mx-auto">
            @livewire('admin.search', ['dropdownMode' => true])
        </div>

        <!-- Mobile: Search Button -->
        <div class="md:hidden flex items-center flex-shrink-0">
            <button
                @click="$dispatch('open-search')"
                type="button"
                class="inline-flex w-8 h-8 items-center justify-center text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200 focus:outline-none"
                aria-label="Search"
            >
                <i class="fa-solid fa-search"></i>
            </button>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-2 md:gap-3 flex-shrink-0">
            <!-- Theme dropdown: Light / Dark / System -->
            <x-ui.dropdown>
                <x-slot name="trigger">
                    <button class="inline-flex w-8 h-8 items-center justify-center rounded-full hover:bg-zinc-200 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300" title="Theme">
                        <i class="fa-solid fa-circle-half-stroke"></i>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <button type="button" @click="$store.darkMode.set('light')" class="w-full flex items-center justify-between px-4 py-2 text-sm text-zinc-900 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800">
                        <span><i class="fa-solid fa-sun mr-2"></i> Light Mode</span>
                        <i class="fa-solid fa-check text-[var(--color-accent)] dark:text-[var(--color-accent-content)]" x-show="$store.darkMode.mode === 'light'"></i>
                    </button>
                    <button type="button" @click="$store.darkMode.set('dark')" class="w-full flex items-center justify-between px-4 py-2 text-sm text-zinc-900 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800">
                        <span><i class="fa-solid fa-moon mr-2"></i> Dark Mode</span>
                        <i class="fa-solid fa-check text-[var(--color-accent)] dark:text-[var(--color-accent-content)]" x-show="$store.darkMode.mode === 'dark'"></i>
                    </button>
                    <button type="button" @click="$store.darkMode.set('system')" class="w-full flex items-center justify-between px-4 py-2 text-sm text-zinc-900 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800">
                        <span><i class="fa-solid fa-desktop mr-2"></i> System Settings</span>
                        <i class="fa-solid fa-check text-[var(--color-accent)] dark:text-[var(--color-accent-content)]" x-show="$store.darkMode.mode === 'system'"></i>
                    </button>
                </x-slot>
            </x-ui.dropdown>

            <!-- User Dropdown -->
            <x-ui.dropdown>
                <x-slot name="trigger">
                    <button class="flex items-center gap-2 text-zinc-900 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white transition-colors">
                        <div class="w-8 h-8 bg-[var(--color-accent)] dark:bg-[var(--color-accent-content)] rounded flex items-center justify-center text-[var(--color-accent-foreground)] dark:text-[var(--color-accent-foreground)] font-semibold text-xs">
                            @if(auth()->check() && auth()->user()->name)
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            @else
                                <span class="text-xs">Ad</span>
                            @endif
                        </div>
                        <span class="hidden md:block">{{ auth()->check() ? auth()->user()->name : 'Admin' }}</span>
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <!-- User Info Section -->
                    <div class="px-4 py-3 border-b border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[var(--color-accent)] dark:bg-[var(--color-accent-content)] rounded flex items-center justify-center text-[var(--color-accent-foreground)] dark:text-[var(--color-accent-foreground)] font-semibold text-sm flex-shrink-0">
                                @if(auth()->check() && auth()->user()->name)
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                @else
                                    <span class="text-sm">Ad</span>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-semibold text-sm text-zinc-900 dark:text-white truncate">
                                    {{ auth()->check() ? auth()->user()->name : 'Admin' }}
                                </div>
                                <div class="text-xs text-zinc-600 dark:text-zinc-400 truncate">
                                    {{ auth()->check() && auth()->user()->email ? auth()->user()->email : 'admin@example.com' }}
                                </div>
                                <div class="text-xs font-medium text-zinc-700 dark:text-zinc-300 mt-0.5">
                                    Administrator
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="py-1">
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                            <i class="fa-regular fa-user w-5 text-center text-zinc-600 dark:text-zinc-400"></i>
                            <span>My Profile</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                            <i class="fa-regular fa-gear w-5 text-center text-zinc-600 dark:text-zinc-400"></i>
                            <span>Settings</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                            <i class="fa-regular fa-shield w-5 text-center text-zinc-600 dark:text-zinc-400"></i>
                            <span>Two-Factor Auth</span>
                        </a>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-zinc-200 dark:border-zinc-700"></div>

                    <!-- Sign Out -->
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </x-slot>
            </x-ui.dropdown>
        </div>
    </div>
</header>
