@php
    // Create sample paginated data for examples
    $sampleItems = collect(range(1, 150))->map(fn($i) => (object)['id' => $i, 'name' => "Item {$i}"]);
    $perPage = 10;
    $currentPage = request()->get('page', 1);
    $paginator1 = new \Illuminate\Pagination\LengthAwarePaginator(
        $sampleItems->forPage($currentPage, $perPage),
        $sampleItems->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );
    
    // For compact variant
    $paginator2 = new \Illuminate\Pagination\LengthAwarePaginator(
        $sampleItems->forPage(3, $perPage),
        $sampleItems->count(),
        $perPage,
        3,
        ['path' => request()->url(), 'query' => request()->query()]
    );
    
    // For first page
    $paginator3 = new \Illuminate\Pagination\LengthAwarePaginator(
        $sampleItems->forPage(1, $perPage),
        $sampleItems->count(),
        $perPage,
        1,
        ['path' => request()->url(), 'query' => request()->query()]
    );
    
    // For last page
    $lastPage = ceil($sampleItems->count() / $perPage);
    $paginator4 = new \Illuminate\Pagination\LengthAwarePaginator(
        $sampleItems->forPage($lastPage, $perPage),
        $sampleItems->count(),
        $perPage,
        $lastPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );
    
    // For middle page
    $paginator5 = new \Illuminate\Pagination\LengthAwarePaginator(
        $sampleItems->forPage(8, $perPage),
        $sampleItems->count(),
        $perPage,
        8,
        ['path' => request()->url(), 'query' => request()->query()]
    );
@endphp

