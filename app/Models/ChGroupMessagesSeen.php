<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChGroupMessagesSeen extends Model
{
    use HasFactory;

    protected $table = 'ch_group_messages_seen';

    public function message(){
        return $this->belongsTo(ChGroupMessages::class);
    }


//    protected static function boot() {
//
//        parent::boot();
//
//        static::saved(function($entity){
//            static::viewsRefresh($entity);
//        });
//
//    }
//
//    protected static function viewsRefresh($entity){
//
//
//        if($entity->seen == 1){
//            if($entity->message) {
//
//                $entity->message->view = ($entity->article->scores()->sum('value') / $entity->article->scores()->count());
//                $entity->article->save();
//                $entity->article->refresh();
//
//            }
//        }
//
//    }
}
