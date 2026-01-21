<?php

namespace App\Models\Ooori;

use Illuminate\Database\Eloquent\Model;

class OverheidStat extends Model
{
    protected $connection = 'pgsql2';
    protected $table = 'overheid_stats';

    protected $fillable = [
        'harvesting_date',
        'typesense_synced_count',
        'documents_count',
    ];

    protected $casts = [
        'harvesting_date' => 'date',
        'typesense_synced_count' => 'integer',
        'documents_count' => 'integer',
    ];
}
