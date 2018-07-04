<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdeaContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idea_content', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('idea_id')->unsigned();
            $table->string('author');
            $table->mediumText('body');
            $table->string('fb_shares');
            $table->string('google_shares');
            $table->string('image');
            $table->mediumText('link');
            $table->mediumText('source');
            $table->mediumText('title');
            $table->string('total_shares');
            $table->string('tw_shares');
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
        Schema::drop('idea_content');
    }
}
