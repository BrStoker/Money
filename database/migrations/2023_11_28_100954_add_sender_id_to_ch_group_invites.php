<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSenderIdToChGroupInvites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ch_group_invites', function (Blueprint $table) {
            $table->integer('sender_id')->nullable(false)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ch_group_invites', function (Blueprint $table) {
            $table->dropColumn('sender_id');
        });
    }
}
