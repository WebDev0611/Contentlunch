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

    // Create seeded content
    // 1st content
    $content = new Content([
      'title' => 'Social Hierarchies of the Schoolyard',
      'body' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.",
      'account_id' => $account->id,
      'content_type_id' => ContentType::where('key', 'blog-post')->pluck('id'),
      'user_id' => $user1->id,
      'buying_stage' => 'suspects',
      'persona' => 'cmo',
      'campaign_id' => $campaigns[0]->id,
      'secondary_buying_stage' => 'prospects',
      'secondary_persona' => 'vp sales',
      'status' => 1, // Create
      'concept' => 'Concept description for social hierarchies'
    ]);
    $content->save();
    // Attach connection
    $content->connections()->attach($connection->id);
    // Attach collaborators
    $content->collaborators()->sync([$user2->id, $user3->id]);
    // Attach content tags
    $content->tags()->save(new ContentTag(['tag' => 'Schoolyard']));
    $content->tags()->save(new ContentTag(['tag' => 'Social']));
    // Attach comments
    $content->comments()->save(new ContentComment(['user_id' => $user1->id,
      'comment' => "I'm doing a little bit of research on this topic before I start writing ."
    ]));
    $content->comments()->save(new ContentComment(['user_id' => $user2->id,
      'comment' => "Okay, please to not hesitate to call me. I was looking  at the other business models while trying to
get ready for my speech and ran across this site:   www.princefamilypaper.com ---   Let me know
what you think."
    ]));
    $content->comments()->save(new ContentComment(['user_id' => $user1->id,
      'comment' => "That  is extremely enlightening. Thanks Dwight!"
    ]));
    $this->command->info('Created content for Surge account: '. $content->title);

    // 2nd content
    $prevContent = $content;
    $content = new Content([
      'title' => 'Untitled',
      'body' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.",
      'account_id' => $account->id,
      'content_type_id' => ContentType::where('key', 'ebook')->pluck('id'),
      'user_id' => $user2->id,
      'buying_stage' => 'prospects',
      'persona' => 'vp sales',
      'campaign_id' => $campaigns[0]->id,
      'secondary_buying_stage' => 'leads',
      'secondary_persona' => 'cmo',
      'status' => 2, // Edit
      'concept' => 'Concept description for Untitled'
    ]);
    $content->save();
    // Attach connection
    $content->connections()->attach($connection->id);
    // Attach related content
    $content->related()->attach($prevContent->id);
    // Attach collaborators
    $content->collaborators()->sync([$user1->id, $user3->id]);
    // Attach content tags
    $content->tags()->save(new ContentTag(['tag' => 'Ebook']));
    $content->tags()->save(new ContentTag(['tag' => 'Prospects']));
    // Attach comments
    $content->comments()->save(new ContentComment(['user_id' => $user3->id,
      'comment' => "Correct the spelling in this post"]));
    $this->command->info('Created content for Surge account: '. $content->title);

    // 3rd content
    $prevContent = $content;
    $content = new Content([
      'title' => 'Content Marketing in Mexico',
      'body' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.",
      'account_id' => $account->id,
      'content_type_id' => ContentType::where('key', 'newsletter')->pluck('id'),
      'user_id' => $user3->id,
      'buying_stage' => 'leads',
      'persona' => 'sales rep',
      'campaign_id' => $campaigns[0]->id,
      'secondary_buying_stage' => 'opportunities',
      'secondary_persona' => 'product manager',
      'status' => 3,
      'concept' => 'Concept description for Content marketing in Mexico'
    ]);
    $content->save();
    // Attach connection
    $content->connections()->attach($connection->id);
    // Attach related content
    $content->related()->attach($prevContent->id);
    // Attach collaborators
    $content->collaborators()->sync([$user1->id, $user2->id]);
    // Attach content tags
    $content->tags()->save(new ContentTag(['tag' => 'Marketing']));
    $content->tags()->save(new ContentTag(['tag' => 'Mexico']));
    $content->tags()->save(new ContentTag(['tag' => 'Newsletter']));
    // Attach comments
    $content->comments()->save(new ContentComment(['user_id' => $user3->id, 'comment' => "Looking for input"]));
    $this->command->info('Created content for Surge account: '. $content->title);

    // 4th content
    $prevContent = $content;
    $content = new Content([
      'title' => 'Selling Paper',
      'body' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.",
      'account_id' => $account->id,
      'content_type_id' => ContentType::where('key', 'sales-letter')->pluck('id'),
      'user_id' => $user2->id,
      'buying_stage' => 'opportunities',
      'persona' => 'product manager',
      'campaign_id' => $campaigns[0]->id,
      'secondary_buying_stage' => 'suspects',
      'secondary_persona' => 'cmo',
      'status' => 4,
      'concept' => 'Concept description for Selling paper'
    ]);
    $content->save();
    // Attach connection
    $content->connections()->attach($connection->id);
    // Attach related content
    $content->related()->attach($prevContent->id);
    // Attach collaborators
    $content->collaborators()->sync([$user1->id, $user3->id]);
    // Attach content tags
    $content->tags()->save(new ContentTag(['tag' => 'Paper']));
    $content->tags()->save(new ContentTag(['tag' => 'Selling']));
    // Attach comments
    $content->comments()->save(new ContentComment(['user_id' => $user1->id, 'comment' => "Loving it so far, keep up the good work!"]));
    $content->comments()->save(new ContentComment(['user_id' => $user1->id, 'comment' => "Thanks!"]));
    $this->command->info('Created content for Surge account: '. $content->title);

    // 5th content
    $prevContent = $content;
    $content = new Content([
      'title' => 'Increasing Revenue',
      'body' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.",
      'account_id' => $account->id,
      'content_type_id' => ContentType::where('key', 'tweet')->pluck('id'),
      'user_id' => $user1->id,
      'buying_stage' => 'prospects',
      'persona' => 'vp sales',
      'campaign_id' => $campaigns[0]->id,
      'secondary_buying_stage' => 'leads',
      'secondary_persona' => 'suspects',
      'status' => 3,
      'archived' => true, // Archive this content
      'concept' => 'Concept description for Selling paper'
    ]);
    $content->save();
    // Attach connection
    $content->connections()->attach($connection->id);
    // Attach related content
    $content->related()->attach($prevContent->id);
    // Attach collaborators
    $content->collaborators()->sync([$user2->id, $user3->id]);
    // Attach content tags
    $content->tags()->save(new ContentTag(['tag' => 'Revenue']));
    $content->tags()->save(new ContentTag(['tag' => 'Prospects']));
    $content->tags()->save(new ContentTag(['tag' => 'Selling']));
    // Attach comments
    $content->comments()->save(new ContentComment(['user_id' => $user2->id, 'comment' => "Please do some more research on this topic"]));
    $this->command->info('Created content for Surge account: '. $content->title);

  }

}
