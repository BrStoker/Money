<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CoursesSubject extends Model
{
    use CrudTrait;
    use HasFactory;
    use \App\Traits\Model;

    protected $table = 'courses_subject';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {

            $model->slug = Str::slug($model->title);

        });
    }
}
