<?php
use Illuminate\Database\Migrations\Migration;

class ConfideSetupUsersTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    // Creates the users table
    Schema::create('users', function($table)
    {
      $table->increments('id');
      $table->string('username');
      $table->string('email');
      $table->string('password');
      $table->string('confirmation_code');
      $table->boolean('confirmed')->default(false);
      $table->string('first_name', 100)->default('');
      $table->string('last_name', 100)->default('');
      $table->string('address')->default('');
      $table->string('address_2')->default('');
      $table->string('city')->default('');
      $table->string('state')->default('');
      $table->string('country')->default('');
      $table->string('phone')->default('');
      $table->integer('image')->nullable();
      $table->integer('status')->default(0);
      $table->string('title')->default('');
      $table->timestamps();
      $table->softDeletes();
    });

    // Creates password reminders table
    Schema::create('password_reminders', function($t)
    {
      $t->string('email');
      $t->string('token');
      $t->timestamp('created_at');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('password_reminders');
    Schema::drop('users');
  }

}
