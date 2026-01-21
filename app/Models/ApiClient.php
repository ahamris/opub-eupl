<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;

class ApiClient extends Model
{
    protected $fillable = [
        'name',
        'key_prefix',
        'api_key_hash',
        'allowed_domains',
        'is_active',
        'last_used_at',
        'last_used_ip',
        'last_used_user_agent',
    ];

    protected function casts(): array
    {
        return [
            'allowed_domains' => AsArrayObject::class,
            'is_active' => 'boolean',
            'last_used_at' => 'datetime',
        ];
    }
}
