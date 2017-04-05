<?php

$factory->define(App\Task::class, function (Faker\Generator $faker) {
    return [
        'account_id' => function() use ($faker) {
            return factory(App\Account::class)->create()->id;
        },

        'user_id' => function($task) use ($faker) {
            $user = factory(App\User::class)->create();
            $user->accounts()->attach(App\Account::find($task['account_id']));

            return $user->id;
        },

        'url' => $faker->imageUrl(200, 200, 'sports'),
        'name' => $faker->sentence,
        'explanation' => $faker->realText,
        'start_date' => $faker->dateTimeBetween('-1 week', 'now'),
        'due_date' => $faker->dateTimeBetween('now', '1 week'),
        'status' => $faker->randomElement([ 'open', 'closed', 'archived' ]),
    ];
});