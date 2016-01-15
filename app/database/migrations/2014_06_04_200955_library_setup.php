<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LibrarySetup extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    // A library is a folder that can contain uploaded files
    // Can be global for all accounts, or specific to an account
    Schema::create('libraries', function ($table) {
      $table->increments('id');
      $table->integer('account_id')->nullable()->references('id')->on('accounts')->onDelete('cascade');
      $table->boolean('global')->default(false);
      $table->string('name');
      $table->text('description')->nullable();
      $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP')); $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
    });

		Schema::create('library_uploads', function ($table) {
      $table->increments('id');
      $table->integer('upload_id')->references('id')->on('uploads')->onDelete('cascade');
      $table->integer('library_id')->references('id')->on('library')->onDelete('cascade');
      $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP')); $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
    });

    Schema::create('upload_ratings', function ($table) {
      $table->increments('id');
      $table->integer('upload_id')->references('id')->on('uploads')->onDelete('cascade');
      $table->integer('rating');
      $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP')); $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
    });

    Schema::create('upload_tags', function ($table) {
      $table->increments('id');
      $table->integer('upload_id')->references('id')->on('uploads')->onDelete('cascade');
      $table->string('tag');
      $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP')); $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
    });

    // Add global library needed for global admin
    $user = User::first();
    $library = new Library([
      'global' => true,
      'name' => 'Global Content Launch Files',
      'description' => 'Global Content Launch Files',
      'user_id' => $user->id
    ]);
    $library->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('library_uploads');
		Schema::drop('upload_ratings');
		Schema::drop('upload_tags');
	   	Schema::drop('libraries');
	}

}
