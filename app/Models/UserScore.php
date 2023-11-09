<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserScore extends Model
{
    use HasFactory;
    //use SoftDeletes;
    use \App\Traits\Model;

    protected $table = 'user_score';

    protected function user() {
        return $this->belongsTo(User::class);
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

    protected static function scoresRefresh($entity) {

        if($entity->user) {

            $entity->user->score = ($entity->user->scores()->sum('value') / $entity->user->scores()->count());
            $entity->user->save();
            $entity->user->refresh();
            
        }

    }

}
