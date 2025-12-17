<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Login' }} - {{ config('app.name', 'Laravel') }}</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    
    {{-- FontAwesome Pro Links --}}
    <link href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css" rel="stylesheet"/>
    <link href="https://site-assets.fontawesome.com/releases/v6.7.2/css/brands.css" rel="stylesheet"/>
    
    {{-- Dynamic Theme Variables - Must be before @vite to override default values --}}
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
    @livewireStyles
    @stack('styles')
    
    {{-- FOUC Prevention --}}
    <script src="{{ asset('js/fouc-prevention.js') }}"></script>
</head>
<body 
    class="h-full {{ $themeAccentClass }} transition-colors font-geist text-zinc-900 dark:text-zinc-100 bg-zinc-50 dark:bg-zinc-900" 
    x-data 
    x-init="$store.darkMode.init()"
>
    {{ $slot }}
    
    @livewireScripts
    @stack('scripts')
</body>
</html>

