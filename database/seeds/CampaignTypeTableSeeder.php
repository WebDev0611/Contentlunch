<?php

class CampaignTypeTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        DB::table('campaign_types')->truncate();
        DB::table('campaign_types')->insert([
            [ 'key' => 'awareness-campaign', 'name' => 'Awareness campaign' ],
            [ 'key' => 'product-launch', 'name' => 'Product launch' ],
            [ 'key' => 'seo-campaign', 'name' => 'SEO campaign' ],
            [ 'key' => 'branding-campaign', 'name' => 'Branding campaign' ],
            [ 'key' => 'webinar-campaign', 'name' => 'Webinar campaign' ],
            [ 'key' => 'trade-show', 'name' => 'Trade show' ],
        ]);

        $this->enableForeignKeys();
    }
}
