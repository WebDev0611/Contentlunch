<?php

use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Campaign::truncate();
        factory(App\Campaign::class, 10)->create();
    }
}
