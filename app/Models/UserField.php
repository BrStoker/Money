<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserField extends Model
{
    use CrudTrait;
    use HasFactory;
    //use SoftDeletes;
    use \App\Traits\Model;

    protected $table = 'user_field';

    public function groups()
    {
        return $this->hasMany(UserFieldGroup::class);
    }

    public function values()
    {
        return $this->hasMany(UserFieldValue::class);
    }

    public static function boot()
    {

        parent::boot();
        
    }

   

}