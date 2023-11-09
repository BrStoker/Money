<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use CrudTrait;
    use \App\Traits\Model;
    //use SoftDeletes;

    protected $table = 'country';

}
