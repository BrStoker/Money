<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CourseScore extends Model
{
    use HasFactory;
    use \App\Traits\Model;

    protected $table = 'course_score';

    protected function course() {
        return $this->belongsTo(Courses::class);
    }

    protected static function boot() {

        parent::boot();

        static::saved(function($entity){
            static::scoresRefresh($entity);
        });

        static::deleting(function($entity) {
            static::scoresRefresh($entity);
        });

    }

    protected static function scoresRefresh($entity){

        if($entity->course) {

            $entity->course->score = ($entity->course->scores()->sum('value') / $entity->courses->scores()->count());
            $entity->course->save();
            $entity->course->refresh();

        }

    }

}
