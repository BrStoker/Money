<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleValue extends Model
{
    use HasFactory;
    //use SoftDeletes;
    use \App\Traits\Model;

    protected $table = 'article_value';

}
