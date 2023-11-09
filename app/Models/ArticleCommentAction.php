<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCommentAction extends Model
{
    use HasFactory;
    use SoftDeletes;
    use \App\Traits\Model;

    protected $table = 'article_comment_action';

    protected $hidden = [ ];

}
