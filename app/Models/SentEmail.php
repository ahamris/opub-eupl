<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperSentEmail
 */
class SentEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'to',
        'cc',
        'subject',
        'body',
        'is_sent',
        'sent_at',
        'mailable_type',
        'mailable_id',
        'error_message',
        'attempts',
    ];

    protected $casts = [
        'is_sent' => 'boolean',
        'sent_at' => 'datetime',
        'attempts' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the parent mailable model (polymorphic relation).
     */
    public function mailable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Mark email as sent.
     */
    public function markAsSent(): void
    {
        $this->update([
            'is_sent' => true,
            'sent_at' => now(),
        ]);
    }

    /**
     * Record a failed attempt.
     */
    public function recordFailure(string $errorMessage): void
    {
        $this->increment('attempts');
        $this->update([
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Scope for unsent emails.
     */
    public function scopeUnsent($query)
    {
        return $query->where('is_sent', false);
    }

    /**
     * Scope for sent emails.
     */
    public function scopeSent($query)
    {
        return $query->where('is_sent', true);
    }

    /**
     * Scope for failed emails (with error message).
     */
    public function scopeFailed($query)
    {
        return $query->whereNotNull('error_message');
    }
}
