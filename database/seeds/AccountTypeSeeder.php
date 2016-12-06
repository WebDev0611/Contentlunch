<?php

use Illuminate\Database\Seeder;

use App\AccountType;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('account_types')->truncate();
        DB::table('account_types')->insert([
            [ 'name' => 'Company Account' ],
            [ 'name' => 'Agency Account' ]
        ]);
    }
}
