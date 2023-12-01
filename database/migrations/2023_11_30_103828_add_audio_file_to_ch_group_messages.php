<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAudioFileToChGroupMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ch_group_messages', function (Blueprint $table) {
            $table->string('audio_file')->nullable()->after('attachments');
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
            $table->dropColumn('audion_file');
        });
    }
}
