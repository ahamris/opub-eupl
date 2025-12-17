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
