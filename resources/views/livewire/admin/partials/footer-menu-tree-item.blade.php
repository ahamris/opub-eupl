@props([
    'item',
    'depth' => 0,
    'expanded' => true,
    'expandedItems' => [],
])

@php
    $isDropdown = $item->item_type === 'dropdown' || $item->item_type === 'megamenu';
    $children = $item->childrenRecursive ?? collect();
    $hasChildren = $children->isNotEmpty();
    
    // Depth'e göre görsel derinlik
    $depthColors = [
        0 => 'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700',
        1 => 'bg-sky-50 dark:bg-sky-800/50 border-sky-200 dark:border-sky-700',
        2 => 'bg-teal-100 dark:bg-teal-800/50 border-teal-200 dark:border-teal-700',
    ];
    $bgColor = $depthColors[min($depth, 2)] ?? $depthColors[2];
    
    $shadowClass = $depth === 0 ? 'shadow-sm' : '';
    
    // Status indicators
    $isDisabled = $item->is_disabled;
    $isHidden = $item->is_hidden;
    $isInactive = !$item->is_active;
@endphp

<li
    class="border rounded-md {{ $bgColor }} {{ $shadowClass }} px-3 py-2.5 transition-all hover:shadow-xs {{ $isDisabled || $isInactive ? 'opacity-60' : '' }}"
    wire:key="footer-menu-item-{{ $item->id }}"
    data-item-id="{{ $item->id }}"
    style="margin-left: {{ $depth * 1 }}rem;"
>
    <div class="flex items-center gap-3 group">
        {{-- Drag Handle --}}
        <x-button 
            variant="secondary" 
            size="sm" 
            icon="grip-vertical" 
            data-sort-handle
            title="Drag to reorder"
            class="shrink-0 cursor-move opacity-40 group-hover:opacity-100 transition-opacity"
        ></x-button>

        {{-- Expand/Collapse for items with children --}}
        @if($hasChildren)
            <x-button 
                variant="secondary" 
                size="sm" 
                icon="{{ $expanded ? 'chevron-down' : 'chevron-right' }}"
                wire:click="toggleExpand({{ $item->id }})"
                class="shrink-0 opacity-40 group-hover:opacity-100 transition-opacity"
            ></x-button>
        @endif

        {{-- Icon --}}
        @if($item->icon)
            <i class="fa-solid fa-{{ $item->icon }} text-gray-500 dark:text-gray-400 shrink-0"></i>
        @endif

        {{-- Label and Info --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="font-medium text-gray-900 dark:text-gray-100 truncate">{{ $item->label }}</span>
                
                {{-- Type Badge --}}
                @if($isDropdown)
                    <x-badge variant="sky" size="xs">Dropdown</x-badge>
                @endif
                
                {{-- Status Badges --}}
                @if($isInactive)
                    <x-badge variant="secondary" size="xs">Inactive</x-badge>
                @endif
                @if($isDisabled)
                    <x-badge variant="warning" size="xs">Disabled</x-badge>
                @endif
                @if($isHidden)
                    <x-badge variant="error" size="xs">Hidden</x-badge>
                @endif
                
                {{-- Custom Badge --}}
                @if($item->badge_text)
                    <x-badge variant="{{ $item->badge_color ?? 'primary' }}" size="xs">{{ $item->badge_text }}</x-badge>
                @endif
            </div>
            
            {{-- Route/URL info --}}
            @if($item->route_name || $item->url)
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                    @if($item->route_name)
                        <i class="fa-solid fa-route mr-1"></i>{{ $item->route_name }}
                    @else
                        <i class="fa-solid fa-link mr-1"></i>{{ $item->url }}
                    @endif
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-1 shrink-0 opacity-40 group-hover:opacity-100 transition-opacity">
            <x-button variant="secondary" size="xs" icon="pencil" wire:click="edit({{ $item->id }})" title="Edit"></x-button>
            <x-button variant="error" size="xs" icon="trash" wire:click="confirmDelete({{ $item->id }})" title="Delete"></x-button>
        </div>
    </div>
</li>

{{-- Children (if expanded) --}}
@if($hasChildren && $expanded)
    <div class="mt-2 relative">
        {{-- Visual connection line --}}
        <div class="absolute left-0 top-0 bottom-0 w-px {{ $depth === 0 ? 'bg-indigo-200 dark:bg-indigo-700' : 'bg-gray-300 dark:bg-gray-600' }} ml-4"></div>
        <ul
            class="space-y-2 list-none pl-6 relative"
            data-footer-menu-sortable
            data-parent-id="{{ $item->id }}"
        >
            @foreach($children as $child)
                @include('livewire.admin.partials.footer-menu-tree-item', [
                    'item' => $child,
                    'depth' => $depth + 1,
                    'expanded' => $expanded && ($expandedItems[$child->id] ?? true),
                    'expandedItems' => $expandedItems
                ])
            @endforeach
        </ul>
    </div>
@endif

