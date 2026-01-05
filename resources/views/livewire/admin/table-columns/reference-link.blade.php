@if($item->link_url)
    <a href="{{ $item->link_url }}" target="_blank" rel="noopener noreferrer" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 inline-flex items-center gap-1">
        {{ $item->link_text ?: Str::limit($item->link_url, 30) }}
        <i class="fas fa-external-link-alt text-xs"></i>
    </a>
@else
    <span class="text-zinc-400">-</span>
@endif
