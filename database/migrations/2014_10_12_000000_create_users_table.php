<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('user_group_id')->default(1);
            $table->boolean('status')->default(0);
            $table->string('sort')->default(500);
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->integer('gender')->default(0);
            $table->date('birthday')->nullable();
            $table->text('image')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('country_id')->nullable();
            $table->string('city_id')->nullable();
            $table->timestamp('last_online_at')->useCurrent();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }

}
