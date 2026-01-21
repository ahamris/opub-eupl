<?php

namespace App\Models\Ooori;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OverheidOrganisation extends Model
{
    protected $connection = 'pgsql2';
    protected $table = 'overheid_organisations';

    protected $fillable = [
        'name',
        'visible_name'
    ];

    /**
     * Get all documents for this organisation.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(OverheidDocument::class, 'overheid_organisation_id');
    }
}
