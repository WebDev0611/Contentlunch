<?php

class AccountTypeSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        DB::table('account_types')->truncate();
        DB::table('account_types')->insert([
            [ 'name' => 'Company Account' ],
            [ 'name' => 'Agency Account' ]
        ]);

        $this->enableForeignKeys();
    }
}
