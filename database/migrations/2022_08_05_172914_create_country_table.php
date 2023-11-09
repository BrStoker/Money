<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryTable extends Migration
{

    public function up()
    {
        Schema::create('country', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('country');
    }
}
