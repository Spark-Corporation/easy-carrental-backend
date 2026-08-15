<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|setting where($value)
 * /**
 */


class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = 0)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function getNumeric(string $key, $default = 0): float
    {
        $setting = static::where('key', $key)->first();
        return $setting ? floatval($setting->value) : $default;
    }

    public static function getBoolean(string $key, $default = false): bool
    {
        $setting = static::where('key', $key)->first();
        return $setting ? filter_var($setting->value, FILTER_VALIDATE_BOOLEAN) : $default;
    }
}
