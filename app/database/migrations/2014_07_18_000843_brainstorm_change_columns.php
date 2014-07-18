<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BrainstormChangeColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('brainstorms', function (Blueprint $table) {
            $table->dropColumn('guest_id');
            $table->dropColumn('credentials');

            $table->dropColumn('agenda');
            $table->dropColumn('description');
            $table->text('agenda')->nullable();
            $table->text('description')->nullable();
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
            $table->integer('guest_id')->nullable();
            $table->text('credentials');
            $table->dropColumn('agenda');
            $table->dropColumn('description');
            $table->text('agenda');
            $table->text('description');
        });
	}

}
