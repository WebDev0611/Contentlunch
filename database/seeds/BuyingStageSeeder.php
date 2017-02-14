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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        App\BuyingStage::truncate();
        factory(App\BuyingStage::class, 5)->create();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
