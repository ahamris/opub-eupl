<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Open Overheid')</title>
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    {{-- Font Awesome 6.5.2 --}}
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    
    {{-- Modern Open Overheid Styles --}}
    <link rel="stylesheet" href="{{ asset('css/openoverheid.css') }}">
    
    @stack('styles')
</head>
<body class="bg-surface text-on-surface min-h-screen flex flex-col">
    @if(request()->routeIs('home') || request()->routeIs('zoek'))
    <!-- Modern Sticky Header for Homepage -->
    <header class="sticky top-0 z-50 bg-surface/95 backdrop-blur-sm border-b border-outline-variant/50 shadow-sm" role="banner">
        <nav class="mx-auto max-w-7xl px-6 lg:px-8" aria-label="Top">
            <div class="flex w-full items-center justify-between py-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="text-headline-small font-semibold text-primary hover:text-primary-dark transition-colors duration-200 focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm">
                        Overheid.nl
                    </a>
                    <span class="text-body-medium text-on-surface-variant font-medium">Open overheid</span>
                </div>
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="text-body-large {{ request()->routeIs('home') || request()->routeIs('zoek') ? 'font-semibold text-primary' : 'text-on-surface-variant hover:text-primary' }} transition-colors duration-200 focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-lg px-4 py-2">
                        Home
                    </a>
                    <a href="{{ route('zoeken') }}" class="text-body-large {{ request()->routeIs('zoeken') ? 'font-semibold text-primary' : 'text-on-surface-variant hover:text-primary' }} transition-colors duration-200 focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-lg px-4 py-2">
                        Zoeken
                    </a>
                    <a href="{{ route('verwijzingen') }}" class="text-body-large {{ request()->routeIs('verwijzingen') ? 'font-semibold text-primary' : 'text-on-surface-variant hover:text-primary' }} transition-colors duration-200 focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-lg px-4 py-2">
                        Verwijzingen
                    </a>
                    <a href="{{ route('over') }}" class="text-body-large {{ request()->routeIs('over') ? 'font-semibold text-primary' : 'text-on-surface-variant hover:text-primary' }} transition-colors duration-200 focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-lg px-4 py-2">
                        Over
                    </a>
                </div>
                <!-- Mobile Menu Button -->
                <button type="button" class="md:hidden p-2 text-on-surface-variant hover:text-primary focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-lg" aria-label="Menu">
                    <i class="fas fa-bars text-xl" aria-hidden="true"></i>
                </button>
            </div>
        </nav>
    </header>
    @else
    <!-- Modern Header with Gradient Background for Other Pages -->
    <header class="relative isolate overflow-hidden bg-gradient-to-br from-primary via-primary-dark to-primary" role="banner">
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
                        <a href="{{ route('home') }}" class="text-body-large {{ request()->routeIs('home') || request()->routeIs('zoek') ? 'font-semibold bg-on-primary/10' : 'text-on-primary/90 hover:bg-on-primary/10' }} transition-all duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-lg px-4 py-2">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('zoeken') }}" class="text-body-large {{ request()->routeIs('zoeken') ? 'font-semibold bg-on-primary/10' : 'text-on-primary/90 hover:bg-on-primary/10' }} transition-all duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-lg px-4 py-2">
                            Zoeken
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('verwijzingen') }}" class="text-body-large {{ request()->routeIs('verwijzingen') ? 'font-semibold bg-on-primary/10' : 'text-on-primary/90 hover:bg-on-primary/10' }} transition-all duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-lg px-4 py-2">
                            Verwijzingen
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('over') }}" class="text-body-large {{ request()->routeIs('over') ? 'font-semibold bg-on-primary/10' : 'text-on-primary/90 hover:bg-on-primary/10' }} transition-all duration-200 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-lg px-4 py-2">
                            Over
                        </a>
                    </li>
                </ul>
                <!-- Mobile Menu Button -->
                <button type="button" class="md:hidden p-2 text-on-primary hover:bg-on-primary/10 focus:outline-2 focus:outline-on-primary focus:outline-offset-2 rounded-lg" aria-label="Menu">
                    <i class="fas fa-bars text-xl" aria-hidden="true"></i>
                </button>
            </div>
        </nav>
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
    
    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Modern Footer -->
    <footer class="bg-gradient-to-br from-neutral-900 via-neutral-800 to-neutral-900 text-white border-t border-neutral-700 mt-auto" aria-labelledby="footer-heading">
        <h2 id="footer-heading" class="sr-only">Footer</h2>
        <div class="mx-auto max-w-7xl px-6 pb-12 pt-16 sm:pt-20 lg:px-8 lg:pt-24">
            <div class="xl:grid xl:grid-cols-3 xl:gap-12">
                <!-- Links Section -->
                <div class="grid grid-cols-2 gap-8 xl:col-span-2">
                    <div class="md:grid md:grid-cols-2 md:gap-8">
                        <div>
                            <h3 class="text-sm font-semibold leading-6 text-white mb-4">Over deze website</h3>
                            <ul role="list" class="space-y-3">
                                <li>
                                    <a href="{{ route('over') }}" class="text-sm leading-6 text-neutral-300 hover:text-white transition-colors duration-200 focus:outline-2 focus:outline-white focus:outline-offset-2 rounded-sm">
                                        Over open.overheid.nl
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('verwijzingen') }}" class="text-sm leading-6 text-neutral-300 hover:text-white transition-colors duration-200 focus:outline-2 focus:outline-white focus:outline-offset-2 rounded-sm">
                                        Verwijzingen
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="mt-8 md:mt-0">
                            <h3 class="text-sm font-semibold leading-6 text-white mb-4">Recht & Privacy</h3>
                            <ul role="list" class="space-y-3">
                                <li>
                                    <a href="#" class="text-sm leading-6 text-neutral-300 hover:text-white transition-colors duration-200 focus:outline-2 focus:outline-white focus:outline-offset-2 rounded-sm">
                                        Privacy & Cookies
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-sm leading-6 text-neutral-300 hover:text-white transition-colors duration-200 focus:outline-2 focus:outline-white focus:outline-offset-2 rounded-sm">
                                        Toegankelijkheid
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="md:grid md:grid-cols-2 md:gap-8">
                        <div>
                            <h3 class="text-sm font-semibold leading-6 text-white mb-4">Externe links</h3>
                            <ul role="list" class="space-y-3">
                                <li>
                                    <a href="https://www.overheid.nl" target="_blank" rel="noopener noreferrer" class="text-sm leading-6 text-neutral-300 hover:text-white transition-colors duration-200 focus:outline-2 focus:outline-white focus:outline-offset-2 rounded-sm inline-flex items-center gap-1.5">
                                        Overheid.nl
                                        <i class="fas fa-external-link-alt text-xs" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.woo-index.nl" target="_blank" rel="noopener noreferrer" class="text-sm leading-6 text-neutral-300 hover:text-white transition-colors duration-200 focus:outline-2 focus:outline-white focus:outline-offset-2 rounded-sm inline-flex items-center gap-1.5">
                                        Woo-index
                                        <i class="fas fa-external-link-alt text-xs" aria-hidden="true"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Mission Statement Column -->
                <div class="mt-12 xl:mt-0">
                    <h3 class="text-sm font-semibold leading-6 text-white mb-4">Onze missie</h3>
                    <p class="text-sm leading-6 text-neutral-300">
                        Open.overheid.nl bundelt actief openbaar gemaakte overheidsdocumenten op één centrale plek, 
                        zodat burgers en professionals deze eenvoudig kunnen vinden en raadplegen. Wij werken op basis 
                        van de Wet open overheid (Woo) om transparantie en toegankelijkheid van overheidsinformatie te bevorderen.
                    </p>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-neutral-700">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-xs leading-5 text-neutral-400">
                        &copy; {{ date('Y') }} Open Overheid. Alle rechten voorbehouden.
                    </p>
                    <div class="flex items-center gap-6">
                        <span class="text-xs text-neutral-400">Digitaliseringspartner voor slimme ICT-oplossingen</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    @stack('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js"></script>
</body>
</html>

