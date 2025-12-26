<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperSearchSubscription
 */
class SearchSubscription extends Model
{
    protected $fillable = [
        'email',
        'frequency',
        'search_query',
        'filters',
        'is_active',
        'verification_token',
        'verified_at',
        'last_sent_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'is_active' => 'boolean',
        'verified_at' => 'datetime',
        'last_sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate a verification token
     */
    public static function generateVerificationToken(): string
    {
        return Str::random(64);
    }

    /**
     * Create a new subscription with verification token
     */
    public static function createWithVerification(array $attributes): self
    {
        $attributes['verification_token'] = self::generateVerificationToken();
        return self::create($attributes);
    }

    /**
     * Verify the subscription
     */
    public function verify(): void
    {
        $this->update([
            'verified_at' => now(),
            'verification_token' => null,
            'is_active' => true,
        ]);
    }

    /**
     * Check if subscription is verified
     */
    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for verified subscriptions
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    /**
     * Scope for unverified subscriptions
     */
    public function scopeUnverified($query)
    {
        return $query->whereNull('verified_at');
    }

    /**
     * Get frequency label
     */
    public function getFrequencyLabelAttribute(): string
    {
        return match($this->frequency) {
            'immediate' => 'Direct na publicatie',
            'daily' => 'Dagelijks',
            'weekly' => 'Wekelijks',
            default => $this->frequency,
        };
    }

    /**
     * Get formatted filters display
     */
    public function getFormattedFiltersAttribute(): string
    {
        if (empty($this->filters) || !is_array($this->filters)) {
            return 'Geen filters';
        }

        $parts = [];
        
        // Map filter keys to readable labels
        $labels = [
            'thema' => 'Thema',
            'organisatie' => 'Organisatie',
            'documentsoort' => 'Documentsoort',
            'bestandstype' => 'Bestandstype',
            'status' => 'Status',
            'informatiecategorie' => 'Categorie',
            'beschikbaarSinds' => 'Beschikbaar sinds',
            'publicatiedatum_van' => 'Van',
            'publicatiedatum_tot' => 'Tot',
            'titles_only' => 'Alleen titels',
            'zoeken' => 'Zoekterm',
        ];

        foreach ($this->filters as $key => $value) {
            if (empty($value)) {
                continue;
            }

            $label = $labels[$key] ?? ucfirst($key);
            
            if (is_array($value)) {
                $value = implode(', ', array_filter($value));
            }
            
            if (!empty($value)) {
                $parts[] = $label . ': ' . $value;
            }
        }

        return !empty($parts) ? implode(' | ', $parts) : 'Geen filters';
    }
}
