<!-- Modern Sticky Header for All Pages -->
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-[var(--color-outline-variant)]/50 shadow-sm" role="banner" x-data="{ mobileMenuOpen: false }">
    <nav class="mx-auto max-w-7xl px-6 lg:px-8" aria-label="Top">
        <div class="flex w-full items-center justify-between py-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="text-[var(--font-size-headline-small)] font-semibold text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] transition-colors duration-200 focus:outline-2 focus:outline-[var(--color-primary)] focus:outline-offset-2 rounded">
                    Overheid.nl
                </a>
                <span class="text-[var(--font-size-body-medium)] text-[var(--color-on-surface-variant)] font-medium">Open overheid</span>
            </div>
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}" class="text-[var(--font-size-body-large)] {{ request()->routeIs('home') || request()->routeIs('zoek') ? 'font-semibold text-[var(--color-primary)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)]' }} transition-colors duration-200 focus:outline-2 focus:outline-[var(--color-primary)] focus:outline-offset-2 rounded px-4 py-2">
                    Home
                </a>
                <a href="{{ route('zoeken') }}" class="text-[var(--font-size-body-large)] {{ request()->routeIs('zoeken') ? 'font-semibold text-[var(--color-primary)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)]' }} transition-colors duration-200 focus:outline-2 focus:outline-[var(--color-primary)] focus:outline-offset-2 rounded px-4 py-2">
                    Zoeken
                </a>
                <a href="{{ route('themas.index') }}" class="text-[var(--font-size-body-large)] {{ request()->routeIs('themas.*') ? 'font-semibold text-[var(--color-primary)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)]' }} transition-colors duration-200 focus:outline-2 focus:outline-[var(--color-primary)] focus:outline-offset-2 rounded px-4 py-2">
                    Thema's
                </a>
                <a href="{{ route('dossiers.index') }}" class="text-[var(--font-size-body-large)] {{ request()->routeIs('dossiers.*') ? 'font-semibold text-[var(--color-primary)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)]' }} transition-colors duration-200 focus:outline-2 focus:outline-[var(--color-primary)] focus:outline-offset-2 rounded px-4 py-2">
                    Dossiers
                </a>
                <a href="{{ route('reports.index') }}" class="text-[var(--font-size-body-large)] {{ request()->routeIs('reports.*') ? 'font-semibold text-[var(--color-primary)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)]' }} transition-colors duration-200 focus:outline-2 focus:outline-[var(--color-primary)] focus:outline-offset-2 rounded px-4 py-2">
                    In cijfers
                </a>
                <a href="{{ route('verwijzingen') }}" class="text-[var(--font-size-body-large)] {{ request()->routeIs('verwijzingen') ? 'font-semibold text-[var(--color-primary)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)]' }} transition-colors duration-200 focus:outline-2 focus:outline-[var(--color-primary)] focus:outline-offset-2 rounded px-4 py-2">
                    Verwijzingen
                </a>
                <a href="{{ route('over') }}" class="text-[var(--font-size-body-large)] {{ request()->routeIs('over') ? 'font-semibold text-[var(--color-primary)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)]' }} transition-colors duration-200 focus:outline-2 focus:outline-[var(--color-primary)] focus:outline-offset-2 rounded px-4 py-2">
                    Over
                </a>
            </div>
            <!-- Mobile Menu Button -->
            <button 
                type="button" 
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="md:hidden p-2 text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)] focus:outline-2 focus:outline-[var(--color-primary)] focus:outline-offset-2 rounded transition-colors duration-200"
                aria-label="Menu"
                aria-expanded="false"
                :aria-expanded="mobileMenuOpen"
            >
                <i class="fas fa-bars text-xl transition-transform duration-200" :class="mobileMenuOpen ? 'rotate-90' : ''" aria-hidden="true"></i>
            </button>
        </div>
    </nav>
    
    <!-- Mobile Menu -->
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
        class="md:hidden border-t border-[var(--color-outline-variant)]/50 bg-white/98 backdrop-blur-sm"
    >
        <nav class="mx-auto max-w-7xl px-6 py-4 space-y-1" aria-label="Mobiele navigatie">
            <a href="{{ route('home') }}" class="block px-4 py-3 text-[var(--font-size-body-large)] {{ request()->routeIs('home') || request()->routeIs('zoek') ? 'font-semibold text-[var(--color-primary)] bg-[var(--color-primary-light)]/30' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/20' }} transition-all duration-200 rounded">
                Home
            </a>
            <a href="{{ route('zoeken') }}" class="block px-4 py-3 text-[var(--font-size-body-large)] {{ request()->routeIs('zoeken') ? 'font-semibold text-[var(--color-primary)] bg-[var(--color-primary-light)]/30' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/20' }} transition-all duration-200 rounded">
                Zoeken
            </a>
            <a href="{{ route('themas.index') }}" class="block px-4 py-3 text-[var(--font-size-body-large)] {{ request()->routeIs('themas.*') ? 'font-semibold text-[var(--color-primary)] bg-[var(--color-primary-light)]/30' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/20' }} transition-all duration-200 rounded">
                Thema's
            </a>
            <a href="{{ route('dossiers.index') }}" class="block px-4 py-3 text-[var(--font-size-body-large)] {{ request()->routeIs('dossiers.*') ? 'font-semibold text-[var(--color-primary)] bg-[var(--color-primary-light)]/30' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/20' }} transition-all duration-200 rounded">
                Dossiers
            </a>
            <a href="{{ route('reports.index') }}" class="block px-4 py-3 text-[var(--font-size-body-large)] {{ request()->routeIs('reports.*') ? 'font-semibold text-[var(--color-primary)] bg-[var(--color-primary-light)]/30' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/20' }} transition-all duration-200 rounded">
                In cijfers
            </a>
            <a href="{{ route('verwijzingen') }}" class="block px-4 py-3 text-[var(--font-size-body-large)] {{ request()->routeIs('verwijzingen') ? 'font-semibold text-[var(--color-primary)] bg-[var(--color-primary-light)]/30' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/20' }} transition-all duration-200 rounded">
                Verwijzingen
            </a>
            <a href="{{ route('over') }}" class="block px-4 py-3 text-[var(--font-size-body-large)] {{ request()->routeIs('over') ? 'font-semibold text-[var(--color-primary)] bg-[var(--color-primary-light)]/30' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/20' }} transition-all duration-200 rounded">
                Over
            </a>
        </nav>
    </div>
