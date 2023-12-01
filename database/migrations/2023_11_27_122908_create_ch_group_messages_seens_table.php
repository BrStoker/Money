<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChGroupMessagesSeensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ch_group_messages_seen', function (Blueprint $table) {
            $table->id();
            $table->integer('ch_group_messages_id');
            $table->integer('user_id');
            $table->tinyInteger('seen');
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
        Schema::dropIfExists('ch_group_messages_seen');
    }
}
