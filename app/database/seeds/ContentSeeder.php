<?php

class ContentSeeder extends DatabaseSeeder {

  public function run()
  {

    // Assign content to account
    $account = Account::where('title', 'Surge')->first();

    // Get users to assign as authors
    $user1 = User::where('username', 'creator@test.com')->first();
    $user2 = User::where('username', 'manager@test.com')->first();
    $user3 = User::where('username', 'editor@test.com')->first();

    // @todo: Create connection for each type
    $connection = AccountConnection::first();

    // Campaigns to assign content to
    $campaigns = Campaign::where('account_id', $account->id)->get();

    foreach ([
      [
        'Social Hierarchies of the Schoolyard',
        $user1->id,
        'blog-post',
        'suspects',
        'cmo',
        $campaigns[0]->id,
        'prospects',
        'vp sales',
        ['Schoolyard', 'Social'],
        [
          [$user1->id, "I'm doing a little bit of research on this topic before I start writing ."],
          [$user2->id, "Okay, please to not hesitate to call me. I was looking  at the other business models while trying to
get ready for my speech and ran across this site:   www.princefamilypaper.com ---   Let me know
what you think."],
          [$user1->id, " That  is extremely enlightening. Thanks Dwight!"]
        ]
      ],
      [
        'Untitled',
        $user2->id,
        'ebook',
        'prospects',
        'vp sales',
        $campaigns[0]->id,
        'leads',
        'cmo',
        ['Ebook', 'Prospects'],
        [
          [$user3->id, "Correct the spelling in this post"]
        ]
      ],
      [
        'Content Marketing in Mexico',
        $user3->id,
        'newsletter',
        'leads',
        'sales rep',
        $campaigns[0]->id,
        'opportunities',
        'product manager',
        ['Marketing', 'Mexico', 'Newsletter'],
        [
          [$user3->id, "Looking for input"]
        ]
      ],
      [
        'Selling Paper',
        $user2->id,
        'sales-letter',
        'opportunities',
        'product manager',
        $campaigns[1]->id,
        'suspects',
        'cmo',
        ['Paper', 'Selling'],
        [
          [$user1->id, "Loving it so far, keep up the good work!"],
          [$user2->id, "Thanks!"]
        ]
      ],
      [
        'Increasing Revenue',
        $user1->id,
        'tweet',
        'prospects',
        'vp sales',
        $campaigns[1]->id,
        'leads',
        'suspects',
        ['Revenue', 'Prospects', 'Selling'],
        [
          [$user2->id, "Please do some more research on this topic"]
        ]
      ]
    ] as $data) {
      $content = new Content;
      $content->title = $data[0];
      $content->body = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.";
      $content->account_id = $account->id;
      $content->connection_id = $connection->id;
      $typeId = ContentType::where('key', $data[2])->pluck('id');
      $content->content_type_id = $typeId;
      $content->user_id = $data[1];
      $content->buying_stage = $data[3];
      $content->persona = $data[4];
      $content->campaign_id = $data[5];
      $content->secondary_buying_stage = $data[6];
      $content->secondary_persona = $data[7];
      $content->save();

      foreach ($data[8] as $tag) {
        $ctag = new ContentTag;
        $ctag->tag = $tag;
        $ctag->content_id = $content->id;
        $ctag->save();
      }

      foreach ($data[9] as $comment) {
        $c = new ContentComment;
        $c->content_id = $content->id;
        $c->user_id = $comment[0];
        $c->comment = $comment[1];
        $c->save();
      }

      if ( ! empty($relatedContent)) {
        $related = new ContentRelated;
        $related->content_id = $content->id;
        $related->related_content_id = $relatedContent;
        $related->save();
      }
      $relatedContent = $content->id;

      $this->command->info('Created content for Surge account: '. $content->title);
    }

  }

}
