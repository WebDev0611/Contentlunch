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
      $table->string('first_name', 100)->nullable();
      $table->string('last_name', 100)->nullable();
      $table->string('address')->nullable();
      $table->string('address_2')->nullable();
      $table->string('city')->nullable();
      $table->string('state')->nullable();
      $table->string('country')->nullable();
      $table->string('phone')->nullable();
      $table->integer('image')->nullable();
      $table->integer('status')->default(0);
      $table->string('title')->nullable();
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

    // Insert the admin super user
    $user = new User([
      'username' => 'admin@test.com',
      'email' => 'admin@test.com',
      'password' => 'launch123',
      'password_confirmation' => 'launch123',
      'first_name' => 'Admin', 
      'last_name' => 'Istrator', 
      'address' => '123 SW 321', 
      'city' => 'Spokane', 
      'state' => 'WA', 
      'country' => 'US', 
      'phone' => '5096901018', 
      'title' => 'Admin',
      'confirmed' => true,
      'status' => 1
    ]);
    $user->save();
    echo "Created Admin super user: ". $user->username . PHP_EOL;
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
