<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnnouncementsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->text('hidden_announcements')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('announcements');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('hidden_announcements');
        });
    }

}
