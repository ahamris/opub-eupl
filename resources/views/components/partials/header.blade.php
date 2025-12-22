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
            <!-- Theme Toggle -->
            <div
                class="mr-10"
                x-data="{
                    darkModePreference: 'system', // 'system', 'on' or 'off',
                    useLocalStorage: true, // true or false

                    // Helper variables
                    localStorageKey: 'dark-mode',

                    // Initialize dark mode on component load
                    init() {
                        // Load preference from localStorage
                        if (this.useLocalStorage) {
                            this.darkModePreference = this.loadDarkModePreference();
                        }
                        
                        // Apply dark mode immediately
                        this.applyDarkMode();
                        
                        // Listen for system preference changes
                        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                            if (this.darkModePreference === 'system') {
                                this.applyDarkMode();
                            }
                        });
                    },

                    // Load dark mode preference from localStorage
                    loadDarkModePreference() {
                        const stored = localStorage.getItem(this.localStorageKey);
                        if (stored === 'on' || stored === 'off' || stored === 'system') {
                            return stored;
                        }
                        return this.darkModePreference;
                    },

                    // Apply dark mode based on current preference
                    applyDarkMode() {
                        let darkModeActive;

                        if (this.darkModePreference === 'system') {
                            darkModeActive = window.matchMedia('(prefers-color-scheme: dark)').matches;
                        } else {
                            darkModeActive = this.darkModePreference === 'on';
                        }

                        document.documentElement.classList.toggle('dark', darkModeActive);
                    },

                    // Set dark mode preference
                    setDarkMode(value) {
                        this.darkModePreference = value;
                        
                        // Save preference to localStorage
                        if (this.useLocalStorage) {
                            localStorage.setItem(this.localStorageKey, value);
                        }

                        this.applyDarkMode();
                    },
                }"
            >
                <div class="inline-flex rounded-full bg-zinc-100/75 p-1 ring-1 ring-zinc-200/90 dark:bg-zinc-950/50 dark:ring-zinc-700/50">
                    <div class="relative inline-flex items-center">
                        <div
                            x-cloak
                            class="toggle-indicator absolute inset-y-0 left-0 w-1/3 rounded-full bg-white shadow-sm transition-transform duration-150 ease-out dark:bg-zinc-700/75"
                            x-bind:class="{
                                'translate-x-0': darkModePreference === 'off',
                                'translate-x-full': darkModePreference === 'system',
                                'translate-x-[200%]': darkModePreference === 'on',
                            }"
                        ></div>
                        <label class="group relative flex">
                            <input
                                class="peer absolute start-0 top-0 appearance-none opacity-0"
                                id="dark-mode-off"
                                name="dark-mode-switch"
                                type="radio"
                                value="off"
                                x-bind:checked="darkModePreference === 'off'"
                                x-on:change="setDarkMode('off')"
                            />
                            <span
                                class="relative flex cursor-pointer items-center justify-center rounded-lg p-2 text-zinc-500 transition-transform duration-150 ease-out peer-checked:text-zinc-900 peer-focus-visible:ring-3 peer-focus-visible:ring-zinc-200 hover:text-zinc-900 active:scale-97 dark:text-zinc-400 dark:peer-checked:text-white dark:peer-focus-visible:ring-zinc-500/50 dark:hover:text-white"
                            >
                                <svg
                                    class="lucide lucide-sun size-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                    height="24"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    width="24"
                                >
                                    <circle cx="12" cy="12" r="4"></circle>
                                    <path d="M12 2v2"></path>
                                    <path d="M12 20v2"></path>
                                    <path d="m4.93 4.93 1.41 1.41"></path>
                                    <path d="m17.66 17.66 1.41 1.41"></path>
                                    <path d="M2 12h2"></path>
                                    <path d="M20 12h2"></path>
                                    <path d="m6.34 17.66-1.41 1.41"></path>
                                    <path d="m19.07 4.93-1.41 1.41"></path>
                                </svg>
                                <span class="sr-only">Light mode</span>
                            </span>
                        </label>
                        <label class="group relative flex">
                            <input
                                class="peer absolute start-0 top-0 appearance-none opacity-0"
                                id="dark-mode-system"
                                name="dark-mode-switch"
                                type="radio"
                                value="system"
                                x-bind:checked="darkModePreference === 'system'"
                                x-on:change="setDarkMode('system')"
                            />
                            <span
                                class="relative flex cursor-pointer items-center justify-center rounded-lg p-2 text-zinc-500 transition-transform duration-150 ease-out peer-checked:text-zinc-900 peer-focus-visible:ring-3 peer-focus-visible:ring-zinc-200 hover:text-zinc-900 active:scale-97 dark:text-zinc-400 dark:peer-checked:text-white dark:peer-focus-visible:ring-zinc-500/50 dark:hover:text-white"
                            >
                                <svg
                                    class="lucide lucide-monitor-smartphone size-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                    height="24"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    width="24"
                                >
                                    <path
                                        d="M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8"
                                    ></path>
                                    <path d="M10 19v-3.96 3.15"></path>
                                    <path d="M7 19h5"></path>
                                    <rect height="10" rx="2" width="6" x="16" y="12"></rect>
                                </svg>
                                <span class="sr-only">System preference</span>
                            </span>
                        </label>
                        <label class="group relative flex">
                            <input
                                class="peer absolute start-0 top-0 appearance-none opacity-0"
                                id="dark-mode-on"
                                name="dark-mode-switch"
                                type="radio"
                                value="on"
                                x-bind:checked="darkModePreference === 'on'"
                                x-on:change="setDarkMode('on')"
                            />
                            <span
                                class="relative flex cursor-pointer items-center justify-center rounded-lg p-2 text-zinc-500 transition-transform duration-150 ease-out peer-checked:text-zinc-900 peer-focus-visible:ring-3 peer-focus-visible:ring-zinc-200 hover:text-zinc-900 active:scale-97 dark:text-zinc-400 dark:peer-checked:text-white dark:peer-focus-visible:ring-zinc-500/50 dark:hover:text-white"
                            >
                                <svg
                                    class="lucide lucide-moon size-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                    height="24"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    width="24"
                                >
                                    <path
                                        d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401"
                                    ></path>
                                </svg>
                                <span class="sr-only">Dark mode</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

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
