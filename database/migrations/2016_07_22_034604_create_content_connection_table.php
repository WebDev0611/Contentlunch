<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentConnectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('provider_id')->unsigned();
            $table->boolean('active')->default(0);
            $table->integer('user_id')->unsigned();
            $table->binary('settings'); // More flexibility when a provider requrest more settings JSON key /pair
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
        Schema::drop('connections');
    }
}
