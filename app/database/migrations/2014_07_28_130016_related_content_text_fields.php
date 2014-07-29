<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RelatedContentTextFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('content_related', function($table)
        {
            $table->dropColumn('related_content_id');
            $table->text('related_content')->after('content_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('content_related', function($table)
        {
            $table->dropColumn('related_content');
            $table->integer('related_content_id');
        });
	}

}
