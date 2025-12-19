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
            <a href="{{ route('zoeken') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('zoeken') ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Zoeken
            </a>
            
            <!-- Collection Flyout Menu -->
            <div class="relative">
                <button popovertarget="desktop-menu-collectie" class="nav-link px-3 py-2 text-sm font-medium inline-flex items-center gap-x-1 {{ request()->routeIs('dossiers.*') || request()->routeIs('themas.*') || request()->routeIs('verwijzingen') ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                    <span>Collectie</span>
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5">
                        <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                    </svg>
                </button>

                <el-popover id="desktop-menu-collectie" anchor="bottom" popover class="w-screen max-w-max overflow-visible bg-transparent px-4 transition transition-discrete [--anchor-gap:--spacing(5)] backdrop:bg-transparent open:flex data-closed:translate-y-1 data-closed:opacity-0 data-enter:duration-200 data-enter:ease-out data-leave:duration-150 data-leave:ease-in">
                    <div class="w-screen max-w-md flex-auto overflow-hidden rounded-md bg-white text-sm/6 shadow-lg ring-1 ring-gray-900/5">
                        <div class="p-4">
                            <!-- Dossiers -->
                            <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50">
                                <div class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                                    <i class="fas fa-folder-open text-lg text-gray-600 group-hover:text-[var(--color-primary-dark)]"></i>
                                </div>
                                <div>
                                    <a href="{{ route('dossiers.index') }}" class="font-semibold text-gray-900">
                                        Dossiers
                                        <span class="absolute inset-0"></span>
                                    </a>
                                    <p class="mt-1 text-sm text-gray-600">Bekijk alle overheidsdossiers en bijbehorende documenten</p>
                                </div>
                            </div>
                            <!-- Thema's -->
                            <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50">
                                <div class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                                    <i class="fas fa-tags text-lg text-gray-600 group-hover:text-[var(--color-primary-dark)]"></i>
                                </div>
                                <div>
                                    <a href="{{ route('themas.index') }}" class="font-semibold text-gray-900">
                                        Thema's
                                        <span class="absolute inset-0"></span>
                                    </a>
                                    <p class="mt-1 text-sm text-gray-600">Verken documenten op basis van thema en onderwerp</p>
                                </div>
                            </div>
                            <!-- Verwijzingen -->
                            <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50">
                                <div class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                                    <i class="fas fa-link text-lg text-gray-600 group-hover:text-[var(--color-primary-dark)]"></i>
                                </div>
                                <div>
                                    <a href="{{ route('verwijzingen') }}" class="font-semibold text-gray-900">
                                        Verwijzingen
                                        <span class="absolute inset-0"></span>
                                    </a>
                                    <p class="mt-1 text-sm text-gray-600">Ontdek gerelateerde bronnen en externe koppelingen</p>
                                </div>
                            </div>
                        </div>
                        <!-- Footer Actions -->
                        <div class="grid grid-cols-2 divide-x divide-gray-900/5 bg-gray-50">
                            <a href="#" class="flex items-center justify-center gap-x-2.5 p-3 text-sm font-semibold text-gray-900 hover:bg-gray-100">
                                <i class="fas fa-upload text-purple-500"></i>
                                Ook aanleveren
                            </a>
                            <a href="{{ route('contact') }}" class="flex items-center justify-center gap-x-2.5 p-3 text-sm font-semibold text-gray-900 hover:bg-gray-100">
                                <i class="fas fa-phone text-purple-500"></i>
                                Contact opnemen
                            </a>
                        </div>
                    </div>
                </el-popover>
            </div>
            
            <a href="{{ route('reports.index') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('reports.*') ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Dashboard
            </a>
            <a href="{{ route('blog.index') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('blog.*') ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Kennisbank
            </a>
            
            <!-- Contact Flyout Menu -->
            <div class="relative">
                <button popovertarget="desktop-menu-contact" class="nav-link px-3 py-2 text-sm font-medium inline-flex items-center gap-x-1 {{ request()->routeIs('contact') || request()->routeIs('over') ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                    <span>Contact</span>
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5">
                        <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                    </svg>
                </button>

                <el-popover id="desktop-menu-contact" anchor="bottom-end" popover class="w-screen max-w-max overflow-visible bg-transparent px-4 transition transition-discrete [--anchor-gap:--spacing(5)] backdrop:bg-transparent open:flex data-closed:translate-y-1 data-closed:opacity-0 data-enter:duration-200 data-enter:ease-out data-leave:duration-150 data-leave:ease-in">
                    <div class="w-72 flex-auto overflow-hidden rounded-md bg-white text-sm/6 shadow-lg ring-1 ring-gray-900/5">
                        <div class="p-2">
                            <!-- Contact -->
                            <a href="{{ route('contact') }}" class="group flex items-center gap-x-3 rounded-lg p-3 hover:bg-gray-50">
                                <div class="flex size-10 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                                    <i class="fas fa-envelope text-gray-600 group-hover:text-[var(--color-primary-dark)]"></i>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-900">Contact</span>
                                    <p class="text-xs text-gray-600">Neem contact met ons op</p>
                                </div>
                            </a>
                            <!-- Over -->
                            <a href="{{ route('over') }}" class="group flex items-center gap-x-3 rounded-lg p-3 hover:bg-gray-50">
                                <div class="flex size-10 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                                    <i class="fas fa-info-circle text-gray-600 group-hover:text-[var(--color-primary-dark)]"></i>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-900">Over ons</span>
                                    <p class="text-xs text-gray-600">Meer over OpenPublicaties</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </el-popover>
            </div>
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
            <a href="{{ route('zoeken') }}" class="block px-3 py-2.5 text-base font-medium {{ request()->routeIs('zoeken') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Zoeken
            </a>
            
            <!-- Collectie Section -->
            <div class="pt-2 pb-1">
                <span class="block px-3 py-1 text-xs font-semibold uppercase tracking-wider text-[var(--color-on-surface-variant)]">Collectie</span>
            </div>
            <a href="{{ route('dossiers.index') }}" class="block px-3 py-2.5 text-base font-medium pl-6 {{ request()->routeIs('dossiers.*') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Dossiers
            </a>
            <a href="{{ route('themas.index') }}" class="block px-3 py-2.5 text-base font-medium pl-6 {{ request()->routeIs('themas.*') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Thema's
            </a>
            <a href="{{ route('verwijzingen') }}" class="block px-3 py-2.5 text-base font-medium pl-6 {{ request()->routeIs('verwijzingen') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Verwijzingen
            </a>
            
            <div class="border-t border-slate-200/60 my-2"></div>
            
            <a href="{{ route('reports.index') }}" class="block px-3 py-2.5 text-base font-medium {{ request()->routeIs('reports.*') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Dashboard
            </a>
            <a href="{{ route('blog.index') }}" class="block px-3 py-2.5 text-base font-medium {{ request()->routeIs('blog.*') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Kennisbank
            </a>
            <a href="{{ route('contact') }}" class="block px-3 py-2.5 text-base font-medium {{ request()->routeIs('contact') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Contact
            </a>
            <a href="{{ route('over') }}" class="block px-3 py-2.5 text-base font-medium {{ request()->routeIs('over') ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}">
                Over ons
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
