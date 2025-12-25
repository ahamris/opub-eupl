@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Dashboard Panel -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
            <div class="flex min-h-[calc(100vh-12rem)]">
                <!-- Left Sidebar -->
                <aside class="hidden lg:block w-64 shrink-0 border-r border-gray-200 bg-gray-50 px-6 py-6">
                    <x-user.sidebar />
                </aside>

                <!-- Main Content Area -->
                <div class="flex-1 flex flex-col min-w-0">
                    <div class="flex-1 flex overflow-hidden">
                        <!-- Main Content -->
                        <main class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-8">
                            @yield('dashboard-content')
                        </main>

                        <!-- Right Info Panel -->
                        @hasSection('info-panel')
                        <aside class="hidden xl:block w-80 shrink-0 border-l border-gray-200 bg-gray-50 px-6 py-6 overflow-y-auto">
                            @yield('info-panel')
                        </aside>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Sidebar Toggle -->
<div class="lg:hidden fixed bottom-4 left-4 z-50" x-data="{ open: false }">
    <button 
        @click="open = !open" 
        class="bg-[var(--color-primary)] text-white p-3 rounded-full shadow-lg hover:bg-[var(--color-primary-dark)] transition-colors"
    >
        <i class="fas fa-bars text-lg"></i>
    </button>
    
    <!-- Mobile Sidebar Overlay -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 bg-black/50 z-40"
    ></div>
    
    <!-- Mobile Sidebar Panel -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 w-64 bg-white shadow-xl z-50 p-6"
    >
        <button @click="open = false" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <i class="fas fa-times text-xl"></i>
        </button>
        <div class="mt-8">
            <x-user.sidebar />
        </div>
    </div>
</div>
@endsection
