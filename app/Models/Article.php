<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model {

    use CrudTrait;
    use HasFactory;
    use SoftDeletes;
    use \App\Traits\Model;

    protected $table = 'article';

    protected $casts = ['article_group_ids' => 'json'];

    protected $hidden = [ ];

    public function comments() {
        return $this->hasMany(ArticleComment::class);
    }

    public function scores()
    {
        return $this->hasMany(ArticleScore::class);
    }

    public function favorites()
    {
        return $this->hasMany(ArticleFavorite::class);
    }

    public function viewed()
    {
        return $this->hasMany(ArticleView::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function values()
    {
        return $this->hasMany(ArticleValue::class);
    }

    public function setImageAttribute($value)
    {
        
        if($value)
        {

            $attribute_name = "image";

            $disk = "public";

            $destination_path = "images";

            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

        }

    }

    public static function boot()
    {

        parent::boot();

        static::deleting(function($obj) {

            \Storage::disk('public')->delete($obj->image);
            
        });



    }


}
