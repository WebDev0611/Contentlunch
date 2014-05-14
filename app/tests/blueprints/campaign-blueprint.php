<?php

use Woodling\Woodling;

Woodling::seed('Campaign', function ($blueprint) {
  $blueprint->sequence('title', function ($i) {
    return 'Campaign '. $i;
  });
  $blueprint->status = 1;
  $blueprint->start_date = time();
  $blueprint->end_date = strtotime('+1 month');
  $blueprint->is_recurring = false;
  $blueprint->description = 'Description lorem ipsum';
  $blueprint->goals = 'Goals lorem ipsum';
});

Woodling::seed('CampaignType', function ($blueprint) {
  $blueprint->sequence('key', function ($i) {
    return 'type_'. $i;
  });
  $blueprint->sequence('name', function ($i) {
    return 'Campaign Type '. $i;
  });
});