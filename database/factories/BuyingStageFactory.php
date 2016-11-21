<?php

$factory->define(App\BuyingStage::class, function (Faker\Generator $faker) {
    return [
        'name' => ucwords($faker->word) . ' Stage',
    ];
});
