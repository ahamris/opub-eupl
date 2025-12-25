@php
    $headerMenu = \App\Models\HeaderMenuItem::getMenuTree();
@endphp

<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/60" role="banner" x-data="{ mobileMenuOpen: false }">
    <nav aria-label="Global" class="mx-auto flex max-w-7xl items-center justify-between px-6 py-3.5 lg:px-8">
        <!-- Brand -->
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="-m-1.5 p-1.5 flex items-center gap-2.5">
                <span class="sr-only">Open overheid</span>
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-md bg-[var(--color-primary)] text-white font-semibold text-sm tracking-tight">
                    OO
                </span>
                <div class="hidden sm:flex flex-col">
                    <span class="text-sm font-semibold text-[var(--color-on-surface)] tracking-tight">Overheid.nl</span>
                </div>
            </a>
        </div>

        <!-- Mobile menu button -->
        <div class="flex lg:hidden">
            <button
                type="button"
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)] transition-colors"
            >
                <span class="sr-only">Open hoofdmenu</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6">
                    <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>

        <!-- Desktop navigation -->
        <div class="hidden lg:flex lg:items-center lg:gap-x-1">
            @foreach($headerMenu as $item)
                @if($item->isDropdown() && $item->hasVisibleChildren())
                    {{-- Dropdown Menu --}}
                    @php
                        $dropdownId = 'desktop-menu-' . $item->slug;
                        $isActive = $item->isCurrentlyActive() || $item->hasActiveChild();
                    @endphp
                    <div class="relative">
                        <button
                            popovertarget="{{ $dropdownId }}"
                            class="nav-link px-3 py-2 text-sm font-medium inline-flex items-center gap-x-1 {{ $isActive ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}"
                        >
                            <span>{{ $item->label }}</span>
                            @if($item->badge_text)
                                <span class="inline-flex items-center rounded-full bg-{{ $item->badge_color ?? 'purple' }}-100 px-1.5 py-0.5 text-[10px] font-medium text-{{ $item->badge_color ?? 'purple' }}-700">{{ $item->badge_text }}</span>
                            @endif
                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5">
                                <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        </button>

                        <el-popover id="{{ $dropdownId }}" anchor="bottom" popover class="w-screen max-w-max overflow-visible bg-transparent px-4 transition transition-discrete [--anchor-gap:--spacing(5)] backdrop:bg-transparent open:flex data-closed:translate-y-1 data-closed:opacity-0 data-enter:duration-200 data-enter:ease-out data-leave:duration-150 data-leave:ease-in">
                            <div class="w-screen max-w-md flex-auto overflow-hidden rounded-md bg-white text-sm/6 shadow-lg ring-1 ring-gray-900/5">
                                <div class="p-4">
                                    @foreach($item->activeChildren as $child)
                                        @if($child->is_disabled)
                                            {{-- Disabled Item --}}
                                            <div class="group relative flex gap-x-6 rounded-lg p-4 opacity-60 cursor-not-allowed">
                                                @if($child->icon)
                                                <div class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50">
                                                    <i class="fas fa-{{ $child->icon }} text-lg text-gray-400"></i>
                                                </div>
                                                @endif
                                                <div>
                                                    <span class="font-semibold text-gray-500 inline-flex items-center gap-2">
                                                        {{ $child->label }}
                                                        @if($child->badge_text)
                                                            <span class="inline-flex items-center rounded-full bg-{{ $child->badge_color ?? 'purple' }}-100 px-2 py-0.5 text-xs font-medium text-{{ $child->badge_color ?? 'purple' }}-700">{{ $child->badge_text }}</span>
                                                        @endif
                                                    </span>
                                                    @if($child->description)
                                                        <p class="mt-1 text-sm text-gray-400">{{ $child->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            {{-- Normal Item --}}
                                            <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50">
                                                @if($child->icon)
                                                <div class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                                                    <i class="fas fa-{{ $child->icon }} text-lg text-gray-600 group-hover:text-[var(--color-primary-dark)]"></i>
                                                </div>
                                                @endif
                                                <div>
                                                    <a href="{{ $child->resolved_url ?? '#' }}" class="font-semibold text-gray-900" @if($child->target) target="{{ $child->target }}" @endif>
                                                        {{ $child->label }}
                                                        @if($child->badge_text)
                                                            <span class="inline-flex items-center rounded-full bg-{{ $child->badge_color ?? 'purple' }}-100 px-2 py-0.5 text-xs font-medium text-{{ $child->badge_color ?? 'purple' }}-700 ml-2">{{ $child->badge_text }}</span>
                                                        @endif
                                                        <span class="absolute inset-0"></span>
                                                    </a>
                                                    @if($child->description)
                                                        <p class="mt-1 text-sm text-gray-600">{{ $child->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </el-popover>
                    </div>
                @else
                    {{-- Simple Link --}}
                    @if($item->is_disabled)
                        <span class="nav-link px-3 py-2 text-sm font-medium text-gray-400 cursor-not-allowed inline-flex items-center gap-1.5">
                            {{ $item->label }}
                            @if($item->badge_text)
                                <span class="inline-flex items-center rounded-full bg-{{ $item->badge_color ?? 'purple' }}-100 px-1.5 py-0.5 text-[10px] font-medium text-{{ $item->badge_color ?? 'purple' }}-700">{{ $item->badge_text }}</span>
                            @endif
                        </span>
                    @else
                        <a
                            href="{{ $item->resolved_url ?? '#' }}"
                            class="nav-link px-3 py-2 text-sm font-medium {{ $item->isCurrentlyActive() ? 'nav-link-active text-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}"
                            @if($item->target) target="{{ $item->target }}" @endif
                        >
                            {{ $item->label }}
                            @if($item->badge_text)
                                <span class="inline-flex items-center rounded-full bg-{{ $item->badge_color ?? 'purple' }}-100 px-1.5 py-0.5 text-[10px] font-medium text-{{ $item->badge_color ?? 'purple' }}-700 ml-1">{{ $item->badge_text }}</span>
                            @endif
                        </a>
                    @endif
                @endif
            @endforeach

            <!-- Login/User Button -->
            <div class="ml-4 pl-4 border-l border-slate-200">
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button 
                            @click="open = !open" 
                            class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)] transition-colors"
                        >
                            <span class="w-7 h-7 rounded-full bg-[var(--color-primary)] flex items-center justify-center text-white text-xs font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden xl:inline">{{ Str::limit(auth()->user()->name, 15) }}</span>
                            <svg viewBox="0 0 20 20" fill="currentColor" class="size-4">
                                <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        </button>
                        <div 
                            x-show="open" 
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black/5 py-1 z-50"
                        >
                            <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-tachometer-alt mr-2 text-gray-400"></i> Dashboard
                            </a>
                            <form method="POST" action="{{ route('user.logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-sign-out-alt mr-2 text-gray-400"></i> Uitloggen
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a 
                        href="{{ route('user.login') }}" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-[var(--color-primary)] rounded-md hover:bg-[var(--color-primary-dark)] transition-colors"
                    >
                        <i class="fas fa-sign-in-alt text-[10px]"></i>
                        Inloggen
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Mobile menu -->
    <div
        x-show="mobileMenuOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        @click.away="mobileMenuOpen = false"
        class="lg:hidden border-t border-slate-200/60 bg-white/98 backdrop-blur-md"
    >
        <nav class="mx-auto max-w-7xl px-6 py-3 space-y-0.5" aria-label="Mobiele navigatie">
            @foreach($headerMenu as $item)
                @if($item->isDropdown() && $item->hasVisibleChildren())
                    {{-- Dropdown Section Header --}}
                    <div class="pt-2 pb-1">
                        <span class="block px-3 py-1 text-xs font-semibold uppercase tracking-wider text-[var(--color-on-surface-variant)]">{{ $item->label }}</span>
                    </div>

                    {{-- Dropdown Children --}}
                    @foreach($item->activeChildren as $child)
                        @if($child->is_disabled)
                            <div class="block px-3 py-2.5 text-base font-medium pl-6 text-gray-400 cursor-not-allowed opacity-60 flex items-center gap-2">
                                {{ $child->label }}
                                @if($child->badge_text)
                                    <span class="inline-flex items-center rounded-full bg-{{ $child->badge_color ?? 'purple' }}-100 px-2 py-0.5 text-xs font-medium text-{{ $child->badge_color ?? 'purple' }}-700">{{ $child->badge_text }}</span>
                                @endif
                            </div>
                        @else
                            <a
                                href="{{ $child->resolved_url ?? '#' }}"
                                class="block px-3 py-2.5 text-base font-medium pl-6 {{ $child->isCurrentlyActive() ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}"
                                @if($child->target) target="{{ $child->target }}" @endif
                            >
                                {{ $child->label }}
                                @if($child->badge_text)
                                    <span class="inline-flex items-center rounded-full bg-{{ $child->badge_color ?? 'purple' }}-100 px-2 py-0.5 text-xs font-medium text-{{ $child->badge_color ?? 'purple' }}-700 ml-2">{{ $child->badge_text }}</span>
                                @endif
                            </a>
                        @endif
                    @endforeach
                @else
                    {{-- Simple Link --}}
                    @if($item->is_disabled)
                        <div class="block px-3 py-2.5 text-base font-medium text-gray-400 cursor-not-allowed opacity-60 flex items-center gap-2">
                            {{ $item->label }}
                            @if($item->badge_text)
                                <span class="inline-flex items-center rounded-full bg-{{ $item->badge_color ?? 'purple' }}-100 px-2 py-0.5 text-xs font-medium text-{{ $item->badge_color ?? 'purple' }}-700">{{ $item->badge_text }}</span>
                            @endif
                        </div>
                    @else
                        <a
                            href="{{ $item->resolved_url ?? '#' }}"
                            class="block px-3 py-2.5 text-base font-medium {{ $item->isCurrentlyActive() ? 'text-[var(--color-primary-dark)] border-l-2 border-[var(--color-primary-dark)]' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-on-surface)]' }}"
                            @if($item->target) target="{{ $item->target }}" @endif
                        >
                            {{ $item->label }}
                            @if($item->badge_text)
                                <span class="inline-flex items-center rounded-full bg-{{ $item->badge_color ?? 'purple' }}-100 px-2 py-0.5 text-xs font-medium text-{{ $item->badge_color ?? 'purple' }}-700 ml-2">{{ $item->badge_text }}</span>
                            @endif
                        </a>
                    @endif
                @endif

                {{-- Add divider after dropdowns --}}
                @if($item->isDropdown() && !$loop->last)
                    <div class="border-t border-slate-200/60 my-2"></div>
                @endif
            @endforeach
        </nav>
    </div>
</header>

<style>
    /* Navigation link underline animation */
    .nav-link {
        position: relative;
        transition: color 0.2s ease;
    }
    
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background-color: var(--color-primary-dark);
        transition: width 0.3s ease, left 0.3s ease;
    }
    
    .nav-link:hover::after {
        width: 100%;
        left: 0;
    }
    
    /* Active state - always show underline */
    .nav-link-active::after {
        width: 100%;
        left: 0;
    }
</style>
