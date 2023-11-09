<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFieldGroup extends Model
{
    use CrudTrait;
    use HasFactory;
    //use SoftDeletes;
    use \App\Traits\Model;

    public function fields()
    {
        return $this->hasMany(UserField::class);
    }

    protected $table = 'user_field_group';

}
