<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewArticleCommentIdToArticleCommentAction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_comment_action', function (Blueprint $table) {
            $table->bigInteger('article_comment_id')->after('article_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_comment_action', function (Blueprint $table) {
            $table->dropColumn('article_comment_id');
        });
    }
}
