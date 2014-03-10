<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountSettings2 extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    // Add more settings fields to accounts
    Schema::table('accounts', function($table) {
      $table->string('country')->default('');
      $table->string('zipcode')->default('');
      $table->string('email')->default('');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    // Drop columns
    Schema::table('accounts', function($table) {
      $table->dropColumn('country');
      $table->dropColumn('zipcode');
      $table->dropColumn('email');
    });
  }

}
