<?php

$factory->define(App\Persona::class, function (Faker\Generator $faker) {
    return [
        'name' => ucwords($faker->word),
    ];
});
