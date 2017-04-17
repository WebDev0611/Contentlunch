<?php

use App\Login;
use App\User;

class LoginSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Login::truncate();
        $users = User::pluck('id')->toArray();

        for($i = 0; $i < 128; $i++) {
            $createdAt = $this->faker->dateTimeBetween('-2 months', 'now');

            $login = Login::create([
                'user_id' => $this->faker->randomElement($users),
                'ip_address' => $this->faker->ipv4,
                'user_agent' => $this->faker->userAgent,
                'fingerprint' => $this->faker->md5,
            ]);

            $login->setCreatedAt($createdAt)
                ->setUpdatedAt($createdAt)
                ->save();
        }
    }
}
