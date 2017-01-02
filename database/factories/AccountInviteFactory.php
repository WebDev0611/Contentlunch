<?php

$factory->define(App\AccountInvite::class, function (Faker\Generator $faker) {
    return [
        'email' => $faker->email,
        'account_id' => function() {
            return factory(App\Account::class)->create()->id;
        }
    ];
});