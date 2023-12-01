<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChGroupInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ch_group_invites', function (Blueprint $table) {
            $table->id();
            $table->integer('chat_id');
            $table->integer('user_id');
            $table->boolean('seen');
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
        Schema::dropIfExists('ch_group_invites');
    }
}
