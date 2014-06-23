<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumThreadRepliesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_thread_replies', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('forum_thread_id')->unsigned();
            $table->foreign('forum_thread_id')->references('id')->on('forum_threads')->onDelete('cascade');

            $table->integer('account_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->text('body');

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
        Schema::drop('forum_thread_replies');
    }

}
