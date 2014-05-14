<?php

use Woodling\Woodling;

Woodling::seed('Connection', function ($blueprint) {
  $blueprint->sequence('name', function ($i) {
    return 'Connection '. $i;
  });
  $blueprint->sequence('provider', function ($i) {
    return 'provider_'. $i;
  });
  $blueprint->type = 'content';
});

Woodling::seed('AccountConnection', function ($blueprint) {
  $blueprint->sequence('name', function ($i) {
    return 'Connection_'. $i;
  });
  $blueprint->status = 1;
  $blueprint->settings = array(
    'apikey' => 123,
    'url' => 'http://test.net'
  );
});
