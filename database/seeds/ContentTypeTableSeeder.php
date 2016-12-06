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
            [ 'name' => 'Blog Post',                'provider_id' =>  1 ],
            [ 'name' => 'Facebook Post',            'provider_id' =>  5 ],
            [ 'name' => 'Tweet',                    'provider_id' =>  6 ],
            [ 'name' => 'Audio Recording',          'provider_id' =>  0 ],
            [ 'name' => 'Case Study',               'provider_id' =>  0 ],
            [ 'name' => 'Ebook',                    'provider_id' =>  0 ],
            [ 'name' => 'Email',                    'provider_id' =>  0 ],
            [ 'name' => 'Feature Length Article',   'provider_id' =>  0 ],
            [ 'name' => 'Google Drive Doc.',        'provider_id' =>  0 ],
            [ 'name' => 'Google+ Update',           'provider_id' =>  0 ],
            [ 'name' => 'Newsletter',               'provider_id' =>  0 ],
            [ 'name' => 'Landing Page',             'provider_id' =>  0 ],
            [ 'name' => 'Linkedin Update',          'provider_id' =>  0 ],
            [ 'name' => 'Photo',                    'provider_id' =>  0 ],
            [ 'name' => 'SalesForce Asset',         'provider_id' =>  0 ],
            [ 'name' => 'Sales Letter',             'provider_id' =>  0 ],
            [ 'name' => 'Sell Sheet Content',       'provider_id' =>  0 ],
            [ 'name' => 'Video',                    'provider_id' =>  0 ],
            [ 'name' => 'Website Page',             'provider_id' =>  0 ],
            [ 'name' => 'Whitepaper',               'provider_id' =>  0 ],
            [ 'name' => 'Workflow Email',           'provider_id' =>  0 ],
        ]);
    }
}
