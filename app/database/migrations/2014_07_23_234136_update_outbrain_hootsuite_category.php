<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOutbrainHootsuiteCategory extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::table('connections')->where('provider', 'hootsuite')->update(['category' => 'social']);
        DB::table('connections')->where('provider', 'outbrain')->update(['category' => 'amplification']);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
