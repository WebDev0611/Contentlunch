<?php

use Illuminate\Database\Seeder;
use App\CampaignType;

class CampaignTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CampaignType::truncate();

        $campaignTypeArray = [
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

        foreach ($campaignTypeArray as $type) {
            $ct = new CampaignType;
            $ct->key = $type[0];
            $ct->name = $type[1];
            $ct->save();
        }
    }
}
