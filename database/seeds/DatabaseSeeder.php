<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Model::unguard();

        $this->call('CountrySeeder');
        $this->call('WriterAccessPriceSeeder');
        $this->call('WriterAccessAssetTypeSeeder');
        $this->call('UsersTableSeeder');
        $this->call('ProviderTableSeeder');
        $this->call('ContentTypeTableSeeder');
        $this->call('CampaignTypeTableSeeder');

        Model::reguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
