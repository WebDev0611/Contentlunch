<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrainstormTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brainstorms', function (Blueprint $table) {
            $table->increments('id');
            $table->text('agenda'); // json array 
            $table->date('date');
            $table->time('time');
            $table->text('description');
            $table->text('credentials');
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
        Schema::drop('brainstorms');
    }

}
