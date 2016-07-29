<?php

use Illuminate\Database\Seeder;

class ProviderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	$providersArray = [
	    	['Wordpress', 'wordpress', 'blog', 'WordPressAPI'],
	    	['Linkedin', 'linkedin', 'website',''],
	    	['Hubspot', 'hubspot', 'website',''],
	    	['Act-on', 'act-on', 'website',''],
	    	['Facebook', 'facebook', 'website',''],
	    	['Twitter', 'twitter', 'website',''],
	    	['YouTube', 'youtube', 'website',''],
	    	['Outbrain', 'outbrain', 'website',''],
	    	['Dropbox', 'dropbox', 'website',''],
	    	['Google Drive', 'google-drive', 'website',''],
	    	['Slideshare', 'slideshare', 'website',''],
	    	['Tumblr', 'tumblr', 'website',''],
	    	['Google+', 'google+', 'website',''],
	    	['Soundcloud', 'soundcloud', 'website',''],
	    	['Blogger', 'blogger', 'website',''],
	    	['Google Analytics', 'google-analytics', 'website',''],
	    	['Drupal', 'drupal', 'website',''],
	    	['Salesforce', 'salesforce', 'website',''],
	    	['Nimble', 'nimble', 'website',''],
	    	['Hootsuite', 'hootsuite', 'website',''],
	    	['ReadyTalk', 'readytalk', 'website',''],
	    	['Constant Contact', 'constant-contact', 'website',''],
	    	['Mailchimp', 'mailchimp', 'website',''],
	    	['Exact Target', 'exact-target', 'website',''],
	    	['Vertical Response', 'vertical-response', 'website',''],
	    	['Marketo', 'Marketo', 'website',''],
	    	['Eloqua', 'Eloqua', 'website',''],
	    	['Joomla', 'joomla', 'website',''],
	    	['SmartShoot', 'smartshoot', 'website',''],
	    	['SharpSpring', 'sharpspring', 'website',''],
	    	['Orbtr', 'orbtr', 'website','']
	];

	foreach ($providersArray as $data) {
	      $pro = new \App\Provider;
	      $pro->name = $data[0];
	      $pro->slug = $data[1];
	      $pro->type = $data[2];
	      $pro->class_name = $data[3];
	      $pro->save();
	}
    }
}
