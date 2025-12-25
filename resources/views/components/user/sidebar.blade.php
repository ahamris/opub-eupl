<nav aria-label="Dashboard Navigation" class="flex flex-1 flex-col">
    <ul role="list" class="-mx-2 space-y-1">
        <!-- Dashboard Home -->
        <li>
            <a href="{{ route('user.dashboard') }}" class="group flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold {{ request()->routeIs('user.dashboard') && !request()->routeIs('user.subscriptions') ? 'bg-white text-[var(--color-primary)]' : 'text-gray-700 hover:bg-white hover:text-[var(--color-primary)]' }} transition-colors">
                <i class="fas fa-home w-5 text-center {{ request()->routeIs('user.dashboard') && !request()->routeIs('user.subscriptions') ? 'text-[var(--color-primary)]' : 'text-gray-400 group-hover:text-[var(--color-primary)]' }}"></i>
                Overzicht
            </a>
        </li>

        <!-- Subscriptions -->
        <li>
            <a href="{{ route('user.subscriptions') }}" class="group flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold {{ request()->routeIs('user.subscriptions') ? 'bg-white text-[var(--color-primary)]' : 'text-gray-700 hover:bg-white hover:text-[var(--color-primary)]' }} transition-colors">
                <i class="fas fa-bell w-5 text-center {{ request()->routeIs('user.subscriptions') ? 'text-[var(--color-primary)]' : 'text-gray-400 group-hover:text-[var(--color-primary)]' }}"></i>
                Mijn Abonnementen
                <span class="ml-auto bg-[var(--color-primary)]/10 text-[var(--color-primary)] text-xs font-medium px-2 py-0.5 rounded-full">3</span>
            </a>
        </li>
</nav>
