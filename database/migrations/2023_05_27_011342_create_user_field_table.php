<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_field', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('active')->default(true);
            $table->integer('sort')->default(500);
            $table->integer('user_field_group_id')->nullable();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('type');
            $table->tinyInteger('multiply')->nullable();
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
        Schema::dropIfExists('user_field');
    }
}
