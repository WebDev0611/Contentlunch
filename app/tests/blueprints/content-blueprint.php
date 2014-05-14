<?php

use Woodling\Woodling;

Woodling::seed('Content', function ($blueprint) {
  $blueprint->sequence('title', function ($i) {
    return 'Content title '. $i;
  });
  $blueprint->body = 'Lorem ipsum';
  $blueprint->content_type_id = DB::table('content_types')->pluck('id');
  $blueprint->buying_stage = 1;
  $blueprint->persona = 'VP';
  $blueprint->secondary_buying_stage = 2;
  $blueprint->secondary_persona = 'Leads';
  $blueprint->concept = 'This is the concept';
  $blueprint->status = 1;
  $blueprint->archived = 0;
});

Woodling::seed('ContentTag', function ($blueprint) {
  $blueprint->sequence('tag', function ($i) {
    return 'Content tag '. $i;
  });
});

Woodling::seed('ContentType', function ($blueprint) {
  $blueprint->sequence('key', function ($i) {
    return 'type_'. $i;
  });
  $blueprint->sequence('name', function ($i) {
    return 'Content Type '. $i;
  });
});