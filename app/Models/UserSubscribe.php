<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSubscribe extends Model
{
    use HasFactory;
    //use SoftDeletes;
    use \App\Traits\Model;

    protected $table = 'user_subscribe';

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

            $entity->user->subscribe = $entity->user->subscribers()->count();
            $entity->user->save();
            $entity->user->refresh();

        }

    }

}
