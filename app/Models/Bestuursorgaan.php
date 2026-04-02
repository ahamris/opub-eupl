<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Bestuursorgaan extends Model
{
    protected $table = 'bestuursorganen';

    protected $fillable = [
        'systeem_id', 'naam', 'afkorting', 'slug', 'type', 'subtype',
        'straat', 'huisnummer', 'postcode', 'woonplaats', 'provincie',
        'postbus', 'post_postcode', 'post_woonplaats',
        'woo_straat', 'woo_huisnummer', 'woo_postbus', 'woo_postcode', 'woo_woonplaats', 'woo_email',
        'telefoon', 'email', 'website', 'contactformulier_url',
        'tooi_identifier', 'owms_identifier', 'relatie_ministerie',
        'is_woo_plichtig', 'woo_url', 'beschrijving',
        'logo_url', 'custom_beschrijving', 'claimed_by_user_id', 'claimed_at',
        'document_match_name',
    ];

    protected $casts = [
        'is_woo_plichtig' => 'boolean',
        'claimed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->naam);
            }
            if (empty($model->document_match_name)) {
                $model->document_match_name = $model->naam;
            }
        });
    }

    public function claimedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'claimed_by_user_id');
    }

    public function isClaimed(): bool
    {
        return $this->claimed_by_user_id !== null;
    }

    public function getBezoekadresAttribute(): ?string
    {
        if (!$this->straat && !$this->huisnummer) return null;
        $parts = array_filter([
            trim($this->straat . ' ' . $this->huisnummer),
            $this->postcode,
            $this->woonplaats,
        ]);
        return implode(', ', $parts);
    }

    public function getPostadresAttribute(): ?string
    {
        if (!$this->postbus && !$this->post_postcode) return null;
        $parts = array_filter([
            $this->postbus ? 'Postbus ' . $this->postbus : null,
            $this->post_postcode,
            $this->post_woonplaats,
        ]);
        return implode(', ', $parts);
    }

    public function getWooAdresAttribute(): ?string
    {
        $parts = array_filter([
            $this->woo_straat ? trim($this->woo_straat . ' ' . $this->woo_huisnummer) : null,
            $this->woo_postbus ? 'Postbus ' . $this->woo_postbus : null,
            $this->woo_postcode,
            $this->woo_woonplaats,
        ]);
        return $parts ? implode(', ', $parts) : null;
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeWooPlichtig($query)
    {
        return $query->where('is_woo_plichtig', true);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where('naam', 'ilike', "%{$term}%");
    }
}
