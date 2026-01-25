<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperContactSubmission
 */
class ContactSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'organisation',
        'full_name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
        'is_archived',
        'notes',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_archived' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the subject label for display
     */
    public function getSubjectLabelAttribute(): string
    {
        $subjects = [
            'algemeen' => 'General Inquiry',
            'technisch' => 'Technical Support',
            'samenwerking' => 'Partnership / Collaboration',
            'data' => 'Data & API Access',
            'feedback' => 'Feedback & Suggestions',
            'media' => 'Media & Press',
            'anders' => 'Other',
        ];

        return $subjects[$this->subject] ?? $this->subject;
    }

    /**
     * Mark the submission as read
     */
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Mark the submission as unread
     */
    public function markAsUnread(): void
    {
        $this->update(['is_read' => false]);
    }

    /**
     * Archive the submission
     */
    public function archive(): void
    {
        $this->update(['is_archived' => true]);
    }

    /**
     * Unarchive the submission
     */
    public function unarchive(): void
    {
        $this->update(['is_archived' => false]);
    }

    /**
     * Scope for unread submissions
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for active (non-archived) submissions
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope for archived submissions
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    /**
     * Get the contact that owns this submission
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Check if submission has an associated contact
     */
    public function hasContact(): bool
    {
        return $this->contact_id !== null;
    }
}
