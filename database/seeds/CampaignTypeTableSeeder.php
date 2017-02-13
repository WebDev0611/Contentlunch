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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('campaign_types')->truncate();
        DB::table('campaign_types')->insert([
            [ 'key' => 'awareness-campaign', 'name' => 'Awareness campaign' ],
            [ 'key' => 'product-launch', 'name' => 'Product launch' ],
            [ 'key' => 'seo-campaign', 'name' => 'SEO campaign' ],
            [ 'key' => 'branding-campaign', 'name' => 'Branding campaign' ],
            [ 'key' => 'webinar-campaign', 'name' => 'Webinar campaign' ],
            [ 'key' => 'trade-show', 'name' => 'Trade show' ],
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
