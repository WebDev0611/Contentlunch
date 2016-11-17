<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = bcrypt('launch123');
        $account = factory(App\Account::class)->create([
            'name' => 'Sample Agency Account',
            'account_type_id' => 2
        ]);

        DB::table('users')->truncate();
        DB::table('users')->insert([
            [
                'name' => 'Admin Istrator',
                'email' => 'admin@test.com',
                'password' => $password,
                'is_admin' => true,
                'account_id' => $account->id
            ],
            [
                'name' => 'Man Ager',
                'email' => 'manager@test.com',
                'password' => $password,
                'is_admin' => false,
                'account_id' => $account->id
            ],
            [
                'name' => 'Cre Ator',
                'email' => 'creator@test.com',
                'password' => $password,
                'is_admin' => false,
                'account_id' => $account->id
            ],
            [
                'name' => 'Ed Itor',
                'email' => 'editor@test.com',
                'password' => $password,
                'is_admin' => false,
                'account_id' => $account->id
            ]
        ]);
    }
}
