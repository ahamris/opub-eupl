<nav aria-label="Dashboard Navigation" class="flex flex-1 flex-col">
    <ul role="list" class="-mx-2 space-y-1">
        <!-- Home -->
        <li>
            <a href="{{ route('user.dashboard') }}" class="group flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold {{ request()->routeIs('user.dashboard') && !request()->routeIs('user.berichtenbox*') && !request()->routeIs('user.subscriptions') ? 'bg-[var(--color-primary)] text-white' : 'text-gray-700 hover:bg-white hover:text-[var(--color-primary)]' }} transition-colors">
                <i class="fas fa-home w-5 text-center {{ request()->routeIs('user.dashboard') && !request()->routeIs('user.berichtenbox*') && !request()->routeIs('user.subscriptions') ? 'text-white' : 'text-gray-400 group-hover:text-[var(--color-primary)]' }}"></i>
                Home
            </a>
        </li>

        <!-- Berichten -->
        <li>
            <a href="{{ route('user.berichtenbox') }}" class="group flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold {{ request()->routeIs('user.berichtenbox*') || request()->routeIs('user.bericht.show') ? 'bg-[var(--color-primary)] text-white' : 'text-gray-700 hover:bg-white hover:text-[var(--color-primary)]' }} transition-colors">
                <i class="fas fa-envelope w-5 text-center {{ request()->routeIs('user.berichtenbox*') || request()->routeIs('user.bericht.show') ? 'text-white' : 'text-gray-400 group-hover:text-[var(--color-primary)]' }}"></i>
                Berichten
                <span class="ml-auto {{ request()->routeIs('user.berichtenbox*') || request()->routeIs('user.bericht.show') ? 'bg-white/20 text-white' : 'bg-[var(--color-primary)]/10 text-[var(--color-primary)]' }} text-xs font-medium px-2 py-0.5 rounded-full">1</span>
            </a>
        </li>

        <!-- Mijn Abonnementen -->
        <li>
            <a href="{{ route('user.subscriptions') }}" class="group flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold {{ request()->routeIs('user.subscriptions') ? 'bg-[var(--color-primary)] text-white' : 'text-gray-700 hover:bg-white hover:text-[var(--color-primary)]' }} transition-colors">
                <i class="fas fa-bell w-5 text-center {{ request()->routeIs('user.subscriptions') ? 'text-white' : 'text-gray-400 group-hover:text-[var(--color-primary)]' }}"></i>
                Mijn Abonnementen
            </a>
        </li>
    </ul>
</nav>
