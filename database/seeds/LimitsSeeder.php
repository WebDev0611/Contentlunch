<?php

use Illuminate\Database\Seeder;

class LimitsSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('limits')->truncate();
        DB::table('limits')->insert([
            [ 'name' => 'topic_search', 'display_name' => 'Maximum topic searches per month' ],
            [ 'name' => 'trend_search', 'display_name' => 'Maximum trend searches per month' ],
            [ 'name' => 'ideas_created', 'display_name' => 'Maximum created ideas per month' ],
            [ 'name' => 'content_launch', 'display_name' => 'Maximum content launches per month' ],
            [ 'name' => 'campaigns', 'display_name' => 'Maximum campaigns per user' ],
            [ 'name' => 'content_edits', 'display_name' => 'Maximum content edits per user' ],
            [ 'name' => 'influencer_search', 'display_name' => 'Maximum influencer searches per month' ],
            [ 'name' => 'calendars', 'display_name' => 'Maximum calendars per user' ],
            [ 'name' => 'users_per_account', 'display_name' => 'Maximum users per account' ],
            [ 'name' => 'subaccounts_per_account', 'display_name' => 'Maximum subaccounts per account' ],
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}