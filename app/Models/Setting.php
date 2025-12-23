<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        '_key',
        '_value',
        'group',
    ];

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('_key', $key)->first();
        return $setting ? $setting->_value : $default;
    }

    /**
     * Set a setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @return Setting
     */
    public static function set(string $key, $value, string $group = 'general'): Setting
    {
        return static::updateOrCreate(
            ['_key' => $key],
            [
                '_value' => $value,
                'group' => $group,
            ]
        );
    }

    /**
     * Get all settings by group.
     *
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByGroup(string $group)
    {
        return static::where('group', $group)->get();
    }

    /**
     * Get all settings as key-value array.
     *
     * @param string|null $group
     * @return array
     */
    public static function getAll(?string $group = null): array
    {
        $query = static::query();
        
        if ($group) {
            $query->where('group', $group);
        }
        
        return $query->pluck('_value', '_key')->toArray();
    }
}
