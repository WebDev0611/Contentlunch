<?php

class ContentTypeTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        DB::table('content_types')->truncate();
        $this->insertContentTypes();

        $this->enableForeignKeys();
    }

    public function insertContentTypes()
    {
        DB::table('content_types')->insert([
            [ 'name' => 'Blog Post',              'provider_id' =>  1, 'active' => true, 'order' => 1 ],
            [ 'name' => 'Facebook Post',          'provider_id' =>  5, 'active' => true, 'order' => 6 ],
            [ 'name' => 'Tweet',                  'provider_id' =>  6, 'active' => true, 'order' => 7 ],
            [ 'name' => 'HubSpot Blog Post',      'provider_id' =>  3, 'active' => true, 'order' => 2 ],
            [ 'name' => 'Email',                  'provider_id' =>  0, 'active' => true, 'order' => 5 ],
            [ 'name' => 'Website Page',           'provider_id' =>  0, 'active' => true, 'order' => 3 ],
            [ 'name' => 'Landing Page',           'provider_id' =>  0, 'active' => true, 'order' => 4 ],
            [ 'name' => 'Linkedin Update',        'provider_id' =>  2, 'active' => true, 'order' => 8 ],
            [ 'name' => 'Custom',                 'provider_id' =>  0, 'active' => true, 'order' => 9 ],
            [ 'name' => 'Ebook',                  'provider_id' =>  0, 'active' => true, 'order' => 10 ],
            [ 'name' => 'White Paper',            'provider_id' =>  0, 'active' => true, 'order' => 11 ],
            [ 'name' => 'Press Release',          'provider_id' =>  0, 'active' => true, 'order' => 12 ],

            [ 'name' => 'Audio Recording',        'provider_id' =>  0, 'active' => false, 'order' => 0 ],
            [ 'name' => 'Case Study',             'provider_id' =>  0, 'active' => false, 'order' => 0 ],
            [ 'name' => 'Feature Length Article', 'provider_id' =>  0, 'active' => false, 'order' => 0 ],
            [ 'name' => 'Google Drive Doc.',      'provider_id' =>  0, 'active' => false, 'order' => 0 ],
            [ 'name' => 'Google+ Update',         'provider_id' =>  0, 'active' => false, 'order' => 0 ],
            [ 'name' => 'Newsletter',             'provider_id' =>  0, 'active' => false, 'order' => 0  ],
            [ 'name' => 'Photo',                  'provider_id' =>  0, 'active' => false, 'order' => 0  ],
            [ 'name' => 'SalesForce Asset',       'provider_id' =>  0, 'active' => false, 'order' => 0  ],
            [ 'name' => 'Sales Letter',           'provider_id' =>  0, 'active' => false, 'order' => 0  ],
            [ 'name' => 'Sell Sheet Content',     'provider_id' =>  0, 'active' => false, 'order' => 0  ],
            [ 'name' => 'Video',                  'provider_id' =>  0, 'active' => false, 'order' => 0  ],
            [ 'name' => 'Workflow Email',         'provider_id' =>  0, 'active' => false, 'order' => 0  ],
        ]);
    }
}
