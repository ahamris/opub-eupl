<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/60" role="banner" x-data="{ mobileMenuOpen: false }">
    <nav aria-label="Global" class="mx-auto flex max-w-7xl items-center justify-between px-6 py-3.5 lg:px-8">
        <!-- Brand -->
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="-m-1.5 p-1.5 flex items-center gap-2.5">
                <span class="sr-only">Open overheid</span>
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-md bg-[var(--color-primary)] text-white font-semibold text-sm tracking-tight">
                    OO
                </span>
                <div class="hidden sm:flex flex-col">
                    <span class="text-sm font-semibold text-[var(--color-on-surface)] tracking-tight">Overheid.nl</span>
                </div>
            </a>
        </div>

        <!-- Mobile menu button -->
        <div class="flex lg:hidden">
            <button
                type="button"
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)] transition-colors"
            >
                <span class="sr-only">Open hoofdmenu</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6">
                    <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>

        <!-- Desktop navigation -->
        <div class="hidden lg:flex lg:items-center lg:gap-x-1">
            <a href="{{ route('home') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('home') || request()->routeIs('zoek') ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Home
            </a>
            
            <!-- Producten Flyout Menu -->
            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <button 
                    type="button"
                    class="nav-link px-3 py-2 text-sm font-medium inline-flex items-center gap-x-1 text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]"
                    @click="open = !open"
                >
                    <span>Producten</span>
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5 transition-transform duration-200" :class="{ 'rotate-180': open }">
                        <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                    </svg>
                </button>

                <!-- Flyout Panel -->
                <div 
                    x-show="open"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute left-1/2 z-50 mt-3 w-screen max-w-md -translate-x-1/2"
                >
                    <div class="overflow-hidden rounded-md bg-white shadow-lg ring-1 ring-gray-900/5">
                        <div class="p-4">
                            <!-- Analytics -->
                            <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50">
                                <div class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                                    <i class="fas fa-chart-pie text-lg text-gray-600 group-hover:text-[var(--color-primary-dark)]"></i>
                                </div>
                                <div>
                                    <a href="#" class="font-semibold text-gray-900">
                                        Analytics
                                        <span class="absolute inset-0"></span>
                                    </a>
                                    <p class="mt-1 text-sm text-gray-600">Krijg beter inzicht in uw documentverkeer</p>
                                </div>
                            </div>
                            <!-- Engagement -->
                            <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50">
                                <div class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                                    <i class="fas fa-mouse-pointer text-lg text-gray-600 group-hover:text-[var(--color-primary-dark)]"></i>
                                </div>
                                <div>
                                    <a href="#" class="font-semibold text-gray-900">
                                        Engagement
                                        <span class="absolute inset-0"></span>
                                    </a>
                                    <p class="mt-1 text-sm text-gray-600">Spreek direct met uw burgers</p>
                                </div>
                            </div>
                            <!-- Security -->
                            <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50">
                                <div class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                                    <i class="fas fa-fingerprint text-lg text-gray-600 group-hover:text-[var(--color-primary-dark)]"></i>
                                </div>
                                <div>
                                    <a href="#" class="font-semibold text-gray-900">
                                        Beveiliging
                                        <span class="absolute inset-0"></span>
                                    </a>
                                    <p class="mt-1 text-sm text-gray-600">Gegevens blijven altijd veilig en beveiligd</p>
                                </div>
                            </div>
                            <!-- Integrations -->
                            <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50">
                                <div class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                                    <i class="fas fa-th-large text-lg text-gray-600 group-hover:text-[var(--color-primary-dark)]"></i>
                                </div>
                                <div>
                                    <a href="#" class="font-semibold text-gray-900">
                                        Integraties
                                        <span class="absolute inset-0"></span>
                                    </a>
                                    <p class="mt-1 text-sm text-gray-600">Verbind met externe tools en systemen</p>
                                </div>
                            </div>
                            <!-- Automations -->
                            <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50">
                                <div class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                                    <i class="fas fa-sync-alt text-lg text-gray-600 group-hover:text-[var(--color-primary-dark)]"></i>
                                </div>
                                <div>
                                    <a href="#" class="font-semibold text-gray-900">
                                        Automatisering
                                        <span class="absolute inset-0"></span>
                                    </a>
                                    <p class="mt-1 text-sm text-gray-600">Bouw strategische workflows die converteren</p>
                                </div>
                            </div>
                        </div>
                        <!-- Footer Actions -->
                        <div class="grid grid-cols-2 divide-x divide-gray-900/5 bg-gray-50">
                            <a href="#" class="flex items-center justify-center gap-x-2.5 p-3 text-sm font-semibold text-gray-900 hover:bg-gray-100">
                                <i class="fas fa-play-circle text-purple"></i>
                                Demo bekijken
                            </a>
                            <a href="{{ route('contact') }}" class="flex items-center justify-center gap-x-2.5 p-3 text-sm font-semibold text-gray-900 hover:bg-gray-100">
                                <i class="fas fa-phone text-purple"></i>
                                Contact opnemen
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('zoeken') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('zoeken') ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Zoeken
            </a>
            <a href="{{ route('themas.index') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('themas.*') ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Thema's
            </a>
            <a href="{{ route('dossiers.index') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('dossiers.*') ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Dossiers
            </a>
            <a href="{{ route('reports.index') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('reports.*') ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                In cijfers
            </a>
            <a href="{{ route('verwijzingen') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('verwijzingen') ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Verwijzingen
            </a>
            <a href="{{ route('blog.index') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('blog.*') ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Blog
            </a>
            <a href="{{ route('over') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('over') ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Over
            </a>
            <a href="{{ route('contact') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('contact') ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Contact
            </a>
        </div>
    </nav>

    <!-- Mobile menu -->
    <div
        x-show="mobileMenuOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        @click.away="mobileMenuOpen = false"
        class="lg:hidden border-t border-slate-200/60 bg-white/98 backdrop-blur-md"
    >
        <nav class="mx-auto max-w-7xl px-6 py-3 space-y-0.5" aria-label="Mobiele navigatie">
            <a href="{{ route('home') }}" class="block px-3 py-2.5 text-base font-medium {{ request()->routeIs('home') || request()->routeIs('zoek') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Home
            </a>
            <a href="{{ route('zoeken') }}" class="block px-3 py-2.5 text-base font-medium {{ request()->routeIs('zoeken') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Zoeken
            </a>
            <a href="{{ route('themas.index') }}" class="block px-3 py-2.5 text-base font-medium {{ request()->routeIs('themas.*') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Thema's
            </a>
            <a href="{{ route('dossiers.index') }}" class="block px-3 py-2.5 text-base font-medium {{ request()->routeIs('dossiers.*') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Dossiers
            </a>
            <a href="{{ route('reports.index') }}" class="block px-3 py-2.5 text-base font-medium {{ request()->routeIs('reports.*') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                In cijfers
            </a>
            <a href="{{ route('verwijzingen') }}" class="block px-3 py-2.5 text-base font-medium {{ request()->routeIs('verwijzingen') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Verwijzingen
            </a>
            <a href="{{ route('blog.index') }}" class="block px-3 py-2.5 text-base font-medium {{ request()->routeIs('blog.*') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Blog
            </a>
            <a href="{{ route('over') }}" class="block px-3 py-2.5 text-base font-medium {{ request()->routeIs('over') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Over
            </a>
            <a href="{{ route('contact') }}" class="block px-3 py-2.5 text-base font-medium {{ request()->routeIs('contact') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Contact
            </a>
        </nav>
    </div>
</header>

<style>
    /* Navigation link underline animation */
    .nav-link {
        position: relative;
        transition: color 0.2s ease;
    }
    
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background-color: var(--color-primary-dark);
        transition: width 0.3s ease, left 0.3s ease;
    }
    
    .nav-link:hover::after {
        width: 100%;
        left: 0;
    }
    
    /* Active state - always show underline */
    .nav-link-active::after {
        width: 100%;
        left: 0;
    }
</style>
