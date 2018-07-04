<?php

$factory->define(App\Connection::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->words(3, true),
        'active' => $faker->boolean,
        'successful' => $faker->boolean,

        'account_id' => function() {
            return factory(App\Account::class)->create()->id;
        },

        'user_id' => function($connection) use ($faker) {
            $user = factory(App\User::class)->create();
            $user->accounts()->attach(App\Account::find($connection['account_id']));

            return $user->id;
        },

        'settings' => function() use ($faker) {
            $object = [
                'oauth_token' => str_random(60),
                'oauth_token_secret' => str_random(60),
                'user_id' => $faker->numerify('#########'),
                'screen_name' => $faker->userName,
            ];

            return json_encode($object);
        },

        'provider_id' => function() use ($faker) {
            $providerIds = App\Provider::where('class_name', '!=', '')
                ->get()
                ->pluck('id');

            if ($providerIds->isEmpty()) {
                return factory(App\Provider::class)->create()->id;
            } else {
                return $faker->randomElement($providerIds->toArray());
            }

        }
    ];
});