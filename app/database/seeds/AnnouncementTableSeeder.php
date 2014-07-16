<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class AnnouncementTableSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();
        $faker = Faker::create();

        foreach(range(1, 5) as $index)
        {
            Announcement::create([
                'message'    => $faker->text,
                'created_at' => $faker->dateTimeBetween($startDate = '-3 months', $endDate = 'now'),
            ]);
        }
    }

}