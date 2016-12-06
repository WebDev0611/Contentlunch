<?php

$factory->define(App\Provider::class, function (Faker\Generator $faker) {
    $name = $faker->company;

    return [
        'name' => $name,
        'slug' => App\Helpers::slugify($name),
        'class_name' => function($provider) {
            return preg_replace('/[-_, ]|and/', '', $provider['name']) . 'API';
        },
        'type' => 'website',
    ];
});