<?php

$factory->define(App\Campaign::class, function (Faker\Generator $faker) {
    return [
        'account_id' => function() use ($faker) {
            return factory(App\Account::class)->create()->id;
        },

        'user_id' => function($task) use ($faker) {
            $user = factory(App\User::class)->create();
            $user->accounts()->attach(App\Account::find($task['account_id']));

            return $user->id;
        },

        'title' => $faker->sentence(3),
        'status' => rand(0, 1),
        'campaign_type_id' => function() use ($faker) {
            $campaignTypeIds = App\ContentType::select('id')
                ->get()
                ->pluck('id')
                ->toArray();

            return $faker->randomElement($campaignTypeIds);
        },
        'start_date' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
        'end_date' => $faker->dateTimeBetween('now', '2 years')->format('Y-m-d'),
        'is_recurring' => $faker->boolean,
        'description' => $faker->paragraph,
        'goals' => $faker->paragraph,
    ];
});
