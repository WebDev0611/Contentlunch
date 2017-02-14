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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        App\Campaign::truncate();
        factory(App\Campaign::class, 10)->create();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
