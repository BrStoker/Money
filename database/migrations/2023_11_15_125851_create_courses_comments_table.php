<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses_comments', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('active')->default(false);
            $table->integer('user_id');
            $table->integer('courses_id');
            $table->integer('courses_comment_id');
            $table->longText('value');
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
        Schema::dropIfExists('courses_comments');
    }
}
