<?php

use Illuminate\Database\Seeder;

class SubscriptionTypesTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     * @return void
     */
    public function run () {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('subscription_types')->truncate();
        DB::table('subscription_types')->insert([
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'price_month' => 99.00,
                'price_year' => 1069.00,
                'description' => 'Basic plan restricts the number of users in the account to 5. All other functionalities are unlimited.'
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'price_month' => 199.00,
                'price_year' => 2149.00,
                'description' => 'Pro plan restricts the number of users in the account to 10. All other functionalities are unlimited.'
            ]
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
