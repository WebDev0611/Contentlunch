<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCalendarIdToIdeaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('idea', function (Blueprint $table) {
            $table->integer('calendar_id')->nullable()->unsigned();

            $table->foreign('calendar_id')
                ->references('id')
                ->on('calendar')
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
        Schema::table('idea', function (Blueprint $table) {
            $table->dropForeign(['calendar_id']);
            $table->dropColumn('calendar_id');
        });
    }
}