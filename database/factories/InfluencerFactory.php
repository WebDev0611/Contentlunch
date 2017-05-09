<?php

$factory->define(App\Influencer::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->sentence,
        'image_url' =>  $faker->imageUrl(200, 200, 'sports'),
        'twitter_screen_name' => $faker->username,
        'twitter_id_str' => (string) $faker->randomNumber(8),
        'twitter_followers_count' => $faker->numberBetween(968, 9938202),
    ];
});