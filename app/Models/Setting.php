<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','value'];

    /**
     * @param $name
     */
    public static function get($name)
    {
        $setting = new self();
        $entry = $setting->where('name', $name)->first();
        if (!$entry) {
            return;
        }
        return $entry->value;
    }

    /**
     * @param $name
     * @param null $value
     * @return bool
     */
    public static function set($name, $value = null)
    {
        self::updateOrInsert(['name' => $name],['name' => $name,'value' => $value]);
        Config::set('name', $value);
        if (Config::get($name) == $value) {
            return true;
        }
        return false;
    }
}
