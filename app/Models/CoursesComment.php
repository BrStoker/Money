<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoursesComment extends Model
{
    use CrudTrait;
    use HasFactory;
    use \App\Traits\Model;

    protected $table = 'courses_comments';

    public function childrens() {
        return $this->hasMany(CoursesComment::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
