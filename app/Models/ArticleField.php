<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleField extends Model
{
    use CrudTrait;
    use HasFactory;
    //use SoftDeletes;
    use \App\Traits\Model;

    protected $table = 'article_field';

    public function groups()
    {
        return $this->hasMany(ArticleFieldGroup::class);
    }

    public function values()
    {
        return $this->hasMany(ArticleFieldValue::class);
    }

}
