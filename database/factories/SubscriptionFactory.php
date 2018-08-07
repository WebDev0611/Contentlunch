<?php

$factory->define(App\Subscription::class, function (Faker\Generator $faker) {
    return [
        'account_id' => function() use ($faker) {
            return factory(App\Account::class)->create()->id;
        },

        'subscription_type_id' => function() use ($faker) {
            return factory(App\SubscriptionType::class)->create()->id;
        },

        'start_date' => \Carbon\Carbon::now(),
        'expiration_date' => \Carbon\Carbon::now()->addMonth(),
        'auto_renew' => $faker->boolean,
        'valid' => $faker->boolean,
    ];
});