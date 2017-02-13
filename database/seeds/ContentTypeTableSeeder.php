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
        DB::table('content_types')->truncate();
        DB::table('content_types')->insert([
            [ 'name' => 'Blog Post',              'provider_id' =>  1, 'active' => true ],
            [ 'name' => 'Facebook Post',          'provider_id' =>  5, 'active' => true ],
            [ 'name' => 'Tweet',                  'provider_id' =>  6, 'active' => true ],
            [ 'name' => 'HubSpot Blog Post',      'provider_id' =>  3, 'active' => true ],
            [ 'name' => 'Email',                  'provider_id' =>  0, 'active' => true ],
            [ 'name' => 'Website Page',           'provider_id' =>  0, 'active' => true ],

            [ 'name' => 'Audio Recording',        'provider_id' =>  0, 'active' => false ],
            [ 'name' => 'Case Study',             'provider_id' =>  0, 'active' => false ],
            [ 'name' => 'Ebook',                  'provider_id' =>  0, 'active' => false ],
            [ 'name' => 'Feature Length Article', 'provider_id' =>  0, 'active' => false ],
            [ 'name' => 'Google Drive Doc.',      'provider_id' =>  0, 'active' => false ],
            [ 'name' => 'Google+ Update',         'provider_id' =>  0, 'active' => false ],
            [ 'name' => 'Newsletter',             'provider_id' =>  0, 'active' => false ],
            [ 'name' => 'Landing Page',           'provider_id' =>  0, 'active' => false ],
            [ 'name' => 'Linkedin Update',        'provider_id' =>  0, 'active' => false ],
            [ 'name' => 'Photo',                  'provider_id' =>  0, 'active' => false ],
            [ 'name' => 'SalesForce Asset',       'provider_id' =>  0, 'active' => false ],
            [ 'name' => 'Sales Letter',           'provider_id' =>  0, 'active' => false ],
            [ 'name' => 'Sell Sheet Content',     'provider_id' =>  0, 'active' => false ],
            [ 'name' => 'Video',                  'provider_id' =>  0, 'active' => false ],
            [ 'name' => 'Whitepaper',             'provider_id' =>  0, 'active' => false ],
            [ 'name' => 'Workflow Email',         'provider_id' =>  0, 'active' => false ],
        ]);
    }
}
