<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConversionsToContentAccountConnections extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('content_account_connections', function(Blueprint $table){
            $table->integer('conversions')->default(0)->after('downloads');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('content_account_connections', function(Blueprint $table){
            $table->dropColumn('conversions');
        });
	}

}
