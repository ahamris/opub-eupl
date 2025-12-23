<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class HeaderMenuItem extends Model
{
    use HasFactory;

    /**
     * Cache key for the menu tree.
     */
    protected const CACHE_KEY = 'header-menu:tree';

    /**
     * Cache TTL in seconds (1 hour).
     */
    protected const CACHE_TTL = 3600;

    protected $fillable = [
        'parent_id',
        'label',
        'slug',
        'item_type',
        'route_name',
        'url',
        'icon',
        'description',
        'badge_text',
        'badge_color',
        'is_disabled',
        'is_hidden',
        'target',
        'position',
        'is_active',
        'options',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'position' => 'integer',
            'is_active' => 'boolean',
            'is_disabled' => 'boolean',
            'is_hidden' => 'boolean',
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderBy('id');
    }

    public function scopeRootItems($query)
    {
        return $query->whereNull('parent_id');
    }

    // ─────────────────────────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────────────────────────

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->ordered();
    }

    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    public function activeChildren(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('is_active', true)
            ->where('is_hidden', false)
            ->ordered();
    }

    // ─────────────────────────────────────────────────────────────
    // Helper Methods
    // ─────────────────────────────────────────────────────────────

    /**
     * Check if this menu item is currently active based on the current route.
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Check options for custom active pattern
        $options = $this->options ?? [];
        if (isset($options['active_pattern'])) {
            return request()->routeIs($options['active_pattern']);
        }

        if ($this->route_name) {
            return request()->routeIs($this->route_name)
                || request()->routeIs($this->route_name . '.*');
        }

        return false;
    }

    /**
     * Check if any child is currently active.
     */
    public function hasActiveChild(): bool
    {
        if (!$this->relationLoaded('children')) {
            return false;
        }

        return $this->children->contains(function (self $child) {
            return $child->isCurrentlyActive() || $child->hasActiveChild();
        });
    }

    /**
     * Get the resolved URL for this menu item.
     */
    public function getResolvedUrlAttribute(): ?string
    {
        if ($this->is_disabled) {
            return null;
        }

        if ($this->route_name) {
            try {
                return route($this->route_name);
            } catch (\Exception $e) {
                return null;
            }
        }

        return $this->url;
    }

    /**
     * Check if this is a dropdown/flyout menu.
     */
    public function isDropdown(): bool
    {
        return $this->item_type === 'dropdown' || $this->item_type === 'megamenu';
    }

    /**
     * Check if this item has visible children.
     */
    public function hasVisibleChildren(): bool
    {
        return $this->children()
            ->where('is_active', true)
            ->where('is_hidden', false)
            ->exists();
    }

    // ─────────────────────────────────────────────────────────────
    // Static Methods
    // ─────────────────────────────────────────────────────────────

    /**
     * Get the full menu tree for rendering (cached).
     */
    public static function getMenuTree(): Collection
    {
        return Cache::remember(static::CACHE_KEY, static::CACHE_TTL, function () {
            return static::query()
                ->with(['activeChildren' => function ($query) {
                    $query->with('activeChildren');
                }])
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->where('is_hidden', false)
                ->ordered()
                ->get();
        });
    }

    /**
     * Clear the menu cache.
     */
    public static function clearCache(): void
    {
        Cache::forget(static::CACHE_KEY);
    }

    // ─────────────────────────────────────────────────────────────
    // Model Events
    // ─────────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::created(fn() => static::clearCache());
        static::updated(fn() => static::clearCache());
        static::deleted(fn() => static::clearCache());
    }
}
