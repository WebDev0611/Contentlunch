<?php

use Illuminate\Database\Seeder;
use App\SubscriptionType as Type;

class SubscriptionTypesTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     * @return void
     */
    public function run () {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Type::truncate();

        $trial = Type::create([
            'name' => 'Trial',
            'price' => 0,
            'price_per_client' => 0,
            'limit_users' => 3,
            'description' => 'Two week trial period.'
        ]);

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

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
