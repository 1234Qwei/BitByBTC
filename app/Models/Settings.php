<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    public static function findOrCreate($id)
    {
        $obj = static::find($id);
        return $obj ?: new static;
    }

    public static function getKeyValue($key)
    {
        $settings = static::where('config_key', $key)->first();
        return $settings->config_value ?? 0;
    }
}
