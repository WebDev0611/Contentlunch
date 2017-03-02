<?php

use Illuminate\Database\Seeder;

class LimitsSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('limits')->truncate();
        DB::table('limits')->insert([
            [
                'name' => 'topic_search',
                'display_name' => 'Maximum topic searches per month',
                'feedback_message' => 'Looks like you\'ll need to upgrade to a paid account to generate more topics.',
            ],
            [
                'name' => 'trend_search',
                'display_name' => 'Maximum trend searches per month',
                'feedback_message' => 'Looks like you\'ll need to upgrade to a paid account to search more content trends.',
            ],
            [
                'name' => 'ideas_created',
                'display_name' => 'Maximum created ideas per month',
                'feedback_message' => 'Looks like you\'ll need to upgrade to a paid account to create more ideas.',
            ],
            [
                'name' => 'content_launch',
                'display_name' => 'Maximum content launches per month',
                'feedback_message' => 'Looks like you\'ll need to upgrade to a paid account to launch more content.',
            ],
            [
                'name' => 'campaigns',
                'display_name' => 'Maximum campaigns per user',
                'feedback_message' => 'Looks like you\'ll need to upgrade to a paid account in order to add a new campaign.',
            ],
            [
                'name' => 'content_edits',
                'display_name' => 'Maximum content edits per user',
                'feedback_message' => '',
            ],
            [
                'name' => 'influencer_search',
                'display_name' => 'Maximum influencer searches per month',
                'feedback_message' => '',
            ],
            [
                'name' => 'calendars',
                'display_name' => 'Maximum calendars per user',
                'feedback_message' => 'Looks like you\'ll need to upgrade to a paid account in order to create more calendars.',
            ],
            [
                'name' => 'users_per_account',
                'display_name' => 'Maximum users per account',
                'feedback_message' => 'Looks like you\'ll need to upgrade to a paid account in order to have more users per account.',
            ],
            [
                'name' => 'subaccounts_per_account',
                'display_name' => 'Maximum subaccounts per account',
                'feedback_message' => 'Looks like you\'ll need to upgrade to a paid account in order to add another sub-account.',
            ],
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}