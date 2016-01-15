<?php

use Illuminate\Database\Schema\Blueprint;
use Launch\Migration;

class ContentCreateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create content table
		Schema::create('content', function ($table) {
			$table->increments('id');
			$table->string('title');
			$table->text('body');
			$table->integer('account_id');
			$table->integer('content_type_id');
			$table->integer('user_id');
			$table->string('buying_stage')->nullable();
			$table->string('persona')->nullable();
			$table->integer('campaign_id')->nullable();
			$table->string('secondary_buying_stage')->nullable();
			$table->string('secondary_persona')->nullable();
      $table->text('concept')->nullable();
      $table->integer('status')->default(0);
      $table->boolean('archived')->default(false);
			$table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP')); $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
		});

		// Create content_type table
		Schema::create('content_types', function ($table) {
			$table->increments('id');
			$table->string('key');
			$table->string('name');
		});

		// Insert content types
		foreach ([
      'audio-recording' => 'Audio Recording',
      'blog-post' => 'Blog Post',
      'casestudy' => 'Case Study',
      'ebook' => 'Ebook',
      'email' => 'Email',
      'facebook-post' => 'Facebook Post',
      'feature-article' => 'Feature Length Article',
      'google-drive-doc' => 'Google Drive Doc.',
      'google-plus-update' => 'Google+ Update',
      'newsletter' => 'Newsletter',
      'landing-page' => 'Landing Page',
      'linkedin-update' => 'Linkedin Update',
      'photo' => 'Photo',
      'salesforce-asset' => 'SalesForce Asset',
      'sales-letter' => 'Sales Letter',
      'sellsheet-content' => 'Sell Sheet Content',
      'tweet' => 'Tweet',
      'video' => 'Video',
      'website-page' => 'Website Page',
      'whitepaper' => 'Whitepaper',
      'workflow-email' => 'Workflow Email'
    ] as $key => $name) {
      $type = new ContentType;
      $type->key = $key;
      $type->name = $name;
      $type->save();
      $this->note('Created content type: '. $type->key .': '. $type->name);
    }

		Schema::create('content_tags', function ($table) {
			$table->increments('id');
			$table->integer('content_id');
			$table->string('tag');
			$table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP')); $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
		});

		Schema::create('content_related', function ($table) {
			$table->increments('id');
			$table->integer('content_id');
			$table->integer('related_content_id');
			$table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP')); $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
		});

    Schema::create('content_collaborators', function ($table) {
      $table->increments('id');
      $table->integer('content_id');
      $table->integer('user_id');
      $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP')); $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
    });

		Schema::create('content_comments', function ($table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('content_id');
			$table->text('comment');
			$table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP')); $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
		});

    Schema::create('content_account_connections', function ($table) {
      $table->increments('id');
      $table->integer('content_id');
      $table->integer('account_connection_id');
      $table->string('external_id');
      $table->integer('likes');
      $table->integer('shares');
      $table->integer('comments');
      $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP')); $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Delete tables
		Schema::drop('content_types');
		Schema::drop('content_tags');
		Schema::drop('content_related');
    Schema::drop('content_collaborators');
		Schema::drop('content_comments');
    Schema::drop('content_account_connections');
		Schema::drop('content');
	}

}
