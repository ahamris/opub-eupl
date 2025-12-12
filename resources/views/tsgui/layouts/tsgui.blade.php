<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Typesense GUI') - Typesense</title>
    
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
<body class="bg-white text-gray-900 min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 flex flex-col" aria-label="Sidebar">
        <div class="p-6 border-b border-gray-200">
            <a href="{{ route('tsgui.index') }}" class="flex items-center gap-2 text-purple-600 hover:text-purple-700 font-semibold text-lg">
                <i class="fas fa-search"></i>
                <span>Typesense GUI</span>
            </a>
        </div>
        
        <nav class="flex-1 overflow-y-auto px-3 py-4">
            <!-- Collections Section -->
            <div class="mb-8">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3">Collections</label>
                @php
                    try {
                        $collections = app(\App\Services\Typesense\TypesenseGuiService::class)->listCollections();
                    } catch (\Exception $e) {
                        $collections = [];
                    }
                @endphp
                <select 
                    id="collection-select"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white"
                    onchange="if(this.value) window.location.href='{{ route('tsgui.collection', ['collection' => '__COLLECTION__']) }}'.replace('__COLLECTION__', this.value)"
                >
                    <option value="">Select a collection...</option>
                    @foreach($collections as $collection)
                        <option value="{{ $collection['name'] }}" {{ request()->route('collection') === $collection['name'] ? 'selected' : '' }}>
                            {{ $collection['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            @if(request()->route('collection'))
                <!-- Collection Navigation -->
                <div class="space-y-1">
                    <a href="{{ route('tsgui.collection', ['collection' => request()->route('collection')]) }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('tsgui.collection') && !request()->routeIs('tsgui.search') ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-cog w-5 text-center"></i>
                        <span>Overview</span>
                    </a>
                    <a href="{{ route('tsgui.search', ['collection' => request()->route('collection'), 'q' => '*']) }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('tsgui.search') ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-search w-5 text-center"></i>
                        <span>Search</span>
                    </a>
                    <a href="{{ route('tsgui.collection', ['collection' => request()->route('collection')]) }}#schema" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md transition-colors text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-database w-5 text-center"></i>
                        <span>Schema</span>
                    </a>
                </div>
            @else
                <!-- Main Navigation -->
                <div class="space-y-1">
                    <a href="{{ route('tsgui.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('tsgui.index') ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-th-large w-5 text-center"></i>
                        <span>Collections</span>
                    </a>
                </div>
            @endif
        </nav>
        
        <div class="p-4 border-t border-gray-200">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Site</span>
            </a>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden bg-gray-50">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 shadow-sm">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">@yield('page-title', 'Typesense')</h1>
                        @hasSection('page-description')
                            <p class="text-sm text-gray-500 mt-1">@yield('page-description')</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                    <span class="text-purple-600 text-sm font-medium">{{ substr(auth()->user()->name ?? auth()->user()->email, 0, 1) }}</span>
                                </div>
                                <span class="text-sm text-gray-700">{{ auth()->user()->name ?? auth()->user()->email }}</span>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Flash Messages -->
        @if(session('success') || session('error'))
            <div class="mx-6 mt-4">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md flex items-center gap-2 shadow-sm">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md flex items-center gap-2 shadow-sm">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
            </div>
        @endif
        
        <!-- Content -->
        <div class="flex-1 overflow-y-auto">
            <div class="py-6">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>
    
    @stack('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js"></script>
</body>
</html>
