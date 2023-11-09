<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCanDeleteToChFolders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ch_folders', function (Blueprint $table) {
            $table->boolean('delete')->default(false)->after('sort');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ch_folders', function (Blueprint $table) {
            $table->dropColumn('delete');
        });
    }
}
