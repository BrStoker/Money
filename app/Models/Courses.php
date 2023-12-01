<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Courses extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;
    use \App\Traits\Model;

    protected $table = 'courses';

    protected $casts = ['courses_type_id' => 'json'];

    public function comments() {
        return $this->hasMany(CoursesComment::class);
    }

    public function scores()
    {
        return $this->hasMany(CourseScore::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function setImageAttribute($value)
    {

        if($value)
        {

            $attribute_name = "image";

            $disk = "public";

            $destination_path = "images";

            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

        }

    }

    public static function boot()
    {

        parent::boot();

        static::deleting(function($obj) {

            \Storage::disk('public')->delete($obj->image);

        });

        static::saving(function ($model) {

            $model->slug = Str::slug($model->title);

        });

    }

}
