<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleView extends Model
{
    use HasFactory;
    //use SoftDeletes;
    use \App\Traits\Model;

    protected $table = 'article_view';

    protected function article() {
        return $this->belongsTo(Article::class);
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

        if($entity->article) {

            $entity->article->view = $entity->article->viewed()->count();
            $entity->article->save();
            $entity->article->refresh();
            
        }

    }

}
