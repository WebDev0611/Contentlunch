<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $password = bcrypt('launch123');
        $users = [
            [ 'name' => 'Admin Istrator', 'email' => 'admin@test.com',   'password' => $password ],
            [ 'name' => 'Man Ager',       'email' => 'manager@test.com', 'password' => $password ],
            [ 'name' => 'Cre Ator',       'email' => 'creator@test.com', 'password' => $password ],
            [ 'name' => 'Ed Itor',        'email' => 'editor@test.com',  'password' => $password ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
