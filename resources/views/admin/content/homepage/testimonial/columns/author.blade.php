<div class="flex items-center gap-3">
    <!-- User Icon -->
    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-indigo-600 dark:text-indigo-400">
            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd"/>
        </svg>
    </div>
    <div class="min-w-0">
        <div class="font-medium text-zinc-900 dark:text-zinc-100 truncate">{{ $item->author }}</div>
        @if($item->role || $item->organization)
            <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ $item->role_display }}</div>
        @endif
    </div>
</div>
