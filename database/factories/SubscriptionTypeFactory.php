<?php

$factory->define(App\SubscriptionType::class, function (Faker\Generator $faker) {
    $name = ucwords($faker->word) . ' Plan';

    return [
        'name' => $name,
        'price' => ($faker->numberBetween(0, 9) * 10) + 9.99,
        'price_per_client' => ($faker->numberBetween(0, 9) * 10) + 9.99,
        'limit_users' => $faker->numberBetween(5, 10),
        'description' => $faker->sentence,
    ];
});