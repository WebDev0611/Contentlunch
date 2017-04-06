<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdeaTaskToCalendarTable extends Migration
{
    /**
     * Run the migrations..
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calendar', function (Blueprint $table) {
            $table->boolean('show_ideas')->after('name')->default(1);
            $table->boolean('show_tasks')->after('show_ideas')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calendar', function (Blueprint $table) {
            $table->dropColumn(['show_ideas', 'show_tasks']);
        });
    }
}
