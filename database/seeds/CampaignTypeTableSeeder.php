<?php

use Illuminate\Database\Seeder;

class CampaignTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('campaign_types')->truncate();
        DB::table('campaign_types')->insert([
            ['key' => 'audio-recording', 'name' => 'Audio Recording'],
            ['key' => 'blog-post', 'name' => 'Blog Post'],
            ['key' => 'casestudy', 'name' => 'Case Study'],
            ['key' => 'ebook', 'name' => 'Ebook'],
            ['key' => 'email', 'name' => 'Email'],
            ['key' => 'facebook-post', 'name' => 'Facebook Post'],
            ['key' => 'feature-article', 'name' => 'Feature Length Article'],
            ['key' => 'google-drive-doc', 'name' => 'Google Drive Doc.'],
            ['key' => 'google-plus-update', 'name' => 'Google+ Update'],
            ['key' => 'newsletter', 'name' => 'Newsletter'],
            ['key' => 'landing-page', 'name' => 'Landing Page'],
            ['key' => 'linkedin-update', 'name' => 'Linkedin Update'],
            ['key' => 'photo', 'name' => 'Photo'],
            ['key' => 'salesforce-asset', 'name' => 'SalesForce Asset'],
            ['key' => 'sales-letter', 'name' => 'Sales Letter'],
            ['key' => 'sellsheet-content', 'name' => 'Sell Sheet Content'],
            ['key' => 'tweet', 'name' => 'Tweet'],
            ['key' => 'video', 'name' => 'Video'],
            ['key' => 'website-page', 'name' => 'Website Page'],
            ['key' => 'whitepaper', 'name' => 'Whitepaper'],
            ['key' => 'workflow-email', 'name' => 'Workflow Email']
        ]);
    }
}
