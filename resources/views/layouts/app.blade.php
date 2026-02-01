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
    
    {{-- Default Open Graph Meta Tags (can be overridden by @push('styles')) --}}
    @php
        $defaultOgTitle = get_setting('og_title') ?: get_setting('site_title', 'Open Overheid');
        $defaultOgDescription = get_setting('og_description') ?: get_setting('site_description', '');
        $defaultOgImage = get_setting('og_image') ? asset('storage/' . get_setting('og_image')) : null;
        $defaultOgUrl = url()->current();
    @endphp
    
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $defaultOgTitle }}">
    @if($defaultOgDescription)
        <meta property="og:description" content="{{ $defaultOgDescription }}">
    @endif
    <meta property="og:url" content="{{ $defaultOgUrl }}">
    @if($defaultOgImage)
        <meta property="og:image" content="{{ $defaultOgImage }}">
    @endif
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $defaultOgTitle }}">
    @if($defaultOgDescription)
        <meta name="twitter:description" content="{{ $defaultOgDescription }}">
    @endif
    @if($defaultOgImage)
        <meta name="twitter:image" content="{{ $defaultOgImage }}">
    @endif
    
    {{-- Google Analytics --}}
    @php
        $gaId = get_setting('google_analytics_id');
    @endphp
    @if($gaId)
        @if(str_starts_with($gaId, 'G-'))
            {{-- Google Analytics 4 (GA4) --}}
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ $gaId }}');
            </script>
        @elseif(str_starts_with($gaId, 'UA-'))
            {{-- Universal Analytics (Legacy) --}}
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ $gaId }}');
            </script>
        @endif
    @endif
    
    @stack('styles')
</head>
<body class="bg-[var(--color-surface)] text-[var(--color-on-surface)] min-h-screen flex flex-col font-sans">
    <x-admin-banner />
    @include('layouts.includes.header')

    {{-- Global breadcrumb component (if $breadcrumbs is set) --}}
    {{-- Hide breadcrumb on pages that have header sections with breadcrumbs or on homepage --}}
    @if(!empty($breadcrumbs ?? []) && !request()->routeIs('home') && !request()->routeIs('zoek') && !request()->routeIs('zoeken') && !request()->routeIs('themas.index') && !request()->routeIs('reports.index') && !request()->routeIs('verwijzingen') && !request()->routeIs('over') && !request()->routeIs('dossiers.index') && !request()->routeIs('contact') && !request()->is('open-overheid/documents/*') && !request()->routeIs('blog.index') && !request()->routeIs('blog.show') && !request()->routeIs('page.show'))
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
    
    {{-- GDPR Cookie Banner --}}
    <x-front.gdpr-banner />
    
    @stack('scripts')

    <script data-host="https://analytics.code-labs.nl" data-dnt="false" src="https://analytics.code-labs.nl/js/script.js" id="ZwSg9rf6GA" async defer></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js"></script>
</body>
</html>
