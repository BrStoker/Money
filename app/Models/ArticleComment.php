<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleComment extends Model
{
    use CrudTrait;
    use HasFactory;
    //use SoftDeletes;
    use \App\Traits\Model;

    protected $table = 'article_comment';

    protected $hidden = [ ];

    public function childrens() {
        return $this->hasMany(ArticleComment::class);
    }

    public function actions() {
        return $this->hasMany(ArticleCommentAction::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

}
