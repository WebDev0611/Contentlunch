<?php

class CampaignSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        App\Campaign::truncate();
        factory(App\Campaign::class, 10)->create();

        $this->enableForeignKeys();
    }
}
