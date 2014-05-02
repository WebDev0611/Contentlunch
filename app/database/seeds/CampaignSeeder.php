<?php

class CampaignSeeder extends DatabaseSeeder {

  public function run()
  {
    // Seed campaigns for the Surge account
    $account = Account::where('title', 'Surge')->first();

    foreach ([
      [
        'Northeast Trade Show',
        'tradeshow-event',
        '-1 week',
        '+1 week',
        false,
        "This is a trade show in Boston that the company is targeting as one of the most important of the year.",
        ['Trade show', 'Boston']
      ],
      [
        'Northwest Trade Show',
        'partner-event',
        'now',
        '+2 weeks',
        true,
        "This is a partner event in Seattle",
        ['Partner Event', 'Seattle', 'Trade show']
      ],
      [
        'Middle East Trade Show',
        'content-marketing',
        '-1 month',
        '+3 days',
        true,
        "This is a content marketing campaign",
        ['Content Marketing']
      ],
      [
        'Las Vegas',
        'advertising',
        '+1 week',
        '+2 months',
        false,
        "This is a advertising campaign targeting Las Vegas",
        ['Las Vegas', 'Advertising']
      ],
      [
        'NYC Trade',
        'webinar',
        '+1 month',
        '+3 months',
        true,
        "This is a webinar for the upcoming New York trade show",
        ['NYC', 'Trade', 'Webinar']
      ]
    ] as $data) {
      $campaign = new Campaign;
      $campaign->account_id = $account->id;
      $campaign->title = $data[0];
      $campaign->status = 1;
      $typeID = CampaignType::where('key', $data[1])->pluck('id');
      $campaign->campaign_type_id = $typeID;
      $campaign->start_date = date('y-m-d H:i:s', strtotime($data[2]));
      $campaign->end_date = date('y-m-d H:i:s', strtotime($data[3]));
      $campaign->is_recurring = $data[4];
      $campaign->description = $data[5];
      $campaign->goals = "These are the goals we need to accomplish for this campaign";
      $campaign->save();
      foreach ($data[6] as $tag) {
        $ctag = new CampaignTag;
        $ctag->tag = $tag;
        $ctag->campaign_id = $campaign->id;
        $ctag->save();
      }
    }
  }

}
