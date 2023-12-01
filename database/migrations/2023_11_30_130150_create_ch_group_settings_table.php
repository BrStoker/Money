<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChGroupSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ch_group_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable(false);
            $table->integer('ch_group_id');
            $table->boolean('important')->nullable()->default(0);
            $table->boolean('notifications')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ch_group_settings');
    }
}
