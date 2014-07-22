<?php

use Illuminate\Database\Schema\Blueprint;
use Launch\Migration;

class ContentTypesUpdate extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Updated content types list file
    $this->syncContentTypes();
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
