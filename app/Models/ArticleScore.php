<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleScore extends Model
{
    use HasFactory;
    //use SoftDeletes;
    use \App\Traits\Model;

    protected $table = 'article_score';

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

            $entity->article->score = ($entity->article->scores()->sum('value') / $entity->article->scores()->count());
            $entity->article->save();
            $entity->article->refresh();
            
        }

    }

}
