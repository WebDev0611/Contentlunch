<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;
use Carbon\Carbon;

class DummyMeasureDataSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();
        $faker = Faker::create();

        // get an account ID and a list of users, content, and campaigns that exist
        $accountID = Account::first()->pluck('id');
        $userIDs = User::where('id', '!=', 1)->lists('id');
        $contentIDs = Content::where('account_id', $accountID)->lists('id');
        $campaignIDs = Campaign::where('account_id', $accountID)->lists('id');

        // Content
        // -------------------------
        $contentTypeIDs = [2, 4, 10, 15, 5]; // content types I KNOW don't take extra fields

        Content::where('title', 'LIKE', 'Dummy Measure Data %')->delete();

        foreach (range(1, 50) as $index) {
            $userID        = $userIDs[array_rand($userIDs)];
            $campaignID    = $campaignIDs[array_rand($campaignIDs)];
            $contentTypeID = $contentTypeIDs[array_rand($contentTypeIDs)];
            $launched      = $faker->dateTimeThisMonth(Carbon::now()->endOfMonth());
            $created       = $faker->dateTimeThisMonth($launched);
            $concept       = $faker->dateTimeBetween($created, $launched);
            $status        = rand(0, 4);

            Content::create([
                'title'                  => "Dummy Measure Data {$index}",
                'body'                   => $faker->text,
                'account_id'             => $accountID,
                'content_type_id'        => $contentTypeID,
                'user_id'                => $userID,
                'buying_stage'           => rand(1, 3),
                'persona'                => 'cmo',
                'campaign_id'            => $campaignID,
                'secondary_buying_stage' => 'prospects',
                'secondary_persona'      => 'vp sales',
                'concept'                => $faker->text,
                'status'                 => $status,
                'archived'               => 0,
                'upload_id'              => null,
                'convert_date'           => $index <= 25 && $status > 0 ? null : $concept,
                'submit_date'            => null,
                'approval_date'          => null,
                'launch_date'            => $status == 4 ? $launched : null, // 4 means launched
                'promote_date'           => null,
                'archive_date'           => null,
                'meta_description'       => null,
                'meta_keywords'          => null,
                'created_at'             => $created,
                'updated_at'             => $created,
            ]);
        }

        // Tasks
        // -------------------------
        $contentTaskGroupIDs = ContentTaskGroup::whereIn('content_id', $contentIDs)->lists('id');

        ContentTask::where('name', 'LIKE', 'Dummy Measure Data %')->delete();
        CampaignTask::where('name', 'LIKE', 'Dummy Measure Data %')->delete();

        foreach (range(1, 100) as $index) {
            $userID             = $userIDs[array_rand($userIDs)];
            $campaignID         = $campaignIDs[array_rand($campaignIDs)];
            $contentTaskGroupID = $contentTaskGroupIDs[array_rand($contentTaskGroupIDs)];
            $dueDate = $faker->dateTimeThisMonth();
            $isComplete = rand(0, 1);
            $dateCompleted = $isComplete ? $faker->dateTimeBetween(Carbon::parse($dueDate->format('Y-m-d H:i:s'))->subWeek(1)->format('Y-m-d H:i:s'), $dueDate) : null;

            $data = [
                'name' => "Dummy Measure Data {$index}",
                'due_date' => $dueDate,
                'date_completed' => $dateCompleted,
                'is_complete' => $isComplete,
                'user_id' => $userID,
                'content_task_group_id' => $contentTaskGroupID,
            ];

            ContentTask::create($data);

            unset($data['content_task_group_id']);
            $data['campaign_id'] = $campaignID;

            CampaignTask::create($data);
        }


        // Seed Measure stuff
        // -------------------------
        $date = Carbon::now()->subMonth(1);
        $now  = Carbon::now();

        do {
            App::make('MeasureController')->measureCreatedContent($date->format('Y-m-d'), 1);
            App::make('MeasureController')->measureLaunchedContent($date->format('Y-m-d'), 1);
            App::make('MeasureController')->measureTimingContent($date->format('Y-m-d'), 1);

            $date->addDay(1);
        } while ($now->gte($date));

        App::make('MeasureController')->measureUserEfficiency(1);
    }

}