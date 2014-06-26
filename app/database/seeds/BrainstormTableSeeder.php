<?php

use Faker\Factory as Faker;

class BrainstormTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 5) as $index)
		{
			Brainstorm::create([
                'user_id'     => 1,
                'content_id'  => $index,
                'account_id'  => 1,
                'agenda'      => $faker->words($index),
                'date'        => date('Y') . '-' . date('m') . '-' . $faker->dayOfMonth,
                'time'        => $faker->time,
                'description' => $faker->paragraph,
			]);
		}
	}

}