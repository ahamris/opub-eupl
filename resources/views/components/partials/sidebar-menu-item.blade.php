@props([
    'item',
    'depth' => 0,
])

@php
    $isSection = $item->item_type === 'section';
    // $filterActiveItems already filters children recursively in Sidebar component
    // So $childrenRecursive already contains only active children
    $children = $item->relationLoaded('childrenRecursive')
        ? $item->childrenRecursive
        : collect();
    $hasChildren = $children->isNotEmpty();
    $url = null;
    if ($item->route_name) {
        try {
            $url = route($item->route_name, $item->resolvedRouteParameters());
        } catch (\Illuminate\Routing\Exceptions\RouteNotFoundException $e) {
            // Route tanımlı değilse, url kullan veya null bırak
            $url = $item->url ?: null;
        }
    } else {
        $url = $item->url ?: null;
    }
@endphp

@if($isSection)
    <x-navigation.nav-section :title="$item->label">
        @foreach($children as $child)
            <x-partials.sidebar-menu-item :item="$child" :depth="$depth"/>
        @endforeach
    </x-navigation.nav-section>
@else
    @if($depth === 0)
        @if($hasChildren)
            <x-navigation.nav-item
                :title="$item->label"
                :icon="$item->icon"
                :badge="$item->resolvedBadgeText"
                :badge-color="$item->badge_color ?? 'primary'"
                :route="$item->route_name"
                :href="$url"
                :active="$item->isCurrentlyActive()"
                :expanded="$item->shouldExpand()"
                :target="$item->target"
            >
                @foreach($children as $child)
                    <x-partials.sidebar-menu-item :item="$child" :depth="$depth + 1" />
                @endforeach
            </x-navigation.nav-item>
        @else
            <x-navigation.nav-item
                :title="$item->label"
                :icon="$item->icon"
                :badge="$item->resolvedBadgeText"
                :badge-color="$item->badge_color ?? 'primary'"
                :route="$item->route_name"
                :href="$url"
                :active="$item->isCurrentlyActive()"
                :target="$item->target"
            />
        @endif
    @else
        <x-navigation.nav-sub-item
            :title="$item->label"
            :icon="$item->icon"
            :route="$item->route_name"
            :href="$url"
            :active="$item->isCurrentlyActive()"
            :badge="$item->resolvedBadgeText"
            :badge-color="$item->badge_color ?? 'primary'"
        />

        @if($hasChildren)
            <div class="mt-1 space-y-1 pl-4 list-none">
                @foreach($children as $child)
                    <x-partials.sidebar-menu-item :item="$child" :depth="$depth + 1" />
                @endforeach
            </div>
        @endif
    @endif
@endif

