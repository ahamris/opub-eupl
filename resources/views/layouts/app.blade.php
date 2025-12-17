<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title x-data="{ 
        baseTitle: '@yield('title', 'Open Overheid')',
        documentCount: {{ isset($documentCount) ? $documentCount : 0 }},
        get displayTitle() {
            if (this.documentCount > 0 && this.baseTitle.includes('Zoek overheidsdocumenten')) {
                return this.baseTitle.replace(/\s*\(\d+[.,]\d+\s+documenten\)/, '') + ' (' + this.formatNumber(this.documentCount) + ' documenten)';
            }
            return this.baseTitle;
        },
        formatNumber(num) {
            return new Intl.NumberFormat('nl-NL').format(num);
        }
    }" x-text="displayTitle">@yield('title', 'Open Overheid')</title>
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    {{-- Font Awesome 6.5.2 --}}
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    
    
    @stack('styles')
</head>
<body class="bg-[var(--color-surface)] text-[var(--color-on-surface)] min-h-screen flex flex-col font-sans">
    <x-admin-banner />
    @include('layouts.includes.header')

    {{-- Global breadcrumb component (if $breadcrumbs is set) --}}
    {{-- Hide breadcrumb on pages that have header sections with breadcrumbs or on homepage --}}
    @if(!empty($breadcrumbs ?? []) && !request()->routeIs('home') && !request()->routeIs('zoek') && !request()->routeIs('zoeken') && !request()->routeIs('themas.index') && !request()->routeIs('reports.index') && !request()->routeIs('verwijzingen') && !request()->routeIs('over') && !request()->routeIs('dossiers.index') && !request()->routeIs('contact') && !request()->is('open-overheid/documents/*'))
        <div class="bg-[var(--color-surface)] border-b border-[var(--color-outline-variant)]">
            <div class="max-w-7xl mx-auto px-8 py-3">
                <x-breadcrumbs :items="$breadcrumbs" />
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    @if (! View::hasSection('hide_footer'))
        @include('layouts.includes.footer')
    @endif
    
    @stack('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js"></script>
</body>
</html>
