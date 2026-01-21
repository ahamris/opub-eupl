<?php

namespace App\Models\Ooori;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OverheidCategory extends Model
{
    protected $table = 'overheid_categories';

    protected $fillable = [
        'name',
        'visible_name',
    ];

    /**
     * Get all documents for this category.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(OverheidDocument::class, 'overheid_category_id');
    }
}
