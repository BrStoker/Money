<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReplyIdToChGroupMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ch_group_messages', function (Blueprint $table) {
            $table->integer('reply_id')->nullable()->after('audio_file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ch_group_messages', function (Blueprint $table) {
            $table->dropColumn('reply_id');
        });
    }
}
