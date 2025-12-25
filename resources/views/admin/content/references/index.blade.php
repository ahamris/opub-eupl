<x-layouts.admin title="References">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">References</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage external links on the Verwijzingen page</p>
            </div>
            <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.reference.create') }}">Add Reference</x-button>
        </div>

        @if(session('success'))
            <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
        @endif

        <!-- References Table -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md overflow-hidden">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider w-12">Icon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider hidden md:table-cell">Link</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider w-20">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider w-24">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($references as $reference)
                    <tr class="{{ !$reference->is_active ? 'opacity-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-[var(--color-primary)]/10">
                                <i class="{{ $reference->icon }} text-sm text-[var(--color-primary)]"></i>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-zinc-900 dark:text-white">{{ $reference->title }}</div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400 truncate max-w-xs">{{ Str::limit($reference->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                            @if($reference->link_url)
                            <a href="{{ $reference->link_url }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-900 inline-flex items-center gap-1">
                                {{ $reference->link_text ?: Str::limit($reference->link_url, 30) }}
                                <i class="fas fa-external-link-alt text-xs"></i>
                            </a>
                            @else
                            <span class="text-zinc-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm text-zinc-500">{{ $reference->sort_order }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.content.reference.toggle-active', $reference) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $reference->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $reference->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.content.reference.edit', $reference) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.content.reference.destroy', $reference) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-link text-4xl mb-4 text-zinc-300 dark:text-zinc-600"></i>
                                <p>No references yet</p>
                                <a href="{{ route('admin.content.reference.create') }}" class="mt-2 text-indigo-600 hover:underline">Create your first reference</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
