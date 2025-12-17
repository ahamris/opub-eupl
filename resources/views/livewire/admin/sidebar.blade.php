<x-navigation.nav-sidebar 
    :title="$menu->name ?? 'Admin Panel'" 
    logo="cube" 
    id="admin-sidebar"
    nav-class="min-h-0"
>
    @forelse($menuItems as $item)
        <x-partials.sidebar-menu-item :item="$item" />
    @empty
        <x-navigation.nav-section title="Navigation">
            <x-navigation.nav-item title="Dashboard" icon="chart-line" route="admin.home" :active="request()->routeIs('admin.home')" />
        </x-navigation.nav-section>
    @endforelse
</x-navigation.nav-sidebar>
