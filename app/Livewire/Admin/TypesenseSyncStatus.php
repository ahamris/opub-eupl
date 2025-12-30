<?php

namespace App\Livewire\Admin;

use App\Models\OpenOverheidDocument;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Livewire\Attributes\On;
use Livewire\Component;

class TypesenseSyncStatus extends Component
{
    public bool $autoRefresh = true;
    public int $refreshInterval = 5; // seconds

    public function mount(): void
    {
        // Start auto-refresh if enabled
        if ($this->autoRefresh) {
            $this->dispatch('start-auto-refresh', interval: $this->refreshInterval * 1000);
        }
    }

    public function getStatsProperty(): array
    {
        $totalDocuments = OpenOverheidDocument::count();
        $syncedDocuments = OpenOverheidDocument::whereNotNull('typesense_synced_at')->count();
        $pendingDocuments = OpenOverheidDocument::needsTypesenseSync()->count();
        $lastSynced = OpenOverheidDocument::whereNotNull('typesense_synced_at')
            ->orderBy('typesense_synced_at', 'desc')
            ->value('typesense_synced_at');

        // Check if sync job is running
        $isRunning = $this->isSyncRunning();

        // Get recent log entries
        $recentLogs = $this->getRecentLogs();

        return [
            'total' => $totalDocuments,
            'synced' => $syncedDocuments,
            'pending' => $pendingDocuments,
            'last_synced' => $lastSynced,
            'is_running' => $isRunning,
            'sync_percentage' => $totalDocuments > 0 ? round(($syncedDocuments / $totalDocuments) * 100, 2) : 0,
            'recent_logs' => $recentLogs,
        ];
    }

    protected function isSyncRunning(): bool
    {
        // Check if there's a running job in the queue
        try {
            $queueSize = Queue::size('default');
            
            // Check cache for sync status (set when sync starts)
            $syncStarted = Cache::get('typesense_sync_running', false);
            
            // Check if sync was started recently (within last 5 minutes)
            $syncStartTime = Cache::get('typesense_sync_started_at');
            if ($syncStartTime && now()->diffInSeconds($syncStartTime) < 300) {
                return true;
            }

            return $syncStarted || $queueSize > 0;
        } catch (\Exception $e) {
            // If queue driver doesn't support size(), just check cache
            return Cache::get('typesense_sync_running', false);
        }
    }

    protected function getRecentLogs(int $limit = 20): array
    {
        try {
            // Try to get logs from the typesense_errors channel
            $logFile = storage_path('logs/laravel-' . now()->format('Y-m-d') . '.log');
            
            // Also check for dedicated typesense log file
            $typesenseLogFile = storage_path('logs/typesense-errors.log');
            
            $logFile = file_exists($typesenseLogFile) ? $typesenseLogFile : $logFile;
            
            if (!file_exists($logFile)) {
                return [];
            }

            // Read last N lines from log file (more efficient for large files)
            $handle = fopen($logFile, 'r');
            if ($handle === false) {
                return [];
            }

            // Go to end of file
            fseek($handle, 0, SEEK_END);
            $fileSize = ftell($handle);
            
            // Read backwards to get last N lines
            $lines = [];
            $line = '';
            $pos = $fileSize - 1;
            
            // Read backwards until we have enough lines
            while ($pos >= 0 && count($lines) < $limit) {
                fseek($handle, $pos);
                $char = fgetc($handle);
                
                if ($char === "\n" && !empty($line)) {
                    $lines[] = strrev($line);
                    $line = '';
                } else {
                    $line = $char . $line;
                }
                
                $pos--;
            }
            
            // Add the last line if exists
            if (!empty($line)) {
                $lines[] = strrev($line);
            }
            
            fclose($handle);
            
            $logs = [];
            foreach (array_reverse($lines) as $line) {
                $trimmed = trim($line);
                if (!empty($trimmed)) {
                    $logs[] = [
                        'message' => $trimmed,
                        'timestamp' => $this->extractTimestamp($trimmed),
                        'level' => $this->extractLogLevel($trimmed),
                    ];
                }
            }

            return $logs;
        } catch (\Exception $e) {
            return [];
        }
    }
    
    protected function extractLogLevel(string $line): string
    {
        if (stripos($line, 'error') !== false) {
            return 'error';
        }
        if (stripos($line, 'warning') !== false || stripos($line, 'warn') !== false) {
            return 'warning';
        }
        if (stripos($line, 'info') !== false) {
            return 'info';
        }
        return 'info';
    }

    protected function extractTimestamp(string $line): ?string
    {
        // Extract timestamp from log line (Laravel log format: [2025-12-30 14:30:45] ...)
        if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function refresh(): void
    {
        // This method is called when user clicks refresh or via auto-refresh
        $this->dispatch('$refresh');
    }

    public function triggerSync(): void
    {
        // Dispatch the sync job
        \App\Jobs\SyncDocumentToTypesense::dispatch();
        
        // Mark sync as running
        Cache::put('typesense_sync_running', true, 300);
        Cache::put('typesense_sync_started_at', now(), 300);
        
        $this->dispatch('sync-started');
    }

    public function render()
    {
        return view('livewire.admin.typesense-sync-status', [
            'stats' => $this->stats,
        ]);
    }
}
