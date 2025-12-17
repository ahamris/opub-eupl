<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>419 - Page Expired - {{ config('app.name', 'Laravel') }}</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    
    {{-- FontAwesome Pro Links --}}
    <link href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css" rel="stylesheet"/>
    <link href="https://site-assets.fontawesome.com/releases/v6.7.2/css/brands.css" rel="stylesheet"/>
    
    {{-- Dynamic Theme Variables --}}
    @php
        use App\Helpers\ThemeHelper;
        use App\Models\Admin\AdminThemeSetting;
        echo ThemeHelper::getThemeCss();
        $themeSettings = AdminThemeSetting::getSettings();
        $accentColor = $themeSettings->accent_color;
        $themeAccentClass = 'theme-accent-' . $accentColor;
    @endphp
    
    {{-- Styles / Scripts --}}
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    
    {{-- FOUC Prevention --}}
    <script src="{{ asset('js/fouc-prevention.js') }}"></script>
</head>
<body class="min-h-screen {{ $themeAccentClass }} transition-colors font-geist text-zinc-900 dark:text-zinc-100 text-sm leading-5 tracking-[0.25px] bg-zinc-50 dark:bg-zinc-900" x-data x-init="$store.darkMode.init()">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full text-center">
            {{-- Error Icon --}}
            <div class="mb-8 flex justify-center">
                <div class="w-24 h-24 rounded-full bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                    <i class="fa-solid fa-clock text-yellow-600 dark:text-yellow-400 text-5xl"></i>
                </div>
            </div>

            {{-- Error Code --}}
            <h1 class="text-9xl font-bold text-yellow-600 dark:text-yellow-400 mb-4">419</h1>

            {{-- Error Message --}}
            <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Page Expired</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-8">
                Your session has expired. Please refresh the page and try again.
            </p>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-6 py-3 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                    <i class="fa-solid fa-arrow-rotate-right mr-2"></i>
                    Refresh Page
                </button>
                <a href="{{ route('admin.home') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-[var(--color-accent)] text-[var(--color-accent-foreground)] hover:opacity-90 transition-opacity">
                    <i class="fa-solid fa-home mr-2"></i>
                    Go Home
                </a>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>

