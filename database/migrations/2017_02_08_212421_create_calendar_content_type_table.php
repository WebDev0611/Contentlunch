<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarContentTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_content_type', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('calendar_id')->unsigned();
            $table->integer('content_type_id')->unsigned();
            $table->timestamps();

            $table->foreign('calendar_id')
                ->references('id')
                ->on('calendar')
                ->onDelete('cascade');

            $table->foreign('content_type_id')
                ->references('id')
                ->on('content_types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('calendar_content_type');
    }
}
