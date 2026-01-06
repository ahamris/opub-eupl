<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }} - {{ config('app.name', 'Laravel') }}</title>
    
    {{-- FOUC Prevention - Inline script must run immediately before any rendering --}}
    <script>
        (function () {
            try {
                // Check localStorage for theme preference
                const stored = localStorage.getItem("theme");
                const prefersDark = window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches;
                
                // Determine if dark mode should be active
                let isDark = false;
                if (stored === "dark") {
                    isDark = true;
                } else if (stored === "light") {
                    isDark = false;
                } else {
                    // System preference (no stored value)
                    isDark = prefersDark;
                }
                
                // Apply dark class immediately before any content renders
                if (isDark) {
                    document.documentElement.classList.add("dark");
                } else {
                    document.documentElement.classList.remove("dark");
                }
            } catch (e) {
                // Fallback: check system preference only
                if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
                    document.documentElement.classList.add("dark");
                }
            }
        })();
    </script>
    
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
    @vite(['resources/css/admin.css', 'resources/js/admin.js', 'resources/js/tiptap.js'])
    @livewireStyles
    @stack('styles')
    
    {{-- Flash Messages Data --}}
    @php
        use App\Helpers\FlashHelper;
        $flashMessages = FlashHelper::getAll();
    @endphp
    @if(!empty($flashMessages))
        <script>
            window.flashMessages = @json($flashMessages);
        </script>
    @endif
</head>
<body
    class="min-h-screen {{ $themeAccentClass }} transition-colors font-geist text-zinc-900 dark:text-zinc-100 text-sm leading-5 tracking-[0.25px] bg-zinc-50 dark:bg-zinc-900"
    x-data
    x-init="$store.darkMode.init()"
    @notify.window="toastManager.show($event.detail.type || 'info', $event.detail.message || 'Notification', { title: $event.detail.title || null, icon: $event.detail.icon || null })"
>
    
    <div class="flex h-screen">
        <!-- Sidebar -->
        @livewire('admin.sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <x-partials.header />

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto">
                <div class="px-6 py-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    
    {{-- Search Component --}}
    @livewire('admin.search')
    
    @livewireScripts
    @stack('scripts')
</body>
</html>
