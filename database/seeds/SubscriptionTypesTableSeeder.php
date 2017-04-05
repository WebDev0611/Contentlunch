<?php

use App\Limit;
use App\SubscriptionType as Type;
use App\User;

class SubscriptionTypesTableSeeder extends BaseSeeder
{
    protected $limits;

    /**
     * Run the database seeds.
     * @return void
     */
    public function run () {
        $this->disableForeignKeys();

        DB::table('subscription_types')->truncate();
        DB::table('limit_subscription_type')->truncate();

        $this->limits = Limit::all()->keyBy('name');

        $this->createFreePlan();
        $this->createTrialPlan();
        $this->createBasicPlan();
        $this->createProPlan();
        $this->setAdminAsPro();

        $this->enableForeignKeys();
    }

    protected function createFreePlan()
    {
        $free = Type::create([
            'name' => 'Free',
            'price' => 0,
            'price_per_client' => 0,
            'description' => 'Free account.'
        ]);

        $free->addLimit($this->limits['topic_search'], 10);
        $free->addLimit($this->limits['trend_search'], 10);
        $free->addLimit($this->limits['ideas_created'], 10);
        $free->addLimit($this->limits['content_launch'], 5);
        $free->addLimit($this->limits['campaigns'], 3);
        $free->addLimit($this->limits['content_edits'], 0);
        $free->addLimit($this->limits['influencer_search'], 5);
        $free->addLimit($this->limits['calendars'], 1);
        $free->addLimit($this->limits['users_per_account'], 2);
        $free->addLimit($this->limits['subaccounts_per_account'], 3);
    }

    protected function createTrialPlan()
    {
        $trial = Type::create([
            'name' => 'Trial',
            'price' => 0,
            'price_per_client' => 0,
            'description' => 'Two week trial period.'
        ]);

        $trial->addLimit($this->limits['topic_search'], 10);
        $trial->addLimit($this->limits['trend_search'], 10);
        $trial->addLimit($this->limits['ideas_created'], 10);
        $trial->addLimit($this->limits['content_launch'], 5);
        $trial->addLimit($this->limits['campaigns'], 3);
        $trial->addLimit($this->limits['content_edits'], 0);
        $trial->addLimit($this->limits['influencer_search'], 5);
        $trial->addLimit($this->limits['calendars'], 1);
        $trial->addLimit($this->limits['users_per_account'], 2);
        $trial->addLimit($this->limits['subaccounts_per_account'], 3);
    }

    public function createBasicPlan()
    {
        $basicMonthly = Type::create([
            'name' => 'Basic Monthly',
            'price' => 99.00,
            'price_per_client' => 99.00,
            'description' => 'Basic plan restricts the number of users in the account to 5. All other functionalities are unlimited.'
        ]);

        $basicAnnually = Type::create([
            'name' => 'Basic Annually',
            'price' => 1069.00,
            'price_per_client' => 99.00,
            'description' => 'Basic plan restricts the number of users in the account to 5. All other functionalities are unlimited.'
        ]);

        $basicMonthly->addLimit($this->limits['users_per_account'], 5);
        $basicAnnually->addLimit($this->limits['users_per_account'], 5);
    }

    public function createProPlan()
    {
        $proMonthly = Type::create([
            'name' => 'Pro Monthly',
            'price' => 199.00,
            'price_per_client' => 99.00,
            'description' => 'Pro plan restricts the number of users in the account to 10. All other functionalities are unlimited.'
        ]);

        $proAnnually = Type::create([
            'name' => 'Pro Annually',
            'price' => 2149.00,
            'price_per_client' => 99.00,
            'description' => 'Pro plan restricts the number of users in the account to 10. All other functionalities are unlimited.'
        ]);

        $proMonthly->addLimit($this->limits['users_per_account'], 10);
        $proAnnually->addLimit($this->limits['users_per_account'], 10);
    }

    protected function setAdminAsPro()
    {
        User::first()
            ->accounts()
            ->first()
            ->subscribe(Type::findBySlug('pro-annually'));
    }
}
