<?php

use Illuminate\Database\Seeder;
use App\WriterAccessAssetType;

class WriterAccessAssetTypeSeeder extends Seeder
{

    public function run()
    {
        DB::table('writer_access_asset_types')->truncate();
        DB::table('writer_access_asset_types')->insert([
            ['writer_access_id' => 2, 'name' => 'Blog post'],
            ['writer_access_id' => 3, 'name' => 'Ebook'],
            ['writer_access_id' => 13, 'name' => 'White paper'],
            ['writer_access_id' => 1, 'name' => 'Case Study'],
            ['writer_access_id' => 30, 'name' => 'Email'],
            ['writer_access_id' => 26, 'name' => 'Website Page'],
            ['writer_access_id' => 8, 'name' => 'Press Release'],
            ['writer_access_id' => 31, 'name' => 'Newsletter'],
            ['writer_access_id' => 32, 'name' => 'Product Description'],
            ['writer_access_id' => 9, 'name' => 'Script (Video)'],
            ['writer_access_id' => 10, 'name' => 'Speech']
        ]);
  }

}
