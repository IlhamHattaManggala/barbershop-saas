<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        try {
            $setting = static::where('key', $key)->first();

            return $setting ? $setting->value : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function set($key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function getEncrypted($key, $default = null)
    {
        $val = static::get($key);
        if (! $val) {
            return $default;
        }
        try {
            return Crypt::decryptString($val);
        } catch (\Throwable $e) {
            return $val;
        }
    }

    public static function setEncrypted($key, $value)
    {
        $encrypted = (! is_null($value) && $value !== '') ? Crypt::encryptString($value) : null;

        return static::set($key, $encrypted);
    }
}
