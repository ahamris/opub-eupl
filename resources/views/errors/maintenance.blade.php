<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Maintenance Mode - {{ config('app.name', 'Laravel') }}</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    
    {{-- Styles --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    @stack('styles')
</head>
<body class="bg-[var(--color-surface)] text-[var(--color-on-surface)] min-h-screen flex flex-col font-sans">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full text-center">
            {{-- Maintenance Icon --}}
            <div class="mb-8 flex justify-center">
                <div class="w-24 h-24 rounded-full bg-[var(--color-primary)]/10 flex items-center justify-center">
                    <i class="fa-solid fa-tools text-[var(--color-primary)] text-5xl"></i>
                </div>
            </div>

            {{-- Maintenance Code --}}
            <h1 class="text-9xl font-bold text-[var(--color-primary)] mb-4">503</h1>

            {{-- Maintenance Message --}}
            <h2 class="text-2xl font-semibold text-[var(--color-on-surface)] mb-3">Maintenance Mode</h2>
            <p class="text-[var(--color-on-surface-variant)] mb-8">
                {!! nl2br(e(get_setting('maintenance_message', 'We\'re currently performing scheduled maintenance to improve your experience. We\'ll be back shortly.'))) !!}
            </p>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button 
                    onclick="window.location.reload()" 
                    class="inline-flex items-center justify-center px-6 py-3 border border-[var(--color-outline-variant)] rounded-lg bg-[var(--color-surface)] text-[var(--color-on-surface)] hover:bg-[var(--color-surface-variant)] transition-colors"
                >
                    <i class="fa-solid fa-arrow-rotate-right mr-2"></i>
                    Refresh Page
                </button>
            </div>
        </div>
    </div>
    
    @stack('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js"></script>
</body>
</html>
