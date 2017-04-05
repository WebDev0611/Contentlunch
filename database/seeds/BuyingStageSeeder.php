<?php

class BuyingStageSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        App\BuyingStage::truncate();
        factory(App\BuyingStage::class, 5)->create();

        $this->enableForeignKeys();
    }
}
