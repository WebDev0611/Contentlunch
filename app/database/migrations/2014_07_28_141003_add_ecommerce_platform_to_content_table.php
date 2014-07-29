<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEcommercePlatformToContentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('content', function($table)
        {
            $table->string('ecommerce_platform', 100)->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('content', function($table)
        {
            $table->dropColumn('ecommerce_platform');
        });
	}

}
