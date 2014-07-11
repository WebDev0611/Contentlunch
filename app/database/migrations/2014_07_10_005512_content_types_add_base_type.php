<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentTypesAddBaseType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    // Add social media post
    DB::table('content_types')->insert([
      'key' => 'social-media-post',
      'name' => 'Social Media Post'
    ]);
    // Add survey
    DB::table('content_types')->insert([
      'key' => 'survey',
      'name' => 'Survey'
    ]);
		Schema::table('content_types', function ($table) {
      $table->string('base_type')->nullable();
    });
    // Update existing content_types with their base_type
    $updates = [
      'attached_file' => ['ebook', 'google-drive-doc', 'salesforce-asset'],
      'audio' => ['audio-recording'],
      'blog_post' => ['blog-post'],
      'document' => ['casestudy', 'feature-article', 'newsletter', 'sales-letter', 'survey', 'sellsheet-content', 'whitepaper'],
      'email' => ['email', 'workflow-email'],
      'long_html' => ['landing-page', 'website-page'],
      'photo' => ['photo'],
      'social_media_post' => ['social-media-post', 'facebook-post', 'google-plus-update', 'linkedin-update', 'tweet'],
      'video' => ['video']
    ];
    foreach ($updates as $base_type => $keys) {
      foreach ($keys as $key) {
        DB::table('content_types')
          ->where('key', $key)
          ->update([
            'base_type' => $base_type
          ]);
      }
    }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
