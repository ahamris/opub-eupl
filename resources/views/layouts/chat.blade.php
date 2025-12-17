<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>@yield('title', 'Chat - Open Overheid')</title>
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    {{-- Font Awesome 6.5.2 --}}
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    
    <style>
        /* Chat layout specific styles */
        html, body {
            height: 100%;
            overflow: hidden;
        }
        .chat-layout {
            display: flex;
            flex-direction: column;
            height: 100vh;
            height: 100dvh; /* Dynamic viewport height for mobile */
        }
        .chat-header {
            flex-shrink: 0;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .chat-input {
            flex-shrink: 0;
        }
        /* Hide scrollbar but keep functionality */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-[var(--color-surface)] text-[var(--color-on-surface)] font-sans">
    <div class="chat-layout">
        <!-- Header -->
        <div class="chat-header">
            @include('layouts.includes.header')
        </div>

        <!-- Main Chat Content -->
        @yield('content')
    </div>
    
    @stack('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js"></script>
</body>
</html>
