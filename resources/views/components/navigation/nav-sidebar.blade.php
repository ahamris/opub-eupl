@props([
    'title' => 'Admin Panel',
    'logo' => 'cube',
    'id' => null,
    'navClass' => null,
])

<div class="flex flex-col h-screen">
    <aside 
        x-data
        @if($id) id="{{ $id }}" @endif
        class="fixed inset-y-0 left-0 z-30 w-64 transform transition-transform duration-300 ease-in-out border-r shadow-lg lg:translate-x-0 lg:static lg:inset-0 flex flex-col h-full"
        :class="{ 'translate-x-0': $store.sidebar.isOpen, '-translate-x-full': !$store.sidebar.isOpen }"
    >
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-16 px-6 border-b shrink-0">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-[var(--color-accent)] dark:bg-[var(--color-accent)] rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-{{ $logo }} text-white text-sm"></i>
                </div>
                <span class="ml-3 text-lg font-semibold text-zinc-900 dark:text-white">{{ $title }}</span>
            </div>
            <button 
                @click="$store.sidebar.toggle()" 
                class="text-zinc-700 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-zinc-100 lg:hidden"
            >
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="mt-3 px-3 flex-1 overflow-y-auto {{ $navClass ?? 'min-h-0' }}" aria-label="Main navigation">
            <ul class="space-y-1 list-none">
                {{ $slot }}
            </ul>
        </nav>
    </aside>

    <!-- Mobile overlay -->
    <div 
        x-data 
        x-show="$store.sidebar.isOpen" 
        @click="$store.sidebar.isOpen = false" 
        class="fixed inset-0 z-20 bg-black/50 lg:hidden"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-50"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-50"
        x-transition:leave-end="opacity-0"
        style="display: none;"
    ></div>
</div>
