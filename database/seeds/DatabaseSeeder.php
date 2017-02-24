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
        Model::unguard();

        $this->call('CountrySeeder');
        $this->call('WriterAccessPriceSeeder');
        $this->call('WriterAccessAssetTypeSeeder');
        $this->call('AccountTypeSeeder');
        $this->call('UsersTableSeeder');
        $this->call('BuyingStageSeeder');
        $this->call('ProviderTableSeeder');
        $this->call('ContentTypeTableSeeder');
        $this->call('CampaignTypeTableSeeder');
        $this->call('SubscriptionTypesTableSeeder');

        // Depends on the User Seeder and the Campaign Type Seeder
        $this->call('CampaignSeeder');

        Model::reguard();
    }
}
