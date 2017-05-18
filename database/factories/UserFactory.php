<?php

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt('launch123'),
        'remember_token' => str_random(10),
        'profile_image' => $faker->imageUrl(200, 200, 'sports'),
        'is_admin' => $faker->boolean,
        'city' => $faker->city,
        'country_code' => $faker->countryCode,
        'address' => $faker->address,
        'phone' => $faker->phoneNumber,
    ];
});

$factory->defineAs(App\User::class, 'admin', function ($faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return array_merge($user, ['is_admin' => true]);
});

$factory->defineAs(App\User::class, 'guest', function ($faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return array_merge($user, ['is_guest' => true, 'is_admin' => false]);
});