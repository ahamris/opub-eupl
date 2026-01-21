<?php

namespace App\Models\Ooori;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OverheidTheme extends Model
{
    protected $connection = 'pgsql2';
    protected $table = 'overheid_themes';

    protected $fillable = [
        'name',
        'visible_name',
        'parent_id',
        'rgt',
        'lft',
        'dpth',
    ];

    protected $casts = [
        'rgt' => 'integer',
        'lft' => 'integer',
        'dpth' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-sync visible_name with name when visible_name is null or empty
        static::saving(function ($theme) {
            if (empty($theme->visible_name) && !empty($theme->name)) {
                $theme->visible_name = $theme->name;
            }
        });
    }

    /**
     * Get the visible name, falling back to name if visible_name is null.
     * This accessor ensures that when reading, we always get a value.
     */
    public function getVisibleNameAttribute($value)
    {
        // If visible_name is null or empty, return name instead
        if (empty($value)) {
            return $this->name ?? $value;
        }
        return $value;
    }

    /**
     * Get the parent theme.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(OverheidTheme::class, 'parent_id');
    }

    /**
     * Get child themes.
     */
    public function children(): HasMany
    {
        return $this->hasMany(OverheidTheme::class, 'parent_id');
    }

    /**
     * Get all documents for this theme.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(OverheidDocument::class, 'overheid_theme_id');
    }
}
