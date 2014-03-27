<?php

use Woodling\Woodling;

Woodling::seed('AccountSubscription', function ($blueprint) {
  $blueprint->licenses = rand(10, 50);
  $blueprint->monthly_price = rand(100, 300);
  $blueprint->annual_discount = 10;
  $blueprint->training = 1;
  $blueprint->features = 'Features!';
  $blueprint->subscription_level = rand(1, 3);
  // Should be set in overrides
  $blueprint->account_id = null;
});
