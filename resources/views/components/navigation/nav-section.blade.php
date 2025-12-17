@props([
    'title' => null,
    'showTitle' => true,
    'collapsible' => false,
    'expanded' => true
])

<li class="space-y-1 list-none" x-data="{ expanded: {{ $expanded ? 'true' : 'false' }} }">
    @if($title && $showTitle)
        <div class="px-3 mt-4 mb-2">
            <h3 class="m-0 text-xs font-bold text-zinc-700 dark:text-zinc-400 uppercase tracking-wider">
                {{ $title }}
            </h3>
        </div>
    @endif

    <ul class="space-y-1 list-none">
        {{ $slot }}
    </ul>
</li>