</header>

@if(false)
<!-- Modern Header with Gradient Background for Other Pages -->
<header class="relative isolate overflow-hidden bg-gradient-to-br from-primary via-primary-dark to-primary" role="banner" x-data="{ mobileMenuOpen: false }">
    <!-- Subtle Pattern Overlay -->
    <div class="absolute inset-0 -z-10 opacity-10">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-primary/95 via-primary/90 to-primary"></div>
    
    <nav class="mx-auto max-w-7xl px-6 lg:px-8 py-4" role="navigation" aria-label="Hoofdnavigatie">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="text-headline-small font-semibold text-on-primary hover:opacity-90 transition-opacity duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-sm">
                    Overheid.nl
                </a>
                <span class="text-body-medium text-on-primary/90 font-medium">Open overheid</span>
            </div>
            <ul class="hidden md:flex gap-1 flex-wrap">
                <li>
                    <a href="{{ route('home') }}" class="text-body-large {{ request()->routeIs('home') || request()->routeIs('zoek') ? 'font-semibold bg-on-primary/10' : 'text-on-primary/90 hover:bg-on-primary/10' }} transition-all duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-sm px-4 py-2">
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('zoeken') }}" class="text-body-large {{ request()->routeIs('zoeken') ? 'font-semibold bg-on-primary/10' : 'text-on-primary/90 hover:bg-on-primary/10' }} transition-all duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-sm px-4 py-2">
                        Zoeken
                    </a>
                </li>
                <li>
                    <a href="{{ route('themas.index') }}" class="text-body-large {{ request()->routeIs('themas.*') ? 'font-semibold bg-on-primary/10' : 'text-on-primary/90 hover:bg-on-primary/10' }} transition-all duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-sm px-4 py-2">
                        Thema's
                    </a>
                </li>
                <li>
                    <a href="{{ route('dossiers.index') }}" class="text-body-large {{ request()->routeIs('dossiers.*') ? 'font-semibold bg-on-primary/10' : 'text-on-primary/90 hover:bg-on-primary/10' }} transition-all duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-sm px-4 py-2">
                        Dossiers
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.index') }}" class="text-body-large {{ request()->routeIs('reports.*') ? 'font-semibold bg-on-primary/10' : 'text-on-primary/90 hover:bg-on-primary/10' }} transition-all duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-sm px-4 py-2">
                        In cijfers
                    </a>
                </li>
                <li>
                    <a href="{{ route('verwijzingen') }}" class="text-body-large {{ request()->routeIs('verwijzingen') ? 'font-semibold bg-on-primary/10' : 'text-on-primary/90 hover:bg-on-primary/10' }} transition-all duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-sm px-4 py-2">
                        Verwijzingen
                    </a>
                </li>
                <li>
                    <a href="{{ route('over') }}" class="text-body-large {{ request()->routeIs('over') ? 'font-semibold bg-on-primary/10' : 'text-on-primary/90 hover:bg-on-primary/10' }} transition-all duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-sm px-4 py-2">
                        Over
                    </a>
                </li>
            </ul>
            <!-- Mobile Menu Button -->
            <button 
                type="button" 
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="md:hidden p-2 text-on-primary hover:bg-on-primary/10 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-sm transition-colors duration-200"
                aria-label="Menu"
                :aria-expanded="mobileMenuOpen"
            >
                <i class="fas fa-bars text-xl transition-transform duration-200" :class="mobileMenuOpen ? 'rotate-90' : ''" aria-hidden="true"></i>
            </button>
        </div>
    </nav>
    
    <!-- Mobile Menu -->
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
        class="md:hidden border-t border-on-primary/20 bg-primary/98 backdrop-blur-sm"
    >
        <nav class="mx-auto max-w-7xl px-6 py-4 space-y-1" aria-label="Mobiele navigatie">
            <a href="{{ route('home') }}" class="block px-4 py-3 text-body-large {{ request()->routeIs('home') || request()->routeIs('zoek') ? 'font-semibold text-on-primary bg-on-primary/20' : 'text-on-primary/90 hover:text-on-primary hover:bg-on-primary/10' }} transition-all duration-200 rounded-sm">
                Home
            </a>
            <a href="{{ route('zoeken') }}" class="block px-4 py-3 text-body-large {{ request()->routeIs('zoeken') ? 'font-semibold text-on-primary bg-on-primary/20' : 'text-on-primary/90 hover:text-on-primary hover:bg-on-primary/10' }} transition-all duration-200 rounded-sm">
                Zoeken
            </a>
            <a href="{{ route('themas.index') }}" class="block px-4 py-3 text-body-large {{ request()->routeIs('themas.*') ? 'font-semibold text-on-primary bg-on-primary/20' : 'text-on-primary/90 hover:text-on-primary hover:bg-on-primary/10' }} transition-all duration-200 rounded-sm">
                Thema's
            </a>
            <a href="{{ route('dossiers.index') }}" class="block px-4 py-3 text-body-large {{ request()->routeIs('dossiers.*') ? 'font-semibold text-on-primary bg-on-primary/20' : 'text-on-primary/90 hover:text-on-primary hover:bg-on-primary/10' }} transition-all duration-200 rounded-sm">
                Dossiers
            </a>
            <a href="{{ route('reports.index') }}" class="block px-4 py-3 text-body-large {{ request()->routeIs('reports.*') ? 'font-semibold text-on-primary bg-on-primary/20' : 'text-on-primary/90 hover:text-on-primary hover:bg-on-primary/10' }} transition-all duration-200 rounded-sm">
                In cijfers
            </a>
            <a href="{{ route('verwijzingen') }}" class="block px-4 py-3 text-body-large {{ request()->routeIs('verwijzingen') ? 'font-semibold text-on-primary bg-on-primary/20' : 'text-on-primary/90 hover:text-on-primary hover:bg-on-primary/10' }} transition-all duration-200 rounded-sm">
                Verwijzingen
            </a>
            <a href="{{ route('over') }}" class="block px-4 py-3 text-body-large {{ request()->routeIs('over') ? 'font-semibold text-on-primary bg-on-primary/20' : 'text-on-primary/90 hover:text-on-primary hover:bg-on-primary/10' }} transition-all duration-200 rounded-sm">
                Over
            </a>
        </nav>
    </div>
    @hasSection('breadcrumbs')
    <div class="bg-surface/95 backdrop-blur-sm text-on-surface-variant border-t border-outline-variant/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-3">
            <p class="text-label-medium">
                @yield('breadcrumbs')
            </p>
        </div>
    </div>
    @endif
</header>
@endif
