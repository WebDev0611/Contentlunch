<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentAccountConnectionAddViewsAndDownloads extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('content_account_connections', function ($table) {
            $table->integer('views')->default(0)->after('comments');
            $table->integer('downloads')->default(0)->after('views');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('content_account_connections', function ($table) {
            $table->dropColumn('downloads');
            $table->dropColumn('views');
        });
	}

}
