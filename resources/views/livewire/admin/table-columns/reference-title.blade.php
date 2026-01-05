<div>
    <div class="font-medium text-zinc-900 dark:text-white">{{ $item->title }}</div>
    @if($item->description)
        <div class="text-sm text-zinc-500 dark:text-zinc-400 truncate max-w-xs">{{ Str::limit($item->description, 50) }}</div>
    @endif
</div>
