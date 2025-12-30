<div x-data="{ autoRefresh: @js($autoRefresh), refreshInterval: @js($refreshInterval * 1000) }" 
     x-init="
        if (autoRefresh) {
            setInterval(() => {
                $wire.refresh();
            }, refreshInterval);
        }
     ">
    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Documents -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Documents</p>
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white mt-2">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                    <i class="fas fa-database text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Synced Documents -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Synced to Typesense</p>
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white mt-2">{{ number_format($stats['synced']) }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">{{ $stats['sync_percentage'] }}% complete</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Documents -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Pending Sync</p>
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white mt-2">{{ number_format($stats['pending']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Sync Status -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Sync Status</p>
                    <div class="mt-2 flex items-center gap-2">
                        @if($stats['is_running'])
                            <span class="inline-flex items-center gap-2">
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                                </span>
                                <span class="text-lg font-semibold text-blue-600 dark:text-blue-400">Running</span>
                            </span>
                        @else
                            <span class="text-lg font-semibold text-zinc-600 dark:text-zinc-400">Idle</span>
                        @endif
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg {{ $stats['is_running'] ? 'bg-blue-100 dark:bg-blue-900/20' : 'bg-zinc-100 dark:bg-zinc-900/20' }} flex items-center justify-center">
                    <i class="fas {{ $stats['is_running'] ? 'fa-sync fa-spin' : 'fa-pause-circle' }} {{ $stats['is_running'] ? 'text-blue-600 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400' }} text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Sync Progress Bar -->
    @if($stats['total'] > 0)
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Sync Progress</h3>
            <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $stats['sync_percentage'] }}%</span>
        </div>
        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-4">
            <div class="bg-blue-600 dark:bg-blue-500 h-4 rounded-full transition-all duration-300" 
                 style="width: {{ $stats['sync_percentage'] }}%"></div>
        </div>
        <div class="flex items-center justify-between mt-2 text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ number_format($stats['synced']) }} synced</span>
            <span>{{ number_format($stats['pending']) }} pending</span>
        </div>
    </div>
    @endif

    <!-- Actions and Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Actions Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Actions</h3>
            <div class="space-y-3">
                <x-button 
                    variant="primary" 
                    icon="sync" 
                    icon-position="left"
                    wire:click="triggerSync"
                    :disabled="$stats['is_running']"
                >
                    @if($stats['is_running'])
                        Sync in Progress...
                    @else
                        Trigger Sync Now
                    @endif
                </x-button>
                <x-button 
                    variant="secondary" 
                    icon="refresh" 
                    icon-position="left"
                    wire:click="refresh"
                >
                    Refresh Status
                </x-button>
            </div>
            @if($stats['last_synced'])
            <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    <i class="fas fa-clock mr-2"></i>
                    Last synced: {{ $stats['last_synced']->diffForHumans() }}
                </p>
            </div>
            @endif
        </div>

        <!-- Sync Information -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Sync Information</h3>
            <div class="space-y-2 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-zinc-600 dark:text-zinc-400">Sync Enabled:</span>
                    <x-ui.badge :color="config('open_overheid.typesense.enabled', true) ? 'green' : 'red'">
                        {{ config('open_overheid.typesense.enabled', true) ? 'Yes' : 'No' }}
                    </x-ui.badge>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-zinc-600 dark:text-zinc-400">Auto-refresh:</span>
                    <x-ui.badge :color="$autoRefresh ? 'green' : 'gray'">
                        {{ $autoRefresh ? 'On' : 'Off' }}
                    </x-ui.badge>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-zinc-600 dark:text-zinc-400">Refresh Interval:</span>
                    <span class="text-zinc-900 dark:text-white">{{ $refreshInterval }}s</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Logs -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Recent Sync Logs</h3>
                <x-button 
                    variant="secondary" 
                    size="sm"
                    icon="refresh" 
                    wire:click="refresh"
                >
                    Refresh
                </x-button>
            </div>
            
            @if(!empty($stats['recent_logs']))
                <div class="bg-zinc-900 dark:bg-black rounded-lg p-4 font-mono text-sm max-h-96 overflow-y-auto">
                    @foreach($stats['recent_logs'] as $log)
                        <div class="mb-1">
                            @if($log['timestamp'])
                                <span class="text-zinc-500">[{{ $log['timestamp'] }}]</span>
                            @endif
                            <span class="text-green-400">{{ $log['message'] }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-zinc-500 dark:text-zinc-400">
                    <i class="fas fa-file-alt text-3xl mb-2"></i>
                    <p>No recent logs found</p>
                    <p class="text-sm mt-1">Logs will appear here when sync operations run</p>
                </div>
            @endif
        </div>
    </div>
</div>
