<?php

use Illuminate\Database\Seeder;

class ContentTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	$contentTypeArray =[
		['audio-recording' , 'Audio Recording'],
		['blog-post' , 'Blog Post'],
		['casestudy' , 'Case Study'],
		['ebook' , 'Ebook'],
		['email' , 'Email'],
		['facebook-post' , 'Facebook Post'],
		['feature-article' , 'Feature Length Article'],
		['google-drive-doc' , 'Google Drive Doc.'],
		['google-plus-update' , 'Google+ Update'],
		['newsletter' , 'Newsletter'],
		['landing-page' , 'Landing Page'],
		['linkedin-update' , 'Linkedin Update'],
		['photo' , 'Photo'],
		['salesforce-asset' , 'SalesForce Asset'],
		['sales-letter' , 'Sales Letter'],
		['sellsheet-content' , 'Sell Sheet Content'],
		['tweet' , 'Tweet'],
		['video' , 'Video'],
		['website-page' , 'Website Page'],
		['whitepaper' , 'Whitepaper'],
		['workflow-email' , 'Workflow Email']
	];

	foreach ($contentTypeArray as $type) {
	      $ct = new \App\ContentType;
	      $ct->name = $type[1];
	      $ct->save();
	}
    }
}
