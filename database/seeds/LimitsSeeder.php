<?php

use Illuminate\Database\Seeder;

class LimitsSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('limits')->truncate();
        DB::table('limits')->insert([
            [ 'name' => 'topic_search', 'display_name' => 'Maximum topic searches per month', 'value' => 10 ],
            [ 'name' => 'trend_search', 'display_name' => 'Maximum trend searches per month', 'value' => 10 ],
            [ 'name' => 'ideas_created', 'display_name' => 'Maximum created ideas per month', 'value' => 10 ],
            [ 'name' => 'content_launch', 'display_name' => 'Maximum content launches per month', 'value' => 5 ],
            [ 'name' => 'campaigns', 'display_name' => 'Maximum campaigns per user', 'value' => 3 ],
            [ 'name' => 'content_edits', 'display_name' => 'Maximum content edits per user', 'value' => 0 ],
            [ 'name' => 'influencer_search', 'display_name' => 'Maximum influencer searches per month', 'value' => 5 ],
            [ 'name' => 'calendars', 'display_name' => 'Maximum calendars per user', 'value' => 1 ],
            [ 'name' => 'users_per_account', 'display_name' => 'Maximum users per account', 'value' => 3 ],
            [ 'name' => 'subaccounts_per_account', 'display_name' => 'Maximum subaccounts per account', 'value' => 2 ],
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}