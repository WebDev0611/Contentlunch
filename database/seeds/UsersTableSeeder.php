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
        DB::table('users')->insert([
            'name' => 'Admin Istrator',
            'email' => 'admin@test.com',
            'password' => bcrypt('launch123'),
            'is_admin' => true,
        ]);

        DB::table('users')->insert([
            'name' => 'Man Ager',
            'email' => 'manager@test.com',
            'password' => bcrypt('launch123'),
            'is_admin' => false,
        ]);

        DB::table('users')->insert([
            'name' => 'Cre Ator',
            'email' => 'creator@test.com',
            'password' => bcrypt('launch123'),
            'is_admin' => false,
        ]);

        DB::table('users')->insert([
            'name' => 'Ed Itor',
            'email' => 'editor@test.com',
            'password' => bcrypt('launch123'),
            'is_admin' => false,
        ]);
    }
}
