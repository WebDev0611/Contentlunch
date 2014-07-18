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

            DB::raw("ALTER TABLE brainstorms CHANGE COLUMN agenda agenda text NULL");
            DB::raw("ALTER TABLE brainstorms CHANGE COLUMN description description text NULL");
            
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
        });
	}

}
