<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixUploadViewsUniqueIndex extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('upload_views', function(Blueprint $table){
            $table->dropUnique('user_id');

            $table->unique(['user_id', 'upload_id']);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

        Schema::table('upload_views', function(Blueprint $table){
            $table->dropUnique('upload_views_user_id_upload_id_unique');

            $table->unique('upload_id', 'user_id');
        });
	}

}
