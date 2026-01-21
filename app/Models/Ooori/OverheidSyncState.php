<?php

namespace App\Models\Ooori;

use Illuminate\Database\Eloquent\Model;

class OverheidSyncState extends Model
{
    protected $connection = 'pgsql2';
    protected $table = 'overheid_sync_states';

    protected $fillable = [
        'sync_type',
        'last_synced_date',
        'last_synced_external_id',
        'last_page',
        'total_processed',
        'total_synced',
        'total_errors',
        'status',
        'started_at',
        'last_checkpoint_at',
        'metadata',
    ];

    protected $casts = [
        'last_synced_date' => 'date',
        'last_page' => 'integer',
        'total_processed' => 'integer',
        'total_synced' => 'integer',
        'total_errors' => 'integer',
        'started_at' => 'datetime',
        'last_checkpoint_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the active sync state for a given sync type
     */
    public static function getActive(string $syncType = 'full'): ?self
    {
        return self::where('sync_type', $syncType)
            ->where('status', 'in_progress')
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Create or update sync state
     */
    public static function checkpoint(
        string $syncType,
        ?string $lastSyncedDate = null,
        ?string $lastSyncedExternalId = null,
        int $lastPage = 1,
        int $totalProcessed = 0,
        int $totalSynced = 0,
        int $totalErrors = 0,
        string $status = 'in_progress',
        ?array $metadata = null
    ): self {
        $state = self::where('sync_type', $syncType)
            ->where('status', 'in_progress')
            ->first();

        if (!$state) {
            $state = new self();
            $state->sync_type = $syncType;
            $state->started_at = now();
        }

        if ($lastSyncedDate) {
            $state->last_synced_date = $lastSyncedDate;
        }
        if ($lastSyncedExternalId) {
            $state->last_synced_external_id = $lastSyncedExternalId;
        }
        $state->last_page = $lastPage;
        $state->total_processed = $totalProcessed;
        $state->total_synced = $totalSynced;
        $state->total_errors = $totalErrors;
        $state->status = $status;
        $state->last_checkpoint_at = now();
        if ($metadata !== null) {
            $state->metadata = $metadata;
        }

        $state->save();

        return $state;
    }

    /**
     * Mark sync as completed
     */
    public function markCompleted(): void
    {
        $this->status = 'completed';
        $this->last_checkpoint_at = now();
        $this->save();
    }

    /**
     * Mark sync as failed
     */
    public function markFailed(): void
    {
        $this->status = 'failed';
        $this->last_checkpoint_at = now();
        $this->save();
    }

    /**
     * Mark sync as paused
     */
    public function markPaused(): void
    {
        $this->status = 'paused';
        $this->last_checkpoint_at = now();
        $this->save();
    }
}
