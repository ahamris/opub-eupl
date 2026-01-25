<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperContact
 */
class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'full_name',
        'organisation',
        'phone',
        'status',
        'priority',
        'notes',
        'last_contacted_at',
    ];

    protected $casts = [
        'last_contacted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all submissions for this contact (tickets)
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(ContactSubmission::class);
    }

    /**
     * Get the latest submission
     */
    public function latestSubmission()
    {
        return $this->hasOne(ContactSubmission::class)->latestOfMany();
    }

    /**
     * Get unread submissions count
     */
    public function getUnreadSubmissionsCountAttribute(): int
    {
        return $this->submissions()->unread()->count();
    }

    /**
     * Get active (non-archived) submissions count
     */
    public function getActiveSubmissionsCountAttribute(): int
    {
        return $this->submissions()->active()->count();
    }

    /**
     * Get all submissions count
     */
    public function getTotalSubmissionsCountAttribute(): int
    {
        return $this->submissions()->count();
    }

    /**
     * Check if contact has unread submissions
     */
    public function hasUnreadSubmissions(): bool
    {
        return $this->submissions()->unread()->exists();
    }

    /**
     * Update last contacted timestamp
     */
    public function updateLastContacted(): void
    {
        $this->update(['last_contacted_at' => now()]);
    }

    /**
     * Find or create a contact by email
     */
    public static function findOrCreateByEmail(string $email, array $attributes = []): self
    {
        $contact = self::where('email', $email)->first();

        if (!$contact) {
            $contact = self::create(array_merge([
                'email' => $email,
            ], $attributes));
        } else {
            // Update attributes if provided
            if (!empty($attributes)) {
                $contact->update($attributes);
            }
        }

        return $contact;
    }

    /**
     * Scope for active contacts (with active submissions)
     */
    public function scopeActive($query)
    {
        return $query->whereHas('submissions', function ($q) {
            $q->active();
        });
    }

    /**
     * Scope for contacts with unread submissions
     */
    public function scopeWithUnread($query)
    {
        return $query->whereHas('submissions', function ($q) {
            $q->unread();
        });
    }

    /**
     * Scope for contacts by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for contacts by priority
     */
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'new' => 'New',
            'active' => 'Active',
            'pending' => 'Pending',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
        ];

        return $statuses[$this->status] ?? $this->status ?? 'New';
    }

    /**
     * Get priority label
     */
    public function getPriorityLabelAttribute(): string
    {
        $priorities = [
            'low' => 'Low',
            'normal' => 'Normal',
            'high' => 'High',
            'urgent' => 'Urgent',
        ];

        return $priorities[$this->priority] ?? $this->priority ?? 'Normal';
    }

    /**
     * Get display name (full name or email)
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->full_name ?: $this->email;
    }
}
