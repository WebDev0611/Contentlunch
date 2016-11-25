<?php

$factory->define(App\BuyingStage::class, function (Faker\Generator $faker) {
    return [
        'name' => ucwords($faker->word) . ' Stage',
        'description' => $faker->realText(200),
        'account_id' => function (array $buyingStage) {
            return factory(App\Account::class)->create()->id;
        }
    ];
});
