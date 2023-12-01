<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(false);
            $table->integer('sort')->default(500);
            $table->string('slug')->default(500);
            $table->string('courses_type_id');
            $table->integer('courses_subject_id');
            $table->integer('user_id');
            $table->text('image')->nullable();
            $table->string('title');
            $table->text('preview')->nullable();
            $table->longText('detail_text')->nullable();
            $table->integer('price');
            $table->integer('score')->default(0);
            $table->boolean('free')->default(false);
            $table->string('link')->nullable();
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
        Schema::dropIfExists('courses');
    }
}
