<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCityTable extends Migration
{

    public function up()
    {
        Schema::create('city', function (Blueprint $table) {
            $table->id();
            $table->integer('country_id');
            $table->integer('region_id');
            $table->string('value')->unique();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('city');
    }
}
