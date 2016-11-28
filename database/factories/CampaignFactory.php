<?php

$factory->define(App\Campaign::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function() use ($faker) {
            $userIds = App\User::select('id')
                ->get()
                ->pluck('id')
                ->toArray();

            return $faker->randomElement($userIds);
        },
        'account_id' => function(array $campaignData) {
            $user = App\User::find($campaignData['user_id']);

            if ($user->accounts->isEmpty()) {
                $account = factory(App\Account::class)->create();
                $user->accounts()->attach($account);
            } else {
                $account = $user->accounts[0];
            }

            return $account->id;
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
        'is_recurring' => $faker->randomElement([ true, false ]),
        'description' => $faker->paragraph,
        'goals' => $faker->paragraph,
    ];
});
