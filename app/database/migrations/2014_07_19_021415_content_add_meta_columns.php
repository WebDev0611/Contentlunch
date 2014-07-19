<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentAddMetaColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('content', function ($table) {
      $table->text('meta_description')->nullable();
      $table->text('meta_keywords')->nullable();
    });
    // Change body to allow null
    DB::raw("ALTER TABLE content CHANGE COLUMN body body text NULL");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
    Schema::table('content', function ($table) {
		  $table->dropColumn('meta_description');
      $table->dropColumn('meta_keywords');
    });
	}

}
