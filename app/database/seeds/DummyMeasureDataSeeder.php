<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;
use Carbon\Carbon;

class DummyMeasureDataSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();
        $faker = Faker::create();

        // get list of users that COULD create content
        $userIDs = User::where('id', '!=', 1)->lists('id');
        $contentTypeIDs = [2, 4, 10, 15, 5];

        Content::where('title', 'LIKE', 'Dummy Measure Data %')->delete();

        foreach(range(1, 10) as $index)
        {
            $userID        = $userIDs[array_rand($userIDs)];
            $contentTypeID = $contentTypeIDs[array_rand($contentTypeIDs)];
            $launched      = $faker->dateTimeThisMonth(Carbon::now()->endOfMonth());
            $created       = $faker->dateTimeThisMonth($launched);
            Content::create([
                'title'                  => "Dummy Measure Data {$index}",
                'body'                   => $faker->text,
                'account_id'             => 1,
                'content_type_id'        => $contentTypeID,
                'user_id'                => $userID,
                'buying_stage'           => rand(1, 3),
                'persona'                => 'cmo',
                'campaign_id'            => 1,
                'secondary_buying_stage' => 'prospects',
                'secondary_persona'      => 'vp sales',
                'concept'                => $faker->text,
                'status'                 => 1,
                'archived'               => 0,
                'upload_id'              => null,
                'convert_date'           => null,
                'submit_date'            => null,
                'approval_date'          => null,
                'launch_date'            => $launched,
                'promote_date'           => null,
                'archive_date'           => null,
                'meta_description'       => null,
                'meta_keywords'          => null,
                'created_at'             => $created,
                'updated_at'             => $created,
            ]);
        }
    }

}