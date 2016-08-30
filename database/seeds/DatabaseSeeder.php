<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(WriterAccessPriceSeeder::class);
        $this->call(WriterAccessAssetTypeSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ProviderTableSeeder::class);
        $this->call(ContentTypeTableSeeder::class);
        $this->call(CampaignTypeTableSeeder::class);
    }
}
