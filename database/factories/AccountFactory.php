<?php

$factory->define(App\Account::class, function (Faker\Generator $faker) {
    return [
        'name' => ucwords($faker->word) . ' Account',
    ];
});

$factory->define(App\AccountType::class, function (Faker\Generator $faker) {
    return [
        'name' => ucwords($faker->word) . ' Type',
    ];
});