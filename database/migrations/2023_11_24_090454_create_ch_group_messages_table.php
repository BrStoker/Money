<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChGroupMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ch_group_messages', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id');
            $table->integer('user_id');
            $table->string('body');
            $table->string('attachments');
            $table->timestamps();
            $table->softDeletes('deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ch_group_messages');
    }
}
