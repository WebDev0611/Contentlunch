<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixUniqueIndicesOnMeasureTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('measure_launched_content', function($table) {
            $table->dropUnique('measure_launched_content_date_unique');
            $table->unique(['account_id', 'date']);
        });

        Schema::table('measure_created_content', function($table) {
            $table->dropUnique('measure_created_content_date_unique');
            $table->unique(['account_id', 'date']);
        });

        Schema::table('measure_timing_content', function($table) {
            $table->dropUnique('measure_timing_content_date_unique');
            $table->unique(['account_id', 'date']);
        });

        Schema::table('measure_content_score', function($table) {
            $table->dropUnique('measure_content_score_date_unique');
            $table->unique(['account_id', 'date']);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('measure_launched_content', function($table) {
            $table->dropUnique('measure_launched_content_account_id_date_unique');
            $table->unique('date');
        });

        Schema::table('measure_created_content', function($table) {
            $table->dropUnique('measure_created_content_account_id_date_unique');
            $table->unique('date');
        });

        Schema::table('measure_timing_content', function($table) {
            $table->dropUnique('measure_timing_content_account_id_date_unique');
            $table->unique('date');
        });

        Schema::table('measure_content_score', function($table) {
            $table->dropUnique('measure_content_score_account_id_date_unique');
            $table->unique('date');
        });
	}

}
