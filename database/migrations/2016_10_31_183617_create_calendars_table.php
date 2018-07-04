<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('calendar', function (Blueprint $table) {

            $table->increments('id')->unsigned(); 

            $table->integer('user_id')->unsigned(); 
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('account_id')->unsigned(); 

            $table->string('name');
            
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
        Schema::drop('calendar');
        //
    }
}
