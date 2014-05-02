<?php

use Woodling\Woodling;

Woodling::seed('AccountConnection', function ($blueprint) {
  $blueprint->sequence('name', function ($i) {
    return 'Connection_'. $i;
  });
  $blueprint->status = 1;
  $blueprint->type = 'seo';
  $blueprint->provider = 'facebook';
  $blueprint->settings = array(
    'apikey' => 123,
    'url' => 'http://test.net'
  );
  $blueprint->account_id = null;
});