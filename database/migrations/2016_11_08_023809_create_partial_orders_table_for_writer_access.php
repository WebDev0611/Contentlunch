<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartialOrdersTableForWriterAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('writer_access_partial_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();

            // First form
            $table->string('project_name');
            $table->date('duedate');
            $table->integer('asset_type_id'); // Writer Access Asset ID -- not the IDs in our database.
            $table->integer('wordcount');
            $table->integer('writer_level');

            // Second form
            $table->string('content_title');
            $table->text('instructions');
            $table->string('narrative_voice');
            $table->string('target_audience');
            $table->string('tone_of_writing');

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
        Schema::drop('writer_access_partial_orders');
    }
}
