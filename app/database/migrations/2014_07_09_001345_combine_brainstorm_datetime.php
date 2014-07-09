<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CombineBrainstormDatetime extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brainstorms', function (Blueprint $table) {
            $table->datetime('datetime')->after('agenda');
            $table->dropColumn('date');
            $table->dropColumn('time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brainstorms', function (Blueprint $table) {
            $table->dropColumn('datetime');
            $table->date('date')->after('agenda');
            $table->time('time')->after('agenda');
        });
    }

}
