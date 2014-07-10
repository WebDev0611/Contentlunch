<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentAddDateColumns extends Migration {

  protected $columns = [
    'convert_date', 'submit_date', 'approval_date',
    'launch_date', 'promote_date', 'archive_date'
  ];

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('content', function ($table) {
      foreach ($this->columns as $column) {
        $table->timestamp($column)->nullable();  
      }
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('content', function ($table) {
      foreach ($this->columns as $column) {
        $table->dropColumn($column);
      }
    });
	}

}
