<?php

use Illuminate\Database\Seeder;
use App\Account;
use App\User;
use App\AccountType;
use App\AccountUser;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Account::truncate();
        AccountUser::truncate();
        User::truncate();

        $password = bcrypt('launch123');
        $account = factory(Account::class)->create([
            'name' => 'Sample Agency Account',
            'account_type_id' => AccountType::AGENCY
        ]);

        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@test.com',
                'password' => $password,
                'is_admin' => true,
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@test.com',
                'password' => $password,
                'is_admin' => false,
            ],
            [
                'name' => 'Creator',
                'email' => 'creator@test.com',
                'password' => $password,
                'is_admin' => false,
            ],
            [
                'name' => 'Editor',
                'email' => 'editor@test.com',
                'password' => $password,
                'is_admin' => false,
            ]
        ];

        collect($users)->each(function($userArray) use ($account) {
            $account->users()->create($userArray);
            $account->save();
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
