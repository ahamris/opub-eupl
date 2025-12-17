<x-layouts.admin title="Dashboard">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Dashboard</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Welcome! You can track your system's overall status here.</p>
            </div>
        </div>
            <!-- Users Column -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Users</h2>
                    <a href="{{ route('admin.users.create') }}">
                        <x-button variant="primary" size="sm" icon="plus">Add</x-button>
                    </a>
                </div>
                <div class="p-4">
                    <livewire:admin.table 
                        resource="users"
                        :columns="[
                            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
                            ['key' => 'name', 'label' => 'Name', 'sortable' => true],
                            ['key' => 'email', 'label' => 'Email', 'sortable' => true],
                        ['key' => 'is_active', 'label' => 'Is Active', 'sortable' => true, 'type' => 'toggle'],
                            ['key' => 'created_at', 'label' => 'Created At', 'sortable' => true, 'format' => 'date'],
                        ]"
                        :search-fields="['name', 'email']"
                    :sortable-fields="['id', 'name', 'email', 'is_active', 'created_at']"
                        :show-actions="true"
                        :actions="['view', 'edit', 'delete']"
                        route-prefix="admin.users"
                        search-placeholder="Search users..."
                        :paginate="5"
                        :show-checkbox="false"
                        :show-bulk-delete="false"
                    />
            </div>
        </div>

        <!-- Component Examples: 4 Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Column 1: Exclusive Mode Accordion -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-4">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Exclusive Mode Accordion</h3>
                <x-ui.accordion :exclusive="true">
                    <x-ui.accordion-item heading="First Item" :expanded="false">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">This is the content of the first accordion item. Only one item can be open at a time in exclusive mode.</p>
                    </x-ui.accordion-item>
                    <x-ui.accordion-item heading="Second Item" :expanded="false">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">This is the content of the second accordion item. When you open this, the first item will close automatically.</p>
                    </x-ui.accordion-item>
                    <x-ui.accordion-item heading="Third Item" :expanded="false">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">This is the content of the third accordion item. Exclusive mode ensures only one item is open at once.</p>
                    </x-ui.accordion-item>
                </x-ui.accordion>
            </div>

            <!-- Column 2: Colored & Custom Icon Accordion -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-4">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Colored & Custom Icons</h3>
                <x-ui.accordion>
                    <x-ui.accordion-item heading="Primary Item" icon="star" color="indigo" :expanded="false">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">This item has a star icon with indigo color styling.</p>
                    </x-ui.accordion-item>
                    <x-ui.accordion-item heading="Success Item" icon="check-circle" color="green" :expanded="false">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">This item has a check-circle icon with green color styling.</p>
                    </x-ui.accordion-item>
                    <x-ui.accordion-item heading="Warning Item" icon="exclamation-triangle" color="yellow" :expanded="false">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">This item has an exclamation-triangle icon with yellow color styling.</p>
                    </x-ui.accordion-item>
                    <x-ui.accordion-item heading="Error Item" icon="xmark-circle" color="red" :expanded="false">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">This item has an xmark-circle icon with red color styling.</p>
                    </x-ui.accordion-item>
                </x-ui.accordion>
            </div>

            <!-- Column 3: Tooltip Examples -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-4">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Tooltip Examples</h3>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <!-- Position Examples -->
                        <div class="flex flex-col gap-3">
                            <x-ui.tooltip text="Tooltip on top" position="top">
                                <x-button variant="primary" size="sm" class="w-full">Top Tooltip</x-button>
                            </x-ui.tooltip>
                            <x-ui.tooltip text="Tooltip on bottom" position="bottom">
                                <x-button variant="success" size="sm" class="w-full">Bottom Tooltip</x-button>
                            </x-ui.tooltip>
                        </div>

                        <!-- Click Trigger Example -->
                        <div>
                            <p class="text-xs text-zinc-600 dark:text-zinc-400 mb-2">Click to show:</p>
                            <x-ui.tooltip text="This tooltip appears on click" position="top" trigger="click">
                                <x-button variant="outline-primary" size="sm" class="w-full">Click Trigger</x-button>
                            </x-ui.tooltip>
                        </div>

                        <!-- With Icon -->
                        <div>
                            <p class="text-xs text-zinc-600 dark:text-zinc-400 mb-2">Icon tooltip:</p>
                            <x-ui.tooltip text="Settings icon tooltip" position="top">
                                <i class="fas fa-cog text-xl text-zinc-600 dark:text-zinc-400 cursor-pointer hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"></i>
                            </x-ui.tooltip>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <!-- Position Examples -->
                        <div class="flex flex-col gap-3">
                            <x-ui.tooltip text="Tooltip on left" position="left">
                                <x-button variant="warning" size="sm" class="w-full">Left Tooltip</x-button>
                            </x-ui.tooltip>
                            <x-ui.tooltip text="Tooltip on right" position="right">
                                <x-button variant="error" size="sm" class="w-full">Right Tooltip</x-button>
                            </x-ui.tooltip>
                        </div>

                        <!-- Long Text Example -->
                        <div>
                            <p class="text-xs text-zinc-600 dark:text-zinc-400 mb-2">Long text:</p>
                            <x-ui.tooltip text="This is a longer tooltip text that wraps to multiple lines when needed." position="top" max-width="200px">
                                <x-button variant="outline-primary" size="sm" class="w-full">Long Tooltip</x-button>
                            </x-ui.tooltip>
                        </div>

                        <!-- Custom Delay -->
                        <div>
                            <p class="text-xs text-zinc-600 dark:text-zinc-400 mb-2">Delay (500ms):</p>
                            <x-ui.tooltip text="Tooltip with custom delay" position="top" delay="500">
                                <x-button variant="outline-primary" size="sm" class="w-full">Delayed Tooltip</x-button>
                            </x-ui.tooltip>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-xs text-zinc-600 dark:text-zinc-400">Hover or click buttons to see different tooltip behaviors.</p>
                </div>
            </div>

            <!-- Column 4: Toast Examples -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-4">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Toast Messages</h3>
                <div class="space-y-3">
                    <x-button 
                        variant="success" 
                        size="sm" 
                        class="w-full" 
                        x-data 
                        x-on:click="toastManager.show('success', 'Operation completed successfully!')"
                    >
                        Success Toast
                    </x-button>
                    <x-button 
                        variant="error" 
                        size="sm" 
                        class="w-full" 
                        x-data 
                        x-on:click="toastManager.show('error', 'An error occurred. Please try again.')"
                    >
                        Error Toast
                    </x-button>
                    <x-button 
                        variant="warning" 
                        size="sm" 
                        class="w-full" 
                        x-data 
                        x-on:click="toastManager.show('warning', 'Please check your input.')"
                    >
                        Warning Toast
                    </x-button>
                    <x-button 
                        variant="primary" 
                        size="sm" 
                        class="w-full" 
                        x-data 
                        x-on:click="toastManager.show('info', 'Here is some information for you.')"
                    >
                        Info Toast
                    </x-button>
                </div>
                <div class="mt-4">
                    <p class="text-xs text-zinc-600 dark:text-zinc-400">Click buttons to show different toast messages.</p>
                </div>
            </div>
        </div>

        <!-- Primary Button Colors -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-4">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Primary Button Colors</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Buttons (span 3 columns) -->
                <div class="md:col-span-3">
                    <div class="flex flex-wrap gap-3">
                        <x-button variant="primary" color="red">Red</x-button>
                        <x-button variant="primary" color="orange">Orange</x-button>
                        <x-button variant="primary" color="amber">Amber</x-button>
                        <x-button variant="primary" color="yellow">Yellow</x-button>
                        <x-button variant="primary" color="lime">Lime</x-button>
                        <x-button variant="primary" color="green">Green</x-button>
                        <x-button variant="primary" color="emerald">Emerald</x-button>
                        <x-button variant="primary" color="teal">Teal</x-button>
                        <x-button variant="primary" color="cyan">Cyan</x-button>
                        <x-button variant="primary" color="sky">Sky</x-button>
                        <x-button variant="primary" color="blue">Blue</x-button>
                        <x-button variant="primary" color="indigo">Indigo</x-button>
                        <x-button variant="primary" color="violet">Violet</x-button>
                        <x-button variant="primary" color="purple">Purple</x-button>
                        <x-button variant="primary" color="fuchsia">Fuchsia</x-button>
                        <x-button variant="primary" color="pink">Pink</x-button>
                        <x-button variant="primary" color="rose">Rose</x-button>
                        <x-button variant="primary" color="zinc">Zinc</x-button>
                    </div>
                </div>

                <!-- Column 4: Badge Examples -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs text-zinc-600 dark:text-zinc-400 mb-1">Badge variants:</span>
                    <x-badge variant="primary" icon="star">Primary</x-badge>
                    <x-badge variant="secondary" icon="tag">Secondary</x-badge>
                    <x-badge variant="success" icon="check">Success</x-badge>
                    <x-badge variant="warning" icon="triangle-exclamation">Warning</x-badge>
                    <x-badge variant="error" icon="circle-xmark">Error</x-badge>
                    <x-badge variant="sky" icon="info-circle">Info</x-badge>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
