<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(false);
            $table->integer('sort')->default(500);
            $table->string('slug')->default(500);
            $table->text('article_group_ids')->nullable();
            $table->integer('user_id');
            $table->text('image')->nullable();
            $table->string('title');
            $table->text('preview')->nullable();
            $table->longText('detail_text')->nullable();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article');
    }
}
