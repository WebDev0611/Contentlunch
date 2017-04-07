<?php

$factory->define(App\Content::class, function (Faker\Generator $faker) {
    $status = $faker->randomElement([1, 2, 3, 4]);

    return [
        'content_type_id' => function() use ($faker) {
            $contentTypes = App\ContentType::where('provider_id', '!=', '0')->get();
            $selectedContentType = $faker->randomElement($contentTypes->toArray());

            return $selectedContentType['id'];
        },

        'account_id' => function() use ($faker) {
            return factory(App\Account::class)->create()->id;
        },

        'connection_id' => function($content) use ($faker) {
            return factory(App\Connection::class)->create([
                'account_id' => $content['account_id']
            ])->id;
        },

        'content_status_id' => function($content) use ($faker) {
            $possibleStatusIDs = \App\ContentStatus::pluck('id')->toArray();

            return $faker->randomElement($possibleStatusIDs);
        },

        'user_id' => function($content) use ($faker) {
            $user = factory(App\User::class)->create();
            $user->accounts()->attach(App\Account::find($content['account_id']));

            return $user->id;
        },

        'body' => '<p>' . $faker->realText . '</p>',
        'meta_title' => $faker->sentence,
        'meta_keywords' => $faker->words(4, true),
        'meta_description' => $faker->text,
        'due_date' => $faker->dateTimeBetween('now', '1 month')->format('Y-m-d'),
        'title' => $faker->sentence,
        'content_status_id' => $status,
    ];
});