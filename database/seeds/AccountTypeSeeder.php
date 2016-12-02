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
        AccountType::truncate();
        factory(AccountType::class)->create([ 'name' => 'Company Account' ]);
        factory(AccountType::class)->create([ 'name' => 'Agency Account' ]);
    }
}
