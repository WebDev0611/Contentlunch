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
                'name' => 'Basic Monthly',
                'slug' => 'basic-monthly',
                'price' => 99.00,
                'price_per_client' => 99.00,
                'limit_users' => 5,
                'description' => 'Basic plan restricts the number of users in the account to 5. All other functionalities are unlimited.'
            ],
            [
                'name' => 'Basic Annually',
                'slug' => 'basic-annually',
                'price' => 1069.00,
                'price_per_client' => 99.00,
                'limit_users' => 5,
                'description' => 'Basic plan restricts the number of users in the account to 5. All other functionalities are unlimited.'
            ],
            [
                'name' => 'Pro Monthly',
                'slug' => 'pro-monthly',
                'price' => 199.00,
                'price_per_client' => 99.00,
                'limit_users' => 10,
                'description' => 'Pro plan restricts the number of users in the account to 10. All other functionalities are unlimited.'
            ],
            [
                'name' => 'Pro Annually',
                'slug' => 'pro-annually',
                'price' => 2149.00,
                'price_per_client' => 99.00,
                'limit_users' => 10,
                'description' => 'Pro plan restricts the number of users in the account to 10. All other functionalities are unlimited.'
            ]
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
