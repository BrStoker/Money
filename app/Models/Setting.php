<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    
    use CrudTrait;
   // use SoftDeletes;
    use \App\Traits\Model;

    protected $table = 'setting';

    public function groups()
    {
        return $this->hasMany(SettingGroup::class);
    }

    public function values()
    {
        return $this->hasMany(SettingValue::class);
    }

    public static function boot()
    {
        parent::boot();   
    }

}
