<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserView extends Model
{
    
    use HasFactory;
    //use SoftDeletes;
    use \App\Traits\Model;

    protected $table = 'user_view';

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

            $entity->user->view = $entity->user->viewed()->count();
            $entity->user->save();
            $entity->user->refresh();
            
        }

    }


}
