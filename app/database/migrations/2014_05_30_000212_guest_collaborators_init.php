<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GuestCollaboratorsInit extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_collaborators', function (Blueprint $table) {
            $table->increments('id');

            // provider_user_id could be just 
            // about anything (connection dependent)
            $table->string('connection_user_id');
            $table->string('name');
            $table->boolean('accepted')->default(false);

            $table->integer('connection_id')->unsigned();
            // probably won't ever delete the connection, but...
            $table->foreign('connection_id')->references('id')->on('connections')->onDelete('cascade');

            $table->integer('content_id')->unsigned();
            $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade');

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
        Schema::drop('guest_collaborators');
    }

}
