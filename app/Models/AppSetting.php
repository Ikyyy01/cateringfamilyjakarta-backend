<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'label', 'description'];

    // Ambil nilai setting berdasarkan key
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    // Set/update nilai setting
    public static function set(string $key, $value): void
    {
        static::where('key', $key)->update(['value' => $value]);
    }
}
