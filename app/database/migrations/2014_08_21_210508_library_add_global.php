<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LibraryAddGlobal extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// This should have run in a previous migration...
		// not sure why it's gone now
		$library = Library::where('global', 1)->first();
		if ( ! $library) {
			$user = User::first();
		    $library = new Library([
		      'global' => true,
		      'name' => 'Global Content Launch Files',
		      'description' => 'Global Content Launch Files',
		      'user_id' => $user->id
		    ]);
		    $library->save();
		}
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
