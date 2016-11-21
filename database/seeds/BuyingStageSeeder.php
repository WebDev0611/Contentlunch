<?php

use Illuminate\Database\Seeder;

class BuyingStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\BuyingStage::truncate();
        factory(App\BuyingStage::class, 5)->create();
    }
}
