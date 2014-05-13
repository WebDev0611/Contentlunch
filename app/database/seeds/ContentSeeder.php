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
    $content = new Content([
      'title' => 'Social Hierarchies of the Schoolyard',
      'body' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.",
      'account_id' => $account->id,
      'connection_id' => $connection->id,
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

    // Attach content tags
    $tag = new ContentTag([
      'content_id' => $content->id,
      'tag' => 'Schoolyard'
    ]);
    $tag->save();
    $tag = new ContentTag([
      'content_id' => $content->id,
      'tag' => 'Social'
    ]);
    $tag->save();

    // Attach comments
    $comment = new ContentComment([
      'user_id' => $user1->id,
      'content_id' => $content->id,
      'comment' => "I'm doing a little bit of research on this topic before I start writing ."
    ]);
    $comment->save();
    $comment = new ContentComment([
      'user_id' => $user2->id,
      'content_id' => $content->id,
      'comment' => "Okay, please to not hesitate to call me. I was looking  at the other business models while trying to
get ready for my speech and ran across this site:   www.princefamilypaper.com ---   Let me know
what you think."
    ]);
    $comment->save();
    $comment = new ContentComment([
      'user_id' => $user1->id,
      'content_id' => $content->id,
      'comment' => "That  is extremely enlightening. Thanks Dwight!"
    ]);
    $comment->save();

    $this->command->info('Created content for Surge account: '. $content->title);

    // 2nd content
    $prevContent = $content;
    $content = new Content([
      'title' => 'Untitled',
      'body' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.",
      'account_id' => $account->id,
      'connection_id' => $connection->id,
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

    // Attach content tags
    $tag = new ContentTag([
      'content_id' => $content->id,
      'tag' => 'Ebook'
    ]);
    $tag->save();
    $tag = new ContentTag([
      'content_id' => $content->id,
      'tag' => 'Prospects'
    ]);
    $tag->save();

    // Attach related content
    $related = new ContentRelated([
      'content_id' => $content->id,
      'related_content_id' => $prevContent->id      
    ]);

    // Attach comments
    $comment = new ContentComment([
      'user_id' => $user3->id,
      'content_id' => $content->id,
      'comment' => "Correct the spelling in this post"
    ]);
    $comment->save();

    $this->command->info('Created content for Surge account: '. $content->title);

    // 3rd content
    $prevContent = $content;
    $content = new Content([
      'title' => 'Content Marketing in Mexico',
      'body' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.",
      'account_id' => $account->id,
      'connection_id' => $connection->id,
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

    // Attach content tags
    $tag = new ContentTag([
      'content_id' => $content->id,
      'tag' => 'Marketing'
    ]);
    $tag->save();
    $tag = new ContentTag([
      'content_id' => $content->id,
      'tag' => 'Mexico'
    ]);
    $tag->save();
    $tag = new ContentTag([
      'content_id' => $content->id,
      'tag' => 'Newsletter'
    ]);
    $tag->save();

    // Attach related content
    $related = new ContentRelated([
      'content_id' => $content->id,
      'related_content_id' => $prevContent->id      
    ]);

    // Attach comments
    $comment = new ContentComment([
      'user_id' => $user3->id,
      'content_id' => $content->id,
      'comment' => "Looking for input"
    ]);
    $comment->save();

    $this->command->info('Created content for Surge account: '. $content->title);

    // 4th content
    $prevContent = $content;
    $content = new Content([
      'title' => 'Selling Paper',
      'body' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.",
      'account_id' => $account->id,
      'connection_id' => $connection->id,
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

    // Attach content tags
    $tag = new ContentTag([
      'content_id' => $content->id,
      'tag' => 'Paper'
    ]);
    $tag->save();
    $tag = new ContentTag([
      'content_id' => $content->id,
      'tag' => 'Selling'
    ]);
    $tag->save();

    // Attach related content
    $related = new ContentRelated([
      'content_id' => $content->id,
      'related_content_id' => $prevContent->id      
    ]);

    // Attach comments
    $comment = new ContentComment([
      'user_id' => $user1->id,
      'content_id' => $content->id,
      'comment' => "Loving it so far, keep up the good work!"
    ]);
    $comment->save();
    $comment = new ContentComment([
      'user_id' => $user1->id,
      'content_id' => $content->id,
      'comment' => "Thanks!"
    ]);
    $comment->save();

    $this->command->info('Created content for Surge account: '. $content->title);

    // 5th content
    $prevContent = $content;
    $content = new Content([
      'title' => 'Increasing Revenue',
      'body' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.",
      'account_id' => $account->id,
      'connection_id' => $connection->id,
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

    // Attach content tags
    $tag = new ContentTag([
      'content_id' => $content->id,
      'tag' => 'Revenue'
    ]);
    $tag->save();
    $tag = new ContentTag([
      'content_id' => $content->id,
      'tag' => 'Prospects'
    ]);
    $tag->save();
    $tag = new ContentTag([
      'content_id' => $content->id,
      'tag' => 'Selling'
    ]);
    $tag->save();

    // Attach related content
    $related = new ContentRelated([
      'content_id' => $content->id,
      'related_content_id' => $prevContent->id      
    ]);

    // Attach comments
    $comment = new ContentComment([
      'user_id' => $user2->id,
      'content_id' => $content->id,
      'comment' => "Please do some more research on this topic"
    ]);
    $comment->save();

    $this->command->info('Created content for Surge account: '. $content->title);

  }

}
