<?php

class CampaignSeeder extends DatabaseSeeder {

  public function run()
  {
    // Seed campaigns for the Surge account
    $account = Account::where('title', 'Surge')->first();

    // Get users to assign as authors
    $user1 = User::where('username', 'creator@test.com')->first();
    $user2 = User::where('username', 'manager@test.com')->first();
    $user3 = User::where('username', 'editor@test.com')->first();

    // 1st campaign
    $campaign = new Campaign([
      'account_id' => $account->id,
      'user_id' => $user1->id,
      'title' => 'Northeast Trade Show',
      'status' => 1,
      'is_active' => 1,
      'campaign_type_id' => CampaignType::where('key', 'tradeshow-event')->pluck('id'),
      'start_date' => date('Y-m-d H:i:s', strtotime('-1 week')),
      'end_date' => date('Y-m-d H:i:s', strtotime('+1 week')),
      'is_recurring' => false,
      'concept' => "This is the concept of the campaign",
      'description' => "This is a trade show in Boston that the company is targeting as one of the most important of the year.",
      'goals' => "These are the goals we need to accomplish for this campaign",
    ]);
    $campaign->save();
    // Attach tags
    $campaign->tags()->save(new CampaignTag(['tag' => 'Trade show']));
    $campaign->tags()->save(new CampaignTag(['tag' => 'Boston']));
    // Attach collaborators
    $campaign->collaborators()->sync([$user2->id, $user3->id]);
    $this->command->info('Created campaign for Surge account: '. $campaign->title);

    // 2nd campaign
    $campaign = new Campaign([
      'account_id' => $account->id,
      'user_id' => $user2->id,
      'title' => 'Northwest Trade Show',
      'status' => 1,
      'is_active' => 1,
      'campaign_type_id' => CampaignType::where('key', 'partner-event')->pluck('id'),
      'start_date' => date('Y-m-d H:i:s', strtotime('now')),
      'end_date' => date('Y-m-d H:i:s', strtotime('+2 weeks')),
      'is_recurring' => true,
      'concept' => "This is the concept of the campaign",
      'description' => "This is a partner event in Seattle",
      'goals' => "These are the goals we need to accomplish for this campaign",
    ]);
    $campaign->save();
    // Attach tags
    $campaign->tags()->save(new CampaignTag(['tag' => 'Partner Event']));
    $campaign->tags()->save(new CampaignTag(['tag' => 'Seattle']));
    $campaign->tags()->save(new CampaignTag(['tag' => 'Trade show']));
    // Attach collaborators
    $campaign->collaborators()->sync([$user3->id]);
    $this->command->info('Created campaign for Surge account: '. $campaign->title);

    // 3rd campaign
    $campaign = new Campaign([
      'account_id' => $account->id,
      'user_id' => $user3->id,
      'title' => 'Middle East Trade Show',
      'status' => 1,
      'is_active' => 1,
      'campaign_type_id' => CampaignType::where('key', 'content-marketing')->pluck('id'),
      'start_date' => date('Y-m-d H:i:s', strtotime('-1 month')),
      'end_date' => date('Y-m-d H:i:s', strtotime('+3 days')),
      'is_recurring' => true,
      'concept' => "This is the concept of the campaign",
      'description' => "This is a content marketing campaign",
      'goals' => "These are the goals we need to accomplish for this campaign",
    ]);
    $campaign->save();
    // Attach tags
    $campaign->tags()->save(new CampaignTag(['tag' => 'Content Marketing']));
    // Attach collaborators
    $campaign->collaborators()->sync([$user2->id, $user1->id]);
    $this->command->info('Created campaign for Surge account: '. $campaign->title);

    // 4th campaign
    $campaign = new Campaign([
      'account_id' => $account->id,
      'user_id' => $user1->id,
      'title' => 'Las Vegas',
      'status' => 1,
      'is_active' => 1,
      'campaign_type_id' => CampaignType::where('key', 'advertising')->pluck('id'),
      'start_date' => date('Y-m-d H:i:s', strtotime('+1 week')),
      'end_date' => date('Y-m-d H:i:s', strtotime('+2 months')),
      'is_recurring' => false,
      'concept' => "This is the concept of the campaign",
      'description' => "This is an advertising campaign targeting Las Vegas",
      'goals' => "These are the goals we need to accomplish for this campaign",
    ]);
    $campaign->save();
    // Attach tags
    $campaign->tags()->save(new CampaignTag(['tag' => 'Las Vegas']));
    $campaign->tags()->save(new CampaignTag(['tag' => 'Advertising']));
    // No collaborators
    $this->command->info('Created campaign for Surge account: '. $campaign->title);

    // 5th campaign
    $campaign = new Campaign([
      'account_id' => $account->id,
      'user_id' => $user2->id,
      'title' => 'NYC Trade',
      'status' => 1,
      'is_active' => 1,
      'campaign_type_id' => CampaignType::where('key', 'webinar')->pluck('id'),
      'start_date' => date('Y-m-d H:i:s', strtotime('+1 month')),
      'end_date' => date('Y-m-d H:i:s', strtotime('+3 months')),
      'is_recurring' => true,
      'concept' => "This is the concept of the campaign",
      'description' => "This is a webinar for the upcoming New York trade show",
      'goals' => "These are the goals we need to accomplish for this campaign",
    ]);
    $campaign->save();
    // Attach tags
    $campaign->tags()->save(new CampaignTag(['tag' => 'NYC']));
    $campaign->tags()->save(new CampaignTag(['tag' => 'Trade']));
    $campaign->tags()->save(new CampaignTag(['tag' => 'Webinar']));
    // Attach collaborators
    $campaign->collaborators()->sync([$user1->id, $user3->id]);
    $this->command->info('Created campaign for Surge account: '. $campaign->title);

  }

}
