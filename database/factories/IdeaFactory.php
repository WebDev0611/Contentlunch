<?php

$factory->define(App\Idea::class, function (Faker\Generator $faker) {
    return [
        'account_id' => function() use ($faker) {
            return factory(App\Account::class)->create()->id;
        },

        'user_id' => function($content) use ($faker) {
            $user = factory(App\User::class)->create();
            $user->accounts()->attach(App\Account::find($content['account_id']));

            return $user->id;
        },

        'name' => $faker->sentence,
        'text' => $faker->realText,
        'tags' => $faker->words(4, true),
        'status' => $faker->randomElement([ 'parked', 'active', 'rejected' ]),
    ];
});