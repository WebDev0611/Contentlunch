<?php

use App\Limit;
use App\SubscriptionType as Type;
use Illuminate\Database\Seeder;

class SubscriptionTypesTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     * @return void
     */
    public function run () {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('subscription_types')->truncate();
        DB::table('limit_subscription_type')->truncate();

        $limits = Limit::all()->keyBy('name');

        /**
         * Free plan
         */
        $free = Type::create([
            'name' => 'Free',
            'price' => 0,
            'price_per_client' => 0,
            'limit_users' => 3,
            'description' => 'Two week trial period.'
        ]);

        $free->addLimit($limits['topic_search'], 10);
        $free->addLimit($limits['trend_search'], 10);
        $free->addLimit($limits['ideas_created'], 10);
        $free->addLimit($limits['content_launch'], 5);
        $free->addLimit($limits['campaigns'], 3);
        $free->addLimit($limits['content_edits'], 0);
        $free->addLimit($limits['influencer_search'], 5);
        $free->addLimit($limits['calendars'], 1);
        $free->addLimit($limits['users_per_account'], 3);
        $free->addLimit($limits['subaccounts_per_account'], 2);

        /**
         * Trial plan
         */
        $trial = Type::create([
            'name' => 'Trial',
            'price' => 0,
            'price_per_client' => 0,
            'limit_users' => 3,
            'description' => 'Two week trial period.'
        ]);

        $trial->addLimit($limits['topic_search'], 10);
        $trial->addLimit($limits['trend_search'], 10);
        $trial->addLimit($limits['ideas_created'], 10);
        $trial->addLimit($limits['content_launch'], 5);
        $trial->addLimit($limits['campaigns'], 3);
        $trial->addLimit($limits['content_edits'], 0);
        $trial->addLimit($limits['influencer_search'], 5);
        $trial->addLimit($limits['calendars'], 1);
        $trial->addLimit($limits['users_per_account'], 3);
        $trial->addLimit($limits['subaccounts_per_account'], 2);

        /**
         * Basic Plan
         */
        $basicMonthly = Type::create([
            'name' => 'Basic Monthly',
            'price' => 99.00,
            'price_per_client' => 99.00,
            'limit_users' => 5,
            'description' => 'Basic plan restricts the number of users in the account to 5. All other functionalities are unlimited.'
        ]);

        $basicAnnually = Type::create([
            'name' => 'Basic Annually',
            'price' => 1069.00,
            'price_per_client' => 99.00,
            'limit_users' => 5,
            'description' => 'Basic plan restricts the number of users in the account to 5. All other functionalities are unlimited.'
        ]);

        $basicMonthly->addLimit($limits['users_per_account'], 5);
        $basicAnnually->addLimit($limits['users_per_account'], 5);

        /**
         * Pro Plan
         */
        $proMonthly = Type::create([
            'name' => 'Pro Monthly',
            'price' => 199.00,
            'price_per_client' => 99.00,
            'limit_users' => 10,
            'description' => 'Pro plan restricts the number of users in the account to 10. All other functionalities are unlimited.'
        ]);

        $proAnnually = Type::create([
            'name' => 'Pro Annually',
            'price' => 2149.00,
            'price_per_client' => 99.00,
            'limit_users' => 10,
            'description' => 'Pro plan restricts the number of users in the account to 10. All other functionalities are unlimited.'
        ]);

        $proMonthly->addLimit($limits['users_per_account'], 10);
        $proAnnually->addLimit($limits['users_per_account'], 10);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