<x-layouts.admin title="Pagination - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Pagination -->
            <div>
                @php
                    $code1 = '<x-ui.pagination :paginator="$paginator" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Pagination</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code1 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden">
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-900">
                        <div class="space-y-2">
                            @foreach($paginator1->items() as $item)
                                <div class="p-2 bg-white dark:bg-zinc-800 rounded text-sm">{{ $item->name }}</div>
                            @endforeach
                        </div>
                    </div>
                    <x-ui.pagination :paginator="$paginator1" />
                </div>
            </div>

            <!-- Compact Variant -->
            <div>
                @php
                    $code2 = '<x-ui.pagination :paginator="$paginator" variant="compact" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Compact Variant</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code2 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden">
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-900">
                        <div class="space-y-2">
                            @foreach($paginator2->items() as $item)
                                <div class="p-2 bg-white dark:bg-zinc-800 rounded text-sm">{{ $item->name }}</div>
                            @endforeach
                        </div>
                    </div>
                    <x-ui.pagination :paginator="$paginator2" variant="compact" />
                </div>
            </div>

            <!-- Without Info -->
            <div>
                @php
                    $code3 = '<x-ui.pagination :paginator="$paginator" :show-info="false" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Without Info</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code3 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden">
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-900">
                        <div class="space-y-2">
                            @foreach($paginator1->items() as $item)
                                <div class="p-2 bg-white dark:bg-zinc-800 rounded text-sm">{{ $item->name }}</div>
                            @endforeach
                        </div>
                    </div>
                    <x-ui.pagination :paginator="$paginator1" :show-info="false" />
                </div>
            </div>

            <!-- Custom onEachSide -->
            <div>
                @php
                    $code4 = '<x-ui.pagination :paginator="$paginator" :on-each-side="3" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Custom onEachSide</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code4 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden">
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-900">
                        <div class="space-y-2">
                            @foreach($paginator5->items() as $item)
                                <div class="p-2 bg-white dark:bg-zinc-800 rounded text-sm">{{ $item->name }}</div>
                            @endforeach
                        </div>
                    </div>
                    <x-ui.pagination :paginator="$paginator5" :on-each-side="3" />
                </div>
            </div>

            <!-- First Page -->
            <div>
                @php
                    $code5 = '<x-ui.pagination :paginator="$paginator" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">First Page</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code5 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden">
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-900">
                        <div class="space-y-2">
                            @foreach($paginator3->items() as $item)
                                <div class="p-2 bg-white dark:bg-zinc-800 rounded text-sm">{{ $item->name }}</div>
                            @endforeach
                        </div>
                    </div>
                    <x-ui.pagination :paginator="$paginator3" />
                </div>
            </div>

            <!-- Last Page -->
            <div>
                @php
                    $code6 = '<x-ui.pagination :paginator="$paginator" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Last Page</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code6 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden">
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-900">
                        <div class="space-y-2">
                            @foreach($paginator4->items() as $item)
                                <div class="p-2 bg-white dark:bg-zinc-800 rounded text-sm">{{ $item->name }}</div>
                            @endforeach
                        </div>
                    </div>
                    <x-ui.pagination :paginator="$paginator4" />
                </div>
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div class="space-y-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm p-6">
                <h2 class="text-2xl font-bold mb-4">Pagination Component</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    A flexible pagination component that works with Laravel's LengthAwarePaginator. Supports both standard HTTP navigation and Livewire wire models.
                </p>

                <h3 class="text-xl font-semibold mb-3">Props</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">paginator</code> - LengthAwarePaginator (required) - The paginated data from Laravel</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">variant</code> - string - 'default' | 'compact' (default: 'default')</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">wireModel</code> - string|null - Livewire component property name for wire navigation (default: null)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">showInfo</code> - bool - Show "Showing X to Y of Z results" (default: true)</li>
                    <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">onEachSide</code> - int - Number of pages to show on each side of current page (default: 2)</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Variants</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li><strong>default</strong> - Shows page numbers with ellipsis for large page counts</li>
                    <li><strong>compact</strong> - Shows "Page X of Y" format instead of individual page numbers</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3">Livewire Support</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    When using with Livewire, pass the component property name to <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">wireModel</code>:
                </p>
                <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto mb-6"><code>&lt;x-ui.pagination :paginator="$users" wire-model="users" /&gt;</code></pre>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    The component will automatically use <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">wire:click</code> for navigation instead of regular links.
                </p>

                <h3 class="text-xl font-semibold mb-3 mt-6">Features</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <li>• Automatic ellipsis for large page counts</li>
                    <li>• First and last page shortcuts</li>
                    <li>• Previous/Next navigation buttons</li>
                    <li>• Results info display (customizable)</li>
                    <li>• Livewire wire model support</li>
                    <li>• Responsive design</li>
                    <li>• Dark mode support</li>
                    <li>• Accessible with proper ARIA labels</li>
                    <li>• Disabled states for first/last pages</li>
                </ul>

                <h3 class="text-xl font-semibold mb-3 mt-6">Common Use Cases</h3>
                <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                    <div>
                        <p class="font-medium mb-1">Standard HTTP Pagination:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>// In Controller
$users = User::paginate(15);

// In Blade
&lt;x-ui.pagination :paginator="$users" /&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Livewire Pagination:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>// In Livewire Component
public function render()
{
    $users = User::paginate(15);
    return view('livewire.users', compact('users'));
}

// In Blade
&lt;x-ui.pagination :paginator="$users" wire-model="users" /&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Compact Variant:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-ui.pagination :paginator="$items" variant="compact" /&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Without Info:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-ui.pagination :paginator="$items" :show-info="false" /&gt;</code></pre>
                    </div>
                    <div>
                        <p class="font-medium mb-1">Custom Page Range:</p>
                        <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code>&lt;x-ui.pagination :paginator="$items" :on-each-side="3" /&gt;</code></pre>
                    </div>
                </div>

                <h3 class="text-xl font-semibold mb-3 mt-6">Controller Example</h3>
                <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto mb-6"><code>public function index()
{
    $items = Item::query()
        ->when(request('search'), fn($q) => $q->where('name', 'like', '%'.request('search').'%'))
        ->orderBy('created_at', 'desc')
        ->paginate(15);
    
    return view('items.index', compact('items'));
}</code></pre>
            </div>
        </div>
    </div>

</x-layouts.admin>

